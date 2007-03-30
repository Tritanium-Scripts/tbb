<?php

class CoreConfig extends ConfigTemplate {
	protected $Config = array(
		'IndexFile'=>'index.php',
		'DefaultAction'=>'ForumIndex',
		'AllowedActions'=>array(
			'Ajax',
			'EditProfile',
			'ForumIndex',
			'Login',
			'Logout',
			'MemberList',
			'PrivateMessages',
			'ViewForum',
			'ViewTopic',
			'Posting',
			'Register',
			'WhoIsOnline'
		)
	);
}

?>