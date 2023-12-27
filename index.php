<?php
/**
 * Runs the board software.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.10
 */
require('vendor/autoload.php');
require('core/Constants.php');
require('core/FunctionsBasic.php');
require('core/Main.php');

$main = new Main;
$main->execute();
?>