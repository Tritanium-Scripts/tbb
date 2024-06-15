<?php
use Smarty\Extension\Base;

/**
 * Smarty extension for calling plug-in hooks from TPL files.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @package TBB1
 */
class PluginHookExtension extends Base
{
    public function getFunctionHandler(string $functionName): ?\Smarty\FunctionHandler\FunctionHandlerInterface
    {
        return $functionName == 'plugin_hook' ? new PluginHookFunctionHandler() : null;
    }
}
?>