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
	private $forumID;

	private $mode;

	private $threadID;

	function __construct()
	{
		$this->forumID = isset($_GET['forum_id']) ? intval($_GET['forum_id']) : -1;
		$this->mode = isset($_GET['mode']) && in_array($_GET['forum_id'], array('viewforum', 'viewthread')) ? $_GET['mode'] : '';
		$this->threadID = isset($_GET['thread']) ? intval($_GET['thread']) : -1;
	}

	/**
	 * Displays specific or all forums.
	 */
	public function execute()
	{
		//Check IP for specific forum only (the global check was performed before in Main)
		if($this->forumID != -1 && ($endtime = Functions::checkIPAccess()) !== true)
			self::getModule('Template')->printMessage(($endtime == -1 ? 'banned_forever_one_forum' : 'banned_for_x_minutes_one_forum'), ceil(($endtime-time())/60));
		//Process news
		if(count($news = Functions::file('vars/news.var')) != 0)
		{
			$newsConfig = explode("\t", $news[0]);
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
			break;

			case 'viewthread':
			//Manage cookies
			setcookie('upbwhere', INDEXFILE . '?mode=viewforum&forum_id=' . $this->forumID . '&thread=' . $this->threadID);
			setcookie('forum.' . $this->forumID . '.' . $this->threadID, time(), time()+60*60*24*365, Main::getModule('Config')->getCfgVal('path_to_forum'));

			//lol?
			$tempVar = 'session.tview.' . $this->forumID . '.' . $this->threadID;
			if($$tempVar != 1)
			{
				$$tempVar = 1;
				
				$_SESSION[$tempVar] = $$tempVar;
			}
			break;

			default:
			//Manage cookie
			setcookie('upbwhere', INDEXFILE);
			//Process categories and forums
			$topicCounter = $postCounter = 0;
			$cats = $forums = array();
			//Prepare categories
			foreach(Functions::file('vars/kg.var') as $curCat)
				#0:id - 1:name
				$cats[] = explode("\t", $curCat);
			//Prepare forums
			$showPrivateForums = Main::getModule('Config')->getCfgVal('show_private_forums') == 1;
			$showCurForum = true;
			foreach(Functions::file('vars/foren.var') as $curForum)
			{
				#0:id - 1:name - 2:descr - 3:topics - 4:postings - 5:catID - 6:lastPostTstamp - 7:options - 8: - 9:lastPostData - 10:permissions - 11:modIDs
				#7:0:bbCode - 7:1:html - 7:2:notifyMods
				#9:0:topicID - 9:1:userID - 9:2:proprietaryDate - #9:3:tSmileyID
				#10:0:memberAccess - 9:1:memberNewTopic - 9:2:memberPostReply - 9:3:memberPostPolls - 9:4:memberEditOwnPosts - 9:5:memberEditPolls - 9:6:guestAccess - 9:7:guestNewTopic - 9:8:guestPostReply - 9:9:guestPostPolls
				$curForum = explode("\t", $curForum);
				//Check permission
				if(!$showPrivateForums)
				{
					$curPerms = explode(',', $curForum[10]);
					//not logged in ? check guest : check member
					$showCurForum = !Main::getModule('Auth')->isLoggedIn() ? $curPerms[6] == '1' : Functions::checkMemberAccess($curForum, 0, $curPerms);
				}
				if($showCurForum)
				{
					$curLastPostData = explode(',', $curForum[9]);
					//Check and prepare last post with link or related message
					if(!isset($curLastPostData[0]))
						$curLastPost = Main::getModule('Language')->getString('no_last_post');
					elseif(!$showCurForum)
						$curLastPost = Functions::formatDate($curLastPostData[2]);
					elseif(!file_exists($curTopicFile = 'foren/' . $curForum[0] . '-' . $curLastPostData[0] . '.xbb'))
						$curLastPost = Main::getModule('Language')->getString('deleted_moved');
					else
					{
						//Prepare topic title of current last posting
						$curTopicTitle = next(explode("\t", current(Functions::file($curTopicFile))));
						if(Main::getModule('Config')->getCfgVal('censored') == 1)
							$curTopicTitle = Functions::censor($curTopicTitle);
						if(Functions::strlen($curTopicTitle) > 22)
							$curTopicTitle = Functions::substr($curTopicTitle, 0, 19) . Main::getModule('Language')->getString('dots');
						//Query template for formatting current last posting
						$curLastPost = Main::getModule('Template')->fetch('LastPost', array('tSmileyURL' => $curLastPostData[3],
							'forumID' => $curForum[0],
							'topicID' => $curLastPostData[0],
							'topicTitle' => $curTopicTitle,
							//Prepare user of current last posting
							'user' => $curLastPostData[1][0] == '0' ? Functions::substr($curLastPostData[1], 1) : Functions::getProfileLink($curLastPostData[1], true),
							'date' => Functions::formatDate($curLastPostData[2])));
					}
					$forums[] = array($curForum[0], $curForum[1], $curForum[2], $curForum[3], $curForum[4], $curForum[5],
						//Cookie check to detect new posts in current forum since last visit
						!isset($_COOKIE['forum.' . $curForum[0]]) || $_COOKIE['forum.' . $curForum[0]] < $curForum[6],
						$curLastPost,
						Functions::getProfileLink($curForum[11]));
					$topicCounter += $curForum[3];
					$postCounter += $curForum[4];
				}
			}
			Main::getModule('Template')->assign(array('cats' => $cats,
				'forums' => $forums,
				'topicCounter' => $topicCounter,
				'postCounter' => $postCounter));
			Main::getModule('WhoIsOnline')->setLocation();
			break;
		}
		Main::getModule('Template')->printPage('Forum');
	}
}
?>