<?php
/**
 * Smarty plugin for optional UTF-8 encoding.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @package TBB1
 */
/**
 * Returns UTF-8 encoded string according to server environment.
 *
 * @param string $string String to encode as UTF-8, if needed
 * @return string UTF-8 encoded string
 */
function smarty_modifier_utf8_encode(?string $string): ?string
{
    if(!is_null($string) && !Core::getInstance()->isUtf8Locale())
        $string = Functions::utf8Encode($string);
    return $string;
}
?>