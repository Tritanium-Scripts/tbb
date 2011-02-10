<?php
/**
 * Manages members.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.5
 */
class AdminUser implements Module
{
	/**
	 * Detected errors during user actions.
	 *
	 * @var array Error messages
	 */
	private $errors = array();

	/**
	 * Contains mode to execute.
	 *
	 * @var string User operation mode
	 */
	private $mode;

	/**
	 * Translates a mode to its template file.
	 *
	 * @var array Mode and template counterparts
	 */
	private static $modeTable = array('ad_user' => 'AdminUser',
		'search' => 'AdminUser',
		'new' => 'AdminUserNewUser',
		'edit' => 'AdminUserEditUser');

	/**
	 * Sets mode and provides needed lang strings.
	 *
	 * @param string $mode User mode
	 * @return AdminUser New instance of this class
	 */
	function __construct($mode)
	{
		$this->mode = $mode;
		Main::getModule('Language')->parseFile('MemberList');
	}

	/**
	 * Compares member search results by percentage of similarity of the search term.
	 *
	 * @param array $m1 First member search result to compare with
	 * @param array $m2 Second member search result to compare with
	 * @return int Comparison result as natural order
	 */
	private function cmpByPercent($m1, $m2)
	{
		return $m1['percent'] == $m2['percent'] ? ($m1['id'] == $m2['id'] ? 0 : ($m1['id'] > $m2['id'] ? 1 : -1)) : ($m1['percent'] < $m2['percent'] ? 1 : -1);
	}

