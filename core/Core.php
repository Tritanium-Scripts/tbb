<?php
/**
 * Loads main module and executes desired forum action and/or subAction.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class Core
{
    use Singleton;

    /**
     * Indicates the used locale is based on UTF-8 and stuff like month names don't need {@link utf8_encode()}.
     *
     * @var bool UTF-8 based locale loaded
     */
    private bool $utf8Locale;

    /**
     * Detected action to execute.
     *
     * @var string Contains detected action.
     */
    private ?string $action;

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
    private static array $actionTable = ['reply' => 'Posting',
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
        'calendar' => 'Calendar',
        'rssFeed' => 'Forum',
        'uploadFile' => 'Upload',
        'markAll' => 'Forum',
        'gdpr' => 'Help',
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
        'adminTemplate' => 'AdminTemplate',
        'adminCalendar' => 'AdminCalendar',
        'adminPlugIns' => 'AdminPlugIns'];

    /**
     * Some initial PHP stuff and preparations.
     */
    function __construct()
    {
        error_reporting(ERR_REPORTING);
        set_exception_handler(function($e)
        {
            Logger::getInstance()->log(get_class($e) . ': ' . $e->getMessage(), Logger::LOG_FILESYSTEM);
            echo($e);
        });
        //Finalize feature set of Functions class by either using Multibyte string functions and/or (overloaded) default PHP ones
        require('Functions' . (extension_loaded('mbstring') ? 'MB' : '') . '.php');
        //Set proper charset, if needed
        if(ini_get('default_charset') != 'UTF-8')
            ini_set('default_charset', 'UTF-8');
        //Quick 'n' dirty fix to set "proper" timezone
        @date_default_timezone_set(date_default_timezone_get());
        //Initialization done
        PlugIns::getInstance()->callHook(PlugIns::HOOK_CORE_INIT);
    }

    /**
     * Executes the board software and the desired action.
     */
    public function run(): void
    {
        PlugIns::getInstance()->callHook(PlugIns::HOOK_CORE_RUN);
        //Set custom error level to replace default one from constructor
        error_reporting(intval(Config::getInstance()->getCfgVal('error_level')));
        //Set locale for dates and number formats
        $this->utf8Locale = Functions::stripos(setlocale(LC_ALL, Functions::explodeByComma(Language::getInstance()->getString('locale', 'Main'))), '.utf8') !== false;
        //Set timeout for getting image sizes or Steam achievements if not available
        if(Config::getInstance()->getCfgVal('use_getimagesize') == 1 || Config::getInstance()->getCfgVal('achievements') == 1)
            @ini_set('default_socket_timeout', 3);
        //Check using file caching
        if(Config::getInstance()->getCfgVal('use_file_caching') != 1)
            Functions::setFileCaching(false);
        //Check available disk space
        if(Config::getInstance()->getCfgVal('use_diskfreespace') == 1 && (($fds = round((($fds = disk_free_space('.')) === false ? PHP_INT_MAX : $fds)/1024)) <= Config::getInstance()->getCfgVal('warn_admin_fds')*1024))
        {
            $fdsVar = intval(Functions::file_get_contents('vars/fds.var')); //false = 0, if file does not exist or if file is empty
            if($fdsVar == 0) //Is this first time warning?
            {
                Functions::mail(Config::getInstance()->getCfgVal('admin_email'), 'fds_warning', Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?faction=login');
                Logger::getInstance()->log('Disk space warning! Admin notified', Logger::LOG_FILESYSTEM);
                Functions::file_put_contents('vars/fds.var', ++$fdsVar);
            }
            if($fds <= Config::getInstance()->getCfgVal('close_forum_fds')*1024)
            {
                Config::getInstance()->setCfgVal('uc', 1); //Emergency closure
                if($fdsVar != 2)
                {
                    Functions::sendMessage(Config::getInstance()->getCfgVal('admin_email'), 'fds_alert', Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?faction=login');
                    Logger::getInstance()->log('Disk space alert! Admin notified; Board closed', Logger::LOG_FILESYSTEM);
                    Functions::file_put_contents('vars/fds.var', 2);
                }
            }
        }
        //Manage output compressions
        if(Config::getInstance()->getCfgVal('use_gzip_compression') == 1)
        {
            if(ini_get('zlib.output_compression') != '1' && ini_get('output_handler') != 'ob_gzhandler')
                ob_start('ob_gzhandler');
            else
                Config::getInstance()->setCfgVal('use_gzip_compression', 0); //Set actual state for tec stats
        }
        if(Config::getInstance()->getCfgVal('use_gzip_compression') == 0 && Config::getInstance()->getCfgVal('activate_ob') == 1)
            ob_start();
        //Manage session
        session_name('sid');
        session_start();
        if(session_id() == '0')
            session_regenerate_id();
        //Provide session IDs
        if(Config::getInstance()->getCfgVal('append_sid_url') == 1 || SID != '')
        {
            //URL-based
            define('SID_QMARK', '?' . htmlspecialchars(SID));
            define('SID_AMPER', '&amp;' . htmlspecialchars(SID));
            define('SID_AMPER_RAW', '&' . htmlspecialchars(SID));
        }
        else
        {
            //Cookie-based
            define('SID_QMARK', '');
            define('SID_AMPER', '');
            define('SID_AMPER_RAW', '');
        }
        //Log connected state of user
        if(!Auth::getInstance()->isConnected())
            Logger::getInstance()->log((Auth::getInstance()->isLoggedIn() ? '%s' : 'User') . ' connected', Logger::LOG_USER_CONNECT);
        //Set root of NavBar
        NavBar::getInstance()->addElement(Config::getInstance()->getCfgVal('forum_name'), INDEXFILE . SID_QMARK);
        //Detect action
        $this->action = self::$actionTable[($fAction = Functions::getValueFromGlobals('faction'))] ?? null;
        Template::getInstance()->assign('action', $this->action);
        //Check maintenance mode
        if(Config::getInstance()->getCfgVal('uc') == 1 && !Auth::getInstance()->isAdmin() && $this->action != 'Login')
            Template::getInstance()->printMessage('maintenance_mode_on'); //Lang strings from Main are already loaded via setlocale()
        //Check IP address
        $endtime = Functions::checkIPAccess();
        if($endtime !== true)
            Template::getInstance()->printMessage(($endtime == -1 ? 'banned_forever_everywhere' : 'banned_for_x_minutes_everywhere'), ceil(($endtime-time())/60));
        //Check force login
        if(Config::getInstance()->getCfgVal('must_be_logged_in') == 1 && !Auth::getInstance()->isLoggedIn() && !in_array($this->action, ['Register', 'Login', 'Help']))
            Template::getInstance()->printMessage('members_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
        //Autoload translation of module
        Language::getInstance()->parseFile($this->action);
        //Execute module with mode or forum action as mode replacement
        PlugIns::getInstance()->callHook(PlugIns::HOOK_CORE_MODULE_CALL);
        $this->getModule($this->action, ($mode = Functions::getValueFromGlobals('mode')) == '' ? $fAction : $mode)->publicCall();
    }

    /**
     * Loads the stated module. Exits if module could not be found with a log entry.
     *
     * @param string $module The module to load
     * @param string $mode Optional mode for not yet loaded module
     * @return PublicModule Reference to the loaded class
     */
    private function getModule(?string $module, ?string $mode=null): PublicModule
    {
        if(!class_exists($module) || !is_subclass_of($module, 'PublicModule'))
        {
            $missing;
            if(is_null($module))
            {
                Logger::getInstance()->log('Call to unknown module "' . $mode . '"', Logger::LOG_FILESYSTEM);
                if(function_exists('http_response_code'))
                    http_response_code(400);
                $missing = $mode;
            }
            else
            {
                if($module != 'Config') //In case of "Config.php.new" to prevent redeclaring Logger
                    Logger::getInstance()->log('Call to missing module "' . $module . '"', Logger::LOG_FILESYSTEM);
                if(function_exists('http_response_code'))
                    http_response_code(500);
                $missing = $module;
            }
            exit('<b>ERROR:</b> Module ' . $missing . ' does not exist!');
        }
        return $module::getInstance($mode);
    }

    /**
     * Returns the used locale is based on UTF-8.
     *
     * @return bool UTF-8 based locale loaded
     */
    public function isUtf8Locale(): bool
    {
        return $this->utf8Locale;
    }
}
?>