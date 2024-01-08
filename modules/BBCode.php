<?php
/**
 * BBCode parser.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class BBCode
{
    use Singleton;

    /**
     * Contains ready-for-use admin smilies with synonym and URL.
     *
     * @var array Prepared admin smilies for search and replace
     */
    private array $aSmilies = [];

    /**
     * Contains ready-for-use smilies with synonym and URL.
     *
     * @var array Prepared smilies for search and replace
     */
    private array $smilies = [];

    /**
     * Needed topic data in case of [lock]-BBCode.
     *
     * @var array Reference to topic data without meta infos (first row of XBB file)
     */
    private array $posterIDs;

    /**
     * Prepares and caches (admin) smilies.
     */
    function __construct()
    {
        if(file_exists('cache/BBCode.cache.php'))
            include('cache/BBCode.cache.php');
        else
        {
            $toCache = [];
            foreach(array_map(['Functions', 'explodeByTab'], Functions::file('vars/smilies.var')) as $curSmiley)
            {
                $this->smilies[$curSmiley[1]] = '<img src="' . $curSmiley[2] . '" alt="' . $curSmiley[1] . '" style="border:none;" />';
                $toCache[] = $curSmiley[1] . '\' => \'' . end($this->smilies);
            }
            if(Functions::file_exists('vars/adminsmilies.var'))
            {
                $twoCache = [];
                foreach(array_map(['Functions', 'explodeByTab'], Functions::file('vars/adminsmilies.var')) as $curSmiley)
                {
                    $this->aSmilies[$curSmiley[1]] = '<img src="' . $curSmiley[2] . '" alt="' . $curSmiley[1] . '" style="border:none;" />';
                    $twoCache[] = $curSmiley[1] . '\' => \'' . end($this->aSmilies);
                }
            }
            if(Config::getInstance()->getCfgVal('use_file_caching') == 1)
                Functions::file_put_contents('cache/BBCode.cache.php', '<?php' . (!empty($toCache) ? ' $this->smilies = array(\'' . implode('\', \'', $toCache) . '\');' : '') . (isset($twoCache) ? ' $this->aSmilies = array(\'' . implode('\', \'', $twoCache) . '\');' : '') . ' ?>', LOCK_EX, false, false);
        }
    }

    /**
     * Returns all admin smilies.
     *
     * @return array All current admin smilies with synonym/image as key/value pairs
     */
    public function getAdminSmilies(): array
    {
        return $this->aSmilies;
    }

    /**
     * Returns all smilies.
     *
     * @return array All current smilies with synonym/image as key/value pairs
     */
    public function getSmilies(): array
    {
        return $this->smilies;
    }

    /**
     * Formats a text with HTML, smilies and BBCode.
     *
     * @param string $string The string to parse
     * @param bool $enableHTML Allow HTML tags
     * @param bool $enableSmilies Parse smilies
     * @param bool $enableBBCode Parse BBCode
     * @param array $topic Reference to current topic data, needed for [lock]-BBCode
     * @return string Formatted string
     */
    public function parse(string $string, bool $enableHTML=false, bool $enableSmilies=true, bool $enableBBCode=true, array &$topic=[]): string
    {
        if($enableHTML)
        {
            PlugIns::getInstance()->callHook(PlugIns::HOOK_BBCODE_PARSE_HTML, $string);
            $string = htmlspecialchars_decode($string, ENT_COMPAT);
        }
        if($enableSmilies)
        {
            PlugIns::getInstance()->callHook(PlugIns::HOOK_BBCODE_PARSE_SMILIES, $string);
            $unmaskedBrackets = array_map(fn($entity): string => $entity . ')', array_merge(array('&quot;'), Functions::getLatin9Entities()));
            $maskedBrackets = array_map(fn($entity): string => $entity . '&#41;', array_merge(array('&quot;'), Functions::getLatin9Entities()));
            //Prevent ") -> &quot;) -> &quot<img...
            $string = Functions::str_replace($unmaskedBrackets, $maskedBrackets, $string);
            $string = strtr($string, $this->smilies);
            $string = strtr($string, $this->aSmilies);
            //...and revert it back
            $string = Functions::str_replace($maskedBrackets, $unmaskedBrackets, $string);
        }
        if($enableBBCode)
        {
            //Cache topic IDs (if any)
            if(!isset($this->posterIDs) || (empty($this->posterIDs) && !empty($topic)))
                //Only consider numeric IDs (guest IDs are strings, but start with 0 at least, e.g. "0123" is a guest, too) and filter out duplicate ones
                $this->posterIDs = @array_filter(array_unique(array_map('next', !empty($topic) && is_string($topic[0]) ? array_map(['Functions', 'explodeByTab'], $topic) : $topic)), fn($id) => !Functions::isGuestID($id));
            //Filter out ignored BBCode
            $string = preg_replace_callback("/\[noparse\](.*?)\[\/noparse\]/si", fn($elements): string => Functions::str_replace(['[', ']'], ['&#91;', '&#93;'], $elements[1]), $string);
            //Start parsing
            PlugIns::getInstance()->callHook(PlugIns::HOOK_BBCODE_PARSE_BBCODE, $string);
            $string = preg_replace_callback("/\[list\][<br \/>\r\n]*?\[\*\](.*?)\[\/list\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', ['type' => BBCODE_LIST, 'listEntries' => explode('[*]', $elements[1])]), $string);
            $string = preg_replace_callback("/\[b\](.*?)\[\/b\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_BOLD, 'boldText' => $elements[1])), $string);
            $string = preg_replace_callback("/\[i\](.*?)\[\/i\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_ITALIC, 'italicText' => $elements[1])), $string);
            $string = preg_replace_callback("/\[u\](.*?)\[\/u\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_UNDERLINE, 'underlineText' => $elements[1])), $string);
            $string = preg_replace_callback("/\[s\](.*?)\[\/s\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_STRIKE, 'strikeText' => $elements[1])), $string);
            $string = preg_replace_callback("/\[sup\](.*?)\[\/sup\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_SUPERSCRIPT, 'superText' => $elements[1])), $string);
            $string = preg_replace_callback("/\[sub\](.*?)\[\/sub\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_SUBSCRIPT, 'subText' => $elements[1])), $string);
            $string = preg_replace_callback("/\[hide\](.*?)\[\/hide\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_HIDE, 'hideText' => $elements[1])), $string);
            $string = preg_replace_callback("/\[lock\](.*?)\[\/lock\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', ['type' => BBCODE_LOCK, 'lockText' => in_array(Auth::getInstance()->getUserID(), $this->posterIDs) ? $elements[1] : '']), $string);
            $string = preg_replace_callback("/\[center\](.*?)\[\/center\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_CENTER, 'centerText' => $elements[1])), $string);
            $string = preg_replace_callback("/\[code\](.*?)\[\/code\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_CODE, 'codeLines' => $elements[1])), $string);
            $string = preg_replace_callback("/\[php\](.*?)\[\/php\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_CODE, 'codeLines' => Functions::str_replace(array('<code>', '</code>'), '', highlight_string(Functions::br2nl(htmlspecialchars_decode($elements[1])), true)))), $string);
            $string = preg_replace_callback("/\[email\](.*?)\[\/email\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_EMAIL, 'eMailAddress' => $elements[1], 'eMailText' => $elements[1])), $string);
            $string = preg_replace_callback("/\[email=(.*?)\](.*?)\[\/email\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_EMAIL, 'eMailAddress' => $elements[1], 'eMailText' => $elements[2])), $string);
            $string = preg_replace_callback("/\[img\](.*?)\[\/img\]/si", fn($elements): string => Functions::substr($elements[1], 0, 11) == 'javascript:' ? $elements[0] : Template::getInstance()->fetch('BBCode', array('type' => BBCODE_IMAGE, 'imageAddress' => $elements[1], 'imageText' => '')), $string);
            #$string = preg_replace_callback("/\[img=(\d+),(\d+)\](.*?)\[\/img\]/si", fn($elements): string => Functions::substr($elements[3], 0, 11) == 'javascript:' ? $elements[0] : Template::getInstance()->fetch('BBCode', array('type' => BBCODE_IMAGE, 'imageAddress' => $elements[3], 'imageText' => '', 'imageHeight' => $elements[2], 'imageWidth' => $elements[1])), $string);
            $string = preg_replace_callback("/\[img=(.*?)\](.*?)\[\/img\]/si", fn($elements): string => Functions::substr($elements[1], 0, 11) == 'javascript:' ? $elements[0] : Template::getInstance()->fetch('BBCode', array('type' => BBCODE_IMAGE, 'imageAddress' => $elements[1], 'imageText' => $elements[2])), $string);
            $string = preg_replace_callback("/\[url\](.*?)\[\/url\]/si", fn($elements): string => Functions::substr($elements[1], 0, 11) == 'javascript:' ? $elements[0] : Template::getInstance()->fetch('BBCode', array('type' => BBCODE_LINK, 'linkAddress' => Functions::addHTTP($elements[1]), 'linkText' => $elements[1])), $string);
            $string = preg_replace_callback("/\[url=(.*?)\](.*?)\[\/url\]/si", fn($elements): string => Functions::substr($elements[1], 0, 11) == 'javascript:' ? $elements[0] : Template::getInstance()->fetch('BBCode', array('type' => BBCODE_LINK, 'linkAddress' => Functions::addHTTP($elements[1]), 'linkText' => $elements[2])), $string);
            $string = preg_replace_callback("/\[color=(\#[a-fA-F0-9]{6}|[a-zA-Z]+)\](.*?)\[\/color\]/si", fn($elements) => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_COLOR, 'colorCode' => $elements[1], 'colorText' => $elements[2])), $string);
            $string = preg_replace_callback("/\[iframe\](.*?)\[\/iframe\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_IFRAME, 'iFrameLink' => $elements[1], 'iFrameWidth' => 560, 'iFrameHeight' => 315)), $string);
            $string = preg_replace_callback("/\[iframe=(\d+),(\d+)\](.*?)\[\/iframe\]/si", fn($elements): string => Functions::substr($elements[3], 0, 11) == 'javascript:' ? $elements[3] : Template::getInstance()->fetch('BBCode', array('type' => BBCODE_IFRAME, 'iFrameLink' => $elements[3], 'iFrameWidth' => $elements[1], 'iFrameHeight' => $elements[2])), $string);
            $string = preg_replace("/\[marquee\](.*?)\[\/marquee\]/si", '<marquee>\1</marquee>', $string);
            //TBB 1.2.3 BBCode hack support
            $string = preg_replace_callback("/\[size=(\-[12]{1}|\+[1-4]{1})\](.*?)\[\/size\]/si", function($elements){switch($elements[1]) {case '+4': $elements[1] = '300%'; break; case '+3': $elements[1] = 'xx-large'; break; case '+2': $elements[1] = 'x-large'; break; case '+1': $elements[1] = 'large'; break; case '-1': $elements[1] = 'x-small'; break; case '-2': $elements[1] = 'xx-small'; break;} return Template::getInstance()->fetch('BBCode', array('type' => BBCODE_SIZE, 'sizeFont' => $elements[1], 'sizeText' => $elements[2]));}, $string);
            $string = preg_replace_callback("/\[glow=(\#[a-fA-F0-9]{6}|[a-zA-Z]+)\](.*?)\[\/glow\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_GLOW, 'glowColor' => $elements[1], 'glowText' => $elements[2])), $string);
            $string = preg_replace_callback("/\[shadow=(\#[a-fA-F0-9]{6}|[a-zA-Z]+)\](.*?)\[\/shadow\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_SHADOW, 'shadowColor' => $elements[1], 'shadowText' => $elements[2])), $string);
            $string = preg_replace_callback("/\[flash\](.*?)\[\/flash\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_FLASH, 'flashLink' => $elements[1], 'flashWidth' => 425, 'flashHeight' => 355)), $string);
            $string = preg_replace_callback("/\[flash[=| ](\d+),(\d+)\](.*?)\[\/flash\]/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_FLASH, 'flashLink' => $elements[3], 'flashWidth' => $elements[1], 'flashHeight' => $elements[2])), $string);
            //Quotes at the end for linked sources
            while(preg_match("/\[quote\](.*?)\[\/quote\]/si", $string))
                $string = preg_replace_callback("/\[quote\](.*?)\[\/quote\][\r\n]*/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_QUOTE, 'quoteText' => $elements[1], 'quoteTitle' => Language::getInstance()->getString('quote_colon', 'BBCode'))), $string);
            while(preg_match("/\[quote=(.*?)\](.*?)\[\/quote\]/si", $string))
                $string = preg_replace_callback("/\[quote=(.*?)\](.*?)\[\/quote\][\r\n]*/si", fn($elements): string => Template::getInstance()->fetch('BBCode', array('type' => BBCODE_QUOTE, 'quoteText' => $elements[2], 'quoteTitle' => sprintf(Language::getInstance()->getString('quote_by_x_colon', 'BBCode'), $elements[1]))), $string);
        }
        return $string;
    }
}
?>