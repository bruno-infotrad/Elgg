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
                	forward('tasks');
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

function jobsin_dashboard_handler() {
        require_once elgg_get_plugins_path() . 'jobsin/pages/dashboard.php';
        return true;
}

