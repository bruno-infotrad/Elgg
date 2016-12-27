<?php
/**
* A user"s group invitations
*
* @uses $vars["invitations"] Array of ElggGroups
*/

$user = elgg_extract("user", $vars);
$invitations = elgg_extract("invitations", $vars);
$email_invites = elgg_extract("email_invitations", $vars, false);

if ((!empty($invitations) && is_array($invitations)) || (!empty($email_invites) && is_array($email_invites))) {
	
	echo "<ul class='elgg-list mbm'>";
	
	// normal invites
	if (!empty($invitations)) {
		foreach ($invitations as $group) {
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
						//'count' => true
        			        ));
				if ($group_bids_for_user) {
					//echo var_export($group_bids_for_user,true);
					//echo 'status='.$group_bids_for_user[0]->status;
					//echo 'invitee='.$group_bids_for_user[0]->invitee;
					$task_guids = $group_bids_for_user[0]->tasks;
					//Need to temporarily disable access control to get the info
					$ia = elgg_set_ignore_access(true);
					$task = get_entity($task_guids);
					$body = "<div class='task'>";
					$body .= "<h4>$task->title</h4>";
					$body .= '<div class="task-dates">';
					$body .= elgg_echo('tasks:start_date'). " : " .elgg_view('output/text',array('value' => date(TASKS_FORMAT_DATE_EVENTDAY, $task->start_date))).'<br/>';
					$body .= elgg_echo('tasks:end_date'). " : &nbsp;&nbsp;" .elgg_view('output/text',array('value' => date(TASKS_FORMAT_DATE_EVENTDAY, $task->end_date))).'<br/>';
					$body .= elgg_echo('tasks:duration'). " : &nbsp;&nbsp;" .elgg_view('output/text',array('value' => $task->duration)).'<br/>';
					$body .= elgg_echo('tasks:suggested_rate'). " : &nbsp;&nbsp;" .elgg_view('output/text',array('value' => $task->rate));
					$body .= '</div>';
					$body .= '</div>';
					elgg_set_ignore_access($ia);
					$body .= "<div class='task-in-project'>";
					$body .= elgg_echo('jobsin:in_project_bid');
					$body .= '</div>';
					if ($group_bids_for_user[0]->status == "submitted") {
						$submit_text = elgg_echo("jobsin:view_bid");
					} else {
						$submit_text = elgg_echo("jobsin:submit_bid");
					}
					$url = "projects/bid_invitations?user_guid=" . $user->getGUID() . "&group_guid=" . $group->getGUID();
					$accept_button = elgg_view("output/url", array(
						"href" => $url,
						"text" => $submit_text,
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
				$url = "action/projects/killinvitation?user_guid=" . $user->getGUID() . "&group_guid=" . $group->getGUID();
				$delete_button = elgg_view("output/url", array(
					"href" => $url,
					"confirm" => elgg_echo("groups:invite:remove:check"),
					"text" => elgg_echo("delete"),
					"class" => "elgg-button elgg-button-delete mlm",
				));

				$body .= "<div class='task'>";
				$body .= "<h4>$group_title</h4>";
				$body .= "<p class='elgg-subtext'>$group->briefdescription</p>";
	
				$alt = $accept_button . $delete_button;
	
				echo "<li class='pvs'>";
				echo elgg_view_image_block($icon, $body, array("image_alt" => $alt));
				echo "</li>";
                $body = "</div>";
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
		



			$group_bids_for_user = elgg_get_entities_from_metadata(array(
        		                'type' => 'object',
        		                'subtypes' => 'bid',
        		                'container_guid' => $group->getGUID(),
        		                'metadata_name_value_pairs' => array( 'name' => 'invitee', 'value' => $user->getGUID()),
					//'count' => true
        		        ));
			if ($group_bids_for_user) {
				//echo var_export($group_bids_for_user,true);
				//echo 'status='.$group_bids_for_user[0]->status;
				//echo 'invitee='.$group_bids_for_user[0]->invitee;
				if ($group_bids_for_user[0]->status == "submitted") {
					$submit_text = elgg_echo("jobsin:view_bid");
				} else {
					$submit_text = elgg_echo("jobsin:submit_bid");
				}
				$url = "projects/bid_invitations?user_guid=" . $user->getGUID() . "&group_guid=" . $group->getGUID();
				$accept_button = elgg_view("output/url", array(
					"href" => $url,
					"text" => $submit_text,
					"class" => "elgg-button elgg-button-submit",
					"is_trusted" => true,
				));
			} else {
				$url = "action/groups/email_invitation?invitecode=" . group_tools_generate_email_invite_code($group->getGUID(), $user->email);
				$accept_button = elgg_view("output/url", array(
					"href" => $url,
					"text" => elgg_echo("accept"),
					"class" => "elgg-button elgg-button-submit",
					"is_trusted" => true,
					"is_action" => true
				));
			}
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
			
			$url = "action/projects/killrequest?user_guid=" . $user->getGUID() . "&group_guid=" . $group->getGUID();
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
