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
					'groupMembersData'=>$groupMembersData
				));
				$this->modules['PageParts']->printPage('AdminGroupsManageMembers.tpl');
				break;

			case 'addmembers':
				$groupID = isset($_GET['groupID']) ? $_GET['groupID'] : 0; // ID der Gruppe
				$p_users = isset($_POST['p_users']) ? $_POST['p_users'] : ''; // IDs/Nicks der User als String mit Kommata getrennt
				$p_leader = isset($_POST['p_leader'])  ? $_POST['p_leader'] : 0; // Angabe ob Kursleiter oder nicht
				if($p_leader != 0 && $p_leader != 1) $p_leader = 0; // Falls weder Kursleiter noch normales Mitglied angegeben wurde, wird "normales Mitglied" verwendet

				if(!$group_data = get_group_data($groupID)) die('Kann Gruppendaten nicht laden!'); // Ueberpruefen, ob Gruppe existiert


				//
				// Ueberpruefen, ob die neuen Mitglieder existieren
				//
				$p_users = explode(',',trim($p_users)); // Den uebergebenen String in Array mit User-Nicks/IDs als Elemente umwandeln
				while(list($akt_key) = each($p_users)) {
					if(!$p_users[$akt_key] = get_user_id($p_users[$akt_key])) unset($p_users[$akt_key]); // Falls User nicht existiert, das Arrayelement loeschen
				}
				reset($p_users); // Arraypointer an den Anfang setzen


				//
				// Die IDs der User laden, die schon Mitglied der Gruppe sind
				//
				$existing_users = array(); // Array, in dem die IDs der User stehen werden, die schon Mitglied sind
				$this->modules['DB']->query("SELECT member_id FROM ".TBLPFX."groups_members WHERE groupID='$groupID' AND member_id IN ('".implode("','",$p_users)."')"); // IDs der User laden, die schon Mitglied sind
				while($akt_euser = $this->modules['DB']->fetch_array())
					$existing_users[$akt_euser['member_id']] = TRUE; // Element mit dem Key "ID des Users" und dem Wert TRUE in das Array einfuegen


				//
				// Die neuen Mitglieder speichern
				//
				while(list(,$akt_user) = each($p_users)) {
					if(!isset($existing_users[$akt_user])) // Falls der User noch nicht Mitglied der Gruppe ist
						$this->modules['DB']->query("INSERT INTO ".TBLPFX."groups_members (groupID,member_id,member_status) VALUES ('$groupID','$akt_user','$p_leader')"); // Die Daten des neuen Mitgleids speichern
				}

				header("Location: administration.php?action=ad_groups&mode=managemembers&groupID=$groupID&$MYSID"); exit; // Zurueck zur Mitgliederuebersicht
				break;

			case 'deletemember':
				$member_id = isset($_GET['member_id']) ? $_GET['member_id'] : 0; // ID des Mitglieds
				$groupID = isset($_GET['groupID']) ? $_GET['groupID'] : 0; // ID der Gruppe

				if(!$group_data = get_group_data($groupID)) die('Kann Gruppendaten nicht laden!'); // Ueberpruefen, ob Gruppe existiert

				$this->modules['DB']->query("DELETE FROM ".TBLPFX."groups_members WHERE groupID='$groupID' AND member_id='$member_id'"); // Mitglied loeschen, falls es existiert

				header("Location: administration.php?action=ad_groups&mode=managemembers&groupID=$groupID&$MYSID"); exit; // Zurueck zur Mitgliederuebersicht
				break;

			case 'switchmemberstatus':
				$member_id = isset($_GET['member_id']) ? $_GET['member_id'] : 0; // ID des Mitglieds
				$groupID = isset($_GET['groupID']) ? $_GET['groupID'] : 0; // ID der Gruppe

				if(!$group_data = get_group_data($groupID)) die('Kann Gruppendaten nicht laden!'); // Ueberpruefen, ob Gruppe existiert

				$this->modules['DB']->query("SELECT member_status FROM ".TBLPFX."groups_members WHERE groupID='$groupID' AND member_id='$member_id'"); // Aktueller Status des Mitglieds laden
				if($this->modules['DB']->affected_rows == 1) { // Falls der User auch Mitglied der Gruppe ist
					list($akt_status) = $this->modules['DB']->fetch_array(); // Der aktuelle Status
					$new_status = ($akt_status == 1) ? 0 : 1; // Falls der alte Status 1 (Kursleiter) war, ist der neue 0 (normales Mitglied), ansonsten 1 (Kursleiter)
					$this->modules['DB']->query("UPDATE ".TBLPFX."groups_members SET member_status='$new_status' WHERE groupID='$groupID' AND member_id='$member_id'"); // Neuer Status speichern
				}

				header("Location: administration.php?action=ad_groups&mode=managemembers&groupID=$groupID&$MYSID"); exit; // Zurueck zur Mitgliederuebersicht
				break;
		}
	}
}

?>