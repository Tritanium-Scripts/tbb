<?php

class CoreConfig extends ConfigTemplate {
	protected $config = array(
		'indexFile'=>'index.php',
		'defaultAction'=>'ForumIndex',
		'enableOutputCompression'=>TRUE,
		'allowedActions'=>array(
			'AdminForums',
			'AdminIndex',
			'AdminProfileFields',
			'AdminSmilies',
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
			'SubscribeTopic',
			'Vote',
			'WhoIsOnline'
		)
	);
}

?>