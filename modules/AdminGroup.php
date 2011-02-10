<?php
/**
 * Manages user groups.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class AdminGroup implements Module
{
	/**
	 * Detected errors during group actions.
	 *
	 * @var array Error messages
	 */
	private $errors = array();

	/**
	 * ID of current group.
	 *
	 * @var int Group ID
	 */
	private $groupID;

	/**
	 * Existing groups.
	 *
	 * @var array Available groups
	 */
	private $groups;

	/**
	 * Contains mode to execute.
	 *
	 * @var string Group mode
	 */
	private $mode;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('ad_groups' => 'AdminGroup',
		'new' => 'AdminGroupNewGroup',
		'edit' => 'AdminGroupEditGroup',
		'kill' => 'AdminGroupDeleteGroup');

	/**
	 * Sets mode, group ID and loads all groups.
	 *
	 * @param string $mode Group mode
	 * @return AdminGroup New instance of this class
	 */
	function __construct($mode)
	{
		$this->mode = $mode;
		$this->groupID = intval(Functions::getValueFromGlobals('group_id'));
		$this->groups = array_map(array('Functions', 'explodeByTab'), Functions::file('vars/groups.var'));
		foreach($this->groups as &$curGroup)
			$curGroup[3] = Functions::explodeByComma($curGroup[3]);
	}

	/**
	 * Executes mode.
	 */
	public function execute()
	{
		Functions::accessAdminPanel();
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('manage_groups'), INDEXFILE . '?faction=ad_groups' . SID_AMPER);
		switch($this->mode)
		{
//AdminGroupNewGroup
			case 'new':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('add_new_group'), INDEXFILE . '?faction=ad_groups&amp;mode=new' . SID_AMPER);
			$newName = htmlspecialchars(trim(Functions::getValueFromGlobals('title')));
			$newAvatar = Functions::getValueFromGlobals('pic');
			$newUserIDs = array_unique(array_filter(array_map('trim', Functions::explodeByComma(Functions::getValueFromGlobals('group_members'))), 'is_numeric'));
			if(Functions::getValueFromGlobals('create') == 'yes')
			{
				if(empty($newName))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_group_name');
				else
				{
					$this->groupID = current(end($this->groups))+1;
					foreach($newUserIDs as $curKey => $curUserID)
					{
						//Don't add to group if user is invalid or already belongs to a group
						if(($curUser = Functions::getUserData($curUserID)) == false || !empty($curUser[15]))
							unset($newUserIDs[$curKey]);
						else
						{
							//Add group to user
							$curUser[15] = $this->groupID;
							$curUser[14] = implode(',', $curUser[14]);
							$curUser[19] = Functions::implodeByTab($curUser[19]);
							Functions::file_put_contents('members/' . $curUser[1] . '.xbb', implode("\n", $curUser));
						}
					}
					Functions::file_put_contents('vars/groups.var', $this->groupID . "\t" . $newName . "\t" . $newAvatar . "\t" . implode(',', $newUserIDs) . "\t\t\t\t\t\t\t\t\t\t\n", FILE_APPEND);
					Main::getModule('Logger')->log('%s created new group (ID: ' . $this->groupID . ')', LOG_ACP_ACTION);
					header('Location: ' . INDEXFILE . '?faction=ad_groups' . SID_AMPER_RAW);
					Main::getModule('Template')->printMessage('group_created');
				}
			}
			Main::getModule('Template')->assign(array('newName' => $newName,
				'newAvatar' => $newAvatar,
				'newUserIDs' => implode(',', $newUserIDs)));
			break;

