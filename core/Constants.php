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
define('ERR_REPORTING', E_ERROR | E_WARNING | E_PARSE); //Report only warnings or higher
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
//BBCode types
define('BBCODE_LIST', 0);       //List
define('BBCODE_BOLD', 1);       //Bold
define('BBCODE_ITALIC', 2);     //Italic
define('BBCODE_UNDERLINE', 3);  //Underline
define('BBCODE_STRIKE', 4);     //Strike
define('BBCODE_SUPERSCRIPT', 5);//Superscript
define('BBCODE_SUBSCRIPT', 6);  //Subscript
define('BBCODE_HIDE', 7);       //Spoiler
define('BBCODE_LOCK', 8);       //Hidden
define('BBCODE_CENTER', 9);     //Center
define('BBCODE_EMAIL', 10);     //E-mail
define('BBCODE_IMAGE', 11);     //Image
define('BBCODE_LINK', 12);      //URL
define('BBCODE_COLOR', 13);     //Color
define('BBCODE_SIZE', 14);      //Size
define('BBCODE_GLOW', 15);      //Glow
define('BBCODE_SHADOW', 16);    //Shadow
define('BBCODE_FLASH', 17);     //Flash
define('BBCODE_QUOTE', 18);     //Quote
define('BBCODE_CODE', 19);      //Code / PHP
define('BBCODE_IFRAME', 20);    //Inline frame
//Smiley types
define('SMILEY_SMILEY', 0);     //Normal smilies
define('SMILEY_TOPIC', 1);      //Post icons
define('SMILEY_ADMIN', 2);      //Admin and (s)mod smilies
?>