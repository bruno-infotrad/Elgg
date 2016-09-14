<?php

$context = elgg_extract('context', $vars, elgg_get_context());
$owner = elgg_get_page_owner_entity();

if (elgg_is_logged_in() && $context) {

	$username = elgg_get_logged_in_user_entity()->username;
	$filter_context = elgg_extract('filter_context', $vars, 'all');

	if (!elgg_instanceof($owner, 'group')) {
		switch ($filter_context) {
			case 'mine':
			case 'owner':
				$url = "owner/$username";
				break;
			case 'assigned':
				$url = "assigned/$username";
				break;
			case 'all':
				$url = "all";
				break;
			default:
				$url = "all";
		}
	}else{
		$url = 'group/'.$owner->guid.'/all';
	}

	echo '<ul class="elgg-menu elgg-menu-entity elgg-menu-hz" style="height: 30px; margin-right: 15px;">';
	echo '<li>';
	echo elgg_view('output/url', array('href'=> elgg_get_site_url() .'calendars/'.$url,"text" => elgg_view('output/img', array("width"=>"20px", "title" => "View Cal", "src" => elgg_get_site_url() . 'mod/tasks/images/calendar'.($context == 'tasks' ? '_gray' : '' ).'.png'))));
	echo '</li>';
	echo '<li>';
	echo elgg_view('output/url', array('href'=> elgg_get_site_url() .'tasks/'.$url,"text" => elgg_view('output/img', array("width"=>"20px", "title" => "View List", "src" => elgg_get_site_url() . 'mod/tasks/images/list'.($context == 'calendars' ? '_gray' : '' ).'.png'))));
	echo '</li>';
	echo "</ul>";


	if (!elgg_instanceof($owner, 'group')) {
		$session = elgg_get_session();
		if (elgg_is_admin_logged_in() || $session->get('project_manager')) {
			$tabs = array(
				'all' => array(
					'text' => elgg_echo('all'),
					'href' => (isset($vars['all_link'])) ? $vars['all_link'] : "$context/all",
					'selected' => ($filter_context == 'all'),
					'priority' => 200,
				),
				'mine' => array(
					'text' => elgg_echo('mine'),
					'href' => (isset($vars['mine_link'])) ? $vars['mine_link'] : "$context/owner/$username",
					'selected' => ($filter_context == 'mine'),
					'priority' => 300,
				),
			);
		} else {
			$tabs = array(
				'all' => array(
					'text' => elgg_echo('all'),
					'href' => (isset($vars['all_link'])) ? $vars['all_link'] : "$context/all",
					'selected' => ($filter_context == 'all'),
					'priority' => 200,
				),
				'assigned' => array(
					'text' => elgg_echo('jobsin:tasks:assigned:filter'),
					'href' => (isset($vars['assigned_link'])) ? $vars['assigned_link'] : "$context/assigned/$username",
					'selected' => ($filter_context == 'assigned'),
					'priority' => 300,
				),
			);
		}
		
		foreach ($tabs as $name => $tab) {
			$tab['name'] = $name;
			
			elgg_register_menu_item('filter', $tab);
		}

		echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));
	}

}
