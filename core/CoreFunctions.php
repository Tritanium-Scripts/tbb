<?php
/**
 * Various static functions and wrappers.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
abstract class CoreFunctions
{
    /**
     * Various cached (loaded and fully exploded) data.
     *
     * @var array Cached data
     */
    protected static array $cache = [];

    /**
     * All read in contents from files are cached here.
     * 0: Data as arrays
     * 1: Single strings
     *
     * @var array Cached file contents subdivided in exploded (file()) and single lines (f_g_c())
     */
    private static array $fileCache = [];

    /**
     * Counter for file accesses.
     *
     * @var int Amount of file reading and writing
     */
    private static int $fileCounter = 0;

    /**
     * Controls file caching.
     *
     * @var bool Use file caching
     */
    private static bool $isCaching = true;

    /**
     * Some ISO-8859-15 characters not part of ISO-8859-1 to search for.
     *
     * @var array ISO-8859-15 characters to handle for conversion
     */
    private static array $latin9Chars = ['€', 'Š', 'š', 'Ž', 'ž', 'Œ', 'œ', 'Ÿ'];

    /**
    * Counterparts of ISO-8859-15 as (X)HTML entities for replacement.
    *
    * @var array (X)HTML entities used for replacement in conversion
    */
    private static array $latin9Entities = ['&euro;', '&Scaron;', '&scaron;', '&#142;', '&#158;', '&OElig;', '&oelig;', '&Yuml;'];

    /**
     * Hidden constructor to prevent instances of this class.
     */
    private function __construct()
    {
    }

    /**
     * Default operations while accessing the admin panel.
     */
    public static function accessAdminPanel(): void
    {
        NavBar::getInstance()->addElement(Language::getInstance()->getString('administration'), INDEXFILE . '?faction=adminpanel' . SID_AMPER);
        if(!Auth::getInstance()->isAdmin())
        {
            Logger::getInstance()->log('%s tried to access administration', Logger::LOG_ACP_ACCESS);
            Template::getInstance()->printMessage('permission_denied');
        }
        //Log first entering of any admin panel site
        if(@Functions::stripos($_SERVER['HTTP_REFERER'], 'faction=ad') === false)
            Logger::getInstance()->log('%s entered administration', Logger::LOG_ACP_ACTION);
        Config::getInstance()->setCfgVal('twidth', '100%');
        Language::getInstance()->parseFile('AdminIndex'); //This is the 'AdminMain.ini'
    }

    /**
     * Adds 'http://' to a link, if needed. Ignores relative links to internal upload folder.
     *
     * @param string $link Link to extend with 'http://'
     * @return string Extended link
     */
    public static function addHTTP(string $link): string
    {
        return !empty($link) && Functions::substr($link, 0, 8) != 'uploads/' && Functions::stripos($link, '://') === false ? 'http://' . $link : $link;
    }

    /**
     * Adds [url]-BBCode to links found in a string, which are not encapsulated by certain BBCodes.
     *
     * @param string $subject String to search for links and formatting them
     * @return string Result string
     */
    public static function addURL(string $subject): string
    {
        $tempBBCode = time(); //This is the placeholder for "url"
        $subject = preg_replace_callback("/([^ ^>^\]^=^\n^\r]+?:\/\/|www\.)[^ ^<^\.^\[]+(\.[^ ^<^\.^\[^\]^\n^\r]+)+/si",
            fn($arr): string => !empty($arr[2]) && Functions::stripos($arr[0], '[url]') === false && Functions::strripos($arr[0], '[/url]') === false
                ? '[' . $tempBBCode . ']' . ($arr[1] == 'www.' ? 'http://' : '') . $arr[0] . '[/' . $tempBBCode . ']'
                : $arr[0],
            $subject);
        //After adding [url]s to *any* link, strip off unwanted ones:
        foreach(['iframe', 'flash', 'url', 'img', 'email', 'code', 'php', 'noparse'] as $curBBCode)
        {
            //Remove the simple ones, e.g. [flash][url]xxx[/url][/flash]
            $subject = Functions::str_ireplace(['[' . $curBBCode . '][' . $tempBBCode . ']', '[/' . $tempBBCode . '][/' . $curBBCode . ']'], ['[' . $curBBCode . ']', '[/' . $curBBCode . ']'], $subject);
            //Remove the advanced ones having any attributes (only start tags are affected), e.g. [flash=xxx,xxx][url]xxx[/flash]
            $subject = preg_replace("/(\[" . $curBBCode . "=.*?\])\[" . $tempBBCode . "\]/si", '\1', $subject);
            //Remove attributed ones in start tags, e.g. [img=[url]xxx[/url]]
            $subject = preg_replace("/(\[" . $curBBCode . "=)\[" . $tempBBCode . "\](.*?)\[\/" . $tempBBCode . "\]\]/si", '\1\2]', $subject);
        }
        //Finally add proper [url]s
        $subject = Functions::str_replace(['[' . $tempBBCode . ']', '[/' . $tempBBCode . ']'], ['[url]', '[/url]'], $subject);
        return $subject;
    }

    /**
     * Reverts PHP's {@link nl2br()} incl. XHTML versions.
     *
     * @param string $string String for search and replace of br-tags
     * @return string Processed string
     */
    public static function br2nl(string $string): string
    {
        return Functions::str_replace(['<br>', '<br/>', '<br />'], "\n", $string);
    }

    /**
     * Censors a string.
     *
     * @param string $string Text to censor
     * @return string Censored text
     */
    public static function censor(string $string): string
    {
        if(Config::getInstance()->getCfgVal('censored') != 1)
            return $string;
        if(!isset(self::$cache['censoredWords']))
            self::$cache['censoredWords'] = array_map(['self', 'explodeByTab'], self::file('vars/cwords.var'));
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
    public static function checkIPAccess(int $forumID=-1, ?string $ipAddress=null)
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
    public static function checkModOfForum($forum): bool
    {
        //Super mods can access all
        if(Auth::getInstance()->isSuperMod())
            return true;
        //Provide proper forum data
        if(is_numeric($forum))
            $forum = self::getForumData($forum);
        //Check moderator permissions
        return !empty($forum[11]) && in_array(Auth::getInstance()->getUserID(), self::explodeByComma($forum[11]));
    }

    /**
     * Checks if member/guest has certain access permissions for a forum.
     *
     * @param array|int $forum Forum data or forum ID
     * @param int $what Access level
     * @param int $whatGuest Access level for guest
     * @return bool Access granted
     */
    public static function checkUserAccess($forum, int $what, int $whatGuest=6): bool
    {
        //Provide proper forum data and permissions
        if(is_numeric($forum))
            $forum = self::getForumData($forum);
        $perms = is_array($forum[10]) ? $forum[10] : self::explodeByComma($forum[10]);
        //Check guests
        if(!Auth::getInstance()->isLoggedIn())
            return $perms[$whatGuest] == '1';
        //Allow access for admins or mods of that forum
        if(Auth::getInstance()->isAdmin() || self::checkModOfForum($forum))
            return true;
        //Get default permission...
        $canAccess = $perms[$what] == '1';
        //...and check with special ones
        if(self::file_exists('foren/' . $forum[0] . '-rights.xbb'))
            foreach(self::file('foren/' . $forum[0] . '-rights.xbb') as $curSpecialPerm)
            {
                $curSpecialPerm = self::explodeByTab($curSpecialPerm);
                if($curSpecialPerm[1] == '1' && $curSpecialPerm[2] == Auth::getInstance()->getUserID() || ($curSpecialPerm[1] == '2' && Auth::getInstance()->getGroupID() == $curSpecialPerm[2]))
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
    public static function explodeByComma(string $string): array
    {
        return explode(',', $string);
    }

    /**
     * Explodes a string by tabulator.
     *
     * @param string $string String to explode
     * @return array Resulting array
     */
    public static function explodeByTab(string $string): array
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
     * @return array|bool Read in file contents as array or false if not found
     */
    public static function file(string $filename, ?int $flags=0, ?string $trimCharList=null, bool $datapath=true)
    {
        $trimCallback = fn($entry): string => trim($entry, empty($trimCharList) ? " \n\r\0\x0B" : $trimCharList);
        if($datapath && self::$isCaching)
        {
            if(isset(self::$fileCache[$filename][0]))
                return array_map(['Functions', 'utf8Encode'], array_map($trimCallback, self::$fileCache[$filename][0]));
            self::$fileCounter++;
            return file_exists(DATAPATH . $filename) ? array_map(['Functions', 'utf8Encode'], array_map($trimCallback, self::$fileCache[$filename][0] = file(DATAPATH . $filename, $flags))) : false;
        }
        self::$fileCounter++;
        $filePath = ($datapath ? DATAPATH : '') . $filename;
        return file_exists($filePath) ? array_map(['Functions', 'utf8Encode'], array_map($trimCallback, file($filePath, $flags))) : false;
    }

    /**
     * Extending PHP's {@link file_exists()} with global data path.
     */
    public static function file_exists(string $filename): bool
    {
        return file_exists(DATAPATH . $filename);
    }

    /**
     * Extending PHP's {@link file_get_contents()} with caching, file counting, UTF-8 converting and global data path.
     */
    public static function file_get_contents(string $filename): string
    {
        //Use file caching
        if(self::$isCaching)
        {
            if(isset(self::$fileCache[$filename][1]))
                return self::utf8Encode(self::$fileCache[$filename][1]);
            self::$fileCounter++;
            return Functions::utf8Encode(self::$fileCache[$filename][1] = file_get_contents(DATAPATH . $filename, LOCK_SH));
        }
        //Use no caching
        self::$fileCounter++;
        return Functions::utf8Encode(file_get_contents(DATAPATH . $filename, LOCK_SH));
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
    public static function file_put_contents(string $filename, $data, int $flags=LOCK_EX, bool $decUTF8=true, bool $datapath=true)
    {
        if(self::$isCaching)
            unset(self::$fileCache[$filename]);
        self::$fileCounter++;
        return file_put_contents(($datapath ? DATAPATH : '') . $filename, $decUTF8 ? Functions::utf8Decode(self::latin9ToEntities($data)) : $data, $flags);
    }

    /**
     * Returns a formatted date string from proprietary date format.
     *
     * @param string $date Proprietary GMT date format (YYYYMMDDhhmmss)
     * @param string $format Alternative pattern to use
     * @return string Ready-for-use date
     */
    public static function formatDate(string $date, ?string $format=null): string
    {
        $timestamp = self::getTimestamp($date);
        $formattedDate = sprintf(
            time()-$timestamp < Config::getInstance()->getCfgVal('emph_date_hours')*3600 ? '<b>%s</b>' : '%s',
            gmstrftime(isset($format)
                ? $format
                : Language::getInstance()->getString(Config::getInstance()->getCfgVal('date_as_text') == 1 && self::getProperYz(time()-86400) <= ($yz = self::getProperYz($timestamp))
                    ? (self::getProperYz(time()) == $yz ? 'TODAY_DATEFORMAT' : 'YESTERDAY_DATEFORMAT')
                    : 'DATEFORMAT'), $timestamp));
        //Encode as UTF-8 in case of month names lack proper encoding
        return Core::getInstance()->isUtf8Locale() ? $formattedDate : Functions::utf8Encode($formattedDate);
    }

    /**
     * Returns current blocked IP addresses.
     *
     * @return array Fully exploded banned IP addresses
     */
    public static function getBannedIPs(): array
    {
        if(!isset(self::$cache['bannedIPs']))
        {
            self::$cache['bannedIPs'] = array_map(['self', 'explodeByTab'], self::file('vars/ip.var'));
            if(!isset(self::$cache['bannedIPs'][0][1]))
                self::$cache['bannedIPs'] = [];
        }
        return self::$cache['bannedIPs'];
    }

    /**
     * Returns amount of file accesses.
     *
     * @return int File counter
     */
    public static function getFileCounter(): int
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
    public static function getFileLock(string $name): bool
    {
        self::$cache['locks'][$name] = fopen(DATAPATH . 'vars/' . $name . '.lock', 'w');
        ($locked = flock(self::$cache['locks'][$name], LOCK_EX)) or Logger::getInstance()->log('Error getting ' . $name . ' file lock!', Logger::LOG_FILESYSTEM);
        return $locked;
    }

    /**
     * Returns data of a forum.
     *
     * @param int $forumID ID of forum
     * @return array|bool Forum data or false if forum was not found
     */
    public static function getForumData(int $forumID)
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
    public static function getGroupData(int $groupID)
    {
        if(!isset(self::$cache['groups']))
        {
            self::$cache['groups'] = array_map(['self', 'explodeByTab'], self::file('vars/groups.var'));
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
     * Returns SHA-2 (SHA-512) hash value for stated string.
     *
     * @param string $string String to hash with SHA-2
     * @return string Hash value of string
     */
    public static function getHash(string $string): string
    {
        return hash('sha512', $string);
    }

    /**
     * Returns a translation table for the common HTML entities and their unicode hexadecimal representation for JavaScript environments.
     * Use this decoder to max out user comfort and valid W3C conform code. Cranks up leet level quite high!
     *
     * @return array Translation table between HTML entities and their JavaScript counterparts
     */
    public static function getHTMLJSTransTable(): array
    {
        if(!isset(self::$cache['htmlJSDecoder']))
        {
            $temp = get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES);
            $temp = array_flip($temp) + ['&#' . (in_array('&#39;', $temp) ? '0' : '') . '39;' => "'", '&apos;' => "'"];
            self::$cache['htmlJSDecoder'] = array_combine(array_keys($temp), array_map(fn($string) => '\u00' . bin2hex($string), array_values($temp)));
        }
        return self::$cache['htmlJSDecoder'];
    }

    /**
     * Returns remote IP address based on the directive to save them or not.
     *
     * @return string IP address to save with postings
     */
    public static function getIPAddress(): string
    {
        return ($saveIPAddress = Config::getInstance()->getCfgVal('save_ip_address')) > 0 ? ($saveIPAddress == 2 && Auth::getInstance()->isLoggedIn() ? '' : $_SERVER['REMOTE_ADDR']) : '';
    }

    /**
     * Returns additional supported ISO-8859-15 characters.
     *
     * @return array (X)HTML entities of additional supported ISO-8859-15 characters
     */
    public static function getLatin9Entities(): array
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
    public static function getLockObject(string $filename): LockObject
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
    public static function getMsgBackLinks(?int $forumID=null, ?int $topicID=null, string $msgIndex='back_to_topic', ?int $postID=null, $postOnPage='last'): string
    {
        return '<br />' . (isset($forumID) ? (isset($topicID) ? sprintf(Language::getInstance()->getString($msgIndex, 'Messages'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $forumID . '&amp;thread=' . $topicID . (isset($postID) ? '&amp;z=' . $postOnPage . SID_AMPER . '#post' . $postID : SID_AMPER)) . '<br />'  : '') . sprintf(Language::getInstance()->getString('back_to_topic_index', 'Messages'), INDEXFILE . '?mode=viewforum&amp;forum_id=' . $forumID . SID_AMPER) . '<br />' : '') . sprintf(Language::getInstance()->getString('back_to_forum_index', 'Messages'), INDEXFILE . SID_QMARK);
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
    public static function getProfileLink($userID, bool $isValid=false, ?string $aAttributes=null, bool $colorRank=false)
    {
        $userLinks = [];
        if(!empty($userID))
            foreach(self::explodeByComma($userID) as $curUserID)
                //Guest check
                if(self::isGuestID($userID))
                    $userLinks[] = Functions::substr($userID, 1);
                //(Optional) deleted check
                elseif($isValid && !self::file_exists('members/' . $curUserID . '.xbb'))
                    $userLinks[] = Language::getInstance()->getString('deleted');
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
                            $curColor = Config::getInstance()->getCfgVal('wio_color_admin');
                            break;

                            case '2':
                            $curColor = Config::getInstance()->getCfgVal('wio_color_mod');
                            break;

                            case '3':
                            $curColor = Config::getInstance()->getCfgVal('wio_color_user');
                            break;

                            case '4':
                            $curColor = Config::getInstance()->getCfgVal('wio_color_banned');
                            break;

                            case '6':
                            $curColor = Config::getInstance()->getCfgVal('wio_color_smod');
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
     public static function getProperYz(int $timestamp): string
     {
        return gmdate('Y', $timestamp) . sprintf('%03d', gmdate('z', $timestamp));
     }

    /**
     * Generates a 10-character random password incl. special chars.
     *
     * @return string Random password
     */
    public static function getRandomPass(): string
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
    public static function getRankImage(string $userState, int $userPosts): string
    {
        if($userPosts < 0)
            return '';
        switch($userState)
        {
            case '1':
            $rankImage = array_fill(0, Config::getInstance()->getCfgVal('stars_admin'), 'rstar');
            break;

            case '2':
            $rankImage = array_fill(0, Config::getInstance()->getCfgVal('stars_mod'), 'gstar');
            break;

            case '3':
            case '4':
            foreach(self::getRanks() as $curRank)
                if($userPosts >= $curRank[2] && $userPosts <= $curRank[3])
                    $rankImage = array_fill(0, $curRank[4], 'ystar');
            break;

            case '6':
            $rankImage = array_fill(0, Config::getInstance()->getCfgVal('stars_smod'), 'bstar');
            break;
        }
        return isset($rankImage) ? '<img src="images/ranks/' . implode('.gif" alt="*" /><img src="images/ranks/', $rankImage) . '.gif" alt="*" />' : '';
    }

    /**
     * Returns fully exploded user ranks.
     *
     * @return array Exploded user ranks
     */
    public static function getRanks(): array
    {
        if(!isset(self::$cache['ranks']))
            self::$cache['ranks'] = array_map(['self', 'explodeByTab'], self::file('vars/rank.var'));
        return self::$cache['ranks'];
    }

    /**
     * Returns display name from an user state.
     *
     * @param int $userState State of user
     * @param int $userPosts Posts of user
     * @return string Display name for user state
     */
    public static function getStateName(string $userState, int $userPosts): string
    {
        switch($userState)
        {
            case '1':
            return Config::getInstance()->getCfgVal('var_admin');
            break;

            case '2':
            return Config::getInstance()->getCfgVal('var_mod');
            break;

            case '3':
            foreach(self::getRanks() as $curRank)
                if($userPosts >= $curRank[2] && $userPosts <= $curRank[3])
                    return $curRank[1];
            break;

            case '4':
            return Config::getInstance()->getCfgVal('var_banned');
            break;

            case '5':
            return Config::getInstance()->getCfgVal('var_killed');
            break;

            case '6':
            return Config::getInstance()->getCfgVal('var_smod');
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
    public static function getTimestamp(string $date): int
    {
        $gmtOffset = Config::getInstance()->getCfgVal('gmt_offset');
        $offset = Functions::substr($gmtOffset, 1, 2)*3600 + Functions::substr($gmtOffset, 3, 2)*60;
        //GMT timestamp + timezone offset + DST offset
        return gmmktime((int) substr($date, 8, 2), (int) substr($date, 10, 2), (int) substr($date, 12, 2), (int) substr($date, 4, 2), (int) substr($date, 6, 2), (int) substr($date, 0, 4)) + ($gmtOffset[0] == '-' ? $offset*-1 : $offset) + date('I')*3600;
    }

    /**
     * Returns the name of a topic.
     *
     * @param int|string $forumID ID of forum
     * @param int|string $topicID ID of topic
     * @return string Name of topic
     */
    public static function getTopicName($forumID, $topicID): string
    {
        return ($topic = self::file('foren/' . $forumID . '-' . $topicID . '.xbb')) == false ? Language::getInstance()->getString('deleted_moved') : @next(self::explodeByTab($topic[0]));
    }

    /**
     * Returns the URL address for a topic smiley.
     *
     * @param int|string $tSmileyID ID of topic smiley
     * @return string Topic smiley address
     */
    public static function getTSmileyURL($tSmileyID): string
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
    public static function getTSmilies(): array
    {
        if(!isset(self::$cache['tSmileyURLs']))
            self::$cache['tSmileyURLs'] = array_map(['self', 'explodeByTab'], self::file('vars/tsmilies.var'));
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
        $user[19] = isset($user[19]) && !empty($user[19]) ? self::explodeByTab($user[19]) : [];
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
     * @return string|array Value from one of the superglobals or empty string if it was not found
     */
    public static function getValueFromGlobals(string $key, bool $stripNewLine=true)
    {
        return Functions::str_replace($stripNewLine ? ["\t", "\n", "\r"] : "\t", '', isset($_GET[$key]) ? $_GET[$key] : (isset($_POST[$key]) ? $_POST[$key] : ''));
    }

    /**
     * Extending PHP's {@link glob()} to handle invalid return values.
     *
     * @param string $pattern The pattern to use
     * @param int $flags Optional flags to use
     * @return array Matched files/directories or empty array
     */
    public static function glob(string $pattern, int $flags=0): array
    {
        return glob($pattern, $flags) ?: [];
    }

    /**
     * Handles and returns template filename requested from given mode translation table and logs unknown modes.
     * By having encountered a certain number of unknown modes, the IP address will be banned.
     *
     * @param string $mode Requested template file for this mode
     * @param array $modeTable Mode and template counterparts
     * @param string $module Name of executing module
     * @param string $defaultMode Another default mode to use in case of unknown mode
     * @return string Name of template file from stated table
     */
    public static function handleMode(string &$mode, array &$modeTable, string $module, string $defaultMode=''): string
    {
        if(!array_key_exists($mode, $modeTable))
        {
            //Escaping of '%' to protect logger
            Logger::getInstance()->log('Unknown mode "' . Functions::str_replace('%', '%%', $mode) . '" in ' . $module . '; using default', Logger::LOG_FILESYSTEM);
            isset($_SESSION['unknownModes']) ? $_SESSION['unknownModes']++ : $_SESSION['unknownModes'] = 0;
            if($_SESSION['unknownModes'] > mt_rand(5, 10))
            {
                list(,,,$lastIPID) = @end(self::getBannedIPs());
                self::file_put_contents('vars/ip.var', $_SERVER['REMOTE_ADDR'] . "\t-1\t-1\t" . ($lastIPID+1) . "\t\n", FILE_APPEND);
                Logger::getInstance()->log('Auto-banned %s after catching ' . $_SESSION['unknownModes'] . ' unknown modes of a possible hacking attempt!', Logger::LOG_ACP_ACTION);
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
    public static function implodeByTab(array $pieces): string
    {
        return implode("\t", $pieces);
    }

    /**
     * Tests an user ID for being a guest ID.
     *
     * @param int|string $id User ID to test
     * @return bool ID is a guest ID
     */
    public static function isGuestID(string $id): bool
    {
        return strncmp($id, '0', 1) == 0;
    }

    /**
     * Verifies an email address.
     *
     * @param string $mailAddress The email address to check
     * @return bool Valid email address
     */
    public static function isValidMail(string $mailAddress): bool
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
    public static function latin9ToEntities(string $data): string
    {
        return Functions::str_replace(self::$latin9Chars, self::$latin9Entities, $data);
    }

    /**
     * Loads data from the given URL depending on the given or supported methods.
     *
     * @param string $url URL to load its content
     * @param bool $useFGC Use {@link file_get_contents()} for loading content (null = detect automatically)
     * @param bool $useCURL Use cURL for loading content (null = detect automatically)
     * @return string|bool Loaded content or false
     */
    public static function loadURL(string $url, ?bool $useFGC=null, ?bool $useCURL=null)
    {
        if(is_null($useFGC))
            $useFGC = ini_get('allow_url_fopen') == '1';
        if(is_null($useCURL))
            $useCURL = $useFGC ? false : extension_loaded('curl');
        if($useFGC)
            return @file_get_contents($url);
        elseif($useCURL)
        {
            $cURL = curl_init($url);
            curl_setopt($cURL, CURLOPT_HEADER, false);
            curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
            $checkRedir = !@curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($cURL, CURLOPT_TIMEOUT, ini_get('default_socket_timeout'));
            curl_setopt($cURL, CURLOPT_ENCODING, ''); //Support for gzip
            curl_setopt($cURL, CURLOPT_USERAGENT, 'TBB/' . VERSION_PUBLIC); //RFC 2616
            $content = curl_exec($cURL);
            if($checkRedir)
            {
                $cURLInfo = curl_getinfo($cURL);
                //Perform a manual location following if not supported by cURL
                if($cURLInfo['http_code'] == 302 && isset($cURLInfo['redirect_url']))
                    $content = self::loadURL($cURLInfo['redirect_url'], false, true);
            }
            curl_close($cURL);
            return $content;
        }
        else
            return false;
    }

    /**
     * Extending PHP's {@link nl2br()} with stripping of all newline chars.
     *
     * @param string $string String for search and replace of newlines
     * @return string Processed string
     */
    public static function nl2br(string $string): string
    {
        return str_replace(["\n", "\r"], '', nl2br($string));
    }

    /**
     * Releases prior granted exclusive file usage.
     *
     * @param string $name Name of file lock instance to release
     * @see getFileLock()
     */
    public static function releaseLock(string $name): void
    {
        flock(self::$cache['locks'][$name], LOCK_UN) or Logger::getInstance()->log('Error releasing ' . $name . ' file lock!', Logger::LOG_FILESYSTEM);
        fclose(self::$cache['locks'][$name]);
    }

    /**
     * Sets using file caching feature.
     *
     * @param bool $caching Use file caching
     */
    public static function setFileCaching(bool $caching=true): void
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
    public static function shorten(string $string, int $maxLength): string
    {
        if(Functions::strlen($string) > $maxLength)
            $string = Functions::substr($string, 0, $maxLength-3) . Language::getInstance()->getString('dots');
        return $string;
    }

    /**
     * Sends redirect header for stated URL to skip confirmation messages, if enabled.
     *
     * @param string $url Redirect URL
     */
    public static function skipConfirmMessage(string $url): void
    {
        if(Config::getInstance()->getCfgVal('skip_confirm_msg') == 1)
            header('Location: ' . $url);
    }

    /**
     * Strips SID parameter URLs from a string.
     *
     * @param string $subject String to strip SIDs off
     * @return string String without SID parameters
     */
    public static function stripSIDs(string $subject): string
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
        return is_array($value) ? array_map(['self', 'stripSlashesDeep'], $value) : stripslashes($value);
    }

    /**
     * Unifies an user email address.
     *
     * @param string $userMail Email to check
     * @param int $ignoreID Optional user ID to ignore during check
     * @return bool User mail address already exists
     */
    public static function unifyUserMail(string $userMail, int $ignoreID=-1): bool
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
    public static function unifyUserName(string $userName, int $ignoreID=-1): bool
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
    public static function unlink(string $filename, bool $datapath=true)
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
    public static function updateForumData(int $forumID, int $topicOffset, int $postOffset, ?int $lastTopicID=null, $lastPosterID=null, ?string $lastDate=null, ?int $lastTSmileyID=null): void
    {
        self::getFileLock('foren');
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
                    $curForum[9] = implode(',', [$lastTopicID, $lastPosterID, $lastDate, $lastTSmileyID]);
                }
            }
            $curForum[7] = implode(',', $curForum[7]);
            $curForum[10] = implode(',', $curForum[10]);
            $curForum = self::implodeByTab($curForum);
        }
        self::file_put_contents('vars/foren.var', implode("\n", self::$cache['forums']) . "\n");
        unset(self::$cache['forums']);
        self::releaseLock('foren');
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
    public static function updateLastPosts(int $forumID, int $topicID, $userID, string $date, int $tSmileyID, int $postID): void
    {
        if(($max = Config::getInstance()->getCfgVal('show_lposts')) < 1)
            return;
        if(($lastPosts = self::file_get_contents('vars/lposts.var')) == '')
            self::file_put_contents('vars/lposts.var', implode(',', [$forumID, $topicID, $userID, $date, $tSmileyID, $postID]));
        else
        {
            $lastPosts = self::explodeByTab($lastPosts);
            array_unshift($lastPosts, implode(',', [$forumID, $topicID, $userID, $date, $tSmileyID, $postID]));
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
    public static function updateTodaysPosts(int $forumID, int $topicID, $userID, string $date, int $tSmileyID, int $postID): void
    {
        self::file_put_contents('vars/todayposts.var', (($todaysPosts = self::file_get_contents('vars/todayposts.var')) == '' || current(self::explodeByTab($todaysPosts)) != gmdate('Yd') ? gmdate('Yd') . "\t" : $todaysPosts . '|') . implode(',', [$forumID, $topicID, $userID, $date, $tSmileyID, $postID]));
    }

    /**
     * Increases post counter of stated user. User has to exist!
     *
     * @param int $userID ID of user
     */
    public static function updateUserPostCounter(int $userID): void
    {
        $user = self::file('members/' . $userID . '.xbb') or exit(Logger::getInstance()->log('Cannot access user ' . $userID . ' for updating posts!', Logger::LOG_FILESYSTEM));
        $user[5]++;
        self::file_put_contents('members/' . $userID . '.xbb', implode("\n", $user));
    }
}
?>