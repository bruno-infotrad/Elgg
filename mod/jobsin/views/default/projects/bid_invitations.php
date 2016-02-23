<?php
/**
* A user"s group invitations
*
* @uses $vars["invitations"] Array of ElggGroups
*/

$user = elgg_extract("user", $vars);
$group = elgg_extract("group", $vars);
$group_bids = elgg_extract("group_bids", $vars);
// Need this because user is not in group yet
$ia = elgg_set_ignore_access(true);
echo "<ul class='elgg-list mbm'>";
foreach ($group_bids as $group_bid) {
	//echo var_export($group_bid,true).'<br>';
	
	$body .= "<li class='pvs'>";
	$group_title = elgg_view("output/url", array( "href" => $group->getURL(), "text" => $group->name, "is_trusted" => true,));
	$body .= "<h4>$group_title</h4>";
	$body .= "<p class='elgg-subtext'>$group->briefdescription</p>";
	$task_guids = $group_bid->tasks;
	//echo $task_guids.'<br>';
	$task = get_entity($task_guids);
	//echo var_export($task,true).'<br>';
	$body .= '<div class="task-description">'.$task->description.'</div>';
	$body .= '<div class="task-dates">';
	$body .= elgg_echo('tasks:start_date'). " : " .elgg_view('output/text',array('value' => date(TASKS_FORMAT_DATE_EVENTDAY, $task->start_date))).'<br/>';
	$body .= elgg_echo('tasks:end_date'). " : &nbsp;&nbsp;" .elgg_view('output/text',array('value' => date(TASKS_FORMAT_DATE_EVENTDAY, $task->end_date)));
	$body .= '</div>';
	$body .= '<div class="task-rate">';
	$body .= elgg_view_form('projects/submit_bid',array(),array('rate' => $group_bid->rate, 'bid_guid' => $group_bid->getGUID()));
	$body .= '</div>';
	//$body .= elgg_echo('jobsin:task_rate');
	//$body .= '$'.elgg_view('input/text',array('name' => 'rate'));
	//$body .= elgg_view('input/hidden',array('bid_guid' => $group_bid->getGUID()));
	$body .= '</li>';
}
elgg_set_ignore_access($ia);
echo $body;
/*
			if ($group instanceof ElggGroup) {
				$icon = elgg_view_entity_icon($group, "tiny", array("use_hover" => "true"));
	
				$group_title = elgg_view("output/url", array(
					"href" => $group->getURL(),
					"text" => $group->name,
					"is_trusted" => true,
				));
	
				$group_bids_for_user = elgg_get_entities_from_metadata(array(
        			                'type' => 'object',
        			                'subtypes' => 'bid',
        			                'container_guid' => $group->getGUID(),
        			                'metadata_name_value_pairs' => array( 'name' => 'invitee', 'value' => $user->getGUID()),
						'count' => true
        			        ));
				if ($group_bids_for_user) {
					$url = "projects/bid_invitation?user_guid=" . $user->getGUID() . "&group_guid=" . $group->getGUID();
					$accept_button = elgg_view("output/url", array(
						"href" => $url,
						"text" => elgg_echo("jobsin:submit_bid"),
						"class" => "elgg-button elgg-button-submit",
						"is_trusted" => true,
					));
				} else {
					$url = "action/groups/join?user_guid=" . $user->getGUID() . "&group_guid=" . $group->getGUID();
					$accept_button = elgg_view("output/url", array(
						"href" => $url,
						"text" => elgg_echo("accept"),
						"class" => "elgg-button elgg-button-submit",
						"is_trusted" => true,
						"is_action" => true
					));
				}
				$url = "action/groups/killinvitation?user_guid=" . $user->getGUID() . "&group_guid=" . $group->getGUID();
				$delete_button = elgg_view("output/url", array(
					"href" => $url,
					"confirm" => elgg_echo("groups:invite:remove:check"),
					"text" => elgg_echo("delete"),
					"class" => "elgg-button elgg-button-delete mlm",
				));
	
				$body = "<h4>$group_title</h4>";
				$body .= "<p class='elgg-subtext'>$group->briefdescription</p>";
	
				$alt = $accept_button . $delete_button;
	
				echo "<li class='pvs'>";
				echo elgg_view_image_block($icon, $body, array("image_alt" => $alt));
				echo "</li>";
			}
		}
	}
	
	// auto detected email invitations
	if (!empty($email_invites)) {
		foreach ($email_invites as $group) {
			$icon = elgg_view_entity_icon($group, "tiny", array("use_hover" => "true"));
		
			$group_title = elgg_view("output/url", array(
				"href" => $group->getURL(),
				"text" => $group->name,
				"is_trusted" => true,
			));
		
			$url = "action/groups/email_invitation?invitecode=" . group_tools_generate_email_invite_code($group->getGUID(), $user->email);
			$accept_button = elgg_view("output/url", array(
				"href" => $url,
				"text" => elgg_echo("accept"),
				"class" => "elgg-button elgg-button-submit",
				"is_trusted" => true,
				"is_action" => true
			));
			
			$url = "action/groups/decline_email_invitation?invitecode=" . group_tools_generate_email_invite_code($group->getGUID(), $user->email);
			$delete_button = elgg_view("output/url", array(
				"href" => $url,
				"confirm" => elgg_echo("groups:invite:remove:check"),
				"text" => elgg_echo("delete"),
				"class" => "elgg-button elgg-button-delete mlm",
			));
		
			$body = "<h4>$group_title</h4>";
			$body .= "<p class='elgg-subtext'>$group->briefdescription</p>";
		
			$alt = $accept_button . $delete_button;
		
			echo "<li class='pvs'>";
			echo elgg_view_image_block($icon, $body, array("image_alt" => $alt));
			echo "</li>";
		}
	}
	
	echo "</ul>";
} else {
	echo "<p class='mtm'>" . elgg_echo("groups:invitations:none") . "</p>";
}

// list membership requests
if (elgg_get_context() == "groups") {
	// get requests
	$requests = elgg_extract("requests", $vars);
	
	$title = elgg_echo("group_tools:group:invitations:request");
	
	if (!empty($requests) && is_array($requests)) {
		$content = "<ul class='elgg-list'>";
		
		foreach ($requests as $group) {
			$icon = elgg_view_entity_icon($group, "tiny", array("use_hover" => "true"));
			
			$group_title = elgg_view("output/url", array(
				"href" => $group->getURL(),
				"text" => $group->name,
				"is_trusted" => true,
			));
			
			$url = "action/groups/killrequest?user_guid=" . $user->getGUID() . "&group_guid=" . $group->getGUID();
			$delete_button = elgg_view("output/url", array(
				"href" => $url,
				"confirm" => elgg_echo("group_tools:group:invitations:request:revoke:confirm"),
				"text" => elgg_echo("group_tools:revoke"),
				"class" => "elgg-button elgg-button-delete mlm",
			));
			
			$body = "<h4>$group_title</h4>";
			$body .= "<p class='elgg-subtext'>$group->briefdescription</p>";
			
			$alt = $delete_button;
			
			$content .= "<li class='pvs'>";
			$content .= elgg_view_image_block($icon, $body, array("image_alt" => $alt));
			$content .= "</li>";
		}
		
		$content .= "</ul>";
	} else {
		$content = elgg_echo("group_tools:group:invitations:request:non_found");
	}
	
	echo elgg_view_module("info", $title, $content);
	
	// show e-mail invitation form
	if (elgg_extract("invite_email", $vars, false)) {
		// make the form for the email invitations
		$form_body = "<div>" . elgg_echo("group_tools:groups:invitation:code:description") . "</div>";
		$form_body .= elgg_view("input/text", array(
			"name" => "invitecode", 
			"value" => get_input("invitecode"), 
			"class" => "mbm"
		));
	
		$form_body .= "<div>";
		$form_body .= elgg_view("input/submit", array("value" => elgg_echo("submit")));
		$form_body .= "</div>";
		
		$form = elgg_view("input/form", array(
			"body" => $form_body,
			"action" => "action/groups/email_invitation"
		));
	
		echo elgg_view_module("info", elgg_echo("group_tools:groups:invitation:code:title"), $form);
	}
}
*/
