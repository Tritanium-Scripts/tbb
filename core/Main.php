<?php
/**
 * Loads main module and executes desired forum action.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
/**
 * Interface template for every implementing module.
 *
 * @package TBB1.5
 */
interface Module
{
	/**
	 * Executes this module.
	 */
	public function execute();
}
/**
 * Main module of TBB 1.5.
 *
 * @package TBB1.5
 */
class Main implements Module
{
	/**
	 * Detected action to execute.
	 *
	 * @var string Contains detected action.
	 */
	private $action;

	/**
	 * Translates the TBB1 faction value to a module.
	 *
	 * @var array Translation table
	 */
	private static $actionTable = array('reply' => 'Posts',
		'newtopic' => 'Topic',
		'editpoll' => 'Poll',
		'vote' => 'Poll',
		'newpoll' => 'Poll',
		'edit' => 'Post',
		'profile' => 'Profile',
		'login' => 'Login',
		'logout' => 'Login',
		'faq' => 'Help',
		'register' => 'Register',
		'pm' => 'PrivateMessage',
		'regeln' => 'Help',
		'search' => 'Search',
		'topic' => 'Topic',
		'wio' => 'WhoIsOnline',
		'viewip' => 'Post',
		'mlist' => 'MemberList',
		'sendpw' => 'Login',
		'formmail' => 'Profile',
		'' => 'Forum',
		'viewforum' => 'Forum',
		'viewthread' => 'Topic',
		'credits' => 'Credits',
		//adminpanel actions
		'adminpanel' => 'AdminIndex',
		'adminforum' => 'AdminForum',
		'adminuser' => 'AdminUser',
		'admingroups' => 'AdminGroups',
		'adminranks' => 'AdminRanks',
		'adminsmilies' => 'AdminSmilies',
		'adminips' => 'AdminIPs',
		'admincensor' => 'AdminCensor',
		'adminsettings' => 'AdminConfig',
		'adminnews' => 'AdminNews',
		'adminnewsletter' => 'AdminNewsletter',
		'adminmaillist' => 'AdminMailList',
		'adminkillposts' => 'AdminKillPosts');

	/**
	 * Loaded modules are stored here after first execution.
	 *
	 * @see Main::getModule()
	 * @var array Created modules
	 */
	private static $loadedModules = array();

	/**
	 * Some initial PHP stuff and instructions.
	 */
	function __construct()
	{
		//Report all errors
		error_reporting(E_ALL);
		//Finalize feature set of Functions class by either using Multibyte string functions and/or (overloaded) default PHP ones
		include('Functions' . (!extension_loaded('mbstring') || ini_set('mbstring.func_overload', '7') === false ? '' : 'MB') . '.php');
		//Check available disk space
		if(self::getModule('Config')->getCfgVal('use_diskfreespace') == 1 && (($fds = round(disk_free_space('.')/1024)) <= self::getModule('Config')->getCfgVal('warn_admin_fds')*1024))
		{
			$fdsVar = intval(Functions::file_get_contents('vars/fds.var')); //false = 0, if file does not exist or if file is empty
			if($fdsVar == 0) //Is this first time warning?
			{
				Functions::mail(self::getModule('Config')->getCfgVal('admin_email'), self::getModule('Language')->getString('fds_warning_subject'), sprintf(self::getModule('Language')->getString('fds_warning_message'), self::getModule('Config')->getCfgVal('address_to_forum') . '/index.php?faction=login'));
				self::getModule('Logger')->log('Disk space warning! Admin notified', LOG_FILESYSTEM);
				Functions::file_put_contents('vars/fds.var', ++$fdsVar);
			}
			if($fds <= self::getModule('Config')->getCfgVal('close_forum_fds')*1024)
			{
				self::getModule('Config')->setCfgVal('uc', 1); //Emergency closure
				if($fdsVar != 2)
				{
					Functions::mail(self::getModule('Config')->getCfgVal('admin_email'), self::getModule('Language')->getString('fds_alert_subject'), sprintf(self::getModule('Language')->getString('fds_alert_message'), self::getModule('Config')->getCfgVal('address_to_forum') . '/index.php?faction=login'));
					self::getModule('Logger')->log('Disk space alert! Admin notified; Board closed', LOG_FILESYSTEM);
					Functions::file_put_contents('vars/fds.var', 2);
				}
			}
		}
		//Log
		#session_start();
		#self::getModule('Logger')->log('User connected', LOG_USER_CONNECT);
		//Revert quoted strings on GPC vars, if needed
		if(ini_get('magic_quotes_gpc') == '1')
			list($_GET, $_POST, $_COOKIE) = Functions::stripSlashesDeep(array($_GET, $_POST, $_COOKIE));
		//Manage output compressions
		if(self::getModule('Config')->getCfgVal('use_gzip_compression'))
			if(ini_get('zlib.output_compression') != '1' && ini_get('output_handler') != 'ob_gzhandler')
				ob_start('ob_gzhandler');
			else
				self::getModule('Config')->setCfgVal('use_gzip_compression', 0); //Set actual state for tec stats
		if(self::getModule('Config')->getCfgVal('use_gzip_compression') == 0 && self::getModule('Config')->getCfgVal('activate_ob') == 1)
			ob_start();
		//Detect action
		$this->action = isset($_GET['faction']) ? $_GET['faction'] : (isset($_POST['faction']) ? $_POST['faction'] : '');
	}

	/**
	 * Executes detected action.
	 */
	public function execute()
	{
		self::getModule(self::$actionTable[$this->action])->execute();
	}

	/**
	 * Loads the stated module. Triggers an error if module could not be found.
	 *
	 * @param string $module The module to load
	 * @return mixed The loaded class or false on failure.
	 */
	public static function &getModule($module)
	{
		if(!isset(self::$loadedModules[$module]))
		{
			if(!file_exists('modules/' . $module . '.php'))
				return !trigger_error('Module ' . $module . ' does not exists', E_USER_WARNING);
			include('modules/' . $module . '.php');
			self::$loadedModules[$module] = new $module;
		}
		return self::$loadedModules[$module];
	}
}
?>