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
	 
	// Have we been supplied with an entity?
		if (isset($vars['entity'])) {

			
			$guid = $vars['entity']->getGUID();
			$title = $vars['entity']->title;
			$description = $vars['entity']->description;
			
			$tags = $vars['entity']->tags;
			$access_id = $vars['entity']->access_id;
			
			$owner = $vars['entity']->getOwnerEntity();
			$highlight = 'default';
			
			$start_date = $vars['entity']->start_date;
			$start_date = date(TASKS_FORMAT_DATE_EVENTDAY, $start_date);
			$end_date = $vars['entity']->end_date;
			$end_date = date(TASKS_FORMAT_DATE_EVENTDAY, $end_date);
			$task_type = $vars['entity']->task_type;
			$status = $vars['entity']->status;
			$assigned_to = $vars['entity']->assigned_to;
			$percent_done = $vars['entity']->percent_done;
			$work_remaining = $vars['entity']->work_remaining;
			$access_id = $vars['entity']->access_id;
			$write_access_id = $vars['entity']->write_access_id;
			
			$container_id = $vars['entity']->getContainerGUID();
			$container = get_entity($container_id);
			
		} else {
			
			$guid = 0;
			$title = get_input('title',"");
			$description = "";

			$highlight = 'all';
			
			if ($address == "previous")
				$address = $_SERVER['HTTP_REFERER'];
			$tags = array();
			
			// bootstrap the access permissions in the entity array so we can use defaults
			/*
			if (defined('ACCESS_DEFAULT')) {
				$vars['entity']->access_id = ACCESS_DEFAULT;
				$vars['entity']->write_access_id = ACCESS_DEFAULT;
			} else {
				$vars['entity']->access_id = 0;
				$vars['entity']->write_access_id = 0;
			}
			*/
			
			$shares = array();
			$owner = $vars['user'];
			
			$container_id = $vars['container_guid'];
			//$container_id = get_input('container_guid');
			$container = get_entity($container_id);
			$access_id = $container->group_acl;
			$write_access_id = $container->group_acl;
		}
		$current_user= elgg_get_logged_in_user_entity();
		$assign_list = array();
		$assign_list[0] = "";
		$assign_list[$current_user->getGUID()] = $current_user->name;
		if($container instanceof ElggGroup){
			$assign_list1 = $container->getMembers(array('limit'=>0, 'offset'=>0, 'count'=>false));
			foreach($assign_list1 as $members)
				$assign_list[$members->getGUID()] = $members->name;
		} else {
			$assign_list1 = $current_user->getFriends(array('limit' => 0));
			foreach($assign_list1 as $friends)
				$assign_list[$friends->getGUID()] = $friends->name;
		}	

		$readonly = false;
		$session = elgg_get_session();
		if (! elgg_is_admin_logged_in() && ! $session->get('project_manager')) {
			$readonly = true;
		}
?>

		<table class="tasks" width="100%">
			<tr colspan="3">
				<p>
					<label>
						<?php 	//echo elgg_echo('title'); ?>
						<?php

								echo elgg_view('input/text',array(
										'name' => 'title',
										'value' => $title,
										'readonly' => $readonly,
								)); 
						
						?>
					</label>
				</p>
			</tr>
			<tr>
				<td width="50%">
					<label><?php echo elgg_echo('tasks:start_date'); ?></label>
						<?php echo elgg_view('input/date',array(
										'name' => 'start_date',
										'value' => $start_date,
										'class' => 'tiny date',
										'disabled' => $readonly,
								)); 
								if ($readonly) {
									echo elgg_view('input/hidden',array( 'name' => 'start_date', 'value' => $start_date));
								}
						?>
					
				</td>
				<td width="50%">
					<label><?php echo elgg_echo('tasks:end_date'); ?></label>
						<?php echo elgg_view('input/date',array(
										'name' => 'end_date',
										'value' => $end_date,
										'class' => 'tiny date',
										'disabled' => $readonly,
								)); 
								if ($readonly) {
									echo elgg_view('input/hidden',array( 'name' => 'end_date', 'value' => $end_date));
								}
					
					?>
				</td>
			</tr>
			<tr>
				<td width="50%">
				<label>	<?php echo elgg_echo('tasks:percent_done'); ?></label>
						<?php echo elgg_view($selectName, array(
								'name' => 'percent_done',
								'options_values' => array( '0' => elgg_echo('tasks:task_percent_done_0'),
														   '1' => elgg_echo('tasks:task_percent_done_1'),
														   '2' => elgg_echo('tasks:task_percent_done_2'),
														   '3' => elgg_echo('tasks:task_percent_done_3'),
														   '4' => elgg_echo('tasks:task_percent_done_4'),
														   '5' => elgg_echo('tasks:task_percent_done_5'),
														 ),
								'value' => $percent_done
							));
					?>
				</td>
				<td width="50%">
				<label>	<?php echo elgg_echo('tasks:work_remaining'); ?></label>
						<?php echo elgg_view('input/text',array(
										'name' => 'work_remaining',
										'value' => $work_remaining,
										'class' => 'number',
								)); 
						?>
				</td>
			</tr>
