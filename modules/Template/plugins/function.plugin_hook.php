<?php
/**
 * Smarty plugin for calling plug-in hooks from TPL files.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @package TBB1
 */
/**
 * Prints output of a called plug-in's hook.
 *
 * @param array $params Parameters
 * @param Smarty_Internal_Template $template Template object
 */
function smarty_function_plugin_hook(array $params, Smarty_Internal_Template $template): void
{
    if(empty($params['hook']))
    {
        trigger_error('plugin_hook: missing \'hook\' parameter');
        return;
    }
    Closure::fromCallable(fn($params) => PlugIns::getInstance()->callHook($params['hook']))->call($template, $params);
}
?>