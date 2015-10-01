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
	$worker=get_entity($entity->assigned_to);
	
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
	$start_date = explode('-',$entity->start_date);
        $unix_start_date = mktime(0,0,1,$start_date[1],$start_date[2],$start_date[0]);
	$end_date = explode('-',$entity->end_date);
        $unix_end_date = mktime(0,0,1,$end_date[1],$end_date[2],$end_date[0]);
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


?>
<?php echo $metadataMenu; ?>
<h4><a href="<?php echo $entity->getURL(); ?>"><?php echo $entity->title; ?></a></h4>
<table class="task" style="color: #aaa; width: 100%; font-size: 85%">
	<tr>
		<td style="width:55%;border: 1px solid">
		<div style="color: black; width: <?php echo $percent_width;?>;background-color: <?php echo $hist_color;?>;"><?php echo elgg_echo('tasks:percent_done'). " : " .elgg_view('output/text',array('value' => elgg_echo("tasks:task_percent_done_{$entity->percent_done}"))); ?></div>
		</td>
		<td width="45%">
		<?php echo elgg_echo('tasks:work_remaining'). " : " .elgg_view('output/text',array('value' => $entity->work_remaining)); ?>
		</td>
	</tr>
	<tr>
		<td width="55%">
		<?php echo elgg_echo('tasks:start_date'). " : " .elgg_view('output/text',array('value' => $entity->start_date, "css"=>"truc")); ?>
		</td>
		<td width="45%">
		<?php echo elgg_echo('tasks:end_date'). " : " .elgg_view('output/text',array('value' => $entity->end_date)); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="elgg-subtext" ><?php  echo elgg_echo('byline', array($owner_link)); ?> ,  <?php echo $friendlytime; ?></td>
	</tr>
</table>

