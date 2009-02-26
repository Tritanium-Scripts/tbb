<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
function get_mtime_counter() {
	$mtime = explode(' ',microtime());
	return $mtime[1] + $mtime[0];
}

$t = get_mtime_counter();

if(phpversion() < 5) die('Your version of PHP is currently '.phpversion().' but you need at least PHP 5 so please update. This software will not work under PHP 4!');
#if(file_exists('install.php')) die('Please delete install.php first'); // Ueberprueft, ob die Installationsdatei geloescht wurde

include('core/Version.php');
include('core/Functions.class.php');
include('core/Factory.class.php');
include('core/ConfigTemplate.class.php');
include('core/ModuleTemplate.class.php');
include('core/Core.class.php');

$Core = new Core;
$Core->executeMe();

//echo (get_mtime_counter()-$t);