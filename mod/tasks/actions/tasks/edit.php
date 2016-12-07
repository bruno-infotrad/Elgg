<?php
/**
 * Create or edit a task
 *
 * @package ElggTasks
 */

$variables = elgg_get_config('tasks');
$input = array();
foreach ($variables as $name => $type) {
	$input[$name] = get_input($name);
	if ($name == 'title') {
		$input[$name] = strip_tags($input[$name]);
	}
	if ($type == 'tags') {
		$input[$name] = string_to_tag_array($input[$name]);
	}
}

// Get guids
$task_guid = (int)get_input('task_guid');
$container_guid = (int)get_input('container_guid');
$parent_guid = (int)get_input('parent_guid');

elgg_make_sticky_form('task');

if (!$input['title']) {
	register_error(elgg_echo('tasks:error:no_title'));
	forward(REFERER);
}

if ($task_guid) {
	$task = get_entity($task_guid);
	$previous_assigned_to = $task->assigned_to;
	if (!$task || !$task->canEdit()) {
		register_error(elgg_echo('tasks:cantedit'));
		forward(REFERER);
	}
	$container_guid = $task->getContainerGUID();
	$new_task = false;
} else {
	$task = new ElggObject();
	if ($parent_guid) {
		$task->subtype = 'task';
	} else {
		$task->subtype = 'task_top';
	}
	$new_task = true;
}
$group = get_entity($container_guid);

if (sizeof($input) > 0) {
	foreach ($input as $name => $value) {
		if ($name == 'start_date'||$name == 'end_date') {
			$date = explode('-',$value);
                	$previous_value = $task->$name;
                	$tmp_value = mktime(0,0,1,$date[1],$date[2],$date[0]);
			//Only group owner and group admins can change date)
			if ($group->canEdit()) {
                		$value = $tmp_value;
				$task->$name = $value; 
			}
		} else {
			$task->$name = $value; 
		}
	}
}

// need to add check to make sure user can write to container
$task->container_guid = $container_guid;

if ($parent_guid) {
	$task->parent_guid = $parent_guid;
}

if ($task->save()) { 

	elgg_clear_sticky_form('task');

	// Now save description as an annotation
	$task->annotate('task', $task->description, $task->access_id);
	// Trigger notifications
	if ($new_task) {
		elgg_trigger_event('create', 'object', $task);
		if ($task->assigned_to) {
			elgg_trigger_event('assigned', 'object', $task);
		}
	} else if ($task->assigned_to && $task->assigned_to != $previous_assigned_to) {
		elgg_trigger_event('assigned', 'object', $task);
	} else if ($task->status == '5') {
		//Task closed, notify owner
		$task_owner = $task->getOwnerEntity();
		$task_url = $task->getURL();
		$submitter = elgg_get_logged_in_user_entity();
		$title = $task->title;
		$subject = elgg_echo('task:closed:notify:subject', array($submitter->name, $title), $task_owner->language);
		$body = elgg_echo('task:closed:notify:body', array($task_owner->name, $submitter->name, $title, $task_url), $task_owner->language);
		$params = [ 'action' => 'closed', 'object' => $task, ];
		notify_user($task_owner->getGUID(), $submitter->getGUID(), $subject, $body, $params);
	}
	system_message(elgg_echo('tasks:saved'));

	if ($new_task) {
		add_to_river('river/object/task/create', 'create', elgg_get_logged_in_user_guid(), $task->guid);
	}

	forward($group->getURL());
} else {
	register_error(elgg_echo('tasks:notsaved'));
	forward(REFERER);
}