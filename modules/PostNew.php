<?php
/**
 * Manages post process of new topic or new poll.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class PostNew implements Module
{
	/**
	 * Detected errors during posting actions.
	 *
	 * @var array Error messages
	 */
	private $errors = array();

	/**
	 * Data of target forum to post in.
	 *
	 * @var array|bool Loaded forum data or false
	 */
	private $forum;

	/**
	 * Contains type of new post.
	 *
	 * @var string Posting type
	 */
	private $mode;

	/**
	 * New post being previewed.
	 *
	 * @var bool New post previewed
	 */
	private $preview;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('newtopic' => 'PostNewTopic', 'newpoll' => 'PostNewPoll');

	/**
	 * Data of new post.
	 *
	 * @var array New post data
	 */
	private $newPost;

	/**
	 * Loads various data and sets mode.
	 *
	 * @param string $newType Type of new post
	 * @return PostNew New instance of this class
	 */
	function __construct($newType)
	{
		$this->mode = $newType;
		$this->forum = Functions::getForumData(intval(Functions::getValueFromGlobals('forum_id')));
		$this->preview = Functions::getValueFromGlobals('preview') != '';
		//Get contents for new post
		$this->newPost = array('nick' => htmlspecialchars(trim(Functions::getValueFromGlobals('nli_name'))),
			'title' => htmlspecialchars(trim(Functions::getValueFromGlobals('title'))),
			'post' => htmlspecialchars(trim(Functions::getValueFromGlobals('post'))),
			'tSmiley' => intval(Functions::getValueFromGlobals('tsmilie')),
			'isSmilies' => Functions::getValueFromGlobals('smilies') == '1',
			'isSignature' => Functions::getValueFromGlobals('show_signatur') == '1',
			'isBBCode' => Functions::getValueFromGlobals('use_upbcode') == '1',
			'isXHTML' => Functions::getValueFromGlobals('use_htmlcode') == '1',
			'isNotify' => Functions::getValueFromGlobals('sendmail2') == '1');
		//Topic smiley fix
		if(empty($this->newPost['tSmiley']))
			$this->newPost['tSmiley'] = 1;
	}

	/**
	 * Posts new topic or poll.
	 */
	public function execute()
	{
		//General checks and navbar for every mode
		if($this->forum == false)
			Main::getModule('Template')->printMessage('forum_not_found');
		Main::getModule('NavBar')->addElement($this->forum[1], INDEXFILE . '?mode=viewforum&amp;forum_id=' . $this->forum[0] . SID_AMPER);
		if(Main::getModule('Auth')->isBanned())
			Main::getModule('Template')->printMessage('banned_from_forum');
		//Execute mode
		switch($this->mode)
		{
//PostNewPoll
			case 'newpoll':
			setcookie('upbwhere', INDEXFILE . '?faction=newpoll&forum_id=' . $this->forum[0]); //Redir cookie after login
			//Specific checks and navbar for this mode
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('post_new_poll'), INDEXFILE . '?faction=newpoll&amp;forum_id=' . $this->forum[0] . SID_AMPER);
			if(!Functions::checkUserAccess($this->forum, 3, 9))
				Main::getModule('Template')->printMessage(Main::getModule('Auth')->isLoggedIn() ? 'forum_no_access' : 'login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
			break;

//PostNewTopic
			case 'newtopic':
			setcookie('upbwhere', INDEXFILE . '?faction=newtopic&forum_id=' . $this->forum[0]); //Redir cookie after login
			//Specific checks and navbar for this mode
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('post_new_topic'), INDEXFILE . '?faction=newtopic&amp;forum_id=' . $this->forum[0] . SID_AMPER);
			if(!Functions::checkUserAccess($this->forum, 1, 7))
				Main::getModule('Template')->printMessage(Main::getModule('Auth')->isLoggedIn() ? 'forum_no_access' : 'login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
			//Preview...
			if($this->preview)
				$this->newPost['preview'] = array('title' => &$this->newPost['title'],
					'post' => Main::getModule('BBCode')->parse(Functions::nl2br($this->newPost['post']), $this->newPost['isXHTML'], $this->newPost['isSmilies'], $this->newPost['isBBCode']),
					'signature' => $this->newPost['isSignature'] ? Main::getModule('BBCode')->parse(Main::getModule('Auth')->getUserSig()) : false);
			//...or final save
			elseif(Functions::getValueFromGlobals('save') == 'yes')
			{
				if(empty($this->newPost['title']))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_title');
				if(empty($this->newPost['post']))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_post');
				//Check and set proper nick name
				$this->newPost['nick'] = Main::getModule('Auth')->isLoggedIn() ? Main::getModule('Auth')->getUserID() : (empty($this->newPost['nick']) ? (Main::getModule('Config')->getCfgVal('nli_must_enter_name') == 1 ? false : Main::getModule('Language')->getString('guest')) : '0' . $this->newPost['nick']);
				if($this->newPost['nick'] === false)
					$this->errors[] = Main::getModule('Language')->getString('please_enter_your_user_name');
				if(empty($this->errors))
				{
					//Build topic meta data
					$newTopic = array('1', //Open state
						$this->newPost['title'],
						$this->newPost['nick'],
						$this->newPost['tSmiley'],
						$this->newPost['isNotify'] ? '1' : '0',
						time(),
						'0', //Views
						'', //Poll ID
						'', '', '', '', '',
					//Build first post
						"\n1", //Post ID (incl. another empty unused value from topic meta data)
						$this->newPost['nick'],
						gmdate('YmdHis'),
						//Strip sid parameters from post
						preg_replace('/[?&]sid=[0-9a-z]{32}/si', '', Functions::nl2br($this->newPost['post'])),
						$_SERVER['REMOTE_ADDR'],
						$this->newPost['isSignature'] ? '1' : '0',
						$this->newPost['tSmiley'],
						$this->newPost['isSmilies'] ? '1' : '0',
						$this->newPost['isBBCode'] ? '1' : '0',
						$this->newPost['isXHTML'] ? '1' : '0',
						'', '', "\n");
					$newLastTopicID = Functions::file_get_contents('foren/' . $this->forum[0] . '-ltopic.xbb')+1;
					//Time to write
					Functions::file_put_contents('foren/' . $this->forum[0] . '-threads.xbb', $newLastTopicID . "\n", FILE_APPEND);
					Functions::file_put_contents('foren/' . $this->forum[0] . '-' . $newLastTopicID . '.xbb', Functions::implodeByTab($newTopic));
					Functions::file_put_contents('foren/' . $this->forum[0] . '-ltopic.xbb', $newLastTopicID);
					//Update all the counters and stats
					Functions::updateForumData($this->forum[0], 1, 1, $newLastTopicID, $this->newPost['nick'], $newTopic[15], $this->newPost['tSmiley']);
					if(Main::getModule('Auth')->isLoggedIn())
						Functions::updateUserPostCounter($this->newPost['nick']);
					if($this->forum[10][6] == '1')
					{
						Functions::updateLastPosts($this->forum[0], $newLastTopicID, $this->newPost['nick'], $newTopic[15], $this->newPost['tSmiley']);
						Functions::updateTodaysPosts($this->forum[0], $newLastTopicID, $this->newPost['nick'], $newTopic[15], $this->newPost['tSmiley']);
					}
					//Notify mods
					if($this->forum[7][2] == '1')
						foreach(array_map(array('Functions', 'getUserData'), Functions::explodeByComma($this->forum[11])) as $curMod)
							Functions::sendMessage($curMod[3], 'notify_mod', $curMod[0], Main::getModule('Config')->getCfgVal('address_to_forum') . '/' . INDEXFILE . '?faction=readforum&mode=viewthread&forum_id=' . $this->forum[0] . '&thread=' . $newLastTopicID);
					//Done
					Main::getModule('Logger')->log('New topic (' . $this->forum[0] . ',' . $newLastTopicID . ') posted by %s', LOG_NEW_POSTING);
					Main::getModule('Template')->printMessage('topic_posted', INDEXFILE . '?mode=viewthread&amp;forum_id=' . $this->forum[0] . '&amp;thread=' . $newLastTopicID . SID_AMPER, INDEXFILE . '?mode=viewforum&amp;forum_id=' . $this->forum[0] . SID_AMPER, INDEXFILE . SID_QMARK);
				}
			}
			break;
		}
		//Always append IDs to WIO location. WIO will not parse them in inapplicable mode.
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode], array(
			'newPost' => $this->newPost,
			//Just give the template what it needs to know
			'forum' => array('forumID' => $this->forum[0],
				'isBBCode' => $this->forum[7][0] == '1',
				'isXHTML' => $this->forum[7][1] == '1'),
			'preview' => $this->preview,
			'errors' => $this->errors,
			'smilies' => Main::getModule('BBCode')->getSmilies(),
			'tSmilies' => Functions::getTSmilies()), null , ',' . $this->forum[0]);
	}
}
?>