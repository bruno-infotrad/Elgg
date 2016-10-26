<?php
/**
 * Event when the user joins a site, mostly when registering (derived from group_tools)
 *
 * @param string           $event        create
 * @param string           $type         member_of_site
 * @param ElggRelationship $relationship the membership relation
 *
 * @return void
 */
function projects_join_site_handler($event, $type, $relationship) {
	
	if (!empty($relationship) && ($relationship instanceof ElggRelationship)) {
		$user_guid = $relationship->guid_one;
		$site_guid = $relationship->guid_two;
		
		$user = get_user($user_guid);
		if (!empty($user)) {
			// ignore access
			$ia = elgg_set_ignore_access(true);
			
			// add user to the auto join groups
			$auto_joins = elgg_get_plugin_setting("auto_join", "group_tools");
			if (!empty($auto_joins)) {
				$auto_joins = string_to_tag_array($auto_joins);
				
				foreach ($auto_joins as $group_guid) {
					$group = get_entity($group_guid);
					if (!empty($group) && ($group instanceof ElggGroup)) {
						if ($group->site_guid == $site_guid) {
							// join the group
							$group->join($user);
						}
					}
				}
			}
			
			// auto detect email invited groups
			$groups = group_tools_get_invited_groups_by_email($user->email, $site_guid);
			if (!empty($groups)) {
				foreach ($groups as $group) {
					// Switch email address with user guid when user creates account
					$group_bids_for_user = elgg_get_entities_from_metadata(array(
									'type' => 'object',
									'subtypes' => 'bid',
									'container_guid' => $group->getGUID(),
									'metadata_name_value_pairs' => array( 'name' => 'invitee', 'value' => $user->email),
								));
					if ($group_bids_for_user) {
						foreach ($group_bids_for_user as $group_bid) {
							$group_bid->deleteMetadata('invitee');
							$group_bid->setMetadata('invitee', $user_guid,'integer');
							$group_bid->save();
						}
					}
				}
			}
			
			// check for manual email invited groups
			$group_invitecode = get_input("group_invitecode");
			if (!empty($group_invitecode)) {
				$group = group_tools_check_group_email_invitation($group_invitecode);
				if (!empty($group)) {
					// Switch email address with user guid when user creates account
					$group_bids_for_user = elgg_get_entities_from_metadata(array(
									'type' => 'object',
									'subtypes' => 'bid',
									'container_guid' => $group->getGUID(),
									'metadata_name_value_pairs' => array( 'name' => 'invitee', 'value' => $user->email),
								));
					if ($group_bids_for_user) {
						foreach ($group_bids_for_user as $group_bid) {
							$group_bid->deleteMetadata('invitee');
							$group_bid->setMetadata('invitee', $user_guid,'integer');
							$group_bid->save();
						}
					}
					
					// cleanup the invite code
					$group_invitecode = sanitise_string($group_invitecode);
					
					$options = array(
						"guid" => $group->getGUID(),
						"annotation_name" => "email_invitation",
						"wheres" => array("(v.string = '" . $group_invitecode . "' OR v.string LIKE '" . $group_invitecode . "|%')"),
						"annotation_owner_guid" => $group->getGUID(),
						"limit" => 1
					);
					
					// ignore access in order to cleanup the invitation
					// Don't clean up yet
					/*
					$ia = elgg_set_ignore_access(true);
					
					elgg_delete_annotations($options);
					
					// restore access
					elgg_set_ignore_access($ia);
					*/
				}
			}
			
			// find domain based groups
			$groups = group_tools_get_domain_based_groups($user, $site_guid);
			if (!empty($groups)) {
				foreach ($groups as $group) {
					// join the group
					$group->join($user);
				}
			}
			
			// restore access settings
			elgg_set_ignore_access($ia);
		}
	}
}
function projects_setup_sidebar_menus() {

	// Get the page owner entity
	$page_owner = elgg_get_page_owner_entity();

	if (elgg_in_context('group_profile')) {
		if (!elgg_instanceof($page_owner, 'group')) {
			forward('', '404');
		}

		if (elgg_is_logged_in() && $page_owner->canEdit() && !$page_owner->isPublicMembership()) {
			$url = elgg_get_site_url() . "projects/requests/{$page_owner->getGUID()}/invites";

			$count = elgg_get_entities_from_relationship(array(
				'type' => 'user',
				'relationship' => 'membership_request',
				'relationship_guid' => $page_owner->getGUID(),
				'inverse_relationship' => true,
				'count' => true,
			));

			if ($count) {
				$text = elgg_echo('projects:membershiprequests:pending', array($count));
			} else {
				$text = elgg_echo('projects:membershiprequests');
			}

			elgg_register_menu_item('page', array(
				'name' => 'membership_requests',
				'text' => $text,
				'href' => $url,
				'priority' => 10,
			));
			//Bid submission page
			$url = elgg_get_site_url() . "projects/bid_submissions/{$page_owner->getGUID()}";

			$count = elgg_get_entities_from_metadata(array(
                                'type' => 'object',
                                'subtypes' => 'bid',
				'container_guid' => $page_owner->getGUID(),
				'metadata_name_value_pairs' => array(array( 'name' => 'status', 'value' => 'selected', 'operand' => '<>'),array( 'name' => 'status', 'value' => 'notselected', 'operand' => '<>')),
				'count' => true,
                        ));
			/*
			$count = elgg_get_entities_from_relationship(array(
				'type' => 'object',
				'subtypes' => 'bid',
				'container_guid' => $page_owner->getGUID(),
				'count' => true,
			));
			*/

			if ($count) {
				$text = elgg_echo('projects:bid_submissions:pending')."<div id='elgg-count'> ($count)</div>";
			} else {
				$text = elgg_echo('projects:bid_submissions:pending');
			}

			elgg_register_menu_item('page', array(
				'name' => 'bid_submissions',
				'text' => $text,
				'href' => $url,
				'priority' => 200,
			));
		}
	}
	if (elgg_get_context() == 'projects' && !elgg_instanceof($page_owner, 'group')) {
		if (elgg_is_admin_logged_in()) {
			elgg_register_menu_item('page', array( 'name' => 'groups:all', 'text' => elgg_echo('groups:all'), 'href' => 'projects/all',));
		}

		$user = elgg_get_logged_in_user_entity();
		if ($user) {
			$session = elgg_get_session();
			if ($session->get('project_manager')) {
				$url =  "projects/owner/$user->username";
				$item = new ElggMenuItem('groups:owned', elgg_echo('groups:owned'), $url);
				elgg_register_menu_item('page', $item);
			} else {
				$url = "projects/member/$user->username";
				$item = new ElggMenuItem('groups:member', elgg_echo('groups:yours'), $url);
				elgg_register_menu_item('page', $item);
			$url = "projects/invitations/$user->username";
			$invitation_count = groups_get_invited_groups($user->getGUID(), false, array('count' => true));
			//Get email invitations
			$options = array(
				"selects" => array("SUBSTRING_INDEX(v.string, '|', -1) AS invited_email"),
				"annotation_name" => "email_invitation",
				//"annotation_owner_guid" => $group->getGUID(),
				"wheres" => array("(v.string LIKE '%|".$user->email."')"),
				"count" => true,
			);
			
			$email_invitation_count = elgg_get_annotations($options);
			$invitation_count += $email_invitation_count;

			if ($invitation_count) {
				$text = elgg_echo('groups:invitations')."<div id='elgg-count'> ($invitation_count)</div>";
			} else {
				$text = elgg_echo('groups:invitations');
			}

			$item = new ElggMenuItem('groups:user:invites', $text, $url);
			elgg_register_menu_item('page', $item);
			}
		}
	}
}
