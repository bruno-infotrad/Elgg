<?php
gatekeeper();

$user_guid = elgg_get_logged_in_user_guid();
$group_guid = get_input('group_guid');
if (! $group_guid) {
	forward();
}
$group= get_entity($group_guid);
// build breadcrumb
// build breadcrumb
elgg_push_breadcrumb(elgg_echo("projects"), "projects/all");
elgg_push_breadcrumb($group->name, $group->getURL());

$title = elgg_echo("projects:bid_submissions");
elgg_push_breadcrumb($title);
if ($group->getOwnerGUID() == $user_guid || check_entity_relationship($user_guid, "group_admin", $group_guid)) {
	$group_bids = elgg_get_entities_from_metadata(array(
				'type' => 'object',
				'subtypes' => 'bid',
				'container_guid' => $group_guid,
			));
} else {
	$group_bids = elgg_get_entities_from_metadata(array(
				'type' => 'object',
				'subtypes' => 'bid',
				'container_guid' => $group_guid,
				'metadata_name_value_pairs' => array( 'name' => 'invitee', 'value' => $user_guid),
			));
}
if ($group_bids) {
	$content = elgg_view("projects/bid_submissions", array(
	        "group" => $group,
	        "group_bids" => $group_bids,
	));
} else {
	$content = elgg_echo('jobsin:project:no_bids');
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
