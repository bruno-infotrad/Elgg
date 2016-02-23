<?php
gatekeeper();

$group_guid = get_input('group_guid');
if (! $group_guid) {
	forward();
}
$group= get_entity($group_guid);
// build breadcrumb
// build breadcrumb
elgg_push_breadcrumb(elgg_echo("groups"), "projects/all");

$title = elgg_echo("projects:bid_invitations");
elgg_push_breadcrumb($title);

$group_bids = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtypes' => 'bid',
			'container_guid' => $group_guid,
		));
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
