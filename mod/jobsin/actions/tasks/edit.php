<?php
/**
 * Create or edit a task
 *
 * @package ElggTasks
 */


// Get guids
$task_guid = (int)get_input('task_guid');
$container_guid = (int)get_input('container_guid');
$parent_guid = (int)get_input('parent_guid');

elgg_make_sticky_form('task');
if ($task_guid) {
	$task = get_entity($task_guid);
	if (!$task || !$task->canEdit()) {
		register_error(elgg_echo('tasks:cantedit'));
		forward(REFERER);
	}
	$variables = elgg_get_config('tasks');
	$input = array();
	foreach ($variables as $name => $type) {
		//if ($input[$name] = get_input($name)) {
		if (is_null(get_input($name))===false) {
			$input[$name] = get_input($name);
			if ($name == 'title') {
				$input[$name] = strip_tags($input[$name]);
			}
			if ($type == 'tags') {
				$input[$name] = string_to_tag_array($input[$name]);
			}
		} else {
			$input[$name] = $task->$name;
		}
	}
	//elgg_log('INPUT='.var_export($input,true),'NOTICE');
	$previous_assigned_to = $task->assigned_to;
	$container_guid = $task->getContainerGUID();
	$new_task = false;
	$group = get_entity($container_guid);
	foreach ($input as $name => $value) {
		if ($name == 'start_date'||$name == 'end_date') {
			$date = explode('-',$value);
        		$previous_value = $task->$name;
        		$tmp_value = mktime(0,0,1,$date[1],$date[2],$date[0]);
			if ($value != $tmp_value) {
				//Only group owner and group admins can change date)
				// no error message because GUI does not allow mod anyway
				if ($group->canEdit()) {
        				$value = $tmp_value;
					$task->$name = $value; 
				}
			}
		} else {
			//Protect special fields from POST hacks
			if ($name == 'task_type'||$name == 'status'||$name == 'assigned_to'||$name == 'access_id'||$name == 'write_access_id'||$name == 'container_guid'||$name == 'parent_guid') {
				if ($group->canEdit()) {
					$task->$name = $value; 
				}
			} else {
				$task->$name = $value; 
			}
		}
	}
	// need to add check to make sure user can write to container
	if ($parent_guid) {
		$task->parent_guid = $parent_guid;
	}
} else {
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
	if (!$input['title']) {
		register_error(elgg_echo('tasks:error:no_title'));
		forward(REFERER);
	}

	$task = new ElggObject();
	if ($parent_guid) {
		$task->subtype = 'task';
	} else {
		$task->subtype = 'task_top';
	}
	$new_task = true;
	$group = get_entity($container_guid);

	if (sizeof($input) > 0) {
		foreach ($input as $name => $value) {
			if ($name == 'start_date'||$name == 'end_date') {
				$date = explode('-',$value);
                		$tmp_value = mktime(0,0,1,$date[1],$date[2],$date[0]);
                		$value = $tmp_value;
			}
			$task->$name = $value; 
		}
	}
	// need to add check to make sure user can write to container
	$task->container_guid = $container_guid;
	if ($parent_guid) {
		$task->parent_guid = $parent_guid;
	}
}
//elgg_log('TASK='.var_export($task,true),'NOTICE');
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
