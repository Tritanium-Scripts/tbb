<?php
/**
 * Provides external access to newest posts of the forum.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
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
	private static string $extPathToForum = 'path/to/forum/'; //Has to end with trailing slash!

	/**
	 * Number of latest posts to display.
	 */
	private static int $numOfLastPosts = 5;

	/**
	 * Encode output as UTF-8?
	 */
	private static bool $isUTF8 = true; //If your website don't use UTF-8, set this to false

	/**
	 * Used language strings for output; you can translate them.
	 */
	private static string $x_by_x_on_x = '%s von %s am %s'; //Do not change the number of %s
	public static string $deleted = 'Gelöscht';
	public static string $deleted_moved = 'Gelöscht / Verschoben';
	private static string $DATE_FORMAT = 'dd. MMMM yyyy HH:mm'; //Values explained @ https://unicode-org.github.io/icu/userguide/format_parse/datetime/#date-field-symbol-table


/* Do not change anything beyond this line */


	/**
	 * Provides needed constants and includes required functions.
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
		}
	}

	/**
	 * Prints out the last posts.
	 */
	public function printLastPosts(): void
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
					!ExtFunctions::file_exists('foren/' . $curNewestPost[0] . '-' . $curNewestPost[1] . '.xbb')
                        ? ExtFunctions::utf8Decode(self::$deleted)
                        : '<img src="' . ExtFunctions::getTSmileyURL($curNewestPost[4]) . '" alt="" /> <a href="' . EXT_PATH_TO_FORUM . INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curNewestPost[0] . '&amp;thread=' . $curNewestPost[1] . '&amp;z=last#post' . $curNewestPost[5] . '">' . ExtFunctions::getTopicName($curNewestPost[0], $curNewestPost[1]) . '</a>',
					ExtFunctions::getProfileLink($curNewestPost[2], true),
					ExtFunctions::formatDate($curNewestPost[3], self::$DATE_FORMAT)) . '<br />');
				self::$numOfLastPosts--;
			}
		}
	}
}

$extLastPosts = new ExtLastPosts;
$extLastPosts->printLastPosts();
?>