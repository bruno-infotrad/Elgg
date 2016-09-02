<?php
/**
 * Invite users to join a group
 *
 * @package ElggGroups
 */

$user = elgg_get_logged_in_user_entity();
$bid_guid = get_input('guid');
if (! $bid_guid) {
	register_error(elgg_echo("projects:missing_bid_guid"));
	forward(REFERER);
}
$bid = get_entity($bid_guid);
$project = get_entity($bid->container_guid);
if ($project->getOwnerEntity() != $user && ! check_entity_relationship($user->getGUID(), "group_admin", $project->getGUID())) {
	register_error(elgg_echo("projects:not_allowed_to_edit"));
	forward(REFERER);
}
// Update bid object
if (is_int($bid->invitee)) {
//Real Elgg user
	$user_guid = get_entity($bid->invitee)->getGUID();
} else {
//External email
	$user_guid = $bid->invitee;
}
$container_guid = $bid->container_guid;
if ($bid->delete()) {
	//elgg_clear_sticky_form('page');
	system_message(elgg_echo('jobsin:bid:deleted'));
} else {
	register_error(elgg_echo('jobsin:bid:notdeleted'));
	forward(REFERER);
}
//check if other bids exists for user within same group. If not, remove project invitation
//Modify other existing submissions as non selected
$other_bids = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtypes' => 'bid',
			'container_guid' => $container_guid,
			'metadata_name_value_pairs' => array( 'name' => 'invitee', 'value' => $user_guid),
			'count' => true
		));
if (! $other_bids) {
// If join request made
	if (check_entity_relationship($container_guid, 'invited', $user_guid)) {
       		remove_entity_relationship($container_guid, 'invited', $user_guid);
       		system_message(elgg_echo("groups:invitekilled"));
	}
}
forward(REFERER);
