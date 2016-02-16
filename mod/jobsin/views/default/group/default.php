<?php 
/**
 * Group entity view
 * 
 * @package ElggGroups
 */

$group = $vars['entity'];
$container_guid = $group->getGUID();

$icon = elgg_view_entity_icon($group, 'small');

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
	if ((elgg_in_context('owner_block') || elgg_in_context('widgets')) && !elgg_in_context("widgets_groups_show_members")) {
		$params = array(
			'entity' => $group,
			//'metadata' => $metadata,
			'subtitle' => $group->briefdescription,
		);
	} else {
		$now = time();
		$first_task = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtypes' => array('task','task_top'),
			'container_guid' => $container_guid,
			'limit' => 1,
			'metadata_name' => array( 'name' => 'start_date'),
			'order_by_metadata' => array('name' => 'start_date','direction' => ASC,'as' => integer)
		));
		$start_date = date(TASKS_FORMAT_DATE_EVENTDAY, $first_task[0]->start_date);
		$last_task = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtypes' => array('task','task_top'),
			'container_guid' => $container_guid,
			'limit' => 1,
			'metadata_name' => array( 'name' => 'end_date'),
			'order_by_metadata' => array('name' => 'end_date','direction' => DESC,'as' => integer)
		));
		$end_date = date(TASKS_FORMAT_DATE_EVENTDAY, $last_task[0]->end_date);
		$num_of_contributors = $group->getMembers(array('limit'=>0, 'offset'=>0, 'count'=>true));
		$num_of_tasks = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtypes' => array('task','task_top'),
			'container_guid' => $container_guid,
			'limit' => false,
			'count' => true,
		));
		$backlog = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtypes' => array('task','task_top'),
			'container_guid' => $container_guid,
			'limit' => false,
			'count' => true,
			'metadata_name_value_pairs' => array( 'name' => 'end_date', 'value' => $now,'operand' => '<=')
		));
		$done = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtypes' => array('task','task_top'),
			'container_guid' => $container_guid,
			'limit' => false,
			'count' => true,
			'metadata_name_value_pairs' => array( 'name' => 'status', 'value' => 5)
		));
		$ready = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtypes' => array('task','task_top'),
			'container_guid' => $container_guid,
			'limit' => false,
			'count' => true,
			'metadata_name_value_pairs' => array( 'name' => 'status', 'value' => 1,'name' => 'assigned_to', 'value' => 0),
		));
		$in_progress = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtypes' => array('task','task_top'),
			'container_guid' => $container_guid,
			'limit' => false,
			'count' => true,
			'metadata_name_value_pairs' => array(array('name' => 'status', 'value' => 4),array('name' => 'assigned_to', 'value' => 0,'operand' => '<>')),
		));

		// brief view
		$params = array(
			'tasknum' => $num_of_tasks,
			'contributors' => $num_of_contributors,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'backlog' => $backlog,
			'done' => $done,
			'ready' => $ready,
			'in_progress' => $in_progress,
			'entity' => $group,
			//'metadata' => $metadata,
			'subtitle' => $group->briefdescription,
			'jobsin_details' => true
		);
	}
	$params = $params + $vars;
	$list_body = elgg_view('group/elements/summary', $params);
	echo elgg_view_image_block($icon, $list_body, $vars);
}
