<?php
gatekeeper();

$group = $vars['entity'];
$user = elgg_get_logged_in_user_entity();
//elgg_set_page_owner_guid($user->guid);
$container_guid = $group->getGUID();
$page_type = get_input('page_type','tasks');

$options = array();
$options['page_type'] = $page_type;
//$options['limit'] = 100;
//$options['body_class'] = 'new-feed';
$filter = elgg_view('groups/profile/dashboard_sort_menu', array('group' => $group, 'page_type' => $page_type));
switch ($page_type) {
        case 'tasks':
		$content = elgg_list_entities(array( 'types' => 'object', 'subtypes' => 'task_top', 'container_guid' => $container_guid, 'full_view' => false,));
		if (!$content) {
        		$content = '<p>' . elgg_echo('tasks:none') . '</p>';
		}
		break;
        case 'members':
		$db_prefix = elgg_get_config("dbprefix");
		$options = array(
			'relationship' => 'member',
			'relationship_guid' => $group->guid,
			'inverse_relationship' => true,
			'types' => 'user',
			'joins' => array("JOIN {$db_prefix}users_entity u ON e.guid = u.guid"),
			'order_by' => 'u.name',
			'limit' => 20,
			'list_type' => 'gallery',
			'size' => 'medium',
			'gallery_class' => 'elgg-gallery-users',
		);
		if (! elgg_is_admin_logged_in()) {
			$options['wheres'] = array("(u.banned = 'no')");
		}
		$content = elgg_list_entities_from_relationship($options);
		break;
}
echo $filter.$content;
