<?php
gatekeeper();

$dbprefix = elgg_get_config('dbprefix');
$user = elgg_get_logged_in_user_entity();
elgg_set_page_owner_guid($user->guid);
$page_type = get_input('page_type','projects');

$title = elgg_echo('dashboard');

$options = array();
$options['page_type'] = $page_type;
//$options['limit'] = 100;
//$options['body_class'] = 'new-feed';
$filter = elgg_view('jobsin/dashboard_sort_menu', array('page_type' => $page_type));
switch ($page_type) {
        case 'projects':
		if (! elgg_is_admin_logged_in()) {
        	        $owner_guid = elgg_get_page_owner_guid();
		}
        	$content = elgg_list_entities(array(
        	        'type' => 'group',
        	        'owner_guid' => $owner_guid,
        	        'joins' => array("JOIN {$dbprefix}groups_entity ge ON e.guid = ge.guid"),
        	        'order_by' => 'ge.name ASC',
        	        'full_view' => false,
        	        'no_results' => elgg_echo('groups:none'),
        	        'distinct' => false,
        	));
		break;
        case 'tasks':
		if (! elgg_is_admin_logged_in()) {
                	$owner_guid = elgg_get_page_owner_guid();
		}
		$content = elgg_list_entities(array( 'types' => 'object', 'subtypes' => 'task_top', 'owner_guid' => $owner_guid, 'full_view' => false,));
		if (!$content) {
        		$content = '<p>' . elgg_echo('tasks:none') . '</p>';
		}
		break;
}
$params = array(
	'content' => $content,
	'title' => $title,
	'filter' => $filter,
);
$body = elgg_view_layout('content', $params);
echo elgg_view_page($title, $body);
