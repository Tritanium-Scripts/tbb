<?php
use Smarty\Compile\Modifier\Base;

/**
 * Smarty modifier compiler for optional UTF-8 encoding.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @package TBB1
 */
class Utf8EncodeModifierCompiler extends Base
{
    public function compile($params, \Smarty\Compiler\Template $compiler): string
    {
        return 'Utf8EncodeModifierCompiler::utf8Encode(' . $params[0] . ')';
    }

    /**
     * Returns UTF-8 encoded string according to server environment.
     *
     * @param string $string String to encode as UTF-8, if needed
     * @return string UTF-8 encoded string
     */
    public static function utf8Encode(?string $string): ?string
    {
        if(!is_null($string) && !Core::getInstance()->isUtf8Locale())
            $string = Functions::utf8Encode($string);
        return $string;
    }
}
?>