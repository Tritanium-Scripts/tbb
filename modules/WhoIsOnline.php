<?php
/**
 * Manages WIO lists.
 *
 * WIO var file structure:
 * 0:timestamp - 1:user/guestSpecialID - 2:location - 3:? - [ - 4:isGhost]
 *
 * WWO var file structure:
 * 0:todaysDate - 1:0:recordMember - 1:1:recordDate[ - 2:guestCounter - 3:members]
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
	 * Contents of WWO var file.
	 *
	 * @var array WWO data
	 */
	private $wwoFile;

	/**
	 * Sets config values and prepares WWO data.
	 *
	 * @return WhoIsOnline New instance of this class
	 */
	public function __construct()
	{
		$this->enabled = Main::getModule('Config')->getCfgVal('wio') == 1;
		$this->timeout = Main::getModule('Config')->getCfgVal('wio_timeout')*60;
		if(!$this->enabled)
			return;
		//Check WWO file
		$this->wwoFile = file_exists('vars/today.var') ? explode("\n", Functions::file_get_contents('vars/today.var')) : array('', "0\t" . date('dmYHis'));
		$update = false;
		if($this->wwoFile[0] != date('dmY'))
		{
			//Reset WWO statistics for new day
			$this->wwoFile = array(date('dmY'), $this->wwoFile[1], 0, '');
			$update = true;
		}
		if(!Main::getModule('Auth')->isConnected() && !Main::getModule('Auth')->isLoggedIn())
		{
			$this->wwoFile[2]++;
			$update = true;
		}
		elseif(Main::getModule('Auth')->isLoggedIn() && !in_array(Main::getModule('Auth')->getUserID() . '#' . Main::getModule('Auth')->isGhost(), Functions::explodeByComma($this->wwoFile[3])))
		{
			//Add member with ghost state
			$this->wwoFile[3] .= (!empty($this->wwoFile[3]) ? ',' : '') . Main::getModule('Auth')->getUserID() . '#' . Main::getModule('Auth')->isGhost();
			$record = Functions::explodeByTab($this->wwoFile[1]);
			//Check record
			if($record[0] < ($size = count(Functions::explodeByComma($this->wwoFile[3]))))
				$this->wwoFile[1] = $size . "\t" . date('dmYHis');
			$update = true;
		}
		if($update)
			Functions::file_put_contents('vars/today.var', implode("\n", $this->wwoFile));
	}

	/**
	 * Deletes a WIO ID from WIO list in case of logins or logouts.
	 *
	 * @param string $wioID WIO ID to remove from WIO
	 */
	public function delete($wioID)
	{
		if($this->enabled)
			$this->refreshVar($wioID);
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

					case 'Message':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('views_a_message'), $curWIOEntryIsGhost, $curTime);
					break;

					case 'Login':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('logs_in'), $curWIOEntryIsGhost, $curTime);
					break;

					case 'RequestPassword':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('requests_a_new_password'), $curWIOEntryIsGhost, $curTime);
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
	 * Returns most active members with date.
	 *
	 * @return array Members / date couple
	 */
	public function getRecord()
	{
		$record = Functions::explodeByTab($this->wwoFile[1]);
		$record[1] = Functions::formatDate(Functions::substr($record[1], 4, 4) . Functions::substr($record[1], 2, 2) . Functions::substr($record[1], 0, 2) . Functions::substr($record[1], 8));
		return $record;
	}

	/**
	 * Returns current active members and amount of guests and ghosts.
	 *
	 * @return array Guests / ghosts / memberProfiles triple
	 */
	public function getUserWIO()
	{
		$guests = $ghosts = 0;
		$members = array();
		if($this->enabled)
			foreach($this->refreshVar() as $curWIOEntry)
				is_numeric($curWIOEntry[1]) ? ($curWIOEntry[4] != '1' ? $members[] = Functions::getProfileLink($curWIOEntry[1], false, ' class="small"', true) : $ghosts++) : $guests++;
		return array($guests, $ghosts, $members);
	}

	/**
	 * Returns todays active members and amount of guests and ghosts.
	 *
	 * @return array Guests / ghosts / members / memberProfiles-isGhost-couple quadruple
	 */
	public function getUserWWO()
	{
		$ghosts = 0;
		$members = array();
		if($this->enabled && !empty($this->wwoFile[3]))
			foreach(Functions::explodeByComma($this->wwoFile[3]) as $curWWOEntry)
			{
				$curWWOEntry = explode('#', $curWWOEntry);
				if(!empty($curWWOEntry[1]))
				{
					$ghosts++;
					if(Main::getModule('Auth')->isAdmin())
						$members[] = array(Functions::getProfileLink($curWWOEntry[0]), true);
				}
				else
					$members[] = array(Functions::getProfileLink($curWWOEntry[0]), false);
			}
		return array($this->wwoFile[2], $ghosts, count($members), $members);
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
		$found ? Functions::file_put_contents('vars/wio.var', implode("\n", $wioFile)) : Functions::file_put_contents('vars/wio.var', (count($wioFile) > 0 ? "\n" : '') . time() . "\t" . Main::getModule('Auth')->getWIOID() . "\t" . $id . "\t\t" . Main::getModule('Auth')->isGhost(), FILE_APPEND);
	}

	/**
	 * Refreshes contents of the WIO data file by removing outdated entries.
	 *
	 * @param string $deleteID Optional WIO ID to delete nevertheless
	 * @return array Already exploded contents of refreshed WIO file.
	 */
	private function refreshVar($deleteWIOID='')
	{
		$update = false;
		$wioFile = Functions::file('vars/wio.var');
		$size = count($wioFile);
		for($i=0; $i<$size; $i++)
		{
			$wioFile[$i] = Functions::explodeByTab($wioFile[$i]);
			if($wioFile[$i][0] + $this->timeout < time() || $wioFile[$i][1] == $deleteWIOID)
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