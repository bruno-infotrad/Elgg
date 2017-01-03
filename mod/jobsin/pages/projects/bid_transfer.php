<?php
/**
 * Invite users to groups
 *
 * @package ElggGroups
 */

gatekeeper();

$guid = (int) get_input("group_guid");
$bid_guid = (int) get_input("bid_guid");
$group = get_entity($guid);

if (!empty($group) && ($group instanceof ElggGroup)) {
	elgg_set_page_owner_guid($guid);
	// get plugin settings
	//$invite = elgg_get_plugin_setting("invite", "group_tools");
	$invite = "no";
	$invite_email = elgg_get_plugin_setting("invite_email", "group_tools");
	//$invite_csv = elgg_get_plugin_setting("invite_csv", "group_tools");
	$invite_csv = "no";
	//$invite_skills = elgg_get_plugin_setting("invite_skills", "group_tools");
	$invite_skills = "no";
		
	if (in_array("yes", array($invite, $invite_csv, $invite_email))) {
		$title = elgg_echo("jobsin:bid_transfer:title");
		$breadcrumb = elgg_echo("jobsin:transfer_bid");
	}
	
	elgg_push_breadcrumb(elgg_echo("groups"), "projects/all");
	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb($breadcrumb);

	$content = elgg_view_form("projects/transfer_bid", array(
		"id" => "invite_to_group",
		"class" => "elgg-form-alt mtm",
		"enctype" => "multipart/form-data"
	), array(
		"entity" => $group,
		"invite" => $invite,
		"invite_email" => $invite_email,
		"invite_csv" => $invite_csv,
		"invite_skills" => $invite_skills,
		"bid_guid" => $bid_guid
	));
	
	$params = array(
		"content" => $content,
		"title" => $title,
		"filter" => "",
	);
	$body = elgg_view_layout("content", $params);

	echo elgg_view_page($title, $body);
} else {
	register_error(elgg_echo("groups:noaccess"));
	forward(REFERER);
}
