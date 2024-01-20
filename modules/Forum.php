<?php
/**
 * Displays specific forum, topic or all forums index with additional stats.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2024 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class Forum extends PublicModule
{
    use Singleton, Mode;

    /**
     * ID of queried forum.
     *
     * @var int Forum ID
     */
    private int $forumID;

    /**
     * Translates a mode to its template file.
     *
     * @var array Mode and template counterparts
     */
    private static array $modeTable = array('' => 'ForumIndex',
        'viewforum' => 'ViewForum',
        'viewthread' => 'ViewTopic',
        'todaysPosts' => 'ViewTodaysPosts');

    /**
     * Page of queried topic.
     *
     * @var int|string Page number
     */
    private $page;

    /**
     * ID of queried topic.
     *
     * @var int Topic ID
     */
    private int $topicID;

    /**
     * Provides named keys for user data according to template and file structure of XBB member files.
     *
     * @var array Named keys sorted by layout of XBB files
     */
    private static array $userKeys = array('userNick', 'userID', 'userPassHash', 'userEMail', 'userState', 'userPosts', 'userRegDate', 'userSig', 'userForumAcc', 'userHP', 'userAvatar', 'userUpdateState', 'userName', 'userICQ', 'userMailOpts', 'userGroup', 'userTimestamp', 'userSpecialState', 'userSteamName', 'userSteamGames');

    /**
     * Amount of named keys for user data.
     *
     * @var int Size of user keys
     */
    private int $userKeysSize;

    /**
     * Shorten topic and post page navigation bars to this value.
     *
     * @var int Number of page links to display at the beginning and end
     */
    private int $shortenPageBar;

    /**
     * Detects IDs, page and sets mode.
     *
     * @param string $mode Forum mode to execute
     */
    function __construct(string $mode)
    {
        parent::__construct();
        $this->mode = $mode;
        $this->forumID = intval(Functions::getValueFromGlobals('forum_id')) ?: -1;
        $this->topicID = intval(Functions::getValueFromGlobals('thread')) ?: -1;
        $this->page = isset($_GET['z']) ? ($_GET['z'] != 'last' ? intval($_GET['z']) : 'last') : 1;
        $this->userKeysSize = count(self::$userKeys);
        $this->shortenPageBar = intval(Config::getInstance()->getCfgVal('shorten_page_bars'));
    }

    /**
     * Displays specific or all forums.
     */
    public function publicCall(): void
    {
        //Check IP for specific forum (and topic, too) only (the global check was performed before in Main)
        if($this->forumID != -1 && ($endtime = Functions::checkIPAccess($this->forumID)) !== true)
            Template::getInstance()->printMessage(($endtime == -1 ? 'banned_forever_one_forum' : 'banned_for_x_minutes_one_forum'), ceil(($endtime-time())/60));
        //Process news
        if(count($news = Functions::file('vars/news.var')) != 0)
        {
            $newsConfig = Functions::explodeByTab(array_shift($news));
            foreach($news as &$curNews)
                $curNews = BBCode::getInstance()->parse($curNews);
            Template::getInstance()->assign(array('news' => time() < $newsConfig[1] || $newsConfig[1] == '-1' ? $news : false,
                'newsType' => intval($newsConfig[0])));
        }
        else
            Template::getInstance()->assign('news', false);
        //Perform mode
        switch($this->mode)
        {
//ViewForum
            case 'viewforum':
            //Manage cookies
            setcookie('upbwhere', INDEXFILE . '?mode=viewforum&forum_id=' . $this->forumID); //Redir cookie after login
            setcookie('forum_' . $this->forumID, time(), time()+60*60*24*365, Config::getInstance()->getCfgVal('path_to_forum')); //Cookie to detect last visit
            //Process forum and its topics
            $forum = Functions::getForumData($this->forumID) or Template::getInstance()->printMessage('forum_not_found');
            if(!Functions::checkUserAccess($forum, 0))
                Template::getInstance()->printMessage('forum_' . (Auth::getInstance()->isLoggedIn() ? 'no_access' : 'need_login'));
            //Provide prefixes with their IDs as keys
            $topicPrefixes = array_map(['Functions', 'explodeByTab'], Functions::file('foren/' . $this->forumID . '-prefixes.xbb') ?: []);
            $topicPrefixes = array_combine(array_map('current', $topicPrefixes), array_map(fn($topicPrefix) => ['prefix' => $topicPrefix[1], 'color' => $topicPrefix[2]], $topicPrefixes));
            $topicFile = array_reverse(Functions::file('foren/' . $this->forumID . '-threads.xbb'));
            //Manage sticky topics
            $stickyFile = @Functions::file('foren/' . $this->forumID . '-sticker.xbb', FILE_SKIP_EMPTY_LINES) ?: [];
            if(!empty($stickyFile))
                //Move stickies to top with some 1337 h4x array magic :)
                $topicFile = array_merge(array_reverse(array_intersect($stickyFile, $topicFile)), array_values(array_diff($topicFile, $stickyFile)));
            //Build page navigation bar
            $pages = ceil(($size = count($topicFile)) / Config::getInstance()->getCfgVal('topics_per_page'));
            $pageBar = $topics = [];
            for($i=1; $i<=$pages; $i++)
                $pageBar[] = $i != $this->page ? '<a href="' . INDEXFILE . '?mode=viewforum&amp;forum_id=' . $this->forumID . '&amp;z=' . $i . SID_AMPER . '">' . $i . '</a>' : $i;
            $pageBar = $this->getShortenPageBar($pageBar, true, $pages);
            //Only add bar by having more than one page
            NavBar::getInstance()->addElement($forum[1], '', ($pageBar = count($pageBar) < 2 ? '' : ' ' . sprintf(Language::getInstance()->getString('pages'), implode(' ', $pageBar))));
            //Process topics for current page
            $end = $this->page*Config::getInstance()->getCfgVal('topics_per_page');
            for($i=$end-Config::getInstance()->getCfgVal('topics_per_page'); $i<($end > $size ? $size : $end); $i++)
            {
                $curTopic = Functions::file('foren/' . $this->forumID . '-' . $topicFile[$i] . '.xbb');
                $curLastPost = Functions::explodeByTab(@end($curTopic));
                $curEnd = ceil(($curSize = count($curTopic)-1) / Config::getInstance()->getCfgVal('posts_per_page'));
                #0:open/close[/moved] - 1:title - 2:userID - 3:tSmileyID - 4:notifyNewReplies[/movedForumID] - 5:timestamp[/movedTopicID] - 6:views[/prefixID] - 7:pollID[ - 8:subscribedUserIDs - 9:prefixID]
                $curTopic = Functions::explodeByTab($curTopic[0]);
                //Detect new posts
                $curCookieID = 'topic_' . $this->forumID . '_' . $topicFile[$i];
                switch($curTopic[0])
                {
                    case '1':
                    case 'open': //Downward compatibility
                    $curTopicIcon = !isset($_COOKIE[$curCookieID]) || $_COOKIE[$curCookieID] < $curTopic[5] ? ($curSize <= Config::getInstance()->getCfgVal('topic_is_hot') ? 'ontopic' : 'onstopic') : ($curSize <= Config::getInstance()->getCfgVal('topic_is_hot') ? 'onntopic' : 'onnstopic');
                    $isMoved = false;
                    break;

                    case '2':
                    case 'close': //Downward compatibility
                    $curTopicIcon = !isset($_COOKIE[$curCookieID]) || $_COOKIE[$curCookieID] < $curTopic[5] ? 'cntopic' : 'cnntopic';
                    $isMoved = false;
                    break;

                    case 'm': //Moved topic
                    $curTopicIcon = 'movetopic';
                    $isMoved = true;
                    break;
                }
                //Build page navigation bar for current topic
                $curTopicPageBar = [];
                for($j=1; $j<=$curEnd; $j++)
                    $curTopicPageBar[] = '<a href="' . INDEXFILE . '?mode=viewthread&amp;forum_id=' . $this->forumID . '&amp;thread=' . $topicFile[$i] . '&amp;z=' . $j . SID_AMPER . '">' . $j . '</a>';
                $curTopicPageBar = $this->getShortenPageBar($curTopicPageBar);
                //Only show bar by having more than one page
                $curTopicPageBar = count($curTopicPageBar) < 2 ? '' : ' ' . sprintf(Language::getInstance()->getString('pages'), implode(' ', $curTopicPageBar));
                //Censor title and add to parsed topics
                $topics[] = array('topicIcon' => $curTopicIcon,
                    'tSmileyURL' => Functions::getTSmileyURL($curTopic[3]),
                    'isPoll' => !empty($curTopic[7]),
                    'topicID' => $topicFile[$i],
                    'topicTitle' => wordwrap(Functions::censor($curTopic[1]), 80, '<br />', true),
                    'topicPageBar' => $curTopicPageBar,
                    'topicStarter' => Functions::getProfileLink($curTopic[2], true),
                    'isSticky' => in_array($topicFile[$i], $stickyFile),
                    'isMoved' => $isMoved) +
                    //Some values are not set for moved topics, but others needed
                    ($isMoved ? array(
                    'postCounter' => '-',
                    'views' => '-',
                    'lastPost' => '-',
                    'topicPrefix' => $curTopic[6] ?? [],
                    'movedForumID' => $curTopic[4],
                    'movedTopicID' => $curTopic[5]) : array(
                    //Set needed values for non-moved topic
                    'postCounter' => $curSize-1,
                    'views' => $curTopic[6],
                    'lastPost' => sprintf(Language::getInstance()->getString('last_post_x_from_x'), Functions::formatDate($curLastPost[2]), Functions::getProfileLink($curLastPost[1], true)),
                    'topicPrefix' => $topicPrefixes[$curTopic[9]] ?? []));
            }
            Template::getInstance()->assign(array('pageBar' => $pageBar,
                'topics' => $topics, //Prepared topics
                'forumID' => $this->forumID));
            break;

//ViewTopic
            case 'viewthread':
            //Manage cookies
            setcookie('upbwhere', INDEXFILE . '?mode=viewthread&forum_id=' . $this->forumID . '&thread=' . $this->topicID);
            setcookie('forum_' . $this->forumID, time(), time()+60*60*24*365, Config::getInstance()->getCfgVal('path_to_forum'));
            setcookie('topic_' . $this->forumID . '_' . $this->topicID, time(), time()+60*60*24*365, Config::getInstance()->getCfgVal('path_to_forum'));
            //Process topic and its posts
            $forum = Functions::getForumData($this->forumID) or Template::getInstance()->printMessage('forum_not_found');
            if(!Functions::checkUserAccess($forum, 0))
                Template::getInstance()->printMessage('forum_' . (Auth::getInstance()->isLoggedIn() ? 'no_access' : 'need_login'));
            NavBar::getInstance()->addElement($forum[1], INDEXFILE . '?mode=viewforum&amp;forum_id=' . $this->forumID . SID_AMPER);
            Functions::getFileLock('tview-' . $this->forumID);
            $topicFile = @Functions::file('foren/' . $this->forumID . '-' . $this->topicID . '.xbb') or Template::getInstance()->printMessage('topic_not_found');
            #0:open/close[/moved] - 1:title - 2:userID - 3:tSmileyID - 4:notifyNewReplies[/movedForumID] - 5:timestamp[/movedTopicID] - 6:views[/prefixID] - 7:pollID[ - 8:subscribedUserIDs - 9:prefixID]
            $topic = Functions::explodeByTab(array_shift($topicFile));
            if($topic[0] == 'm')
                Template::getInstance()->printMessage('topic_has_moved', INDEXFILE . '?mode=viewthread&amp;forum_id=' . $topic[4] . '&amp;thread=' . $topic[5] . SID_AMPER, Functions::getMsgBackLinks($this->forumID));
            //Manage topic views
            if(!isset($_SESSION['session.tview.' . $this->forumID . '.' . $this->topicID]))
            {
                $topic[6]++;
                //Not using array_unshift to avoid changes in $topicFile
                Functions::file_put_contents('foren/' . $this->forumID . '-' . $this->topicID . '.xbb', implode("\n", array_merge(array(Functions::implodeByTab($topic)), $topicFile)) . "\n");
                $_SESSION['session.tview.' . $this->forumID . '.' . $this->topicID] = true;
            }
            Functions::releaseLock('tview-' . $this->forumID);
            $topic[1] = Functions::censor($topic[1]);
            //Build page navigation bar
            $pages = ceil(($size = count($topicFile)) / Config::getInstance()->getCfgVal('posts_per_page'));
            if($this->page == 'last')
                $this->page = $pages;
            $pageBar = $posts = $parsedSignatures = [];
            for($i=1; $i<=$pages; $i++)
                $pageBar[] = $i != $this->page ? '<a href="' . INDEXFILE . '?mode=viewthread&amp;forum_id=' . $this->forumID . '&amp;thread=' . $this->topicID . '&amp;z=' . $i . SID_AMPER . '">' . $i . '</a>' : $i;
            $pageBar = $this->getShortenPageBar($pageBar, true, $pages);
            //Only add bar by having more than one page
            NavBar::getInstance()->addElement($topic[1], '', ($pageBar = count($pageBar) < 2 ? '' : ' ' . sprintf(Language::getInstance()->getString('pages'), implode(' ', $pageBar))));
            //Process possible poll
            $isPoll = false;
            $isMod = Functions::checkModOfForum($forum);
            if(!empty($topic[7]) && ($pollFile = @Functions::file('polls/' . $topic[7] . '-1.xbb')) != false)
            {
                $isPoll = true;
                #0:pollState - 1:creatorID - 2:proprietaryDate - 3:title/question - 4:totalVotes - 5:forumID,topicID
                $poll = Functions::explodeByTab(array_shift($pollFile));
                $pollVoters = Functions::explodeByComma(Functions::file_get_contents('polls/' . $topic[7] . '-2.xbb'));
                //Process each vote option
                $pollOptions = [];
                foreach(array_map(['Functions', 'explodeByTab'], $pollFile) as $curPoll)
                    $pollOptions[] = array('optionID' => $curPoll[0],
                        'pollOption' => $curPoll[1],
                        'percent' => ($curPercent = $curPoll[2] == '0' ? 0 : round(($curPoll[2]/$poll[4])*100, 1)),
                        'voteText' => sprintf(Language::getInstance()->getString('x_percent_x_votes'), $curPercent, $curPoll[2]));
                Template::getInstance()->assign(array('pollID' => $topic[7],
                    'pollTitle' => $poll[3],
                    'isPollClosed' => $poll[0] > '2',
                    'hasVoted' => isset($_SESSION['session_poll_' . $topic[7]]) || isset($_COOKIE['cookie_poll_' . $topic[7]]) || (Auth::getInstance()->isLoggedIn() && in_array(Auth::getInstance()->getUserID(), $pollVoters)),
                    'needsLogin' => !(Auth::getInstance()->isLoggedIn() || $poll[0] == '1'),
                    'canEdit' => Auth::getInstance()->isAdmin() || $isMod || (Auth::getInstance()->isLoggedIn() && Auth::getInstance()->getUserID() == $poll[1]),
                    'pollOptions' => $pollOptions,
                    'totalVotes' => $poll[4]));
            }
            //Process user and posts
            $end = $this->page*Config::getInstance()->getCfgVal('posts_per_page');
            for($i=$end-Config::getInstance()->getCfgVal('posts_per_page'); $i<($end > $size ? $size : $end); $i++)
            {
                #0:postID - 1:posterID - 2:proprietaryDate - 3:post - 4:ip - 5:isSignature - 6:tSmileyURL - 7:isSmiliesOn - 8:isBBCode - 9:isHTML[ - 10:lastEditByID]
                $curPost = Functions::explodeByTab($topicFile[$i]);
                //Prepare user data of current post
                if($curPost[1][0] == '0')
                    //Guest values
                    $curPoster = $this->getGuestTemplate(Functions::substr($curPost[1], 1));
                elseif(($curPoster = @Functions::getUserData($curPost[1])) === false)
                    //Deleted user values
                    $curPoster = $this->getKilledTemplate($curPost[1]);
                else
                {
                    //User values
                    $curPoster = array_combine(self::$userKeys, array_slice($curPoster, 0, $this->userKeysSize)) + array('sendPM' => true);
                    //Check user mail settings
                    if($curPoster['userMailOpts'][0] != '1' && $curPoster['userMailOpts'][1] != '1')
                        $curPoster['userEMail'] = false;
                    elseif(!($curPoster['userMailOpts'][0] != '1' && $curPoster['userMailOpts'][1] == '1'))
                        $curPoster['userEMail'] = true;
                    //Nick + Date + HP
                    $curPoster['userNick'] = Functions::getProfileLink($curPoster['userID'], true);
                    $curPoster['userRegDate'] = Functions::formatDate($curPoster['userRegDate'] . (Functions::strlen($curPoster['userRegDate']) == 6 ? '01000000' : ''), Language::getInstance()->getString('REGDATEFORMAT'));
                    $curPoster['userHP'] = Functions::addHTTP($curPoster['userHP']);
                    //Group stuff
                    if(!empty($curPoster['userGroup']))
                    {
                        $curGroup = Functions::getGroupData($curPoster['userGroup']);
                        $curPoster['userGroup'] = $curGroup[1];
                        //Use the group's avatar if user has none
                        if(empty($curPoster['userAvatar']))
                            $curPoster['userAvatar'] = $curGroup[2];
                    }
                    //Prepare avatar
                    if(!empty($curPoster['userAvatar']))
                    {
                        $curPoster['userAvatar'] = Functions::addHTTP($curPoster['userAvatar']);
                        list($curWidth, $curHeight) = array(Config::getInstance()->getCfgVal('avatar_width'), Config::getInstance()->getCfgVal('avatar_height'));
                        if(Config::getInstance()->getCfgVal('use_getimagesize') == 1 && ($avatar = @getimagesize($curPoster['userAvatar'])) != false)
                        {
                            if($curWidth > $avatar[0])
                                $curWidth = $avatar[0];
                            if($curHeight > $avatar[1])
                                $curHeight = $avatar[1];
                        }
                        $curPoster['userAvatar'] = '<img src="' . $curPoster['userAvatar'] . '" alt="" style="height:' . $curHeight . 'px; width:' . $curWidth . 'px;" />';
                    }
                    //Rank images
                    $curPoster['userRank'] = Functions::getRankImage($curPoster['userState'], $curPoster['userPosts']);
                    //Detect rank
                    $curPoster['userState'] = Functions::getStateName($curPoster['userState'], $curPoster['userPosts']);
                    //Signature incl. cache check =)
                    $curPoster['userSig'] = !empty($curPoster['userSig']) && ($curPost[5] == '1' || $curPost[5] == 'yes') ? (isset($parsedSignatures[$curPost[1]]) ? $parsedSignatures[$curPost[1]] : ($parsedSignatures[$curPost[1]] = BBCode::getInstance()->parse(Functions::censor($curPoster['userSig']), false, true, true, $topicFile))) : '';
                }
                unset($curPoster['userMailOpts'], $curPoster['userPassHash'], $curPoster['userForumAcc']);
                //User values done, proceed with post
                $curPost[3] = BBCode::getInstance()->parse($curPost[3], $curPost[9] == '1' && $forum[7][1] == '1', $curPost[7] == '1' || $curPost[7] == 'yes', $forum[7][0] == '1' && ($curPost[8] == '1' || $curPost[8] == 'yes'), $topicFile);
                //Add prepared user data and post data
                $posts[] = $curPoster + array('postID' => $curPost[0],
                    'tSmileyURL' => Functions::getTSmileyURL($curPost[6]),
                    'date' => Functions::formatDate($curPost[2]),
                    'postIPText' => !empty($curPost[4]) ? sprintf(Language::getInstance()->getString('ip_saved'), INDEXFILE . '?faction=viewip&amp;forum_id=' . $this->forumID . '&amp;topic_id=' . $this->topicID . '&amp;post_id=' . $curPost[0] . SID_AMPER) : Language::getInstance()->getString('ip_not_saved'),
                    'canModify' => Auth::getInstance()->isAdmin() || $isMod || (Auth::getInstance()->isLoggedIn() && Auth::getInstance()->getUserID() == $curPost[1] && (Functions::checkUserAccess($forum, 4) || Functions::getTimestamp(gmdate('YmdHis')) < Functions::getTimestamp($curPost[2])+intval(Config::getInstance()->getCfgVal('edit_time')))),
                    'post' => Functions::censor($curPost[3]),
                    'lastEditBy' => isset($curPost[10]) && is_numeric($curPost[10]) ? Functions::getProfileLink($curPost[10], true) : '');
            }
            Template::getInstance()->assign(array('page' => $this->page,
                'pageBar' => $pageBar,
                'topicTitle' => $topic[1],
                'isPoll' => $isPoll,
                'forumID' => $this->forumID,
                'topicID' => $this->topicID,
                'canModify' => ($canModify = Auth::getInstance()->isAdmin() || $isMod),
                'isOpen' => $topic[0] == '1' || $topic[0] == 'open',
                'isSticky' => $canModify && ($stickyFile = @Functions::file('foren/' . $this->forumID . '-sticker.xbb', FILE_SKIP_EMPTY_LINES)) != false && in_array($this->topicID, $stickyFile),
                'isSubscribed' => Auth::getInstance()->isLoggedIn() && ($topic[4] == '1' && Auth::getInstance()->getUserID() == $topic[2] || in_array(Auth::getInstance()->getUserID(), Functions::explodeByComma($topic[8]))),
                'posts' => $posts)); //Prepared posts with users
            break;

//ViewTodaysPosts
            case 'todaysPosts':
            setcookie('upbwhere', INDEXFILE . '?faction=todaysPosts');
            NavBar::getInstance()->addElement(Language::getInstance()->getString('todays_posts'), INDEXFILE . '?faction=todaysPosts');
            $todaysPosts = [];
            if(($todaysPostsFile = Functions::file_get_contents('vars/todayposts.var')) != '' && current($todaysPostsFile = Functions::explodeByTab($todaysPostsFile)) == gmdate('Yd'))
                foreach(array_map(array('Functions', 'explodeByComma'), explode('|', $todaysPostsFile[1])) as $curTodaysPost)
                    if(Functions::checkUserAccess($curForumData = Functions::getForumData($curTodaysPost[0]), 0))
                        #0:forumID - 1:topicID - 2:userID - 3:date - 4:tSmileyID[ - 5:postID]
                        $todaysPosts[] = array('forumID' => $curTodaysPost[0],
                            'forumTitle' => $curForumData[1],
                            'topic' => !Functions::file_exists('foren/' . $curTodaysPost[0] . '-' . $curTodaysPost[1] . '.xbb') ? Language::getInstance()->getString('deleted_moved') : '<a href="' . INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curTodaysPost[0] . '&amp;thread=' . $curTodaysPost[1] . '&amp;z=last#post' . @$curTodaysPost[5] . SID_AMPER . '">' . (Functions::censor(Functions::getTopicName($curTodaysPost[0], $curTodaysPost[1]))) . '</a>',
                            'author' => Functions::getProfileLink($curTodaysPost[2], true),
                            'date' => Functions::formatDate($curTodaysPost[3]),
                            'tSmiley' => Functions::getTSmileyURL($curTodaysPost[4]));
            Template::getInstance()->assign('todaysPosts', array_reverse($todaysPosts));
            break;

            case 'rssFeed':
            if(Config::getInstance()->getCfgVal('show_lposts') < 1)
                Template::getInstance()->printMessage('function_deactivated');
            if(($newestPosts = Functions::file_get_contents('vars/lposts.var')) != '')
            {
                WhoIsOnline::getInstance()->setLocation('RSSFeed');
                $newestPosts = Functions::explodeByTab($newestPosts);
                //Retrieve proper data
                foreach($newestPosts as &$curNewestPost)
                {
                    #0:forumID - 1:topicID - 2:userID - 3:proprietaryDate[ - 4:tSmileyID - 5:postID]
                    $curNewestPost = Functions::explodeByComma($curNewestPost . ',1'); //Make sure index 4 is available (index 5 not eligible)
                    $curNewestPost[2] = Functions::isGuestID($curNewestPost[2]) ? Functions::substr($curNewestPost[2], 1) : (Functions::file_exists('members/' . $curNewestPost[2] . '.xbb') ? current(Functions::file('members/' . $curNewestPost[2] . '.xbb')) : Language::getInstance()->getString('deleted'));
                    $curNewestPost[5] = date('r', Functions::getTimestamp($curNewestPost[3] . '01000000')-date('Z'));
                    $curNewestPost[4] = Functions::getTSmileyURL($curNewestPost[4]);
                }
                unset($curNewestPost); //Delete remaining reference to avoid conflicts
                //Get pubDate from regdate of first user
                $i = 1;
                $size = intval(Functions::file_get_contents('vars/last_user_id.var')) ?: 1;
                do
                    $firstUser = Functions::getUserData($i++);
                while($firstUser == false && $i <= $size);
                //RSS header
                header('Content-Type: application/rss+xml');
                echo('<?xml version="1.0" encoding="' . Language::getInstance()->getString('html_encoding') . '" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/">
 <channel>
  <title>' . sprintf(Language::getInstance()->getString('x_rss_feed'), Config::getInstance()->getCfgVal('forum_name')) . '</title>
  <link>' . Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE . '</link>
  <description>' . sprintf(Language::getInstance()->getString('newest_posts_from_x'), Config::getInstance()->getCfgVal('forum_name')) . '</description>
  <language>' . Language::getInstance()->getLangCode() . '</language>
  <lastBuildDate>' . current(array_slice($newestPosts[0], 5, 1)) . '</lastBuildDate>
  <pubDate>' . date('r', $firstUser != false ? Functions::getTimestamp($firstUser[6] . '01000000')-date('Z') : time()) . '</pubDate>
  <docs>https://www.rssboard.org/rss-specification</docs>
  <generator>Tritanium Bulletin Board ' . VERSION_PUBLIC . '</generator>
  <atom:link href="' . Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?faction=rssFeed" rel="self" type="application/rss+xml" />
');
                //RSS body with items
                foreach($newestPosts as $curNewestPost)
                {
                    //Get post data
                    if(($curTopic = @Functions::file('foren/' . $curNewestPost[0] . '-' . $curNewestPost[1] . '.xbb')) !== false)
                    {
                        $curNewestPostDeleted = true;
                        foreach(array_slice($curTopic, 1) as $curKey => $curPost)
                        {
                            $curPost = Functions::explodeByTab($curPost);
                            if($curPost[2] == $curNewestPost[3])
                            {
                                //Topic and post found
                                $curTopic = array('title' => Functions::censor(@next(Functions::explodeByTab($curTopic[0]))),
                                    'post' => htmlspecialchars(BBCode::getInstance()->parse($curPost[3])),
                                    'count' => count($curTopic)-2,
                                    'page' => ceil(($curKey+1) / Config::getInstance()->getCfgVal('posts_per_page')),
                                    'postID' => $curPost[0]);
                                $curNewestPostDeleted = false;
                                break;
                            }
                        }
                        if($curNewestPostDeleted)
                            //Topic found, but post was deleted
                            $curTopic = array('title' => Functions::censor(@next(Functions::explodeByTab($curTopic[0]))),
                                'post' => Language::getInstance()->getString('deleted'),
                                'count' => count($curTopic)-2,
                                'page' => 1,
                                'postID' => 1);
                    }
                    else
                        //Topic not found, post therefore also deleted
                        $curTopic = array('title' => Language::getInstance()->getString('deleted'),
                            'post' => Language::getInstance()->getString('deleted'),
                            'count' => 0,
                            'page' => 1,
                            'postID' => 1);
                    echo('  <item>
   <title>' . $curTopic['title'] . '</title>
   <link>' . Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curNewestPost[0] . '&amp;thread=' . $curNewestPost[1] . '&amp;z=' . $curTopic['page'] . '#post' . $curTopic['postID'] . '</link>
   <guid isPermaLink="true">' . Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curNewestPost[0] . '&amp;thread=' . $curNewestPost[1] . '&amp;z=' . $curTopic['page'] . '#post' . $curTopic['postID'] . '</guid>
   <pubDate>' . $curNewestPost[5] . '</pubDate>
   <dc:creator>' . $curNewestPost[2] . '</dc:creator>
   <category>' . @next(Functions::getForumData($curNewestPost[0])) . '</category>
   <description>&lt;img src=&quot;' . $curNewestPost[4] . '&quot; alt=&quot;&quot; style=&quot;float:right;&quot;&gt;' . $curTopic['post'] .  '</description>
   <comments>' . Config::getInstance()->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?faction=reply&amp;forum_id=' . $curNewestPost[0] . '&amp;thread_id=' . $curNewestPost[1] . '</comments>
   <slash:comments>' . $curTopic['count'] . '</slash:comments>
  </item>
');
                }
                //RSS footer
                exit(' </channel>
</rss>');
            }
            else
                Template::getInstance()->printMessage('no_newest_posts');
            break;

            case 'markAll':
            //Prepare cookie data to set for each forum
            $cookieData = array(time(), time()+60*60*24*365, Config::getInstance()->getCfgVal('path_to_forum'));
            foreach(array_map(function($curForum)
            {
                return current(Functions::explodeByTab($curForum));
            }, Functions::file('vars/foren.var')) as $curForumID)
                setcookie('forum_' . $curForumID, $cookieData[0], $cookieData[1], $cookieData[2]);
            //Simple, wasn't it?
            header('Location: ' . INDEXFILE . SID_QMARK);
            Template::getInstance()->printMessage('forums_marked_as_read');
            break;

//ForumIndex
            default:
            //Manage cookie
            setcookie('upbwhere', INDEXFILE);
            //Process categories and forums
            $topicCounter = $postCounter = 0;
            $cats = $forums = $newestPosts = $processedCats = [];
            //Prepare categories
            foreach(Functions::file('vars/kg.var') as $curCat)
            {
                #0:id - 1:name
                $cats[] = Functions::explodeByTab($curCat);
                $processedCats[current(end($cats))] = false;
            }
            //Prepare forums
            $showPrivateForums = Config::getInstance()->getCfgVal('show_private_forums') == 1;
            foreach(array_map(['Functions', 'explodeByTab'], Functions::file('vars/foren.var')) as $curForum)
            {
                #0:id - 1:name - 2:descr - 3:topics - 4:posts - 5:catID - 6:lastPostTstamp - 7:options - 8:status? - 9:lastPostData - 10:permissions - 11:modIDs
                #7:0:bbCode - 7:1:html - 7:2:notifyMods
                #9:0:topicID - 9:1:userID - 9:2:proprietaryDate - #9:3:tSmileyID
                #10:0:memberAccess - 10:1:memberNewTopic - 10:2:memberPostReply - 10:3:memberPostPolls - 10:4:memberEditOwnPosts - 10:5:memberEditPolls - 10:6:guestAccess - 10:7:guestNewTopic - 10:8:guestPostReply - 10:9:guestPostPolls
                //Check permission
                $showCurForum = Functions::checkUserAccess($curForum, 0);
                if($showPrivateForums || $showCurForum)
                {
                    $curLastPostData = Functions::explodeByComma($curForum[9]);
                    //Check and prepare last post with link or related message
                    if(empty($curLastPostData[0]))
                        $curLastPost = Language::getInstance()->getString('no_last_post');
                    elseif(!$showCurForum) //At the latest checkUserAccess is needed here
                        $curLastPost = Functions::formatDate($curLastPostData[2]);
                    elseif(!Functions::file_exists($curTopicFile = 'foren/' . $curForum[0] . '-' . $curLastPostData[0] . '.xbb'))
                        $curLastPost = Language::getInstance()->getString('deleted_moved');
                    else
                    {
                        //Query template for formatting current last posting
                        $curLastPost = Template::getInstance()->fetch('LastPost', array('tSmileyURL' => Functions::getTSmileyURL($curLastPostData[3]),
                            'forumID' => $curForum[0],
                            'topicID' => $curLastPostData[0],
                            'topicTitle' => Functions::censor(@next(Functions::explodeByTab(current(Functions::file($curTopicFile))))),
                            //Prepare user of current last posting
                            'user' => Functions::getProfileLink($curLastPostData[1], true),
                            'date' => Functions::formatDate($curLastPostData[2])));
                    }
                    //Compile (into) array with all the data for template
                    $forums[] = array('forumID' => $curForum[0],
                        'forumTitle' => $curForum[1],
                        'forumDescr' => $curForum[2],
                        'forumTopics' => $curForum[3],
                        'forumPosts' => $curForum[4],
                        'catID' => $curForum[5],
                        //Cookie check to detect new posts in current forum since last visit
                        'isNewPost' => !isset($_COOKIE['forum_' . $curForum[0]]) || $_COOKIE['forum_' . $curForum[0]] < $curForum[6],
                        'lastPost' => $curLastPost,
                        'mods' => Functions::getProfileLink($curForum[11]));
                    $topicCounter += $curForum[3];
                    $postCounter += $curForum[4];
                    //Update processed cats LUT
                    if(array_key_exists($curForum[5], $processedCats))
                        $processedCats[$curForum[5]] = true;
                }
            }
            //Filter out cats having no forums to display
            foreach($cats as $curKey => $curCat)
                if(!$processedCats[$curCat[0]])
                    unset($cats[$curKey]);
            //Process newest posts
            if(Config::getInstance()->getCfgVal('show_lposts') >= 1 && ($lastPosts = Functions::file_get_contents('vars/lposts.var')) != '')
            {
                foreach(Functions::explodeByTab($lastPosts) as $curNewestPost)
                {
                    #0:forumID - 1:topicID - 2:userID - 3:proprietaryDate[ - 4:tSmileyID - 5:postID]
                    $curNewestPost = Functions::explodeByComma($curNewestPost . ',1,'); //Make sure index 4 and 5 are available
                    $newestPosts[] = sprintf(Language::getInstance()->getString('x_by_x_on_x'),
                        //Topic check + link + title preparation
                        !Functions::file_exists('foren/' . $curNewestPost[0] . '-' . $curNewestPost[1] . '.xbb') ? Language::getInstance()->getString('deleted') : '<img src="' . Functions::getTSmileyURL($curNewestPost[4]) . '" alt="" /> <a href="' . INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curNewestPost[0] . '&amp;thread=' . $curNewestPost[1] . '&amp;z=last' . SID_AMPER . '#post' . $curNewestPost[5] . '">' . (Functions::shorten(Functions::censor(Functions::getTopicName($curNewestPost[0], $curNewestPost[1])), 53)) . '</a>',
                        Functions::getProfileLink($curNewestPost[2], true),
                        Functions::formatDate($curNewestPost[3]));
                }
            }
            Template::getInstance()->assign(array('cats' => $cats,
                'forums' => $forums,
                'topicCounter' => $topicCounter,
                'postCounter' => $postCounter,
                'newestPosts' => $newestPosts) +
                //Add board statistics
                (Config::getInstance()->getCfgVal('show_board_stats') == 1 ? array(
                'newestMember' => Functions::getProfileLink(Functions::file_get_contents('vars/last_user_id.var'), true),
                'memberCounter' => Functions::file_get_contents('vars/member_counter.var')) : []));
            break;
        }
        //Always append IDs to WIO location. WIO will not parse them in inapplicable mode.
        Template::getInstance()->printPage(Functions::handleMode($this->mode, self::$modeTable, __CLASS__), null, null, ',' . $this->forumID . ',' . $this->topicID);
    }

    /**
     * Returns user data with default values for a guest.
     *
     * @param string $nick Guest name
     * @return array Guest data with named keys ready-for-use in template
     */
    private function getGuestTemplate(string $nick): array
    {
        return array_combine(self::$userKeys, array_merge(array($nick, 0, '', false, Language::getInstance()->getString('guest')), array_fill(0, $this->userKeysSize-5, ''))) + array('userRank' => '', 'sendPM' => false);
    }

    /**
     * Returns user data with default values for a deleted user.
     *
     * @param int $userID Former user ID
     * @return array Deleted user data with named keys ready-for-use in template
     */
    private function getKilledTemplate(int $userID): array
    {
        return array_combine(self::$userKeys, array_merge(array(Config::getInstance()->getCfgVal('var_killed'), $userID, '', false, Language::getInstance()->getString('deleted')), array_fill(0, $this->userKeysSize-5, ''))) + array('userRank' => '', 'sendPM' => false);
    }

    /**
     * Returns shorten page bar of given one if feature is enabled and page bar has sufficient entries.
     *
     * @param array $pageBar The page navigation bar to shorten
     * @param bool $dynamic Shorten bar from current page viewpoint or just cut the middle out
     * @param int $pages Number of total pages, needed for dynamic mode
     * @return array Shorten page bar (if needed)
     */
    private function getShortenPageBar(array $pageBar, bool $dynamic=false, ?int $pages=null): array
    {
        //Shorten page bar if needed
        if($this->shortenPageBar > 0 && count($pageBar) > $this->shortenPageBar*2)
        {
            if($dynamic)
            {
                $pageKey = array_search($this->page, $pageBar);
                $pageBar = array_merge(
                    //Left part from current page
                    $this->page > 1 ? array_merge(array(Functions::str_replace('>1<', '>&laquo;<', $pageBar[0])), $pageKey < $this->shortenPageBar ? array_slice($pageBar, 0, $pageKey) : array_slice($pageBar, $pageKey-$this->shortenPageBar, $this->shortenPageBar)) : [],
                    //The current page
                    array($this->page),
                    //Right part from current page
                    $this->page < $pages ? array_merge(array_slice($pageBar, $pageKey+1, $this->shortenPageBar), array(Functions::str_replace('>' . $pages . '<', '>&raquo;<', end($pageBar)))) : []);
            }
            else
                array_splice($pageBar, $this->shortenPageBar, -$this->shortenPageBar, array(Language::getInstance()->getString('dots')));
        }
        return $pageBar;
    }
}
?>