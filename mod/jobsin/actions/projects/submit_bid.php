<?php
/**
 * Invite users to join a group
 *
 * @package ElggGroups
 */

$logged_in_user = elgg_get_logged_in_user_entity();
$rate = get_input('rate');
$bid_guid = get_input('bid_guid');
if (! $rate) {
	register_error(elgg_echo("projects:missing_parameter"));
	forward(REFERER);
}
if (! $bid_guid) {
	register_error(elgg_echo("projects:missing_bid_guid"));
	forward(REFERER);
}
$bid = get_entity($bid_guid);
if ($bid->invitee != $logged_in_user->getGUID()) {
	register_error(elgg_echo("projects:not_allowed_to_bid"));
	forward(REFERER);
}
// Update bid object
$bid->rate = $rate;
$bid->status = 'submitted';
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
forward(REFERER);
/*
$url = elgg_normalize_url("projects/bid_invitations/".$bid->getGUID());

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
		
$params = [ 'action' => 'invite', 'object' => $group, ];

// Send notification
$result = notify_user($user->getGUID(), $group->owner_guid, $subject, $body, $params);

if ($result) {
	system_message(elgg_echo("groups:userinvited"));
} else {
	register_error(elgg_echo("groups:usernotinvited"));
}
forward(REFERER);
*/
