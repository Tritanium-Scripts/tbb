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
	private $catTable = array();

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
		'newforum' => 'AdminForumNewForum',
		'change' => 'AdminForumEditForum',
		'AdminForumDeleteForum' => 'AdminForumDeleteForum',
		'edit_forum_rights' => 'AdminForumSpecialRights',
		'new_user_right' => 'AdminForumNewUserRight');

	/**
	 * Sets mode and prepares category translation table.
	 * 
	 * @param string $mode
	 * @return AdminForum New instance of this class
	 */
	function __construct($mode)
	{
		$this->mode = $mode;
		$this->catTable[-1] = Main::getModule('Language')->getString('no_category');
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
			$newIsBBCode = Functions::getValueFromGlobals('upbcode');
			$newIsXHTML = Functions::getValueFromGlobals('htmlcode');
			$newIsNotify = Functions::getValueFromGlobals('sm_mods');
			$newRights = (array) Functions::getValueFromGlobals('new_rights') + array_fill(0, 10, ''); //Fill up missing keys/values from unchecked boxes with this neat array union trick :)
			$newModIDs = array_map('trim', Functions::explodeByComma(Functions::getValueFromGlobals('mods')));
			if(Functions::getValueFromGlobals('create') == 'yes')
			{
				if($newName == '')
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_forum_name');
				//Allow no description
				else
				{
					ksort($newRights);
					//Check user states of given mod IDs and fix them, if needed
					foreach(array_map(array('Functions', 'getUserData'), $newModIDs) as $curUser)
						if($curUser != false && $curUser[4] != -1 && !in_array($curUser[1], $this->getModIDs()))
						{
							$curUser[4] = 2;
							Functions::file_put_contents('members/' . $curUser[1] . '.xbb', implode("\n", $curUser));
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
				'newIsBBCode' => $newIsBBCode == '1',
				'newIsXHTML' => $newIsXHTML == '1',
				'newIsNotify' => $newIsNotify == '1',
				'newRights' => $newRights,
				'newModIDs' => $newModIDs));
			break;

//AdminForumEditForum
			case 'change':
			$forumID = intval(Functions::getValueFromGlobals('ad_forum_id'));
			Main::getModule('NavBar')->addElement(array(
				array(Main::getModule('Language')->getString('manage_forums'), INDEXFILE . '?faction=ad_forum&amp;mode=forumview' . SID_AMPER),
				array(Main::getModule('Language')->getString('edit_forum'), INDEXFILE . '?faction=ad_forum&amp;ad_forum_id=' . $forumID . '&amp;mode=change' . SID_AMPER)));
			//Get forum to edit
			if(($key = array_search($forumID, array_map('current', $this->forums))) === false)
				Main::getModule('Template')->printMessage('forum_not_found');
			$editForum = &$this->forums[$key];
			$editForum[7] = Functions::explodeByComma($editForum[7]);
			$editForum[10] = Functions::explodeByComma($editForum[10]);
			$editForum[11] = Functions::explodeByComma($editForum[11]);
			if(Functions::getValueFromGlobals('change') == 'yes')
			{
				//Delete forum?
				if(Functions::getValueFromGlobals('kill') != '')
				{
					//Confirmed?
					if(Functions::getValueFromGlobals('confirm') == 'yes')
					{
						//Let's rock! :D
						//Remove from forum index
						unset($this->forums[$key]);
						Functions::file_put_contents('vars/foren.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $this->forums)) . "\n");
						//Update groups with data from special rights file
						/*
						$groups = array_map(array('Functions', 'explodeByTab'), Functions::file('vars/groups.var'));
						foreach(array_map(array('Functions', 'explodeByTab'), Functions::file('foren/' . $forumID . '-rights.xbb')) as $curSpecialRight)
							if($curSpecialRight[1] == '2')
								foreach($groups as $curGroup)
									if($curGroup[0] == $curSpecialRight[2])
									{
										$curGroup[5] = Functions::explodeByComma($curGroup[5]);
										foreach(Functions::explodeByComma($curGroup[5]) as $cur)
										break;
									}
						*/
						//Delete topics
						$sizeCounter = $fileCounter = 0;
						foreach(Functions::file('foren/' . $forumID . '-threads.xbb') as $curTopicID)
						{
							//Delete possible poll
							$curTopic = Functions::explodeByTab(current(Functions::file('foren/' . $forumID . '-' . $curTopicID . '.xbb', null, null, false)));
							if(!empty($curTopic[7]))
							{
								$sizeCounter += Functions::unlink('polls/' . $curTopic[7] . '-1.xbb');
								$sizeCounter += Functions::unlink('polls/' . $curTopic[7] . '-2.xbb');
								$fileCounter += 2;
							}
							//Delete topic
							$sizeCounter += Functions::unlink('foren/' . $forumID . '-' . $curTopicID . '.xbb');
							$fileCounter++;
						}
						//Delete links of moved topics (if any)
						foreach(glob(DATAPATH . 'foren/' . $forumID . '-[0-9]*.xbb') as $curMovedTopic)
						{
							$sizeCounter += Functions::unlink($curMovedTopic, false);
							$fileCounter++;
						}
						//Delete forum data
						$sizeCounter += Functions::unlink('foren/' . $forumID . '-threads.xbb');
						$sizeCounter += Functions::unlink('foren/' . $forumID . '-ltopic.xbb');
						$fileCounter += 2;
						if(Functions::file_exists('foren/' . $forumID . '-rights.xbb'))
						{
							$sizeCounter += Functions::unlink('foren/' . $forumID . '-rights.xbb');
							$fileCounter++;
						}
						if(Functions::file_exists('foren/' . $forumID . '-sticker.xbb'))
						{
							$sizeCounter += Functions::unlink('foren/' . $forumID . '-sticker.xbb');
							$fileCounter++;
						}
						//Check mods (if any)
						foreach($editForum[11] as $curModID)
							//By deleting forum from index, all mod IDs won't list them either
							if(!in_array($curModID, $this->getModIDs()) && !empty($curModID))
							{
								$curUser = Functions::getUserData($curModID);
								if($curUser[4] == 1)
									continue;
								$curUser[4] = 3;
								$curUser[14] = implode(',', $curUser[14]);
								Functions::file_put_contents('members/' . $curModID . '.xbb', implode("\n", $curUser));
							}
						//Done
						Main::getModule('Logger')->log('%s deleted forum (ID: ' . $forumID . ')', LOG_ACP_ACTION);
						Main::getModule('Template')->printMessage('forum_deleted_freed_x_in_xxx', $sizeCounter/1024, $fileCounter, $editForum[4], $editForum[3]);
					}
					//Get confirmation
					else
					{
						Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('delete_forum'));
						$this->mode = 'AdminForumDeleteForum';
					}
				}
				//Normal edit
				else
				{
					$editForum[1] = htmlspecialchars(trim(Functions::getValueFromGlobals('titel')));
					$editForum[2] = htmlspecialchars(trim(Functions::getValueFromGlobals('description')));
					$editForum[5] = intval(Functions::getValueFromGlobals('kg'));
					$editForum[7][0] = Functions::getValueFromGlobals('upbcode');
					$editForum[7][1] = Functions::getValueFromGlobals('htmlcode');
					$editForum[7][2] = Functions::getValueFromGlobals('sm_mods');
					$editForum[10] = (array) Functions::getValueFromGlobals('new_rights') + array_fill(0, 10, ''); //Fill up missing keys/values from unchecked boxes with this neat array union trick :)
					ksort($editForum[10]);
					if(empty($editForum[1]))
						$this->errors[] = Main::getModule('Language')->getString('please_enter_a_forum_name');
					else
					{
						$editModIDs = array_map('trim', Functions::explodeByComma(Functions::getValueFromGlobals('mods')));
						//Adjust new mod user rankings
						if($editForum[11] != $editModIDs)
						{
							$allModIDs = $this->getModIDs();
							//#1 Get IDs of mods to downgrade rank
							foreach(array_diff($editForum[11], $editModIDs) as $curModID)
							{
								if(empty($curModID))
									continue;
								//Kick out removed mod one time from all known mods
								unset($allModIDs[array_search($curModID, $allModIDs)]);
								//Verify they need to be downgraded aka being no mod somewhere else
								if(!in_array($curModID, $allModIDs))
								{
									$curUser = Functions::getUserData($curModID);
									if($curUser[4] == 1)
										continue;
									$curUser[4] = 3;
									$curUser[14] = implode(',', $curUser[14]);
									Functions::file_put_contents('members/' . $curModID . '.xbb', implode("\n", $curUser));
								}
							}
							//#2 Get IDs of users to upgrade rank
							foreach(array_diff($editModIDs, $editForum[11]) as $curModID)
								//Verify they need to be upgraded aka not being mod somehwere else already
								if(!in_array($curModID, $allModIDs) && !empty($curModID))
								{
									$curUser = Functions::getUserData($curModID);
									if($curUser[4] == 1)
										continue;
									$curUser[4] = 2;
									$curUser[14] = implode(',', $curUser[14]);
									Functions::file_put_contents('members/' . $curModID . '.xbb', implode("\n", $curUser));
								}
						}
						$editForum[11] = $editModIDs;
						$editForum[7] = implode(',', $editForum[7]);
						$editForum[10] = implode(',', $editForum[10]);
						$editForum[11] = implode(',', $editForum[11]);
						Functions::file_put_contents('vars/foren.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $this->forums)) . "\n");
						//Done
						Main::getModule('Logger')->log('%s edited forum (ID: ' . $forumID . ')', LOG_ACP_ACTION);
						header('Location: ' . INDEXFILE . '?faction=ad_forum&mode=forumview' . SID_AMPER_RAW);
						Main::getModule('Template')->printMessage('forum_edited');
					}
				}
			}
			Main::getModule('Template')->assign(array('editID' => $forumID,
				'editName' => $editForum[1],
				'editDescr' => $editForum[2],
				'editCatID' => $editForum[5],
				'editOptions' => $editForum[7],
				'editRights' => $editForum[10],
				'editModIDs' => $editForum[11]));
			break;

			case 'moveforumup':
			$forumID = intval(Functions::getValueFromGlobals('id'));
			//Get forum to edit
			if(($key = array_search($forumID, array_map('current', $this->forums))) === false)
				Main::getModule('Template')->printMessage('forum_not_found');
			//Already on top?
			if($key != 0)
			{
				//Credits for this nice var swapping idea goes to hasin:
				//http://booleandreams.wordpress.com/2008/07/30/how-to-swap-values-of-two-variables-without-using-a-third-variable/#comment-10486
				list($this->forums[$key], $this->forums[$key-1]) = array($this->forums[$key-1], $this->forums[$key]);
				Functions::file_put_contents('vars/foren.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $this->forums)) . "\n");
			}
			header('Location: ' . INDEXFILE . '?faction=ad_forum&mode=forumview' . SID_AMPER_RAW);
			Main::getModule('Template')->printMessage('forum_moved');
			break;

#  )   ___                                   ______                              ______                  #
# (__/_____) /)       ,                     (, /    )     /) /)        ,        (, /    )             /) #
#   /       (/   __     _   _      __/        /---(      // //  _ _/_   __        /---(  ____   __  _(/  #
#  /        / )_/ (__(_/_)_/_)_(_/_ /(__   ) / ____)(_(_(/_(/__(/_(___(_/ (_   ) / ____)(_)(_(_/ (_(_(_  #
# (______)                    .-/  /      (_/ (                               (_/ (                      #
#                            (_/                                                                         #

			case 'moveforumdown':
			$forumID = intval(Functions::getValueFromGlobals('id'));
			//Get forum to edit
			if(($key = array_search($forumID, array_map('current', $this->forums))) === false)
				Main::getModule('Template')->printMessage('forum_not_found');
			//Already at bottom?
			if($key != count($this->forums)-1)
			{
				//Credits for this nice var swapping idea goes to hasin:
				//http://booleandreams.wordpress.com/2008/07/30/how-to-swap-values-of-two-variables-without-using-a-third-variable/#comment-10486
				list($this->forums[$key], $this->forums[$key+1]) = array($this->forums[$key+1], $this->forums[$key]);
				Functions::file_put_contents('vars/foren.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $this->forums)) . "\n");
			}
			header('Location: ' . INDEXFILE . '?faction=ad_forum&mode=forumview' . SID_AMPER_RAW);
			Main::getModule('Template')->printMessage('forum_moved');
			break;

//AdminForumSpecialRights
			case 'edit_forum_rights':
			$forumID = intval(Functions::getValueFromGlobals('forum_id'));
			Main::getModule('NavBar')->addElement(array(
				array(Main::getModule('Language')->getString('manage_forums'), INDEXFILE . '?faction=ad_forum&amp;mode=forumview' . SID_AMPER),
				array(Main::getModule('Language')->getString('edit_forum'), INDEXFILE . '?faction=ad_forum&amp;ad_forum_id=' . $forumID . '&amp;mode=change' . SID_AMPER),
				array(Main::getModule('Language')->getString('edit_special_rights'), INDEXFILE . '?faction=ad_forum&amp;mode=edit_forum_rights&amp;forum_id=' . $forumID . SID_AMPER)));
			//Check for valid forum ID
			if(!in_array($forumID, array_map('current', $this->forums)))
				Main::getModule('Template')->printMessage('forum_not_found');
			//Get special rights or create new
			$specialRights = @Functions::file('foren/' . $forumID . '-rights.xbb') or $specialRights = array();
			#0:rightID - 1:rightType - 2:user/groupID - 3:isAccessForum - 4:isPostTopics - 5:isPostReplies - 6:isPostPolls - 7:isEditOwnPosts - 8:isEditOwnPolls
			$specialRights = array_map(array('Functions', 'explodeByTab'), $specialRights);
			if(Functions::getValueFromGlobals('change') == 'yes' && ($newRights = Functions::getValueFromGlobals('new_rights')) != '')
			{
				foreach($specialRights as &$curSpecialRight)
				{
					//Only consider changes to special rights if the first option to access a forum is set
					if(isset($newRights[$curSpecialRight[0]][0]))
					{
						$newRights[$curSpecialRight[0]] += array_fill(1, 5, ''); //Fill up missing indices aka unchecked options
						list($curSpecialRight[3], $curSpecialRight[4], $curSpecialRight[5], $curSpecialRight[6], $curSpecialRight[7], $curSpecialRight[8]) = array($newRights[$curSpecialRight[0]][0], $newRights[$curSpecialRight[0]][1], $newRights[$curSpecialRight[0]][2], $newRights[$curSpecialRight[0]][3], $newRights[$curSpecialRight[0]][4], $newRights[$curSpecialRight[0]][5]);
					}
					$curSpecialRight = Functions::implodeByTab($curSpecialRight);
				}
				Functions::file_put_contents('foren/' . $forumID . '-rights.xbb', implode("\n", $specialRights) . "\n");
				Main::getModule('Logger')->log('%s edited special rights of forum (ID: ' . $forumID . ')', LOG_ACP_ACTION);
				//Undo implode for template
				$specialRights = array_map(array('Functions', 'explodeByTab'), $specialRights);
			}
			//Get names for user/group IDs and split them by right type
			$specialUserRights = $specialGroupRights = array();
			foreach($specialRights as &$curSpecialRight)
			{
				if($curSpecialRight[1] == 1)
					$specialUserRights[] = $curSpecialRight + array('idName' => Functions::getProfileLink($curSpecialRight[2], true));
				elseif($curSpecialRight[1] == 2)
					$specialGroupRights[] = $curSpecialRight + array('idName' => next(Functions::getGroupData($curSpecialRight[2])));
			}
			Main::getModule('Template')->assign(array('forumID' => $forumID,
				'specialUserRights' => $specialUserRights,
				'specialGroupRights' => $specialGroupRights));
			break;

//AdminForumSpecialRights
			case 'new_user_right':
			$forumID = intval(Functions::getValueFromGlobals('forum_id'));
			Main::getModule('NavBar')->addElement(array(
				array(Main::getModule('Language')->getString('manage_forums'), INDEXFILE . '?faction=ad_forum&amp;mode=forumview' . SID_AMPER),
				array(Main::getModule('Language')->getString('edit_forum'), INDEXFILE . '?faction=ad_forum&amp;ad_forum_id=' . $forumID . '&amp;mode=change' . SID_AMPER),
				array(Main::getModule('Language')->getString('edit_special_rights'), INDEXFILE . '?faction=ad_forum&amp;mode=edit_forum_rights&amp;forum_id=' . $forumID . SID_AMPER),
				array(Main::getModule('Language')->getString('add_new_special_user_right'), INDEXFILE . '?faction=ad_forum&amp;mode=new_user_right&amp;forum_id=' . $forumID . SID_AMPER)));
			//Check for valid forum ID
			if(($key = array_search($forumID, array_map('current', $this->forums))) === false)
				Main::getModule('Template')->printMessage('forum_not_found');
			if(Functions::getValueFromGlobals('change') == 'yes')
			{
				//Get special rights or create new
				$specialRights = @Functions::file('foren/' . $forumID . '-rights.xbb') or $specialRights = array();
				$specialUserIDs = array_map(create_function('$right', 'return $right[1] == 1 ? $right[2] : 0;'), $specialRights = array_map(array('Functions', 'explodeByTab'), $specialRights));
				//Get new user IDs and rights to add
				$newUserIDs = array_unique(Functions::explodeByComma(Functions::getValueFromGlobals('new_user_ids')));
				$newUserRights = Functions::getValueFromGlobals('new_right') + array_fill(0, 6, '');
				ksort($newUserRights);
				//Filter out invalid IDs
				foreach($newUserIDs as $curKey => $curUserID)
					if($curUserID == 0 || !Functions::file_exists('members/' . $curUserID . '.xbb') || in_array($curUserID, $specialUserIDs))
						unset($newUserIDs[$curKey]);
				//Add new valid rights
				$newSpecialRightID = empty($specialRights) ? 1 : current(end($specialRights))+1;
				$toAppend = '';
				foreach($newUserIDs as $curUserID)
					$toAppend .= $newSpecialRightID++ . "\t1\t" . $curUserID . "\t" . Functions::implodeByTab($newUserRights) . "\t\t\t\t\t\t\n";
				Functions::file_put_contents('foren/' . $forumID . '-rights.xbb', $toAppend, FILE_APPEND);
				//Done
				Main::getModule('Logger')->log('%s added new special user right(s) for forum (ID: ' . $forumID . ')', LOG_ACP_ACTION);
				header('Location: ' . INDEXFILE . '?faction=ad_forum&mode=edit_forum_rights&forum_id=' . $forumID . SID_AMPER_RAW);
				Main::getModule('Template')->printMessage('special_right_added');
			}
			Main::getModule('Template')->assign(array('forumID' => $forumID,
				'forumRights' => array_map(create_function('$right', 'return $right == 1;'), Functions::explodeByComma($this->forums[$key][10]))));
			break;
		}
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode], array('catTable' => $this->catTable,
			'errors' => $this->errors));
	}

	/**
	 * Returns all user IDs with moderator positons.
	 *
	 * @return array Moderator IDs of entire borad
	 */
	private function getModIDs()
	{
		return array_filter(Functions::explodeByComma(implode(',', array_map(create_function('$forum', 'return implode(\',\', (array) $forum[11]);'), $this->forums))), 'is_numeric');
	}
}
?>