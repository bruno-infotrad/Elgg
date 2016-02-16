<?php
$group = $vars['entity'];
$container_guid = $group->getGUID();
$title_link = elgg_extract('title', $vars, '');
$num_of_contributors = elgg_extract('contributors',$vars);
$num_of_tasks = elgg_extract('tasknum',$vars);
$start_date = elgg_extract('start_date',$vars);
$end_date = elgg_extract('end_date',$vars);
$backlog = elgg_extract('backlog', $vars);
$done = elgg_extract('done', $vars);
$ready = elgg_extract('ready', $vars);
$in_progress = elgg_extract('in_progress', $vars);
$jobsin_details = elgg_extract('jobsin_details', $vars,false);
if ($title_link === '') {
	if (isset($group->title)) {
		$text = $group->title;
	} else {
		$text = $group->name;
	}
	$params = array(
		'text' => elgg_get_excerpt($text, 100),
		'href' => $group->getURL(),
		'is_trusted' => true,
	);
	$title_link = elgg_view('output/url', $params);
}

$metadata = elgg_extract('metadata', $vars, '');
$subtitle = elgg_extract('subtitle', $vars, '');
$content = elgg_extract('content', $vars, '');

$tags = elgg_extract('tags', $vars, '');
if ($tags === '') {
	$tags = elgg_view('output/tags', array('tags' => $group->tags));
}

if ($metadata) {
	echo $metadata;
}
if ($jobsin_details) {
	echo "<div class=\"jobsin-tasks jobsin-done\"><div class=\"jobsin-tasks-nums\">$done</div></div>";
	echo "<div class=\"jobsin-tasks jobsin-inprogress\"><div class=\"jobsin-tasks-nums\">$in_progress</div></div>";
	echo "<div class=\"jobsin-tasks jobsin-ready\"><div class=\"jobsin-tasks-nums\">$ready</div></div>";
	echo "<div class=\"jobsin-tasks jobsin-backlog\"><div class=\"jobsin-tasks-nums\">$backlog</div></div>";
	if ($num_of_tasks > 1) {
		$numtaskstring = elgg_echo('jobsin:task:num_of_tasks',array($num_of_tasks));
	} else {
		$numtaskstring = elgg_echo('jobsin:task:num_of_task',array($num_of_tasks));
	}
	if ($num_of_contributors > 1) {
		$numcontstring = elgg_echo('jobsin:task:num_of_contributors',array($num_of_contributors));
	} else {
		$numcontstring = elgg_echo('jobsin:task:num_of_contributor',array($num_of_contributors));
	}
	echo "<div class=\"jobsin-dates\">".elgg_echo('jobsin:project:start_date').$start_date.'<br/>'.elgg_echo('jobsin:project:end_date').$end_date."</div>";
	echo "<div class=\"jobsin-tasknum\">".$numtaskstring.'<br/>'.$numcontstring."</div>";
}
if ($title_link) {
	echo "<h3>$title_link</h3>";
}
echo "<div class=\"elgg-subtext\">$subtitle</div>";
echo $tags;

echo elgg_view('object/summary/extend', $vars);

if ($content) {
	echo "<div class=\"elgg-content\">$content</div>";
}
