<?php
/**
 * Various static functions and wrappers.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class FunctionsBasic
{
	/**
	 * Various cached (loaded and fully exploded) data.
	 *
	 * @var array Cached data
	 */
	private static $cache = array();

	/**
	 * Counter for file accesses.
	 *
	 * @var int Amount of file reading and writing
	 */
	private static $fileCounter = 0;

	/**
	 * Adds 'http://' to a link, if needed.
	 *
	 * @param string $link Link to extend with 'http://'
	 * @return string Extended link
	 */
	public static function addHTTP($link)
	{
		return !empty($link) && Functions::substr($link, 0, 7) != 'http://' ? 'http://' . $link : $link;
	}

	/**
	 * Checks current IP for access permission.
	 *
	 * @param int $forumID Only check for a specific forum, whole board otherwise
	 * @return bool|int Access permission granted or ban endtime
	 */
	public static function checkIPAccess($forumID=-1)
	{
		if(!isset(self::$cache['bannedIPs']))
			self::$cache['bannedIPs'] = array_map(array('self', 'explodeByTab'), self::file('vars/ip.var'));
		foreach(self::$cache['bannedIPs'] as $curIP)
			if($curIP[0] == $_SERVER['REMOTE_ADDR'] && $curIP[2] == $forumID && ($curIP[1] > time() || $curIP[1] == '-1'))
				return (int) $curIP[1];
		return true;
	}

	/**
	 * Checks if member has certain access permissions for a forum.
	 *
	 * @param array|int $forum Forum data or forum ID
	 * @param int $what Access level
	 * @return bool Access granted
	 */
	public static function checkUserAccess($forum, $what)
	{
		//Provide proper forum data and permissions
		if(is_numeric($forum))
			$forum = self::getForumData($forum);
		$perms = is_array($forum[10]) ? $forum[10] : Functions::explodeByComma($forum[10]);
		//Check guests
		if(!Main::getModule('Auth')->isLoggedIn())
			return $perms[6] == '1';
		//Allow access for admins or mods of that forum
		if(Main::getModule('Auth')->isAdmin() || self::checkModOfForum($forum))
			return true;
		//Get default permission...
		$canAccess = $perms[$what] == '1';
		//...and check with special ones
		foreach(self::file('foren/' . $forum[0] . '-rights.xbb') as $curSpecialPerm)
		{
			$curSpecialPerm = self::explodeByTab($curSpecialPerm);
			if($curSpecialPerm[1] == '1' && $curSpecialPerm[2] == Main::getModule('Auth')->getUserID() || ($curSpecialPerm[1] == '2' && Main::getModule('Auth')->getGroupID() == $curSpecialPerm[2]))
			{
				if(($canAccess && $curSpecialPerm[$what+3] != '1') || (!$canAccess && $curSpecialPerm[$what+3] == '1'))
					$canAccess = !$canAccess;
				break;
			}
		}
		return $canAccess;
	}

	/**
	 * Censors a string.
	 *
	 * @param string $string Text to censor
	 * @return string Censored text
	 */
	public static function censor($string)
	{
		if(!isset(self::$cache['censoredWords']))
			self::$cache['censoredWords'] = array_map(array('self', 'explodeByTab'), self::file('vars/cwords.var'));
		foreach(self::$cache['censoredWords'] as $curWord)
			$string = Functions::str_ireplace($curWord[1], $curWord[2], $string);
		return $string;
	}

	/**
	 * Checks current user has moderator permissions in a forum.
	 *
	 * @param array|int $forum Forum data or forum ID
	 * @return bool Moderator permissions of stated forum
	 */
	public static function checkModOfForum($forum)
	{
		//Super mods can access all
		if(Main::getModule('Auth')->isSuperMod())
			return true;
		//Provide proper forum data
		if(is_numeric($forum))
			$forum = self::getForumData($forum);
		//Check moderator permissions
		return !empty($forum[11]) && in_array(Main::getModule('Auth')->getUserID(), self::explodeByComma($forum[11]));
	}

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
	 * Extending PHP's {@link file()} with file counting, custom trimming, UTF-8 converting and global data path.
	 *
	 * @param string $filename Name of file
	 * @param int $flags Optional constants
	 * @param string $trimCharList Characters to trim from each entry (default: all except \t)
	 */
	public static function file($filename, $flags=null, $trimCharList=null)
	{
		self::$fileCounter++;
		$trimCallback = create_function('$entry', 'return trim($entry, "' . (empty($trimCharList) ? ' \n\r\0\x0B' : $trimCharList) . '");');
		return array_map('utf8_encode', array_map($trimCallback, file(DATAPATH . $filename, $flags)));
	}

	/**
	 * Extending PHP's {@link file_exists()} with global data path.
	 */
	public static function file_exists($filename)
	{
		return file_exists(DATAPATH . $filename);
	}

	/**
	 * Extending PHP's {@link file_get_contents()} with file counting, UTF-8 converting and global data path.
	 */
	public static function file_get_contents($filename)
	{
		self::$fileCounter++;
		return utf8_encode(file_get_contents(DATAPATH . $filename));
	}

	/**
	 * Extending PHP's {@link file_put_contents()} with file counting, UTF-8 converting and global data path.
	 * <b>Be very careful changing the $decUTF8 parameter and disabling the UTF-8 decoder! There is usually no need to do this.</b>
	 */
	public static function file_put_contents($filename, $data, $flags=LOCK_EX, $decUTF8=true)
	{
		self::$fileCounter++;
		return file_put_contents(DATAPATH . $filename, $decUTF8 ? utf8_decode($data) : $data, $flags);
	}

	/**
	 * Returns a formatted date string from proprietary date format.
	 *
	 * @param string $date Proprietary date format (YYYYMMDDhhmmss)
	 * @param string $format Alternative pattern to use
	 * @return string Ready-for-use date
	 */
	public static function formatDate($date, $format=null)
	{
		$gmtOffset = Main::getModule('Config')->getCfgVal('gmt_offset');
		$offset = Functions::substr($gmtOffset, 1, 2)*3600 + Functions::substr($gmtOffset, 3, 2)*60;
		$timestamp = mktime(substr($date, 8, 2), substr($date, 10, 2), 0, substr($date, 4, 2), substr($date, 6, 2), substr($date, 0, 4)) + ($gmtOffset[0] == '-' ? $offset*-1 : $offset) + date('Z');
		//Encode as UTF-8, because month names lacks proper encoding
		return sprintf((time()-$timestamp) < Main::getModule('Config')->getCfgVal('emph_date_hours')*3600 ? '<b>%s</b>' : '%s', utf8_encode(gmstrftime(isset($format) ? $format : Main::getModule('Language')->getString('DATEFORMAT'), $timestamp)));
	}

	/**
	 * Returns amount of file accesses.
	 *
	 * @return int File counter
	 */
	public static function getFileCounter()
	{
		return self::$fileCounter;
	}

	/**
	 * Returns data of a forum.
	 *
	 * @param int $forumID ID of forum
	 * @return array|bool Forum data or false if forum was not found
	 */
	public static function getForumData($forumID)
	{
		if(!isset(self::$cache['forums']))
		{
			self::$cache['forums'] = self::file('vars/foren.var');
			foreach(self::$cache['forums'] as &$curForum)
			{
				$curForum = self::explodeByTab($curForum);
				$curForum[7] = self::explodeByComma($curForum[7]); //BBCode options
				$curForum[10] = self::explodeByComma($curForum[10]); //Permissions
			}
		}
		foreach(self::$cache['forums'] as $curForum)
			if($curForum[0] == $forumID)
				return $curForum;
		return false;
	}

	/**
	 * Returns data of a group.
	 *
	 * @param int $groupID ID of group
	 * @return array|bool Group data or false if group was not found
	 */
	public static function getGroupData($groupID)
	{
		if(!isset(self::$cache['groups']))
		{
			self::$cache['groups'] = self::file('vars/groups.var');
			foreach(self::$cache['groups'] as &$curGroup)
				$curGroup[3] = self::explodeByComma($curGroup[3]);
		}
		foreach(self::$cache['groups'] as $curGroup)
			if($curGroup[0] == $groupID)
			{
				$curGroup[3] = self::explodeByComma($curGroup[3]);
				return $curGroup;
			}
		return false;
	}

	/**
	 * Returns linked user profile for given user IDs.
	 *
	 * @param int|string $userID Single or multiple user IDs separated with comma
	 * @param bool $isValid Performs an additional check if user(s) exists to prevent linking deleted profiles
	 * @param string $aAttributes Additional attributes for profile link tag, start with space!
	 * @param bool $colorRank Emphasize linked names with corresponding rank color
	 * @return string Linked user profile(s) as one string
	 */
	public static function getProfileLink($userID, $isValid=false, $aAttributes=null, $colorRank=false)
	{
		$userLinks = array();
		if(!empty($userID))
			foreach(self::explodeByComma($userID) as $curUserID)
				if($isValid && !self::file_exists('members/' . $curUserID . '.xbb'))
					$userLinks[] = Main::getModule('Language')->getString('deleted');
				else
				{
					$curUser = Functions::file('members/' . $curUserID . '.xbb');
					$curColor = '';
					if($colorRank)
					{
						switch($curUser[4])
						{
							case '1':
							$curColor = Main::getModule('Config')->getCfgVal('wio_color_admin');
							break;

							case '2':
							$curColor = Main::getModule('Config')->getCfgVal('wio_color_mod');
							break;

							case '3':
							$curColor = Main::getModule('Config')->getCfgVal('wio_color_member');
							break;

							case '4':
							$curColor = Main::getModule('Config')->getCfgVal('wio_color_banned');
							break;

							case '6':
							$curColor = Main::getModule('Config')->getCfgVal('wio_color_smod');
							break;
						}
						if(!empty($curColor))
							$curColor = sprintf(' style="color:%s";', $curColor);
					}
					$userLinks[] = '<a' . $aAttributes . ' href="' . INDEXFILE . '?faction=profile&amp;profile_id=' . $curUserID . SID_AMPER . '"' . $curColor . '>' . $curUser[0] . '</a>';
				}
		return implode(', ', $userLinks);
	}

	/**
	 * Returns calculated rank images according to user state and/or amount of posts.
	 *
	 * @param int $userState State of user
	 * @param int $userPosts Posts of user
	 * @return string Rank image(s)
	 */
	public static function getRankImage($userState, $userPosts)
	{
		if($userPosts < 0)
			return '';
		switch($userState)
		{
			case '1':
			$rankImage = array_fill(0, Main::getModule('Config')->getCfgVal('stars_admin'), 'rstar');
			break;

			case '2':
			$rankImage = array_fill(0, Main::getModule('Config')->getCfgVal('stars_mod'), 'gstar');
			break;

			case '3':
			case '4':
			if(!isset(self::$cache['ranks']))
				self::$cache['ranks'] = array_map(array('self', 'explodeByTab'), self::file('vars/rank.var'));
			foreach(self::$cache['ranks'] as $curRank)
				if($userPosts >= $curRank[2] && $userPosts <= $curRank[3])
					$rankImage = array_fill(0, $curRank[4], 'ystar');
			break;

			case '6':
			$rankImage = array_fill(0, Main::getModule('Config')->getCfgVal('stars_smod'), 'bstar');
			break;
		}
		return '<img src="images/ranks/' . implode('.gif" alt="*" /><img src="images/ranks/', $rankImage) . '.gif" alt="*" />';
	}

	/**
	 * Returns display name from an user state.
	 *
	 * @param int $userState State of user
	 * @param int $userPosts Posts of user
	 * @return string Display name for user state
	 */
	public static function getStateName($userState, $userPosts)
	{
		switch($userState)
		{
			case '1':
			return Main::getModule('Config')->getCfgVal('var_admin');
			break;

			case '2':
			return Main::getModule('Config')->getCfgVal('var_mod');
			break;

			case '3':
			if(!isset(self::$cache['ranks']))
				self::$cache['ranks'] = array_map(array('self', 'explodeByTab'), self::file('vars/rank.var'));
			foreach(self::$cache['ranks'] as $curRank)
				if($userPosts >= $curRank[2] && $userPosts <= $curRank[3])
					return $curRank[1];
			break;

			case '4':
			return Main::getModule('Config')->getCfgVal('var_banned');
			break;

			case '5':
			return Main::getModule('Config')->getCfgVal('var_killed');
			break;

			case '6':
			return Main::getModule('Config')->getCfgVal('var_smod');
			break;

			default:
			return '';
			break;
		}
	}

	/**
	 * Returns data of an user.
	 *
	 * @param int $userID ID of user
	 * @return array|bool User data or false if user was not found, is a guest or is deleted
	 */
	public static function getUserData($userID)
	{
		if($userID == 0 || !($user = self::file('members/' . $userID . '.xbb')) || $user[4] == '5')
			return false;
		$user[14] = self::explodeByComma($user[14]);
		return $user;
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
		return !($topic = self::file('foren/' . $forumID . '-' . $topicID . '.xbb')) ? Main::getModule('Language')->getString('deleted_moved') : next(self::explodeByTab($topic[0]));
	}

	/**
	 * Returns the URL address for a topic smiley.
	 *
	 * @param int|string $tSmileyID ID of topic smiley
	 * @return string Topic smiley address
	 */
	public static function getTSmileyURL($tSmileyID)
	{
		if(!isset(self::$cache['tSmileyURLs']))
			self::$cache['tSmileyURLs'] = array_map(array('self', 'explodeByTab'), self::file('vars/tsmilies.var'));
		foreach(self::$cache['tSmileyURLs'] as $curTSmiley)
			if($curTSmiley[0] == $tSmileyID)
				return $curTSmiley[1];
		return 'images/tsmilies/1.gif';
	}

	/**
	 * Shortens a string to stated length by appending dots.
	 *
	 * @param string $string String to shorten
	 * @param int $maxLength Maximal length of string may have
	 * @return string Shortened string
	 */
	public static function shorten($string, $maxLength)
	{
		if(Functions::strlen($string) > $maxLength)
			$string = Functions::substr($curTopicTitle, 0, $maxLength-3) . Main::getModule('Language')->getString('dots');
		return $string;
	}

	/**
	 * Applies {@link stripslashes()} recursively on arrays as well.
	 *
	 * @param mixed $value Input value(s) to strip backslashes off
	 * @return mixed Input value(s) with backslashes stripped off
	 */
	public static function stripSlashesDeep($value)
	{
		return is_array($value) ? array_map(array('Functions', 'stripSlashesDeep'), $value) : stripslashes($value);
	}
}
?>