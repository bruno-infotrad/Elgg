<?php
/**
 * Invite users to join a group
 *
 * @package ElggGroups
 */

$logged_in_user = elgg_get_logged_in_user_entity();
$bid_guid = get_input('bid_guid');
if (! $bid_guid) {
	register_error(elgg_echo("projects:missing_bid_guid"));
	forward(REFERER);
}
$bid = get_entity($bid_guid);
$project = get_entity($bid->container_guid);
if ($project->getOwnerEntity() != $logged_in_user && ! check_entity_relationship($logged_in_user->getGUID(), "group_admin", $project->getGUID())) {
	register_error(elgg_echo("projects:not_allowed_to_edit"));
	forward(REFERER);
}
// Update bid object
$bid->status = 'selected';
//Usual save routine
if ($bid->save()) {
	// Update associated task with selected bid owner
	elgg_trigger_event('selected', 'object', $bid);
	$task_guids = $bid->tasks;
	if (is_array($task_guids)) {
		foreach ($task_guids as $task_guid) {
			$task = get_entity($task_guid);
			$task->assigned_to = $bid->invitee;
			$task->status = 2;
			$task->rate = $bid->rate;
			$task->save();
			elgg_trigger_event('assigned', 'object', $task);
			$bid_owner = $bid->getOwnerEntity();
        		$invitee = get_entity($bid->invitee);
			$subject = elgg_echo('bid:selected:notify:subject', array($task->title), $invitee->language);
			$body = elgg_echo('bid:selected:notify:body', array($invitee->name, $task->title, $task->getURL()), $invitee->language);
			$params = [ 'action' => 'selected', 'object' => $bid, ];
			// Notify selected user
			notify_user($invitee->getGUID(), $bid_owner->getGUID(), $subject, $body, $params);
		}
	} else {
		//echo $task_guids.'<br>';
		$task = get_entity($task_guids);
		$task->assigned_to = $bid->invitee;
		$task->status = 2;
		$task->rate = $bid->rate;
		$task->save();
		$bid_owner = $bid->getOwnerEntity();
        	$invitee = get_entity($bid->invitee);
		$subject = elgg_echo('bid:selected:notify:subject', array($task->title), $invitee->language);
		$body = elgg_echo('bid:selected:notify:body', array($invitee->name, $task->title, $task->getURL()), $invitee->language);
		$params = [ 'action' => 'selected', 'object' => $bid, ];
		// Notify selected user
		notify_user($invitee->getGUID(), $bid_owner->getGUID(), $subject, $body, $params);
	}
	system_message(elgg_echo('jobsin:bid:selected'));
	/*
	if ($new_bid) {
	elgg_create_river_item(array( 'view' => 'river/object/page/create', 'action_type' => 'create', 'subject_guid' => elgg_get_logged_in_user_guid(), 'object_guid' => $page->guid,));
	}
	*/
} else {
	register_error(elgg_echo('jobsin:bid:notselected'));
	forward(REFERER);
}
//Add selected user to group
$user = get_entity($bid->invitee);
$group = get_entity($bid->container_guid);
if (projects_join_group($group, $user)) {
	system_message(elgg_echo("projects:addedtoproject",array($user->name)));
} else {
	register_error(elgg_echo("projects:cantadd"));
	forward(REFERER);
}
//Modify other existing submissions as non selected
$other_bids = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtypes' => 'bid',
			'container_guid' => $bid->container_guid,
			'metadata_name_value_pairs' => array(array( 'name' => 'tasks', 'value' => $bid->tasks),array( 'name' => 'invitee', 'value' => $bid->invitee, 'operand' => '<>')),
		));
if ($other_bids) {
	foreach ($other_bids as $other_bid) {
		$other_bid->status = 'notselected';
		$other_bid->save();
		$unselected_user_bids = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtypes' => 'bid',
			'container_guid' => $bid->container_guid,
			'metadata_name_value_pairs' => array(array( 'name' => 'tasks', 'value' => $bid->tasks),array( 'name' => 'invitee', 'value' => $other_bid->invitee)),
			'count' => true
		));
		// If join request made
		if (count($unselected_user_bids) && check_entity_relationship($bid->container_guid, 'invited', $other_bid->invitee)) {
        		remove_entity_relationship($bid->container_guid, 'invited', $other_bid->invitee);
        		system_message(elgg_echo("groups:invitekilled"));
		}
	}
}
forward(REFERER);