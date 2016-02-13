<?php
$group = $vars['entity'];
$container_guid = $group->getGUID();
$title_link = elgg_extract('title', $vars, '');
$backlog = elgg_extract('backlog', $vars);
$done = elgg_extract('done', $vars);
$ready = elgg_extract('ready', $vars);
$in_progress = elgg_extract('in_progress', $vars);
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
/*
$backlog = elgg_get_entities_from_metadata(array(
	'type' => 'object',
	'subtypes' => array('task','task_top'),
	'container_guid' => $container_guid,
	'limit' => false,
	'count' => true,
	'metadata_name_value_pairs' => array( 'name' => 'end_date', 'value' => time(),'operand' => '<=')
));
*/
echo "<div class=\"jobsin-tasks jobsin-done\">$done</div>";
echo "<div class=\"jobsin-tasks jobsin-inprogress\">$in_progress</div>";
echo "<div class=\"jobsin-tasks jobsin-ready\">$ready</div>";
echo "<div class=\"jobsin-tasks jobsin-backlog\">$backlog</div>";
if ($title_link) {
	echo "<h3>$title_link</h3>";
}
echo "<div class=\"elgg-subtext\">$subtitle</div>";
echo $tags;

echo elgg_view('object/summary/extend', $vars);

if ($content) {
	echo "<div class=\"elgg-content\">$content</div>";
}
