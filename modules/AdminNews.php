<?php
/**
 * Manages the forum news.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class AdminNews implements Module
{
	/**
	 * Current news text.
	 *
	 * @var string News text
	 */
	private $news;

	/**
	 * News display duration.
	 *
	 * @var int Display duration
	 */
	private $newsDuration;

	/**
	 * Type of news.
	 *
	 * @var int News type
	 */
	private $newsType;

	/**
	 * Sets data of news.
	 *
	 * @return AdminNews New instance of this class
	 */
	function __construct()
	{
		$this->news = trim(Functions::getValueFromGlobals('news', false));
		$this->newsDuration = intval(Functions::getValueFromGlobals('expiredate')) or $this->newsDuration = -1;
		$this->newsType = intval(Functions::getValueFromGlobals('typ')) or $this->newsType = 1;
	}

	/**
	 * Edits and saves the forum news.
	 */
	public function execute()
	{
		Functions::accessAdminPanel();
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('edit_forum_news'), INDEXFILE . '?faction=ad_news' . SID_AMPER);
		if(Functions::getValueFromGlobals('save') == 'yes')
		{
			if(!empty($this->news))
			{
				if($this->newsDuration != -1)
					$this->newsDuration = time()+60*$this->newsDuration;
				$this->news = $this->newsType . "\t" . $this->newsDuration . "\t\n" . ($this->newsType == 1 ? Functions::nl2br($this->news) : ($this->newsType == 2 ? $this->news : ''));
			}
			Functions::file_put_contents('vars/news.var', $this->news);
			Main::getModule('Logger')->log('%s updated forum news', LOG_ACP_ACTION);
		}
		//Process available/updated news
		if(count($this->news = Functions::file('vars/news.var')) != 0)
		{
			list($this->newsType, $this->newsDuration) = Functions::explodeByTab(array_shift($this->news));
			$newsPreview = array();
			foreach($this->news as $curNews)
				$newsPreview[] = Main::getModule('BBCode')->parse($curNews);
		}
		else
			$this->news = $newsPreview = array('');
		if($this->newsDuration != -1)
			//Get duration choice from back calculation
			$this->newsDuration = $this->newsDuration-time() <= 3600 ? 60 : ($this->newsDuration-time() <= 7200 ? 120 : ($this->newsDuration-time() <= 18000 ? 300 : ($this->newsDuration-time() <= 86400 ? 1440 : ($this->newsDuration-time() <= 172800 ? 2880 : ($this->newsDuration-time() <= 432000 ? 7200 : ($this->newsDuration-time() <= 864000 ? 14400 : 43200))))));
		Main::getModule('Template')->printPage('AdminNews', array('newsType' => $this->newsType,
			'newsDuration' => $this->newsDuration,
			'newsText' => htmlspecialchars($this->newsType == 1 ? Functions::br2nl($this->news[0]) : implode("\n", $this->news)),
			'newsPreview' => $this->newsType == 1 ? $newsPreview[0] : $newsPreview,
			//Show admin smilies
			'isMod' => true));
	}
}
?>