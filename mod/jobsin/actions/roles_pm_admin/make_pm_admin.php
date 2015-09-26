<?php 

$guid = get_input('guid');
$user = get_entity($guid);

if (elgg_instanceof($user, 'user')) {
	$role = roles_get_role_by_name('pm_admin');
	if (elgg_instanceof($role, 'object', 'role')) {
		roles_set_role($role, $user);	
		system_message(sprintf(elgg_echo('roles_pm_admin:action:make_pm_admin:success'), $user->name));
	} else {
		register_error(elgg_echo('roles_pm_admin:action:make_pm_admin:failure'));
	}
} else {
	register_error(elgg_echo('roles_pm_admin:action:make_pm_admin:failure'));
}

forward(REFERER);
	
?>
