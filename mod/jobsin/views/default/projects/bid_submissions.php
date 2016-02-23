<?php
$group = elgg_extract("group", $vars);
$group_bids = elgg_extract("group_bids", $vars);
// Need this because user is not in group yet
echo "<ul class='elgg-list mbm'>";
foreach ($group_bids as $group_bid) {
	//echo var_export($group_bid,true).'<br>';
	
	$body .= "<li class='pvs'>";
	$group_title = elgg_view("output/url", array( "href" => $group->getURL(), "text" => $group->name, "is_trusted" => true,));
	$body .= "<h4>$group_title</h4>";
	$body .= "<p class='elgg-subtext'>$group->briefdescription</p>";
	$task_guids = $group_bid->tasks;
	//echo $task_guids.'<br>';
	$task = get_entity($task_guids);
	//echo var_export($task,true).'<br>';
	$body .= '<div class="task-description">'.$task->description.'</div>';
	$body .= '<div class="task-dates">';
	$body .= elgg_echo('tasks:start_date'). " : " .elgg_view('output/text',array('value' => date(TASKS_FORMAT_DATE_EVENTDAY, $task->start_date))).'<br/>';
	$body .= elgg_echo('tasks:end_date'). " : &nbsp;&nbsp;" .elgg_view('output/text',array('value' => date(TASKS_FORMAT_DATE_EVENTDAY, $task->end_date)));
	$body .= '</div>';
	$body .= '<div class="task-rate">';
	$body .= '<p>'.elgg_echo('jobsin:bid_status').': ';
	$body .= $group_bid->status.'</p>';
	$body .= '<p>'.elgg_echo('jobsin:task_rate');
	$body .= $group_bid->rate.'</p>';
	$body .= '</div>';
	$body .= '</li>';
}
echo $body;
