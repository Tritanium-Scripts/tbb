<?php
/**
 * Displays specific or all forums.
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
		//Manage cookies
		switch($this->mode)
		{
			case 'viewforum':
			setcookie('upbwhere', INDEXFILE . '?mode=viewforum&forum_id=' . $this->forumID); //Redir cookie after login
			setcookie('forum.' . $this->forumID, time(), time()+60*60*24*365, Main::getModule('Config')->getCfgVal('path_to_forum')); //Cookie to detect last visit
			break;

			case 'viewthread':
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
			setcookie('upbwhere', INDEXFILE);
			break;
		}
		Main::getModule('Template')->printPage('Forum');
	}
}
?>