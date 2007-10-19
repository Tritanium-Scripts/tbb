<?php

class AdminGroups extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'AuthAdmin',
		'DB',
		'GlobalsAdmin',
		'Language',
		'Navbar',
		'Template'
	);

	public function executeMe() {
		$this->modules['Language']->addFile('AdminGroups');
		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Manage_groups'),INDEXFILE.'?action=AdminGroups&amp;'.MYSID);

		switch(@$_GET['mode']) {
			default:
				$this->modules['DB']->query('SELECT * FROM '.TBLPFX.'groups ORDER BY "groupName"');
				$groupsData = $this->modules['DB']->raw2Array();

				$this->modules['Template']->assign(array(
					'groupsData'=>$groupsData
				));
				$this->modules['Template']->printPage('AdminGroups.tpl');
				break;

			case 'AddGroup':
				$p = Functions::getSGValues($_POST['p'],array('groupName'),'');

				$error = '';

				if(isset($_GET['doit'])) {
					if(trim($p['groupName']) == '') $error = $this->modules['Language']->getString('error_no_group_name');
					else {
                        $this->modules['DB']->queryParams('
                            INSERT INTO
                                '.TBLPFX.'groups
                            SET
                                "groupName"=$1
                        ', array(
                            $p['groupName']
                        ));
						Functions::myHeader(INDEXFILE.'?action=AdminGroups&'.MYSID);
					}
				}

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Add_group'),INDEXFILE.'?action=AdminGroups&amp;mode=AddGroup&amp;'.MYSID);

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'error'=>$error
				));
				$this->modules['Template']->printPage('AdminGroupsAddGroup.tpl');
				break;

			case 'EditGroup':
				$groupID = isset($_GET['groupID']) ? intval($_GET['groupID']) : 0;
				if(!$groupData = FuncGroups::getGroupData($groupID)) die('Cannot load data: group');

				$p = Functions::getSGValues($_POST['p'],array('groupName'),'',Functions::addSlashes($groupData));

				$error = '';

				if(isset($_GET['doit'])) {
					if(trim($p['groupName']) == '') $error = $this->modules['Language']->getString('error_no_group_name');
					else {
                        $this->modules['DB']->queryParams('
                            UPDATE
                                '.TBLPFX.'groups
                            SET
                                "groupName"=$1
                            WHERE
                                "groupID"=$2
                        ', array(
                            $p['groupName'],
                            $groupID
                        ));
						Functions::myHeader(INDEXFILE.'?action=AdminGroups&'.MYSID);
					}
				}

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Add_group'),INDEXFILE.'?action=AdminGroups&amp;mode=EditGroup&amp;groupID='.$groupID.'&amp;'.MYSID);

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'error'=>$error,
					'groupID'=>$groupID
				));
				$this->modules['Template']->printPage('AdminGroupsEditGroup.tpl');
				break;

			case 'DeleteGroup':
				$groupID = isset($_GET['groupID']) ? intval($_GET['groupID']) : 0; // ID der Gruppe

                $this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'groups WHERE "groupID"=$1', array($groupID));
				if($this->modules['DB']->getAffectedRows() == 1) {
                    $this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'groups_members WHERE "groupID"=$1', array($groupID));
                    $this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'forums_auth WHERE "authType"=$1 AND "authID"=$2', array(AUTH_TYPE_GROUP, $groupID));
				}

				Functions::myHeader(INDEXFILE.'?action=AdminGroups&'.MYSID);
				break;

			case 'ManageMembers':
				$groupID = isset($_GET['groupID']) ? intval($_GET['groupID']) : 0;

				if(!$groupData = FuncGroups::getGroupData($groupID)) die('Cannot load data: group');

				// Group admins
                $this->modules['DB']->queryParams('SELECT t1."memberID", t2."userNick" AS "memberNick" FROM '.TBLPFX.'groups_members t1, '.TBLPFX.'users t2 WHERE t1."memberID"=t2."userID" AND t1."groupID"=$1 AND t1."memberStatus"=1 ORDER BY t2."userNick"', array($groupID));
				$groupAdminsData = $this->modules['DB']->raw2Array();

				// 'Ordinary' group members
                $this->modules['DB']->queryParams('SELECT t1."memberID", t2."userNick" AS "memberNick" FROM '.TBLPFX.'groups_members t1, '.TBLPFX.'users t2 WHERE t1."memberID"=t2."userID" AND t1."groupID"=$1 AND t1."memberStatus"=0 ORDER BY t2."userNick"', array($groupID));
				$groupMembersData = $this->modules['DB']->raw2Array();

				$this->modules['Template']->assign(array(
					'groupAdminsData'=>$groupAdminsData,
					'groupMembersData'=>$groupMembersData,
					'groupID'=>$groupID
				));
				$this->modules['Template']->printPage('AdminGroupsManageMembers.tpl');
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
                $this->modules['DB']->queryParams('SELECT "memberID" FROM '.TBLPFX.'groups_members WHERE "groupID"=$1 AND "memberID" IN $2', array($groupID, $newMembers));
				while($curMember = $this->modules['DB']->fetchArray())
					$existingUsers[$curMember['memberID']] = TRUE;


				//
				// Die neuen Mitglieder speichern
				//
				foreach($newMembers AS &$curMember) {
					if(!isset($existingUsers[$curMember]))
                        $this->modules['DB']->queryParams('
                            INSERT INTO
                                '.TBLPFX.'groups_members
                            SET
                                "groupID"=$1,
                                "memberID"=$2,
                                "memberStatus"=$3
                        ', array(
                            $groupID,
                            $curMember,
                            $p['membersAreLeader']
                        ));
				}

				Functions::myHeader(INDEXFILE."?action=AdminGroups&mode=ManageMembers&groupID=$groupID&".MYSID);
				break;

			case 'DeleteMember':
				$memberID = isset($_GET['memberID']) ? intval($_GET['memberID']) : 0;
				$groupID = isset($_GET['groupID']) ? intval($_GET['groupID']) : 0;

                $this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'groups_members WHERE "groupID"=$1 AND "memberID"=$2', array($groupID, $memberID));

				Functions::myHeader(INDEXFILE."?action=AdminGroups&mode=ManageMembers&groupID=$groupID&".MYSID);
				break;

			case 'SwitchMemberStatus':
				$memberID = isset($_GET['memberID']) ? $_GET['memberID'] : 0;
				$groupID = isset($_GET['groupID']) ? $_GET['groupID'] : 0;

                $this->modules['DB']->queryParams('"SELECT "memberStatus" FROM '.TBLPFX.'groups_members WHERE "groupID"=$1 AND "memberID"=$2', array($groupID, $memberID));
				if($this->modules['DB']->getAffectedRows() == 1) {
					list($memberStatus) = $this->modules['DB']->fetchArray();
					$newMemberStatus = ($memberStatus == 1) ? 0 : 1;
                    $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'groups_members SET "memberStatus"=$1 WHERE "groupID"=$2 AND "memberID"=$3', array($newMemberStatus, $groupID, $memberID));
				}

				Functions::myHeader(INDEXFILE."?action=AdminGroups&mode=ManageMembers&groupID=$groupID&".MYSID);
				break;
		}
	}
}

?>