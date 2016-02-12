<?php
/**
 * All groups listing page navigation
 *
 */
$tabs = array(
	'projects' => array(
		'name' => 'projects',
		'text' => elgg_echo('groups'),
		'href' => 'dashboard?page_type=projects',
		'priority' => 50,
	), 
	'tasks' => array(
		'name' => 'tasks',
		'text' => elgg_echo('tasks'),
		'href' => 'dashboard?page_type=tasks',
		'priority' => 100,
	)
);
// sets default selected item
if (strpos(current_page_url(), 'page_type') === false) {
	$tabs[$vars['page_type']]['selected'] = true;
}
foreach ($tabs as $name => $tab) {
	$tab['name'] = $name;
	elgg_register_menu_item('filter', $tab);
}

echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));
