<?php
/**
 * Join a user to a group, add river event, clean-up invitations
* Derived from groups function
 *
 * @param ElggGroup $group
 * @param ElggUser  $user
 * @return bool
 */
function projects_join_group($group, $user) {

	// access ignore so user can be added to access collection of invisible group
	$ia = elgg_set_ignore_access(TRUE);
	$result = $group->join($user);
	elgg_set_ignore_access($ia);

	if ($result) {
		// flush user's access info so the collection is added
		get_access_list($user->guid, 0, true);

		// Remove any invite or join request flags
		remove_entity_relationship($group->guid, 'invited', $user->guid);
		remove_entity_relationship($user->guid, 'membership_request', $group->guid);
		// cleanup email invitations
		$options = array(
				"annotation_name" => "email_invitation",
				"annotation_value" => group_tools_generate_email_invite_code($group->getGUID(), $user->email).'|'.$user->email,
				"limit" => false
			);
		$ia = elgg_set_ignore_access(true);
		$annotations = elgg_get_annotations($options);
                // restore access
                elgg_set_ignore_access($ia);
		/*
		if (elgg_is_logged_in()) {
			elgg_delete_annotations($options);
		} elseif ($annotations = elgg_get_annotations($options)) {
			group_tools_delete_annotations($annotations);
		} 
		*/
		if ($annotations) {
			group_tools_delete_annotations($annotations);
		} 

		elgg_create_river_item(array(
			'view' => 'river/relationship/member/create',
			'action_type' => 'join',
			'subject_guid' => $user->guid,
			'object_guid' => $group->guid,
		));

		return true;
	}

	return false;
}
function projects_invite_user(ElggGroup $group, ElggUser $user, $text = "", $resend = false, $task_guids = null, $end_date = null) {
	$result = false;
	
	$loggedin_user = elgg_get_logged_in_user_entity();
	
	if (!empty($user) && ($user instanceof ElggUser) && !empty($group) && ($group instanceof ElggGroup) && !empty($loggedin_user)) {
		// Create relationship
		$relationship = add_entity_relationship($group->getGUID(), "invited", $user->getGUID());
		
		if ($task_guids && $end_date && $relationship) {
			$bid = create_task_bid($user,$group->getGUID(),$end_date,$task_guids);
		}
		if ($relationship || $resend) {
			// Send email
			if ($task_guids) {
				if (is_array($task_guids)) {
					foreach ($task_guids as $task_guid) {
						$task = get_entity($task_guid);
						$title = ($title ? $title.','.$task->title :$task->title);
					}
				} else {
					$task = get_entity($task_guids);
					$title = $task->title;
				}
				$url = elgg_normalize_url("projects/bid_invitations/".$bid->getGUID());
				$subject = elgg_echo("project:invite:bid:subject", array($task->title));
				$msg = elgg_echo("project:invite:bid:body", array( $user->name, $loggedin_user->name, $title, $group->name, $text, $url));
			} else {
				$url = elgg_normalize_url("projects/invitations/".$user->username);
				$subject = elgg_echo("groups:invite:subject", array( $user->name, $group->name));
				$msg = elgg_echo("group_tools:groups:invite:body", array( $user->name, $loggedin_user->name, $group->name, $text, $url));
			}
			
			
			if (notify_user($user->getGUID(), $group->getOwnerGUID(), $subject, $msg, array(), "email")) {
				$result = true;
			}
		}
	}
	
	return $result;
}
function projects_transfer_bid_to_user(ElggGroup $group, ElggUser $user, $text = "", $bid_guid = null) {
	$result = false;
	$loggedin_user = elgg_get_logged_in_user_entity();
	if (!empty($user) && ($user instanceof ElggUser) && !empty($group) && ($group instanceof ElggGroup) && !empty($loggedin_user) && $bid_guid) {
		// Create relationship
		$relationship = add_entity_relationship($group->getGUID(), "invited", $user->getGUID());
		elgg_log("relationship=$relationship",'NOTICE');
		
		if ($relationship && $bid_guid) {
			$bid = transfer_task_bid($user,$group->getGUID(), $bid_guid);
			elgg_log("New bid=".var_export($bid,true),'NOTICE');
		}
		if ($relationship || $resend) {
			// Send email
			$task_guids = get_entity($bid_guid)->tasks;
			$task_owner = get_entity(get_entity($bid_guid)->owner_guid);
			if ($task_guids) {
				// Need to give temporary read access to task metadata
				$ia = elgg_set_ignore_access(TRUE);
				if (is_array($task_guids)) {
					foreach ($task_guids as $task_guid) {
						$task = get_entity($task_guid);
						$title = ($title ? $title.','.$task->title :$task->title);
					}
				} else {
					$task = get_entity($task_guids);
					$title = $task->title;
				}
				elgg_set_ignore_access($ia);
				$url = elgg_normalize_url("projects/bid_invitations/".$bid->getGUID());
				$subject = elgg_echo("project:transfer:bid:subject", array($task->title));
				$msg = elgg_echo("project:transfer:bid:body", array($user->name, $loggedin_user->name, $task_owner->name, $title, $group->name, $text, $url));
				//Notify transfer recipient as well as bid originator
				if (notify_user($user->getGUID(), $group->getOwnerGUID(), $subject, $msg, array(), "email")) {
					$result0 = true;
				}
				$url = elgg_normalize_url("projects/bid_submissions/".$bid->getGUID());
				$subject = elgg_echo("project:transfer:bid:subject:task_owner", array($task->title, $user->name, $loggedin_user->name));
				$msg = elgg_echo("project:transfer:bid:body:task_owner", array($task_owner->name, $loggedin_user->name, $title, $group->name, $user->name, $text, $url));
				if ($result0 && notify_user($task_owner->getGUID(), $group->getOwnerGUID(), $subject, $msg, array(), "email")) {
					$result = true;
				}
			}
			
			
		}
	}
	return $result;
}

