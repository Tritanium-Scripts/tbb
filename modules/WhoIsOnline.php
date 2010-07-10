<?php
/**
 * Manages WIO lists.
 *
 * WIO var file structure:
 * 0:timestamp - 1:user/guestSpecialID - 2:location - 3:? - [ - 4:isGhost]
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class WhoIsOnline implements Module
{
	/**
	 * Activation state of WIO module.
	 *
	 * @var bool State of WIO module
	 */
	private $enabled;

	/**
	 * Timeout to clear listed user from WIO list.
	 *
	 * @var int Timeout in minutes
	 */
	private $timeout;

	/**
	 * Sets config values.
	 */
	public function __construct()
	{
		$this->enabled = Main::getModule('Config')->getCfgVal('wio') == 1;
		$this->timeout = Main::getModule('Config')->getCfgVal('wio_timeout');
	}

	/**
	 * Parses WIO data file and displays the WIO list.
	 */
	public function execute()
	{
		if(!$this->enabled)
			return;
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('who_is_online'));
		$this->setLocation('WhoIsOnline'); //Add WIO location now, in Template module would be too late
		$wioLocations = array();
		foreach(Functions::file('vars/wio.var') as $curWIOEntry)
		{
			$curWIOEntry = Functions::explodeByTab($curWIOEntry);
			$curWIOEntry[2] = Functions::explodeByComma($curWIOEntry[2]);
			//Admins may also see ghosts
			if(Main::getModule('Auth')->isAdmin() || $curWIOEntry[4] != '1')
			{
				$curUser = is_numeric($curWIOEntry[1]) ? Functions::getProfileLink($curWIOEntry[1]) : Main::getModule('Language')->getString('guest') . Functions::substr($curWIOEntry[1], 5, 5);
				switch($curWIOEntry[2][0])
				{
					case 'ForumIndex':
					default:
					$wioLocations[] = array($curUser, sprintf(Main::getModule('Language')->getString('views_the_forum_index'), INDEXFILE . SID_QMARK), $curWIOEntry[4] == '1');
					break;

					case 'ViewForum':
					if(Main::getModule('Config')->getCfgVal('show_private_forums') == 1 || Functions::checkMemberAccess($curWIOEntry[2][1], 0))
						$wioLocations[] = array($curUser, sprintf(Main::getModule('Language')->getString('views_the_forum_x'), INDEXFILE . '?mode=viewforum&amp;forum_id=' . $curWIOEntry[2][1] . SID_AMPER, next(Functions::getForumData($curWIOEntry[2][1]))), $curWIOEntry[4] == '1');
					else
						$wioLocations[] = array($curUser, sprintf(Main::getModule('Language')->getString('views_a_forum'), INDEXFILE . '?mode=viewforum&amp;forum_id=' . $curWIOEntry[2][1] . SID_AMPER), $curWIOEntry[4] == '1');
					break;

					case 'ViewTopic':
					break;

					case 'WhoIsOnline':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('views_the_wio_list'), $curWIOEntry[4] == '1');
					break;
				}
			}
		}
		Main::getModule('Template')->printPage('WhoIsOnline', array('wioLocations' => $wioLocations));
	}

	/**
	 * Returns current active members and amount of guests and ghosts.
	 *
	 * @return array Guests / members / ghosts triple
	 */
	public function getUser()
	{
		$guests = $ghosts = 0;
		$members = array();
		if($this->enabled)
			foreach($this->refreshVar() as $curWIOEntry)
				is_numeric($curWIOEntry[1]) ? ($curWIOEntry[4] != '1' ? $members[] = Functions::getProfileLink($curWIOEntry[1], false, ' class="small"') : $ghosts++) : $guests++;
		return array($guests, $members, $ghosts);
	}

	/**
	 * Writes WIO location for current user.
	 *
	 * @param string $id Identifier for location
	 */
	public function setLocation($id)
	{
		if(!$this->enabled)
			return;
		$found = false;
		$wioFile = $this->refreshVar();
		foreach($wioFile as &$curWIOEntry)
			//Implode all entries (incl. new one) back
			if($curWIOEntry[1] == Main::getModule('Auth')->getUserID())
			{
				$curWIOEntry = time() . "\t" . $curWIOEntry[1] . "\t" . $id . "\t\t" . $curWIOEntry[4];
				$found = true;
			}
			else
				$curWIOEntry = implode("\t", $curWIOEntry);
		//If user was found in WIO, write updated data, otherwise append new entry
		$found ? Functions::file_put_contents('vars/wio.var', implode("\n", $wioFile)) : Functions::file_put_contents('vars/wio.var', time() . "\t" . Main::getModule('Auth')->getUserID() . "\t" . $id . "\t\t" . Main::getModule('Auth')->isGhost(), FILE_APPEND);
	}

	/**
	 * Refreshes contents of the WIO data file.
	 *
	 * @return array Already exploded contents of WIO file.
	 */
	private function refreshVar()
	{
		$update = false;
		$wioFile = Functions::file('vars/wio.var');
		foreach($wioFile as &$curWIOEntry)
		{
			$curWIOEntry = Functions::explodeByTab($curWIOEntry);
			if($curWIOEntry[0] + $timeout*60 < time())
			{
				unset($curWIOEntry);
				$update = true;
			}
		}
		if($update)
			Functions::file_put_contents('vars/wio.var', implode("\n", $wioFile));
		return $wioFile;
	}
}
?>