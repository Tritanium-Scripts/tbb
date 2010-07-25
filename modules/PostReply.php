<?php
/**
 * Manages new replies, poster IPs or post editing.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class PostReply implements Module
{
	/**
	 * Detected errors during posting or editing actions.
	 *
	 * @var array Error messages
	 */
	private $errors = array();

	/**
	 * Data of target forum to post in / edit.
	 *
	 * @var array|bool Loaded forum data or false
	 */
	private $forum;

	/**
	 * Contains mode to execute.
	 *
	 * @var string Posting mode
	 */
	private $mode;

	/**
	 * Data of new reply.
	 *
	 * @var array New reply data
	 */
	private $newReply;

	/**
	 * ID of post to edit or view IP.
	 *
	 * @var int Post ID
	 */
	private $postID;

	/**
	 * New reply being previewed.
	 *
	 * @var bool New reply previewed
	 */
	private $preview;

	/**
	 * Meta data of target topic to post in / edit.
	 *
	 * @var array|bool Loaded topic data or false
	 */
	private $topic;

	/**
	 * Posts of target topic to post in / edit.
	 *
	 * @var array Topic posts
	 */
	private $topicFile;

	/**
	 * ID of topic to reply to / edit.
	 *
	 * @var int Topid ID
	 */
	private $topicID;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('reply' => 'PostReply',
		'edit' => 'EditPost',
		'topic' => 'EditTopic',
		'editpoll' => 'EditPoll',
		'vote' => 'PostVotePoll',
		'viewip' => 'PostViewIP');

	/**
	 * Loads various data and sets mode.
	 *
	 * @param string $mode Mode to execute
	 * @return PostReply New instance of this class
	 */
	function __construct($mode)
	{
		$this->mode = $mode;
		$this->forum = Functions::getForumData($forumID = intval(Functions::getValueFromGlobals('forum_id')));
		#0:open/close[/moved] - 1:title - 2:userID - 3:tSmileyID - 4:notifyNewReplies[/movedForumID] - 5:timestamp[/movedTopicID] - 6:views - 7:pollID
		if(($this->topicFile = Functions::file('foren/' . $forumID . '-' . ($this->topicID = intval(Functions::getValueFromGlobals('thread_id'))) . '.xbb')) != false)
			$this->topic = Functions::explodeByTab(array_shift($this->topicFile));
		$this->postID = intval(Functions::getValueFromGlobals('post_id')) or $this->postID = intval(Functions::getValueFromGlobals('quote'));
		$this->preview = Functions::getValueFromGlobals('preview') != '';
		//Get contents for new reply
		$this->newReply = array('nick' => htmlspecialchars(trim(Functions::getValueFromGlobals('nli_name'))),
			'title' => htmlspecialchars(trim(Functions::getValueFromGlobals('title'))),
			'post' => htmlspecialchars(trim(Functions::getValueFromGlobals('post'))),
			'tSmileyID' => intval(Functions::getValueFromGlobals('tsmilie')),
			'isSmilies' => Functions::getValueFromGlobals('smilies') == '1',
			'isSignature' => Functions::getValueFromGlobals('show_signatur') == '1',
			'isBBCode' => Functions::getValueFromGlobals('use_upbcode') == '1',
			'isXHTML' => Functions::getValueFromGlobals('use_htmlcode') == '1',
			'isNotify' => Functions::getValueFromGlobals('sendmail2') == '1',
			'isAddURLs' => Functions::getValueFromGlobals('isAddURLs') == 'true');
		//Topic smiley fix
		if(empty($this->newReply['tSmileyID']))
			$this->newReply['tSmileyID'] = 1;
	}

	/**
	 * Executes the mode.
	 */
	public function execute()
	{
		//General checks and navbar for every mode
		if($this->forum == false)
			Main::getModule('Template')->printMessage('forum_not_found');
		Main::getModule('NavBar')->addElement($this->forum[1], INDEXFILE . '?mode=viewforum&amp;forum_id=' . $this->forum[0] . SID_AMPER);
		if(Main::getModule('Auth')->isBanned())
			Main::getModule('Template')->printMessage('banned_from_forum');
		Main::getModule('NavBar')->addElement($this->topic[1], INDEXFILE . '?mode=viewthread&amp;forum_id=' . $this->forum[0] . '&amp;thread=' . $this->topicID . SID_AMPER);
		if($this->topicFile == false)
			Main::getModule('Template')->printMessage('topic_not_found');
		elseif($this->topic[0] == 'm')
			Main::getModule('Template')->printMessage('topic_has_moved', INDEXFILE . '?mode=viewthread&amp;forum_id=' . $this->topic[4] . '&amp;thread=' . $this->topic[5] . SID_AMPER, INDEXFILE . '?mode=viewforum&amp;forum_id=' . $this->forum[0] . SID_AMPER);
		//Execute action (and subaction)
		switch($this->mode)
		{
			case 'reply':
			setcookie('upbwhere', INDEXFILE . '?faction=reply&forum_id=' . $this->forum[0] . '&thread_id=' . $this->topicID); //Redir cookie after login
			//Specific checks and navbar for this mode
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('post_new_reply'), INDEXFILE . '?faction=reply&amp;thread_id=' . $this->topicID . '&amp;forum_id=' . $this->forum[0] . SID_AMPER);
			if(!Functions::checkUserAccess($this->forum, 2, 8))
				Main::getModule('Template')->printMessage(Main::getModule('Auth')->isLoggedIn() ? 'forum_no_access' : 'login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
			elseif($this->topic[0] != '1' && $this->topic[0] != 'open' && !Main::getModule('Auth')->isAdmin() && !Functions::checkModOfForum($this->forum))
				Main::getModule('Template')->printMessage('topic_is_closed');
			//Auto URL check
			if($this->newReply['isAddURLs'])
				$this->newReply['post'] = Functions::addURL($this->newReply['post']);
			//Preview...
			if($this->preview)
				$this->newReply['preview'] = array('title' => &$this->newReply['title'],
					'tSmileyID' => Functions::getTSmileyURL($this->newReply['tSmileyID']),
					'post' => Main::getModule('BBCode')->parse(Functions::nl2br($this->newReply['post']), $this->newReply['isXHTML'], $this->newReply['isSmilies'], $this->newReply['isBBCode']),
					'signature' => $this->newReply['isSignature'] ? Main::getModule('BBCode')->parse(Main::getModule('Auth')->getUserSig()) : false);
			//...or final save...
			elseif(Functions::getValueFromGlobals('save') == 'yes')
			{
				if(!Main::getModule('Auth')->isLoggedIn() && empty($this->newReply['nick']) && Main::getModule('Config')->getCfgVal('nli_must_enter_name') == 1)
					$this->errors[] = Main::getModule('Language')->getString('please_enter_your_user_name');
				if(empty($this->newReply['post']))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_post');
				if(empty($this->errors))
				{
					//Set proper nick name
					$this->newReply['nick'] = Main::getModule('Auth')->isLoggedIn() ? Main::getModule('Auth')->getUserID() : (empty($this->newReply['nick']) ? Main::getModule('Language')->getString('guest') : '0' . $this->newReply['nick']);
					//Build new post
					$newPost = array(current(Functions::explodeByTab(end($this->topicFile)))+1,
						$this->newReply['nick'],
						gmdate('YmdHis'),
						Functions::stripSIDs(Functions::nl2br($this->newReply['post'])),
						$_SERVER['REMOTE_ADDR'],
						$this->newReply['isSignature'] ? '1' : '0',
						$this->newReply['tSmileyID'],
						$this->newReply['isSmilies'] ? '1' : '0',
						$this->newReply['isBBCode'] ? '1' : '0',
						$this->newReply['isXHTML'] ? '1' : '0',
						'', '', "\n");
					//Write post related stuff
					$this->topic[5] = time();
					$this->topicFile[] = Functions::implodeByTab($newPost);
					Functions::file_put_contents('foren/' . $this->forum[0] . '-' . $this->topicID . '.xbb', Functions::implodeByTab($this->topic) . "\n" . implode("\n", $this->topicFile));
					$this->setTopicOnTop();
					//Update all the counters and stats
					Functions::updateForumData($this->forum[0], 0, 1, $this->topicID, $this->newReply['nick'], $newPost[2], $this->newReply['tSmileyID']);
					if(Main::getModule('Auth')->isLoggedIn())
						Functions::updateUserPostCounter($this->newReply['nick']);
					if($this->forum[10][6] == '1')
					{
						Functions::updateLastPosts($this->forum[0], $this->topicID, $this->newReply['nick'], $newPost[2], $this->newReply['tSmileyID']);
						Functions::updateTodaysPosts($this->forum[0], $this->topicID, $this->newReply['nick'], $newPost[2], $this->newReply['tSmileyID']);
					}
					//Notify topic creator
					if($this->topic[4] == '1' && Main::getModule('Config')->getCfgVal('activate_mail') == 1 && Main::getModule('Config')->getCfgVal('notify_new_replies') == 1 && Main::getModule('Auth')->getUserID() != $this->topic[2] && ($notifyUser = Functions::getUserData($this->topic[2])) != false)
						Functions::sendMessage($notifyUser[3], 'notify_new_reply', $notifyUser[0], Main::getModule('Config')->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?faction=readforum&mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $this->topicID);
					//Done
					Main::getModule('Logger')->log('New reply (' . $this->forum[0] . ',' . $this->topicID . ') posted by %s', LOG_NEW_POSTING);
					Main::getModule('Template')->printMessage('reply_posted', INDEXFILE . '?mode=viewthread&amp;forum_id=' . $this->forum[0] . '&amp;thread=' . $this->topicID . '&amp;z=last' . SID_AMPER . '#' . $newPost[0], INDEXFILE . '?mode=viewforum&amp;forum_id=' . $this->forum[0] . SID_AMPER, INDEXFILE . SID_QMARK);
				}
			}
			//...or first call and add possible quote with quoted user
			elseif(!empty($this->postID))
			{
				list(,$quoterID,,$this->newReply['post']) = Functions::explodeByTab($this->topicFile[$this->postID-1]);
				#todo
				$this->newReply['post'] = '[quote=' . (is_numeric($quoterID) ? current(Functions::getUserData($quoterID)) : ($quoterID != Main::getModule('Language')->getString('guest') ? Functions::substr($quoterID, 1) : $quoterID)) . ']' . Functions::br2nl(preg_replace("/\[lock\](.*?)\[\/lock\]/si", '', $this->newReply['post'])) . '[/quote]';
			}
			//Process last x posts
			$lastReplies = array();
			foreach(array_map(array('Functions', 'explodeByTab'), $this->topicFile) as $curReply)
				$lastReplies[] = array('nick' => Functions::getProfileLink($curReply[1], true),
					'post' => Functions::censor(Main::getModule('BBCode')->parse($curReply[3], $curReply[9] == '1' && $this->forum[7][1] == '1', $curReply[7] == '1' || $curReply[7] == 'yes', ($curReply[8] == '1' || $curReply[8] == 'yes') && $this->forum[7][0] == '1', $this->topicFile)));
			Main::getModule('Template')->assign(array('newReply' => $this->newReply,
				'preview' => $this->preview,
	//Sorta...
	/* Du musst doch echt verrückt sein, wenn du versuchst meinen Code zu verstehen ;) */
	//...crazy coder
				'lastReplies' => array_reverse(array_slice($lastReplies, 0, 10)), //Just the last 10 replies
				'smilies' => Main::getModule('BBCode')->getSmilies(),
				'tSmilies' => Functions::getTSmilies()));
			break;

			case 'viewip':
			if(!Main::getModule('Auth')->isAdmin() || !Functions::checkModOfForum($this->forum))
				Main::getModule('Template')->printMessage('no_access');
			break;
		}
		//Always append IDs to WIO location. WIO will not parse them in inapplicable mode.
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode], array('forumID' => $this->forum[0],
			'topicID' => $this->topicID,
			//Just give the template what it needs to know
			'forum' => array('forumID' => $this->forum[0],
				'isBBCode' => $this->forum[7][0] == '1',
				'isXHTML' => $this->forum[7][1] == '1'),
			'errors' => $this->errors), null , ',' . $this->forum[0] . ',' . $this->topicID);
	}

	/**
	 * Sets the current loaded topic on newest position in the topic list of current forum.
	 */
	private function setTopicOnTop()
	{
		$topicIDs = Functions::file('foren/' . $this->forum[0] . '-threads.xbb', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		if(($oldPos = array_search($this->topicID, $topicIDs)) !== false)
			unset($topicIDs[$oldPos]);
		Functions::file_put_contents('foren/' . $this->forum[0] . '-threads.xbb', implode("\n", $topicIDs) . "\n" . $this->topicID . "\n");
	}
}
?>