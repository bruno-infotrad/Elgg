<?php

/**
 * Elgg tasks plugin form
 * 
 * @package Elggtasks
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Fx NION 
 * @copyright Fx NION Ltd 2008-20014
 * @link http://elgg.org/
 */

 
 $selectName = (datalist_get("version") > 2014012000) ? "input/select" : "input/pulldown";
 
// Existing task
if (isset($vars['entity'])) {
	$readonly=true;
	$guid = $vars['entity']->getGUID();
	$vars['guid'] = $guid;
	$title = $vars['entity']->title;
	$description = $vars['entity']->description;
	
	$tags = $vars['entity']->tags;
	$access_id = $vars['entity']->access_id;
	
	$owner = $vars['entity']->getOwnerEntity();
	$highlight = 'default';
	
	$start_date = $vars['entity']->start_date;
	//$start_date = date(TASKS_FORMAT_DATE_EVENTDAY, $start_day);
	$end_date = $vars['entity']->end_date;
	//$end_date = date(TASKS_FORMAT_DATE_EVENTDAY, $end_day);
	$duration = $vars['entity']->duration;
	$rate = $vars['entity']->rate;
	$task_type = $vars['entity']->task_type;
	$status = $vars['entity']->status;
	$assigned_to = $vars['entity']->assigned_to;
	$percent_done = $vars['entity']->percent_done;
	//$work_remaining = $vars['entity']->work_remaining;
	$write_access_id = $vars['entity']->write_access_id;
	
	$container_id = $vars['entity']->getContainerGUID();
	$container = get_entity($container_id);
	
// New task
} else {
	$readonly=false;
	$guid = 0;
	$title = get_input('title',"");
	$description = "";

	$highlight = 'all';
	
	if ($address == "previous")
		$address = $_SERVER['HTTP_REFERER'];
	$tags = array();
	
	// bootstrap the access permissions in the entity array so we can use defaults
	if (defined('ACCESS_DEFAULT')) {
		$vars['entity']->access_id = ACCESS_DEFAULT;
		$vars['entity']->write_access_id = ACCESS_DEFAULT;
	} else {
		$vars['entity']->access_id = 0;
		$vars['entity']->write_access_id = 0;
	}
	
	$shares = array();
	$owner = $vars['user'];
	
	//$container_id = $vars['container_guid'];
	$container_id = get_input('container_guid');
	$container = get_entity($container_id);
	
}
$current_user= elgg_get_logged_in_user_entity();
$assign_list = array();
$assign_list[0] = "";
$assign_list[$current_user->getGUID()] = $current_user->name;
if($container instanceof ElggGroup){
	$assign_list1 = $container->getMembers(array('limit'=>300));
	foreach($assign_list1 as $members)
		$assign_list[$members->getGUID()] = $members->name;
}else{
	$assign_list1 = $current_user->getFriends(array('limit' => 300));
	//$assign_list1 = $current_user->getFriends("", 300, $offset = 0);
	foreach($assign_list1 as $friends)
		$assign_list[$friends->getGUID()] = $friends->name;
}	
if ($container->getOwnerEntity() == elgg_get_logged_in_user_entity()){
	$admin_readonly=true;
	if ($assigned_to){
		$form = "<label>";
		$form .= elgg_view('input/text',array( 'name' => 'title', 'value' => $title,'readonly' => $admin_readonly));
		$form .= "</label> <div class='table-row'> <div class='table-cell-label'> <label>";
		$form .= elgg_echo('tasks:start_date');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view('input/date',array( 'name' => 'start_date', 'value' => $start_date, 'class' => 'tiny date','readonly' => $admin_readonly));
		$form .= "</div><div class='table-cell-label'><label>";
		$form .= elgg_echo('tasks:end_date');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view('input/date',array( 'name' => 'end_date', 'value' => $end_date, 'class' => 'tiny date','readonly' => $admin_readonly));
		$form .= "</div><div class='table-cell-label'><label>";
		$form .= elgg_echo('tasks:duration');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view('input/text',array( 'name' => 'duration', 'value' => $duration, 'class' => 'number','readonly' => $admin_readonly));
		$form .= "</div><div class='table-cell-label'><label>";
		$form .= elgg_echo('tasks:rate');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view('input/text',array( 'name' => 'rate', 'value' => $rate, 'class' => 'number','readonly' => $admin_readonly));
		$form .= "</div></div><div class='table-row'><div class='table-cell-label'><label>";
		$form .= elgg_echo('tasks:task_type');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view($selectName, array( 'name' => 'task_type', 'options_values' => array( '0' => "",
															   '1' => elgg_echo('tasks:task_type_1'),
															   '2' => elgg_echo('tasks:task_type_2'),
															   '3' => elgg_echo('tasks:task_type_3'),
															   '4' => elgg_echo('tasks:task_type_4'),
															   '5' => elgg_echo('tasks:task_type_5'),
															 ),
									'value' => $task_type,'readonly' => $admin_readonly
								));
		$form .= "</div><div class='table-cell-label'><label>";
		$form .= elgg_echo('tasks:status');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view($selectName, array( 'name' => 'status', 'options_values' => array( '0' => "",
																   '1' => elgg_echo('tasks:task_status_1'),
																   '2' => elgg_echo('tasks:task_status_2'),
																   '3' => elgg_echo('tasks:task_status_3'),
																   '4' => elgg_echo('tasks:task_status_4'),
																   '5' => elgg_echo('tasks:task_status_5'),
																 ),
										'value' => $status
									));
		$form .= "</div><div class='table-cell-label'><label>";
		$form .= elgg_echo('tasks:percent_done');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view($selectName, array( 'name' => 'percent_done', 'options_values' => array( '0' => elgg_echo('tasks:task_percent_done_0'),
														   '1' => elgg_echo('tasks:task_percent_done_1'),
														   '2' => elgg_echo('tasks:task_percent_done_2'),
														   '3' => elgg_echo('tasks:task_percent_done_3'),
														   '4' => elgg_echo('tasks:task_percent_done_4'),
														   '5' => elgg_echo('tasks:task_percent_done_5'),
														 ),
								'value' => $percent_done
							));
		$form .= "</div></div><div class='table-row'><div class='table-cell-label'><label>";
		$form .= elgg_echo('tasks:assigned_to');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view($selectName, array( 'name' => 'assigned_to', 'options_values' => $assign_list, 'value' => $assigned_to,'readonly' => $admin_readonly));
		$form .= "</div></div><div class='table-row'><label>";
		$form .= elgg_echo('description');
		$form .= "</label>";
		$form .= elgg_view('input/longtext',array( 'name' => 'description', 'value' => $description,));
		$form .= "</div><div class='table-row'><div class='table-cell-label'><label>";
		$form .= elgg_echo('tags');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view('input/tags',array( 'name' => 'tags', 'value' => $tags, 'maxlength' => 10));
		$form .= "</div><div class='table-cell-label'><label>";
		$form .= elgg_echo('tasks:access_id');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view('input/access',array( 'name' => 'access_id', 'value' => $access_id,));
		$form .= "</div> <div class='table-cell-label'><label>";
		$form .= elgg_echo('tasks:write_access_id');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view('input/access',array( 'name' => 'write_access_id', 'value' => $write_access_id,));
		// Ajout de FXN pour gérer les catégories dans les tasks
		$cats = elgg_view('input/categories',$vars);
		if (!empty($cats)) { echo $cats; }
		$form .="</div></div>";
		
		$form .= '<div class="elgg-foot">';
		if ($vars['guid']) {
			$form .= elgg_view('input/hidden', array( 'name' => 'task_guid', 'value' => $vars['guid'],));
		}
		$form .= elgg_view('input/hidden', array( 'name' => 'container_guid', 'value' => $vars['container_guid'],));
		if ($vars['parent_guid']) {
			$form .= elgg_view('input/hidden', array( 'name' => 'parent_guid', 'value' => $vars['parent_guid'],));
		}
		
		$form .= elgg_view('input/submit', array('value' => elgg_echo('save')));
		$form .= '</div>';
	} else {
		$form = "<label>";
		$form .= elgg_view('input/text',array( 'name' => 'title', 'value' => $title));
		$form .= "</label> <div class='table-row'> <div class='table-cell-label'> <label>";
		$form .= elgg_echo('tasks:start_date');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view('input/date',array( 'name' => 'start_date', 'value' => $start_date, 'class' => 'tiny date'));
		$form .= "</div><div class='table-cell-label'><label>";
		$form .= elgg_echo('tasks:end_date');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view('input/date',array( 'name' => 'end_date', 'value' => $end_date, 'class' => 'tiny date'));
		$form .= "</div><div class='table-cell-label'><label>";
		$form .= elgg_echo('tasks:duration');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view('input/text',array( 'name' => 'duration', 'value' => $duration, 'class' => 'number'));
		$form .= "</div><div class='table-cell-label'><label>";
		$form .= elgg_echo('tasks:rate');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view('input/text',array( 'name' => 'rate', 'value' => $rate, 'class' => 'number'));
		$form .= "</div></div><div class='table-row'><div class='table-cell-label'><label>";
		$form .= elgg_echo('tasks:task_type');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view($selectName, array( 'name' => 'task_type', 'options_values' => array( '0' => "",
															   '1' => elgg_echo('tasks:task_type_1'),
															   '2' => elgg_echo('tasks:task_type_2'),
															   '3' => elgg_echo('tasks:task_type_3'),
															   '4' => elgg_echo('tasks:task_type_4'),
															   '5' => elgg_echo('tasks:task_type_5'),
															 ),
									'value' => $task_type
								));
		$form .= "</div><div class='table-cell-label'><label>";
		$form .= elgg_echo('tasks:status');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view($selectName, array( 'name' => 'status', 'options_values' => array( '0' => "",
																   '1' => elgg_echo('tasks:task_status_1'),
																   '2' => elgg_echo('tasks:task_status_2'),
																   '3' => elgg_echo('tasks:task_status_3'),
																   '4' => elgg_echo('tasks:task_status_4'),
																   '5' => elgg_echo('tasks:task_status_5'),
																 ),
										'value' => $status
									));
		$form .= "</div><div class='table-cell-label'><label>";
		$form .= elgg_echo('tasks:percent_done');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view($selectName, array( 'name' => 'percent_done', 'options_values' => array( '0' => elgg_echo('tasks:task_percent_done_0'),
														   '1' => elgg_echo('tasks:task_percent_done_1'),
														   '2' => elgg_echo('tasks:task_percent_done_2'),
														   '3' => elgg_echo('tasks:task_percent_done_3'),
														   '4' => elgg_echo('tasks:task_percent_done_4'),
														   '5' => elgg_echo('tasks:task_percent_done_5'),
														 ),
								'value' => $percent_done
							));
		$form .= "</div></div><div class='table-row'><div class='table-cell-label'><label>";
		$form .= elgg_echo('tasks:assigned_to');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view($selectName, array( 'name' => 'assigned_to', 'options_values' => $assign_list, 'value' => $assigned_to));
		$form .= "</div></div><div class='table-row'><label>";
		$form .= elgg_echo('description');
		$form .= "</label>";
		$form .= elgg_view('input/longtext',array( 'name' => 'description', 'value' => $description,));
		$form .= "</div><div class='table-row'><div class='table-cell-label'><label>";
		$form .= elgg_echo('tags');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view('input/tags',array( 'name' => 'tags', 'value' => $tags));
		$form .= "</div><div class='table-cell-label'><label>";
		$form .= elgg_echo('tasks:access_id');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view('input/access',array( 'name' => 'access_id', 'value' => $access_id,));
		$form .= "</div><div class='table-cell-label'><label>";
		$form .= elgg_echo('tasks:write_access_id');
		$form .= "</label></div><div class='table-cell'>";
		$form .= elgg_view('input/access',array( 'name' => 'write_access_id', 'value' => $write_access_id,));
		// Ajout de FXN pour gérer les catégories dans les tasks
		$cats = elgg_view('input/categories',$vars);
		if (!empty($cats)) { echo $cats; }
		$form .="</div></div>";
		
		$form .= '<div class="elgg-foot">';
		if ($vars['guid']) {
			$form .= elgg_view('input/hidden', array( 'name' => 'task_guid', 'value' => $vars['guid'],));
		}
		$form .= elgg_view('input/hidden', array( 'name' => 'container_guid', 'value' => $vars['container_guid'],));
		if ($vars['parent_guid']) {
			$form .= elgg_view('input/hidden', array( 'name' => 'parent_guid', 'value' => $vars['parent_guid'],));
		}
		
		$form .= elgg_view('input/submit', array('value' => elgg_echo('save')));
		$form .= '</div>';
	}
} else {
	$form = "<label>";
	$form .= elgg_view('input/text',array( 'name' => 'title', 'value' => $title,'readonly' => $readonly));
	$form .= "</label> <div class='table-row'> <div class='table-cell-label'> <label>";
	$form .= elgg_echo('tasks:start_date');
	$form .= "</label></div><div class='table-cell'>";
	$form .= elgg_view('input/date',array( 'name' => 'start_date', 'value' => $start_date, 'class' => 'tiny date','disabled' => $readonly));
	$form .= "</div><div class='table-cell-label'><label>";
	$form .= elgg_echo('tasks:end_date');
	$form .= "</label></div><div class='table-cell'>";
	$form .= elgg_view('input/date',array( 'name' => 'end_date', 'value' => $end_date, 'class' => 'tiny date','disabled' => $readonly));
	$form .= "</div><div class='table-cell-label'><label>";
	$form .= elgg_echo('tasks:duration');
	$form .= "</label></div><div class='table-cell'>";
	$form .= elgg_view('input/text',array( 'name' => 'duration', 'value' => $duration, 'class' => 'number','readonly' => $readonly));
	$form .= "</div><div class='table-cell-label'><label>";
	$form .= elgg_echo('tasks:rate');
	$form .= "</label></div><div class='table-cell'>";
	$form .= elgg_view('input/text',array( 'name' => 'rate', 'value' => $rate, 'class' => 'number','readonly' => $readonly));
	$form .= "</div></div><div class='table-row'><div class='table-cell-label'><label>";
	$form .= elgg_echo('tasks:task_type');
	$form .= "</label></div><div class='table-cell'>";
	/*
	$form .= elgg_view($selectName, array( 'name' => 'task_type', 'options_values' => array( '0' => "",
														   '1' => elgg_echo('tasks:task_type_1'),
														   '2' => elgg_echo('tasks:task_type_2'),
														   '3' => elgg_echo('tasks:task_type_3'),
														   '4' => elgg_echo('tasks:task_type_4'),
														   '5' => elgg_echo('tasks:task_type_5'),
														 ),
								'value' => $task_type,'readonly' => $readonly
							));
	*/
	$form .= elgg_echo("tasks:task_type_$task_type");
	$form .= "</div><div class='table-cell-label'><label>";
	$form .= elgg_echo('tasks:status');
	$form .= "</label></div><div class='table-cell'>";
	/*
	$form .= elgg_view($selectName, array( 'name' => 'status', 'options_values' => array( '0' => "",
															   '1' => elgg_echo('tasks:task_status_1'),
															   '2' => elgg_echo('tasks:task_status_2'),
															   '3' => elgg_echo('tasks:task_status_3'),
															   '4' => elgg_echo('tasks:task_status_4'),
															   '5' => elgg_echo('tasks:task_status_5'),
															 ),
									'value' => $status,'readonly' => $readonly
								));
	*/
	$form .= elgg_echo("tasks:task_status_$status");
	$form .= "</div><div class='table-cell-label'><label>";
	$form .= elgg_echo('tasks:percent_done');
	$form .= "</label></div><div class='table-cell'>";
	$form .= elgg_view($selectName, array( 'name' => 'percent_done', 'options_values' => array( '0' => elgg_echo('tasks:task_percent_done_0'),
													   '1' => elgg_echo('tasks:task_percent_done_1'),
													   '2' => elgg_echo('tasks:task_percent_done_2'),
													   '3' => elgg_echo('tasks:task_percent_done_3'),
													   '4' => elgg_echo('tasks:task_percent_done_4'),
													   '5' => elgg_echo('tasks:task_percent_done_5'),
													 ),
							'value' => $percent_done
						));
	$form .= "</div></div><div class='table-row'><div class='table-cell-label'><label>";
	$form .= elgg_echo('tasks:assigned_to');
	$form .= "</label></div><div class='table-cell'>";
	//$form .= elgg_view($selectName, array( 'name' => 'assigned_to', 'options_values' => $assign_list, 'value' => $assigned_to,'readonly' => $readonly));
	$form .= get_entity($assigned_to)->name;
	$form .= "</div></div><div class='table-row'><label>";
	$form .= elgg_echo('description');
	$form .= "</label>";
	$form .= elgg_view('input/longtext',array( 'name' => 'description', 'value' => $description,'readonly' => $readonly));
	$form .= "</div><div class='table-row'><div class='table-cell-label'><label>";
	$form .= elgg_echo('tags');
	$form .= "</label></div><div class='table-cell'>";
	$form .= elgg_view('input/tags',array( 'name' => 'tags', 'value' => $tags,));
	$form ."</label></div></div><div class='table-row'><div class='table-cell-label'><label>";
	//$form .= elgg_echo('tasks:access_id');
	$form .= "</label></div><div class='table-cell'>";
	$form .= elgg_view('input/hidden',array( 'name' => 'access_id', 'value' => $access_id,));
	$form .= "</div> <div class='table-cell-label'><label>";
	//$form .= elgg_echo('tasks:write_access_id');
	$form .= "</label></div><div class='table-cell'>";
	$form .= elgg_view('input/hidden',array( 'name' => 'write_access_id', 'value' => $write_access_id,));
	// Ajout de FXN pour gérer les catégories dans les tasks
	$cats = elgg_view('input/categories',$vars);
	if (!empty($cats)) { echo $cats; }
	$form .="</div></div>";
	
	$form .= '<div class="elgg-foot">';
	if ($vars['guid']) {
		$form .= elgg_view('input/hidden', array( 'name' => 'task_guid', 'value' => $vars['guid'],));
	}
	$form .= elgg_view('input/hidden', array( 'name' => 'container_guid', 'value' => $vars['container_guid'],));
	if ($vars['parent_guid']) {
		$form .= elgg_view('input/hidden', array( 'name' => 'parent_guid', 'value' => $vars['parent_guid'],));
	}
	
	$form .= elgg_view('input/submit', array('value' => elgg_echo('save')));
	$form .= '</div>';
}
echo $form;
