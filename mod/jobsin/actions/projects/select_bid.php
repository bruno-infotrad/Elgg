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
	$task_guids = $group_bid->tasks;
	if (is_array($task_guids)) {
		foreach ($task_guids as $task_guid) {
			$task = get_entity($task_guid);
			$task->assigned_to = $bid->invitee;
			$task->status = 2;
			$task->save();
		}
	} else {
		//echo $task_guids.'<br>';
		$task = get_entity($task_guids);
		$task->assigned_to = $bid->invitee;
		$task->status = 2;
		$task->save();
	}
	system_message(elgg_echo('jobsin:bid:saved'));
	/*
	if ($new_bid) {
	elgg_create_river_item(array( 'view' => 'river/object/page/create', 'action_type' => 'create', 'subject_guid' => elgg_get_logged_in_user_guid(), 'object_guid' => $page->guid,));
	}
	*/
} else {
	register_error(elgg_echo('jobsin:bid:notsaved'));
	forward(REFERER);
}
//Add selected user to group
$user = get_entity($bid->invitee);
$group = get_entity($bid->container_guid);
if (projects_join_group($group, $user)) {
	system_message(elgg_echo("projects:added",array($user->name)));
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
