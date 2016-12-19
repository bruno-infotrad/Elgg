<?php
/**
 * jQuery procedure to fill an autocomplete dropdown
 */
global $CONFIG;

$q = sanitize_string(get_input("q"));
$current_users = sanitize_string(get_input("user_guids"));
$limit = (int) get_input("limit", 50);
$group_guid = (int) get_input("group_guid", 0);
$relationship = sanitize_string(get_input("relationship", "none"));

$include_self = get_input("include_self", false);
if (!empty($include_self)) {
	$include_self = true;
}

$user = elgg_get_logged_in_user_entity();
$result = array();

if (!empty($user) && !empty($q) && !empty($group_guid)) {
	// show hidden (unvalidated) users
	$hidden = access_get_show_hidden_status();
	access_show_hidden_entities(true);
	
	if ($relationship == "friends"||$relationship == "site") {
		$dbprefix = elgg_get_config("dbprefix");
		
		// find existing users
		$query_options = array(
			"type" => "user",
			"limit" => $limit,
			"joins" => array("JOIN {$dbprefix}users_entity u ON e.guid = u.guid"),
			"wheres" => array("(u.name LIKE '%{$q}%' OR u.username LIKE '%{$q}%')", "u.banned = 'no'"),
			"order_by" => "u.name asc"
		);
		
		if (!$include_self) {
			if (empty($current_users)) {
				$current_users = $user->getGUID();
			} else {
				$current_users .= "," . $user->getGUID();
			}
		}
		
		if (!empty($current_users)) {
			$query_options["wheres"][] = "e.guid NOT IN (" . $current_users . ")";
		}
		
		if ($relationship == "friends") {
			$query_options["relationship"] = "friend";
			$query_options["relationship_guid"] = $user->getGUID();
		} elseif ($relationship == "site") {
			$query_options["relationship"] = "member_of_site";
			$query_options["relationship_guid"] = elgg_get_site_entity()->getGUID();
			$query_options["inverse_relationship"] = true;
		}
		
		$entities = elgg_get_entities_from_relationship($query_options);
		if (!empty($entities)) {
			foreach ($entities as $entity) {
				if (!check_entity_relationship($entity->getGUID(), "member", $group_guid)) {
					$result[] = array(
						"type" => "user",
						"value" => $entity->getGUID(),
						"label" => $entity->name,
						"content" => "<img src='" . $entity->getIconURL("tiny") . "' /> " . $entity->name,
						"name" => $entity->name
					);
				}
			}
		}
	} elseif ($relationship == "skills") {
		$limit = (int) get_input("limit", 50);
		$result = array();
		$params['limit'] = $limit;
		$params['tag_names'] = array('skills');
		$params['count'] = FALSE;
		$params['wheres'] = array("msv.string like '%$q%'");
		$returned_tags = elgg_get_tags($params);
		if ($returned_tags) {
			foreach($returned_tags as $returned_tag){
				$tag_values[] = $returned_tag->tag;
			}
			$query_options["type"] = 'user';
			$query_options["type"] = 'user';
			$query_options["metadata_name_value_pairs"] = array('name' => 'skills','value' => $tag_values);
			if (!$include_self) {
				if (empty($current_users)) {
					$current_users = $user->getGUID();
				} else {
					$current_users .= "," . $user->getGUID();
				}
			}
			
			if (!empty($current_users)) {
				$query_options["wheres"][] = "e.guid NOT IN (" . $current_users . ")";
			}
			$entities = elgg_get_entities_from_metadata($query_options);
			if (!empty($entities)) {
				foreach ($entities as $entity) {
					if (!check_entity_relationship($entity->getGUID(), "member", $group_guid)&& !elgg_is_admin_user($entity->getGUID())) {
						$entity_skills='<div class="skill">';
						if (! is_array($entity->skills)) {
							if (stripos($entity->skills,$q)) {
								$entity_skills="$entity->skills ";
							}
						} else {
							foreach ($entity->skills as $skills){
								if (stripos($skills,$q)) {
									$entity_skills.="$skills ";
								}
							}
						}
						$entity_skills.='</div>';
						$result[] = array(
							"type" => "user",
							"value" => $entity->getGUID(),
							"label" => $entity->name,
							"content" => "<img src='" . $entity->getIconURL("tiny") . "' /> " . $entity->name,
							"extended_content" => "<img src='" . $entity->getIconURL("tiny") . "' /><div class='name_in_skill'>" . $entity->name . "</div> ".$entity_skills,
							"name" => $entity->name
						);
					}
				}
			}
		}
	} elseif ($relationship == "email") {
		// invite by email
		$regexpr = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/";
		if (preg_match($regexpr, $q)) {
			$users = get_user_by_email($q);
			if (!empty($users)) {
				foreach ($users as $user) {
					// @todo check for group relationship
					$result[] = array(
						"type" => "user",
						"value" => $user->getGUID(),
						"label" => $user->name,
						"content" => "<img src='" . $user->getIconURL("tiny") . "' /> " . $user->name,
						"name" => $user->name
					);
				}
			} else {
				$result[] = array(
					"type" => "email",
					"value" => $q,
					"label" => $q,
					"content" => $q
				);
			}
		}
	}
	
	// restore hidden users
	access_show_hidden_entities($hidden);
}

header("Content-Type: application/json");
echo json_encode(array_values($result));

exit();
