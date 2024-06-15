<?php
use Smarty\Extension\Base;

/**
 * Smarty extension for trimming version numbers.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @package TBB1
 */
class TrimVersionExtension extends Base
{
    public function getModifierCompiler(string $modifier): ?\Smarty\Compile\Modifier\ModifierCompilerInterface
    {
        return $modifier == 'trim_version' ? new TrimVersionModifierCompiler() : null;
    }
}
?>