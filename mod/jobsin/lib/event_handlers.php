<?php
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

			$count = elgg_get_entities_from_relationship(array(
				'type' => 'object',
				'subtypes' => 'bid',
				'container_guid' => $page_owner->getGUID(),
				'count' => true,
			));

			//if ($count) {
				$text = elgg_echo('projects:bid_submissions:pending', array($count));
			//} else {
				//$text = elgg_echo('projects:membershiprequests');
			//}

			elgg_register_menu_item('page', array(
				'name' => 'bid_submissions',
				'text' => $text,
				'href' => $url,
				'priority' => 200,
			));
		}
	}
	if (elgg_get_context() == 'groups' && !elgg_instanceof($page_owner, 'group')) {
		elgg_register_menu_item('page', array(
			'name' => 'groups:all',
			'text' => elgg_echo('groups:all'),
			'href' => 'projects/all',
		));

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
			}
			$url = "projects/invitations/$user->username";
			$invitation_count = groups_get_invited_groups($user->getGUID(), false, array('count' => true));

			if ($invitation_count) {
				$text = elgg_echo('groups:invitations:pending', array($invitation_count));
			} else {
				$text = elgg_echo('groups:invitations');
			}

			$item = new ElggMenuItem('groups:user:invites', $text, $url);
			elgg_register_menu_item('page', $item);
		}
	}
}
