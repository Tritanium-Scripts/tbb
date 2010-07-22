<?php
/**
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class PrivateMessage implements Module
{
	/**
	 * Mode to execute.
	 *
	 * @var string PM mode
	 */
	private $mode;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('' => 'PrivateMessageIndex', 'overview' => 'PrivateMessageIndex');

	private $pmBoxID;

	/**
	 * Sets mode and PM box ID.
	 *
	 * @param string $mode PM mode
	 * @return PrivateMessage New instance of this class
	 */
	function __construct($mode)
	{
		$this->mode = $mode;
		$this->pmBoxID = Functions::getValueFromGlobals('pmbox_id') or $this->pmBoxID = Main::getModule('Auth')->getUserID();
	}

	public function execute()
	{
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('pms'), INDEXFILE . '?faction=pm&amp;mode=overview' . SID_AMPER);
		if(!Main::getModule('Auth')->isLoggedIn())
			Main::getModule('Template')->printMessage('login_only');
		elseif($this->pmBoxID != Main::getModule('Auth')->getUserID())
			Main::getModule('Template')->printMessage('pm_no_access');
		switch($this->mode)
		{
			case 'overview':
			default:
			$pms = array_reverse(Functions::file('members/' . $this->pmBoxID . '.pm'));
			foreach($pms as &$curPM)
			{
				$curPM = Functions::explodeByTab($curPM);
				$curPM[3] = Functions::getProfileLink($curPM[3], true);
				$curPM[4] = Functions::formatDate($curPM[4]);
			}
			Main::getModule('Template')->assign('pms', $pms);
			break;
		}
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode]);
	}
}
?>