<?php
/**
 * BBCode parser.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class BBCode
{
	/**
	 * Contains ready-for-use smilies with synonym and URL.
	 *
	 * @var array Prepared smilies for search and replace
	 */
	private $smilies = array();

	/**
	 * Prepares smilies.
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
			if(Main::getModule('Config')->getCfgVal('use_file_caching') == 1)
				Functions::file_put_contents('cache/BBCode.cache.php', '<?php $this->smilies = array(\'' . implode('\', \'', $toCache) . '\'); ?>');
		}
	}

	/**
	 * Formats a text with HTML, smilies and BBCode.
	 *
	 * @param string $string The string to parse
	 * @param bool $enableHTML Allow HTML tags
	 * @param bool $enableSmilies Parse smilies
	 * @param bool $enableBBCode Parse BBCode
	 * @return string Formatted string
	 */
	public function parse($string, $enableHTML=false, $enableSmilies=true, $enableBBCode=true)
	{
		if(!$enableHTML)
			$string = htmlspecialchars_decode($string, ENT_COMPAT);
		if($enableSmilies)
			$string = strtr($string, $this->smilies);
		return $string;
	}
}
?>