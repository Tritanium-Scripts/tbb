<?php

class AdminGroups extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'AuthAdmin',
		'DB',
		'GlobalsAdmin',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
		$this->modules['Language']->addFile('AdminGroups');
		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Manage_groups'),INDEXFILE.'?action=AdminGroups&amp;'.MYSID);

		switch(@$_GET['mode']) {
			default:
				$this->modules['DB']->query("SELECT * FROM ".TBLPFX."groups ORDER BY groupName");
				$groupsData = $this->modules['DB']->raw2Array();

				$this->modules['Template']->assign(array(
					'groupsData'=>$groupsData
				));
				$this->modules['PageParts']->printPage('AdminGroups.tpl');
				break;

			case 'AddGroup':
				$p = Functions::getSGValues($_POST['p'],array('groupName'),'');

				$error = '';

				if(isset($_GET['doit'])) {
					if(trim($p['groupName']) == '') $error = $this->modules['Language']->getString('error_no_group_name');
					else {
						$this->modules['DB']->query("
							INSERT INTO
								".TBLPFX."groups
							SET
								groupName='".$p['groupName']."'
						");
						Functions::myHeader(INDEXFILE.'?action=AdminGroups&'.MYSID);
					}
				}

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Add_group'),INDEXFILE.'?action=AdminGroups&amp;mode=AddGroup&amp;'.MYSID);

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'error'=>$error
				));
				$this->modules['PageParts']->printPage('AdminGroupsAddGroup.tpl');
				break;

			case 'EditGroup':
				$groupID = isset($_GET['groupID']) ? intval($_GET['groupID']) : 0;
				if(!$groupData = FuncGroups::getGroupData($groupID)) die('Cannot load data: group');

				$p = Functions::getSGValues($_POST['p'],array('groupName'),'',Functions::addSlashes($groupData));

				$error = '';

				if(isset($_GET['doit'])) {
					if(trim($p['groupName']) == '') $error = $this->modules['Language']->getString('error_no_group_name');
					else {
						$this->modules['DB']->query("
							UPDATE
								".TBLPFX."groups
							SET
								groupName='".$p['groupName']."'
							WHERE
								groupID='$groupID'
						");
						Functions::myHeader(INDEXFILE.'?action=AdminGroups&'.MYSID);
					}
				}

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Add_group'),INDEXFILE.'?action=AdminGroups&amp;mode=EditGroup&amp;groupID='.$groupID.'&amp;'.MYSID);

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'error'=>$error,
					'groupID'=>$groupID
				));
				$this->modules['PageParts']->printPage('AdminGroupsEditGroup.tpl');
				break;

			case 'DeleteGroup':
				$groupID = isset($_GET['groupID']) ? intval($_GET['groupID']) : 0; // ID der Gruppe

				$this->modules['DB']->query("DELETE FROM ".TBLPFX."groups WHERE groupID='$groupID'");
				if($this->modules['DB']->getAffectedRows() == 1) {
					$this->modules['DB']->query("DELETE FROM ".TBLPFX."groups_members WHERE groupID='$groupID'");
					$this->modules['DB']->query("DELETE FROM ".TBLPFX."forums_auth WHERE authType='".AUTH_TYPE_GROUP."' AND authID='$groupID'");
				}

				Functions::myHeader(INDEXFILE.'?action=AdminGroups&'.MYSID);
				break;

			case 'ManageMembers':
				$groupID = isset($_GET['groupID']) ? intval($_GET['groupID']) : 0;

				if(!$groupData = FuncGroups::getGroupData($groupID)) die('Cannot load data: group');

				// Group admins
				$this->modules['DB']->query("SELECT t1.memberID, t2.userNick AS memberNick FROM ".TBLPFX."groups_members t1, ".TBLPFX."users t2 WHERE t1.memberID=t2.userID AND t1.groupID='$groupID' AND t1.memberStatus='1' ORDER BY t2.userNick");
				$groupAdminsData = $this->modules['DB']->raw2Array();

				// 'Ordinary' group members
				$this->modules['DB']->query("SELECT t1.memberID, t2.userNick AS memberNick FROM ".TBLPFX."groups_members t1, ".TBLPFX."users t2 WHERE t1.memberID=t2.userID AND t1.groupID='$groupID' AND t1.memberStatus='0' ORDER BY t2.userNick");
				$groupMembersData = $this->modules['DB']->raw2Array();

				$this->modules['Template']->assign(array(
					'groupAdminsData'=>$groupAdminsData,
					'groupMembersData'=>$groupMembersData,
					'groupID'=>$groupID
				));
				$this->modules['PageParts']->printPage('AdminGroupsManageMembers.tpl');
				break;

			case 'AddMembers':
				$groupID = isset($_GET['groupID']) ? intval($_GET['groupID']) : 0;
				if(!$groupData = FuncGroups::getGroupData($groupID)) die('Cannot load data: group');

				$p = Functions::getSGValues($_POST['p'],array('newMembers','membersAreLeader'),'');
				if(!in_array($p['membersAreLeader'],array(0,1))) $p['membersAreLeader'] = 0;

				//
				// Ueberpruefen, ob die neuen Mitglieder existieren
				//
				$newMembers = explode(',',$p['newMembers']);
				while(list($curKey) = each($newMembers)) {
					if(!$newMembers[$curKey] = FuncUsers::getUserID($newMembers[$curKey]))
						unset($newMembers[$curKey]);
				}
				reset($newMembers);


				//
				// Die IDs der User laden, die schon Mitglied der Gruppe sind
				//
				$existingUsers = array();
				$this->modules['DB']->query("SELECT memberID FROM ".TBLPFX."groups_members WHERE groupID='$groupID' AND memberID IN ('".implode("','",$newMembers)."')");
				while($curMember = $this->modules['DB']->fetchArray())
					$existingUsers[$curMember['memberID']] = TRUE;


				//
				// Die neuen Mitglieder speichern
				//
				foreach($newMembers AS &$curMember) {
					if(!isset($existingUsers[$curMember]))
						$this->modules['DB']->query("
							INSERT INTO
								".TBLPFX."groups_members
							SET
								groupID='$groupID',
								memberID='$curMember',
								memberStatus='".$p['membersAreLeader']."'
						");
				}

				Functions::myHeader(INDEXFILE."?action=AdminGroups&mode=ManageMembers&groupID=$groupID&".MYSID);
				break;

			case 'DeleteMember':
				$memberID = isset($_GET['memberID']) ? intval($_GET['memberID']) : 0;
				$groupID = isset($_GET['groupID']) ? intval($_GET['groupID']) : 0;

				$this->modules['DB']->query("DELETE FROM ".TBLPFX."groups_members WHERE groupID='$groupID' AND memberID='$memberID'");

				Functions::myHeader(INDEXFILE."?action=AdminGroups&mode=ManageMembers&groupID=$groupID&".MYSID);
				break;

			case 'SwitchMemberStatus':
				$memberID = isset($_GET['memberID']) ? $_GET['memberID'] : 0;
				$groupID = isset($_GET['groupID']) ? $_GET['groupID'] : 0;

				$this->modules['DB']->query("SELECT memberStatus FROM ".TBLPFX."groups_members WHERE groupID='$groupID' AND memberID='$memberID'");
				if($this->modules['DB']->getAffectedRows() == 1) {
					list($memberStatus) = $this->modules['DB']->fetchArray();
					$newMemberStatus = ($memberStatus == 1) ? 0 : 1;
					$this->modules['DB']->query("UPDATE ".TBLPFX."groups_members SET memberStatus='$newMemberStatus' WHERE groupID='$groupID' AND memberID='$memberID'");
				}

				Functions::myHeader(INDEXFILE."?action=AdminGroups&mode=ManageMembers&groupID=$groupID&".MYSID);
				break;
		}
	}
}

?>