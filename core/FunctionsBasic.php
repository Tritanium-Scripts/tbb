<?php
/**
 * Various static functions and wrappers.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2015 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.6
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
	 * All read in contents from files are cached here.
	 * 0: Data as arrays
	 * 1: Single strings
	 *
	 * @var array Cached file contents subdivided in exploded (file()) and single lines (f_g_c())
	 */
	private static $fileCache = array();

	/**
	 * Counter for file accesses.
	 *
	 * @var int Amount of file reading and writing
	 */
	private static $fileCounter = 0;

	/**
	 * Controls file caching.
	 *
	 * @var bool Use file caching
	 */
	private static $isCaching = true;

	/**
	 * Some ISO-8859-15 characters not part of ISO-8859-1 to search for.
	 *
	 * @var array ISO-8859-15 characters to handle for conversion
	 */
	private static $latin9Chars = array('€', 'Š', 'š', 'Ž', 'ž', 'Œ', 'œ', 'Ÿ');

	/**
	* Counterparts of ISO-8859-15 as (X)HTML entities for replacement.
	*
	* @var array (X)HTML entities used for replacement in conversion
	*/
	private static $latin9Entities = array('&euro;', '&Scaron;', '&scaron;', '&#142;', '&#158;', '&OElig;', '&oelig;', '&Yuml;');

	/**
	 * Default operations while accessing the admin panel.
	 */
	public static function accessAdminPanel()
	{
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('administration'), INDEXFILE . '?faction=adminpanel' . SID_AMPER);
		if(!Main::getModule('Auth')->isAdmin())
		{
			Main::getModule('Logger')->log('%s tried to access administration', LOG_ACP_ACCESS);
			Main::getModule('Template')->printMessage('permission_denied');
		}
		//Log first entering of any admin panel site
		if(@Functions::stripos($_SERVER['HTTP_REFERER'], 'faction=ad') === false)
			Main::getModule('Logger')->log('%s entered administration', LOG_ACP_ACTION);
		Main::getModule('Config')->setCfgVal('twidth', '100%');
		Main::getModule('Language')->parseFile('AdminIndex'); //This is the 'AdminMain.ini'
	}

	/**
	 * Adds 'http://' to a link, if needed. Ignores relative links to internal upload folder.
	 *
	 * @param string $link Link to extend with 'http://'
	 * @return string Extended link
	 */
	public static function addHTTP($link)
	{
		return !empty($link) && Functions::substr($link, 0, 8) != 'uploads/' && Functions::stripos($link, '://') === false ? 'http://' . $link : $link;
	}

	/**
	 * Adds [url]-BBCode to links found in a string, which are not encapsulated by certain BBCodes.
	 *
	 * @param string $subject String to search for links and formatting them
	 * @return string Result string
	 */
	public static function addURL($subject)
	{
		$tempBBCode = time(); //This is the placeholder for "url"
		$subject = preg_replace_callback("/([^ ^>^\]^=^\n^\r]+?:\/\/|www\.)[^ ^<^\.^\[]+(\.[^ ^<^\.^\[^\]^\n^\r]+)+/si", create_function('$arr', 'return !empty($arr[2]) && Functions::stripos($arr[0], \'[url]\') === false && Functions::strripos($arr[0], \'[/url]\') === false ? \'[' . $tempBBCode . ']\' . ($arr[1] == \'www.\' ? \'http://\' : \'\') . $arr[0] . \'[/' . $tempBBCode . ']\' : $arr[0];'), $subject);
		//After adding [url]s to *any* link, strip off unwanted ones:
		foreach(array('iframe', 'flash', 'url', 'img', 'email', 'code', 'php', 'noparse') as $curBBCode)
		{
			//Remove the simple ones, e.g. [flash][url]xxx[/url][/flash]
			$subject = Functions::str_ireplace(array('[' . $curBBCode . '][' . $tempBBCode . ']', '[/' . $tempBBCode . '][/' . $curBBCode . ']'), array('[' . $curBBCode . ']', '[/' . $curBBCode . ']'), $subject);
			//Remove the advanced ones having any attributes (only start tags are affected), e.g. [flash=xxx,xxx][url]xxx[/flash]
			$subject = preg_replace("/(\[" . $curBBCode . "=.*?\])\[" . $tempBBCode . "\]/si", '\1', $subject);
			//Remove attributed ones in start tags, e.g. [img=[url]xxx[/url]]
			$subject = preg_replace("/(\[" . $curBBCode . "=)\[" . $tempBBCode . "\](.*?)\[\/" . $tempBBCode . "\]\]/si", '\1\2]', $subject);
		}
		//Finally add proper [url]s
		$subject = Functions::str_replace(array('[' . $tempBBCode . ']', '[/' . $tempBBCode . ']'), array('[url]', '[/url]'), $subject);
		return $subject;
	}

	/**
	 * Reverts PHP's {@link nl2br()} incl. XHTML versions.
	 *
	 * @param string $string String for search and replace of br-tags
	 * @return string Processed string
	 */
	public static function br2nl($string)
	{
		return Functions::str_replace(array('<br>', '<br/>', '<br />'), "\n", $string);
	}

	/**
	 * Censors a string.
	 *
	 * @param string $string Text to censor
	 * @return string Censored text
	 */
	public static function censor($string)
	{
		if(Main::getModule('Config')->getCfgVal('censored') != 1)
			return $string;
		if(!isset(self::$cache['censoredWords']))
			self::$cache['censoredWords'] = array_map(array('self', 'explodeByTab'), self::file('vars/cwords.var'));
		foreach(self::$cache['censoredWords'] as $curWord)
			$string = Functions::str_ireplace($curWord[1], $curWord[2], $string);
		return $string;
	}

	/**
	 * Checks current or stated IP address for access permission.
	 *
	 * @param int $forumID Only check for a specific forum, entire board otherwise
	 * @param string $ipAddress Check for this specific IP, current otherwise
	 * @return bool|int Access permission granted or ban endtime
	 */
	public static function checkIPAccess($forumID=-1, $ipAddress=null)
	{
		if(empty($ipAddress))
			$ipAddress = $_SERVER['REMOTE_ADDR'];
		foreach(self::getBannedIPs() as $curIP)
			if($curIP[0] == $ipAddress && $curIP[2] == $forumID && ($curIP[1] > time() || $curIP[1] == '-1'))
				return (int) $curIP[1];
		return true;
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
	 * Checks if member/guest has certain access permissions for a forum.
	 *
	 * @param array|int $forum Forum data or forum ID
	 * @param int $what Access level
	 * @param int $whatGuest Access level for guest
	 * @return bool Access granted
	 */
	public static function checkUserAccess($forum, $what, $whatGuest=6)
	{
		//Provide proper forum data and permissions
		if(is_numeric($forum))
			$forum = self::getForumData($forum);
		$perms = is_array($forum[10]) ? $forum[10] : Functions::explodeByComma($forum[10]);
		//Check guests
		if(!Main::getModule('Auth')->isLoggedIn())
			return $perms[$whatGuest] == '1';
		//Allow access for admins or mods of that forum
		if(Main::getModule('Auth')->isAdmin() || self::checkModOfForum($forum))
			return true;
		//Get default permission...
		$canAccess = $perms[$what] == '1';
		//...and check with special ones
		if(self::file_exists('foren/' . $forum[0] . '-rights.xbb'))
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
	 * Extending PHP's {@link file()} with caching, file counting, custom trimming, UTF-8 converting and global data path.
	 *
	 * @param string $filename Name of file
	 * @param int $flags Optional constants
	 * @param string $trimCharList Characters to trim from each entry (default: all except \t)
	 * @param bool $datapath Apply the global datapath to filename, there is usually no need to change this
	 * @return array Read in file contents as array
	 */
	public static function file($filename, $flags=null, $trimCharList=null, $datapath=true)
	{
		$trimCallback = create_function('$entry', 'return trim($entry, "' . (empty($trimCharList) ? ' \n\r\0\x0B' : $trimCharList) . '");');
		if($datapath && self::$isCaching)
		{
			if(isset(self::$fileCache[$filename][0]))
				return array_map('utf8_encode', array_map($trimCallback, self::$fileCache[$filename][0]));
			self::$fileCounter++;
			return array_map('utf8_encode', array_map($trimCallback, self::$fileCache[$filename][0] = file(DATAPATH . $filename, $flags)));
		}
		self::$fileCounter++;
		return array_map('utf8_encode', array_map($trimCallback, file(($datapath ? DATAPATH : '') . $filename, $flags)));
	}

	/**
	 * Extending PHP's {@link file_exists()} with global data path.
	 */
	public static function file_exists($filename)
	{
		return file_exists(DATAPATH . $filename);
	}

	/**
	 * Extending PHP's {@link file_get_contents()} with caching, file counting, UTF-8 converting and global data path.
	 */
	public static function file_get_contents($filename)
	{
		//Use file caching
		if(self::$isCaching)
		{
			if(isset(self::$fileCache[$filename][1]))
				return utf8_encode(self::$fileCache[$filename][1]);
			self::$fileCounter++;
			return utf8_encode(self::$fileCache[$filename][1] = file_get_contents(DATAPATH . $filename, LOCK_SH));
		}
		//Use no caching
		self::$fileCounter++;
		return utf8_encode(file_get_contents(DATAPATH . $filename, LOCK_SH));
	}

	/**
	 * Extending PHP's {@link file_put_contents()} with file counting, UTF-8 converting, Latin-9 handling and global data path.
	 * <b>Be very careful changing the $decUTF8 parameter and disabling the UTF-8 decoder! There is usually no need to do this.</b>
	 *
	 * @param string $filename Name of file
	 * @param mixed $data Data to write
	 * @param int $flags Optional constants
	 * @param bool $decUTF8 Decode UTF-8 data to ISO-8859-1, <b>do not change this unless you really know what you are doing!</b>
	 * @param bool $datapath Apply the global datapath to filename, there is usually no need to change this
	 * @return int|bool Number of bytes written to file or false on failure
	 */
	public static function file_put_contents($filename, $data, $flags=LOCK_EX, $decUTF8=true, $datapath=true)
	{
		if(self::$isCaching)
			unset(self::$fileCache[$filename]);
		self::$fileCounter++;
		return file_put_contents(($datapath ? DATAPATH : '') . $filename, $decUTF8 ? utf8_decode(self::latin9ToEntities($data)) : $data, $flags);
	}

	/**
	 * Returns a formatted date string from proprietary date format.
	 *
	 * @param string $date Proprietary GMT date format (YYYYMMDDhhmmss)
	 * @param string $format Alternative pattern to use
	 * @return string Ready-for-use date
	 */
	public static function formatDate($date, $format=null)
	{
		$timestamp = self::getTimestamp($date);
		//Encode as UTF-8, because month names lack proper encoding
		return sprintf(time()-$timestamp < Main::getModule('Config')->getCfgVal('emph_date_hours')*3600 ? '<b>%s</b>' : '%s', utf8_encode(gmstrftime(isset($format) ? $format : Main::getModule('Language')->getString(Main::getModule('Config')->getCfgVal('date_as_text') == 1 && self::getProperYz(time()-86400) <= ($yz = self::getProperYz($timestamp)) ? (self::getProperYz(time()) == $yz ? 'TODAY_DATEFORMAT' : 'YESTERDAY_DATEFORMAT') : 'DATEFORMAT'), $timestamp)));
	}

	/**
	 * Returns current blocked IP addresses.
	 *
	 * @return array Fully exploded banned IP addresses
	 */
	public static function getBannedIPs()
	{
		if(!isset(self::$cache['bannedIPs']))
		{
			self::$cache['bannedIPs'] = array_map(array('self', 'explodeByTab'), self::file('vars/ip.var'));
			if(!isset(self::$cache['bannedIPs'][0][1]))
				self::$cache['bannedIPs'] = array();
		}
		return self::$cache['bannedIPs'];
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
	 * Returns permission for exclusive file usage, but the file is not specified here.
	 *
	 * @param string $name Name of file lock instance
	 * @return bool Exclusive lock granted
	 * @see releaseLock()
	 * @see getLockObject()
	 */
	public static function getFileLock($name)
	{
		self::$cache['locks'][$name] = fopen(DATAPATH . 'vars/' . $name . '.lock', 'w');
		($locked = flock(self::$cache['locks'][$name], LOCK_EX)) or Main::getModule('Logger')->log('Error getting ' . $name . ' file lock!', LOG_FILESYSTEM);
		return $locked;
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
			unset($curForum); //Delete remaining reference to avoid conflicts
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
			self::$cache['groups'] = array_map(array('self', 'explodeByTab'), self::file('vars/groups.var'));
			foreach(self::$cache['groups'] as &$curGroup)
				$curGroup[3] = self::explodeByComma($curGroup[3]);
			unset($curGroup); //Delete remaining reference to avoid conflicts
		}
		foreach(self::$cache['groups'] as $curGroup)
			if($curGroup[0] == $groupID)
				return $curGroup;
		return false;
	}

	/**
	 * Returns hash value for stated string.
	 * If supported, SHA-2 (SHA-512) will be used, DES as fallback and downward compatibility to TBB 1.2.3 otherwise.
	 *
	 * @param string $string String to hash with SHA-2 (or DES)
	 * @return string Hash value of string
	 */
	public static function getHash($string)
	{
		return function_exists('hash') ? hash('sha512', $string) : crypt($string, 'Xb');
	}

	/**
	 * Returns a translation table for the common HTML entities and their unicode hexadecimal representation for JavaScript environments.
	 * Use this decoder to max out user comfort and valid W3C conform code. Cranks up leet level quite high!
	 *
	 * @return mixed Translation table between HTML entities and their JavaScript counterparts
	 */
	public static function getHTMLJSTransTable()
	{
		return isset(self::$cache['htmlJSDecoder']) ? self::$cache['htmlJSDecoder'] : (self::$cache['htmlJSDecoder'] = array_combine(array_keys($temp = array_flip($temp = get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES))+array('&#' . (in_array('&#39;', $temp) ? '0' : '') . '39;' => "'", '&apos;' => "'")), array_map(create_function('$string', 'return \'\u00\' . bin2hex($string);'), array_values($temp))));
	}

	/**
	 * Returns remote IP address based on the directive to save them or not.
	 *
	 * @return string IP address to save with postings
	 */
	public static function getIPAddress()
	{
		return ($saveIPAddress = Main::getModule('Config')->getCfgVal('save_ip_address')) > 0 ? ($saveIPAddress == 2 && Main::getModule('Auth')->isLoggedIn() ? '' : $_SERVER['REMOTE_ADDR']) : '';
	}

	/**
	 * Returns additional supported ISO-8859-15 characters.
	 *
	 * @return array (X)HTML entities of additional supported ISO-8859-15 characters
	 */
	public static function getLatin9Entities()
	{
		return self::$latin9Entities;
	}

	/**
	 * Returns a new LockObject instance for saver file reading and writing with exclusive locking.
	 * Filename will be extended with global data path.
	 *
	 * @param string $filename Name/path of file to initiate the LockObject with
	 * @return LockObject New LockObject instance
	 */
	public static function getLockObject($filename)
	{
		include_once('LockObject.php');
		return new LockObject(DATAPATH . $filename);
	}

	/**
	 * Compiles back links for forum messages. A link to the forum index will always be generated.
	 *
	 * @param int $forumID Generates back link to topics of this forum, if provided
	 * @param int $topicID Generates back link to posts of this topic, if provided (needs $topicMsgIndex)
	 * @param string $msgIndex Identifier of message to display for topic link
	 * @param int $postID Extends back link of this topic with link to single post, if provided
	 * @param int|string $postOnPage Optional topic page number of single post
	 * @return string Compiled back links
	 */
	public static function getMsgBackLinks($forumID=null, $topicID=null, $msgIndex='back_to_topic', $postID=null, $postOnPage='last')
	{
		return '<br />' . (isset($forumID) ? (isset($topicID) ? sprintf(Main::getModule('Language')->getString($msgIndex, 'Messages'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $forumID . '&amp;thread=' . $topicID . (isset($postID) ? '&amp;z=' . $postOnPage . SID_AMPER . '#post' . $postID : SID_AMPER)) . '<br />'  : '') . sprintf(Main::getModule('Language')->getString('back_to_topic_index', 'Messages'), INDEXFILE . '?mode=viewforum&amp;forum_id=' . $forumID . SID_AMPER) . '<br />' : '') . sprintf(Main::getModule('Language')->getString('back_to_forum_index', 'Messages'), INDEXFILE . SID_QMARK);
	}

	/**
	 * Returns linked user profile for given user IDs.
	 *
	 * @param int|string $userID Single or multiple user IDs separated with comma
	 * @param bool $isValid Performs an additional check if user(s) exists to prevent linking deleted profiles
	 * @param string $aAttributes Additional attributes for profile link tag, start with space!
	 * @param bool $colorRank Emphasize linked names with corresponding rank color - setting $isValid to true would be a good idea
	 * @return string|array Linked user profile(s) or unlinked state(s)
	 */
	public static function getProfileLink($userID, $isValid=false, $aAttributes=null, $colorRank=false)
	{
		$userLinks = array();
		if(!empty($userID))
			foreach(self::explodeByComma($userID) as $curUserID)
				//Guest check
				if(self::isGuestID($userID))
					$userLinks[] = Functions::substr($userID, 1);
				//(Optional) deleted check
				elseif($isValid && !self::file_exists('members/' . $curUserID . '.xbb'))
					$userLinks[] = Main::getModule('Language')->getString('deleted');
				//Create profile link
				else
				{
					$curUser = self::file('members/' . $curUserID . '.xbb');
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
							$curColor = Main::getModule('Config')->getCfgVal('wio_color_user');
							break;

							case '4':
							$curColor = Main::getModule('Config')->getCfgVal('wio_color_banned');
							break;

							case '6':
							$curColor = Main::getModule('Config')->getCfgVal('wio_color_smod');
							break;
						}
						if(!empty($curColor))
							$curColor = sprintf(' style="color:%s;"', $curColor);
					}
					$userLinks[] = '<a' . $aAttributes . ' href="' . INDEXFILE . '?faction=profile&amp;profile_id=' . $curUserID . SID_AMPER . '"' . $curColor . '>' . $curUser[0] . '</a>';
				}
		return count($userLinks) < 2 ? current($userLinks) : $userLinks;
	}

	/**
	 * Returns "Yz" call from {@link gmdate()} with proper padded zeros of the day of year.
	 *
	 * @param int $timestamp Timestamp to use
	 * @return string Proper "Yz" result
	 */
	 public static function getProperYz($timestamp)
	 {
		return gmdate('Y', $timestamp) . sprintf('%03d', gmdate('z', $timestamp));
	 }

	/**
	 * Generates a 10-character random password incl. special chars.
	 *
	 * @return string Random password
	 */
	public static function getRandomPass()
	{
		for($i=0,$newPass=''; $i<10; $i++)
			$newPass .= chr(mt_rand(33, 126));
		return $newPass;
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
			foreach(self::getRanks() as $curRank)
				if($userPosts >= $curRank[2] && $userPosts <= $curRank[3])
					$rankImage = array_fill(0, $curRank[4], 'ystar');
			break;

			case '6':
			$rankImage = array_fill(0, Main::getModule('Config')->getCfgVal('stars_smod'), 'bstar');
			break;
		}
		return isset($rankImage) ? '<img src="images/ranks/' . implode('.gif" alt="*" /><img src="images/ranks/', $rankImage) . '.gif" alt="*" />' : '';
	}

	/**
	 * Returns fully exploded user ranks.
	 *
	 * @return array Exploded user ranks
	 */
	public static function getRanks()
	{
		if(!isset(self::$cache['ranks']))
			self::$cache['ranks'] = array_map(array('self', 'explodeByTab'), self::file('vars/rank.var'));
		return self::$cache['ranks'];
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
			foreach(self::getRanks() as $curRank)
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
	 * Returns an GMT unix timestamp with timezone offset and daylight saving time offset from a proprietary date.
	 *
	 * @param string $date Proprietary date format (YYYYMMDDhhmmss)
	 * @return int Unix GMT timestamp with added offsets
	 */
	public static function getTimestamp($date)
	{
		$gmtOffset = Main::getModule('Config')->getCfgVal('gmt_offset');
		$offset = Functions::substr($gmtOffset, 1, 2)*3600 + Functions::substr($gmtOffset, 3, 2)*60;
		//GMT timestamp + timezone offset + DST offset
		return gmmktime(substr($date, 8, 2), substr($date, 10, 2), substr($date, 12, 2), substr($date, 4, 2), substr($date, 6, 2), substr($date, 0, 4)) + ($gmtOffset[0] == '-' ? $offset*-1 : $offset) + date('I')*3600;
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
		return ($topic = self::file('foren/' . $forumID . '-' . $topicID . '.xbb')) == false ? Main::getModule('Language')->getString('deleted_moved') : @next(self::explodeByTab($topic[0]));
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
				return $curTSmiley[1];
		return 'images/tsmilies/1.gif';
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
	 * Returns fully exploded data of an user.
	 *
	 * @param int $userID ID of user
	 * @return array|bool User data or false if user was not found, is a guest or is deleted
	 */
	public static function getUserData($userID)
	{
		if(self::isGuestID($userID) || !($user = @self::file('members/' . $userID . '.xbb')) || $user[4] == '5')
			return false;
		$user[14] = self::explodeByComma($user[14]); //Mail options
		//Downward compatibility: Create fields that doesn't exist in TBB 1.2.3
		if(!isset($user[16]))
			$user[16] = '';
		if(!isset($user[17]))
			$user[17] = '';
		if(!isset($user[18]))
			$user[18] = '';
		$user[19] = isset($user[19]) && !empty($user[19]) ? self::explodeByTab($user[19]) : array();
		if(!isset($user[20]))
			$user[20] = '';
		if(!isset($user[21]))
			$user[21] = '';
		return $user;
	}

	/**
	 * Returns a value from superglobals in order GET and POST.
	 * Strips off tab characters and optional newlines.
	 *
	 * @param string $key Key identifier for array access in superglobals
	 * @param bool $stripNewLine Optional removal of new line characters
	 * @return mixed Value from one of the superglobals or empty string if it was not found
	 */
	public static function getValueFromGlobals($key, $stripNewLine=true)
	{
		return Functions::str_replace($stripNewLine ? array("\t", "\n", "\r") : "\t", '', isset($_GET[$key]) ? $_GET[$key] : (isset($_POST[$key]) ? $_POST[$key] : ''));
	}

	/**
	 * Extending PHP's {@link glob()} to handle invalid return values.
	 *
	 * @param string $pattern The pattern to use
	 * @param int $flags Optional flags to use
	 * @return array Matched files/directories or empty array
	 */
	public static function glob($pattern, $flags=null)
	{
		return is_array($files = glob($pattern, $flags)) ? $files : array();
	}

	/**
	 * Handles and returns template filename requested from given mode translation table and logs unknown modes.
	 * By having encountered a certain number of unknown modes, the IP address will be banned.
	 *
	 * @param string $mode Requested template file for this mode
	 * @param string $modeTable Mode and template counterparts
	 * @param string $module Name of executing module
	 * @param string $defaultMode Another default mode to use in case of unknown mode
	 * @return string Name of template file from stated table
	 */
	public static function handleMode(&$mode, &$modeTable, $module, $defaultMode='')
	{
		if(!array_key_exists($mode, $modeTable))
		{
			//Escaping of '%' to protect logger
			Main::getModule('Logger')->log('Unknown mode "' . Functions::str_replace('%', '%%', $mode) . '" in ' . $module . '; using default', LOG_FILESYSTEM);
			isset($_SESSION['unknownModes']) ? $_SESSION['unknownModes']++ : $_SESSION['unknownModes'] = 0;
			if($_SESSION['unknownModes'] > mt_rand(5, 10))
			{
				list(,,,$lastIPID) = @end(Functions::getBannedIPs());
				Functions::file_put_contents('vars/ip.var', $_SERVER['REMOTE_ADDR'] . "\t-1\t-1\t" . ($lastIPID+1) . "\t\n", FILE_APPEND);
				Main::getModule('Logger')->log('Auto-banned %s after catching ' . $_SESSION['unknownModes'] . ' unknown modes of a possible hacking attempt!', LOG_ACP_ACTION);
			}
			$mode = $defaultMode;
		}
		return $modeTable[$mode];
	}

	/**
	 * Implodes an array by tabulator.
	 *
	 * @param array $pieces Array to implode
	 * @return string Resulting string
	 */
	public static function implodeByTab($pieces)
	{
		return implode("\t", $pieces);
	}

	/**
	 * Tests an user ID for being a guest ID.
	 *
	 * @param int|string $id User ID to test
	 * @return bool ID is a guest ID
	 */
	public static function isGuestID($id)
	{
		return strncmp($id, '0', 1) == 0;
	}

	/**
	 * Verifies an e-mail address.
	 *
	 * @param string $mailAddress The e-mail address to check
	 * @return bool Valid e-mail address
	 */
	public static function isValidMail($mailAddress)
	{
		return (bool) preg_match('/[\.0-9a-z_-]+@[\.0-9a-z-]+\.[a-z]+/si', $mailAddress);
	}

	/**
	 * Converts certain ISO-8859-15 characters (not included in ISO-8859-1) to (X)HTML entities.
	 * This can be used as a temporary € sign fix for the ISO-8859-1 database until the TBB 2.0 is out.
	 *
	 * @param string $data String to convert
	 * @return string Converted string
	 */
	public static function latin9ToEntities($data)
	{
		return Functions::str_replace(self::$latin9Chars, self::$latin9Entities, $data);
	}

	/**
	 * Extending PHP's {@link nl2br()} with stripping of all newline chars.
	 *
	 * @param string $string String for search and replace of newlines
	 * @return string Processed string
	 */
	public static function nl2br($string)
	{
		return str_replace(array("\n", "\r"), '', nl2br($string));
	}

	/**
	 * Releases prior granted exclusive file usage.
	 *
	 * @param mixed $name Name of file lock instance to release
	 * @see getFileLock()
	 */
	public static function releaseLock($name)
	{
		flock(self::$cache['locks'][$name], LOCK_UN) or Main::getModule('Logger')->log('Error releasing ' . $name . ' file lock!', LOG_FILESYSTEM);
		fclose(self::$cache['locks'][$name]);
	}

	/**
	 * Sets using file caching feature.
	 *
	 * @param bool $caching Use file caching
	 */
	public static function setFileCaching($caching=true)
	{
		self::$isCaching = $caching;
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
			$string = Functions::substr($string, 0, $maxLength-3) . Main::getModule('Language')->getString('dots');
		return $string;
	}

	/**
	 * Sends redirect header for stated URL to skip confirmation messages, if enabled.
	 *
	 * @param mixed $url Redirect URL
	 */
	public static function skipConfirmMessage($url)
	{
		if(Main::getModule('Config')->getCfgVal('skip_confirm_msg') == 1)
			header('Location: ' . $url);
	}

	/**
	 * Strips SID parameter URLs from a string.
	 *
	 * @param string $subject String to strip SIDs off
	 * @return string String without SID parameters
	 */
	public static function stripSIDs($subject)
	{
		return preg_replace('/[?&amp;]sid=[0-9a-z]{32}/si', '', $subject);
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

	/**
	 * Unifies an user e-mail address.
	 *
	 * @param string $userMail E-mail to check
	 * @param int $ignoreID Optional user ID to ignore during check
	 * @return bool User mail address already exists
	 */
	public static function unifyUserMail($userMail, $ignoreID=-1)
	{
		foreach(self::glob(DATAPATH . 'members/[!0]*.xbb') as $curMember)
		{
			$curMember = self::file($curMember);
			if($curMember[3] == $userMail && $curMember[1] != $ignoreID)
				return true;
		}
		return false;
	}

	/**
	 * Unifies an user name.
	 *
	 * @param string $userName Name to check
	 * @param int $ignoreID Optional user ID to ignore during check
	 * @return bool User name already exists
	 */
	public static function unifyUserName($userName, $ignoreID=-1)
	{
		$userName = Functions::strtolower($userName);
		foreach(self::glob(DATAPATH . 'members/[!0]*.xbb') as $curMember)
		{
			$curMember = self::file($curMember);
			if(Functions::strtolower($curMember[0]) == $userName && $curMember[4] != '5' && $curMember[1] != $ignoreID)
				return true;
		}
		return false;
	}

	/**
	 * Extending PHP's {@link unlink()} with global data path and returns file size of <b>successfully</b> deleted file.
	 *
	 * @param string $filename File to delete
	 * @param bool $datapath Apply the global datapath to filename, there is usually no need to change this
	 * @return int|bool Size of deleted file or false
	 */
	public static function unlink($filename, $datapath=true)
	{
		return ($fileSize = filesize(($datapath ? DATAPATH : '') . $filename)) !== false && unlink(($datapath ? DATAPATH : '') . $filename) ? $fileSize : false;
	}

	/**
	 * Updates topic counter, post counter and last post (incl. timestamp) of stated forum.
	 * Either update just the counters or everything incl. last post data. That means provide 3 or all parameters!
	 *
	 * @param int $forumID Forum ID
	 * @param int $topicOffset Offset to increase or decrease amount of topics
	 * @param int $postOffset Offset to increase or decrease amount of posts
	 * @param int $lastTopicID Optional ID of newest topic in forum
	 * @param int|string $lastPosterID Optional ID of of last user posted in forum
	 * @param string $lastDate Optional proprietary date of last post
	 * @param int $lastTSmileyID Optional topic smiley ID of last post
	 */
	public static function updateForumData($forumID, $topicOffset, $postOffset, $lastTopicID=null, $lastPosterID=null, $lastDate=null, $lastTSmileyID=null)
	{
		Functions::getFileLock('foren');
		//Make sure forums are loaded
		if(!isset(self::$cache['forums']))
			self::getForumData(0);
		foreach(self::$cache['forums'] as &$curForum)
		{
			if($curForum[0] == $forumID)
			{
				$curForum[3] += $topicOffset;
				$curForum[4] += $postOffset;
				if(func_num_args() > 3)
				{
					$curForum[6] = time();
					$curForum[9] = implode(',', array($lastTopicID, $lastPosterID, $lastDate, $lastTSmileyID));
				}
			}
			$curForum[7] = implode(',', $curForum[7]);
			$curForum[10] = implode(',', $curForum[10]);
			$curForum = self::implodeByTab($curForum);
		}
		self::file_put_contents('vars/foren.var', implode("\n", self::$cache['forums']) . "\n");
		unset(self::$cache['forums']);
		Functions::releaseLock('foren');
	}

	/**
	 * Updates the last posts var file by adding a new one.
	 *
	 * @param int $forumID ID of forum of newest post
	 * @param int $topicID ID of newest topic in forum
	 * @param int|string $userID ID of of last user posted in forum
	 * @param string $date Proprietary date of last post
	 * @param int $tSmileyID ID of topic smiley
	 * @param int $postID ID of newest post
	 */
	public static function updateLastPosts($forumID, $topicID, $userID, $date, $tSmileyID, $postID)
	{
		if(($max = Main::getModule('Config')->getCfgVal('show_lposts')) < 1)
			return;
		if(($lastPosts = self::file_get_contents('vars/lposts.var')) == '')
			self::file_put_contents('vars/lposts.var', implode(',', array($forumID, $topicID, $userID, $date, $tSmileyID, $postID)));
		else
		{
			$lastPosts = self::explodeByTab($lastPosts);
			array_unshift($lastPosts, implode(',', array($forumID, $topicID, $userID, $date, $tSmileyID, $postID)));
			while(count($lastPosts) > $max)
				array_pop($lastPosts);
			self::file_put_contents('vars/lposts.var', self::implodeByTab($lastPosts));
		}
	}

	/**
	 * Updates today's posts var file.
	 *
	 * @param int $forumID ID of forum of newest post
	 * @param int $topicID ID of newest topic in forum
	 * @param int|string $userID ID of of last user posted in forum
	 * @param string $date Proprietary date of last post
	 * @param int $tSmileyID ID of topic smiley
	 * @param int $postID ID of newest post
	 */
	public static function updateTodaysPosts($forumID, $topicID, $userID, $date, $tSmileyID, $postID)
	{
		self::file_put_contents('vars/todayposts.var', (($todaysPosts = self::file_get_contents('vars/todayposts.var')) == '' || current(self::explodeByTab($todaysPosts)) != gmdate('Yd') ? gmdate('Yd') . "\t" : $todaysPosts . '|') . implode(',', array($forumID, $topicID, $userID, $date, $tSmileyID, $postID)));
	}

	/**
	 * Increases post counter of stated user. User has to exist!
	 *
	 * @param int $userID ID of user
	 */
	public static function updateUserPostCounter($userID)
	{
		$user = self::file('members/' . $userID . '.xbb') or exit(Main::getModule('Logger')->log('Cannot access user ' . $userID . ' for updating posts!', LOG_FILESYSTEM));
		$user[5]++;
		self::file_put_contents('members/' . $userID . '.xbb', implode("\n", $user));
	}
}
?>