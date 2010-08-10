<?php
/**
 * BBCode parser.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class BBCode
{
	/**
	 * Contains ready-for-use admin smilies with synonym and URL.
	 *
	 * @var array Prepared admin smilies for search and replace
	 */
	private $aSmilies = array();

	/**
	 * Contains ready-for-use smilies with synonym and URL.
	 *
	 * @var array Prepared smilies for search and replace
	 */
	private $smilies = array();

	/**
	 * Needed topic data in case of [lock]-BBCode.
	 *
	 * @var array Reference to topic data without meta infos (first row of XBB file)
	 */
	private $posterIDs;

	/**
	 * Prepares and caches (admin) smilies.
	 *
	 * @return BBCode New instance of this class
	 */
	function __construct()
	{
		if(file_exists('cache/BBCode.cache.php'))
			include('cache/BBCode.cache.php');
		else
		{
			$toCache = array();
			foreach(array_map(array('Functions', 'explodeByTab'), Functions::file('vars/smilies.var')) as $curSmiley)
			{
				$this->smilies[$curSmiley[1]] = '<img src="' . $curSmiley[2] . '" alt="' . $curSmiley[1] . '" style="border:none;" />';
				$toCache[] = $curSmiley[1] . '\' => \'' . end($this->smilies);
			}
			if(Functions::file_exists('vars/adminsmilies.var'))
			{
				$twoCache = array();
				foreach(array_map(array('Functions', 'explodeByTab'), Functions::file('vars/adminsmilies.var')) as $curSmiley)
				{
					$this->aSmilies[$curSmiley[1]] = '<img src="' . $curSmiley[2] . '" alt="' . $curSmiley[1] . '" style="border:none;" />';
					$twoCache[] = $curSmiley[1] . '\' => \'' . end($this->aSmilies);
				}
			}
			if(Main::getModule('Config')->getCfgVal('use_file_caching') == 1)
				Functions::file_put_contents('cache/BBCode.cache.php', '<?php $this->smilies = array(\'' . implode('\', \'', $toCache) . '\');' . (isset($twoCache) ? ' $this->aSmilies = array(\'' . implode('\', \'', $twoCache) . '\');' : '') . ' ?>', LOCK_EX, false, false);
		}
	}

	/**
	 * Returns all admin smilies.
	 *
	 * @return array All current admin smilies with synonym/image as key/value pairs
	 */
	public function getAdminSmilies()
	{
		return $this->aSmilies;
	}

	/**
	 * Returns all smilies.
	 *
	 * @return array All current smilies with synonym/image as key/value pairs
	 */
	public function getSmilies()
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
	public function parse($string, $enableHTML=false, $enableSmilies=true, $enableBBCode=true, &$topic=array())
	{
		if($enableHTML)
			$string = htmlspecialchars_decode($string, ENT_COMPAT);
		if($enableSmilies)
		{
			$string = strtr($string, $this->smilies);
			$string = strtr($string, $this->aSmilies);
		}
		if($enableBBCode)
		{
			//Cache topic IDs (if any)
			if(!isset($this->posterIDs))
				//Only consider numeric IDs (guest IDs are strings, but start with 0 at least, e.g. "0123" is a guest, too) and filter out dublicate ones
				$this->posterIDs = array_filter(array_unique(array_map('next', !empty($topic) && is_string($topic[0]) ? array_map(array('Functions', 'explodeByTab'), $topic) : $topic)), create_function('$id', 'return !Functions::isGuestID($id);')); #SORT_NUMERIC
			//Filter out ignored BBCode
			$string = preg_replace_callback("/\[noparse\](.*?)\[\/noparse\]/si", create_function('$elements', 'return Functions::str_replace(array(\'[\', \']\'), array(\'&#91;\', \'&#93;\'), $elements[1]);'), $string);
			//Start parsing
			$string = preg_replace_callback("/\[list\][<br \/>\r\n]*?\[\*\](.*?)\[\/list\]/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_LIST, \'listEntries\' => explode(\'[*]\', $elements[1])));') , $string);
			$string = preg_replace_callback("/\[b\](.*?)\[\/b\]/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_BOLD, \'boldText\' => $elements[1]));'), $string);
			$string = preg_replace_callback("/\[i\](.*?)\[\/i\]/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_ITALIC, \'italicText\' => $elements[1]));'), $string);
			$string = preg_replace_callback("/\[u\](.*?)\[\/u\]/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_UNDERLINE, \'underlineText\' => $elements[1]));'), $string);
			$string = preg_replace_callback("/\[s\](.*?)\[\/s\]/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_STRIKE, \'strikeText\' => $elements[1]));'), $string);
			$string = preg_replace_callback("/\[sup\](.*?)\[\/sup\]/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_SUPERSCRIPT, \'superText\' => $elements[1]));'), $string);
			$string = preg_replace_callback("/\[sub\](.*?)\[\/sub\]/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_SUBSCRIPT, \'subText\' => $elements[1]));'), $string);
			$string = preg_replace_callback("/\[hide\](.*?)\[\/hide\]/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_HIDE, \'hideText\' => $elements[1]));'), $string);
			$string = preg_replace_callback("/\[lock\](.*?)\[\/lock\]/si", array(&$this, 'cbLock'), $string);
			#create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_LOCK, \'lockText\' => in_array(Main::getModule(\'Auth\')->getUserID(), $this->posterIDs) ? $elements[1] : \'\'));')
			$string = preg_replace_callback("/\[center\](.*?)\[\/center\]/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_CENTER, \'centerText\' => $elements[1]));'), $string);
			$string = preg_replace_callback("/\[code\](.*?)\[\/code\]/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_CODE, \'codeLines\' => $elements[1]));'), $string);
			$string = preg_replace_callback("/\[php\](.*?)\[\/php\]/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_CODE, \'codeLines\' => Functions::str_replace(array(\'<code>\', \'</code>\'), \'\', highlight_string($elements[1], true))));'), $string);
			$string = preg_replace_callback("/\[email\](.*?)\[\/email\]/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_EMAIL, \'eMailAddress\' => $elements[1], \'eMailText\' => $elements[1]));'), $string);
			$string = preg_replace_callback("/\[email=(.*?)\](.*?)\[\/email\]/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_EMAIL, \'eMailAddress\' => $elements[1], \'eMailText\' => $elements[2]));'), $string);
			$string = preg_replace_callback("/\[img\](.*?)\[\/img\]/si", create_function('$elements', 'return Functions::substr($elements[1], 0, 11) == \'javascript:\' ? $elements[0] : Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_IMAGE, \'imageAddress\' => $elements[1], \'imageText\' => \'\'));'), $string);
			$string = preg_replace_callback("/\[img=(.*?)\](.*?)\[\/img\]/si", create_function('$elements', 'return Functions::substr($elements[1], 0, 11) == \'javascript:\' ? $elements[0] : Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_IMAGE, \'imageAddress\' => $elements[1], \'imageText\' => $elements[2]));'), $string);
			$string = preg_replace_callback("/\[url\](.*?)\[\/url\]/si", create_function('$elements', 'return Functions::substr($elements[1], 0, 11) == \'javascript:\' ? $elements[0] : Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_LINK, \'linkAddress\' => Functions::addHTTP($elements[1]), \'linkText\' => $elements[1]));'), $string);
			$string = preg_replace_callback("/\[url=(.*?)\](.*?)\[\/url\]/si", create_function('$elements', 'return Functions::substr($elements[1], 0, 11) == \'javascript:\' ? $elements[0] : Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_LINK, \'linkAddress\' => Functions::addHTTP($elements[1]), \'linkText\' => $elements[2]));'), $string);
			$string = preg_replace_callback("/\[color=(\#[a-fA-F0-9]{6}|[a-zA-Z]+)\](.*?)\[\/color\]/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_COLOR, \'colorCode\' => $elements[1], \'colorText\' => $elements[2]));'), $string);
			$string = preg_replace("/\[marquee\](.*?)\[\/marquee\]/si", '<marquee>\1</marquee>', $string);
			//TBB 1.2.3 BBCode hack support
			$string = preg_replace_callback("/\[size=(\-[12]{1}|\+[1-4]{1})\](.*?)\[\/size\]/si", create_function('$elements', 'switch($elements[1]) {case \'+4\': $elements[1] = \'300%\'; break; case \'+3\': $elements[1] = \'xx-large\'; break; case \'+2\': $elements[1] = \'x-large\'; break; case \'+1\': $elements[1] = \'large\'; break; case \'-1\': $elements[1] = \'x-small\'; break; case \'-2\': $elements[1] = \'xx-small\'; break;} return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_SIZE, \'sizeFont\' => $elements[1], \'sizeText\' => $elements[2]));'), $string);
			$string = preg_replace_callback("/\[glow=(\#[a-fA-F0-9]{6}|[a-zA-Z]+)\](.*?)\[\/glow\]/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_GLOW, \'glowColor\' => $elements[1], \'glowText\' => $elements[2]));'), $string);
			$string = preg_replace_callback("/\[shadow=(\#[a-fA-F0-9]{6}|[a-zA-Z]+)\](.*?)\[\/shadow\]/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_SHADOW, \'shadowColor\' => $elements[1], \'shadowText\' => $elements[2]));'), $string);
			$string = preg_replace_callback("/\[flash\](.*?)\[\/flash\]/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_FLASH, \'flashLink\' => $elements[1], \'flashWidth\' => 425, \'flashHeight\' => 355));'), $string);
			$string = preg_replace_callback("/\[flash[=| ](\d+),(\d+)\](.*?)\[\/flash\]/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_FLASH, \'flashLink\' => $elements[3], \'flashWidth\' => $elements[1], \'flashHeight\' => $elements[2]));'), $string);
			//Quotes at the end for linked sources
			while(preg_match("/\[quote\](.*?)\[\/quote\]/si", $string))
				$string = preg_replace_callback("/\[quote\](.*?)\[\/quote\][\r\n]*/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_QUOTE, \'quoteText\' => $elements[1], \'quoteTitle\' => Main::getModule(\'Language\')->getString(\'quote_colon\', \'BBCode\')));'), $string);
			while(preg_match("/\[quote=(.*?)\](.*?)\[\/quote\]/si", $string))
				$string = preg_replace_callback("/\[quote=(.*?)\](.*?)\[\/quote\][\r\n]*/si", create_function('$elements', 'return Main::getModule(\'Template\')->fetch(\'BBCode\', array(\'type\' => BBCODE_QUOTE, \'quoteText\' => $elements[2], \'quoteTitle\' => sprintf(Main::getModule(\'Language\')->getString(\'quote_by_x_colon\', \'BBCode\'), $elements[1])));'), $string);
		}
		return $string;
	}

	/**
	 * Returns parsed contents inside lock-BBCode.
	 *
	 * @param array $elements Recognized markup elements
	 */
	private function cbLock($elements)
	{
		return Main::getModule('Template')->fetch('BBCode', array('type' => BBCODE_LOCK, 'lockText' => in_array(Main::getModule('Auth')->getUserID(), $this->posterIDs) ? $elements[1] : ''));
	}
}
?>