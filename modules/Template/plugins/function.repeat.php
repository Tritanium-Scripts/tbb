<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {repeat} function plugin
 *
 * Type:	 function<br>
 * Name:	 repeat<br>
 * Date:	 March 24, 2008<br>
 * Purpose:  Output something x times<br>
 * Input:
 *		 - text = text to output
 *		 - cycles = number of cycles
 *
 * @author Julian Backes <julian at tritanium-scripts.com>
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string|null
 */
function smarty_function_repeat($params, &$smarty) {
	$text = (isset($params['text'])) ? $params['text'] : '';
	$cycles = (isset($params['cycles'])) ? (int)$params['cycles'] : 0;
	
	$toReturn = '';
	
	for($i = 0; $i < $cycles; $i++)
		$toReturn .= $text;

	return $toReturn;
}

?>
