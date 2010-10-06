<?php
/**
 * Displays specific forum, topic or all forums index with additional stats.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class Forum implements Module
{
	/**
	 * ID of queried forum.
	 *
	 * @var int Forum ID
	 */
	private $forumID;

	/**
	 * View mode to display all forums, single one with all topics or single topic.
	 *
	 * @var string View mode
	 */
	private $mode;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('' => 'ForumIndex',
		'viewforum' => 'ViewForum',
		'viewthread' => 'ViewTopic',
		'todaysPosts' => 'ViewTodaysPosts');

	/**
	 * Page of queried topic.
	 *
	 * @var int Page number
	 */
	private $page;

	/**
	 * ID of queried topic.
	 *
	 * @var int Topic ID
	 */
	private $topicID;

	/**
	 * Provides named keys for user data according to template and file structure of XBB member files.
	 *
	 * @var array Named keys sorted by layout of XBB files
	 */
	private static $userKeys = array('userNick', 'userID', 'userPassHash', 'userEMail', 'userState', 'userPosts', 'userRegDate', 'userSig', 'userForumAcc', 'userHP', 'userAvatar', 'userUpdateState', 'userName', 'userICQ', 'userMailOpts', 'userGroup', 'userTimestamp', 'userSpecialState', 'userSteamName');

	/**
	 * Amount of named keys for user data.
	 *
	 * @var int Size of user keys
	 */
	private $userKeysSize;

	/**
	 * Detects IDs, page and sets mode.
	 *
	 * @return Forum New instance of this class
	 */
	function __construct($mode)
	{
		$this->mode = $mode;
		$this->forumID = intval(Functions::getValueFromGlobals('forum_id')) or $this->forumID = -1;
		$this->topicID = intval(Functions::getValueFromGlobals('thread')) or $this->topicID = -1;
		$this->page = isset($_GET['z']) ? ($_GET['z'] != 'last' ? intval($_GET['z']) : 'last') : 1;
		$this->userKeysSize = count(self::$userKeys);
	}

	/**
	 * Displays specific or all forums.
	 */
	public function execute()
	{
		//Check IP for specific forum (and topic, too) only (the global check was performed before in Main)
		if($this->forumID != -1 && ($endtime = Functions::checkIPAccess($this->forumID)) !== true)
			Main::getModule('Template')->printMessage(($endtime == -1 ? 'banned_forever_one_forum' : 'banned_for_x_minutes_one_forum'), ceil(($endtime-time())/60));
		//Process news
		if(count($news = Functions::file('vars/news.var')) != 0)
		{
			$newsConfig = Functions::explodeByTab(array_shift($news));
			foreach($news as &$curNews)
				$curNews = Main::getModule('BBCode')->parse($curNews);
			Main::getModule('Template')->assign(array('news' => time() < $newsConfig[1] || $newsConfig[1] == '-1' ? $news : false,
				'newsType' => intval($newsConfig[0])));
		}
		else
			Main::getModule('Template')->assign('news', false);
		//Perform mode
		switch($this->mode)
		{
//ViewForum
			case 'viewforum':
			//Manage cookies
			setcookie('upbwhere', INDEXFILE . '?mode=viewforum&forum_id=' . $this->forumID); //Redir cookie after login
			setcookie('forum.' . $this->forumID, time(), time()+60*60*24*365, Main::getModule('Config')->getCfgVal('path_to_forum')); //Cookie to detect last visit
			//Process forum and its topics
			$forum = Functions::getForumData($this->forumID) or Main::getModule('Template')->printMessage('forum_not_found');
			if(!Functions::checkUserAccess($forum, 0))
				Main::getModule('Template')->printMessage('forum_' . (Main::getModule('Auth')->isLoggedIn() ? 'no_access' : 'need_login'));
			$topicFile = array_reverse(Functions::file('foren/' . $this->forumID . '-threads.xbb'));
			//Manage sticky topics
			$stickyFile = @Functions::file('foren/' . $this->forumID . '-sticker.xbb', FILE_SKIP_EMPTY_LINES) or $stickyFile = array();
			if(!empty($stickyFile))
				//Move stickies to top with some 1337 h4x array magic :)
				$topicFile = array_merge(array_reverse(array_intersect($stickyFile, $topicFile)), array_values(array_diff($topicFile, $stickyFile)));
			//Build page navigation bar
			$pages = ceil(($size = count($topicFile)) / Main::getModule('Config')->getCfgVal('topics_per_page'));
			$pageBar = $topics = array();
			for($i=1; $i<=$pages; $i++)
				$pageBar[] = $i != $this->page ? '<a href="' . INDEXFILE . '?mode=viewforum&amp;forum_id=' . $this->forumID . '&amp;z=' . $i . SID_AMPER . '">' . $i . '</a>' : $i;
			//Only add bar by having more than one page
			Main::getModule('NavBar')->addElement($forum[1], '', ($pageBar = count($pageBar) < 2 ? '' : ' ' . sprintf(Main::getModule('Language')->getString('pages'), implode(' ', $pageBar))));
			//Process topics for current page
			$end = $this->page*Main::getModule('Config')->getCfgVal('topics_per_page');
			for($i=$end-Main::getModule('Config')->getCfgVal('topics_per_page'); $i<($end > $size ? $size : $end); $i++)
			{
				$curLastPost = Functions::explodeByTab(@end($curTopic = Functions::file('foren/' . $this->forumID . '-' . $topicFile[$i] . '.xbb')));
				$curEnd = ceil(($curSize = count($curTopic)-1) / Main::getModule('Config')->getCfgVal('posts_per_page'));
				#0:open/close[/moved] - 1:title - 2:userID - 3:tSmileyID - 4:[/movedForumID] - 5:timestamp[/movedTopicID] - 6:views - 7:pollID - ...
				$curTopic = Functions::explodeByTab($curTopic[0]);
				//Detect new posts
				$curCookieID = 'topic.' . $this->forumID . '.' . $topicFile[$i];
				switch($curTopic[0])
				{
					case '1':
					case 'open': //Downward compatibility
					$curTopicIcon = !isset($_COOKIE[$curCookieID]) || $_COOKIE[$curCookieID] < $curTopic[5] ? ($curSize <= Main::getModule('Config')->getCfgVal('topic_is_hot') ? 'ontopic' : 'onstopic') : ($curSize <= Main::getModule('Config')->getCfgVal('topic_is_hot') ? 'onntopic' : 'onnstopic');
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
				$curTopicPageBar = array();
				for($j=1; $j<=$curEnd; $j++)
					$curTopicPageBar[] = '<a href="' . INDEXFILE . '?mode=viewthread&amp;forum_id=' . $this->forumID . '&amp;thread=' . $topicFile[$i] . '&amp;z=' . $j . SID_AMPER . '">' . $j . '</a>';
				//Only show bar by having more than one page
				$curTopicPageBar = count($curTopicPageBar) < 2 ? '' : ' ' . sprintf(Main::getModule('Language')->getString('pages'), implode(' ', $curTopicPageBar));
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
					'movedForumID' => $curTopic[4],
					'movedTopicID' => $curTopic[5]) : array(
					//Set needed values for non-moved topic
					'postCounter' => $curSize-1,
					'views' => $curTopic[6],
					'lastPost' => sprintf(Main::getModule('Language')->getString('last_post_x_from_x'), Functions::formatDate($curLastPost[2]), Functions::getProfileLink($curLastPost[1], true))));
			}
			Main::getModule('Template')->assign(array('pageBar' => $pageBar,
				'topics' => $topics, //Prepared topics
				'forumID' => $this->forumID));
			break;

//ViewTopic
			case 'viewthread':
			//Manage cookies
			setcookie('upbwhere', INDEXFILE . '?mode=viewthread&forum_id=' . $this->forumID . '&thread=' . $this->topicID);
			setcookie('forum.' . $this->forumID . '.' . $this->topicID, time(), time()+60*60*24*365, Main::getModule('Config')->getCfgVal('path_to_forum'));
			//Process topic and its posts
			$forum = Functions::getForumData($this->forumID) or Main::getModule('Template')->printMessage('forum_not_found');
			if(!Functions::checkUserAccess($forum, 0))
				Main::getModule('Template')->printMessage('forum_' . (Main::getModule('Auth')->isLoggedIn() ? 'no_access' : 'need_login'));
			Main::getModule('NavBar')->addElement($forum[1], INDEXFILE . '?mode=viewforum&amp;forum_id=' . $this->forumID . SID_AMPER);
			$topicFile = @Functions::file('foren/' . $this->forumID . '-' . $this->topicID . '.xbb') or Main::getModule('Template')->printMessage('topic_not_found');
			#0:open/close[/moved] - 1:title - 2:userID - 3:tSmileyID - 4:notifyNewReplies[/movedForumID] - 5:timestamp[/movedTopicID] - 6:views - 7:pollID - ...
			$topic = Functions::explodeByTab(array_shift($topicFile));
			if($topic[0] == 'm')
				Main::getModule('Template')->printMessage('topic_has_moved', INDEXFILE . '?mode=viewthread&amp;forum_id=' . $topic[4] . '&amp;thread=' . $topic[5] . SID_AMPER, Functions::getMsgBackLinks($this->forumID));
			//Manage topic views
			if(!isset($_SESSION['session.tview.' . $this->forumID . '.' . $this->topicID]))
			{
				$topic[6]++;
				//Not using array_unshift to avoid changes in $topicFile
				Functions::file_put_contents('foren/' . $this->forumID . '-' . $this->topicID . '.xbb', implode("\n", array_merge(array(Functions::implodeByTab($topic)), $topicFile)));
				$_SESSION['session.tview.' . $this->forumID . '.' . $this->topicID] = true;
			}
			$topic[1] = Functions::censor($topic[1]);
			//Build page navigation bar
			$pages = ceil(($size = count($topicFile)) / Main::getModule('Config')->getCfgVal('posts_per_page'));
			if($this->page == 'last')
				$this->page = $pages;
			$pageBar = $posts = $parsedSignatures = array();
			for($i=1; $i<=$pages; $i++)
				$pageBar[] = $i != $this->page ? '<a href="' . INDEXFILE . '?mode=viewthread&amp;forum_id=' . $this->forumID . '&amp;thread=' . $this->topicID . '&amp;z=' . $i . SID_AMPER . '">' . $i . '</a>' : $i;
			//Only add bar by having more than one page
			Main::getModule('NavBar')->addElement($topic[1], '', ($pageBar = count($pageBar) < 2 ? '' : ' ' . sprintf(Main::getModule('Language')->getString('pages'), implode(' ', $pageBar))));
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
				$pollOptions = array();
				foreach(array_map(array('Functions', 'explodeByTab'), $pollFile) as $curPoll)
					$pollOptions[] = array('optionID' => $curPoll[0],
						'pollOption' => $curPoll[1],
						'percent' => ($curPercent = $curPoll[2] == '0' ? 0 : round(($curPoll[2]/$poll[4])*100, 1)),
						'voteText' => sprintf(Main::getModule('Language')->getString('x_percent_x_votes'), $curPercent, $curPoll[2]));
				Main::getModule('Template')->assign(array('pollID' => $topic[7],
					'pollTitle' => $poll[3],
					'isPollClosed' => $poll[0] > '2',
					'hasVoted' => isset($_SESSION['session_poll_' . $topic[7]]) || isset($_COOKIE['cookie_poll_' . $topic[7]]) || (Main::getModule('Auth')->isLoggedIn() && in_array(Main::getModule('Auth')->getUserID(), $pollVoters)),
					'needsLogin' => !(Main::getModule('Auth')->isLoggedIn() || $poll[0] == '1'),
					'canEdit' => Main::getModule('Auth')->isAdmin() || $isMod || (Main::getModule('Auth')->isLoggedIn() && Main::getModule('Auth')->getUserID() == $poll[1]),
					'pollOptions' => $pollOptions,
					'totalVotes' => $poll[4]));
			}
			//Process user and posts
			$end = $this->page*Main::getModule('Config')->getCfgVal('posts_per_page');
			for($i=$end-Main::getModule('Config')->getCfgVal('posts_per_page'); $i<($end > $size ? $size : $end); $i++)
			{
				#0:postID - 1:posterID - 2:proprietaryDate - 3:post - 4:ip - 5:isSignature - 6:tSmileyURL - 7:isSmiliesOn - 8:isBBCode - 9:isHTML
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
					$curPoster['userRegDate'] = Functions::formatDate($curPoster['userRegDate'] . (Functions::strlen($curPoster['userRegDate']) == 6 ? '01000000' : ''), Main::getModule('Language')->getString('REGDATEFORMAT'));
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
						list($curWidth, $curHeight) = array(Main::getModule('Config')->getCfgVal('avatar_width'), Main::getModule('Config')->getCfgVal('avatar_height'));
						if(Main::getModule('Config')->getCfgVal('use_getimagesize') == 1 && ($avatar = @getimagesize($curPoster['userAvatar'])) != false)
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
					$curPoster['userSig'] = !empty($curPoster['userSig']) && ($curPost[5] == '1' || $curPost[5] == 'yes') ? (isset($parsedSignatures[$curPost[1]]) ? $parsedSignatures[$curPost[1]] : ($parsedSignatures[$curPost[1]] = Main::getModule('BBCode')->parse(Functions::censor($curPoster['userSig']), false, true, true, $topicFile))) : '';
				}
				unset($curPoster['userMailOpts'], $curPoster['userPassHash'], $curPoster['userForumAcc']);
				//User values done, proceed with post
				$curPost[3] = Main::getModule('BBCode')->parse($curPost[3], $curPost[9] == '1' && $forum[7][1] == '1', $curPost[7] == '1' || $curPost[7] == 'yes', $forum[7][0] == '1' && ($curPost[8] == '1' || $curPost[8] == 'yes'), $topicFile);
				//Add prepared user data and post data
				$posts[] = $curPoster + array('postID' => $curPost[0],
					'tSmileyURL' => Functions::getTSmileyURL($curPost[6]),
					'date' => Functions::formatDate($curPost[2]),
					'postIPText' => !empty($curPost[4]) ? sprintf(Main::getModule('Language')->getString('ip_saved'), INDEXFILE . '?faction=viewip&amp;forum_id=' . $this->forumID . '&amp;topic_id=' . $this->topicID . '&amp;post_id=' . $curPost[0] . SID_AMPER) : Main::getModule('Language')->getString('ip_not_saved'),
					'canModify' => Main::getModule('Auth')->isAdmin() || $isMod || ($forum[10][4] == '1' && Main::getModule('Auth')->isLoggedIn() && Main::getModule('Auth')->getUserID() == $curPost[1]),
					'post' => Functions::censor($curPost[3]));
			}
			Main::getModule('Template')->assign(array('pageBar' => $pageBar,
				'topicTitle' => $topic[1],
				'isPoll' => $isPoll,
				'forumID' => $this->forumID,
				'topicID' => $this->topicID,
				'canModify' => ($canModify = Main::getModule('Auth')->isAdmin() || $isMod),
				'isOpen' => $topic[0] == '1' || $topic[0] == 'open',
				'isSticky' => $canModify && ($stickyFile = @Functions::file('foren/' . $this->forumID . '-sticker.xbb', FILE_SKIP_EMPTY_LINES)) != false && in_array($this->topicID, $stickyFile),
				'posts' => $posts)); //Prepared posts with users
			break;

