<?php
/**
 * Various static functions and wrappers, optimized for external calls and standalone actions.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2021 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.7
 */
class ExtFunctions
{
	/**
	 * Various cached (loaded and fully exploded) data.
	 *
	 * @var array Cached data
	 */
	private static $cache = array();

	/**
	 * Explodes a string by comma.
	 *
	 * @param string $string String to explode
	 * @return array Resulting array
	 */
	public static function explodeByComma($string)
	{
		return explode(',', $string);
	}

	/**
	 * Explodes a string by tabulator.
	 *
	 * @param string $string String to explode
	 * @return array Resulting array
	 */
	public static function explodeByTab($string)
	{
		return explode("\t", $string);
	}

	/**
	 * Extending PHP's {@link file()} with custom trimming, UTF-8 converting and global data path.
	 *
	 * @param string $filename Name of file
	 * @param int $flags Optional constants
	 * @return array Read in file contents as array
	 */
	public static function file($filename, $flags=null)
	{
		return array_map(array('self', 'utf8_encode'), array_map(function($entry)
		{
			return trim($entry, " \n\r\0\x0B");
		}, file(EXT_PATH_TO_DATA . $filename, $flags)));
	}

	/**
	 * Extending PHP's {@link file_exists()} with global data path.
	 */
	public static function file_exists($filename)
	{
		return file_exists(EXT_PATH_TO_DATA . $filename);
	}

	/**
	 * Extending PHP's {@link file_get_contents()} with UTF-8 converting and global data path.
	 */
	public static function file_get_contents($filename)
	{
		return self::utf8_encode(file_get_contents(EXT_PATH_TO_DATA . $filename, LOCK_SH));
	}

	/**
	 * Returns a formatted date string from proprietary date format.
	 *
	 * @param string $date Proprietary date format (YYYYMMDDhhmmss)
	 * @param string $format Date format pattern to use
	 * @return string Ready-for-use date
	 */
	public static function formatDate($date, $format)
	{
		$timestamp = self::getTimestamp($date);
		//Encode as UTF-8, because month names lacks proper encoding
		return self::utf8_encode(gmstrftime($format, $timestamp));
	}

	/**
	 * Returns linked user profile for given user IDs.
	 *
	 * @param int|string $userID Single or multiple user IDs separated with comma
	 * @param bool $isValid Performs an additional check if user(s) exists to prevent linking deleted profiles
	 * @return string|array Linked user profile(s) or unlinked state(s)
	 */
	public static function getProfileLink($userID, $isValid=false)
	{
		$userLinks = array();
		if(!empty($userID))
			foreach(self::explodeByComma($userID) as $curUserID)
				//Guest check
				if(self::isGuestID($userID))
					$userLinks[] = self::substr($userID, 1);
				//(Optional) deleted check
				elseif($isValid && !self::file_exists('members/' . $curUserID . '.xbb'))
					$userLinks[] = ExtLastPosts::$deleted;
				//Create profile link
				else
				{
					$curUser = self::file('members/' . $curUserID . '.xbb');
					$userLinks[] = '<a href="' . EXT_PATH_TO_FORUM . INDEXFILE . '?faction=profile&amp;profile_id=' . $curUserID . '" target="_blank">' . $curUser[0] . '</a>';
				}
		return count($userLinks) < 2 ? current($userLinks) : $userLinks;
	}

	/**
	 * Returns an unix timestamp with offset from a proprietary date.
	 *
	 * @param string $date Proprietary date format (YYYYMMDDhhmmss)
	 * @return int Unix timestamp
	 */
	public static function getTimestamp($date)
	{
		return mktime(substr($date, 8, 2), substr($date, 10, 2), 0, substr($date, 4, 2), substr($date, 6, 2), substr($date, 0, 4)) + date('Z');
	}

	/**
	 * Returns the name of a topic.
	 *
	 * @param int|string $forumID ID of forum
	 * @param int|string $topicID ID of topic
	 * @return string Name of topic
	 */
	public static function getTopicName($forumID, $topicID)
	{
		return ($topic = self::file('foren/' . $forumID . '-' . $topicID . '.xbb')) == false ? (EXT_IS_UTF8 ? ExtLastPosts::$deleted_moved : utf8_decode(ExtLastPosts::$deleted_moved)) : @next(self::explodeByTab($topic[0]));
	}

	/**
	 * Returns the URL address for a topic smiley.
	 *
	 * @param int|string $tSmileyID ID of topic smiley
	 * @return string Topic smiley address
	 */
	public static function getTSmileyURL($tSmileyID)
	{
		foreach(self::getTSmilies() as $curTSmiley)
			if($curTSmiley[0] == $tSmileyID)
				return EXT_PATH_TO_FORUM . $curTSmiley[1];
		return EXT_PATH_TO_FORUM . 'images/tsmilies/1.gif';
	}

	/**
	 * Returns all current topic smiley URLs.
	 *
	 * @return array Topic smilies
	 */
	public static function getTSmilies()
	{
		if(!isset(self::$cache['tSmileyURLs']))
			self::$cache['tSmileyURLs'] = array_map(array('self', 'explodeByTab'), self::file('vars/tsmilies.var'));
		return self::$cache['tSmileyURLs'];
	}

	/**
	 * Tests an user ID for being a guest ID.
	 *
	 * @param int|string $id User ID to test
	 * @return ID is a guest ID
	 */
	public static function isGuestID($id)
	{
		return strncmp($id, '0', 1) == 0;
	}

	/**
	 * Wraps PHP's {@link substr()} to Multibyte's {@link mb_substr()}, if UTF-8 is enabled.
	 */
	public static function substr($string, $start, $length=null)
	{
		return EXT_IS_UTF8 && function_exists('mb_substr') ? (isset($length) ? mb_substr($string, $start, $length) : mb_substr($string, $start)) : (isset($length) ? substr($string, $start, $length) : substr($string, $start));
	}

	/**
	 * Uses PHP's {@link utf8_encode()} depending on made settings.
	 */
	public static function utf8_encode($data)
	{
		return EXT_IS_UTF8 ? utf8_encode($data) : $data;
	}
}
?>