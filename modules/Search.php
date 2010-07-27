<?php
class Search implements Module
{
	/**
	 * Detected errors during search operation.
	 *
	 * @var array Error messages
	 */
	private $errors = array();

	private $searchAge;

	private $searchFor;

	private $searchIn;

	private $searchOption;

	function __construct()
	{
		$this->searchAge = Functions::getValueFromGlobals('searchAge');
		$this->searchFor = Functions::getValueFromGlobals('searchfor');
		$this->searchIn = Functions::getValueFromGlobals('auswahl');
		$this->searchOption = Functions::getValueFromGlobals('searchOption');
	}

	/**
	 * Starts the search.
	 */
	public function execute()
	{
		if(Functions::getValueFromGlobals('search') == 'yes')
		{
			if(empty($this->searchFor))
				$this->errors[] = Main::getModule('Language')->getString('please_enter_a_search_term');
			if(empty($this->searchIn))
				$this->errors[] = Main::getModule('Language')->getString('please_select_a_forum');
			if(empty($this->errors))
			{
				//Detect search id and previous search, which is already done
				$searchID = md5($this->searchAge . $this->searchFor . $this->searchIn . $this->searchOption);
				if(!isset($_SESSION['searchID']) || $_SESSION['searchID'] != $searchID)
					$_SESSION['searchID'] = $searchID;
			}
		}
		Main::getModule('Template')->printPage('Search');
	}
}
?>