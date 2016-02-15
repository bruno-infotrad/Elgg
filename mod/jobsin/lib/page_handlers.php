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
                	forward('tasks/assigned');
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
                } elseif (isset($page[0])&& $page[0] == 'assigned') {
                        $base_dir = elgg_get_plugins_path() . 'jobsin/pages/tasks';
                        include "$base_dir/assigned.php";
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

