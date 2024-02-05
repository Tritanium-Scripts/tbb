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
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class WhoIsOnline extends PublicModule
{
    use Singleton;

    /**
     * Activation state of WIO module.
     *
     * @var bool State of WIO module
     */
    private bool $enabled;

    /**
     * Timeout to clear listed user from WIO list.
     *
     * @var int Timeout in seconds
     */
    private int $timeout;

    /**
     * Contents of WWO var file.
     *
     * @var array WWO data
     */
    private array $wwoFile;

    /**
     * Sets config values and prepares WWO data.
     */
    public function __construct()
    {
        parent::__construct();
        $this->enabled = Config::getInstance()->getCfgVal('wio') > 0;
        $this->timeout = Config::getInstance()->getCfgVal('wio_timeout')*60;
        if(!$this->enabled)
            return;
        //Check WWO file
        Functions::getFileLock('today');
        $this->wwoFile = file_exists('vars/today.var') ? explode("\n", Functions::file_get_contents('vars/today.var')) : ['', "0\t" . date('dmYHis')];
        $update = false;
        if($this->wwoFile[0] != date('dmY'))
        {
            //Reset WWO statistics for new day
            $this->wwoFile = [date('dmY'), $this->wwoFile[1], 0, '', 0];
            $update = true;
        }
        if(!Auth::getInstance()->isConnected() && !Auth::getInstance()->isLoggedIn())
        {
            if($this->isBot($_SERVER['HTTP_USER_AGENT']))
                $this->wwoFile[4]++;
            else
                $this->wwoFile[2]++;
            $update = true;
        }
        elseif(Auth::getInstance()->isLoggedIn() && !in_array(Auth::getInstance()->getUserID() . '#' . Auth::getInstance()->isGhost(), Functions::explodeByComma($this->wwoFile[3])))
        {
            //Add member with ghost state
            $this->wwoFile[3] .= (!empty($this->wwoFile[3]) ? ',' : '') . Auth::getInstance()->getUserID() . '#' . Auth::getInstance()->isGhost();
            $record = Functions::explodeByTab($this->wwoFile[1]);
            //Check record
            if($record[0] < ($size = count(Functions::explodeByComma($this->wwoFile[3]))))
                $this->wwoFile[1] = $size . "\t" . date('dmYHis');
            $update = true;
        }
        if($update)
            Functions::file_put_contents('vars/today.var', implode("\n", $this->wwoFile));
        Functions::releaseLock('today');
        PlugIns::getInstance()->callHook(PlugIns::HOOK_WHO_IS_ONLINE_INIT);
    }

    /**
     * Deletes a WIO ID from WIO list in case of logins or logouts.
     *
     * @param string $wioID WIO ID to remove from WIO
     */
    public function delete(string $wioID): void
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
    public function publicCall(): void
    {
        if(!$this->enabled)
            Template::getInstance()->printMessage('function_deactivated');
        elseif(!Auth::getInstance()->isLoggedIn() && Config::getInstance()->getCfgVal('wio') == 2)
            Template::getInstance()->printMessage('login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
        NavBar::getInstance()->addElement(Language::getInstance()->getString('who_is_online'));
        $this->setLocation('WhoIsOnline'); //Add WIO location now, in Template module would be too late
        $time = time(); //Same time as starting basis for all entries
        $wioLocations = [];
        foreach(Functions::file('vars/wio.var') as $curWIOEntry)
        {
            $curWIOEntry = Functions::explodeByTab($curWIOEntry);
            $curWIOEntry[2] = Functions::explodeByComma($curWIOEntry[2]); //Get IDs of position, if any
            //Admins may also see ghosts
            $curWIOEntryIsGhost = $curWIOEntry[4] == '1';
            if(!$curWIOEntryIsGhost || Auth::getInstance()->isAdmin())
            {
                $curUser = is_numeric($curWIOEntry[1])
                    ? Functions::getProfileLink($curWIOEntry[1])
                    : Language::getInstance()->getString($this->isBot($curWIOEntry[5]) ? 'bot' : 'guest') . Functions::substr($curWIOEntry[1], 5, 5);
                $curTime = $time-$curWIOEntry[0];
                $curTime = $curTime < 60
                    ? sprintf(Language::getInstance()->getString('x_seconds_ago'), $curTime)
                    : ($curTime < 120
                        ? Language::getInstance()->getString('one_minute_ago')
                        : sprintf(Language::getInstance()->getString('x_minutes_ago'), $curTime/60));
                //Only admins may see user agents
                if(!Auth::getInstance()->isAdmin())
                    $curWIOEntry[5] = '';
                //Switching through subAction
                switch($curWIOEntry[2][0])
                {
                    case 'ForumIndex':
                    $wioLocations[] = [$curUser, sprintf(Language::getInstance()->getString('views_the_forum_index'), INDEXFILE . SID_QMARK), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'ViewForum':
                    $wioLocations[] = Config::getInstance()->getCfgVal('show_private_forums') == 1 || Functions::checkUserAccess($curWIOEntry[2][1], 0)
                        ? [$curUser, sprintf(Language::getInstance()->getString('views_the_forum_x'), INDEXFILE . '?mode=viewforum&amp;forum_id=' . $curWIOEntry[2][1] . SID_AMPER, @next(Functions::getForumData($curWIOEntry[2][1]))), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]]
                        : [$curUser, sprintf(Language::getInstance()->getString('views_a_forum'), INDEXFILE . '?mode=viewforum&amp;forum_id=' . $curWIOEntry[2][1] . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'ViewTopic':
                    $wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0)
                        ? [$curUser, sprintf(Language::getInstance()->getString('views_the_topic_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER, Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]]
                        : [$curUser, sprintf(Language::getInstance()->getString('views_a_topic'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'ViewTodaysPosts':
                    $wioLocations[] = [$curUser, sprintf(Language::getInstance()->getString('views_todays_posts'), INDEXFILE . '?faction=todaysPosts' . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'RSSFeed':
                    $wioLocations[] = [$curUser, sprintf(Language::getInstance()->getString('views_the_rss_feed'), INDEXFILE . '?faction=rssFeed' . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'WhoIsOnline':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('views_the_wio_list'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'MemberList':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('views_the_member_list'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'Message':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('views_a_message'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'Login':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('logs_in'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'RequestPassword':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('requests_a_new_password'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'ViewProfile':
                    $wioLocations[] = [$curUser, sprintf(Language::getInstance()->getString('views_the_profile_from_x'), Functions::getProfileLink($curWIOEntry[2][1])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'vCard':
                    $wioLocations[] = [$curUser, sprintf(Language::getInstance()->getString('downloads_the_vcard_from_x'), Functions::getProfileLink($curWIOEntry[2][1])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'SendMail':
                    $wioLocations[] = [$curUser, sprintf(Language::getInstance()->getString('writes_a_mail_to_x'), Functions::getProfileLink($curWIOEntry[2][1])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'ViewAchievements':
                    $wioLocations[] = [$curUser, sprintf(Language::getInstance()->getString('views_achievements_from_x'), Functions::getProfileLink($curWIOEntry[2][1])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'EditProfile':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('edits_own_profile'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'EditProfileConfirmDelete':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('deletes_own_account'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'Register':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('registers'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'BoardRules':
                    $wioLocations[] = [$curUser, sprintf(Language::getInstance()->getString('reads_board_rules'), INDEXFILE . '?faction=regeln' . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'FAQ':
                    $wioLocations[] = [$curUser, sprintf(Language::getInstance()->getString('views_the_faq'), INDEXFILE . '?faction=faq' . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'GDPR':
                    $wioLocations[] = [$curUser, sprintf(Language::getInstance()->getString('reads_privacy_policy'), INDEXFILE . '?faction=gdpr' . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'Credits':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('views_the_credits'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'PrivateMessageIndex':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('manages_pms'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'PrivateMessageViewPM':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('reads_a_pm'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'PrivateMessageNewPM':
                    case 'PrivateMessageNewPMConfirmSend':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('writes_new_pm'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'PrivateMessageConfirmDelete':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('deletes_a_pm'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'PostNewTopic':
                    $wioLocations[] = Config::getInstance()->getCfgVal('show_private_forums') == 1 || Functions::checkUserAccess($curWIOEntry[2][1], 0)
                        ? [$curUser, sprintf(Language::getInstance()->getString('posts_new_topic_in_x'), INDEXFILE . '?mode=viewforum&amp;forum_id=' . $curWIOEntry[2][1] . SID_AMPER, @next(Functions::getForumData($curWIOEntry[2][1]))), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]]
                        : [$curUser, Language::getInstance()->getString('posts_new_topic'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'PostNewPoll':
                    $wioLocations[] = Config::getInstance()->getCfgVal('show_private_forums') == 1 || Functions::checkUserAccess($curWIOEntry[2][1], 0)
                        ? [$curUser, sprintf(Language::getInstance()->getString('posts_new_poll_in_x'), INDEXFILE . '?mode=viewforum&amp;forum_id=' . $curWIOEntry[2][1] . SID_AMPER, @next(Functions::getForumData($curWIOEntry[2][1]))), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]]
                        : [$curUser, Language::getInstance()->getString('posts_new_poll'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'PostReply':
                    $wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0)
                        ? [$curUser, sprintf(Language::getInstance()->getString('writes_reply_to_topic_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER, Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]]
                        : [$curUser, sprintf(Language::getInstance()->getString('writes_reply_to_a_topic'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'PostViewIP':
                    $wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0)
                        ? [$curUser, sprintf(Language::getInstance()->getString('views_ip_of_post_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER . '&amp;z=' . $curWIOEntry[2][4] . '#post' . $curWIOEntry[2][3], Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]]
                        : [$curUser, sprintf(Language::getInstance()->getString('views_ip_of_a_post'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER . '&amp;z=' . $curWIOEntry[2][4] . '#post' . $curWIOEntry[2][3]), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'PostBlockIP':
                    $wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0)
                        ? [$curUser, sprintf(Language::getInstance()->getString('blocks_ip_of_post_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER . '&amp;z=' . $curWIOEntry[2][4] . '#post' . $curWIOEntry[2][3], Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]]
                        : [$curUser, sprintf(Language::getInstance()->getString('blocks_ip_of_a_post'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER . '&amp;z=' . $curWIOEntry[2][4] . '#post' . $curWIOEntry[2][3]), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'EditPoll':
                    $wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0)
                        ? [$curUser, sprintf(Language::getInstance()->getString('edits_the_poll_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER, Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]]
                        : [$curUser, sprintf(Language::getInstance()->getString('edits_a_poll'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'EditPost':
                    $wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0)
                        ? [$curUser, sprintf(Language::getInstance()->getString('edits_the_post_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER . '&amp;z=' . $curWIOEntry[2][4] . '#post' . $curWIOEntry[2][3], Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]]
                        : [$curUser, sprintf(Language::getInstance()->getString('edits_a_post'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER . '&amp;z=' . $curWIOEntry[2][4] . '#post' . $curWIOEntry[2][3]), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'EditPostConfirmDelete':
                    $wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0)
                        ? [$curUser, sprintf(Language::getInstance()->getString('deletes_the_post_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER . '&amp;z=' . $curWIOEntry[2][4] . '#post' . $curWIOEntry[2][3], Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]]
                        : [$curUser, sprintf(Language::getInstance()->getString('deletes_a_post'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER . '&amp;z=' . $curWIOEntry[2][4] . '#post' . $curWIOEntry[2][3]), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'EditTopicDelete':
                    $wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0)
                        ? [$curUser, sprintf(Language::getInstance()->getString('deletes_the_topic_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER, Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]]
                        : [$curUser, sprintf(Language::getInstance()->getString('deletes_a_topic'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'EditTopicClose':
                    $wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0)
                        ? [$curUser, sprintf(Language::getInstance()->getString('closes_the_topic_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER, Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]]
                        : [$curUser, sprintf(Language::getInstance()->getString('closes_a_topic'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'EditTopicOpen':
                    $wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0)
                        ? [$curUser, sprintf(Language::getInstance()->getString('opens_the_topic_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER, Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]]
                        : [$curUser, sprintf(Language::getInstance()->getString('opens_a_topic'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'EditTopicMove':
                    $wioLocations[] = Functions::checkUserAccess($curWIOEntry[2][1], 0)
                        ? [$curUser, sprintf(Language::getInstance()->getString('moves_the_topic_x'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER, Functions::getTopicName($curWIOEntry[2][1], $curWIOEntry[2][2])), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]]
                        : [$curUser, sprintf(Language::getInstance()->getString('moves_a_topic'), INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curWIOEntry[2][1] . '&amp;thread=' . $curWIOEntry[2][2] . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'Search':
                    $wioLocations[] = [$curUser, sprintf(Language::getInstance()->getString('searches_the_board'), INDEXFILE . '?faction=search' . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'SearchResults':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('views_search_results'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'Newsletter':
                    $wioLocations[] = [$curUser, sprintf(Language::getInstance()->getString('is_in_newsletter_archive'), INDEXFILE . '?faction=newsletter' . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'NewsletterReadLetter':
                    $wioLocations[] = [$curUser, sprintf(Language::getInstance()->getString('reads_a_newsletter'), INDEXFILE . '?faction=newsletter&amp;mode=read&amp;newsletter=' . $curWIOEntry[2][1] . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'Calendar':
                    $wioLocations[] = [$curUser, sprintf(Language::getInstance()->getString('views_the_calendar'), INDEXFILE . '?faction=calendar' . SID_AMPER), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'Upload':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('uploads_a_file'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminIndex':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('is_in_administration'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminForum':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('manages_forums_categories'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminForumIndex':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('manages_forums'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminForumEditForum':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('edits_a_forum'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminForumDeleteForum':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('deletes_a_forum'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminForumNewForum':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('creates_a_new_forum'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminForumSpecialRights':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('manages_special_rights'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminForumNewUserRight':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('creates_new_user_special_right'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminForumNewGroupRight':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('creates_new_group_special_right'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminForumIndexCat':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('manages_categories'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminForumEditCat':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('edits_a_category'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminForumNewCat':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('creates_new_category'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminRankIndex':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('manages_user_ranks'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminRankEditRank':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('edits_an_user_rank'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminRankNewRank':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('creates_new_user_rank'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminConfig':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('edits_board_settings'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminConfigResetConfirm':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('resets_board_settings'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminConfigCountersConfirm':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('recalculates_counters'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminLogfile':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('manages_logfiles'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminLogfileViewLog':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('views_a_logfile'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminTemplate':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('manages_templates'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminNews':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('writes_board_news'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminMailList':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('retrieves_mail_list'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminUser':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('manages_user'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminUserNewUser':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('creates_new_user'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminUserEditUser':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('edits_an_user'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminGroup':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('manages_groups'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminGroupNewGroup':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('creates_new_group'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminGroupEditGroup':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('edits_a_group'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminGroupDeleteGroup':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('deletes_a_group'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminCensor':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('manages_censorships'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminCensorNewWord':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('creates_new_censorship'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminCensorEditWord':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('edits_a_censorship'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminIP':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('manages_ip_blocks'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminIPNewBlock':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('creates_new_ip_block'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminSmiley':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('manages_smilies'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminSmileyNewSmiley':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('creates_new_smiley'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminSmileyEditSmiley':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('edits_a_smiley'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminNewsletter':
                    case 'AdminNewsletterConfirm':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('writes_a_newsletter'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminDeleteOld':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('deletes_old_topics'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminCalendar':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('manages_calendar'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminCalendarNewEvent':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('creates_new_event'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminCalendarEditEvent':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('edits_an_event'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminPlugIns':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('manages_plug_ins'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminForumTopicPrefixes':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('manages_topic_prefixes'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminForumNewTopicPrefix':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('creates_new_topic_prefix'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    case 'AdminForumEditTopicPrefix':
                    $wioLocations[] = [$curUser, Language::getInstance()->getString('edits_a_topic_prefix'), $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;

                    default:
                    $wioLocations[] = [$curUser, '<b>WARNING: Unknown WIO location!</b>', $curWIOEntryIsGhost, $curTime, $curWIOEntry[5]];
                    break;
                }
            }
        }
        Template::getInstance()->printPage('WhoIsOnline', 'wioLocations', $wioLocations);
    }

    /**
     * Returns most active members with date.
     *
     * @return array Members / date couple
     */
    public function getRecord(): array
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
    public function getUserWIO(): array
    {
        $guests = $ghosts = $bots = 0;
        $members = [];
        if($this->enabled)
        {
            Functions::getFileLock('wio');
            foreach($this->refreshVar() as $curWIOEntry)
                is_numeric($curWIOEntry[1])
                    ? ($curWIOEntry[4] != '1' ? $members[] = Functions::getProfileLink($curWIOEntry[1], false, ' class="small"', true) : $ghosts++)
                    : ($this->isBot($curWIOEntry[5]) ? $bots++ : $guests++);
            Functions::releaseLock('wio');
        }
        return [$guests, $ghosts, $members, $bots];
    }

    /**
     * Returns todays active members and amount of guests, ghosts and bots.
     *
     * @return array Guests / ghosts / members / memberProfiles-isGhost-couples / bots quintuple
     */
    public function getUserWWO(): array
    {
        $ghosts = 0;
        $members = [];
        if($this->enabled && !empty($this->wwoFile[3]))
            foreach(Functions::explodeByComma($this->wwoFile[3]) as $curWWOEntry)
            {
                $curWWOEntry = explode('#', $curWWOEntry);
                if(!empty($curWWOEntry[1]))
                {
                    $ghosts++;
                    if(Auth::getInstance()->isAdmin())
                        $members[] = [Functions::getProfileLink($curWWOEntry[0], true), true];
                }
                else
                    $members[] = [Functions::getProfileLink($curWWOEntry[0], true), false];
            }
        return [$this->wwoFile[2], $ghosts, count($members), $members, $this->wwoFile[4]];
    }

    /**
     * Writes WIO location for current user.
     *
     * @param string $id Identifier for location
     */
    public function setLocation(string $id): void
    {
        if(!$this->enabled)
            return;
        $found = false;
        Functions::getFileLock('wio');
        $wioFile = $this->refreshVar();
        foreach($wioFile as &$curWIOEntry)
        {
            if($curWIOEntry[1] == Auth::getInstance()->getWIOID())
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
        if($found)
            Functions::file_put_contents('vars/wio.var', implode("\n", $wioFile));
        else
            Functions::file_put_contents('vars/wio.var', (count($wioFile) > 0 ? "\n" : '') . time() . "\t" . Auth::getInstance()->getWIOID() . "\t" . $id . "\t\t" . Auth::getInstance()->isGhost() . "\t" . htmlspecialchars($_SERVER['HTTP_USER_AGENT']), FILE_APPEND);
        Functions::releaseLock('wio');
    }

    /**
     * Refreshes contents of the WIO data file by removing outdated entries.
     *
     * @param string $deleteWIOID Optional WIO ID to delete nevertheless
     * @return array Already exploded contents of refreshed WIO file.
     */
    private function refreshVar(string $deleteWIOID=''): array
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
            Functions::file_put_contents('vars/wio.var', implode("\n", array_map(['Functions', 'implodeByTab'], $wioFile)));
        return $wioFile;
    }

    /**
     * Returns given user agent being used by a web crawler.
     *
     * @param string $userAgent User agent to check
     * @return bool User agent being used by a search bot
     */
    private function isBot(string $userAgent): bool
    {
        return Functions::stripos($userAgent, 'bot') !== false
            || Functions::stripos($userAgent, 'spider') !== false
            || Functions::stripos($userAgent, 'crawl') !== false
            || Functions::stripos($userAgent, 'slurp') !== false
            || Functions::stripos($userAgent, 'qwant') !== false
            || Functions::stripos($userAgent, 'bubing') !== false
            || Functions::stripos($userAgent, 'ia_archiver') !== false
            || Functions::stripos($userAgent, 'panscient') !== false
            || Functions::stripos($userAgent, 'daum') !== false
            || Functions::stripos($userAgent, 'ubermetrics') !== false
            || Functions::stripos($userAgent, 'knowledge ai') !== false
            || Functions::stripos($userAgent, 'buck') !== false
            || Functions::stripos($userAgent, 'http://') !== false
            || Functions::stripos($userAgent, 'https://') !== false;
    }
}
?>