<?php
$bid_guid = get_input('bid_guid');
if (! $bid_guid) {
	register_error(elgg_echo('jobsin:missing_bid_id'));
	forward(REFERER);
}
$bid = get_entity($bid_guid);
if (! $bid->canEdit()) {
	register_error(elgg_echo('noaccess'));
	forward(REFERER);
}
$group_guid = $bid->container_guid;
$group = get_entity($group_guid);
$user_guid = elgg_get_logged_in_user_guid();
if ($group->getOwnerGUID() != $user_guid && ! check_entity_relationship($user_guid, "group_admin", $group_guid)) {
	$readonly = true;
}
$title = elgg_echo('jobsin:edit_bid');
$content = elgg_view_form('projects/edit_bid',array(),array('bid' => $bid, 'readonly' => $readonly));
// build page content
$params = array(
        "content" => $content,
        "title" => $title,
        "filter" => "",
);
$body = elgg_view_layout("content", $params);

// draw page
echo elgg_view_page($title, $body);

