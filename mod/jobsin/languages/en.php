<?php
/**
 * Basic Light english
 * 
 */
 
$english = array(
	
	// Misc
	'jobsin:copyright'     => 'Copyright &copy; ',
	'jobsin:elgg'      	=> 'Powered by Elgg',
	'jobsin:thewire'		=> 'The Wire:',
	'jobsin:tooltip'		=> 'Visit Elggzone',
	'jobsin:welcome'		=> 'Hello, %s',
	'jobsin:task:num_of_task'		=> '%s task',
	'jobsin:task:num_of_tasks'		=> '%s tasks',
	'jobsin:task:num_of_contributor'		=> '%s contributor',
	'jobsin:task:num_of_contributors'		=> '%s contributors',
	'jobsin:tasks:assigned:filter'		=> 'Assigned',
	'jobsin:tasks:assigned'		=> 'Assigned Tasks',
	'jobsin:tasks:assigned_to'		=> 'Assigned to %s',
	'jobsin:project:start_date'		=> 'Start Date: ',
	'jobsin:project:end_date'		=> 'End Date: &nbsp;&nbsp;',
	'jobsin:in_project'		=> ' in project %s',
	'jobsin:project_manager'		=> 'Entrepreneur',
	'jobsin:project:invite:users:task' => 'Select task(s) you want the contributor to submit a bid for',
	'jobsin:project:invite:notask' => 'No specific task',
	'jobsin:view_bid' => 'View your bid',
	'jobsin:submit_bid' => 'Submit a bid',
	'jobsin:task_rate' => 'Hourly rate ($)',
	'tasks:duration' => 'Duration (days)',
	'tasks:rate' => 'Rate (C$)',
	'tasks:suggested_rate' => 'Suggested rate (C$)',
	'task:lowest_rate' => 'Lowest submitted rate',
	'tasks:notallowed' => 'You are not allowed to modify this task parameter',
	'projects:bid_invitations' => 'Invitations to bid',
	'projects:missing_parameter' => 'Missing parameter',
	'jobsin:bidder' => 'Bidder',
	'jobsin:bid_status' => 'Bid Status',
	'jobsin:edit_bid' => 'Edit Bid',
	'jobsin:submission:end_date' => 'Submission closing date',
	'projects:bid_submissions' => 'Bid Submissions',
	'projects:bid_submissions:pending' => 'Pending submissions',
	'jobsin:project:no_bids' => 'No pending bids',
	'jobsin:submit_bid:confirm' => 'Confirm submission',
	'jobsin:bid:submitted' => 'Bid submitted',
	'jobsin:bid:selected' => 'Bid selected',
	'jobsin:bid:notselected' => 'Bid not selected',
	'jobsin:bid:deleted' => 'Bid deleted',
	// notifications
	'project:invite:bid:subject' => "You have been invited to bid on task '%s'",
	'project:invite:bid:body' => "Hello %s,
%s has invited you to bid on task '%s' for project '%s'. 
%s
Click on the the link below to submit a bid.
%s",
	'bid:submitted:notify:summary' => 'Bid  %s submitted',
	'bid:submitted:notify:subject' => "%s has submitted his bid for task '%s'",
	'bid:submitted:notify:body' => "Hello, %s! %s has submitted his bid for task '%s'. Click on the link below to see the bid.
%s",
	'bid:selected:notify:summary' => "Bid  for task '%s' selected",
	'bid:selected:notify:subject' => "Your bid for task '%s' has been selected",
	'bid:selected:notify:body' => "Goods news, %s! Your bid for task '%s' has been selected.
You can update your task at %s",
	'task:closed:notify:subject' => "User %s has closed task '%s'",
	'task:closed:notify:body' => "Hello %s,
User %s has closed the task '%s'. Click on the link below to go to the task.
%s",

	// Profile
	'profile:skills' => 'Skills',

	// Settings
	'admin:settings:jobsin'	=> 'Basic Light',
	'elggzone:panel'				=> 'Theme Options',
		
	'jobsin:info:general'		=> 'General:',
	'jobsin:info:modules'		=> 'Select sidebar modules:',
	'jobsin:info:sidebar'		=> 'Sidebar:',
	'jobsin:label:columns'		=> 'Select your preferred layout',
	'jobsin:label:custom'		=> 'Custom Content?',
	'jobsin:label:html'		=> 'Add Custom Content:',
	'jobsin:label:menu' 		=> 'Owner Menu',
	'jobsin:label:show_icon'	=> 'Profile Icon',
	'jobsin:label:theme' 		=> 'Select your preferred Color Scheme',
	'jobsin:label:thewire'		=> 'Do you want to display The Wire on activity page?',
	'jobsin:option:activity' 	=> 'Activity Stream',
	'jobsin:option:sidebar:left' => 'Left Sidebar',
	'jobsin:option:sidebar:right' => 'Right Sidebar',
	'jobsin:option:two'		=> 'Two Columns',
	'jobsin:option:three'		=> 'Three Columns',
	'jobsin:option:default'	=> 'Default',
	'jobsin:option:palesky'	=> 'Pale Sky',
	'jobsin:option:bronco'		=> 'Bronco',
	'jobsin:tab:general'		=> 'General',	
	'jobsin:tab:sidebar'		=> 'Sidebar',					
	
	// demo	
	'jobsin:demo:title'	=> 'Info',
	//'jobsin:demo:text'		=> '<p>Pass any content from the included demo file to Elgg secondary sidebar.</p><p>For example Google AdSense.</p>',
	
//Rewrite group labels
	'tasks:group' => "Project tasks",
	/**
	 * Menu items and titles
	 */
	'groups' => "Projects",
	'groups:owned' => "Projects I own",
	'groups:owned:user' => 'Projects %s owns',
	'groups:yours' => "My projects",
	'groups:user' => "%s's projects",
	'groups:all' => "All projects",
	'groups:add' => "Create a new project",
	'projects:add' => "Create a new project",
	'projects:addedtoproject' => 'Successfully added user %s to the project',
	'groups:edit' => "Edit project",
	'projects:edit' => "Edit project",
	'groups:delete' => 'Delete project',
	'projects:delete' => 'Delete project',
	'groups:membershiprequests' => 'Manage join requests',
	'groups:membershiprequests:pending' => 'Manage join requests (%s)',
	'projects:membershiprequests' => 'Manage project invitations',
	'projects:membershiprequests:pending' => 'Manage project invitation (%s)',
	'groups:invitations' => 'Project invitations',
	'groups:invitations:pending' => 'Project invitations (%s)',

	'groups:icon' => 'Project icon (leave blank to leave unchanged)',
	'groups:name' => 'Project name',
	'groups:username' => 'Project short name (displayed in URLs, alphanumeric characters only)',
	'groups:description' => 'Description',
	'groups:briefdescription' => 'Brief description',
	'groups:interests' => 'Tags',
	'groups:website' => 'Website',
	'groups:members' => 'Project members',
	'groups:my_status' => 'My status',
	'groups:my_status:group_owner' => 'You own this project',
	'groups:my_status:group_member' => 'You are in this project',
	'groups:subscribed' => 'Project notifications on',
	'groups:unsubscribed' => 'Project notifications off',

	'groups:members:title' => 'Members of %s',
	'groups:members:more' => "View all members",
	'groups:membership' => "Project membership permissions",
	'groups:access' => "Access permissions",
	'groups:owner' => "Owner",
	'groups:owner:warning' => "Warning: if you change this value, you will no longer be the owner of this project.",
	'groups:widget:num_display' => 'Number of projects to display',
	'groups:widget:membership' => 'Project membership',
	'groups:widgets:description' => 'Display the projects you are a member of on your profile',
	'groups:noaccess' => 'No access to project',
	'groups:permissions:error' => 'You do not have the permissions for this',
	'groups:ingroup' => 'in the project',
	'groups:cantcreate' => 'You can not create a project. Only admins can.',
	'groups:cantedit' => 'You can not edit this project',
	'groups:saved' => 'Project saved',
	'groups:featured' => 'Featured projects',
	'groups:makeunfeatured' => 'Unfeature',
	'groups:makefeatured' => 'Make featured',
	'groups:featuredon' => '%s is now a featured project.',
	'groups:unfeatured' => '%s has been removed from the featured projects.',
	'groups:featured_error' => 'Invalid project.',
	'groups:joinrequest' => 'Request membership',
	'groups:join' => 'Join project',
	'groups:leave' => 'Leave project',
	'projects:leave' => 'Leave project',
	'projects:invite' => 'Invite contributors',
	'projects:invite:title' => 'Invite contributors to this project',
	'groups:inviteto' => "Invite friends to '%s'",
	'groups:nofriends' => "You have no friends left who have not been invited to this project.",
	'groups:nofriendsatall' => 'You have no friends to invite!',
	'groups:viagroups' => "via projects",
	'groups:group' => "Project",
	'groups:search:tags' => "tag",
	'groups:search:title' => "Search for projects tagged with '%s'",
	'groups:search:none' => "No matching projects were found",
	'groups:search_in_group' => "Search in this project",
	'groups:acl' => "Project: %s",

	'discussion:notification:topic:subject' => 'New project discussion post',
	'groups:notification' =>
'%s added a new discussion topic to %s:

%s
%s

View and reply to the discussion:
%s
',

	'discussion:notification:reply:body' =>
'%s replied to the discussion topic %s in the project %s:

%s

View and reply to the discussion:
%s
',

	'groups:activity' => "Project activity",
	'groups:enableactivity' => 'Enable project activity',
	'groups:activity:none' => "There is no project activity yet",

	'groups:notfound' => "Project not found",
	'groups:notfound:details' => "The requested project either does not exist or you do not have access to it",

	'groups:requests:none' => 'There are no current membership requests.',

	'groups:invitations:none' => 'There are no current invitations.',

	'item:object:groupforumtopic' => "Discussion topics",

	'groupforumtopic:new' => "Add discussion post",

	'groups:count' => "projects created",
	'groups:open' => "open project",
	'groups:closed' => "closed project",
	'groups:member' => "members",
	'groups:searchtag' => "Search for projects by tag",


	'groups:more' => 'More projects',
	'groups:none' => 'No projects',
	'blog:group' => 'Project blogs',


	/*
	 * Access
	 */
	'groups:access:private' => 'Closed - Users must be invited',
	'groups:access:public' => 'Open - Any user may join',
	'groups:access:group' => 'Project members only',
	'groups:closedgroup' => 'This project has a closed membership.',
	'groups:closedgroup:request' => 'To ask to be added, click the "request membership" menu link.',
	'groups:visibility' => 'Who can see this project?',

	/*
	Project tools
	*/
	'groups:enableforum' => 'Enable project discussion',
	'groups:yes' => 'yes',
	'groups:no' => 'no',
	'groups:lastupdated' => 'Last updated %s by %s',
	'groups:lastcomment' => 'Last comment %s by %s',

	/*
	Project discussion
	*/
	'discussion' => 'Discussion',
	'discussion:add' => 'Add discussion topic',
	'discussion:latest' => 'Latest discussion',
	'discussion:group' => 'Project discussion',
	'discussion:none' => 'No discussion',
	'discussion:reply:title' => 'Reply by %s',

	'discussion:topic:created' => 'The discussion topic was created.',
	'discussion:topic:updated' => 'The discussion topic was updated.',
	'discussion:topic:deleted' => 'Discussion topic has been deleted.',

	'discussion:topic:notfound' => 'Discussion topic not found',
	'discussion:error:notsaved' => 'Unable to save this topic',
	'discussion:error:missing' => 'Both title and message are required fields',
	'discussion:error:permissions' => 'You do not have permissions to perform this action',
	'discussion:error:notdeleted' => 'Could not delete the discussion topic',

	'discussion:reply:deleted' => 'Discussion reply has been deleted.',
	'discussion:reply:error:notdeleted' => 'Could not delete the discussion reply',

	'reply:this' => 'Reply to this',

	'group:replies' => 'Replies',
	'groups:forum:created' => 'Created %s with %d comments',
	'groups:forum:created:single' => 'Created %s with %d reply',
	'groups:forum' => 'Discussion',
	'groups:addtopic' => 'Add a topic',
	'groups:forumlatest' => 'Latest discussion',
	'groups:latestdiscussion' => 'Latest discussion',
	'groups:newest' => 'Newest',
	'groups:popular' => 'Popular',
	'groupspost:success' => 'Your reply was succesfully posted',
	'groups:alldiscussion' => 'Latest discussion',
	'groups:edittopic' => 'Edit topic',
	'groups:topicmessage' => 'Topic message',
	'groups:topicstatus' => 'Topic status',
	'groups:reply' => 'Post a comment',
	'groups:topic' => 'Topic',
	'groups:posts' => 'Posts',
	'groups:lastperson' => 'Last person',
	'groups:when' => 'When',
	'grouptopic:notcreated' => 'No topics have been created.',
	'groups:topicopen' => 'Open',
	'groups:topicclosed' => 'Closed',
	'groups:topicresolved' => 'Resolved',
	'grouptopic:created' => 'Your topic was created.',
	'groupstopic:deleted' => 'The topic has been deleted.',
	'groups:topicsticky' => 'Sticky',
	'groups:topicisclosed' => 'This discussion is closed.',
	'groups:topiccloseddesc' => 'This discussion is closed and is not accepting new comments.',
	'grouptopic:error' => 'Your project topic could not be created. Please try again or contact a system administrator.',
	'groups:forumpost:edited' => "You have successfully edited the forum post.",
	'groups:forumpost:error' => "There was a problem editing the forum post.",


	'groups:privategroup' => 'This project is closed. Requesting membership.',
	'groups:notitle' => 'Projects must have a title',
	'groups:cantjoin' => 'Can not join project',
	'groups:cantleave' => 'Could not leave project',
	'groups:removeuser' => 'Remove from project',
	'groups:cantremove' => 'Cannot remove user from project',
	'groups:removed' => 'Successfully removed %s from project',
	'groups:addedtogroup' => 'Successfully added the user to the project',
	'groups:joinrequestnotmade' => 'Could not request to join project',
	'groups:joinrequestmade' => 'Requested to join project',
	'groups:joined' => 'Successfully joined project!',
	'groups:left' => 'Successfully left project',
	'groups:notowner' => 'Sorry, you are not the owner of this project.',
	'groups:notmember' => 'Sorry, you are not a member of this project.',
	'groups:alreadymember' => 'You are already a member of this project!',
	'groups:userinvited' => 'User has been invited.',
	'groups:usernotinvited' => 'User could not be invited.',
	'groups:useralreadyinvited' => 'User has already been invited',
	'groups:invite:subject' => "%s you have been invited to join %s!",
	'groups:updated' => "Last reply by %s %s",
	'groups:started' => "Started by %s",
	'groups:joinrequest:remove:check' => 'Are you sure you want to remove this join request?',
	'groups:invite:remove:check' => 'Are you sure you want to remove this invitation?',
	'groups:invite:body' => "Hi %s,

%s invited you to join the '%s' project. Click below to view your invitations:

%s",

	'groups:welcome:subject' => "Welcome to the %s project!",
	'groups:welcome:body' => "Hi %s!

You are now a member of the '%s' project! Click below to begin posting!

%s",

	'groups:request:subject' => "%s has requested to join %s",
	'groups:request:body' => "Hi %s,

%s has requested to join the '%s' project. Click below to view their profile:

%s

or click below to view the project's join requests:

%s",

	/*
		Forum river items
	*/

	'river:create:group:default' => '%s created the project %s',
	'river:join:group:default' => '%s joined the project %s',
	'river:create:object:groupforumtopic' => '%s added a new discussion topic %s',
	'river:reply:object:groupforumtopic' => '%s replied on the discussion topic %s',
	
	'groups:nowidgets' => 'No widgets have been defined for this project.',


	'groups:widgets:members:title' => 'Project members',
	'groups:widgets:members:description' => 'List the members of a project.',
	'groups:widgets:members:label:displaynum' => 'List the members of a project.',
	'groups:widgets:members:label:pleaseedit' => 'Please configure this widget.',

	'groups:widgets:entities:title' => "Objects in project",
	'groups:widgets:entities:description' => "List the objects saved in this project",
	'groups:widgets:entities:label:displaynum' => 'List the objects of a project.',
	'groups:widgets:entities:label:pleaseedit' => 'Please configure this widget.',

	'groups:forumtopic:edited' => 'Forum topic successfully edited.',

	'groups:allowhiddengroups' => 'Do you want to allow private (invisible) projects?',
	'groups:whocancreate' => 'Who can create new projects?',
	'notifications:subscriptions:changesettings:groups' => 'Project notifications',
	'group_tools:notifications:title' => "Project notifications",
	'notifications:subscriptions:groups:description' => 'To receive notifications when new content is added to a project you are a member of, find it below and select the notification method(s) you would like to use.',
	/**
	 * Action messages
	 */
	'group:deleted' => 'Project and project contents deleted',
	'group:notdeleted' => 'Project could not be deleted',

	'group:notfound' => 'Could not find the project',
	'grouppost:deleted' => 'Project posting successfully deleted',
	'grouppost:notdeleted' => 'Project posting could not be deleted',
	'groupstopic:deleted' => 'Topic deleted',
	'groupstopic:notdeleted' => 'Topic not deleted',
	'grouptopic:blank' => 'No topic',
	'grouptopic:notfound' => 'Could not find the topic',
	'grouppost:nopost' => 'Empty post',
	'groups:deletewarning' => "Are you sure you want to delete this project? There is no undo!",

	'groups:invitekilled' => 'The invite has been deleted.',
	'groups:joinrequestkilled' => 'The join request has been deleted.',

	// ecml
	'groups:ecml:discussion' => 'Project Discussions',
	'groups:ecml:groupprofile' => 'Project profiles',
	'group_tools:group:invite:users:description' => "Start typing a site member name and select him/her from the list",
	'group_tools:group:invite:email:description' => "Enter a valid e-mail address",

	// Roles
	'login:user_not_pm' => 'User is not allowed to log in as a project administrator',
	'roles_pm_admin:role:title' => 'Project Administrator',
	'roles_pm_admin:action:make_pm_admin' => 'Add as project administrator',
	'roles_pm_admin:action:make_pm_admin:success' => 'User %s is now a project administrator',
	'roles_pm_admin:action:make_pm_admin:failure' => 'Could not add user to project administrator group',
	'roles_pm_admin:action:revoke_pm_admin' => 'Remove as project administrator',
	'roles_pm_admin:action:revoke_pm_admin:success' => 'User %s is not a project administrator anymore',
	'roles_pm_admin:action:revoke_pm_admin:failure' => 'Could not remove user from project administrator group',
	// Replace friends with connections
	'tasks:friends' => "Connections' tasks",
	'members:list:popular:none' => 'No members have any connections.',
	'blog:title:friends' => "Connections' blogs",
	'groups:inviteto' => "Invite connections to '%s'",
	'groups:nofriends' => "You have no connections left who have not been invited to this project.",
	'groups:nofriendsatall' => 'You have no connections to invite!',
	'thewire:friends' => "Connections' wire posts",
	'pages:friends' => "Connections' pages",
	'file:friends' => "Connections' files",
	'file:title:friends' => "Connections'",
	'file:friends:type:video' => "Your connections' videos",
	'file:friends:type:document' => "Your connections' documents",
	'file:friends:type:audio' => "Your connections' audio",
	'file:friends:type:image' => "Your connections' pictures",
	'file:friends:type:general' => "Your connections' general files",
	'groups:invite' => 'Invite connections',
	'groups:invite:title' => 'Invite connections to this group',
	'groups:inviteto' => "Invite connections to '%s'",
	'groups:nofriends' => "You have no connections left who have not been invited to this group.",
	'groups:nofriendsatall' => 'You have no connections to invite!',
	'friends:all' => 'All friends',
	'notifications:subscriptions:friends:title' => 'Connections',
	'notifications:subscriptions:friends:description' => 'Below are collections of your connections. Selecting a collection turns on notifications for the users in that collection.',
	'notifications:subscriptions:description' => 'To receive notifications from your connections (on an individual basis) when they create new content, find them below and select the notification method you would like to use.',
	'group_tools:settings:invite' => "Allow all users to be invited (not just connections)",
	'group_tools:group:invite:friends:select_all' => "Select all connections",
	'group_tools:group:invite:friends:deselect_all' => "Deselect all connections",
	'bookmarks:friends' => "Connections' bookmarks",
	'friends:invite' => 'Invite connections',
	'invitefriends:introduction' => 'To invite connections to join you on this network, enter their email addresses and a message they will receive with your invitation',
	'invitefriends:success' => 'Your connections were invited.',
	'access:friends:label' => "Connections",
	'friends' => "Connections",
	'friends:yours' => "Your connections",
	'friends:owned' => "%s's connections",
	'friend:add' => "Add connection",
	'friend:remove' => "Remove connection",
	'friends:add:successful' => "You have successfully added %s as a connection.",
	'friends:add:failure' => "We couldn't add %s as a connection.",
	'friends:remove:successful' => "You have successfully removed %s from your connections.",
	'friends:remove:failure' => "We couldn't remove %s from your connections.",
	'friends:none' => "No connections yet.",
	'friends:none:you' => "You don't have any connections yet.",
	'friends:none:found' => "No connections were found.",
	'friends:of:none' => "Nobody has added this user as a connection yet.",
	'friends:of:none:you' => "Nobody has added you as a connection yet. Start adding content and fill in your profile to let people find you!",
	'friends:of:owned' => "People who have made %s a connection",
	'friends:of' => "Connections of",
	'friends:collections' => "Connection collections",
	'friends:collections:add' => "New connections collection",
	'friends:addfriends' => "Select connections",
	'river:friend:user:default' => "%s is now a connection with %s",
	'river:widgets:friends' => 'Connections activity',
	'userpicker:only_friends' => 'Only connections',
	'river:friends' => 'Connections Activity',
	'friends:widget:description' => "Displays some of your connections.",
	'friends:num_display' => "Number of connections to display",
	'friend:newfriend:subject' => "%s has made you a connection!",
	'friend:newfriend:body' => "%s has made you a connection!",
);

add_translation("en", $english);