<?php 
				$task_type_label_display = elgg_echo('tasks:task_type');
				$task_type_display = elgg_view($selectName, array(
									'name' => 'task_type',
									'options_values' => array( '0' => "",
															   '1' => elgg_echo('tasks:task_type_1'),
															   '2' => elgg_echo('tasks:task_type_2'),
															   '3' => elgg_echo('tasks:task_type_3'),
															   '4' => elgg_echo('tasks:task_type_4'),
															   '5' => elgg_echo('tasks:task_type_5'),
															 ),
									'value' => $task_type
								));
$form = <<< END
			<tr>
				<td width="50%">
				<label>	$task_type_label_display</label>$task_type_display
				</td>
END;
if (elgg_is_admin_logged_in() || $session->get('project_manager')) {
	echo $form;
} else {
	echo elgg_view('inut/hidden', array( 'name' => 'task_type', 'value' => $task_type));
}
?>
				<td width="50%">
					<label>	<?php echo elgg_echo('tasks:status'); ?></label>	
							<?php echo elgg_view($selectName, array(
										'name' => 'status',
										'options_values' => array( '0' => "",
																   '1' => elgg_echo('tasks:task_status_1'),
																   '2' => elgg_echo('tasks:task_status_2'),
																   '3' => elgg_echo('tasks:task_status_3'),
																   '4' => elgg_echo('tasks:task_status_4'),
																   '5' => elgg_echo('tasks:task_status_5'),
																 ),
										'value' => $status
									));
							?>
				</td>
			</tr>
<?php
			$assigned_to_display = elgg_echo('tasks:assigned_to');
			//$assign_list_display = elgg_view($selectName, array( 'name' => 'assigned_to', 'options_values' => $assign_list, 'value' => $assigned_to));
			$assign_list_display = elgg_view("input/select", array( "name" => "assigned_to", "value" =>  $assigned_to, "options_values" => $assign_list, ));
$form = <<< END
			<tr>
				<td width="50%" colspan="2">
					<label>$assigned_to_display</label>$assign_list_display
				</td>
			</tr>
END;
if (elgg_is_admin_logged_in() || $session->get('project_manager')) {
	echo $form;
} else {
	echo elgg_view("input/hidden", array( "name" => "assigned_to", "value" =>  $assigned_to));
}
?>
			<tr>
				<td colspan="2">
					<label> <?php echo elgg_echo('description'); ?></label>
							<?php 
								if ($readonly) {
									echo $description;
									echo elgg_view('input/hidden',array( 'name' => 'description', 'value' => $description));
								} else {
									echo elgg_view('input/longtext',array(
											'name' => 'description',
											'value' => $description,
											'readonly' => $readonly,
									)); 
								}
						?>
					<label>	<?php echo elgg_echo('tags'); ?></label>
							<?php echo elgg_view('input/tags',array(
										'name' => 'tags',
										'value' => $tags,
										'readonly' => $readonly,
								)); 
						?>
					</label>
<?php
if (elgg_is_admin_logged_in()) {
					echo '<label>'.elgg_echo('tasks:access_id').'</label>';
					echo elgg_view('input/access',array( 'name' => 'access_id', 'value' => $access_id,)); 
					echo '<label>'.elgg_echo('tasks:write_access_id').'</label>';
					echo elgg_view('input/access',array( 'name' => 'write_access_id', 'value' => $write_access_id,)); 
} else {
					echo elgg_view('input/hidden',array( 'name' => 'access_id', 'value' => $access_id,)); 
					echo elgg_view('input/hidden',array( 'name' => 'write_access_id', 'value' => $write_access_id,)); 
}
					// Ajout de FXN pour gérer les catégories dans les tasks
					$cats = elgg_view('input/categories',$vars);
					if (!empty($cats)) { echo $cats; }
					?>
				</td>
			</tr>
		</table>

<?php


echo '<div class="elgg-foot">';
if ($vars['guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'task_guid',
		'value' => $vars['guid'],
	));
}
echo elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $vars['container_guid'],
));
if ($vars['parent_guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'parent_guid',
		'value' => $vars['parent_guid'],
	));
}

echo elgg_view('input/submit', array('value' => elgg_echo('save')));

echo '</div>';