function projects_invite_email(ElggGroup $group, $email, $text = "", $resend = false, $task_guids = null, $end_date =  null) {
	$result = false;
	
	$loggedin_user = elgg_get_logged_in_user_entity();
	if (!empty($group) && ($group instanceof ElggGroup) && !empty($email) && is_email_address($email) && !empty($loggedin_user)) {
		// generate invite code
		$invite_code = group_tools_generate_email_invite_code($group->getGUID(), $email);
		
		if (!empty($invite_code)) {
			$found_group = group_tools_check_group_email_invitation($invite_code, $group->getGUID());
			if (empty($found_group) && $task_guids && $end_date) {
				$bid = create_task_bid($email,$group->getGUID(),$end_date,$task_guids);
			}
			if (empty($found_group) || $resend) {
				// make site email
				$site = elgg_get_site_entity();
				if (!empty($site->email)) {
					if (!empty($site->name)) {
						$site_from = $site->name . " <" . $site->email . ">";
					} else {
						$site_from = $site->email;
					}
				} else {
					// no site email, so make one up
					if (!empty($site->name)) {
						$site_from = $site->name . " <noreply@" . $site->getDomain() . ">";
					} else {
						$site_from = "noreply@" . $site->getDomain();
					}
				}
				
				if (empty($found_group)) {
					// register invite with group
					$group->annotate("email_invitation", $invite_code . "|" . $email, ACCESS_LOGGED_IN, $group->getGUID());
				}
				
				// make subject
				$subject = elgg_echo("group_tools:groups:invite:email:subject", array($group->name));
				
				// make body
				$body = elgg_echo("group_tools:groups:invite:email:body", array(
					$loggedin_user->name,
					$group->name,
					$site->name,
					$text,
					$site->name,
					elgg_get_site_url() . "register?group_invitecode=" . $invite_code,
					elgg_get_site_url() . "groups/invitations/?invitecode=" . $invite_code,
					$invite_code
				));
				
				$params = array(
					"group" => $group,
					"inviter" => $loggedin_user,
					"invitee" => $email
				);
				$body = elgg_trigger_plugin_hook("invite_notification", "group_tools", $params, $body);
				
				$result = elgg_send_email($site_from, $email, $subject, $body);
			} else {
				$result = null;
			}
		}
	}
	
	return $result;
}

