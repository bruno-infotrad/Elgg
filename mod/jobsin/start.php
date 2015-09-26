<?php
elgg_register_event_handler('init','system','basic_init');
 
function basic_init() {

	require_once 'lib/hook_handlers.php';
	require_once 'lib/page_handlers.php';
	elgg_register_library('elgg:jobsin', elgg_get_plugins_path() . 'jobsin/lib/jobsin.php');
	$action_path = dirname(__FILE__) . '/actions';
	elgg_register_plugin_hook_handler('roles:config', 'role', 'roles_pm_admins_config', 600);
	elgg_register_plugin_hook_handler("permissions_check", "group", "pm_admin_can_edit_hook");
	elgg_register_action("roles_pm_admin/make_pm_admin", "$action_path/roles_pm_admin/make_pm_admin.php");
	elgg_register_action("roles_pm_admin/revoke_pm_admin", "$action_path/roles_pm_admin/revoke_pm_admin.php");
	elgg_register_action("jobsin/admin/settings", "$action_path/settings.php", 'admin');
	elgg_register_action("jobsin/admin/sidebar", "$action_path/settings.php", 'admin');
	elgg_register_action("login", "$action_path/login.php",'public');
		
	$plugin = elgg_get_plugin_from_id('jobsin');
	
	if ($plugin->show_thewire == 'yes'){
		elgg_register_action("jobsin/add", "$action_path/add.php");		
		elgg_extend_view('js/elgg', 'js/jobsin/update');
	}
	
	elgg_register_event_handler('pagesetup', 'system', 'basic_pagesetup_handler', 1000);
	elgg_unregister_page_handler('', 'elgg_front_page_handler');
	elgg_register_page_handler('', 'jobsin_front_page_handler');
	elgg_register_page_handler('jobsin', 'jobsin_page_handler');

	elgg_register_admin_menu_item('configure', 'jobsin', 'settings');
		
	elgg_extend_view('css/elgg', 'jobsin/css');
	elgg_extend_view('css/admin', 'jobsin/admin');
	
	elgg_unregister_js('elgg.friendspicker');

	if (elgg_is_logged_in()	&& elgg_get_context() == 'activity'){
			
		if ($plugin->show_thewire == 'yes'){	
			elgg_extend_view('page/layouts/content/header', 'page/elements/riverwire', 1);
		}
		if ($plugin->show_icon != 'no'){
			elgg_extend_view('page/elements/' . $plugin->show_icon, 'page/elements/rivericon', '501');
		}
		if ($plugin->show_menu != 'no'){		
			elgg_extend_view('page/elements/' . $plugin->show_menu, 'page/elements/ownermenu', '502');
		}
	}
	if ((elgg_get_context() == 'activity') || (elgg_get_context() == 'thewire')){
		if ($plugin->show_custom != 'no'){
			elgg_extend_view('page/elements/' . $plugin->show_custom, 'page/elements/custom_module', 504);
		}
	}
	
	themes_register_themes();

	$theme = elgg_get_plugin_setting('active_theme', 'jobsin');
	
	if ($theme != 'default' && elgg_get_context() != 'admin'){
		elgg_load_css($theme);
	}

}

function themes_register_themes() {
	$themes = array('default', 'bronco', 'palesky');

	foreach($themes as $theme) {
		elgg_register_simplecache_view("css/themes/$theme");
		$url = elgg_get_simplecache_url('css', "themes/$theme");
		elgg_register_css($theme, $url);
	}
}

function basic_pagesetup_handler() {

	elgg_unextend_view('page/elements/header', 'search/header');

	elgg_unregister_menu_item('topbar', 'dashboard');
	elgg_unregister_menu_item('topbar', 'elgg_logo');
	
	if (elgg_is_logged_in()) {	
		$user = elgg_get_logged_in_user_entity();
		
		if (elgg_is_active_plugin('dashboard')) {
			elgg_register_menu_item('topbar', array(
				'name' => 'dashboard',
				'href' => 'dashboard',
				'text' => elgg_view_icon('home') . elgg_echo('dashboard'),
				'priority' => 1000,
				'section' => 'alt',
			));
		}
		if (elgg_is_active_plugin('reportedcontent')) {
			elgg_unregister_menu_item('footer', 'report_this');
		
			$href = "javascript:elgg.forward('reportedcontent/add'";
			$href .= "+'?address='+encodeURIComponent(location.href)";
			$href .= "+'&title='+encodeURIComponent(document.title));";
				
			elgg_register_menu_item('extras', array(
				'name' => 'report_this',
				'href' => $href,
				'text' => elgg_view_icon('report-this') . elgg_echo(''),
				'title' => elgg_echo('reportedcontent:this:tooltip'),
				'priority' => 100,
			));
		}
	}
}

function jobsin_page_handler($page) {

        elgg_load_library('elgg:jobsin');

        switch ($page[0]) {
                case 'all':
                        jobsin_handle_all_page();
                        break;
                case 'search':
                        jobsin_search_page();
                        break;
                case 'owner':
                        jobsin_handle_owned_page();
                        break;
                case 'member':
                        set_input('username', $page[1]);
                        jobsin_handle_mine_page();
                        break;
                case 'invitations':
                        set_input('username', $page[1]);
                        jobsin_handle_invitations_page();
                        break;
                case 'add':
                        jobsin_handle_edit_page('add');
                        break;
                case 'edit':
                        jobsin_handle_edit_page('edit', $page[1]);
                        break;
                case 'profile':
                        jobsin_handle_profile_page($page[1]);
                        break;
                case 'activity':
                        jobsin_handle_activity_page($page[1]);
                        break;
                case 'members':
                        jobsin_handle_members_page($page[1]);
                        break;
                case 'invite':
                        jobsin_handle_invite_page($page[1]);
                        break;
                case 'requests':
                        jobsin_handle_requests_page($page[1]);
                        break;
                default:
                        return false;
        }
        return true;
}

