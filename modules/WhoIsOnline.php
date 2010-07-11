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
	 * @var int Timeout in seconds
	 */
	private $timeout;

	/**
	 * Sets config values.
	 */
	public function __construct()
	{
		$this->enabled = Main::getModule('Config')->getCfgVal('wio') == 1;
		$this->timeout = Main::getModule('Config')->getCfgVal('wio_timeout')*60;
	}

	/**
	 * Parses WIO data file and displays the WIO list.
	 */
	public function execute()
	{
		if(!$this->enabled)
			Main::getModule('Template')->printMessage('function_deactivated');
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('who_is_online'));
		$this->setLocation('WhoIsOnline'); //Add WIO location now, in Template module would be too late
		$time = time(); //Same time as starting basis for all entries
		$wioLocations = array();
		foreach(Functions::file('vars/wio.var') as $curWIOEntry)
		{
			$curWIOEntry = Functions::explodeByTab($curWIOEntry);
			$curWIOEntry[2] = Functions::explodeByComma($curWIOEntry[2]); //Get IDs of position, if any
			//Admins may also see ghosts
			if(!($curWIOEntryIsGhost = $curWIOEntry[4] == '1') || Main::getModule('Auth')->isAdmin())
			{
				$curUser = is_numeric($curWIOEntry[1]) ? Functions::getProfileLink($curWIOEntry[1]) : Main::getModule('Language')->getString('guest') . Functions::substr($curWIOEntry[1], 5, 5);
				$curTime = ($curTime = $time-$curWIOEntry[0]) < 60 ? sprintf(Main::getModule('Language')->getString('x_seconds_ago'), $curTime) : ($curTime < 120 ? Main::getModule('Language')->getString('one_minute_ago') : sprintf(Main::getModule('Language')->getString('x_minutes_ago'), $curTime/60));
				switch($curWIOEntry[2][0])
				{
					case 'ForumIndex':
					$wioLocations[] = array($curUser, sprintf(Main::getModule('Language')->getString('views_the_forum_index'), INDEXFILE . SID_QMARK), $curWIOEntryIsGhost, $curTime);
					break;

					case 'ViewForum':
					$wioLocations[] = Main::getModule('Config')->getCfgVal('show_private_forums') == 1 || Functions::checkUserAccess($curWIOEntry[2][1], 0) ? array($curUser, sprintf(Main::getModule('Language')->getString('views_the_forum_x'), INDEXFILE . '?mode=viewforum&amp;forum_id=' . $curWIOEntry[2][1] . SID_AMPER, next(Functions::getForumData($curWIOEntry[2][1]))), $curWIOEntryIsGhost, $curTime) : $wioLocations[] = array($curUser, sprintf(Main::getModule('Language')->getString('views_a_forum'), INDEXFILE . '?mode=viewforum&amp;forum_id=' . $curWIOEntry[2][1] . SID_AMPER), $curWIOEntryIsGhost, $curTime);
					break;

					case 'ViewTopic':
					$wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0) ? array($curUser,  sprintf(Main::getModule('Language')->getString('views_the_topic_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER, Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime) : array($curUser, sprintf(Main::getModule('Language')->getString('views_a_topic'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER), $curWIOEntryIsGhost, $curTime);
					break;

					case 'WhoIsOnline':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('views_the_wio_list'), $curWIOEntryIsGhost, $curTime);
					break;

					default:
					$wioLocations[] = array($curUser, '<b>WARNING: Unknown WIO location!</b>', $curWIOEntryIsGhost, $curTime);
					break;
				}
			}
		}
		Main::getModule('Template')->printPage('WhoIsOnline', array('wioLocations' => $wioLocations));
	}

	/**
	 * Returns current active members and amount of guests and ghosts.
	 *
	 * @return array Guests / ghosts / members triple
	 */
	public function getUser()
	{
		$guests = $ghosts = 0;
		$members = array();
		if($this->enabled)
			foreach($this->refreshVar() as $curWIOEntry)
				is_numeric($curWIOEntry[1]) ? ($curWIOEntry[4] != '1' ? $members[] = Functions::getProfileLink($curWIOEntry[1], false, ' class="small"', true) : $ghosts++) : $guests++;
		return array($guests, $ghosts, $members);
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
		{
			if($curWIOEntry[1] == Main::getModule('Auth')->getWIOID())
			{
				//Refresh time and location
				$curWIOEntry[0] = time();
				$curWIOEntry[2] = $id;
				$found = true;
			}
			//Implode all entries (incl. refreshed one) back
			$curWIOEntry = implode("\t", $curWIOEntry);
		}
		//If user was found in WIO, write updated data, otherwise append new entry
		$found ? Functions::file_put_contents('vars/wio.var', implode("\n", $wioFile)) : Functions::file_put_contents('vars/wio.var', time() . "\t" . Main::getModule('Auth')->getWIOID() . "\t" . $id . "\t\t" . Main::getModule('Auth')->isGhost()/* . "\n"*/, FILE_APPEND);
	}

	/**
	 * Refreshes contents of the WIO data file by removing outdated entries.
	 *
	 * @return array Already exploded contents of refreshed WIO file.
	 */
	private function refreshVar()
	{
		$update = false;
		$wioFile = Functions::file('vars/wio.var');
		$size = count($wioFile);
		for($i=0; $i<$size; $i++)
		{
			$wioFile[$i] = Functions::explodeByTab($wioFile[$i]);
			if($wioFile[$i][0] + $this->timeout < time())
			{
				//Delete outdated
				unset($wioFile[$i]);
				$update = true;
			}
		}
		if($update)
			Functions::file_put_contents('vars/wio.var', implode("\n", array_map(create_function('$entry', 'return implode("\t", $entry);'), $wioFile)));
		return $wioFile;
	}
}
?>