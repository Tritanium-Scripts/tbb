<?php
/**
 * Displays specific forum or all forums index with additional stats.
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
	private static $modeTable = array('' => 'ForumIndex', 'viewforum' => 'ViewForum', 'viewthread' => 'ViewTopic');

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
	 * Detects IDs, page and sets mode.
	 */
	function __construct()
	{
		$this->mode = isset($_GET['mode']) && in_array($_GET['mode'], array('viewforum', 'viewthread')) ? $_GET['mode'] : '';
		$this->forumID = isset($_GET['forum_id']) ? intval($_GET['forum_id']) : -1;
		$this->topicID = isset($_GET['thread']) ? intval($_GET['thread']) : -1;
		$this->page = isset($_GET['z']) ? intval($_GET['z']) : 1;
	}

	/**
	 * Displays specific or all forums.
	 */
	public function execute()
	{
		//Check IP for specific forum (and topic, too) only (the global check was performed before in Main)
		if($this->forumID != -1 && ($endtime = Functions::checkIPAccess()) !== true)
			self::getModule('Template')->printMessage(($endtime == -1 ? 'banned_forever_one_forum' : 'banned_for_x_minutes_one_forum'), ceil(($endtime-time())/60));
		//Process news
		if(count($news = Functions::file('vars/news.var')) != 0)
		{
			$newsConfig = Functions::explodeByTab($news[0]);
			Main::getModule('Template')->assign(array('news' => time() < $newsConfig[1] || $newsConfig[1] == '-1' ? array_slice($news, 1) : false,
				'newsType' => $newsConfig[0]));
		}
		else
			Main::getModule('Template')->assign('news', false);
		//Perform mode
		switch($this->mode)
		{
			case 'viewforum':
			//Manage cookies
			setcookie('upbwhere', INDEXFILE . '?mode=viewforum&forum_id=' . $this->forumID); //Redir cookie after login
			setcookie('forum.' . $this->forumID, time(), time()+60*60*24*365, Main::getModule('Config')->getCfgVal('path_to_forum')); //Cookie to detect last visit
			//Process forum and its topics
			$forum = Functions::getForumData($this->forumID) or Main::getModule('Template')->printMessage('forum_not_found');
			if(!Functions::checkUserAccess($forum, 0))
				Main::getModule('Template')->printMessage('forum_' . (Main::getModule('Auth')->isLoggedIn() ? 'no_access' : 'need_login'));
			$topicFile = array_reverse(Functions::file('foren/' . $this->forumID . '-threads.xbb'));
			$pages = ceil(($size = count($topicFile)) / Main::getModule('Config')->getCfgVal('topics_per_page'));
			//Build page navigation bar
			$pageBar = $topics = array();
			for($i=1; $i<=$pages; $i++)
				$pageBar[] = $i != $this->page ? '<a href="' . INDEXFILE . '?mode=viewforum&amp;forum_id=' . $this->forumID . '&amp;z=' . $i . SID_AMPER . '">' . $i . '</a>' : $i;
			Main::getModule('NavBar')->addElement($forum[1] . ($pageBar = count($pageBar) < 2 ? '' : ' ' . sprintf(Main::getModule('Language')->getString('pages'), implode(' ', $pageBar))));
			//Process topics for current page
			$end = $this->page*Main::getModule('Config')->getCfgVal('topics_per_page');
			for($i=$end-Main::getModule('Config')->getCfgVal('topics_per_page'); $i<($end > $size ? $size : $end); $i++)
			{
				$curLastPost = Functions::explodeByTab(@end($curTopic = Functions::file('foren/' . $this->forumID . '-' . $topicFile[$i] . '.xbb')));
				$curEnd = ceil(($curSize = count($curTopic)-1) / Main::getModule('Config')->getCfgVal('posts_per_page'));
				#0:open/close - 1:title - 2:userID - 3:tSmileyID - 4: - 5:timestamp - 6:views - 7:pollID - ...
				$curTopic = Functions::explodeByTab($curTopic[0]);
				//Detect new posts
				$curCookieID = 'topic.' . $this->forumID . '.' . $topicFile[$i];
				switch($curTopic[0])
				{
					case '1':
					case 'open': //Downward compatibility
					$curTopicIcon = !isset($_COOKIE[$curCookieID]) || $_COOKIE[$curCookieID] < $curTopic[5] ? ($size <= Main::getModule('Config')->getCfgVal('topic_is_hot') ? 'ontopic' : 'onstopic') : ($size <= Main::getModule('Config')->getCfgVal('topic_is_hot') ? 'onntopic' : 'onnstopic');
					break;

					case '2':
					case 'close': //Downward compatibility
					$curTopicIcon = !isset($_COOKIE[$curCookieID]) || $_COOKIE[$curCookieID] < $curTopic[5] ? 'cntopic' : 'cnntopic';
					break;
				}
				//Build page navigation bar for current topic
				$curTopicPageBar = array();
				for($j=1; $j<=$curEnd; $j++)
					$curTopicPageBar[] = '<a href="' . INDEXFILE . '?mode=viewforum&amp;forum_id=' . $this->forumID . '&amp;thread=' . $topicFile[$i] . '&amp;z=' . $j . SID_AMPER . '">' . $j . '</a>';
				$curTopicPageBar = count($curTopicPageBar) < 2 ? '' : ' ' . sprintf(Main::getModule('Language')->getString('pages'), implode(' ', $curTopicPageBar));
				//Censor title and add to parsed topics
				if(Main::getModule('Config')->getCfgVal('censored') == 1)
					$curTopic[1] = Functions::censor($curTopic[1]);
				$topics[] = array('topicIcon' => $curTopicIcon,
					'tSmileyURL' => Functions::getTSmileyURL($curTopic[3]),
					'isPoll' => !empty($curTopic[7]),
					'topicID' => $topicFile[$i],
					'topicTitle' => utf8_encode($curTopic[1]),
					'topicPageBar' => $curTopicPageBar,
					'topicStarter' => utf8_encode(Functions::getProfileLink($curTopic[2], true)),
					'postCounter' => $curSize-1, #todo: evtl kein minus!!
					'views' => $curTopic[6],
					'lastPost' => sprintf(Main::getModule('Language')->getString('last_post_x_from_x'), Functions::formatDate($curLastPost[2]), utf8_encode(Functions::getProfileLink($curLastPost[1], true))));
			}
			Main::getModule('Template')->assign(array('pageBar' => $pageBar,
				'topics' => $topics,
				'forumID' => $this->forumID));
			break;

			case 'viewthread':
			//Manage cookies
			setcookie('upbwhere', INDEXFILE . '?mode=viewforum&forum_id=' . $this->forumID . '&thread=' . $this->topicID);
			setcookie('forum.' . $this->forumID . '.' . $this->topicID, time(), time()+60*60*24*365, Main::getModule('Config')->getCfgVal('path_to_forum'));
			//lol?
			if(!isset($_SESSION['session.tview.' . $this->forumID . '.' . $this->topicID]))
			{
				#increase_topic_views
				$_SESSION['session.tview.' . $this->forumID . '.' . $this->topicID] = 1;
			}
			//Process topic and its posts
			
			break;

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
			foreach(Functions::file('vars/foren.var') as $curForum)
			{
				#0:id - 1:name - 2:descr - 3:topics - 4:postings - 5:catID - 6:lastPostTstamp - 7:options - 8:status? - 9:lastPostData - 10:permissions - 11:modIDs
				#7:0:bbCode - 7:1:html - 7:2:notifyMods
				#9:0:topicID - 9:1:userID - 9:2:proprietaryDate - #9:3:tSmileyID
				#10:0:memberAccess - 10:1:memberNewTopic - 10:2:memberPostReply - 10:3:memberPostPolls - 10:4:memberEditOwnPosts - 10:5:memberEditPolls - 10:6:guestAccess - 10:7:guestNewTopic - 10:8:guestPostReply - 10:9:guestPostPolls
				$curForum = Functions::explodeByTab($curForum);
				//Check permission
				$showCurForum = Functions::checkUserAccess($curForum, 0);
				if($showPrivateForums || $showCurForum)
				{
					$curLastPostData = Functions::explodeByComma($curForum[9]);
					//Check and prepare last post with link or related message
					if(!isset($curLastPostData[0]))
						$curLastPost = Main::getModule('Language')->getString('no_last_post');
					elseif(!$showCurForum) //At the latest checkUserAccess is needed here
						$curLastPost = Functions::formatDate($curLastPostData[2]);
					elseif(!Functions::file_exists($curTopicFile = 'foren/' . $curForum[0] . '-' . $curLastPostData[0] . '.xbb'))
						$curLastPost = Main::getModule('Language')->getString('deleted_moved');
					else
					{
						//Prepare topic title of current last posting
						$curTopicTitle = @next(Functions::explodeByTab(current(Functions::file($curTopicFile))));
						if(Main::getModule('Config')->getCfgVal('censored') == 1)
							$curTopicTitle = Functions::censor($curTopicTitle);
						//Query template for formatting current last posting
						$curLastPost = Main::getModule('Template')->fetch('LastPost', array('tSmileyURL' => Functions::getTSmileyURL($curLastPostData[3]),
							'forumID' => $curForum[0],
							'topicID' => $curLastPostData[0],
							'topicTitle' => Functions::shorten($curTopicTitle, 22),
							//Prepare user of current last posting
							'user' => $curLastPostData[1][0] == '0' ? Functions::substr($curLastPostData[1], 1) : Functions::getProfileLink($curLastPostData[1], true),
							'date' => Functions::formatDate($curLastPostData[2])));
					}
					//Compile (into) array with all the data for template
					$forums[] = array($curForum[0], $curForum[1], $curForum[2], $curForum[3], $curForum[4], $curForum[5],
						//Cookie check to detect new posts in current forum since last visit
						!isset($_COOKIE['forum.' . $curForum[0]]) || $_COOKIE['forum.' . $curForum[0]] < $curForum[6],
						$curLastPost,
						Functions::getProfileLink($curForum[11]));
					$topicCounter += $curForum[3];
					$postCounter += $curForum[4];
				}
			}
			//Process newest posts
			if(Main::getModule('Config')->getCfgVal('show_lposts') == 1 && ($lastPosts = Functions::file_get_contents('vars/lposts.var')) != '')
			{
				foreach(Functions::explodeByTab($lastPosts) as $curNewestPost);
				{
					#0:forumID - 1:topicID - 2:userID - 3:proprietaryDate - 4:tSmileyID
					$curNewestPost = Functions::explodeByComma($curNewestPost);
					$newestPosts[] = sprintf(Main::getModule('Language')->getString('x_by_x_on_x'),
						//Topic check + link + title preparation
						!Functions::file_exists('foren/' . $curNewestPost[0] . '-' . $curNewestPost[1] . '.xbb') ? Main::getModule('Language')->getString('deleted_moved') : '<img src="' . Functions::getTSmileyURL($curNewestPost[4]) . '" alt="" /> <a href="' . INDEXFILE . '?mode=viewthread&amp;forum_id=' . $curNewestPost[0] . '&amp;thread=' . $curNewestPost[1] . SID_AMPER . '">' . (Functions::shorten(Main::getModule('Config')->getCfgVal('censored') == 1 ? Functions::censor(Functions::getTopicName($curNewestPost[0], $curNewestPost[1])) : Functions::getTopicName($curNewestPost[0], $curNewestPost[1]), 53)) . '</a>',
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
				'newestMember' => Functions::getProfileLink(Functions::file_get_contents('vars/last_user_id.var')),
				'memberCounter' => Functions::file_get_contents('vars/member_counter.var')) : array()));
			break;
		}
		//Always append IDs to WIO location. WIO will not parse them in inapplicable mode.
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode], null, null, ',' . $this->forumID . ',' . $this->topicID);
	}
}
?>