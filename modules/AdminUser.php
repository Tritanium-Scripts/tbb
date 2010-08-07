<?php
/**
 * Manages members.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class AdminUser implements Module
{
	/**
	 * Detected errors during user actions.
	 *
	 * @var array Error messages
	 */
	private $errors = array();

	/**
	 * Contains mode to execute.
	 *
	 * @var string Dummy mode
	 */
	private $mode;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('ad_user' => 'AdminUser',
		'search' => 'AdminUser',
		'new',
		'edit');

	/**
	 * Sets mode.
	 * 
	 * @param string $mode
	 * @return AdminUser New instance of this class
	 */
	function __construct($mode)
	{
		$this->mode = $mode;
	}

	/**
	 * Compares member search results by percentage of similarity of the search term.
	 *
	 * @param array $m1 First member search result to compare with
	 * @param array $m2 Second member search result to compare with
	 * @return int Comparison result as natural order
	 */
	private function cmpByPercent($m1, $m2)
	{
		return $m1['percent'] == $m2['percent'] ? ($m1['id'] == $m2['id'] ? 0 : ($m1['id'] > $m2['id'] ? 1 : -1)) : ($m1['percent'] < $m2['percent'] ? 1 : -1);
	}

	/**
	 * Executes mode.
	 */
	public function execute()
	{
		Functions::accessAdminPanel();
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('manage_user'), INDEXFILE . '?faction=ad_user' . SID_AMPER);
		switch($this->mode)
		{
			case 'new':
			break;

			case 'edit':
			break;

			default:
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('member_search'), INDEXFILE . '?faction=ad_user&amp;mode=search' . SID_AMPER);
			$searchMethod = Functions::getValueFromGlobals('searchmethod') or $searchMethod = 'id';
			$searchFor = Functions::strtolower(htmlspecialchars(trim(Functions::getValueFromGlobals('searched'))));
			$results = array();
			if(Functions::getValueFromGlobals('search') == 'yes')
			{
				if(empty($searchFor))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_search_term');
				else
				{
					switch($searchMethod)
					{
						case 'id':
						if(($userFile = Functions::getUserData($searchFor)) !== false)
							$results[] = array('id' => $userFile[1],
								'nick' => $userFile[0],
								'mail' => $userFile[3],
								'percent' => 100);
						break;

						case 'nick':
						case 'email':
						$index = $searchMethod == 'nick' ? 0 : 3;
						foreach(glob(DATAPATH . 'members/[!0]*.xbb') as $curMember)
						{
							$curMember = Functions::file($curMember, null, null, false);
							similar_text($curMember[$index], $searchFor, $curPercent); //Calculate percentage of similarity
							if($curPercent > 0) //Add to result list by having a minimum of similarity
								$results[] = array('id' => $curMember[1],
								'nick' => $curMember[0],
								'mail' => $curMember[3],
								'percent' => round($curPercent));
						}
						break;
					}
					if(count($results) > 1)
						usort($results, array($this, 'cmpByPercent'));
				}
			}
			Main::getModule('Template')->assign(array('results' => $results,
				'searchMethod' => $searchMethod,
				'searchFor' => $searchFor));
			break;
		}
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode], array('errors' => $this->errors));
	}
}
?>