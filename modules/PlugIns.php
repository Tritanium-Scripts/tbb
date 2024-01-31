<?php
/**
 * Plug-in controller for caching, loading and calling all found plug-ins hooking into executing of the board.
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

    public const HOOK_LANGUAGE_PARSE_FILE = 'HOOK_LANGUAGE_PARSE_FILE';

    public const HOOK_AUTH_USER_LOGGED_IN = 'HOOK_AUTH_USER_LOGGED_IN';

    public const HOOK_NAVBAR_ADD_ELEMENT = 'HOOK_NAVBAR_ADD_ELEMENT';

    public const HOOK_BBCODE_PARSE_HTML = 'HOOK_BBCODE_PARSE_HTML';
    public const HOOK_BBCODE_PARSE_SMILIES = 'HOOK_BBCODE_PARSE_SMILIES';
    public const HOOK_BBCODE_PARSE_BBCODE = 'HOOK_BBCODE_PARSE_BBCODE';

    public const HOOK_TEMPLATE_INIT = 'HOOK_TEMPLATE_INIT';
    public const HOOK_TEMPLATE_PAGE = 'HOOK_TEMPLATE_PAGE';

    public const HOOK_UPLOAD_UPLOAD = 'HOOK_UPLOAD_UPLOAD';
    public const HOOK_UPLOAD_UPLOADED = 'HOOK_UPLOAD_UPLOADED';

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
                $this->plugIns[basename($curPlugIn)] = new $curPlugInClass();
                $plugInsCache .= 'include(\'' . $curPlugIn . '\'); $this->plugIns[\'' . basename($curPlugIn) . '\'] = new ' . $curPlugInClass . "();\n";
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
                $curPlugIn->onHook($hook, in_array($hook, $this->officialHooks))?->call($caller, $args);
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