function projects_transfer_bid_via_email(ElggGroup $group, $email, $text = "", $bid_guid = null) {
	$result = false;
	$loggedin_user = elgg_get_logged_in_user_entity();
	if (!empty($group) && ($group instanceof ElggGroup) && !empty($email) && is_email_address($email) && !empty($loggedin_user)) {
		// generate invite code
		$invite_code = group_tools_generate_email_invite_code($group->getGUID(), $email);
		
		if (!empty($invite_code)) {
			$found_group = group_tools_check_group_email_invitation($invite_code, $group->getGUID());
			if (empty($found_group) && $bid_guid) {
				$task_guids = get_entity($bid_guid)->tasks;
				$task_owner = get_entity(get_entity($bid_guid)->owner_guid);
				$bid = transfer_task_bid($email,$group->getGUID(), $bid_guid);
				// Need to give temporary read access to task metadata
				$ia = elgg_set_ignore_access(TRUE);
				if (is_array($task_guids)) {
					foreach ($task_guids as $task_guid) {
						$task = get_entity($task_guid);
						$title = ($title ? $title.','.$task->title :$task->title);
					}
				} else {
					$task = get_entity($task_guids);
					$title = $task->title;
				}
				elgg_set_ignore_access($ia);
				$url = elgg_normalize_url("projects/bid_invitations/".$bid->getGUID());
				$subject = elgg_echo("project:transfer:bid:subject", array($task->title));
				$body = elgg_echo("project:transfer:bid:body", array($email, $loggedin_user->name, $task_owner->name, $title, $group->name, $text, $url));
				// make site email
				$site = elgg_get_site_entity();
				if (!empty($site->email)) {
					if (!empty($site->name)) {
						$site_from = $site->name . " <" . $site->email . ">";
					} else {
						$site_from = $site->email;
					}
				} else {
					// no site email, so make one up
					if (!empty($site->name)) {
						$site_from = $site->name . " <noreply@" . $site->getDomain() . ">";
					} else {
						$site_from = "noreply@" . $site->getDomain();
					}
				}
				
				// register invite with group
				$group->annotate("email_invitation", $invite_code . "|" . $email, ACCESS_LOGGED_IN, $group->getGUID());
				//Notify transfer recipient as well as bid originator
				$result0 = elgg_send_email($site_from, $email, $subject, $body);
				$url = elgg_normalize_url("projects/bid_submissions/".$bid->getGUID());
				$subject = elgg_echo("project:transfer:bid:subject:task_owner", array($task->title, $email, $loggedin_user->name));
				$msg = elgg_echo("project:transfer:bid:body:task_owner", array($task_owner->name, $loggedin_user->name, $title, $group->name, $email, $text, $url));
				if ($result0 && notify_user($task_owner->getGUID(), $group->getOwnerGUID(), $subject, $msg, array(), "email")) {
					$result = true;
				}
			} else {
				$result = null;
			}
		}
	}
	return $result;
}

