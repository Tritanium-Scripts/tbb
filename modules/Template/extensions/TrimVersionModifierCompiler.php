<?php
use Smarty\Compile\Modifier\Base;

/**
 * Smarty modifier compiler for trimming version numbers.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @package TBB1
 */
class TrimVersionModifierCompiler extends Base
{
    public function compile($params, \Smarty\Compiler\Template $compiler): string
    {
        return 'TrimVersionModifierCompiler::trimVersion(' . $params[0] . ')';
    }

    /**
     * Returns trimmed version number without trailing zero parts.
     *
     * @param string $string String to trim
     * @return string Trimmed version number
     */
    public static function trimVersion(string $string): string
    {
        while(Functions::substr($string, -2) == '.0')
            $string = Functions::substr($string, 0, Functions::strlen($string)-2);
        return $string;
    }
}
?>