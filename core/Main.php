<?php
/**
 * Loads main module and executes desired forum action and/or subAction.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
/**
 * Interface template for every implementing module which can be called "directly" from an user.
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
	 * Translates the old TBB 1.2.3 faction value to a module.
	 *
	 * action is the proper faction: Name of module
	 * subAction is the proper mode: Name of template file
	 * If no mode is set, faction is the mode to execute.
	 * The translation from mode to subAction is done in each module, if needed.
	 * action and subAction are both known in the template environment.
	 * faction and mode are unknown to the template environment.
	 *
	 * Each module (incl. Main) has a language file with the same name.
	 * But each module can rely on multiple template files.
	 *
	 * m = amount of factions
	 * n = amount of modules
	 * x = amount of language file
	 * y = amount of template files
	 * -> m >= n
	 * -> n == x
	 * -> x <= y
	 *
	 * @var array Translation table
	 */
	private static $actionTable = array('reply' => 'Posting',
		'newtopic' => 'PostNew',
		'editpoll' => 'Posting',
		'vote' => 'Posting',
		'newpoll' => 'PostNew',
		'edit' => 'Posting',
		'profile' => 'Profile',
		'login' => 'Login',
		'logout' => 'Login',
		'faq' => 'Help',
		'register' => 'Register',
		'pm' => 'PrivateMessage',
		'regeln' => 'Help',
		'search' => 'Search',
		'topic' => 'Posting',
		'wio' => 'WhoIsOnline',
		'viewip' => 'Posting',
		'mlist' => 'MemberList',
		'sendpw' => 'Login',
		'formmail' => 'Profile',
		'' => 'Forum',
		'credits' => 'Credits',
		'newsletter' => 'Newsletter',
		'todaysPosts' => 'Forum',
		'rssFeed' => 'Forum',
		'uploadFile' => 'Upload',
		'markAll' => 'Forum',
		//Adminpanel actions
		'adminpanel' => 'AdminIndex',
		'ad_forum' => 'AdminForum',
		'ad_user' => 'AdminUser',
		'ad_groups' => 'AdminGroup',
		'ad_rank' => 'AdminRank',
		'ad_smilies' => 'AdminSmiley',
		'ad_ip' => 'AdminIP',
		'ad_censor' => 'AdminCensor',
		'ad_settings' => 'AdminConfig',
		'ad_news' => 'AdminNews',
		'ad_newsletter' => 'AdminNewsletter',
		'ad_emailist' => 'AdminMailList',
		'ad_killposts' => 'AdminDeleteOld',
		'ad_login' => 'Login',
		'adminLogfile' => 'AdminLogfile',
		'adminTemplate' => 'AdminTemplate');

	/**
	 * Loaded modules are stored here after first execution.
	 *
	 * @see Main::getModule()
	 * @var array Created modules
	 */
	private static $loadedModules = array();

	/**
	 * Some initial PHP stuff and preparations.
	 *
	 * @return Main New instance of this class
	 */
	function __construct()
	{
		error_reporting(ERR_REPORTING);
		set_exception_handler(create_function('$e', 'Main::getModule(\'Logger\')->log(get_class($e) . \': \' . $e->getMessage(), LOG_FILESYSTEM); echo($e);'));
		//Finalize feature set of Functions class by either using Multibyte string functions and/or (overloaded) default PHP ones
		require('Functions' . (!extension_loaded('mbstring') || (extension_loaded('mbstring') && ini_set('mbstring.func_overload', '7') !== false) ? '' : 'MB') . '.php');
		//Revert quoted strings on GPC vars, if needed
		if(ini_get('magic_quotes_gpc') == '1')
			list($_GET, $_POST, $_COOKIE) = Functions::stripSlashesDeep(array($_GET, $_POST, $_COOKIE));
		//Qick 'n' dirty fix to set "proper" timezone
		@date_default_timezone_set(date_default_timezone_get());
	}

	/**
	 * Executes the board software and the desired action.
	 */
	public function execute()
	{
		//Set custom error level to replace defaut one from constructor
		error_reporting(self::getModule('Config')->getCfgVal('error_level'));
		//Set locale for dates and number formats
		setlocale(LC_ALL, Functions::explodeByComma(self::getModule('Language')->getString('locale', 'Main')));
		//Set timeout for getting image sizes or steam achievements if not available
		if(self::getModule('Config')->getCfgVal('use_getimagesize') == 1 || self::getModule('Config')->getCfgVal('achievements') == 1)
			@ini_set('default_socket_timeout', 3);
		//Check using file caching
		if(self::getModule('Config')->getCfgVal('use_file_caching') != 1)
			Functions::setFileCaching(false);
		//Check available disk space
		if(self::getModule('Config')->getCfgVal('use_diskfreespace') == 1 && (($fds = round(disk_free_space('.')/1024)) <= self::getModule('Config')->getCfgVal('warn_admin_fds')*1024))
		{
			$fdsVar = intval(Functions::file_get_contents('vars/fds.var')); //false = 0, if file does not exist or if file is empty
			if($fdsVar == 0) //Is this first time warning?
			{
				Functions::mail(self::getModule('Config')->getCfgVal('admin_email'), 'fds_warning', self::getModule('Config')->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?faction=login');
				self::getModule('Logger')->log('Disk space warning! Admin notified', LOG_FILESYSTEM);
				Functions::file_put_contents('vars/fds.var', ++$fdsVar);
			}
			if($fds <= self::getModule('Config')->getCfgVal('close_forum_fds')*1024)
			{
				self::getModule('Config')->setCfgVal('uc', 1); //Emergency closure
				if($fdsVar != 2)
				{
					Functions::sendMessage(self::getModule('Config')->getCfgVal('admin_email'), 'fds_alert', self::getModule('Config')->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?faction=login');
					self::getModule('Logger')->log('Disk space alert! Admin notified; Board closed', LOG_FILESYSTEM);
					Functions::file_put_contents('vars/fds.var', 2);
				}
			}
		}
		//Manage output compressions
		if(self::getModule('Config')->getCfgVal('use_gzip_compression') == 1)
			if(ini_get('zlib.output_compression') != '1' && ini_get('output_handler') != 'ob_gzhandler')
				ob_start('ob_gzhandler');
			else
				self::getModule('Config')->setCfgVal('use_gzip_compression', 0); //Set actual state for tec stats
		if(self::getModule('Config')->getCfgVal('use_gzip_compression') == 0 && self::getModule('Config')->getCfgVal('activate_ob') == 1)
			ob_start();
		//Manage session
		session_name('sid');
		session_start();
		if(session_id() == '0')
			session_regenerate_id();
		//Provide session IDs
		if(self::getModule('Config')->getCfgVal('append_sid_url') == 1 || SID != '')
		{
			//URL-based
			define('SID_QMARK', '?sid=' . session_id());
			define('SID_AMPER', '&amp;sid=' . session_id());
			define('SID_AMPER_RAW', '&sid=' . session_id());
		}
		else
		{
			//Cookie-based
			define('SID_QMARK', '');
			define('SID_AMPER', '');
			define('SID_AMPER_RAW', '');
		}
		//Log connected state of user
		if(!self::getModule('Auth')->isConnected())
			self::getModule('Logger')->log((self::getModule('Auth')->isLoggedIn() ? '%s' : 'User') . ' connected', LOG_USER_CONNECT);
		//Set root of NavBar
		Main::getModule('NavBar')->addElement(Main::getModule('Config')->getCfgVal('forum_name'), INDEXFILE . SID_QMARK);
		//Detect action
		$this->action = self::$actionTable[($fAction = Functions::getValueFromGlobals('faction'))];
		self::getModule('Template')->assign('action', $this->action);
		//Check maintenance mode
		if(self::getModule('Config')->getCfgVal('uc') == 1 && !self::getModule('Auth')->isAdmin() && $this->action != 'Login')
			self::getModule('Template')->printMessage('maintenance_mode_on'); //Lang strings from Main are already loaded via setlocale()
		//Check IP address
		if(($endtime = Functions::checkIPAccess()) !== true)
			self::getModule('Template')->printMessage(($endtime == -1 ? 'banned_forever_everywhere' : 'banned_for_x_minutes_everywhere'), ceil(($endtime-time())/60));
		//Check force login
		if(self::getModule('Config')->getCfgVal('must_be_logged_in') == 1 && !self::getModule('Auth')->isLoggedIn() && !in_array($this->action, array('Register', 'Login', 'Help')))
			self::getModule('Template')->printMessage('members_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
		//Autoload translation of module
		self::getModule('Language')->parseFile($this->action);
		//Execute module with mode or forum action as mode replacement
		self::getModule($this->action, ($mode = Functions::getValueFromGlobals('mode')) == '' ? $fAction : $mode)->execute();
	}

	/**
	 * Loads the stated module. Exits if module could not be found.
	 *
	 * @param string $module The module to load
	 * @param string $mode Optional mode for not yet loaded module
	 * @return mixed Reference to the loaded class
	 */
	public static function &getModule($module, $mode=null)
	{
		if(!isset(self::$loadedModules[$module]))
		{
			if(!file_exists('modules/' . $module . '.php'))
				exit('<b>ERROR:</b> Module ' . $module . ' does not exists!');
			include('modules/' . $module . '.php');
			self::$loadedModules[$module] = !isset($mode) ? new $module : new $module($mode);
		}
		return self::$loadedModules[$module];
	}

	/**
	 * Returns reference to all loaded modules.
	 *
	 * @return array Loaded modules
	 */
	public static function &getModules()
	{
		return self::$loadedModules;
	}
}
?>