<?php
/**
 * Smarty plugin for trimming version numbers.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @package TBB1
 */
/**
 * Returns trimmed version number without trailing zero parts.
 *
 * @param string $string String to trim
 * @return string Trimmed version number
 */
function smarty_modifier_trim_version(string $string): string
{
    while(Functions::substr($string, -2) == '.0')
        $string = Functions::substr($string, 0, Functions::strlen($string)-2);
    return $string;
}
?>