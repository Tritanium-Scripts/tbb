<?php
/**
 * Runs the board software.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
foreach(array('Constants', 'FunctionsBasic', 'Main') as $curInclude)
	require('core/' . $curInclude . '.php');

$main = new Main;
$main->execute();
?>