function create_task_bid($bidder,$group_guid,$end_date,$task_guids) {
	$bid = new ElggObject();
	$bid->subtype = 'bid';
	$bid->inviter = elgg_get_logged_in_user_guid();
	// Provision for bids sent to external (non registered) users
	if ($bidder instanceof ElggUser) {
		$bid->invitee = $bidder->guid;
	} else {
		$bid->invitee = $bidder;
	}
	$bid->container_guid = $group_guid;
	$bid->end_date = $end_date;
	$bid->tasks = $task_guids;
	$bid->status = 'pending';
	$bid->transfernum = 0;
	$bid->access_id = ACCESS_LOGGED_IN;
	//Maybe have to create access collection to allow invitee to view/edit
	if ($bid->save()) {
		//elgg_clear_sticky_form('page');
		//system_message(elgg_echo('jobsin:bid:saved'));
		return $bid;
		/*
		if ($new_bid) {
			elgg_create_river_item(array( 'view' => 'river/object/page/create', 'action_type' => 'create', 'subject_guid' => elgg_get_logged_in_user_guid(), 'object_guid' => $page->guid,));
		}
		*/
	} else {
		//register_error(elgg_echo('jobsin:bid:notsaved'));
		//forward(REFERER);
		return false;
	}
}
function transfer_task_bid($bidder,$group_guid,$bid_guid) {
	$bid_to_transfer = get_entity($bid_guid);
	$bid_owner_guid = $bid_to_transfer->owner_guid;
	$bid_task_guid = $bid_to_transfer->tasks;
	$bid = new ElggObject();
	$bid->owner_guid = $bid_owner_guid;
	$bid->subtype = 'bid';
	$bid->inviter = $bid_owner_guid;
	$bid->transferrer = elgg_get_logged_in_user_guid();
	// Provision for bids sent to external (non registered) users
	if ($bidder instanceof ElggUser) {
		$bid->invitee = $bidder->guid;
	} else {
		$bid->invitee = $bidder;
	}
	$bid->container_guid = $group_guid;
	$bid->end_date = $bid_to_transfer->end_date;
	$bid->tasks = $bid_task_guid;
	$bid->status = 'pending';
	$bid->transfernum = 1;
	$bid->access_id = ACCESS_LOGGED_IN;
	//Maybe have to create access collection to allow invitee to view/edit
	//Usual save routine but need to ignore access control for now
	elgg_register_plugin_hook_handler("permissions_check", "all", "projects_permissions_check_bid_transfer");
	if ($bid->save()) {
		//elgg_clear_sticky_form('page');
		//system_message(elgg_echo('jobsin:bid:saved'));
		elgg_unregister_plugin_hook_handler("permissions_check", "all", "projects_permissions_check_bid_transfer");
		return $bid;
		/*
		if ($new_bid) {
			elgg_create_river_item(array( 'view' => 'river/object/page/create', 'action_type' => 'create', 'subject_guid' => elgg_get_logged_in_user_guid(), 'object_guid' => $page->guid,));
		}
		*/
	} else {
		//register_error(elgg_echo('jobsin:bid:notsaved'));
		//forward(REFERER);
		elgg_unregister_plugin_hook_handler("permissions_check", "all", "projects_permissions_check_bid_transfer");
		return false;
	}
}

function jobsin_handle_project_page() {

	// all groups doesn't get link to self
	elgg_pop_breadcrumb();
	elgg_push_breadcrumb(elgg_echo('groups'));

	if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
		elgg_register_title_button();
	}

	$selected_tab = get_input('filter', 'newest');

	switch ($selected_tab) {
		case 'popular':
			$content = elgg_list_entities_from_relationship_count(array(
				'type' => 'group',
				'relationship' => 'member',
				'inverse_relationship' => false,
				'full_view' => false,
			));
			if (!$content) {
				$content = elgg_echo('groups:none');
			}
			break;
		case 'discussion':
			$content = elgg_list_entities(array(
				'type' => 'object',
				'subtype' => 'groupforumtopic',
				'order_by' => 'e.last_action desc',
				'limit' => 40,
				'full_view' => false,
			));
			if (!$content) {
				$content = elgg_echo('discussion:none');
			}
			break;
		case 'newest':
		default:
			$content = elgg_list_entities(array(
				'type' => 'group',
				'full_view' => false,
			));
			if (!$content) {
				$content = elgg_echo('groups:none');
			}
			break;
	}

	$filter = elgg_view('groups/group_sort_menu', array('selected' => $selected_tab));
	
	$sidebar = elgg_view('groups/sidebar/find');
	$sidebar .= elgg_view('groups/sidebar/featured');

	$params = array(
		'content' => $content,
		'sidebar' => $sidebar,
		'filter' => $filter,
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page(elgg_echo('groups:all'), $body);
}

