<?php
/**
 * Plug-in controller for caching, loading and calling all found plug-ins hooking into executing of the Newsscript.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class PlugIns
{
    use Singleton;

    const HOOK_CORE_INIT = 'HOOK_CORE_INIT';
    const HOOK_CORE_RUN = 'HOOK_CORE_RUN';
    const HOOK_CORE_MODULE_CALL = 'HOOK_CORE_MODULE_CALL';

    const HOOK_BBCODE_PARSE_HTML = 'HOOK_BBCODE_PARSE_HTML';
    const HOOK_BBCODE_PARSE_SMILIES = 'HOOK_BBCODE_PARSE_SMILIES';
    const HOOK_BBCODE_PARSE_BBCODE = 'HOOK_BBCODE_PARSE_BBCODE';

    const HOOK_TEMPLATE_INIT = 'HOOK_TEMPLATE_INIT';
    const HOOK_TEMPLATE_PAGE = 'HOOK_TEMPLATE_PAGE';

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
                $this->plugIns[] = new $curPlugInClass();
                $plugInsCache .= 'include(\'' . $curPlugIn . '\'); $this->plugIns[] = new ' . $curPlugInClass . "();\n";
            }
            //Set official hook names
            $curReflectionClass = new ReflectionClass($this);
            $this->officialHooks = array_values($curReflectionClass->getConstants());
            $plugInsCache .= '$this->officialHooks = [\'' . implode('\', \'', $this->officialHooks) . "'];\n";
            Functions::file_put_contents('cache/PlugIns.cache.php', $plugInsCache . '?>', LOCK_EX);
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