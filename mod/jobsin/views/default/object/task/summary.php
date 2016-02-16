<?php
/**
 * Task summary
 *
 */
$entity = $vars['entity'];
if ($entity->assigned_to) {
	$worker=get_entity($entity->assigned_to);
}
$owner = $entity->getOwnerEntity();
$container = get_entity($entity->getContainerGUID());
$friendlytime = elgg_view_friendly_time($entity->time_created);
$metadata = elgg_extract('metadata', $vars, '');
$urlTaskOwner = elgg_get_site_url()."tasks/owner/".$container->username;
$start_date = $entity->start_date;
$start_date = date(TASKS_FORMAT_DATE_EVENTDAY, $start_date);
$end_date = $entity->end_date;
$end_date = date(TASKS_FORMAT_DATE_EVENTDAY, $end_date);
/*
<!--
	<table width="100%" class="tasks" >
		<tr>
			<td width="33%">
				<h3><a href="<?php echo $entity->getURL(); ?>"><?php echo $entity->title; ?></a></h3>
			</td>
			<td width="33%">
				<a href="<?php echo $urlTaskOwner; ?>"><?php echo $container->name; ?></a>&nbsp;<?php echo $friendlytime; ?>
			</td width="33%">
			<td width="33%" style="text-align: right;">
				<?php if ($metadata) {	echo $metadata; } ?>
			</td>
		</tr>
	</table>
-->
*/
echo '<hr><table width="100%" class="tasks" ><tr><td width="50%"><label>'.elgg_echo('tasks:start_date').'</label>'.elgg_view('output/text',array('value' => $start_date)).' </td>';
echo '<td width="50%"><label>'.elgg_echo('tasks:end_date').'</label>'.elgg_view('output/text',array('value' => $end_date)).'</td></tr>';
echo '<tr> <td width="50%"><label>'.elgg_echo('tasks:task_type').'</label>'.elgg_view('output/text',array('value' => elgg_echo("tasks:task_type_{$entity->task_type}"))).'</td>';
echo '<td width="50%"><label>'.elgg_echo('tasks:status').'</label>'.elgg_view('output/text',array('value' => elgg_echo("tasks:task_status_{$entity->status}"))).'</td></tr>';
echo '<tr><td width="50%"><label>'.elgg_echo('tasks:percent_done').'</label>'.elgg_view('output/text',array('value' => elgg_echo("tasks:task_percent_done_{$entity->percent_done}"))).'</td>';
echo '<td width="50%"><label>'.elgg_echo('tasks:work_remaining').'</label>'.elgg_view('output/text',array('value' => $entity->work_remaining)).'</td></tr>';
echo '<tr><td width="50%"> <label>'.elgg_echo('tasks:assigned_to').'</label>';
if ($worker) {
	echo '<a href="'.elgg_get_site_url().'profile/'.$worker->username.'">'.$worker->name.'</a>';
}
echo '</td></tr>';
echo '<tr><td width="100%" colspan="2"><hr> <label>'.elgg_echo('tasks:description').'</label>'.elgg_view('output/longtext',array('value' => $entity->description)).'.</td></tr></table><hr>';