//AdminGroupEditGroup
			case 'edit':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('edit_group'), INDEXFILE . '?faction=ad_groups&amp;mode=edit&amp;group_id=' . $this->groupID . SID_AMPER);
			if(($key = array_search($this->groupID, array_map('current', $this->groups))) === false)
				Main::getModule('Template')->printMessage('group_not_found');
			$editName = htmlspecialchars(trim(Functions::getValueFromGlobals('title')));
			$editAvatar = Functions::getValueFromGlobals('pic');
			$editUserIDs = array_unique(array_filter(array_map('trim', Functions::explodeByComma(Functions::getValueFromGlobals('group_members'))), 'is_numeric'));
			if(Functions::getValueFromGlobals('update') == 'yes')
			{
				if(empty($editName))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_group_name');
				else
				{
					//Update members
					#1 Remove from group
					foreach(array_diff($this->groups[$key][3], $editUserIDs) as $curUserID)
						if(($curUser = Functions::getUserData($curUserID)) != false)
						{
							$curUser[15] = '';
							$curUser[14] = implode(',', $curUser[14]);
							$curUser[19] = Functions::implodeByTab($curUser[19]);
							Functions::file_put_contents('members/' . $curUser[1] . '.xbb', implode("\n", $curUser));
						}
					#2 Add to group
					foreach(array_diff($editUserIDs, $this->groups[$key][3]) as $curUserID)
						//Don't add to group if user is invalid or already belongs to a group
						if(($curUser = Functions::getUserData($curUserID)) == false || !empty($curUser[15]))
							unset($editUserIDs[array_search($curUserID, $editUserIDs)]);
						else
						{
							//Add group to user
							$curUser[15] = $this->groupID;
							$curUser[14] = implode(',', $curUser[14]);
							$curUser[19] = Functions::implodeByTab($curUser[19]);
							Functions::file_put_contents('members/' . $curUser[1] . '.xbb', implode("\n", $curUser));
						}
					//Update group
					$this->groups[$key][1] = $editName;
					$this->groups[$key][2] = $editAvatar;
					$this->groups[$key][3] = $editUserIDs;
					foreach($this->groups as &$curGroup)
						$curGroup[3] = implode(',', $curGroup[3]);
					Functions::file_put_contents('vars/groups.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $this->groups)) . "\n");
					//Done
					Main::getModule('Logger')->log('%s edited group (ID: ' . $this->groupID . ')', LOG_ACP_ACTION);
					header('Location: ' . INDEXFILE . '?faction=ad_groups' . SID_AMPER_RAW);
					Main::getModule('Template')->printMessage('group_edited');
				}
			}
			else
			{
				$editName = $this->groups[$key][1];
				$editAvatar = $this->groups[$key][2];
				$editUserIDs = !empty($this->groups[$key][3][0]) ? $this->groups[$key][3] : array();
			}
			Main::getModule('Template')->assign(array('groupID' => $this->groupID,
				'editName' => $editName,
				'editAvatar' => $editAvatar,
				'editUserIDs' => implode(',', $editUserIDs)));
			break;

//AdminGroupDeleteGroup
			case 'kill':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('delete_group'), INDEXFILE . '?faction=ad_groups&amp;mode=kill&amp;group_id=' . $this->groupID . SID_AMPER);
			if(($key = array_search($this->groupID, array_map('current', $this->groups))) === false)
				Main::getModule('Template')->printMessage('group_not_found');
			if(Functions::getValueFromGlobals('kill') == 'yes')
			{
				//Delete group from members
				foreach($this->groups[$key][3] as $curUserID)
					if(($curUser = Functions::getUserData($curUserID)) != false)
					{
						$curUser[15] = '';
						$curUser[14] = implode(',', $curUser[14]);
						$curUser[19] = Functions::implodeByTab($curUser[19]);
						Functions::file_put_contents('members/' . $curUser[1] . '.xbb', implode("\n", $curUser));
					}
				//Delete group from forum special rights
				foreach(Functions::explodeByComma($this->groups[$key][5]) as $curForumID)
				{
					if(empty($curForumID))
						continue;
					$curSpecialRights = Functions::file('foren/' . $curForumID . '-rights.xbb');
					foreach($curSpecialRights as $curKey => $curSpecialRight)
					{
						$curSpecialRight = Functions::explodeByTab($curSpecialRight);
						if($curSpecialRight[1] == '2' && $curSpecialRight[2] == $this->groupID)
						{
							unset($curSpecialRights[$curKey]);
							Functions::file_put_contents('foren/' . $curForumID . '-rights.xbb', implode("\n", $curSpecialRights) . "\n");
							break;
						}
					}
				}
				//Delete group
				unset($this->groups[$key]);
				foreach($this->groups as &$curGroup)
					$curGroup[3] = implode(',', $curGroup[3]);
				Functions::file_put_contents('vars/groups.var', empty($this->groups) ? '' : implode("\n", array_map(array('Functions', 'implodeByTab'), $this->groups)) . "\n");
				//Done
				Main::getModule('Logger')->log('%s deleted group (ID: ' . $this->groupID . ')', LOG_ACP_ACTION);
				header('Location: ' . INDEXFILE . '?faction=ad_groups' . SID_AMPER_RAW);
				Main::getModule('Template')->printMessage('group_deleted');
			}
			Main::getModule('Template')->assign(array('groupID' => $this->groupID,
				'groupName' => $this->groups[$key][1]));
			break;

//AdminGroup
			default:
			//Get names from IDs
			foreach($this->groups as &$curGroup)
				$curGroup[3] = !empty($curGroup[3][0]) ? array_map(array('Functions', 'getProfileLink'), $curGroup[3], array_fill(0, count($curGroup[3]), true)) : array();
			break;
		}
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode], array('groups' => $this->groups,
			'errors' => $this->errors));
	}
}
?>