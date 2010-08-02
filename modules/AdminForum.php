<?php
/**
 * Manages categories and forums incl. special rights.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class AdminForum implements Module
{
	/**
	 * Translates a category ID to its name.
	 * 
	 * @var array Cat IDs and name counterparts
	 */
	private $catTable = array(-1 => '');

	/**
	 * Detected errors during cat / forum actions.
	 *
	 * @var array Error messages
	 */
	private $errors = array();

	/**
	 * Available forums.
	 *
	 * @var array All current forums
	 */
	private $forums;

	/**
	 * Contains mode to execute.
	 *
	 * @var string Mode
	 */
	private $mode;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('ad_forum' => 'AdminForum',
		'forumview' => 'AdminForumIndex',
		'newforum' => 'AdminForumNewForum');

	/**
	 * Sets mode and prepares category translation table.
	 * 
	 * @param string $mode
	 * @return AdminForum New instance of this class
	 */
	function __construct($mode)
	{
		$this->mode = $mode;
		foreach(array_map(array('Functions', 'explodeByTab'), Functions::file('vars/kg.var')) as $curCat)
			$this->catTable[$curCat[0]] = $curCat[1];
		$this->forums = array_map(array('Functions', 'explodeByTab'), Functions::file('vars/foren.var'));
	}

	/**
	 * Executes mode.
	 */
	public function execute()
	{
		Functions::accessAdminPanel();
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('manage_forums_categories'), INDEXFILE . '?faction=ad_forum' . SID_AMPER);
		switch($this->mode)
		{
//AdminForumIndex
			case 'forumview':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('manage_forums'), INDEXFILE . '?faction=ad_forum&amp;mode=forumview' . SID_AMPER);
			$forums = array();
			foreach($this->forums as $curForum)
				$forums[] = array('id' => $curForum[0],
					'name' => $curForum[1],
					'descr' => $curForum[2],
					'catID' => $curForum[5],
					'mods' => Functions::getProfileLink($curForum[11]));
			Main::getModule('Template')->assign('forums', $forums);
			break;

//AdminForumNewForum
			case 'newforum':
			Main::getModule('NavBar')->addElement(array(
				array(Main::getModule('Language')->getString('manage_forums'), INDEXFILE . '?faction=ad_forum&amp;mode=forumview' . SID_AMPER),
				array(Main::getModule('Language')->getString('add_new_forum'), INDEXFILE . '?faction=ad_forum&amp;mode=newforum' . SID_AMPER)));
			$newName = htmlspecialchars(trim(Functions::getValueFromGlobals('titel')));
			$newDescr = htmlspecialchars(trim(Functions::getValueFromGlobals('description')));
			$newCatID = intval(Functions::getValueFromGlobals('kg'));
			$newModIDs = array_map('trim', Functions::explodeByComma(Functions::getValueFromGlobals('mods')));
			$newIsBBCode = Functions::getValueFromGlobals('upbcode');
			$newIsXHTML = Functions::getValueFromGlobals('htmlcode');
			$newRights = (array) Functions::getValueFromGlobals('new_rights') + array_fill(0, 10, ''); //Fill up missing keys/values from unchecked boxes with this neat array union trick :)
			$newIsNotify = Functions::getValueFromGlobals('sm_mods');
			if(Functions::getValueFromGlobals('create') == 'yes')
			{
				if($newName == '')
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_forum_name');
				//Allow no description
				else
				{
					//Check user states of given mod IDs and fix them, if needed
					foreach(array_map(array('Functions', 'getUserData'), $newModIDs) as $curUser)
					{
						if($curUser != false && $curUser[4] != -1 && !in_array($curUser[1], array_map(create_function('$forum', 'return Functions::explodeByComma($forum[11]);'), $this->forums)))
						{
							$curUser[4] = 2;
							Functions::file_put_contents('members/' . $curUser[1] . '.xbb', implode("\n", $curUser));
						}
					}
					//Get and update newest forum ID
					Functions::file_put_contents('vars/forens.var', $newForumID = Functions::file_get_contents('vars/forens.var')+1);
					//Build forum data
					$newForum = array($newForumID,
						$newName,
						$newDescr,
						0, //Topics
						0, //Posts
						$newCatID,
						'', //Timestamp of last post
						$newIsBBCode . ',' . $newIsXHTML . ',' . $newIsNotify,
						'', //Status?
						'', //Last post
						implode(',', $newRights),
						implode(',', $newModIDs),
						'', '', '', "\n");
					//Write all the data
					Functions::file_put_contents('foren/' . $newForumID . '-ltopic.xbb', '0');
					Functions::file_put_contents('foren/' . $newForumID . '-threads.xbb', '');
					Functions::file_put_contents('vars/foren.var', Functions::implodeByTab($newForum), FILE_APPEND);
					//Done
					Main::getModule('Logger')->log('%s created new forum (ID: ' . $newForumID . ')', LOG_ACP_ACTION);
					header('Location: ' . INDEXFILE . '?faction=ad_forum&mode=forumview' . SID_AMPER_RAW);
					Main::getModule('Template')->printMessage('new_forum_added');
				}
			}
			else
			{
				//Set default settings
				$newRights = array_fill(0, 7, true) + array_fill(7, 3, false);
				$newIsBBCode = true;
				$newIsXHTML = $newIsNotify = false;
			}
			Main::getModule('Template')->assign(array('newName' => $newName,
				'newDescr' => $newDescr,
				'newCatID' => $newCatID,
				'newModIDs' => $newModIDs,
				'newRights' => $newRights,
				'newIsBBCode' => $newIsBBCode == '1',
				'newIsXHTML' => $newIsXHTML == '1',
				'newIsNotify' => $newIsNotify == '1'));
			break;

			case 'new_user_right':
			break;
		}
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode], array('catTable' => $this->catTable,
			'errors' => $this->errors));
	}
}
?>