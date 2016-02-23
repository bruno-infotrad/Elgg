<?php
gatekeeper();

$user_guid = get_input('user_guid');
$group_guid = get_input('group_guid');
if (! $user_guid || ! $group_guid) {
	forward();
}
$user= get_entity($user_guid);
$group= get_entity($group_guid);
// build breadcrumb
// build breadcrumb
elgg_push_breadcrumb(elgg_echo("groups"), "projects/all");

$title = elgg_echo("projects:bid_invitations");
elgg_push_breadcrumb($title);

$group_bids_for_user = elgg_get_entities_from_metadata(array(
				'type' => 'object',
				'subtypes' => 'bid',
				'container_guid' => $group_guid,
				'metadata_name_value_pairs' => array( 'name' => 'invitee', 'value' => $user_guid),
			));
if ($group_bids_for_user) {
	$content = elgg_view("projects/bid_invitations", array(
	        "user" => $user,
	        "group" => $group,
	        "group_bids" => $group_bids_for_user,
	));
} else {
	$content = elgg_echo('jobsin:project:no_bid_for_user');
}

// build page content
$params = array(
	"content" => $content,
	"title" => $title,
	"filter" => "",
);
$body = elgg_view_layout("content", $params);

// draw page
echo elgg_view_page($title, $body);
