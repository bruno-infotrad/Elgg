<?php
function jobsin_tasks_entity_menu_setup($hook, $type, $return, $params) {

	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'tasks') {
		return $return;
	}

	// remove delete if not owner or admin
	if (!elgg_is_admin_logged_in() && elgg_get_logged_in_user_guid() != $entity->getOwnerGuid()) {
		foreach ($return as $index => $item) {
			if ($item->getName() == 'delete') {
				unset($return[$index]);
			}
		}
	}
	return $return;
}
function jobsin_tasks_write_permission_check($hook, $entity_type, $returnvalue, $params) {
        if (!tasks_is_task($params['entity'])) {
                return null;
        }
        $entity = $params['entity'];
        /* @var ElggObject $entity */

        $write_permission = $entity->write_access_id;
        $user = $params['user'];

        if ($write_permission && $user) {
                switch ($write_permission) {
                        case ACCESS_PRIVATE:
                                // Elgg's default decision is what we want
                                return null;
                                break;
                        case ACCESS_FRIENDS:
                                $owner = $entity->getOwnerEntity();
                                if (($owner instanceof ElggUser) && $owner->isFriendsWith($user->guid)) {
                                        return true;
                                }
                                break;
                        default:
                                $list = get_access_array($user->guid);
                                if (in_array($write_permission, $list)) {
                                        // user in the access collection
					if ($entity instanceof ElggEntity && (($entity->getSubtype() == 'task_top') || ($entity->getSubtype() == 'task'))) {
						if ($entity->assigned_to == elgg_get_logged_in_user_guid()) {
                                        		return true;
						}
					}
                                }
                                break;
                }
        }
}

function roles_pm_admins_config($hook_name, $entity_type, $return_value, $params) {

	$roles = array(

		'pm_admin' => array(
			'title' => 'roles_pm_admin:role:title',
			'permissions' => array(
				'actions' => array(
				),
				'menus' => array(
				),
				'views' => array(
				),
				'hooks' => array(
				),

			),
		),
		ADMIN_ROLE => array(
			'title' => 'roles:role:ADMIN_ROLE',
			'extends' => array(),
			'permissions' => array(
				'actions' => array(
				),
				'menus' => array(
				),
				'views' => array(
					'forms/account/settings' => array(
						'rule' => 'extend',
						'view_extension' => array(
							'view' => 'roles/settings/account/role',
							'priority' => 150
						)
					),
				),
				'hooks' => array(
					'usersettings:save::user' => array(
						'rule' => 'extend',
						'hook' => array(
							'handler' => 'roles_user_settings_save',
							'priority' => 500,
						)
					),
					'register::menu:user_hover' => array(
						'rule' => 'extend',
						'hook' => array(
							'handler' => 'pm_admin_user_menu_setup',
							'priority' => 500,
						)
					,)
				),
			),
		),		
	);

	if (!is_array($return_value)) {
		return $roles;
	} else {
		return array_merge($return_value, $roles);
	}
}
function pm_admin_user_menu_setup($hook, $type, $return, $params) {

        // Make sure we have a logged-in user, who is not an admin
        $user = $params['entity'];
        if (!elgg_instanceof($user, 'user') || $user->isAdmin()) {
                return $return;
        }

        $role = roles_get_role($user);
        if ($role->name == 'pm_admin') {
                $action = 'revoke_pm_admin';
        } else {
                $action = 'make_pm_admin';
        }

        $url = "action/roles_pm_admin/$action?guid={$user->guid}";
        $url = elgg_add_action_tokens_to_url($url);
        $item = new ElggMenuItem($action, elgg_echo("roles_pm_admin:action:$action"), $url);
        $item->setSection('admin');
        $item->setLinkClass('data-confirm');
        $return[] = $item;

        return $return;
}
function pm_admin_can_edit_hook($hook, $type, $return_value, $params){
	$result = $return_value;
	if(!empty($params) && is_array($params) && !$result){
		if(array_key_exists("entity", $params) && array_key_exists("user", $params)){
			$entity = $params["entity"];
			$user = $params["user"];
			if(($entity instanceof ElggGroup) && ($user instanceof ElggUser)){
				$session = elgg_get_session();
				if((roles_has_role($user, "pm_admin") && $session->get('project_manager'))||elgg_is_admin_logged_in()){
					$result = true;
				}
			}
		}
	}
	return $result;
}

function jobsin_route_groups_handler($hook, $type, $return_value, $params){
	/**
	 * $return_value contains:
	 * $return_value['handler'] => requested handler
	 * $return_value['segments'] => url parts ($page)
	 */
	$result = $return_value;
	
	if(!empty($return_value) && is_array($return_value)){
		$page = $return_value['segments'];
		
		switch($page[0]){
			case "all":
				$filter = get_input("filter");
				
				if(empty($filter) && ($default_filter = elgg_get_plugin_setting("group_listing", "group_tools"))){
					$filter = $default_filter;
					set_input("filter", $default_filter);
				} elseif(empty($filter)) {
					$filter = "newest";
					set_input("filter", $filter);
				}
				
				if(in_array($filter, array("open", "closed", "alpha", "ordered", "suggested"))){
					// we will handle the output
					$result = false;
					
					include(dirname(dirname(__FILE__)) . "/pages/groups/all.php");
				}
				
				break;
			case "suggested":
				$result = false;
				
				include(dirname(dirname(__FILE__)) . "/pages/groups/suggested.php");
				break;
			case "requests":
				$result = false;
				
				set_input("group_guid", $page[1]);
				
				include(dirname(dirname(__FILE__)) . "/pages/groups/membershipreq.php");
				break;
			case "invite":
				$result = false;
				
				set_input("group_guid", $page[1]);
				
				include(dirname(dirname(__FILE__)) . "/pages/groups/invite.php");
				break;
			case "mail":
				$result = false;
				
				set_input("group_guid", $page[1]);
					
				include(dirname(dirname(__FILE__)) . "/pages/mail.php");
				break;
			case "group_invite_autocomplete":
				$result = false;
				
				include(dirname(dirname(__FILE__)) . "/procedures/group_invite_autocomplete.php");
				break;
			case "add":
				if(group_tools_is_group_creation_limited()){
					admin_gatekeeper();
				}
				break;
			case "invitations":
				$result = false;
				if(isset($page[1])){
					set_input("username", $page[1]);
				}
				
				include(dirname(dirname(__FILE__)) . "/pages/groups/invitations.php");
				break;
			default:
				// check if we have an old group profile link
				if(isset($page[0]) && is_numeric($page[0])) {
					if(($group = get_entity($page[0])) && elgg_instanceof($group, "group", null, "ElggGroup")){
						register_error(elgg_echo("changebookmark"));
						forward($group->getURL());
					}
				}
				break;
		}
	}
	
	return $result;
}
