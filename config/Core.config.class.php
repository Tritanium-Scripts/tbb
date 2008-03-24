<?php

class CoreConfig extends ConfigTemplate {
	protected $config = array(
		'indexFile'=>'index.php',
		'defaultAction'=>'ForumIndex',
		'enableOutputCompression'=>TRUE,
		'allowedActions'=>array(
			'AdminAvatars',
			'AdminConfig',
			'AdminForums',
			'AdminGroups',
			'AdminIndex',
			'AdminProfileFields',
			'AdminRanks',
			'AdminSmilies',
			'AdminTemplates',
			'AdminUsers',
			'Ajax',
			'EditProfile',
			'EditTopic',
			'ForumIndex',
			'Login',
			'Logout',
			'MemberList',
			'PrivateMessages',
			'ViewForum',
			'ViewProfile',
			'ViewTopic',
			'Posting',
			'Register',
			'Search',
			'SubscribeTopic',
			'ViewHelp',
			'Vote',
			'WhoIsOnline'
		)
	);
}

?>