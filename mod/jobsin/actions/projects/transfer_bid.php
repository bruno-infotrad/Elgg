<?php
/**
 * Invite a user to join a group
 *
 * @package ElggGroups
 */
elgg_load_library('elgg:jobsin');
//elgg_log('INVITE','NOTICE');

$logged_in_user = elgg_get_logged_in_user_entity();
$bid_guid = (int) get_input("bid_guid");
$user_guids = get_input("user_guid");
if (!empty($user_guids) && !is_array($user_guids)) {
	$user_guids = array($user_guids);
}

/*
$end_date = get_input('end_date',null);
$task_guids = get_input('task_guid',null);
if ($task_guids && !is_array($task_guids)) {
	$task_guids = array($task_guids);
}
if ($end_date) {
	$date = explode('-',$end_date);
	$end_date = mktime(0,0,1,$date[1],$date[2],$date[0]);
}
*/
$group_guid = (int) get_input("group_guid");
$text = get_input("comment");

$emails = get_input("user_guid_email");
if (!empty($emails) && !is_array($emails)) {
	$emails = array($emails);
}

$group = get_entity($group_guid);

if ((!empty($user_guids) || !empty($emails) || !empty($csv)) && !empty($group)) {
	if ($group instanceof ElggGroup) {
		// show hidden (unvalidated) users
		$hidden = access_get_show_hidden_status();
		access_show_hidden_entities(true);
		
		// counters
		$already_invited = 0;
		$invited = 0;
		$member = 0;
		$join = 0;
		
		// invite existing users
		if (!empty($user_guids)) {
			// invite users
			foreach ($user_guids as $u_id) {
				$user = get_user($u_id);
				if (!empty($user)) {
					if (!$group->isMember($user)) {
						if (!check_entity_relationship($group->getGUID(), "invited", $user->getGUID())) {
							if (projects_transfer_bid_to_user($group, $user, $text, $bid_guid)) {
								$invited++;
							}
						} else {
							// user was already invited
							$already_invited++;
						}
					} else {
						$member++;
					}
				}
			}
		}
		
		// Invite member by e-mail address
		if (!empty($emails)) {
			foreach ($emails as $email) {
				$invite_result = projects_transfer_bid_via_email($group, $email, $text, $bid_guid);
				if ($invite_result === true) {
					$invited++;
				} elseif ($invite_result === null) {
					$already_invited++;
				}
			}
		}
		
		// invite from csv
		/* disabled for now
		if (!empty($csv)) {
			$file_location = $_FILES["csv"]["tmp_name"];
			$fh = fopen($file_location, "r");
			
			if (!empty($fh)) {
				while (($data = fgetcsv($fh, 0, ";")) !== false) {
					//
					// data structure
					// data[0] => displayname
					// data[1] => e-mail address
					//
					$email = "";
					if (isset($data[1])) {
						$email = trim($data[1]);
					}
					
					if (!empty($email) && is_email_address($email)) {
						$users = get_user_by_email($email);
						if (!empty($users)) {
							// found a user with this email on the site, so invite (or add)
							$user = $users[0];
							
							if (!$group->isMember($user)) {
								if (!$adding) {
									if (!check_entity_relationship($group->getGUID(), "invited", $user->getGUID()) || $resend) {
										// invite user
										if (projects_invite_user($group, $user, $text, $resend, $task_guids, $end_date)) {
											$invited++;
										}
									} else {
										// user was already invited
										$already_invited++;
									}
								} else {
									if (group_tools_add_user($group, $user, $text)) {
										$join++;
									}
								}
							} else {
								$member++;
							}
						} else {
							// user not found so invite based on email address
							$invite_result = projects_invite_email($group, $email, $text, $resend, $task_guids, $end_date);
							
							if ($invite_result === true) {
								$invited++;
							} elseif ($invite_result === null) {
								$already_invited++;
							}
						}
					}
				}
			}
		}
		*/
		
		// restore hidden users
		access_show_hidden_entities($hidden);
		
		// which message to show
		if (!empty($invited) || !empty($join)) {
			system_message(elgg_echo("group_tools:action:transfer:success", array($invited, $already_invited, $member)));
		} else {
			register_error(elgg_echo("group_tools:action:transfer:error", array($already_invited, $member)));
		}
	} else {
		register_error(elgg_echo("group_tools:action:error:edit"));
	}
} else {
	register_error(elgg_echo("group_tools:action:error:input"));
}

forward(REFERER);
