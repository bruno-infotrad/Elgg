<?php
/**
 * Invite users to join a group
 *
 * @package ElggGroups
 */

$logged_in_user = elgg_get_logged_in_user_entity();

$user_guids = get_input('user_guid');
if (!is_array($user_guids)) {
	$user_guids = array($user_guids);
}
$group_guid = get_input('group_guid');
$group = get_entity($group_guid);
$end_date = get_input('end_date');
$task_guids = get_input('task_guid');
if ($task_guids && !is_array($task_guids)) {
	$task_guids = array($task_guids);
}
if ($end_date) {
	$date = explode('-',$end_date);
	$end_date = mktime(0,0,1,$date[1],$date[2],$date[0]);
}

if (count($user_guids) > 0 && elgg_instanceof($group, 'group') && $group->canEdit()) {
	foreach ($user_guids as $guid) {
		$user = get_user($guid);
		if (!$user) {
			continue;
		}

		if (check_entity_relationship($group->guid, 'invited', $user->guid)) {
			register_error(elgg_echo("groups:useralreadyinvited"));
			continue;
		}

		if (check_entity_relationship($user->guid, 'member', $group->guid)) {
			// @todo add error message
			continue;
		}

		// Create bid object if task selected only
		if ($task_guids) {
			$bid = new ElggObject();
                	$bid->subtype = 'bid';
                	$bid->inviter = elgg_get_logged_in_user_guid();
                	$bid->invitee = $user->guid;
                	$bid->container_guid = $group_guid;
                	$bid->end_date = $end_date;
                	$bid->tasks = $task_guids;
                	$bid->status = 'pending';
                	$bid->access_id = ACCESS_LOGGED_IN;
			//Maybe have to create access collection to allow invitee to view/edit
			//Usual save routine
			if ($bid->save()) {
				//elgg_clear_sticky_form('page');
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
		}
		// Create relationship
		add_entity_relationship($group->guid, 'invited', $user->guid);

		
		if ($task_guids) {
			$url = elgg_normalize_url("projects/bid_invitations/".$bid->getGUID());
		} else {
			$url = elgg_normalize_url("projects/invitations/".$user->username);
		}

		$subject = elgg_echo('groups:invite:subject', array(
			$user->name,
			$group->name
		), $user->language);

		$body = elgg_echo('groups:invite:body', array(
			$user->name,
			$logged_in_user->name,
			$group->name,
			$url,
		), $user->language);
		
		$params = [
			'action' => 'invite',
			'object' => $group,
		];

		// Send notification
		$result = notify_user($user->getGUID(), $group->owner_guid, $subject, $body, $params);

		if ($result) {
			system_message(elgg_echo("groups:userinvited"));
		} else {
			register_error(elgg_echo("groups:usernotinvited"));
		}
	}
}

forward(REFERER);
