<?php
/**
 * Calendar for all Tasks
 *
 * @package ElggPages
 */


// access check for closed groups
group_gatekeeper();

$title = elgg_echo('jobsin:tasks:assigned:filter');
$filter_context ='assigned';

include elgg_get_plugins_path() . 'tasks/pages/calendar/common.php';

