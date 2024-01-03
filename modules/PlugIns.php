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

    const HOOK_CONFIG_INIT = 'HOOK_CONFIG_INIT';

    const HOOK_CORE_INIT = 'HOOK_CORE_INIT';
    const HOOK_CORE_RUN = 'HOOK_CORE_RUN';
    const HOOK_CORE_MODULE_CALL = 'HOOK_CORE_MODULE_CALL';

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
     * Already called hooks during one execution run to prevent plug-ins called again.
     *
     * @var array Processed hook names
     */
    private array $calledHooks = [];

    /**
     * Loads all found / cached plug-ins and detects official hooks.
     *
     * @return PlugIns New instance of this class
     */
    private function __construct()
    {
        if(file_exists('cache/PlugIns.cache.php'))
            include('cache/PlugIns.cache.php');
        else
        {
            $plugInsCache = "<?php\n";
            foreach(Functions::glob('plugins/*.php') as $curPlugIn)
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
                if(is_subclass_of($curPlugInClass, __NAMESPACE__ . '\\PlugIn'))
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
            $plugInsCache .= '$this->officialHooks = [\'' . implode('\', \'', array_values($curReflectionClass->getConstants())) . "'];\n";
            Functions::file_put_contents('cache/PlugIns.cache.php', $plugInsCache . '?>', LOCK_EX);
        }
    }

    /**
     * Calls registered plug-ins on given hook.
     *
     * @param string $hook Official or custom hook name
     * @return Hook was dispatched among all registered plug-ins
     */
    public function callHook(string $hook): bool
    {
        //Hook already called before?
        if(in_array($hook, $this->calledHooks))
        {
            Logger::getInstance()->log('Script "' . debug_backtrace(null, 1)[0]['file'] . '" tried to call hook "' . $hook . '" again!', Logger::LOG_FILESYSTEM);
            return false;
        }
        //Mark hook as called first and dispatch afterwards
        $this->calledHooks[] = $hook;
        foreach($this->plugIns as $curPlugIn)
            try
            {
                $curPlugIn->onHook($hook, in_array($hook, $this->officialHooks));
            }
            catch(Exception $e)
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