<?php
/**
 * Front page handler
 * 
 * @return bool
 */
function jobsin_front_page_handler() {

        if (elgg_is_logged_in()) {
		$user = elgg_get_logged_in_user_entity();
		$session = elgg_get_session();
		//echo var_export($session,true);
		if (elgg_is_admin_logged_in() || $session->get('project_manager')) {
                	forward('projects/owner/'.$user->username);
		} else {
			//First time user
			if (! $user->pf) {
				$user->pf = 1;
				$forward_url = 'profile/'.$user->username.'/edit';
			} else {
				// get membership requests
				$invitations = groups_get_invited_groups($user->getGUID());
				if ($invitations) {
					$forward_url = 'projects/invitations/'.$user->username;
				} else {
					$forward_url = 'tasks/assigned';
				}
			}
                	forward($forward_url);
		}
        }

        $title = elgg_echo('content:latest');
        $content = elgg_list_river();
        if (!$content) {
                $content = elgg_echo('river:none');
        }

        $login_box = elgg_view('core/account/login_box');

        $params = array(
                        'title' => $title,
                        'content' => $content,
                        'sidebar' => $login_box
        );
        $body = elgg_view_layout('one_sidebar', $params);
        echo elgg_view_page(null, $body);
        return true;
}
function jobsin_calendars_page_handler($page) {
        if (! elgg_is_logged_in()) {
                forward('/dashboard');
        } else {
		elgg_load_library('elgg:tasks');
		// add the jquery treeview files for navigation
		elgg_load_js('jquery-treeview');
		elgg_load_css('jquery-treeview');
                if (isset($page[0])&& $page[0] == 'assigned') {
                        $base_dir = elgg_get_plugins_path() . 'jobsin/pages/calendars';
                        include "$base_dir/assigned.php";
                } else {
                        return calendars_page_handler($page);
                }
                return true;
	}
}
function jobsin_tasks_page_handler($page) {
        if (! elgg_is_logged_in()) {
                forward('/dashboard');
        } else {
		elgg_load_library('elgg:tasks');
		// add the jquery treeview files for navigation
		elgg_load_js('jquery-treeview');
		elgg_load_css('jquery-treeview');
                if (isset($page[0])&& $page[0] == 'group' && isset($page[1])&& isset($page[2]) && $page[2] == 'all') {
                        $base_dir = elgg_get_plugins_path() . 'jobsin/pages/tasks';
			set_input('owner_guid', $page[1]);
			elgg_set_page_owner_guid($page[1]);
                        include "$base_dir/owner.php";
                } elseif (isset($page[0])&& $page[0] == 'owner' && isset($page[1])) {
                        $base_dir = elgg_get_plugins_path() . 'jobsin/pages/tasks';
			$owner_guid = get_user_by_username($page[1])->getGUID();
			set_input('owner_guid', $owner_guid);
			elgg_set_page_owner_guid($owner_guid);
                        include "$base_dir/owner.php";
                } elseif (isset($page[0])&& $page[0] == 'assigned') {
                        //elgg_set_context('all_projects');
                        $base_dir = elgg_get_plugins_path() . 'jobsin/pages/tasks';
                        include "$base_dir/assigned.php";
                } elseif (isset($page[0])&& $page[0] == 'all') {
                        //elgg_set_context('all_projects');
                        $base_dir = elgg_get_plugins_path() . 'jobsin/pages/tasks';
                        include "$base_dir/world.php";
                        //return tasks_page_handler($page);
                } else {
                        return tasks_page_handler($page);
                }
                return true;
	}
}


function jobsin_dashboard_handler() {
        require_once elgg_get_plugins_path() . 'jobsin/pages/dashboard.php';
        return true;
}

function tags_autocomplete() {
        require_once elgg_get_plugins_path() . 'jobsin/lib/tags_autocomplete.php';
        return true;
}
function populate_skills_list() {
        require_once elgg_get_plugins_path() . 'jobsin/lib/populate_skills_list.php';
        return true;
}
