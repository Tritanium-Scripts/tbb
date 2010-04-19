<?php
/**
*
* Tritanium Bulletin Board 2 - ad_groups.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

switch(@$_GET['mode']) {

	//*
	//* Standard: Gruppenuebersicht
	//*
	default:
		$ad_groups_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_groups_overview']); // Neues Template-Objekt erstellen

		$DB->query("SELECT * FROM ".TBLPFX."groups ORDER BY group_name"); // Daten aller Gruppen laden
		if($DB->affected_rows > 0) { // Falls es mindestens eine Gruppe gibt
			while($akt_group = $DB->fetch_array())
				$ad_groups_tpl->blocks['grouprow']->parse_code(FALSE,TRUE); // Block fuer aktuelle Gruppe erstellen
		}

		include_once('ad_pheader.php'); // Seitenkopf einfuegen
		$ad_groups_tpl->parse_code(TRUE); // Seite ausgeben
		include_once('ad_ptail.php'); // Seitenende ausgeben
	break;


	//*
	//* Gruppe hinzufuegen
	//*
	case 'addgroup':
		$p_name = isset($_POST['p_name']) ? $_POST['p_name'] : ''; // Name der neuen Gruppe
		$error = ''; // Fehler

		if(isset($_GET['doit'])) { // Falls Formular abgeschickt wurde
			if($p_name == '') $error = $LNG['error_no_group_name']; // Falls kein Name angegeben wurde
			else {
				$DB->query("INSERT INTO ".TBLPFX."groups (group_name) VALUES ('$p_name')"); // Daten der neuen Gruppe speichern
				header("Location: administration.php?faction=ad_groups&$MYSID"); exit; // Zurueck zur Gruppenuebersicht
			}
		}

		$ad_groups_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_groups_addgroup']); // Neues Template-Objekt erstellen

		if($error != '') $ad_groups_tpl->blocks['errorrow']->parse_code(); // Falls es einen Fehler gibt

		include_once('ad_pheader.php'); // Seitenkopf ausgeben
		$ad_groups_tpl->parse_code(TRUE); // Seite ausgeben
		include_once('ad_ptail.php'); // Seitenende ausgeben
	break;


	//*
	//* Gruppe bearbeiten
	//*
	case 'editgroup':
		$group_id = isset($_GET['group_id']) ? $_GET['group_id'] : 0; // ID der Gruppe
		$error = ''; // Fehler

		if(!$group_data = get_group_data($group_id)) die('Kann Gruppendaten nicht laden!'); // Ueberpruefen, ob Gruppe existiert

		$p_name = isset($_POST['p_name']) ? $_POST['p_name'] : $group_data['group_name']; // Falls kein Name angegeben wurde, den alten verwenden

		if(isset($_GET['doit'])) { // Falls Formular abgeschickt wurde
			if($p_name == '') $error = $LNG['error_no_group_name']; // Falls kein Name angegeben wurde
			else {
				$DB->query("UPDATE ".TBLPFX."groups SET group_name='$p_name' WHERE group_id='$group_id'"); // Die aktualisierten Daten der Gruppe speichern
				header("Location: administration.php?faction=ad_groups&$MYSID"); exit; // Zurueck zur Gruppenuebersicht
			}
		}

		$ad_groups_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_groups_editgroup']); // Neues Template-Objekt erstellen

		if($error != '') $ad_groups_tpl->blocks['errorrow']->parse_code(); // Falls es einen Fehler gibt

		include_once('ad_pheader.php'); // Seitenkopf ausgeben
		$ad_groups_tpl->parse_code(TRUE); // Seite ausgeben
		include_once('ad_ptail.php'); // Seitenende ausgeben
	break;


	//*
	//* Gruppe loeschen
	//*
	case 'deletegroup':
		$group_id = isset($_GET['group_id']) ? $_GET['group_id'] : 0; // ID der Gruppe

		if(!$group_data = get_group_data($group_id)) die('Kann Gruppendaten nicht laden!'); // Ueberpruefen, ob Gruppe existiert

		$DB->query("DELETE FROM ".TBLPFX."groups WHERE group_id='$group_id'"); // Die Gruppe loeschen
		$DB->query("DELETE FROM ".TBLPFX."groups_members WHERE group_id='$group_id'"); // Die Mitglieder der Gruppe loeschen
		$DB->query("DELETE FROM ".TBLPFX."forums_auth WHERE auth_type='1' AND auth_id='$group_id'"); // Die Forenrechte der Gruppe loeschen

		header("Location: administration.php?faction=ad_groups&$MYSID"); exit; // Zurueck zur Gruppenuebersicht
	break;


	//*
	//* Mitglieder der Gruppe verwalten
	//*
	case 'managemembers':
		$group_id = isset($_GET['group_id']) ? $_GET['group_id'] : 0; // ID der Gruppe

		if(!$group_data = get_group_data($group_id)) die('Kann Gruppendaten nicht laden!'); // Ueberpruefen, ob Gruppe existiert

		$table_header = sprintf($LNG['Members_of_x'],$group_data['group_name']); // Den Text fuer die "Ueberschrift" der Tabelle (HTML-Teil)


		$ad_groups_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_groups_managemembers']); // Neues Template-Objekt erstellen


		//
		// Die Kursleiter
		//
		$DB->query("SELECT t1.member_id, t2.user_nick AS member_nick FROM ".TBLPFX."groups_members AS t1 LEFT JOIN ".TBLPFX."users AS t2 ON t1.member_id=t2.user_id WHERE t1.group_id='$group_id' AND t1.member_status='1' ORDER BY t2.user_nick"); // Daten der Kursleiter laden
		if($DB->affected_rows != 0) { // Falls es Grupppenleiter gibt
			while($akt_leader = $DB->fetch_array())
				$ad_groups_tpl->blocks['leaderrow']->parse_code(FALSE,TRUE); // Block fuer aktuellen Kursleiter erstellen
		}
		else $ad_groups_tpl->unset_block('leaderrow'); // Falls kein Kursleiter, Block komplett loeschen


		//
		// Die normalen Mitglieder
		//
		$DB->query("SELECT t1.member_id, t2.user_nick AS member_nick FROM ".TBLPFX."groups_members AS t1 LEFT JOIN ".TBLPFX."users AS t2 ON t1.member_id=t2.user_id WHERE t1.group_id='$group_id' AND t1.member_status='0' ORDER BY t2.user_nick"); // Daten der normalen Mitglieder laden
		if($DB->affected_rows != 0) { // Falls es normale Mitglieder gibt
			while($akt_member = $DB->fetch_array())
				$ad_groups_tpl->blocks['memberrow']->parse_code(FALSE,TRUE); // Block fuer aktuelles Mitglied erstellen
		}
		else $ad_groups_tpl->unset_block('memberrow'); // Falls kein normales Mitglied, Block komplett loeschen


		include_once('ad_pheader.php'); // Seitenkopf ausgeben
		$ad_groups_tpl->parse_code(TRUE); // Seite ausgeben
		include_once('ad_ptail.php'); // Seitenende ausgeben
	break;


	//*
	//* Mitglieder zu Gruppe hinzufuegen
	//*
	case 'addmembers':
		$group_id = isset($_GET['group_id']) ? $_GET['group_id'] : 0; // ID der Gruppe
		$p_users = isset($_POST['p_users']) ? $_POST['p_users'] : ''; // IDs/Nicks der User als String mit Kommata getrennt
		$p_leader = isset($_POST['p_leader'])  ? $_POST['p_leader'] : 0; // Angabe ob Kursleiter oder nicht
		if($p_leader != 0 && $p_leader != 1) $p_leader = 0; // Falls weder Kursleiter noch normales Mitglied angegeben wurde, wird "normales Mitglied" verwendet

		if(!$group_data = get_group_data($group_id)) die('Kann Gruppendaten nicht laden!'); // Ueberpruefen, ob Gruppe existiert


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
		$DB->query("SELECT member_id FROM ".TBLPFX."groups_members WHERE group_id='$group_id' AND member_id IN ('".implode("','",$p_users)."')"); // IDs der User laden, die schon Mitglied sind
		while($akt_euser = $DB->fetch_array())
			$existing_users[$akt_euser['member_id']] = TRUE; // Element mit dem Key "ID des Users" und dem Wert TRUE in das Array einfuegen


		//
		// Die neuen Mitglieder speichern
		//
		while(list(,$akt_user) = each($p_users)) {
			if(!isset($existing_users[$akt_user])) // Falls der User noch nicht Mitglied der Gruppe ist
				$DB->query("INSERT INTO ".TBLPFX."groups_members (group_id,member_id,member_status) VALUES ('$group_id','$akt_user','$p_leader')"); // Die Daten des neuen Mitgleids speichern
		}

		header("Location: administration.php?faction=ad_groups&mode=managemembers&group_id=$group_id&$MYSID"); exit; // Zurueck zur Mitgliederuebersicht
	break;


	//*
	//* Mitglieder einer Gruppe loeschen
	//*
	case 'deletemember':
		$member_id = isset($_GET['member_id']) ? $_GET['member_id'] : 0; // ID des Mitglieds
		$group_id = isset($_GET['group_id']) ? $_GET['group_id'] : 0; // ID der Gruppe

		if(!$group_data = get_group_data($group_id)) die('Kann Gruppendaten nicht laden!'); // Ueberpruefen, ob Gruppe existiert

		$DB->query("DELETE FROM ".TBLPFX."groups_members WHERE group_id='$group_id' AND member_id='$member_id'"); // Mitglied loeschen, falls es existiert

		header("Location: administration.php?faction=ad_groups&mode=managemembers&group_id=$group_id&$MYSID"); exit; // Zurueck zur Mitgliederuebersicht
	break;


	//*
	//* Status eines Gruppenmitglieds aendern
	//*
	case 'switchmemberstatus':
		$member_id = isset($_GET['member_id']) ? $_GET['member_id'] : 0; // ID des Mitglieds
		$group_id = isset($_GET['group_id']) ? $_GET['group_id'] : 0; // ID der Gruppe

		if(!$group_data = get_group_data($group_id)) die('Kann Gruppendaten nicht laden!'); // Ueberpruefen, ob Gruppe existiert

		$DB->query("SELECT member_status FROM ".TBLPFX."groups_members WHERE group_id='$group_id' AND member_id='$member_id'"); // Aktueller Status des Mitglieds laden
		if($DB->affected_rows == 1) { // Falls der User auch Mitglied der Gruppe ist
			list($akt_status) = $DB->fetch_array(); // Der aktuelle Status
			$new_status = ($akt_status == 1) ? 0 : 1; // Falls der alte Status 1 (Kursleiter) war, ist der neue 0 (normales Mitglied), ansonsten 1 (Kursleiter)
			$DB->query("UPDATE ".TBLPFX."groups_members SET member_status='$new_status' WHERE group_id='$group_id' AND member_id='$member_id'"); // Neuer Status speichern
		}

		header("Location: administration.php?faction=ad_groups&mode=managemembers&group_id=$group_id&$MYSID"); exit; // Zurueck zur Mitgliederuebersicht
	break;
}

?>