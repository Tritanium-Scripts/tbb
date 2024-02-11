<?php
/**
 * Various static functions and wrappers, optimized for external calls and standalone actions.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class ExtFunctions
{
    /**
     * Various cached (loaded and fully exploded) data.
     *
     * @var array Cached data
     */
    private static array $cache = [];

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
     * Extending PHP's {@link file()} with custom trimming, UTF-8 converting and global data path.
     *
     * @param string $filename Name of file
     * @param int $flags Optional constants
     * @return array Read in file contents as array
     */
    public static function file(string $filename, int $flags=0): array
    {
        return array_map(['ExtFunctions', 'utf8Encode'], array_map(fn($entry) => trim($entry, " \n\r\0\x0B"), file(EXT_PATH_TO_DATA . $filename, $flags)));
    }

    /**
     * Extending PHP's {@link file_exists()} with global data path.
     */
    public static function file_exists(string $filename): bool
    {
        return file_exists(EXT_PATH_TO_DATA . $filename);
    }

    /**
     * Extending PHP's {@link file_get_contents()} with UTF-8 converting and global data path.
     */
    public static function file_get_contents(string $filename): string
    {
        return self::utf8Encode(file_get_contents(EXT_PATH_TO_DATA . $filename, LOCK_SH));
    }

    /**
     * Returns a formatted date string from proprietary date format.
     *
     * @param string $date Proprietary date format (YYYYMMDDhhmmss)
     * @param string $format Date format pattern to use
     * @return string Ready-for-use date
     */
    public static function formatDate(string $date, string $format): string
    {
        $timestamp = self::getTimestamp($date);
        //Encode as UTF-8, because month names lacks proper encoding
        return self::utf8Encode(self::gmstrftime($format, $timestamp));
    }

    /**
     * Returns linked user profile for given user IDs.
     *
     * @param int|string $userID Single or multiple user IDs separated with comma
     * @param bool $isValid Performs an additional check if user(s) exists to prevent linking deleted profiles
     * @return string|array Linked user profile(s) or unlinked state(s)
     */
    public static function getProfileLink($userID, bool $isValid=false)
    {
        $userLinks = [];
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
    public static function getTimestamp(string $date): int
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
    public static function getTopicName(int $forumID, int $topicID): string
    {
        $topic = self::file('foren/' . $forumID . '-' . $topicID . '.xbb');
        return $topic == false ? self::utf8Decode(ExtLastPosts::$deleted_moved) : @next(self::explodeByTab($topic[0]));
    }

    /**
     * Returns the URL address for a topic smiley.
     *
     * @param int|string $tSmileyID ID of topic smiley
     * @return string Topic smiley address
     */
    public static function getTSmileyURL(int $tSmileyID): string
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
    public static function getTSmilies(): array
    {
        self::$cache['tSmileyURLs'] ??= array_map(['ExtFunctions', 'explodeByTab'], self::file('vars/tsmilies.var'));
        return self::$cache['tSmileyURLs'];
    }

    /**
     * Replacement for PHP's deprecated {@link gmstrftime()}.
     *
     * @param string $format Format pattern
     * @param int $timestamp Timestamp to format
     */
    public static function gmstrftime(string $format, ?int $timestamp=null)
    {
        self::$cache['intl'][$format] ??= new IntlDateFormatter(basename(setlocale(LC_CTYPE, '0')), IntlDateFormatter::FULL, IntlDateFormatter::FULL, date_default_timezone_get(), IntlDateFormatter::GREGORIAN, $format);
        return self::$cache['intl'][$format]->format($timestamp);
    }

    /**
     * Tests an user ID for being a guest ID.
     *
     * @param int|string $id User ID to test
     * @return ID is a guest ID
     */
    public static function isGuestID(string $id): bool
    {
        return strncmp($id, '0', 1) == 0;
    }

    /**
     * Wraps PHP's {@link substr()} to Multibyte's {@link mb_substr()}, if UTF-8 is enabled.
     */
    public static function substr(string $string, int $start, int $length=null): string
    {
        return EXT_IS_UTF8 && function_exists('mb_substr')
            ? (isset($length) ? mb_substr($string, $start, $length) : mb_substr($string, $start))
            : (isset($length) ? substr($string, $start, $length) : substr($string, $start));
    }

    /**
     * Uses PHP's {@link utf8_encode()} or some appropriate fallback depending on made settings.
     */
    public static function utf8Encode(string $string): string
    {
        return EXT_IS_UTF8
            ? (function_exists('utf8_encode')
                ? @utf8_encode($string)
                : (function_exists('mb_convert_encoding')
                    ? mb_convert_encoding($string, 'UTF-8', 'ISO-8859-1')
                    : iconv('ISO-8859-1', 'UTF-8', $string)))
            : $string;
    }

    /**
     * Uses PHP's {@link utf8_decode()} or some appropriate fallback depending on made settings.
     */
    public static function utf8Decode(string $string): string
    {
        return EXT_IS_UTF8
            ? $string
            : (function_exists('utf8_decode')
                ? @utf8_decode($string)
                : (function_exists('mb_convert_encoding')
                    ? mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8')
                    : iconv('UTF-8', 'ISO-8859-1', $string)));
    }
}
?>