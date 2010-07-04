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
	private $loadedModules = array();

	/**
	 * Some initial PHP stuff and detection of action.
	 */
	function __construct()
	{
		error_reporting(E_ALL);
		//Revert quoted strings on GPC vars, if needed
		if(ini_get('magic_quotes_gpc') == '1')
			list($_GET, $_POST, $_COOKIE) = self::getModule('Functions')->stripSlashesDeep(array($_GET, $_POST, $_COOKIE));
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
		if(!isset($this->loadedModules[$module]))
		{
			if(!file_exists('modules/' . $module . '.php'))
				return !trigger_error('Module ' . $module . ' does not exists', E_USER_WARNING);
			include('modules/' . $module . '.php');
			$this->loadedModules[$module] = new $module;
		}
		return $this->loadedModules[$module];
	}
}

$main = new Main;
$main->execute();
?>
