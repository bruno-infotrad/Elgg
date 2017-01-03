<?php
/**
 * Elgg groups plugin
 *
 * @package ElggGroups
 */

$group = elgg_extract("entity", $vars, elgg_get_page_owner_entity());
$bid_guid = elgg_extract("bid_guid", $vars);
$group_guid = $group->getGUID();
$invite_site_members = elgg_extract("invite", $vars, "no");
$invite_email = elgg_extract("invite_email", $vars, "no");;
$invite_csv = elgg_extract("invite_csv", $vars, "no");;
$invite_skills = elgg_extract("invite_skills", $vars, "no");;

$owner = $group->getOwnerEntity();
$forward_url = $group->getURL();

$tabs = false;

$friends = elgg_get_logged_in_user_entity()->getFriends(array("limit" => false));
if (!empty($friends)) {
	$toggle_content = "<span>" . elgg_echo("group_tools:group:invite:friends:select_all") . "</span>";
	$toggle_content .= "<span class='hidden'>" . elgg_echo("group_tools:group:invite:friends:deselect_all") . "</span>";
	
	$friendspicker = elgg_view("output/url", array("text" => $toggle_content, "href" => "javascript:void(0);", "onclick" => "group_tools_toggle_all_friends();", "id" => "friends_toggle", "class" => "float-alt elgg-button elgg-button-action"));
	$friendspicker .= elgg_view('input/friendspicker', array('entities' => $friends, 'name' => 'user_guid', 'highlight' => 'all'));
} else {
	$friendspicker = elgg_echo('groups:nofriendsatall');
}
// which options to show
if (in_array("yes", array($invite_site_members, $invite_email, $invite_csv))) {
	$tabs = array(
		"friends" => array(
			"text" => elgg_echo("friends"),
			"href" => "#",
			"rel" => "friends",
			"priority" => 200,
			"onclick" => "group_tools_group_invite_switch_tab(\"friends\");",
			"selected" => true
		)
	);
	
	// invite friends
	$form_data = "<div id='group_tools_group_invite_friends'>";
	$form_data .= $friendspicker;
	$form_data .= "</div>";
	//invite all site members
	if ($invite_site_members == "yes") {
		$tabs["users"] = array(
			"text" => elgg_echo("group_tools:group:invite:users"),
			"href" => "#",
			"rel" => "users",
			"priority" => 300,
			"onclick" => "group_tools_group_invite_switch_tab(\"users\");",
		);
		$form_data .= "<div id='group_tools_project_invite_users' class='hidden mbm'>";
		$form_data .= "<div>" . elgg_echo("group_tools:group:invite:users:description") . "</div>";
		$form_data .= elgg_view("input/project_invite_autocomplete", array("name" => "user_guid", "id" => "group_tools_group_invite_autocomplete", "group_guid" => $group_guid, "relationship" => "site"));
		if (elgg_is_admin_logged_in()) {
			$form_data .= elgg_view("input/checkbox", array("name" => "all_users", "value" => "yes"));
			$form_data .= elgg_echo("group_tools:group:invite:users:all");
		}
		
		$form_data .= "</div>";
	}
	// invite by email
	if ($invite_email == "yes") {
		$tabs["email"] = array(
			"text" => elgg_echo("group_tools:group:invite:email"),
			"href" => "#",
			"rel" => "users",
			"priority" => 400,
			"onclick" => "group_tools_group_invite_switch_tab(\"email\");"
		);
		
		$form_data .= "<div id='group_tools_group_invite_email' class='hidden mbm'>";
		$form_data .= "<div id='group_tools_group_invite_email_description'>" . elgg_echo("group_tools:group:invite:email:description") . "</div>";
		/*
		$form_data .= elgg_view("input/project_invite_autocomplete", array("name" => "user_guid",
										"id" => "group_tools_group_invite_autocomplete_email",
										"group_guid" => $group->getGUID(),
										"relationship" => "email"));
		*/
		$form_data .= elgg_view("input/text", array("name" => "user_guid_email"));
		$form_data .= "</div>";
	}
	
	// invite by skills
	if ($invite_skills == "yes") {
		$tabs["skills"] = array(
			"text" => elgg_echo("group_tools:group:invite:skills"),
			"href" => "#",
			"rel" => "users",
			"priority" => 400,
			"onclick" => "group_tools_group_invite_switch_tab(\"skills\");"
		);
		
		$form_data .= "<div id='group_tools_group_invite_skills' class='hidden mbm'>";
		$form_data .= "<div id='group_tools_group_invite_skills_description'>" . elgg_echo("group_tools:group:invite:skills:description") . "</div>";
		$form_data .= elgg_view("input/project_invite_autocomplete", array("name" => "user_guid", "id" => "group_tools_group_invite_autocomplete_skills", "group_guid" => $group->getGUID(), "relationship" => "skills"));
		$form_data .= "</div>";
	}
	
	//invite by cvs upload
	if ($invite_csv == "yes") {
		$tabs["csv"] = array(
			"text" => elgg_echo("group_tools:group:invite:csv"),
			"href" => "#",
			"rel" => "users",
			"priority" => 500,
			"onclick" => "group_tools_group_invite_switch_tab(\"csv\");"
		);
		
		$form_data .= "<div id='group_tools_group_invite_csv' class='hidden mbm'>";
		$form_data .= "<div>" . elgg_echo("group_tools:group:invite:csv:description") . "</div>";
		$form_data .= elgg_view("input/file", array("name" => "csv"));
		$form_data .= "</div>";
	}
/*
	if ($invite_site_members == "yes"||$invite_email == "yes"||$invite_csv == "yes") {
		$tasks = elgg_get_entities(array( 'types' => 'object', 'subtypes' => 'task_top', 'container_guid' => $group_guid));
		$options_values[0] = elgg_echo("jobsin:project:invite:notask");
		foreach ($tasks as $task) {
			$options_values[$task->getGUID()] = $task->title;
		}
		$form_data .= "<div id='group_tools_project_bid'>";
		$form_data .= "<div>" . elgg_echo("jobsin:submission:end_date") . "</div>";
		$form_data .= elgg_view("input/date", array("name" => "end_date","maxlength" => 10));
		$form_data .= "</div>";
		$form_data .= "<div id='group_tools_project_tasks'>";
		$form_data .= "<div>" . elgg_echo("jobsin:project:invite:users:task") . "</div>";
		//$form_data .= elgg_view("input/multiselect", array("name" => "task_guid", 'options_values' => $options_values));
		$form_data .= elgg_view("input/select", array("name" => "task_guid", 'options_values' => $options_values));
		$form_data .= "</div>";
	}
*/
} else {
	// only friends
	$form_data = $friendspicker;
}

