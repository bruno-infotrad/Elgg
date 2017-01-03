<?php
$group = elgg_extract("group", $vars);
$bids = elgg_extract("group_bids", $vars);
$transferred_bids = elgg_extract("transferred_bids", $vars);
$project_admin = elgg_extract("project_admin", $vars);
// Need this because user is not in group yet
$body = "<ul class='elgg-list mbm bids'>";
$body .= '<h3>'.elgg_echo('jobsin:direct_bids').'</h3>';
foreach ($bids as $group_bid) {
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
		$body .= elgg_echo('tasks:duration'). " : &nbsp;&nbsp;" .elgg_view('output/text',array('value' => $task->duration)).'<br/>';
		$body .= elgg_echo('tasks:suggested_rate'). " : &nbsp;&nbsp;" .elgg_view('output/text',array('value' => $task->rate));
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
		$body .= elgg_echo('tasks:duration'). " : &nbsp;&nbsp;" .elgg_view('output/text',array('value' => $task->duration)).'<br/>';
		$body .= elgg_echo('tasks:suggested_rate'). " : &nbsp;&nbsp;" .elgg_view('output/text',array('value' => $task->rate));
		$body .= '</div>';
	}
	$body .= '<div class="task-rate">';
	$body .= '<div class="transferred-to-label">'.elgg_echo('jobsin:bidder').': ';
	if (is_int($group_bid->invitee)) {
	//Real Elgg user
	        $bidder = get_entity($group_bid->invitee);
		//$bidder .= elgg_view_entity($bidder, array('tiny'));
		$bidder_box = " ".$bidder->name.'</div>';
		$bidder_box .= " ".elgg_view_entity_icon($bidder, "small", array("use_hover" => "true"));
	} else {
	//External email
	        $bidder_box = $group_bid->invitee.'</div>';
	}

	$body .= $bidder_box;
	$body .= '<p>'.elgg_echo('jobsin:bid_status').': ';
	$body .= $group_bid->status.'</p>';
	$body .= "<div class='p'>".elgg_echo('jobsin:task_rate');
	$body .= "<div id='bid-rate'>".$group_bid->rate.'</div></div>';
	$body .= '</div>';
	if ($project_admin && $group_bid->rate && $group_bid->status != 'selected' && $group_bid->status != 'notselected') {
		$body .= elgg_view_form('projects/select_bid',array('class' => 'task-selection'),array('bid_guid' => $group_bid->getGUID()));
	}
	$body .= '</li>';
}
$body .= '</ul>';
$body .= "<ul class='elgg-list mbm bids'>";
$body .= '<h3>'.elgg_echo('jobsin:transferred_bids').'</h3>';
foreach ($transferred_bids as $group_bid) {
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
		$body .= elgg_echo('tasks:duration'). " : &nbsp;&nbsp;" .elgg_view('output/text',array('value' => $task->duration)).'<br/>';
		$body .= elgg_echo('tasks:suggested_rate'). " : &nbsp;&nbsp;" .elgg_view('output/text',array('value' => $task->rate));
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
		$body .= elgg_echo('tasks:duration'). " : &nbsp;&nbsp;" .elgg_view('output/text',array('value' => $task->duration)).'<br/>';
		$body .= elgg_echo('tasks:suggested_rate'). " : &nbsp;&nbsp;" .elgg_view('output/text',array('value' => $task->rate));
		$body .= '</div>';
	}
	$body .= '<div class="task-rate">';
	$body .= '<div class="transferred-to-label">'.elgg_echo('jobsin:bidder').': ';
	if (is_int($group_bid->invitee)) {
	//Real Elgg user
	        $bidder = get_entity($group_bid->invitee);
		//$bidder .= elgg_view_entity($bidder, array('tiny'));
		$bidder_box = " ".$bidder->name.'</div>';
		$bidder_box .= " ".elgg_view_entity_icon($bidder, "small", array("use_hover" => "true"));
	} else {
	//External email
	        $bidder_box = $group_bid->invitee.'</div>';
	}

	$body .= $bidder_box;
	$body .= '<p>'.elgg_echo('jobsin:bid_status').': ';
	$body .= $group_bid->status.'</p>';
	$body .= "<div class='p'>".elgg_echo('jobsin:task_rate');
	$body .= "<div id='bid-rate'>".$group_bid->rate.'</div></div>';
	$body .= '</div>';
	$body .= '<div class="transferred-by">';
	$body .= '<div class="transferred-by-label">'.elgg_echo('jobsin:transferrer').': ';
	$transferrer = get_entity($group_bid->transferrer);
	//$body .= elgg_view_entity($transferrer, array('tiny'));
	$body .= " ".$transferrer->name;
	$body .= '</div>';
	$body .= " ".elgg_view_entity_icon($transferrer, "small", array("use_hover" => "true"));
	//$body .= $transferrer->name.'</p>';
	$body .= '</div>';
	if ($project_admin && $group_bid->rate && $group_bid->status != 'selected' && $group_bid->status != 'notselected') {
		$body .= elgg_view_form('projects/select_bid',array('class' => 'task-selection'),array('bid_guid' => $group_bid->getGUID()));
	}
	$body .= '</li>';
}
$body .= '</ul>';
echo $body;