//ViewTodaysPosts
			case 'todaysPosts':
			setcookie('upbwhere', INDEXFILE . '?faction=todaysPosts');
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('todays_posts'), INDEXFILE . '?faction=todaysPosts');
			$todaysPosts = array();
			if(($todaysPostsFile = Functions::file_get_contents('vars/todayposts.var')) != '' && current($todaysPostsFile = Functions::explodeByTab($todaysPostsFile)) == gmdate('Yd'))
				foreach(array_map(array('Functions', 'explodeByComma'), explode('|', $todaysPostsFile[1])) as $curTodaysPost)
					#0:forumID - 1:topicID - 2:userID - 3:date - 4:tSmileyID
					$todaysPosts[] = array('forumID' => $curTodaysPost[0],
						'forumTitle' => @next(Functions::getForumData($curTodaysPost[0])),
						'topic' => !Functions::file_exists('foren/' . $curTodaysPost[0] . '-' . $curTodaysPost[1] . '.xbb') ? Main::getModule('Language')->getString('deleted_moved') : '<a href="' . INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curTodaysPost[0] . '&amp;thread=' . $curTodaysPost[1] . '&amp;z=last' . SID_AMPER . '">' . (Functions::censor(Functions::getTopicName($curTodaysPost[0], $curTodaysPost[1]))) . '</a>',
						'author' => Functions::getProfileLink($curTodaysPost[2], true),
						'date' => Functions::formatDate($curTodaysPost[3]),
						'tSmiley' => Functions::getTSmileyURL($curTodaysPost[4]));
			Main::getModule('Template')->assign('todaysPosts', array_reverse($todaysPosts));
			break;

			case 'rssFeed':
			if(Main::getModule('Config')->getCfgVal('show_lposts') < 1)
				Main::getModule('Template')->printMessage('function_deactivated');
			if(($newestPosts = Functions::file_get_contents('vars/lposts.var')) != '')
			{
				Main::getModule('WhoIsOnline')->setLocation('RSSFeed');
				$newestPosts = Functions::explodeByTab($newestPosts);
				//Retrieve proper data
				foreach($newestPosts as &$curNewestPost)
				{
					#0:forumID - 1:topicID - 2:userID - 3:proprietaryDate[ - 4:tSmileyID]
					$curNewestPost = Functions::explodeByComma($curNewestPost . ',1'); //Make sure index 4 is available
					$curNewestPost[2] = Functions::isGuestID($curNewestPost[2]) ? Functions::substr($curNewestPost[2], 1) : (Functions::file_exists('members/' . $curNewestPost[2] . '.xbb') ? current(Functions::file('members/' . $curNewestPost[2] . '.xbb')) : Main::getModule('Language')->getString('deleted'));
					$curNewestPost[5] = date('r', Functions::getTimestamp($curNewestPost[3] . '01000000')-date('Z'));
					$curNewestPost[4] = Functions::getTSmileyURL($curNewestPost[4]);
				}
				unset($curNewestPost); //Delete remaining reference to avoid conflicts
				//Get pubDate from regdate of first user
				$i = 1;
				$size = intval(Functions::file_get_contents('vars/last_user_id.var')) or $size = 1;
				do
					$firstUser = Functions::getUserData($i++);
				while($firstUser == false && $i <= $size);
				//RSS header
				header('Content-Type: application/rss+xml');
				echo('<?xml version="1.0" encoding="' . Main::getModule('Language')->getString('html_encoding') .'" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/">
 <channel>
  <title>' . sprintf(Main::getModule('Language')->getString('x_rss_feed'), Main::getModule('Config')->getCfgVal('forum_name')) . '</title>
  <link>' . Main::getModule('Config')->getCfgVal('address_to_forum') . '/' . INDEXFILE . '</link>
  <description>' . sprintf(Main::getModule('Language')->getString('newest_posts_from_x'), Main::getModule('Config')->getCfgVal('forum_name')) . '</description>
  <language>' . Main::getModule('Language')->getLangCode() .'</language>
  <lastBuildDate>' . current(array_slice($newestPosts[0], 5, 1)) . '</lastBuildDate>
  <pubDate>' . date('r', $firstUser != false ? Functions::getTimestamp($firstUser[6] . '01000000')-date('Z') : time()) . '</pubDate>
  <docs>http://www.rssboard.org/rss-specification</docs>
  <generator>Tritanium Bulletin Board ' . VERSION_PUBLIC . '</generator>
  <atom:link href="' . Main::getModule('Config')->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?faction=rssFeed" rel="self" type="application/rss+xml" />
');
				//RSS body with items
				foreach($newestPosts as $curNewestPost)
				{
					//Get post data
					if(($curTopic = @Functions::file('foren/' . $curNewestPost[0] . '-' . $curNewestPost[1] . '.xbb')) != false)
						foreach(array_slice($curTopic, 1) as $curKey => $curPost)
						{
							$curPost = Functions::explodeByTab($curPost);
							if($curPost[2] == $curNewestPost[3])
							{
								$curTopic = array('title' => Functions::censor(@next(Functions::explodeByTab($curTopic[0]))),
									'post' => htmlspecialchars(Main::getModule('BBCode')->parse($curPost[3])),
									'count' => count($curTopic)-2,
									'page' => ceil($curKey / Main::getModule('Config')->getCfgVal('posts_per_page')),
									'postID' => $curPost[0]);
							}
						}
					else
						$curTopic = array('title' => Main::getModule('Language')->getString('deleted'),
							'post' => Main::getModule('Language')->getString('deleted'),
							'count' => 0,
							'page' => 1,
							'postID' => 1);
					echo('  <item>
   <title>' . $curTopic['title'] . '</title>
   <link>' . Main::getModule('Config')->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curNewestPost[0] . '&amp;thread=' . $curNewestPost[1] . '&amp;z=' . $curTopic['page'] . '#post' . $curTopic['postID'] . '</link>
   <guid isPermaLink="true">' . Main::getModule('Config')->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curNewestPost[0] . '&amp;thread=' . $curNewestPost[1] . '&amp;z=' . $curTopic['page'] . '#post' . $curTopic['postID'] . '</guid>
   <pubDate>' . $curNewestPost[5] . '</pubDate>
   <dc:creator>' . $curNewestPost[2] . '</dc:creator>
   <category>' . @next(Functions::getForumData($curNewestPost[0])) . '</category>
   <description>&lt;img src=&quot;' . $curNewestPost[4] . '&quot; alt=&quot;&quot; style=&quot;float:right;&quot;&gt;' . $curTopic['post'] .  '</description>
   <comments>' . Main::getModule('Config')->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?faction=reply&amp;forum_id=' . $curNewestPost[0] . '&amp;thread_id=' . $curNewestPost[1] . '</comments>
   <slash:comments>' . $curTopic['count'] . '</slash:comments>
  </item>
');
				}
				//RSS footer
				exit(' </channel>
</rss>');
			}
			else
				Main::getModule('Template')->printMessage('no_newest_posts');
			break;

//ForumIndex
			default:
			//Manage cookie
			setcookie('upbwhere', INDEXFILE);
			//Process categories and forums
			$topicCounter = $postCounter = 0;
			$cats = $forums = $newestPosts = array();
			//Prepare categories
			foreach(Functions::file('vars/kg.var') as $curCat)
				#0:id - 1:name
				$cats[] = Functions::explodeByTab($curCat);
			//Prepare forums
			$showPrivateForums = Main::getModule('Config')->getCfgVal('show_private_forums') == 1;
			foreach(array_map(array('Functions', 'explodeByTab'), Functions::file('vars/foren.var')) as $curForum)
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
						$curLastPost = Main::getModule('Language')->getString('no_last_post');
					elseif(!$showCurForum) //At the latest checkUserAccess is needed here
						$curLastPost = Functions::formatDate($curLastPostData[2]);
					elseif(!Functions::file_exists($curTopicFile = 'foren/' . $curForum[0] . '-' . $curLastPostData[0] . '.xbb'))
						$curLastPost = Main::getModule('Language')->getString('deleted_moved');
					else
					{
						//Query template for formatting current last posting
						$curLastPost = Main::getModule('Template')->fetch('LastPost', array('tSmileyURL' => Functions::getTSmileyURL($curLastPostData[3]),
							'forumID' => $curForum[0],
							'topicID' => $curLastPostData[0],
							'topicTitle' => Functions::shorten(Functions::censor(@next(Functions::explodeByTab(current(Functions::file($curTopicFile))))), 22),
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
						'isNewPost' => !isset($_COOKIE['forum.' . $curForum[0]]) || $_COOKIE['forum.' . $curForum[0]] < $curForum[6],
						'lastPost' => $curLastPost,
						'mods' => Functions::getProfileLink($curForum[11]));
					$topicCounter += $curForum[3];
					$postCounter += $curForum[4];
				}
			}
			//Process newest posts
			if(Main::getModule('Config')->getCfgVal('show_lposts') >= 1 && ($lastPosts = Functions::file_get_contents('vars/lposts.var')) != '')
			{
				foreach(Functions::explodeByTab($lastPosts) as $curNewestPost)
				{
					#0:forumID - 1:topicID - 2:userID - 3:proprietaryDate[ - 4:tSmileyID]
					$curNewestPost = Functions::explodeByComma($curNewestPost . ',1'); //Make sure index 4 is available
					$newestPosts[] = sprintf(Main::getModule('Language')->getString('x_by_x_on_x'),
						//Topic check + link + title preparation
						!Functions::file_exists('foren/' . $curNewestPost[0] . '-' . $curNewestPost[1] . '.xbb') ? Main::getModule('Language')->getString('deleted') : '<img src="' . Functions::getTSmileyURL($curNewestPost[4]) . '" alt="" /> <a href="' . INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curNewestPost[0] . '&amp;thread=' . $curNewestPost[1] . '&amp;z=last' . SID_AMPER . '">' . (Functions::shorten(Functions::censor(Functions::getTopicName($curNewestPost[0], $curNewestPost[1])), 53)) . '</a>',
						Functions::getProfileLink($curNewestPost[2], true),
						Functions::formatDate($curNewestPost[3]));
				}
			}
			Main::getModule('Template')->assign(array('cats' => $cats,
				'forums' => $forums,
				'topicCounter' => $topicCounter,
				'postCounter' => $postCounter,
				'newestPosts' => $newestPosts) + 
				//Add board statistics
				(Main::getModule('Config')->getCfgVal('show_board_stats') == 1 ? array(
				'newestMember' => Functions::getProfileLink(Functions::file_get_contents('vars/last_user_id.var'), true),
				'memberCounter' => Functions::file_get_contents('vars/member_counter.var')) : array()));
			break;
		}
		//Always append IDs to WIO location. WIO will not parse them in inapplicable mode.
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode], null, null, ',' . $this->forumID . ',' . $this->topicID);
	}

	/**
	 * Returns user data with default values for a guest.
	 *
	 * @param string $nick Guest name
	 * @return array Guest data with named keys ready-for-use in template
	 */
	private function getGuestTemplate($nick)
	{
		return array_combine(self::$userKeys, array_merge(array($nick, 0, '', false, Main::getModule('Language')->getString('guest')), array_fill(0, $this->userKeysSize-5, ''))) + array('userRank' => '', 'sendPM' => false);
	}

	/**
	 * Returns user data with default values for a deleted user.
	 *
	 * @param int $userID Former user ID
	 * @return array Deleted user data with named keys ready-for-use in template
	 */
	private function getKilledTemplate($userID)
	{
		return array_combine(self::$userKeys, array_merge(array(Main::getModule('Config')->getCfgVal('var_killed'), $userID, '', false, Main::getModule('Language')->getString('deleted')), array_fill(0, $this->userKeysSize-5, ''))) + array('userRank' => '', 'sendPM' => false);
	}
}
?>