// optional text
$form_data .= elgg_view_module("aside", elgg_echo("group_tools:group:invite:text"), elgg_view("input/longtext", array("name" => "comment")));

// build tabs
if (!empty($tabs)) {
	foreach ($tabs as $name => $tab) {
		$tab["name"] = $name;
			
		elgg_register_menu_item("filter", $tab);
	}
	echo elgg_view_menu("filter", array("sort_by" => "priority"));
}

// show form
echo $form_data;

// show buttons
echo '<div class="elgg-foot">';
echo elgg_view('input/hidden', array('name' => 'forward_url', 'value' => $forward_url));
echo elgg_view('input/hidden', array('name' => 'group_guid', 'value' => $group_guid));
echo elgg_view('input/hidden', array('name' => 'bid_guid', 'value' => $bid_guid));
echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('invite')));
echo '</div>';
	
?>
<script type="text/javascript">
	function group_tools_group_invite_switch_tab(tab) {
		$('#invite_to_group li').removeClass('elgg-state-selected');

		$('#invite_to_group li.elgg-menu-item-' + tab).addClass('elgg-state-selected');

		switch (tab) {
			case "users":
				$('#group_tools_group_invite_friends').hide();
				$('#group_tools_group_invite_email').hide();
				$('#group_tools_group_invite_csv').hide();
				$('#group_tools_group_invite_skills').hide();
				
				$('#group_tools_project_invite_users').show();
				break;
			case "email":
				$('#group_tools_group_invite_friends').hide();
				$('#group_tools_project_invite_users').hide();
				$('#group_tools_group_invite_csv').hide();
				$('#group_tools_group_invite_skills').hide();
				
				$('#group_tools_group_invite_email').show();
				$('#group_tools_group_invite_email').css('display','inline-block');
				break;
			case "csv":
				$('#group_tools_group_invite_friends').hide();
				$('#group_tools_project_invite_users').hide();
				$('#group_tools_group_invite_email').hide();
				$('#group_tools_group_invite_skills').hide();
				
				$('#group_tools_group_invite_csv').show();
				break;
			case "skills":
				$('#group_tools_group_invite_friends').hide();
				$('#group_tools_project_invite_users').hide();
				$('#group_tools_group_invite_email').hide();
				$('#group_tools_group_invite_csv').hide();
				
				$('#group_tools_group_invite_skills').show();
				break;
			case "friends":
			default:
				$('#group_tools_project_invite_users').hide();
				$('#group_tools_group_invite_email').hide();
				$('#group_tools_group_invite_csv').hide();
				$('#group_tools_group_invite_skills').hide();
				
				$('#group_tools_group_invite_friends').show();
				break;
		}
	}

	function group_tools_toggle_all_friends() {

		if ($('#friends_toggle span:first').is(':visible')) {
			$('#friends-picker1 input[type="checkbox"]').attr("checked", "checked");
		} else {
			$('#friends-picker1 input[type="checkbox"]').removeAttr("checked");
		}

		$('#friends_toggle span').toggle();
	}
	group_tools_group_invite_switch_tab("friends");
</script>