<?php
/**
 * All groups listing page navigation
 *
 */
$group = elgg_extract('group', $vars);
$tabs = array(
	'tasks' => array(
		'name' => 'group_tasks',
		'text' => elgg_echo('tasks'),
		'href' => 'projects/profile/'.$group->getGUID().'/'.$group->name,
		'priority' => 100,
	),
	'members' => array(
		'name' => 'members',
		'text' => elgg_echo('members'),
		'href' => 'projects/profile/'.$group->getGUID().'/'.$group->name.'?page_type=members',
		'priority' => 200,
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
