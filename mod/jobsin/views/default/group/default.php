<?php 
/**
 * Group entity view
 * 
 * @package ElggGroups
 */

$group = $vars['entity'];
$container_guid = $group->getGUID();

$icon = elgg_view_entity_icon($group, 'tiny');

$metadata = elgg_view_menu('entity', array(
	'entity' => $group,
	'handler' => 'groups',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

if ((elgg_in_context('owner_block') || elgg_in_context('widgets')) && !elgg_in_context("widgets_groups_show_members")) {
	$metadata = '';
}
if ($vars['full_view']) {
	echo 'FULL'.elgg_view('groups/profile/summary', $vars);
} else {
	$now = time();
	$backlog = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtypes' => array('task','task_top'),
		'container_guid' => $container_guid,
		'limit' => false,
		'count' => true,
		'metadata_name_value_pairs' => array( 'name' => 'end_date', 'value' => $now,'operand' => '<=')
	));

	// brief view
	$params = array(
		'backlog' => $backlog,
		'entity' => $group,
		//'metadata' => $metadata,
		'subtitle' => $group->briefdescription,
	);
	$params = $params + $vars;
	$list_body = elgg_view('group/elements/summary', $params);

	echo elgg_view_image_block($icon, $list_body, $vars);
}
