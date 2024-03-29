<?php
use Smarty\Extension\Base;

/**
 * Smarty extension for calling any PHP functions as modifiers.
 *
 * @author Simon Wisselink
 */
class WildcardExtension extends Base
{
    public function getModifierCallback(string $modifierName)
    {
        return is_callable($modifierName) ? $modifierName : null;
    }
}
?>