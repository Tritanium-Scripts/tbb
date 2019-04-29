<?php
/**
 * Manages WIO lists.
 *
 * WIO var file structure:
 * 0:timestamp - 1:user/guestSpecialID - 2:location - 3:?[ - 4:isGhost - 5:userAgent]
 *
 * WWO var file structure:
 * 0:todaysDate - 1:0:recordMember - 1:1:recordDate[ - 2:guestCounter - 3:members - 4:bots]
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2017 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.6
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
		Functions::getFileLock('today');
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
			if($this->isBot($_SERVER['HTTP_USER_AGENT']))
				$this->wwoFile[4]++;
			else
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
		Functions::releaseLock('today');
	}

	/**
	 * Deletes a WIO ID from WIO list in case of logins or logouts.
	 *
	 * @param string $wioID WIO ID to remove from WIO
	 */
	public function delete($wioID)
	{
		if($this->enabled)
		{
			Functions::getFileLock('wio');
			$this->refreshVar($wioID);
			Functions::releaseLock('wio');
		}
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
				$curUser = is_numeric($curWIOEntry[1]) ? Functions::getProfileLink($curWIOEntry[1]) : Main::getModule('Language')->getString($this->isBot($curWIOEntry[5]) ? 'bot' : 'guest') . Functions::substr($curWIOEntry[1], 5, 5);
				$curTime = ($curTime = $time-$curWIOEntry[0]) < 60 ? sprintf(Main::getModule('Language')->getString('x_seconds_ago'), $curTime) : ($curTime < 120 ? Main::getModule('Language')->getString('one_minute_ago') : sprintf(Main::getModule('Language')->getString('x_minutes_ago'), $curTime/60));
				//Only admins may see user agents
				if(!Main::getModule('Auth')->isAdmin())
					$curWIOEntry[5] = '';
				//Switching through subAction
				switch($curWIOEntry[2][0])
				{
					case 'ForumIndex':
					$wioLocations[] = array($curUser, sprintf(Main::getModule('Language')->getString('views_the_forum_index'), INDEXFILE . SID_QMARK), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'ViewForum':
					$wioLocations[] = Main::getModule('Config')->getCfgVal('show_private_forums') == 1 || Functions::checkUserAccess($curWIOEntry[2][1], 0) ? array($curUser, sprintf(Main::getModule('Language')->getString('views_the_forum_x'), INDEXFILE . '?mode=viewforum&amp;forum_id=' . $curWIOEntry[2][1] . SID_AMPER, next(Functions::getForumData($curWIOEntry[2][1]))), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]) : $wioLocations[] = array($curUser, sprintf(Main::getModule('Language')->getString('views_a_forum'), INDEXFILE . '?mode=viewforum&amp;forum_id=' . $curWIOEntry[2][1] . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'ViewTopic':
					$wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0) ? array($curUser, sprintf(Main::getModule('Language')->getString('views_the_topic_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER, Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]) : array($curUser, sprintf(Main::getModule('Language')->getString('views_a_topic'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'ViewTodaysPosts':
					$wioLocations[] = array($curUser, sprintf(Main::getModule('Language')->getString('views_todays_posts'), INDEXFILE . '?faction=todaysPosts' . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'RSSFeed':
					$wioLocations[] = array($curUser, sprintf(Main::getModule('Language')->getString('views_the_rss_feed'), INDEXFILE . '?faction=rssFeed' . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'WhoIsOnline':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('views_the_wio_list'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'MemberList':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('views_the_member_list'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'Message':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('views_a_message'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'Login':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('logs_in'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'RequestPassword':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('requests_a_new_password'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'ViewProfile':
					$wioLocations[] = array($curUser, sprintf(Main::getModule('Language')->getString('views_the_profile_from_x'), Functions::getProfileLink($curWIOEntry[2][1])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'vCard':
					$wioLocations[] = array($curUser, sprintf(Main::getModule('Language')->getString('downloads_the_vcard_from_x'), Functions::getProfileLink($curWIOEntry[2][1])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'SendMail':
					$wioLocations[] = array($curUser, sprintf(Main::getModule('Language')->getString('writes_a_mail_to_x'), Functions::getProfileLink($curWIOEntry[2][1])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'ViewAchievements':
					$wioLocations[] = array($curUser, sprintf(Main::getModule('Language')->getString('views_achievements_from_x'), Functions::getProfileLink($curWIOEntry[2][1])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'EditProfile':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('edits_own_profile'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'EditProfileConfirmDelete':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('deletes_own_account'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'Register':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('registers'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'BoardRules':
					$wioLocations[] = array($curUser, sprintf(Main::getModule('Language')->getString('reads_board_rules'), INDEXFILE . '?faction=regeln' . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'FAQ':
					$wioLocations[] = array($curUser, sprintf(Main::getModule('Language')->getString('views_the_faq'), INDEXFILE . '?faction=faq' . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'Credits':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('views_the_credits'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'PrivateMessageIndex':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('manages_pms'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'PrivateMessageViewPM':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('reads_a_pm'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'PrivateMessageNewPM':
					case 'PrivateMessageNewPMConfirmSend':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('writes_new_pm'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'PrivateMessageConfirmDelete':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('deletes_a_pm'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'PostNewTopic':
					$wioLocations[] = Main::getModule('Config')->getCfgVal('show_private_forums') == 1 || Functions::checkUserAccess($curWIOEntry[2][1], 0) ? array($curUser, sprintf(Main::getModule('Language')->getString('posts_new_topic_in_x'), INDEXFILE . '?mode=viewforum&amp;forum_id=' . $curWIOEntry[2][1] . SID_AMPER, next(Functions::getForumData($curWIOEntry[2][1]))), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]) : array($curUser, Main::getModule('Language')->getString('posts_new_topic'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'PostNewPoll':
					$wioLocations[] = Main::getModule('Config')->getCfgVal('show_private_forums') == 1 || Functions::checkUserAccess($curWIOEntry[2][1], 0) ? array($curUser, sprintf(Main::getModule('Language')->getString('posts_new_poll_in_x'), INDEXFILE . '?mode=viewforum&amp;forum_id=' . $curWIOEntry[2][1] . SID_AMPER, next(Functions::getForumData($curWIOEntry[2][1]))), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]) : array($curUser, Main::getModule('Language')->getString('posts_new_poll'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'PostReply':
					$wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0) ? array($curUser, sprintf(Main::getModule('Language')->getString('writes_reply_to_topic_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER, Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]) : array($curUser, sprintf(Main::getModule('Language')->getString('writes_reply_to_a_topic'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'PostViewIP':
					$wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0) ? array($curUser, sprintf(Main::getModule('Language')->getString('views_ip_of_post_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER . '&amp;z=' . $curWIOEntry[2][4] . '#post' . $curWIOEntry[2][3], Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]) : array($curUser, sprintf(Main::getModule('Language')->getString('views_ip_of_a_post'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER . '&amp;z=' . $curWIOEntry[2][4] . '#post' . $curWIOEntry[2][3]), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'PostBlockIP':
					$wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0) ? array($curUser, sprintf(Main::getModule('Language')->getString('blocks_ip_of_post_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER . '&amp;z=' . $curWIOEntry[2][4] . '#post' . $curWIOEntry[2][3], Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]) : array($curUser, sprintf(Main::getModule('Language')->getString('blocks_ip_of_a_post'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER . '&amp;z=' . $curWIOEntry[2][4] . '#post' . $curWIOEntry[2][3]), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'EditPoll':
					$wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0) ? array($curUser, sprintf(Main::getModule('Language')->getString('edits_the_poll_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER, Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]) : array($curUser, sprintf(Main::getModule('Language')->getString('edits_a_poll'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'EditPost':
					$wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0) ? array($curUser, sprintf(Main::getModule('Language')->getString('edits_the_post_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER . '&amp;z=' . $curWIOEntry[2][4] . '#post' . $curWIOEntry[2][3], Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]) : array($curUser, sprintf(Main::getModule('Language')->getString('edits_a_post'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER . '&amp;z=' . $curWIOEntry[2][4] . '#post' . $curWIOEntry[2][3]), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'EditPostConfirmDelete':
					$wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0) ? array($curUser, sprintf(Main::getModule('Language')->getString('deletes_the_post_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER . '&amp;z=' . $curWIOEntry[2][4] . '#post' . $curWIOEntry[2][3], Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]) : array($curUser, sprintf(Main::getModule('Language')->getString('deletes_a_post'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER . '&amp;z=' . $curWIOEntry[2][4] . '#post' . $curWIOEntry[2][3]), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'EditTopicDelete':
					$wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0) ? array($curUser, sprintf(Main::getModule('Language')->getString('deletes_the_topic_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER, Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]) : array($curUser, sprintf(Main::getModule('Language')->getString('deletes_a_topic'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'EditTopicClose':
					$wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0) ? array($curUser, sprintf(Main::getModule('Language')->getString('closes_the_topic_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER, Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]) : array($curUser, sprintf(Main::getModule('Language')->getString('closes_a_topic'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'EditTopicOpen':
					$wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0) ? array($curUser, sprintf(Main::getModule('Language')->getString('opens_the_topic_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER, Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]) : array($curUser, sprintf(Main::getModule('Language')->getString('opens_a_topic'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'EditTopicMove':
					$wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0) ? array($curUser, sprintf(Main::getModule('Language')->getString('moves_the_topic_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER, Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]) : array($curUser, sprintf(Main::getModule('Language')->getString('moves_a_topic'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'Search':
					$wioLocations[] = array($curUser, sprintf(Main::getModule('Language')->getString('searches_the_board'), INDEXFILE . '?faction=search' . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'SearchResults':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('views_search_results'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'Newsletter':
					$wioLocations[] = array($curUser, sprintf(Main::getModule('Language')->getString('is_in_newsletter_archive'), INDEXFILE . '?faction=newsletter' . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'NewsletterReadLetter':
					$wioLocations[] = array($curUser, sprintf(Main::getModule('Language')->getString('reads_a_newsletter'), INDEXFILE . '?faction=newsletter&amp;mode=read&amp;newsletter=' . $curWIOEntry[2][1] . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'Upload':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('uploads_a_file'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminIndex':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('is_in_administration'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminForum':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('manages_forums_categories'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminForumIndex':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('manages_forums'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminForumEditForum':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('edits_a_forum'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminForumDeleteForum':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('deletes_a_forum'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminForumNewForum':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('creates_a_new_forum'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminForumSpecialRights':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('manages_special_rights'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminForumNewUserRight':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('creates_new_user_special_right'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminForumNewGroupRight':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('creates_new_group_special_right'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminForumIndexCat':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('manages_categories'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminForumEditCat':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('edits_a_category'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminForumNewCat':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('creates_new_category'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminRankIndex':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('manages_user_ranks'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminRankEditRank':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('edits_an_user_rank'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminRankNewRank':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('creates_new_user_rank'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminConfig':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('edits_board_settings'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminConfigResetConfirm':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('resets_board_settings'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminConfigCountersConfirm':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('recalculates_counters'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminLogfile':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('manages_logfiles'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminLogfileViewLog':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('views_a_logfile'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminTemplate':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('manages_templates'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminNews':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('writes_board_news'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminMailList':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('retrieves_mail_list'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminUser':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('manages_user'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminUserNewUser':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('creates_new_user'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminUserEditUser':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('edits_an_user'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminGroup':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('manages_groups'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminGroupNewGroup':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('creates_new_group'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminGroupEditGroup':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('edits_a_group'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminGroupDeleteGroup':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('deletes_a_group'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminCensor':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('manages_censorships'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminCensorNewWord':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('creates_new_censorship'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminCensorEditWord':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('edits_a_censorship'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminIP':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('manages_ip_blocks'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminIPNewBlock':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('creates_new_ip_block'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminSmiley':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('manages_smilies'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminSmileyNewSmiley':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('creates_new_smiley'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminSmileyEditSmiley':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('edits_a_smiley'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminNewsletter':
					case 'AdminNewsletterConfirm':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('writes_a_newsletter'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					case 'AdminDeleteOld':
					$wioLocations[] = array($curUser, Main::getModule('Language')->getString('deletes_old_topics'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;

					default:
					$wioLocations[] = array($curUser, '<b>WARNING: Unknown WIO location!</b>', $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]);
					break;
				}
			}
		}
		Main::getModule('Template')->printPage('WhoIsOnline', 'wioLocations', $wioLocations);
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
	 * Returns current active members and amount of guests, ghosts and bots.
	 *
	 * @return array Guests / ghosts / memberProfiles / bots quadruple
	 */
	public function getUserWIO()
	{
		$guests = $ghosts = $bots = 0;
		$members = array();
		if($this->enabled)
		{
			Functions::getFileLock('wio');
			foreach($this->refreshVar() as $curWIOEntry)
				is_numeric($curWIOEntry[1]) ? ($curWIOEntry[4] != '1' ? $members[] = Functions::getProfileLink($curWIOEntry[1], false, ' class="small"', true) : $ghosts++) : ($this->isBot($curWIOEntry[5]) ? $bots++ : $guests++);
			Functions::releaseLock('wio');
		}
		return array($guests, $ghosts, $members, $bots);
	}

	/**
	 * Returns todays active members and amount of guests, ghosts and bots.
	 *
	 * @return array Guests / ghosts / members / memberProfiles-isGhost-couples / bots quintuple
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
						$members[] = array(Functions::getProfileLink($curWWOEntry[0], true), true);
				}
				else
					$members[] = array(Functions::getProfileLink($curWWOEntry[0], true), false);
			}
		return array($this->wwoFile[2], $ghosts, count($members), $members, $this->wwoFile[4]);
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
		Functions::getFileLock('wio');
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
			$curWIOEntry = Functions::implodeByTab($curWIOEntry);
		}
		//If user was found in WIO, write updated data, otherwise append new entry
		$found ? Functions::file_put_contents('vars/wio.var', implode("\n", $wioFile)) : Functions::file_put_contents('vars/wio.var', (count($wioFile) > 0 ? "\n" : '') . time() . "\t" . Main::getModule('Auth')->getWIOID() . "\t" . $id . "\t\t" . Main::getModule('Auth')->isGhost() . "\t" . htmlspecialchars($_SERVER['HTTP_USER_AGENT']), FILE_APPEND);
		Functions::releaseLock('wio');
	}

	/**
	 * Refreshes contents of the WIO data file by removing outdated entries.
	 *
	 * @param string $deleteWIOID Optional WIO ID to delete nevertheless
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
			Functions::file_put_contents('vars/wio.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $wioFile)));
		return $wioFile;
	}

	/**
	 * Returns given user agent being used by a web crawler.
	 *
	 * @param string $userAgent User agent to check
	 * @return bool User agent being used by a search bot
	 */
	private function isBot($userAgent)
	{
		return Functions::stripos($userAgent, 'bot') !== false
			|| Functions::stripos($userAgent, 'spider') !== false
			|| Functions::stripos($userAgent, 'crawl') !== false
			|| Functions::stripos($userAgent, 'slurp') !== false
			|| Functions::stripos($userAgent, 'qwant') !== false
			|| Functions::stripos($userAgent, 'bubing') !== false
			|| Functions::stripos($userAgent, 'ia_archiver') !== false;
	}
}
?>