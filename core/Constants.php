<?php
/**
 * Defines various constants.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
define('SCRIPTSTART', microtime(true));
define('INDEXFILE', 'index.php');
define('ERR_REPORTING', E_ERROR | E_PARSE); //Report only errors in case of Composer warnings
define('COPYRIGHT_YEAR', 2024); //Displayed in page footer
/*
 * Two version numbers of this script are used
 * to prevent selective bug using in case of
 * security vulnerabilities of a specific version.
 */
define('VERSION_PUBLIC', '1.10');
define('VERSION_PRIVATE', VERSION_PUBLIC . '.0.0');
//Define global data path
include('DataPath.php');
?>