/*
function groups_handle_all_page() {

	// all groups doesn't get link to self
	elgg_pop_breadcrumb();
	elgg_push_breadcrumb(elgg_echo('groups'));

	if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
		elgg_register_title_button();
	}

	$selected_tab = get_input('filter', 'newest');

	switch ($selected_tab) {
		case 'popular':
			$content = elgg_list_entities_from_relationship_count(array(
				'type' => 'group',
				'relationship' => 'member',
				'inverse_relationship' => false,
				'full_view' => false,
			));
			if (!$content) {
				$content = elgg_echo('groups:none');
			}
			break;
		case 'discussion':
			$content = elgg_list_entities(array(
				'type' => 'object',
				'subtype' => 'groupforumtopic',
				'order_by' => 'e.last_action desc',
				'limit' => 40,
				'full_view' => false,
			));
			if (!$content) {
				$content = elgg_echo('discussion:none');
			}
			break;
		case 'newest':
		default:
			$content = elgg_list_entities(array(
				'type' => 'group',
				'full_view' => false,
			));
			if (!$content) {
				$content = elgg_echo('groups:none');
			}
			break;
	}

	$filter = elgg_view('groups/group_sort_menu', array('selected' => $selected_tab));
	
	$sidebar = elgg_view('groups/sidebar/find');
	$sidebar .= elgg_view('groups/sidebar/featured');

	$params = array(
		'content' => $content,
		'sidebar' => $sidebar,
		'filter' => $filter,
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page(elgg_echo('groups:all'), $body);
}

function groups_search_page() {
	elgg_push_breadcrumb(elgg_echo('search'));

	$tag = get_input("tag");
	$title = elgg_echo('groups:search:title', array($tag));

	// groups plugin saves tags as "interests" - see groups_fields_setup() in start.php
	$params = array(
		'metadata_name' => 'interests',
		'metadata_value' => $tag,
		'type' => 'group',
		'full_view' => FALSE,
	);
	$content = elgg_list_entities_from_metadata($params);
	if (!$content) {
		$content = elgg_echo('groups:search:none');
	}

	$sidebar = elgg_view('groups/sidebar/find');
	$sidebar .= elgg_view('groups/sidebar/featured');

	$params = array(
		'content' => $content,
		'sidebar' => $sidebar,
		'filter' => false,
		'title' => $title,
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

function groups_handle_owned_page() {

	$page_owner = elgg_get_page_owner_entity();

	if ($page_owner->guid == elgg_get_logged_in_user_guid()) {
		$title = elgg_echo('groups:owned');
	} else {
		$title = elgg_echo('groups:owned:user', array($page_owner->name));
	}
	elgg_push_breadcrumb($title);

	elgg_register_title_button();

	$content = elgg_list_entities(array(
		'type' => 'group',
		'owner_guid' => elgg_get_page_owner_guid(),
		'full_view' => false,
	));
	if (!$content) {
		$content = elgg_echo('groups:none');
	}

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

function groups_handle_mine_page() {

	$page_owner = elgg_get_page_owner_entity();

	if ($page_owner->guid == elgg_get_logged_in_user_guid()) {
		$title = elgg_echo('groups:yours');
	} else {
		$title = elgg_echo('groups:user', array($page_owner->name));
	}
	elgg_push_breadcrumb($title);

	elgg_register_title_button();

	$content = elgg_list_entities_from_relationship(array(
		'type' => 'group',
		'relationship' => 'member',
		'relationship_guid' => elgg_get_page_owner_guid(),
		'inverse_relationship' => false,
		'full_view' => false,
	));
	if (!$content) {
		$content = elgg_echo('groups:none');
	}

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

function groups_handle_edit_page($page, $guid = 0) {
	gatekeeper();
	
	if ($page == 'add') {
		elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
		$title = elgg_echo('groups:add');
		elgg_push_breadcrumb($title);
		if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
			$content = elgg_view('groups/edit');
		} else {
			$content = elgg_echo('groups:cantcreate');
		}
	} else {
		$title = elgg_echo("groups:edit");
		$group = get_entity($guid);

		if ($group && $group->canEdit()) {
			elgg_set_page_owner_guid($group->getGUID());
			elgg_push_breadcrumb($group->name, $group->getURL());
			elgg_push_breadcrumb($title);
			$content = elgg_view("groups/edit", array('entity' => $group));
		} else {
			$content = elgg_echo('groups:noaccess');
		}
	}
	
	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

function groups_handle_invitations_page() {
	gatekeeper();

	$user = elgg_get_page_owner_entity();

	$title = elgg_echo('groups:invitations');
	elgg_push_breadcrumb($title);

	// @todo temporary workaround for exts #287.
	$invitations = groups_get_invited_groups(elgg_get_logged_in_user_guid());
	$content = elgg_view('groups/invitationrequests', array('invitations' => $invitations));

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

function groups_handle_profile_page($guid) {
	elgg_set_page_owner_guid($guid);

	// turn this into a core function
	global $autofeed;
	$autofeed = true;

	elgg_push_context('group_profile');

	$group = get_entity($guid);
	if (!$group) {
		forward('groups/all');
	}

	elgg_push_breadcrumb($group->name);

	groups_register_profile_buttons($group);

	$content = elgg_view('groups/profile/layout', array('entity' => $group));
	$sidebar = '';

	if (group_gatekeeper(false)) {	
		if (elgg_is_active_plugin('search')) {
			$sidebar .= elgg_view('groups/sidebar/search', array('entity' => $group));
		}
		//$sidebar .= elgg_view('groups/sidebar/members', array('entity' => $group));

		$subscribed = false;
		if (elgg_is_active_plugin('notifications')) {
			global $NOTIFICATION_HANDLERS;
			
			foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
				$relationship = check_entity_relationship(elgg_get_logged_in_user_guid(),
						'notify' . $method, $guid);
				
				if ($relationship) {
					$subscribed = true;
					break;
				}
			}
		}
		
		$sidebar .= elgg_view('groups/sidebar/my_status', array(
			'entity' => $group,
			'subscribed' => $subscribed
		));
	}

	$params = array(
		'content' => $content,
		'sidebar' => $sidebar,
		'title' => $group->name,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($group->name, $body);
}

function groups_handle_activity_page($guid) {

	elgg_set_page_owner_guid($guid);

	$group = get_entity($guid);
	if (!$group || !elgg_instanceof($group, 'group')) {
		forward();
	}

	group_gatekeeper();

	$title = elgg_echo('groups:activity');

	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb($title);

	$db_prefix = elgg_get_config('dbprefix');

	$content = elgg_list_river(array(
		'joins' => array("JOIN {$db_prefix}entities e ON e.guid = rv.object_guid"),
		'wheres' => array("e.container_guid = $guid")
	));
	if (!$content) {
		$content = '<p>' . elgg_echo('groups:activity:none') . '</p>';
	}
	
	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

function groups_handle_members_page($guid) {

	elgg_set_page_owner_guid($guid);

	$group = get_entity($guid);
	if (!$group || !elgg_instanceof($group, 'group')) {
		forward();
	}

	group_gatekeeper();

	$title = elgg_echo('groups:members:title', array($group->name));

	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb(elgg_echo('groups:members'));

	$content = elgg_list_entities_from_relationship(array(
		'relationship' => 'member',
		'relationship_guid' => $group->guid,
		'inverse_relationship' => true,
		'type' => 'user',
		'limit' => 20,
	));

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

function groups_handle_invite_page($guid) {
	gatekeeper();

	elgg_set_page_owner_guid($guid);

	$group = get_entity($guid);

	$title = elgg_echo('groups:invite:title');

	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb(elgg_echo('groups:invite'));

	if ($group && $group->canEdit()) {
		$content = elgg_view_form('groups/invite', array(
			'id' => 'invite_to_group',
			'class' => 'elgg-form-alt mtm',
		), array(
			'entity' => $group,
		));
	} else {
		$content .= elgg_echo('groups:noaccess');
	}

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

function groups_handle_requests_page($guid) {

	gatekeeper();

	elgg_set_page_owner_guid($guid);

	$group = get_entity($guid);

	$title = elgg_echo('groups:membershiprequests');

	if ($group && $group->canEdit()) {
		elgg_push_breadcrumb($group->name, $group->getURL());
		elgg_push_breadcrumb($title);
		
		$requests = elgg_get_entities_from_relationship(array(
			'type' => 'user',
			'relationship' => 'membership_request',
			'relationship_guid' => $guid,
			'inverse_relationship' => true,
			'limit' => 0,
		));
		$content = elgg_view('groups/membershiprequests', array(
			'requests' => $requests,
			'entity' => $group,
		));

	} else {
		$content = elgg_echo("groups:noaccess");
	}

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

function groups_register_profile_buttons($group) {

	$actions = array();

	// group owners
	if ($group->canEdit()) {
		// edit and invite
		$url = elgg_get_site_url() . "groups/edit/{$group->getGUID()}";
		$actions[$url] = 'groups:edit';
		$url = elgg_get_site_url() . "groups/invite/{$group->getGUID()}";
		$actions[$url] = 'groups:invite';
	}

	// group members
	if ($group->isMember(elgg_get_logged_in_user_entity())) {
		if ($group->getOwnerGUID() != elgg_get_logged_in_user_guid()) {
			// leave
			$url = elgg_get_site_url() . "action/groups/leave?group_guid={$group->getGUID()}";
			$url = elgg_add_action_tokens_to_url($url);
			$actions[$url] = 'groups:leave';
		}
	} elseif (elgg_is_logged_in()) {
		// join - admins can always join.
		$url = elgg_get_site_url() . "action/groups/join?group_guid={$group->getGUID()}";
		$url = elgg_add_action_tokens_to_url($url);
		if ($group->isPublicMembership() || $group->canEdit()) {
			$actions[$url] = 'groups:join';
		} else {
			// request membership
			$actions[$url] = 'groups:joinrequest';
		}
	}

	if ($actions) {
		foreach ($actions as $url => $text) {
			elgg_register_menu_item('title', array(
				'name' => $text,
				'href' => $url,
				'text' => elgg_echo($text),
				'link_class' => 'elgg-button elgg-button-action',
			));
		}
	}
}

function groups_prepare_form_vars($group = null) {
	$values = array(
		'name' => '',
		'membership' => ACCESS_PUBLIC,
		'vis' => ACCESS_PUBLIC,
		'guid' => null,
		'entity' => null
	);

	// handle customizable profile fields
	$fields = elgg_get_config('group');

	if ($fields) {
		foreach ($fields as $name => $type) {
			$values[$name] = '';
		}
	}

	// handle tool options
	$tools = elgg_get_config('group_tool_options');
	if ($tools) {
		foreach ($tools as $group_option) {
			$option_name = $group_option->name . "_enable";
			$values[$option_name] = $group_option->default_on ? 'yes' : 'no';
		}
	}

	// get current group settings
	if ($group) {
		foreach (array_keys($values) as $field) {
			if (isset($group->$field)) {
				$values[$field] = $group->$field;
			}
		}

		if ($group->access_id != ACCESS_PUBLIC && $group->access_id != ACCESS_LOGGED_IN) {
			// group only access - this is done to handle access not created when group is created
			$values['vis'] = ACCESS_PRIVATE;
		} else {
			$values['vis'] = $group->access_id;
		}

		$values['entity'] = $group;
	}

	// get any sticky form settings
	if (elgg_is_sticky_form('groups')) {
		$sticky_values = elgg_get_sticky_values('groups');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('groups');

	return $values;
}
*/
function is_project_manager($user) {
	if (! roles_has_role($user,'pm_admin') && ! elgg_is_admin_user($user->guid)) {
		elgg_log($user->name.' IS NOT PROJECT_MANAGER','NOTICE');
		return FALSE;
	} else {
		elgg_log($user->name.' IS PROJECT_MANAGER','NOTICE');
		return TRUE;
	}
}
