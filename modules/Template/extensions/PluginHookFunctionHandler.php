<?php
use Smarty\FunctionHandler\Base;

/**
 * Smarty function handler for calling plug-in hooks from TPL files.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @package TBB1
 */
class PluginHookFunctionHandler extends Base
{
    /**
     * Prints output of a called plug-in's hook.
     *
     * @param array $params Parameters
     * @param \Smarty\Template $template Template object
     */
    public function handle($params, \Smarty\Template $template): void
    {
        if(empty($params['hook']))
        {
            trigger_error('plugin_hook: missing \'hook\' parameter');
            return;
        }
        Closure::fromCallable(fn($params) => PlugIns::getInstance()->callHook($params['hook']))->call($template, $params);
    }
}
?>