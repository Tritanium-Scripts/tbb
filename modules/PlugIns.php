<?php
/**
 * Plug-in controller for caching, loading and calling all found plug-ins hooking into execution of the board.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class PlugIns
{
    use Singleton;

    public const HOOK_CORE_INIT = 'HOOK_CORE_INIT';
    public const HOOK_CORE_RUN = 'HOOK_CORE_RUN';
    public const HOOK_CORE_MODULE_CALL = 'HOOK_CORE_MODULE_CALL';
    public const HOOK_CORE_MISSING_MODULE = 'HOOK_CORE_MISSING_MODULE';

    public const HOOK_ADMIN_CALENDAR_INIT = 'HOOK_ADMIN_CALENDAR_INIT';
    public const HOOK_ADMIN_CALENDAR_NEW_EVENT = 'HOOK_ADMIN_CALENDAR_NEW_EVENT';
    public const HOOK_ADMIN_CALENDAR_EDIT_EVENT = 'HOOK_ADMIN_CALENDAR_EDIT_EVENT';
    public const HOOK_ADMIN_CALENDAR_DELETE_EVENT = 'HOOK_ADMIN_CALENDAR_DELETE_EVENT';
    public const HOOK_ADMIN_CALENDAR_SHOW_EVENTS = 'HOOK_ADMIN_CALENDAR_SHOW_EVENTS';

    public const HOOK_ADMIN_CENSOR_INIT = 'HOOK_ADMIN_CENSOR_INIT';
    public const HOOK_ADMIN_CENSOR_NEW_CENSORSHIP = 'HOOK_ADMIN_CENSOR_NEW_CENSORSHIP';
    public const HOOK_ADMIN_CENSOR_EDIT_CENSORSHIP = 'HOOK_ADMIN_CENSOR_EDIT_CENSORSHIP';
    public const HOOK_ADMIN_CENSOR_DELETE_CENSORSHIP = 'HOOK_ADMIN_CENSOR_DELETE_CENSORSHIP';
    public const HOOK_ADMIN_CENSOR_SHOW_CENSORSHIPS = 'HOOK_ADMIN_CENSOR_SHOW_CENSORSHIPS';

    public const HOOK_ADMIN_CONFIG_INIT = 'HOOK_ADMIN_CONFIG_INIT';
    public const HOOK_ADMIN_CONFIG_REBUILD_TOPIC_INDEX = 'HOOK_ADMIN_CONFIG_REBUILD_TOPIC_INDEX';
    public const HOOK_ADMIN_CONFIG_RECALCULATE_COUNTERS = 'HOOK_ADMIN_CONFIG_RECALCULATE_COUNTERS';
    public const HOOK_ADMIN_CONFIG_CLEAR_CACHE = 'HOOK_ADMIN_CONFIG_CLEAR_CACHE';
    public const HOOK_ADMIN_CONFIG_RESET_SETTINGS = 'HOOK_ADMIN_CONFIG_RESET_SETTINGS';
    public const HOOK_ADMIN_CONFIG_EDIT_SETTINGS = 'HOOK_ADMIN_CONFIG_EDIT_SETTINGS';

    public const HOOK_ADMIN_DELETE_OLD = 'HOOK_ADMIN_DELETE_OLD';
    public const HOOK_ADMIN_DELETE_OLD_DELETE_TOPICS = 'HOOK_ADMIN_DELETE_OLD_DELETE_TOPICS';

    public const HOOK_ADMIN_FORUM_INIT = 'HOOK_ADMIN_FORUM_INIT';
    public const HOOK_ADMIN_FORUM_FORUMS = 'HOOK_ADMIN_FORUM_FORUMS';
    public const HOOK_ADMIN_FORUM_NEW_FORUM = 'HOOK_ADMIN_FORUM_NEW_FORUM';
    public const HOOK_ADMIN_FORUM_DELETE_FORUM = 'HOOK_ADMIN_FORUM_DELETE_FORUM';
    public const HOOK_ADMIN_FORUM_EDIT_FORUM = 'HOOK_ADMIN_FORUM_EDIT_FORUM';
    public const HOOK_ADMIN_FORUM_MOVE_FORUM_UP = 'HOOK_ADMIN_FORUM_MOVE_FORUM_UP';
    public const HOOK_ADMIN_FORUM_MOVE_FORUM_DOWN = 'HOOK_ADMIN_FORUM_MOVE_FORUM_DOWN';
    public const HOOK_ADMIN_FORUM_SPECIAL_RIGHTS = 'HOOK_ADMIN_FORUM_SPECIAL_RIGHTS';
    public const HOOK_ADMIN_FORUM_NEW_USER_RIGHT = 'HOOK_ADMIN_FORUM_NEW_USER_RIGHT';
    public const HOOK_ADMIN_FORUM_NEW_GROUP_RIGHT = 'HOOK_ADMIN_FORUM_NEW_GROUP_RIGHT';
    public const HOOK_ADMIN_FORUM_DELETE_RIGHT = 'HOOK_ADMIN_FORUM_DELETE_RIGHT';
    public const HOOK_ADMIN_FORUM_TOPIC_PREFIXES = 'HOOK_ADMIN_FORUM_TOPIC_PREFIXES';
    public const HOOK_ADMIN_FORUM_NEW_TOPIC_PREFIX = 'HOOK_ADMIN_FORUM_NEW_TOPIC_PREFIX';
    public const HOOK_ADMIN_FORUM_EDIT_TOPIC_PREFIX = 'HOOK_ADMIN_FORUM_EDIT_TOPIC_PREFIX';
    public const HOOK_ADMIN_FORUM_DELETE_TOPIC_PREFIX = 'HOOK_ADMIN_FORUM_DELETE_TOPIC_PREFIX';
    public const HOOK_ADMIN_FORUM_CATEGORIES = 'HOOK_ADMIN_FORUM_CATEGORIES';
    public const HOOK_ADMIN_FORUM_NEW_CATEGORY = 'HOOK_ADMIN_FORUM_NEW_CATEGORY';
    public const HOOK_ADMIN_FORUM_MOVE_CATEGORY_UP = 'HOOK_ADMIN_FORUM_MOVE_CATEGORY_UP';
    public const HOOK_ADMIN_FORUM_MOVE_CATEGORY_DOWN = 'HOOK_ADMIN_FORUM_MOVE_CATEGORY_DOWN';
    public const HOOK_ADMIN_FORUM_EDIT_CATEGORY = 'HOOK_ADMIN_FORUM_EDIT_CATEGORY';
    public const HOOK_ADMIN_FORUM_DELETE_CATEGORY = 'HOOK_ADMIN_FORUM_DELETE_CATEGORY';

    public const HOOK_ADMIN_GROUP_INIT = 'HOOK_ADMIN_GROUP_INIT';
    public const HOOK_ADMIN_GROUP_NEW_GROUP = 'HOOK_ADMIN_GROUP_NEW_GROUP';
    public const HOOK_ADMIN_GROUP_EDIT_GROUP = 'HOOK_ADMIN_GROUP_EDIT_GROUP';
    public const HOOK_ADMIN_GROUP_DELETE_GROUP = 'HOOK_ADMIN_GROUP_DELETE_GROUP';
    public const HOOK_ADMIN_GROUP_SHOW_GROUPS = 'HOOK_ADMIN_GROUP_SHOW_GROUPS';

    public const HOOK_ADMIN_INDEX = 'HOOK_ADMIN_INDEX';

    public const HOOK_ADMIN_IP_INIT = 'HOOK_ADMIN_IP_INIT';
    public const HOOK_ADMIN_IP_NEW_BLOCK = 'HOOK_ADMIN_IP_NEW_BLOCK';
    public const HOOK_ADMIN_IP_DELETE_BLOCK = 'HOOK_ADMIN_IP_DELETE_BLOCK';
    public const HOOK_ADMIN_IP_SHOW_BLOCKS = 'HOOK_ADMIN_IP_SHOW_BLOCKS';

    public const HOOK_ADMIN_LOGFILE_INIT = 'HOOK_ADMIN_LOGFILE_INIT';
    public const HOOK_ADMIN_LOGFILE_VIEW_LOG = 'HOOK_ADMIN_LOGFILE_VIEW_LOG';
    public const HOOK_ADMIN_LOGFILE_DOWNLOAD_LOG = 'HOOK_ADMIN_LOGFILE_DOWNLOAD_LOG';
    public const HOOK_ADMIN_LOGFILE_DELETE_LOG = 'HOOK_ADMIN_LOGFILE_DELETE_LOG';
    public const HOOK_ADMIN_LOGFILE_SHOW_LOGS = 'HOOK_ADMIN_LOGFILE_SHOW_LOGS';

    public const HOOK_ADMIN_MAIL_BLOCK_INIT = 'HOOK_ADMIN_MAIL_BLOCK_INIT';
    public const HOOK_ADMIN_MAIL_BLOCK_NEW_BLOCK = 'HOOK_ADMIN_MAIL_BLOCK_NEW_BLOCK';
    public const HOOK_ADMIN_MAIL_BLOCK_DELETE_BLOCK = 'HOOK_ADMIN_MAIL_BLOCK_DELETE_BLOCK';
    public const HOOK_ADMIN_MAIL_BLOCK_SHOW_BLOCKS = 'HOOK_ADMIN_MAIL_BLOCK_SHOW_BLOCKS';

    public const HOOK_ADMIN_MAIL_LIST = 'HOOK_ADMIN_MAIL_LIST';

    public const HOOK_ADMIN_NEWS_INIT = 'HOOK_ADMIN_NEWS_INIT';
    public const HOOK_ADMIN_NEWS = 'HOOK_ADMIN_NEWS';

    public const HOOK_ADMIN_NEWSLETTER_INIT = 'HOOK_ADMIN_NEWSLETTER_INIT';
    public const HOOK_ADMIN_NEWSLETTER_SEND = 'HOOK_ADMIN_NEWSLETTER_SEND';

    public const HOOK_ADMIN_PLUG_INS_INIT = 'HOOK_ADMIN_PLUG_INS_INIT';
    public const HOOK_ADMIN_PLUG_INS_DELETE_PLUG_IN = 'HOOK_ADMIN_PLUG_INS_DELETE_PLUG_IN';
    public const HOOK_ADMIN_PLUG_INS_SHOW_PLUG_IN = 'HOOK_ADMIN_PLUG_INS_SHOW_PLUG_IN';

    public const HOOK_ADMIN_RANK_INIT = 'HOOK_ADMIN_RANK_INIT';
    public const HOOK_ADMIN_RANK_EDIT_RANK = 'HOOK_ADMIN_RANK_EDIT_RANK';
    public const HOOK_ADMIN_RANK_NEW_RANK = 'HOOK_ADMIN_RANK_NEW_RANK';
    public const HOOK_ADMIN_RANK_DELETE_RANK = 'HOOK_ADMIN_RANK_DELETE_RANK';
    public const HOOK_ADMIN_RANK_SHOW_RANKS = 'HOOK_ADMIN_RANK_SHOW_RANKS';

    public const HOOK_ADMIN_SMILEY_INIT = 'HOOK_ADMIN_SMILEY_INIT';
    public const HOOK_ADMIN_SMILEY_ADD_SMILEY = 'HOOK_ADMIN_SMILEY_ADD_SMILEY';
    public const HOOK_ADMIN_SMILEY_ADD_TOPIC_SMILEY = 'HOOK_ADMIN_SMILEY_ADD_TOPIC_SMILEY';
    public const HOOK_ADMIN_SMILEY_ADD_ADMIN_SMILEY = 'HOOK_ADMIN_SMILEY_ADD_ADMIN_SMILEY';
    public const HOOK_ADMIN_SMILEY_EDIT_SMILEY = 'HOOK_ADMIN_SMILEY_EDIT_SMILEY';
    public const HOOK_ADMIN_SMILEY_EDIT_TOPIC_SMILEY = 'HOOK_ADMIN_SMILEY_EDIT_TOPIC_SMILEY';
    public const HOOK_ADMIN_SMILEY_EDIT_ADMIN_SMILEY = 'HOOK_ADMIN_SMILEY_EDIT_ADMIN_SMILEY';
    public const HOOK_ADMIN_SMILEY_DELETE_SMILEY = 'HOOK_ADMIN_SMILEY_DELETE_SMILEY';
    public const HOOK_ADMIN_SMILEY_DELETE_TOPIC_SMILEY = 'HOOK_ADMIN_SMILEY_DELETE_TOPIC_SMILEY';
    public const HOOK_ADMIN_SMILEY_DELETE_ADMIN_SMILEY = 'HOOK_ADMIN_SMILEY_DELETE_ADMIN_SMILEY';
    public const HOOK_ADMIN_SMILEY_MOVE_SMILEY_UP = 'HOOK_ADMIN_SMILEY_MOVE_SMILEY_UP';
    public const HOOK_ADMIN_SMILEY_MOVE_TOPIC_SMILEY_UP = 'HOOK_ADMIN_SMILEY_MOVE_TOPIC_SMILEY_UP';
    public const HOOK_ADMIN_SMILEY_MOVE_ADMIN_SMILEY_UP = 'HOOK_ADMIN_SMILEY_MOVE_ADMIN_SMILEY_UP';
    public const HOOK_ADMIN_SMILEY_MOVE_SMILEY_DOWN = 'HOOK_ADMIN_SMILEY_MOVE_SMILEY_DOWN';
    public const HOOK_ADMIN_SMILEY_MOVE_TOPIC_SMILEY_DOWN = 'HOOK_ADMIN_SMILEY_MOVE_TOPIC_SMILEY_DOWN';
    public const HOOK_ADMIN_SMILEY_MOVE_ADMIN_SMILEY_DOWN = 'HOOK_ADMIN_SMILEY_MOVE_ADMIN_SMILEY_DOWN';
    public const HOOK_ADMIN_SMILEY_SHOW_SMILIES = 'HOOK_ADMIN_SMILEY_SHOW_SMILIES';

    public const HOOK_ADMIN_TEMPLATE_TEST_TEMPLATE = 'HOOK_ADMIN_TEMPLATE_TEST_TEMPLATE';
    public const HOOK_ADMIN_TEMPLATE_EDIT_TEMPLATE = 'HOOK_ADMIN_TEMPLATE_EDIT_TEMPLATE';
    public const HOOK_ADMIN_TEMPLATE_SHOW_TEMPLATES = 'HOOK_ADMIN_TEMPLATE_SHOW_TEMPLATES';

    public const HOOK_ADMIN_USER_INIT = 'HOOK_ADMIN_USER_INIT';
    public const HOOK_ADMIN_USER_NEW_USER = 'HOOK_ADMIN_USER_NEW_USER';
    public const HOOK_ADMIN_USER_EDIT_USER = 'HOOK_ADMIN_USER_EDIT_USER';
    public const HOOK_ADMIN_USER_SHOW_USER = 'HOOK_ADMIN_USER_SHOW_USER';

    public const HOOK_AUTH_USER_LOGGED_IN = 'HOOK_AUTH_USER_LOGGED_IN';
    public const HOOK_AUTH_LOGIN_CHANGED = 'HOOK_AUTH_LOGIN_CHANGED';

    public const HOOK_BBCODE_PARSE_HTML = 'HOOK_BBCODE_PARSE_HTML';
    public const HOOK_BBCODE_PARSE_SMILIES = 'HOOK_BBCODE_PARSE_SMILIES';
    public const HOOK_BBCODE_PARSE_BBCODE = 'HOOK_BBCODE_PARSE_BBCODE';

    public const HOOK_CALENDAR_INIT = 'HOOK_CALENDAR_INIT';
    public const HOOK_CALENDAR_SHOW_EVENTS = 'HOOK_CALENDAR_SHOW_EVENTS';

    public const HOOK_CREDITS_SHOW_CREDITS = 'HOOK_CREDITS_SHOW_CREDITS';

    public const HOOK_FORUM_INIT = 'HOOK_FORUM_INIT';
    public const HOOK_FORUM_SHOW_FORUM = 'HOOK_FORUM_SHOW_FORUM';
    public const HOOK_FORUM_SHOW_TOPIC = 'HOOK_FORUM_SHOW_TOPIC';
    public const HOOK_FORUM_TODAYS_POSTS = 'HOOK_FORUM_TODAYS_POSTS';
    public const HOOK_FORUM_RSS_FEED = 'HOOK_FORUM_RSS_FEED';
    public const HOOK_FORUM_MARK_ALL = 'HOOK_FORUM_MARK_ALL';
    public const HOOK_FORUM_SHOW_FORUMS = 'HOOK_FORUM_SHOW_FORUMS';

    public const HOOK_HELP_INIT = 'HOOK_HELP_INIT';
    public const HOOK_HELP_SHOW_FAQ = 'HOOK_HELP_SHOW_FAQ';
    public const HOOK_HELP_SHOW_BOARD_RULES = 'HOOK_HELP_SHOW_BOARD_RULES';
    public const HOOK_HELP_SHOW_GDPR = 'HOOK_HELP_SHOW_GDPR';

    public const HOOK_LANGUAGE_INIT = 'HOOK_LANGUAGE_INIT';
    public const HOOK_LANGUAGE_PARSE_FILE = 'HOOK_LANGUAGE_PARSE_FILE';

    public const HOOK_LOGGER_INIT = 'HOOK_LOGGER_INIT';
    public const HOOK_LOGGER_LOG = 'HOOK_LOGGER_LOG';

    public const HOOK_LOGIN_INIT = 'HOOK_LOGIN_INIT';
    public const HOOK_LOGIN_VERIFY = 'HOOK_LOGIN_VERIFY';
    public const HOOK_LOGIN_REQUEST_NEW_PASSWORD = 'HOOK_LOGIN_REQUEST_NEW_PASSWORD';
    public const HOOK_LOGIN_LOGOUT = 'HOOK_LOGIN_LOGOUT';

    public const HOOK_MEMBER_LIST_INIT = 'HOOK_MEMBER_LIST_INIT';
    public const HOOK_MEMBER_LIST_SHOW_MEMBERS = 'HOOK_MEMBER_LIST_SHOW_MEMBERS';

    public const HOOK_NAV_BAR_ADD_ELEMENT = 'HOOK_NAV_BAR_ADD_ELEMENT';
    public const HOOK_NAV_BAR_GET_NAV_BAR = 'HOOK_NAV_BAR_GET_NAV_BAR';

    public const HOOK_NEWSLETTER_INIT = 'HOOK_NEWSLETTER_INIT';
    public const HOOK_NEWSLETTER_SHOW_LETTER = 'HOOK_NEWSLETTER_SHOW_LETTER';
    public const HOOK_NEWSLETTER_DELETE_LETTERS = 'HOOK_NEWSLETTER_DELETE_LETTERS';
    public const HOOK_NEWSLETTER_SHOW_LETTERS = 'HOOK_NEWSLETTER_SHOW_LETTERS';

    public const HOOK_POSTING_INIT = 'HOOK_POSTING_INIT';
    public const HOOK_POSTING_SUBSCRIBE_TOPIC = 'HOOK_POSTING_SUBSCRIBE_TOPIC';
    public const HOOK_POSTING_UNSUBSCRIBE_TOPIC = 'HOOK_POSTING_UNSUBSCRIBE_TOPIC';
    public const HOOK_POSTING_NEW_REPLY = 'HOOK_POSTING_NEW_REPLY';
    public const HOOK_POSTING_DELETE_POST = 'HOOK_POSTING_DELETE_POST';
    public const HOOK_POSTING_EDIT_POST = 'HOOK_POSTING_EDIT_POST';
    public const HOOK_POSTING_DELETE_TOPIC = 'HOOK_POSTING_DELETE_TOPIC';
    public const HOOK_POSTING_CLOSE_TOPIC = 'HOOK_POSTING_CLOSE_TOPIC';
    public const HOOK_POSTING_OPEN_TOPIC = 'HOOK_POSTING_OPEN_TOPIC';
    public const HOOK_POSTING_MOVE_TOPIC = 'HOOK_POSTING_MOVE_TOPIC';
    public const HOOK_POSTING_PIN_TOPIC = 'HOOK_POSTING_PIN_TOPIC';
    public const HOOK_POSTING_UNPIN_TOPIC = 'HOOK_POSTING_UNPIN_TOPIC';
    public const HOOK_POSTING_OPEN_POLL = 'HOOK_POSTING_OPEN_POLL';
    public const HOOK_POSTING_CLOSE_POLL = 'HOOK_POSTING_CLOSE_POLL';
    public const HOOK_POSTING_EDIT_POLL = 'HOOK_POSTING_EDIT_POLL';
    public const HOOK_POSTING_VOTE_POLL = 'HOOK_POSTING_VOTE_POLL';
    public const HOOK_POSTING_BLOCK_IP = 'HOOK_POSTING_BLOCK_IP';
    public const HOOK_POSTING_VIEW_IP = 'HOOK_POSTING_VIEW_IP';

    public const HOOK_POST_NEW_INIT = 'HOOK_POST_NEW_INIT';
    public const HOOK_POST_NEW_POLL = 'HOOK_POST_NEW_POLL';
    public const HOOK_POST_NEW_TOPIC = 'HOOK_POST_NEW_TOPIC';
    public const HOOK_POST_NEW_WRITE_TOPIC = 'HOOK_POST_NEW_WRITE_TOPIC';

    public const HOOK_PRIVATE_MESSAGE_INIT = 'HOOK_PRIVATE_MESSAGE_INIT';
    public const HOOK_PRIVATE_MESSAGE_VIEW_PM = 'HOOK_PRIVATE_MESSAGE_VIEW_PM';
    public const HOOK_PRIVATE_MESSAGE_NEW_PM = 'HOOK_PRIVATE_MESSAGE_NEW_PM';
    public const HOOK_PRIVATE_MESSAGE_DELETE_PMS = 'HOOK_PRIVATE_MESSAGE_DELETE_PMS';
    public const HOOK_PRIVATE_MESSAGE_VIEW_PMS = 'HOOK_PRIVATE_MESSAGE_VIEW_PMS';

    public const HOOK_PROFILE_INIT = 'HOOK_PROFILE_INIT';
    public const HOOK_PROFILE_REFRESH_STEAM_GAMES = 'HOOK_PROFILE_REFRESH_STEAM_GAMES';
    public const HOOK_PROFILE_DELETE_PROFILE = 'HOOK_PROFILE_DELETE_PROFILE';
    public const HOOK_PROFILE_EDIT_PROFILE = 'HOOK_PROFILE_EDIT_PROFILE';
    public const HOOK_PROFILE_MY_PROFILE = 'HOOK_PROFILE_MY_PROFILE';
    public const HOOK_PROFILE_SEND_MAIL = 'HOOK_PROFILE_SEND_MAIL';
    public const HOOK_PROFILE_DOWNLOAD_VCARD = 'HOOK_PROFILE_DOWNLOAD_VCARD';
    public const HOOK_PROFILE_VIEW_ACHIEVEMENTS = 'HOOK_PROFILE_VIEW_ACHIEVEMENTS';
    public const HOOK_PROFILE_VIEW_PROFILE = 'HOOK_PROFILE_VIEW_PROFILE';

    public const HOOK_REGISTER_INIT = 'HOOK_REGISTER_INIT';
    public const HOOK_REGISTER_NEW_MEMBER = 'HOOK_REGISTER_NEW_MEMBER';
    public const HOOK_REGISTER_VERIFICATION = 'HOOK_REGISTER_VERIFICATION';
    public const HOOK_REGISTER_REGISTRATION = 'HOOK_REGISTER_REGISTRATION';

    public const HOOK_SEARCH_INIT = 'HOOK_SEARCH_INIT';
    public const HOOK_SEARCH_SHOW_RESULTS = 'HOOK_SEARCH_SHOW_RESULTS';
    public const HOOK_SEARCH_SEARCHING = 'HOOK_SEARCH_SEARCHING';
    public const HOOK_SEARCH_NEW_SEARCH = 'HOOK_SEARCH_NEW_SEARCH';

    public const HOOK_TEMPLATE_INIT = 'HOOK_TEMPLATE_INIT';
    public const HOOK_TEMPLATE_PAGE = 'HOOK_TEMPLATE_PAGE';

    public const HOOK_UPLOAD_UPLOAD = 'HOOK_UPLOAD_UPLOAD';
    public const HOOK_UPLOAD_UPLOADED = 'HOOK_UPLOAD_UPLOADED';

    public const HOOK_WHO_IS_ONLINE_INIT = 'HOOK_WHO_IS_ONLINE_INIT';
    public const HOOK_WHO_IS_ONLINE_PARSE_LOCATIONS = 'HOOK_WHO_IS_ONLINE_PARSE_LOCATIONS';
    public const HOOK_WHO_IS_ONLINE_SHOW_LOCATIONS = 'HOOK_WHO_IS_ONLINE_SHOW_LOCATIONS';

    public const HOOK_TPL_PAGE_HEADER_HTML_HEAD = 'HOOK_TPL_PAGE_HEADER_HTML_HEAD';
    public const HOOK_TPL_PAGE_HEADER_TOOLBAR_LOGGED_IN = 'HOOK_TPL_PAGE_HEADER_TOOLBAR_LOGGED_IN';
    public const HOOK_TPL_PAGE_HEADER_TOOLBAR_LOGGED_OUT = 'HOOK_TPL_PAGE_HEADER_TOOLBAR_LOGGED_OUT';
    public const HOOK_TPL_BBCODES = 'HOOK_TPL_BBCODES';

    /**
     * Loaded plug-in instances.
     *
     * @var array Loaded plug-ins
     */
    private array $plugIns = [];

    /**
     * Detected official hooks as provided by this controller.
     *
     * @var array Official hook names
     */
    private array $officialHooks;

    /**
     * Loads all found / cached plug-ins and detects official hooks.
     */
    private function __construct()
    {
        if(file_exists('cache/PlugIns.cache.php'))
            include('cache/PlugIns.cache.php');
        else
        {
            $plugInsCache = "<?php\n";
            foreach(Functions::glob('modules/PlugIns/*.php') as $curPlugIn)
            {
                //Detect namespace + class name of current plug-in
                $curDeclaredClasses = get_declared_classes();
                include($curPlugIn);
                $curDeclaredClasses = array_diff(get_declared_classes(), $curDeclaredClasses);
                //Check for valid class
                if(count($curDeclaredClasses) != 1)
                {
                    Logger::getInstance()->log('Plug-in "' . $curPlugIn . '" has defined invalid number of classes, loading skipped!', Logger::LOG_FILESYSTEM);
                    continue;
                }
                $curPlugInClass = current($curDeclaredClasses);
                //Check for interface
                if(!is_subclass_of($curPlugInClass, __NAMESPACE__ . '\\PlugIn'))
                {
                    Logger::getInstance()->log('Plug-in "' . $curPlugIn . '" does not implement required interface, loading skipped!', Logger::LOG_FILESYSTEM);
                    continue;
                }
                //Use full class path for creating instance
                $curPlugInName = basename($curPlugIn);
                $this->plugIns[$curPlugInName] = new $curPlugInClass();
                //Check for fulfilled TBB version
                $curMinVersion = $this->plugIns[$curPlugInName]->getMinVersion() ?? '1.10.0.0';
                while(substr_count($curMinVersion, '.') < 3)
                    $curMinVersion .= '.0';
                if(version_compare(VERSION_PRIVATE, $curMinVersion, '<'))
                {
                    Logger::getInstance()->log('Plug-in "' . $curPlugIn . '" requires newer TBB version ' . $curMinVersion . ', skipping!', Logger::LOG_FILESYSTEM);
                    unset($this->plugIns[$curPlugInName]);
                    continue;
                }
                //All checks passed and loaded - add to cache
                $plugInsCache .= 'include(\'' . $curPlugIn . '\'); $this->plugIns[\'' . $curPlugInName . '\'] = new ' . $curPlugInClass . "();\n";
            }
            //Set official hook names
            $curReflectionClass = new ReflectionClass($this);
            $this->officialHooks = array_values($curReflectionClass->getConstants());
            $plugInsCache .= '$this->officialHooks = [\'' . implode('\', \'', $this->officialHooks) . "'];\n";
            Functions::file_put_contents('cache/PlugIns.cache.php', $plugInsCache . '?>', LOCK_EX, false, false);
        }
    }

    /**
     * Calls registered plug-ins on given hook.
     *
     * @param string $hook Official or custom hook name
     * @param mixed $args Any arguments relevant to the hooked in execution
     * @return Hook was dispatched among all registered plug-ins
     */
    public function callHook(string $hook, &...$args): bool
    {
        if(Config::getInstance()->getCfgVal('activate_plug_ins') != 1)
            return false;
        $caller = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[1]['object'];
        foreach($this->plugIns as $curPlugIn)
            try
            {
                $curCallback = $curPlugIn->onHook($hook, in_array($hook, $this->officialHooks)); #TODO nullsafe operator since PHP 8.0: ?->call($caller, $args);
                if(!is_null($curCallback))
                    $curCallback->call($caller, $args);
            }
            catch(Throwable $e)
            {
                Logger::getInstance()->log('Plug-in "' . get_class($curPlugIn) . '" failed execution on called hook "' . $hook . '": ' . $e->getMessage(), Logger::LOG_FILESYSTEM);
            }
        return true;
    }

    /**
     * Deletes given plug-in file.
     *
     * @param string $file Name of PHP file
     * @return bool Plug-in being deleted
     */
    public function deletePlugIn(string $file): bool
    {
        $file = basename($file);
        $plugIn = 'modules/PlugIns/' . $file;
        if(file_exists($plugIn) && is_file($plugIn))
        {
            Functions::unlink($plugIn, false);
            unset($this->plugIns[$file]);
            Functions::unlink('cache/PlugIns.cache.php', false);
            return true;
        }
        return false;
    }

    /**
     * Returns loaded plug-ins.
     *
     * @return array Current active plug-ins
     */
    public function getPlugIns(): array
    {
        return $this->plugIns;
    }
}
?>