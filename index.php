<?php
/**
 * Runs the board software.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
require('vendor/autoload.php');
require('core/Constants.php');
require('core/CoreFunctions.php');
require('core/Interfaces.php');
require('core/Traits.php');
require('core/Core.php');

Core::getInstance()->run();
?>