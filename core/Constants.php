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
define('ERR_REPORTING', E_WARNING); //Report only warnings or higher
/*
 * Two version numbers of this script are used
 * to prevent selective bug using in case of
 * security vulnerabilities of a specific version.
 */
define('VERSION_PUBLIC', '1.5');
define('VERSION_PRIVATE', VERSION_PUBLIC . '.1.0');
//Define global data path
include('DataPath.php');
//Logging constants
define('LOG_FILESYSTEM', 1);	//Problems with filesystem
define('LOG_ACP_ACCESS', 2);	//Failed ACP access
define('LOG_FAILED_LOGIN', 3);	//Failed login
define('LOG_NEW_POSTING', 4);	//New topic or post
define('LOG_EDIT_POSTING', 5);	//Edited, deleted, moved post or topic
define('LOG_USER_CONNECT', 6);	//User connected to board
define('LOG_LOGIN_LOGOUT', 7);	//Logins and logouts
define('LOG_ACP_ACTION', 8);	//Admin actions
define('LOG_USER_TRAFFIC', 9);	//PMs and mails
define('LOG_EDIT_PROFILE', 10);	//Profile changed
define('LOG_REGISTRATION', 11);	//New registration
define('LOG_NEW_PASSWORD', 12);	//New password request
//BBCode types
define('BBCODE_LIST', 0);		//List
define('BBCODE_BOLD', 1);		//Bold
define('BBCODE_ITALIC', 2);		//Italic
define('BBCODE_UNDERLINE', 3);	//Underline
define('BBCODE_STRIKE', 4);		//Strike
define('BBCODE_SUPERSCRIPT', 5);//Superscript
define('BBCODE_SUBSCRIPT', 6);	//Subscript
define('BBCODE_HIDE', 7);		//Spoiler
define('BBCODE_LOCK', 8);		//Hidden
define('BBCODE_CENTER', 9);		//Center
define('BBCODE_EMAIL', 10);		//E-mail
define('BBCODE_IMAGE', 11);		//Image
define('BBCODE_LINK', 12);		//URL
define('BBCODE_COLOR', 13);		//Color
define('BBCODE_SIZE', 14);		//Size
define('BBCODE_GLOW', 15);		//Glow
define('BBCODE_SHADOW', 16);	//Shadow
define('BBCODE_FLASH', 17);		//Flash
define('BBCODE_QUOTE', 18);		//Quote
define('BBCODE_CODE', 19);		//Code / PHP
//Smiley types
define('SMILEY_SMILEY', 0);		//Normal smiles
define('SMILEY_TOPIC', 1);		//Post icons
define('SMILEY_ADMIN', 2);		//Admin and (s)mod smilies
?>