<?php
/**
 * Defines various constants.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
define('SCRIPTSTART', microtime(true));
define('INDEXFILE', 'index.php');
//Define data path
include('DataPath.php');
//Logging constants
define('LOG_FILESYSTEM', 1); //Problems with filesystem
define('LOG_ACP_ACCESS', 2); //Failed ACP access
define('LOG_FAILED_LOGIN', 3); //Failed login
define('LOG_NEW_POSTING', 4); //New topic or post
define('LOG_EDIT_POSTING', 5); //Edited, deleted, moved post or topic
define('LOG_USER_CONNECT', 6); //User connected to board
define('LOG_LOGIN_LOGOUT', 7); //Logins and logouts
define('LOG_ACP_ACTION', 8); //Admin actions
define('LOG_USER_TRAFFIC', 9); //PMs and mails
define('LOG_EDIT_PROFILE', 10); //Profile changed
define('LOG_REGISTRATION', 11); //New registration
define('LOG_NEW_PASSWORD', 12); //New password request
?>