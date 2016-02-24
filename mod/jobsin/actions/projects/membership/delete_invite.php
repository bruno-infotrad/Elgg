<?php
/**
 * Delete an invitation to join a project.
 *
 * Does not deal with multiple bids - they will al be deleted
 */

$user_guid = get_input('user_guid', elgg_get_logged_in_user_guid());
$group_guid = get_input('group_guid');

$user = get_user($user_guid);

// invisible groups require overriding access to delete invite
$old_access = elgg_set_ignore_access(true);
$group = get_entity($group_guid);
elgg_set_ignore_access($old_access);

if (!$user && !elgg_instanceof($group, 'group')) {
	forward(REFERER);
}

// If join request made
if (check_entity_relationship($group->guid, 'invited', $user->guid)) {
	remove_entity_relationship($group->guid, 'invited', $user->guid);
	system_message(elgg_echo("groups:invitekilled"));
}
// Delete bids if they exists
$group_bids_for_user = elgg_get_entities_from_metadata(array(
                                'type' => 'object',
                                'subtypes' => 'bid',
                                'container_guid' => $group_guid,
                                'metadata_name_value_pairs' => array( 'name' => 'invitee', 'value' => $user_guid),
                        ));
foreach ($group_bids_for_user as $bid) {
	$bid->delete();
}
forward(REFERER);
