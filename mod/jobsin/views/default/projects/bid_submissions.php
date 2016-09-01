<?php
$group = elgg_extract("group", $vars);
$group_bids = elgg_extract("group_bids", $vars);
$project_admin = elgg_extract("project_admin", $vars);
// Need this because user is not in group yet
echo "<ul class='elgg-list mbm'>";
foreach ($group_bids as $group_bid) {
	//echo var_export($group_bid,true).'<br>';
	
	$body .= "<li class='pvs'>";
	//$group_title = elgg_view("output/url", array( "href" => $group->getURL(), "text" => $group->name, "is_trusted" => true,));
	//$body .= "<h4>$group_title</h4>";
	//$body .= "<p class='elgg-subtext'>$group->briefdescription</p>";
	$metadataMenu = elgg_view_menu('entity', array(
                'entity' => $group_bid,
                'handler' => 'projects/bid_submissions',
                'sort_by' => 'priority',
                'class' => 'elgg-menu-hz',
        ));
	$body .= $metadataMenu;
	$task_guids = $group_bid->tasks;
	if (is_array($task_guids)) {
		foreach ($task_guids as $task_guid) {
		//echo $task_guid.'<br>';
		$task = get_entity($task_guid);
		//echo var_export($task,true).'<br>';
		$body .= "<h4>$task->title</h4>";
		if (! $project_admin) {
			$body .= '<div class="task-description">'.$task->description.'</div>';
		}
		$body .= '<div class="task-dates">';
		$body .= elgg_echo('Task').' '.elgg_echo('tasks:start_date'). " : " .elgg_view('output/text',array('value' => date(TASKS_FORMAT_DATE_EVENTDAY, $task->start_date))).'<br/>';
		$body .= elgg_echo('Task').' '.elgg_echo('tasks:end_date'). " : &nbsp;&nbsp;" .elgg_view('output/text',array('value' => date(TASKS_FORMAT_DATE_EVENTDAY, $task->end_date)));
		$body .= '<p><b>'.elgg_echo('jobsin:submission:end_date');
		$body .= " : &nbsp;&nbsp;" .elgg_view('output/text',array('value' => date(TASKS_FORMAT_DATE_EVENTDAY, $group_bid->end_date))).'</b></p>';
		$body .= '</div>';
		}
	} else {
		//echo $task_guids.'<br>';
		$task = get_entity($task_guids);
		//echo var_export($task,true).'<br>';
		$body .= "<h4>$task->title</h4>";
		if (! $project_admin) {
			$body .= '<div class="task-description">'.$task->description.'</div>';
		}
		$body .= '<div class="task-dates">';
		$body .= elgg_echo('Task').' '.elgg_echo('tasks:start_date'). " : " .elgg_view('output/text',array('value' => date(TASKS_FORMAT_DATE_EVENTDAY, $task->start_date))).'<br/>';
		$body .= elgg_echo('Task').' '.elgg_echo('tasks:end_date'). " : &nbsp;&nbsp;" .elgg_view('output/text',array('value' => date(TASKS_FORMAT_DATE_EVENTDAY, $task->end_date)));
		$body .= '<p><b>'.elgg_echo('jobsin:submission:end_date');
		$body .= " : &nbsp;&nbsp;" .elgg_view('output/text',array('value' => date(TASKS_FORMAT_DATE_EVENTDAY, $group_bid->end_date))).'</b></p>';
		$body .= '</div>';
	}
	$body .= '<div class="task-rate">';
	$body .= '<p>'.elgg_echo('jobsin:bidder').': ';
	$body .= get_entity($group_bid->invitee)->name.'</p>';
	$body .= '<p>'.elgg_echo('jobsin:bid_status').': ';
	$body .= $group_bid->status.'</p>';
	$body .= '<p>'.elgg_echo('jobsin:task_rate');
	$body .= $group_bid->rate.'</p>';
	$body .= '</div>';
	if ($project_admin && $group_bid->rate && $group_bid->status != 'selected') {
		$body .= elgg_view_form('projects/select_bid',array('class' => 'task-selection'),array('bid_guid' => $group_bid->getGUID()));
	}
	$body .= '</li>';
}
echo $body;
