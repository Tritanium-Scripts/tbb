<?php
/**
 * Provides external access to newest posts of the forum.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2012 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.6
 */
class ExtLastPosts
{
/*
 * Copy this file into your website directory and paste in the following into your PHP code:
 * include('ext_lposts.php');
 * But prior to this you have to adjust these settings:
 */
	/**
	 * (Relative) Path to forum.
	 */
	private static $extPathToForum = 'path/to/forum/'; //Has to end with trailing slash!

	/**
	 * Number of latest posts to display.
	 */
	private static $numOfLastPosts = 5;

	/**
	 * Encode output as UTF-8?
	 */
	private static $isUTF8 = true; //If your website don't use UTF-8, set this to false

	/**
	 * Used language strings for output; you can translate them.
	 */
	private static $x_by_x_on_x = '%s von %s am %s'; //Do not change the number of %s
	public static $deleted = 'Gelöscht';
	public static $deleted_moved = 'Gelöscht / Verschoben';
	private static $DATE_FORMAT = '%d. %B %Y %H:%M'; //Values explained @ http://www.php.net/date


/* Do not change anything beyond this line */


	/**
	 * Contains the locale informations to restore after printing out last posts.
	 * This avoids possible interferences with the following scripts, since this code is not running in the well-known TBB environment.
	 *
	 * @var string Backed up locale informations
	 */
	private $oldLocale;

	/**
	 * Provides needed constants and includes required functions.
	 *
	 * @return ExtLastPosts New instance of this class
	 */
	function __construct()
	{
		if(!defined('EXT_PATH_TO_FORUM'))
		{
			define('EXT_PATH_TO_FORUM', self::$extPathToForum);
			require_once(EXT_PATH_TO_FORUM . 'core/Constants.php');
			define('EXT_PATH_TO_DATA', EXT_PATH_TO_FORUM . DATAPATH);
			define('EXT_IS_UTF8', self::$isUTF8);
			require_once(EXT_PATH_TO_FORUM . 'core/ExtFunctions.php');
			error_reporting(ERR_REPORTING);
			//Quick 'n' dirty fix to set "proper" timezone
			@date_default_timezone_set(date_default_timezone_get());
			#$this->oldLocale = setlocale(LC_ALL, '0');
		}
	}

	/**
	 * Prints out the last posts.
	 */
	public function printLastPosts()
	{
		if(($lastPosts = ExtFunctions::file_get_contents('vars/lposts.var')) != '')
		{
			foreach(ExtFunctions::explodeByTab($lastPosts) as $curNewestPost)
			{
				if(self::$numOfLastPosts < 1)
					break;
				#0:forumID - 1:topicID - 2:userID - 3:proprietaryDate[ - 4:tSmileyID - 5:postID]
				$curNewestPost = ExtFunctions::explodeByComma($curNewestPost . ',1,'); //Make sure index 4 and 5 are available
				echo(sprintf(self::$x_by_x_on_x,
					//Topic check + link + title preparation
					!ExtFunctions::file_exists('foren/' . $curNewestPost[0] . '-' . $curNewestPost[1] . '.xbb') ? (EXT_IS_UTF8 ? self::$deleted : utf8_decode(self::$deleted)) : '<img src="' . ExtFunctions::getTSmileyURL($curNewestPost[4]) . '" alt="" /> <a href="' . EXT_PATH_TO_FORUM . INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curNewestPost[0] . '&amp;thread=' . $curNewestPost[1] . '&amp;z=last#post' . $curNewestPost[5] . '">' . ExtFunctions::getTopicName($curNewestPost[0], $curNewestPost[1]) . '</a>',
					ExtFunctions::getProfileLink($curNewestPost[2], true),
					ExtFunctions::formatDate($curNewestPost[3], self::$DATE_FORMAT)) . '<br />');
				self::$numOfLastPosts--;
			}
			#setlocale(LC_ALL, $this->oldLocale);
		}
	}
}

$extLastPosts = new ExtLastPosts;
$extLastPosts->printLastPosts();
?>