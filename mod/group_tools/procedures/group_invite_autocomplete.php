<?php 
	global $CONFIG;

	$q = sanitize_string(get_input("q"));
	$current_users = sanitize_string(get_input("user_guids"));
	$limit = (int) get_input("limit", 50);
	$group_guid = (int) get_input("group_guid", 0);
	$relationship = sanitize_string(get_input("relationship", "none"));
	
	$include_self = get_input("include_self", false);
	if(!empty($include_self)){
		$include_self = true;
	}
	
	$result = array();
	
	if(($user = elgg_get_logged_in_user_entity()) && !empty($q) && !empty($group_guid)){
		// show hidden (unvalidated) users
		$hidden = access_get_show_hidden_status();
		access_show_hidden_entities(true);
		
		if($relationship == "site"){
			$dbprefix = elgg_get_config("dbprefix");
			
			// find existing users
			$query_options = array(
				"type" => "user",
				"limit" => $limit,
				"joins" => array("JOIN {$dbprefix}users_entity u ON e.guid = u.guid"),
				"wheres" => array("(u.name LIKE '%{$q}%' OR u.username LIKE '%{$q}%')", "u.banned = 'no'"),
				"order_by" => "u.name asc"
			);
			
			if(!$include_self){
				if(empty($current_users)){
					$current_users = $user->getGUID();
				} else {
					$current_users .= "," . $user->getGUID();
				}
			}
			
			if(!empty($current_users)){
				$query_options["wheres"][] = "e.guid NOT IN (" . $current_users . ")";
			}
			
			if($relationship == "friends"){
				$query_options["relationship"] = "friend";
				$query_options["relationship_guid"] = $user->getGUID();
			} elseif($relationship == "site"){
				$query_options["relationship"] = "member_of_site";
				$query_options["relationship_guid"] = elgg_get_site_entity()->getGUID();
				$query_options["inverse_relationship"] = true;
			}
			
			if($entities = elgg_get_entities_from_relationship($query_options)){
				foreach($entities as $entity){
					if(!check_entity_relationship($entity->getGUID(), "member", $group_guid)){
						$result[] = array("type" => "user", "value" => $entity->getGUID(),"content" => "<img src='" . $entity->getIconURL("tiny") . "' /> " . $entity->name, "name" => $entity->name);
					}	
				}
			}
		} elseif ($relationship == "competences"){
			$dbprefix = elgg_get_config("dbprefix");



        $params['joins'] = array(
                "JOIN {$dbprefix}users_entity ue ON e.guid = ue.guid",
                "JOIN {$dbprefix}metadata md on e.guid = md.entity_guid",
                "JOIN {$dbprefix}metastrings msv ON n_table.value_id = msv.id"
        );

        // username and display name
        $fields = array('username', 'name');
        //$where = search_get_where_sql('ue', $fields, $params, FALSE);

        // profile fields
        $profile_fields = array_keys(elgg_get_config('profile_fields'));

        // get the where clauses for the md names
        // can't use egef_metadata() because the n_table join comes too late.
        $clauses = elgg_entities_get_metastrings_options('metadata', array(
                'metadata_names' => $profile_fields,
        ));

        $params['joins'] = array_merge($clauses['joins'], $params['joins']);
        // no fulltext index, can't disable fulltext search in this function.
        // $md_where .= " AND " . search_get_where_sql('msv', array('string'), $params, FALSE);
        $md_where = "(({$clauses['wheres'][0]}) AND msv.string LIKE '%{$q}%')";

        $params['wheres'] = array("$md_where");

        // override subtype -- All users should be returned regardless of subtype.
        $params['subtype'] = ELGG_ENTITIES_ANY_VALUE;
        $params['count'] = true;
        $count = elgg_get_entities($params);

        // no need to continue if nothing here.
        if (!$count) {
                return array('entities' => array(), 'count' => $count);
        }

        $params['count'] = FALSE;
        $params['order_by'] = search_get_order_by_sql('e', 'ue', $params['sort'], $params['order']);
        //$entities = elgg_get_entities($params);

			// find existing users
			/*
			$query_options = array(
				"type" => "user",
				"limit" => $limit,
				"joins" => array("JOIN {$dbprefix}users_entity u ON e.guid = u.guid","JOIN {$db_prefix}metadata md on e.guid = md.entity_guid","JOIN {$db_prefix}metastrings msv ON n_table.value_id = msv.id"),
				"wheres" => array("(u.name LIKE '%{$q}%' OR u.username LIKE '%{$q}%')", "u.banned = 'no'"),
				"order_by" => "u.name asc"
			);
			
			if(!$include_self){
				if(empty($current_users)){
					$current_users = $user->getGUID();
				} else {
					$current_users .= "," . $user->getGUID();
				}
			}
			
			if(!empty($current_users)){
				$query_options["wheres"][] = "e.guid NOT IN (" . $current_users . ")";
			}
			
			if($relationship == "friends"){
				$query_options["relationship"] = "friend";
				$query_options["relationship_guid"] = $user->getGUID();
			} elseif($relationship == "site"){
				$query_options["relationship"] = "member_of_site";
				$query_options["relationship_guid"] = elgg_get_site_entity()->getGUID();
				$query_options["inverse_relationship"] = true;
			}
			*/
			if($entities = elgg_get_entities($params)){
				foreach($entities as $entity){
					if(!check_entity_relationship($entity->getGUID(), "member", $group_guid)){
						$result[] = array("type" => "user", "value" => $entity->getGUID(),"content" => "<img src='" . $entity->getIconURL("tiny") . "' /> " . $entity->name . $entity->skills, "name" => $entity->name);
					}	
				}
			}
		} elseif ($relationship == "email"){
			// invite by email
			$regexpr = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/";
			if(preg_match($regexpr, $q)){
				if($users = get_user_by_email($q)){
					foreach($users as $user){
						// @todo check for group relationship
						
						$result[] = array("type" => "user", "value" => $user->getGUID(),"content" => "<img src='" . $user->getIconURL("tiny") . "' /> " . $user->name, "name" => $user->name);
					}
				} else {
					$result[] = array("type" => "email", "value" => $q, "content" => $q);
				}
			}
		}
		
		// restore hidden users
		access_show_hidden_entities($hidden);
	}
	
	header("Content-Type: application/json");
	echo json_encode(array_values($result));
	
	exit();
