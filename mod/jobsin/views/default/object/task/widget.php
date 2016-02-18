<?php
/**
 * Summary of an task for lists/galleries
 *
 * @uses $vars['entity'] TAsk
 *
 * @author Fx Nion
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */


	$entity = elgg_extract('entity', $vars);
	if ($entity->assigned_to) {
		$worker=get_entity($entity->assigned_to);
		$worker_link = elgg_view('output/url', array(
			'href' => "profile/$worker->username",
			'text' => $worker->name,
			'is_trusted' => true,
		));
	}
	$container = $entity->getContainerEntity();
	$friendlytime = elgg_view_friendly_time($entity->time_updated);
	$metadata = elgg_extract('metadata', $vars, '');
	$urlTaskOwner = elgg_get_site_url()."tasks/owner/".$container->username;
	$categories = elgg_view('output/categories', $vars);
	
	$metadataMenu = elgg_view_menu('entity', array(
	'entity' => $entity,
	'handler' => 'tasks',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
	));
	
	$owner = $entity->getOwnerEntity();
	$owner_link = elgg_view('output/url', array(
		'href' => "tasks/owner/$owner->username",
		'text' => $owner->name,
		'is_trusted' => true,
	));
	$author_text = elgg_echo('byline', array($owner_link));
	$start_date = $unix_start_date = $entity->start_date;
	$start_date = date(TASKS_FORMAT_DATE_EVENTDAY, $start_date);
	$end_date = $unix_end_date = $entity->end_date;
	$end_date = date(TASKS_FORMAT_DATE_EVENTDAY, $end_date);
	if ($entity->status <> 5 && $entity->end_date < time()) {
		$class = 'backlog';
	} elseif ($entity->status == 5 && $entity->percent_done == 5) {
		$class = 'done';
	} elseif ($entity->status == 1 && ! $entity->assigned_to) {
		$class = 'ready';
	} elseif ($entity->status == 4 && $entity->assigned_to) {
		$class = 'inprogress';
	}

/*
	$percent_done = 100*$entity->percent_done/5.0;
	if ($percent_done == 0) {
		$percent_width = '100%';
	} else {
		$percent_width = $percent_done.'%';
	}
	$percent_time_elapsed = 100*(time() - $unix_start_date)/($unix_end_date - $unix_start_date);
	$hist_color = 'red';
	if ($percent_time_elapsed != 0) {
		$status = $percent_done/$percent_time_elapsed;
		//$status = $percent_time_elapsed - $percent_done;
		$hist_width = 100*$status;
		if ($status > .75) {
			$hist_color = 'green';
		} elseif ($status < .75 and $status > .5) {
			$hist_color = 'yellow';
		} elseif ($status < .5 and $status > .25) {
			$hist_color = 'orange';
		} elseif ($status < .25) {
			$hist_color = 'red';
		}
	}
	*/


echo $metadataMenu;
echo '<h4 class="task-title"><a href="'.$entity->getURL().'">'.$entity->title.'</a></h4>';
echo "<div class=\"jobsin-$class\">";
echo '<div class="task-description">'.$entity->description.'</div>';
echo '<div class="task-dates">';
echo elgg_echo('tasks:start_date'). " : " .elgg_view('output/text',array('value' => $start_date)).'<br/>';
echo elgg_echo('tasks:end_date'). " : &nbsp;&nbsp;" .elgg_view('output/text',array('value' => $end_date)); 
echo '</div>';
echo '<div class="task-status">';
echo elgg_echo('jobsin:tasks:assigned_to', array($worker_link)).'<br/>';
echo elgg_echo('tasks:task_status_'.$entity->status).'<br/>';
echo elgg_view('output/text',array('value' => elgg_echo("tasks:task_percent_done_{$entity->percent_done}")));
//echo elgg_echo('tasks:work_remaining'). " : " .elgg_view('output/text',array('value' => $entity->work_remaining));
//echo elgg_echo('updated').' '.$friendlytime;
echo '</div>';
echo '</div>';
