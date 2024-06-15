<?php
use Smarty\Extension\Base;

/**
 * Smarty extension for optional UTF-8 encoding.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @package TBB1
 */
class Utf8EncodeExtension extends Base
{
    public function getModifierCompiler(string $modifier): ?\Smarty\Compile\Modifier\ModifierCompilerInterface
    {
        return $modifier == 'utf8_encode' ? new Utf8EncodeModifierCompiler() : null;
    }
}
?>