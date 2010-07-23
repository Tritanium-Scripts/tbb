<?php
/**
 * 
 */
class Post implements Module
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
	 * @var array|bool Loaded forum data
	 */
	private $forum;

	/**
	 * 
	 *
	 * @var string 
	 */
	private $mode;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('newtopic' => 'PostNewTopic');

	function __construct($mode)
	{
		$this->mode = $mode;
		$this->forum = Functions::getForumData(intval(Functions::getValueFromGlobals('forum_id')));
	}

	/**
	 * 
	 */
	public function execute()
	{
		if($this->forum == false)
			Main::getModule('Template')->printMessage('forum_not_found');
		Main::getModule('NavBar')->addElement($this->forum[1], INDEXFILE . '?mode=viewforum&amp;forum_id=' . $this->forum[0] . SID_AMPER);
		if(!Main::getModule('Auth')->isLoggedIn())
		{
			if($this->forum[10][7] != '1')
				Main::getModule('Template')->printMessage('login_only', INDEXFILE . '?faction=register' . SID_AMPER, INDEXFILE . '?faction=login' . SID_AMPER);
		}
		elseif(Main::getModule('Auth')->isBanned())
			Main::getModule('Template')->printMessage('banned_from_forum');
		elseif(!Functions::checkUserAccess($this->forum, 1))
			Main::getModule('Template')->printMessage('forum_no_access');
		switch($this->mode)
		{
//PostNewTopic
			case 'newtopic':
			setcookie('upbwhere', INDEXFILE . '?faction=newtopic&forum_id=' . $this->forum[0]);
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('post_new_topic'), INDEXFILE . '?faction=newtopic&amp;forum_id=' . $this->forum[0] . SID_AMPER);
			//Preview...
			if(Functions::getValueFromGlobals('preview') != '')
			{
				
			}
			//...or final save
			elseif(Functions::getValueFromGlobals('save') == 'yes')
			{
				
			}
			Main::getModule('Template')->assign(array('preview' => Functions::getValueFromGlobals('preview')));
			break;
		}
		//Always append IDs to WIO location. WIO will not parse them in inapplicable mode.
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode], array('forum' => $this->forum,
			'errors' => $this->errors,
			'smilies' => Main::getModule('BBCode')->getSmilies()), null , ',' . $this->forum[0]);
	}
}
?>