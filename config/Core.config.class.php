<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
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
			'FileUploads',
			'ForumIndex',
			'Groups',
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