	/**
	 * Searches for, edits and creates new member.
	 */
	public function execute()
	{
		Functions::accessAdminPanel();
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('manage_user'), INDEXFILE . '?faction=ad_user' . SID_AMPER);
		switch($this->mode)
		{
//AdminUserNewUser
			case 'new':
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('add_new_member'), INDEXFILE . '?faction=ad_user&amp;mode=new' . SID_AMPER);
			$newUser = Functions::getValueFromGlobals('new');
			$groups = array_map(array('Functions', 'explodeByTab'), Functions::file('vars/groups.var'));
			if(Functions::getValueFromGlobals('create') == 'yes')
			{
				$newUser['nick'] = htmlspecialchars(trim($newUser['nick']));
				$sendRegMail = $newUser['send_reg'] = isset($newUser['send_reg']);
				//Check nick name
				if(empty($newUser['nick']))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_an_user_name');
				elseif(Functions::unifyUserName($newUser['nick']))
					$this->errors[] = Main::getModule('Language')->getString('the_user_name_already_exists');
				//Check mail addy
				if(empty($newUser['email']))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_mail');
				elseif(!Functions::isValidMail($newUser['email']))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_valid_mail');
				elseif(Functions::unifyUserMail($newUser['email']))
					$this->errors[] = Main::getModule('Language')->getString('the_mail_address_already_exists');
				//Check + hash password
				if(empty($newUser['pw1']))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_password');
				elseif($newUser['pw1'] != $newUser['pw2'])
					$this->errors[] = Main::getModule('Language')->getString('passwords_do_not_match');
				else
					//Unhashed pass is still available under 'pw2' for sending reg mail
					$newUser['pw1'] = Functions::getHash($newUser['pw1']);
				if(empty($this->errors))
				{
					//Get new ID
					$lockObj = Functions::getLockObject('vars/last_user_id.var');
					$newUserID = $lockObj->getFileContent()+1;
					//Process group stuff
					if(!empty($newUser['group']))
					{
						foreach($groups as &$curGroup)
							if($curGroup[0] == $newUser['group'])
							{
								$curGroup[3] .= (empty($curGroup[3]) ? '' : ',') . $newUserID;
								break;
							}
						Functions::file_put_contents('vars/groups.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $groups)) . "\n");
					}
					//Build member file
					$newUserFile = array($newUser['nick'],
						$newUserID,
						$newUser['pw1'],
						$newUser['email'],
						'3',
						'0',
						date('YmdHis'),
						'',
						'',
						'',
						'',
						'0',
						'',
						'',
						'1,1',
						$newUser['group'],
						//New TBB 1.5 values
						time(),
						'',
						'',
						'',
						'',
						'');
					//Writing time
					Functions::file_put_contents('members/' . $newUserID . '.xbb', implode("\n", $newUserFile));
					Functions::file_put_contents('members/' . $newUserID . '.pm', '');
					$lockObj->setFileContent($newUserID);
					$lockObj = Functions::getLockObject('vars/member_counter.var');
					$lockObj->setFileContent($lockObj->getFileContent()+1);
					//Send reg mail, if required
					if($sendRegMail)
						Functions::sendMessage($newUserFile[3], 'new_registration', htmlspecialchars_decode($newUserFile[0]), Main::getModule('Config')->getCfgVal('forum_name'), $newUserFile[1], $newUserFile[3], $newUser['pw2'], Main::getModule('Config')->getCfgVal('address_to_forum') . '/' . INDEXFILE);
					//Done
					Main::getModule('Logger')->log('%s created new member (ID: ' . $newUserID . ')', LOG_ACP_ACTION);
					Main::getModule('Template')->printMessage('member_created');
				}
			}
			else
				$newUser = array('nick' => '',
					'email' => '',
					'group' => '',
					'send_reg' => true);
			Main::getModule('Template')->assign(array('newUser' => $newUser,
				'groups' => $groups));
			break;

//AdminUserEditUser
			case 'edit':
			$editUserID = intval(Functions::getValueFromGlobals('id'));
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('edit_user'), INDEXFILE . '?faction=ad_user&amp;mode=edit&amp;id=' . $editUserID . SID_AMPER);
			$editUser = Functions::getUserData($editUserID) or Main::getModule('Template')->printMessage('user_does_not_exist');
			if(Functions::getValueFromGlobals('edit') == 'yes')
			{
				//Delete user?
				if(Functions::getValueFromGlobals('kill') != '')
				{
					//Remove from group
					if(!empty($editUser[15]))
					{
						$groups = array_map(array('Functions', 'explodeByTab'), Functions::file('vars/groups.var'));
						foreach($groups as &$curGroup)
							if($curGroup[0] == $editUser[15])
							{
								$curGroup[3] = Functions::explodeByComma($curGroup[3]);
								if(($key = array_search($editUser[1], $curGroup[3])) !== false)
								{
									unset($curGroup[3][$key]);
									$curGroup[3] = implode(',', $curGroup[3]);
									Functions::file_put_contents('vars/groups.var', implode("\n", array_map(array('Functions', 'implodeByTab'), $groups)) . "\n");
								}
								break;
							}
					}
					//Bye bye
					Functions::unlink('members/' . $editUser[1] . '.xbb');
					Functions::unlink('members/' . $editUser[1] . '.pm');
					//Decrease member counter
					$lockObj = Functions::getLockObject('vars/member_counter.var');
					$lockObj->setFileContent($lockObj->getFileContent()-1);
					//Done
					Main::getModule('Logger')->log('%s deleted user (ID: ' . $editUser[1] . ')', LOG_ACP_ACTION);
					Main::getModule('Template')->printMessage('member_deleted');
				}
				//Normal edit
				$editUserName = htmlspecialchars(trim(Functions::getValueFromGlobals('name')));
				$editUser[3] = Functions::getValueFromGlobals('email');
				$editUser[4] = intval(Functions::getValueFromGlobals('status'));
				$editUser[7] = Functions::nl2br(htmlspecialchars(trim(Functions::getValueFromGlobals('signatur', false))));
				$editUser[10] = Functions::getValueFromGlobals('pic');
				$editUser[17] = htmlspecialchars(trim(Functions::getValueFromGlobals('specialState')));
				if(empty($editUserName))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_an_user_name');
				elseif($editUser[0] != $editUserName && Functions::unifyUserName($editUserName))
					$this->errors[] = Main::getModule('Language')->getString('the_user_name_already_exists');
				else
					$editUser[0] = $editUserName;
				if(empty($editUser[3]))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_mail');
				elseif(!Functions::isValidMail($editUser[3]))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_valid_mail');
				if(empty($this->errors))
				{
					$editUser[14] = implode(',', $editUser[14]);
					$editUser[19] = Functions::implodeByTab($editUser[19]);
					Functions::file_put_contents('members/' . $editUser[1] . '.xbb', implode("\n", $editUser));
					//Done
					Main::getModule('Logger')->log('%s edited user (ID: ' . $editUser[1] . ')', LOG_ACP_ACTION);
					Main::getModule('Template')->printMessage('member_edited');
				}
			}
			$editUser[7] = Functions::br2nl($editUser[7]);
			unset($editUser[2], $editUser[5], $editUser[6], $editUser[8], $editUser[9], $editUser[11], $editUser[12], $editUser[13], $editUser[14], $editUser[15], $editUser[16], $editUser[18], $editUser[19]);
			Main::getModule('Template')->assign('editUser', $editUser);
			break;

//AdminUser
			default:
			Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('member_search'), INDEXFILE . '?faction=ad_user&amp;mode=search' . SID_AMPER);
			$searchMethod = Functions::getValueFromGlobals('searchmethod') or $searchMethod = 'nick';
			$searchFor = Functions::strtolower(htmlspecialchars(trim(Functions::getValueFromGlobals('searched'))));
			$results = array();
			if(Functions::getValueFromGlobals('search') == 'yes')
			{
				if(empty($searchFor))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_search_term');
				else
				{
					switch($searchMethod)
					{
						case 'id':
						if(($userFile = Functions::getUserData($searchFor)) !== false)
							$results[] = array('id' => $userFile[1],
								'nick' => $userFile[0],
								'mail' => $userFile[3],
								'percent' => 100);
						break;

						case 'nick':
						case 'email':
						$index = $searchMethod == 'nick' ? 0 : 3;
						foreach(glob(DATAPATH . 'members/[!0t]*.xbb') as $curMember)
						{
							$curMember = Functions::file($curMember, null, null, false);
							similar_text(Functions::strtolower($curMember[$index]), $searchFor, $curPercent); //Calculate percentage of similarity
							if($curPercent > 0) //Add to result list by having a minimum of similarity
								$results[] = array('id' => $curMember[1],
								'nick' => $curMember[0],
								'mail' => $curMember[3],
								'percent' => $curPercent);
						}
						break;
					}
					if(count($results) > 1)
						usort($results, array($this, 'cmpByPercent'));
				}
			}
			Main::getModule('Template')->assign(array('results' => $results,
				'searchMethod' => $searchMethod,
				'searchFor' => $searchFor));
			break;
		}
		Main::getModule('Template')->printPage(self::$modeTable[$this->mode], array('errors' => $this->errors));
	}
}
?>