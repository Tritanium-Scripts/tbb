<?php
/**
*
* Tritanium Bulletin Board 2 - pms.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

if($USER_LOGGED_IN != 1) die('Nicht eingeloggt!');
elseif($CONFIG['enable_pms'] != 1) {
	add_navbar_items(array($LNG['Function_deactivated'],''));


	include_once('pheader.php');
	show_navbar();
	show_message($LNG['Function_deactivated'],$LNG['message_function_deactivated']);
	include_once('ptail.php'); exit;
}

add_navbar_items(array($LNG['Private_messages'],"index.php?faction=pms&amp;$MYSID"));

switch(@$_GET['mode']) {
	default:
		$folder_id = isset($_GET['folder_id']) ? $_GET['folder_id'] : 0;
		$z = isset($_GET['z']) ? $_GET['z'] : 1;

		if($folder_id == 0) $folder_data = array('folder_id'=>0,'folder_name'=>$LNG['Inbox']);
		elseif($folder_id == 1) $folder_data = array('folder_id'=>1,'folder_name'=>$LNG['Outbox']);
		else {
			$DB->query("SELECT folder_id, folder_name FROM ".TBLPFX."pms_folders WHERE user_id='$USER_ID' AND folder_id='$folder_id'");
			if($DB->affected_rows == 0) {
				$folder_data = array('folder_id'=>0,'folder_name'=>$LNG['Inbox']);
				$folder_id = 0;
			}
			else $folder_data = $DB->fetch_array();
		}

		$pms_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['pms_viewfolder']);

		$DB->query("SELECT folder_name,folder_id FROM ".TBLPFX."pms_folders WHERE user_id='$USER_ID' ORDER BY folder_name");
		$folders_data = $DB->raw2array();

		array_unshift($folders_data, // Fuegt an den Anfang die Standardordner hinzu...
			array('folder_id'=>0,'folder_name'=>$LNG['Inbox']), // ...Posteingang...
			array('folder_id'=>1,'folder_name'=>$LNG['Outbox']) // ...Postausgang
		);
		reset($folders_data);

		while(list(,$akt_folder) = each($folders_data))
			$pms_tpl->blocks['folderrow']->parse_code(FALSE,TRUE);

		$DB->query("SELECT COUNT(*) FROM ".TBLPFX."pms WHERE pm_to_id='$USER_ID' AND folder_id='$folder_id'");
		list($pms_counter) = $DB->fetch_array();

		$CONFIG['pms_per_page'] = 10;

		$page_listing = create_page_listing($pms_counter,$CONFIG['pms_per_page'],$z,"<a href=\"index.php?faction=pms&amp;mode=viewfolder&amp;folder_id=$folder_id&amp;z=%1\$s&amp;$MYSID\">%2\$s</a>"); //sprintf($LNG['Pages'],$page_counter,$pre.implode(' | ',$page_listing).$suf);
		$start = $z*$CONFIG['pms_per_page']-$CONFIG['pms_per_page'];


		//
		// PM-Daten laden
		//
		$DB->query("SELECT t1.pm_id,t1.pm_subject,t1.pm_send_time, t1.pm_from_id, t1.pm_type, t1.pm_read_status, t2.user_nick AS pm_from_nick FROM ".TBLPFX."pms AS t1 LEFT JOIN ".TBLPFX."users AS t2 ON t1.pm_from_id=t2.user_id WHERE pm_to_id='$USER_ID' AND folder_id='$folder_id' ORDER BY pm_send_time DESC LIMIT $start,".$CONFIG['pms_per_page']);
		if($DB->affected_rows == 0)	$pms_tpl->blocks['nopms']->parse_code();
		else {
			while($akt_pm = $DB->fetch_array()) {
				$akt_sender = ($akt_pm['pm_type'] == 0) ? sprintf($LNG['from_x'],$akt_pm['pm_from_nick']) : sprintf($LNG['to_x'],$akt_pm['pm_from_nick']);
				$akt_date = format_date($akt_pm['pm_send_time']);
				if($akt_pm['pm_read_status'] == 0) $akt_pm['pm_subject'] = '<b>'.$akt_pm['pm_subject'].'</b>';
				$pms_tpl->blocks['pmrow']->parse_code(FALSE,TRUE);
			}
		}

		add_navbar_items(array($folder_data['folder_name'],"index.php?faction=pms&amp;mode=viewfolder&amp;folder_id=$folder_id&amp;$MYSID"));

		include_once('pheader.php');
		show_navbar();
		$pms_tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;

	case 'addfolder':
		add_navbar_items(array($LNG['Add_folder'],"index.php?faction=pms&amp;mode=addfolder&amp;$MYSID"));

		$DB->query("SELECT COUNT(*) AS folders_counter FROM ".TBLPFX."pms_folders WHERE user_id='$USER_ID'");
		list($folders_counter) = $DB->fetch_array();

		if($CONFIG['maximum_pms_folders'] != -1 && $CONFIG['maximum_pms_folders'] <= $folders_counter) {
			include_once('pheader.php');
			show_navbar();
			show_message($LNG['No_more_folders_allowed'],$LNG['message_no_more_folders_allowed']);
			include_once('ptail.php'); exit;
		}

		$error = '';

		$p_name = isset($_POST['p_name']) ? $_POST['p_name'] : '';

		if(isset($_GET['doit'])) {
			if(trim($p_name) == '') $error = $LNG['error_no_name'];
			else {
				$DB->query("SELECT MAX(folder_id) AS max_folder_id FROM ".TBLPFX."pms_folders WHERE user_id='$USER_ID'");
				list($max_folder_id) = $DB->fetch_array();

				$new_folder_id = ($max_folder_id < 3) ? 3 : $max_folder_id + 1;

				$DB->query("INSERT INTO ".TBLPFX."pms_folders (folder_id,user_id,folder_name) VALUES ('$new_folder_id','$USER_ID','$p_name')");

				header("Location: index.php?faction=pms&$MYSID"); exit;
			}
		}

		$pms_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['pms_addfolder']);

		if($error != '') $pms_tpl->blocks['errorrow']->parse_code();

		include_once('pheader.php');
		show_navbar();
		$pms_tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;

	case 'newpm':
		add_navbar_items(array($LNG['New_private_message'],"index.php?faction=pms&amp;mode=newpm&amp;$MYSID"));

		$error = '';

		$p_recipient = isset($_POST['p_recipient']) ? $_POST['p_recipient'] : '';
		$p_subject = isset($_POST['p_subject']) ? $_POST['p_subject'] : '';
		$p_message = isset($_POST['p_message_text']) ? $_POST['p_message_text'] : '';

		$p_smilies = $p_signature = $p_bbcode = $p_saveoutbox = 1;
		$p_htmlcode = $p_rconfirmation = 0;

		if(isset($_GET['doit'])) {
			$p_smilies = isset($_POST['p_smilies']) ? 1 : 0;
			$p_signature = isset($_POST['p_signature']) ? 1 : 0;
			$p_bbcode = isset($_POST['p_bbcode']) ? 1 : 0;
			$p_htmlcode = isset($_POST['p_htmlcode']) ? 1 : 0;
			$p_saveoutbox = isset($_POST['p_saveoutbox']) ? 1 : 0;
			$p_rconfirmation = isset($_POST['p_rconfirmation']) ? 1 : 0;

			$recipients = explode(',',$p_recipient);
			while(list($akt_key) = each($recipients)) {
				$recipients[$akt_key] = trim($recipients[$akt_key]);
				if(!$recipients[$akt_key] = get_user_id($recipients[$akt_key])) unset($recipients[$akt_key]);
			}
			reset($recipients);

			if(count($recipients) == 0) $error = $LNG['error_no_recipient'];
			elseif(trim($p_subject) == '') $error = $LNG['error_no_subject'];
			elseif(trim($p_message) == '') $error = $LNG['error_no_message'];
			else {
				while(list(,$akt_recipient_id) = each($recipients)) {
					$DB->query("INSERT INTO ".TBLPFX."pms (folder_id,pm_from_id,pm_to_id,pm_read_status,pm_type,pm_subject,pm_send_time,pm_enable_bbcode,pm_enable_smilies,pm_enable_htmlcode,pm_show_sig,pm_request_rconfirmation,pm_text) VALUES ('0','$USER_ID','$akt_recipient_id','0','0','$p_subject','".time()."','$p_bbcode','$p_smilies','$p_htmlcode','$p_signature','$p_rconfirmation','$p_message')");
					$new_pm_id = $DB->insert_id;

					if($p_saveoutbox == 1 && $CONFIG['enable_outbox'] == 1) {
						$DB->query("INSERT INTO ".TBLPFX."pms (folder_id,pm_from_id,pm_to_id,pm_read_status,pm_type,pm_subject,pm_send_time,pm_enable_bbcode,pm_enable_smilies,pm_enable_htmlcode,pm_show_sig,pm_request_rconfirmation,pm_text) VALUES ('1','$akt_recipient_id','$USER_ID','1','1','$p_subject','".time()."','$p_bbcode','$p_smilies','$p_htmlcode','$p_signature','$p_rconfirmation','$p_message')");
						$new_pm_id = $DB->insert_id;
					}
				}
				header("Location: index.php?faction=pms&$MYSID"); exit;
			}
		}

		$c = ' checked';
		$checked['smilies'] = ($p_smilies == 1) ? $c : '';
		$checked['signature'] = ($p_signature == 1) ? $c : '';
		$checked['bbcode'] = ($p_bbcode == 1) ? $c : '';
		$checked['htmlcode'] = ($p_htmlcode == 1) ? $c : '';
		$checked['saveoutbox'] = ($p_saveoutbox == 1) ? $c : '';
		$checked['rconfirmation'] = ($p_rconfirmation == 1) ? $c : '';

		$pms_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['pms_newpm']);


		//
		// Die Bloecke...
		//
		if($error != '') $pms_tpl->blocks['errorrow']->parse_code(); //...Fehler...
		if($CONFIG['allow_pms_smilies'] == 1) $pms_tpl->blocks['smiliescheck']->parse_code(); //...Smilies...
		if($CONFIG['enable_sig'] == 1 && $CONFIG['allow_pms_signature']) $pms_tpl->blocks['sigcheck']->parse_code(); //...Signatur...
		if($CONFIG['allow_pms_bbcode'] == 1) { //...BBCode...
			$bbcode_box = get_bbcode_box();

			$pms_tpl->blocks['bbcoderow']->parse_code();
			$pms_tpl->blocks['bbcodecheck']->parse_code();
		}
		if($CONFIG['allow_pms_htmlcode'] == 1) $pms_tpl->blocks['htmlcodecheck']->parse_code(); //...HTML...
		if($CONFIG['enable_outbox'] == 1) $pms_tpl->blocks['saveoutboxcheck']->parse_code(); //...Postausgang...
		if($CONFIG['allow_pms_rconfirmation'] == 1) $pms_tpl->blocks['rconfirmationcheck']->parse_code(); //...Lesebestaetigung


		include_once('pheader.php');
		show_navbar();
		$pms_tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;

	case 'newpmreceived':
		$pms_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['pms_newpmreceived']);

		include_once('pop_pheader.php');
		$pms_tpl->parse_code(TRUE);
		include_once('pop_ptail.php');
	break;

	case 'markread':
		$pm_ids = isset($_POST['pm_ids']) ? $_POST['pm_ids'] : array();

		$return_z = isset($_GET['return_z']) ? $_GET['return_z'] : 1; // Die Seite, zu der zurueckgekehrt werden soll
		$return_f = isset($_GET['return_f']) ? $_GET['return_f'] : 0; // Der Ordner, in den zurueckgekehrt werden soll

		if(count($pm_ids) != 0) {
			$pm_ids = implode("','",$pm_ids);
			$DB->query("UPDATE ".TBLPFX."pms SET pm_read_status='1' WHERE pm_id IN ('$pm_ids') AND pm_to_id='$USER_ID' AND pm_request_rconfirmation<>'1'");
		}

		header("Location: index.php?faction=pms&mode=viewfolder&folder_id=$return_f&z=$return_z&$MYSID"); exit;
	break;

	case 'deletepms':
		$pm_id = isset($_GET['pm_id']) ? $_GET['pm_id'] : 0;
		$pm_ids = isset($_POST['pm_ids']) ? $_POST['pm_ids'] : array();

		$return_z = isset($_GET['return_z']) ? $_GET['return_z'] : 1; // Die Seite, zu der zurueckgekehrt werden soll
		$return_f = isset($_GET['return_f']) ? $_GET['return_f'] : 0; // Der Ordner, in den zurueckgekehrt werden soll

		if($pm_id != 0)
			$DB->query("DELETE FROM ".TBLPFX."pms WHERE pm_id='$pm_id' AND pm_to_id='$USER_ID'");

		if(count($pm_ids) != 0) {
			$pm_ids = implode("','",$pm_ids);
			$DB->query("SELECT pm_id FROM ".TBLPFX."pms WHERE pm_id IN ('$pm_ids') AND pm_to_id='$USER_ID'");

			$pm_ids = array();
			while(list($akt_pm_id) = $DB->fetch_array())
				$pm_ids[] = $akt_pm_id;

			$pm_ids = implode("','",$pm_ids);
			$DB->query("DELETE FROM ".TBLPFX."pms WHERE pm_id IN ('$pm_ids')");
		}

		header("Location: index.php?faction=pms&mode=viewfolder&folder_id=$return_f&z=$return_z&$MYSID"); exit;
	break;

	case 'viewpm':
		$error = '';

		$pm_id = isset($_GET['pm_id']) ? $_GET['pm_id'] : 0;
		$return_z = isset($_GET['return_z']) ? $_GET['return_z'] : 1;


		//
		// PM-Daten laden
		//
		$DB->query("SELECT t1.pm_subject, t1.pm_request_rconfirmation, t1.pm_send_time, t1.folder_id, t1.pm_type, t1.pm_to_id, t1.pm_from_id, t1.pm_read_status, t2.user_nick AS pm_from_nick, t1.pm_text, t4.folder_name AS pm_folder_name FROM ".TBLPFX."pms AS t1 LEFT JOIN ".TBLPFX."users AS t2 ON t1.pm_from_id=t2.user_id LEFT JOIN ".TBLPFX."pms_folders AS t4 ON t4.folder_id=t1.folder_id AND t4.user_id='$USER_ID' WHERE t1.pm_id='$pm_id'");
		if($DB->affected_rows == 0) die('Kann PM-Daten nicht laden!');
		$pm_data = $DB->fetch_array();


		//
		// Ueberpruefen ob...
		//
		if($pm_data['pm_to_id'] != $USER_ID) die('Kein Zugriff auf diese Nachricht!'); // ...User Zugriff auf PM hat...
		if($pm_data['pm_read_status'] == 0) {  // ...die PM schon gelesen ist...
			$DB->query("UPDATE ".TBLPFX."pms SET pm_read_status='1' WHERE pm_id='$pm_id'");
			if($pm_data['pm_request_rconfirmation'] == 1 && $CONFIG['allow_pms_rconfirmation'] == 1 && $pm_data['pm_from_id'] != 0) { // ...und eine Lesebestaetigung angefordert wurde
				$DB->query("INSERT INTO ".TBLPFX."pms (folder_id,pm_from_id,pm_to_id,pm_read_status,pm_type,pm_subject,pm_send_time,pm_enable_bbcode,pm_enable_smilies,pm_enable_htmlcode,pm_show_sig,pm_request_rconfirmation,pm_text) VALUES ('0','$USER_ID','".$pm_data['pm_from_id']."','0','0','".$LNG['read_confirmation_subject']."','".time()."','0','0','0','0','0','".mysql_escape_string(sprintf($LNG['read_confirmation_message'],$pm_data['pm_from_nick'],$pm_data['pm_subject']))."')");
				$new_pm_id = $DB->insert_id;
			}
		}


		//
		// Ein paar Standardsachen
		//
		$p_recipient = isset($_POST['p_recipient']) ? $_POST['p_recipient'] : $pm_data['pm_from_nick'];
		$p_subject = isset($_POST['p_subject']) ? $_POST['p_subject'] : $pm_data['pm_subject'];
		$p_message = isset($_POST['p_message_text']) ? $_POST['p_message_text'] : '';

		if($p_subject != '' && strtolower(substr($p_subject,0,3)) != 're:') $p_subject = 'Re: '.$p_subject; // Falls noch kein Re: da ist, anfuegen

		$p_smilies = $p_signature = $p_bbcode = $p_saveoutbox = 1;
		$p_htmlcode = $p_rconfirmation = 0;


		//
		// Falls eine Antwort geschrieben wurde und die PM auch an einen selbst gerichtet ist
		//
		if(isset($_GET['doit']) && $pm_data['pm_type'] == 0) {
			$p_smilies = isset($_POST['p_smilies']) ? 1 : 0;
			$p_signature = isset($_POST['p_signature']) ? 1 : 0;
			$p_bbcode = isset($_POST['p_bbcode']) ? 1 : 0;
			$p_htmlcode = isset($_POST['p_htmlcode']) ? 1 : 0;
			$p_saveoutbox = isset($_POST['p_saveoutbox']) ? 1 : 0;
			$p_rconfirmation = isset($_POST['p_rconfirmation']) ? 1 : 0;

			$recipients = explode(',',$p_recipient);
			while(list($akt_key) = each($recipients)) {
				$recipients[$akt_key] = trim($recipients[$akt_key]);
				if(!$recipients[$akt_key] = get_user_id($recipients[$akt_key])) unset($recipients[$akt_key]);
			}
			reset($recipients);

			if(count($recipients) == 0) $error = $LNG['error_no_recipient'];
			elseif(trim($p_subject) == '') $error = $LNG['error_no_subject'];
			elseif(trim($p_message) == '') $error = $LNG['error_no_message'];
			else {
				while(list(,$akt_recipient_id) = each($recipients)) {
					$DB->query("INSERT INTO ".TBLPFX."pms (folder_id,pm_from_id,pm_to_id,pm_read_status,pm_type,pm_subject,pm_send_time,pm_enable_bbcode,pm_enable_smilies,pm_enable_htmlcode,pm_show_sig,pm_request_rconfirmation,pm_text) VALUES ('0','$USER_ID','$akt_recipient_id','0','0','$p_subject','".time()."','$p_bbcode','$p_smilies','$p_htmlcode','$p_signature','$p_rconfirmation','$p_message')");
					$new_pm_id = $DB->insert_id;

					if($p_saveoutbox == 1 && $CONFIG['enable_outbox'] == 1) {
						$DB->query("INSERT INTO ".TBLPFX."pms (folder_id,pm_from_id,pm_to_id,pm_read_status,pm_type,pm_subject,pm_send_time,pm_enable_bbcode,pm_enable_smilies,pm_enable_htmlcode,pm_show_sig,pm_request_rconfirmation,pm_text) VALUES ('1','$akt_recipient_id','$USER_ID','1','1','$p_subject','".time()."','$p_bbcode','$p_smilies','$p_htmlcode','$p_signature','$p_rconfirmation','$p_message')");
						$new_pm_id = $DB->insert_id;
					}
				}
				header("Location: index.php?faction=pms&$MYSID"); exit;
			}
		}

		$pm_send_date = format_date($pm_data['pm_send_time']); // Das Datum
		$pm_sender = ($pm_data['pm_type'] == 0) ? sprintf($LNG['from_x'],$pm_data['pm_from_nick']) : sprintf($LNG['to_x'],$pm_data['pm_from_nick']); // Der Sender bzw. Empfaenger

		if($pm_data['folder_id'] == 0) $pm_data['pm_folder_name'] = $LNG['Inbox'];
		elseif($pm_data['folder_id'] == 1) $pm_data['pm_folder_name'] = $LNG['Outbox'];


		//
		// Template laden
		//
		$pms_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['pms_viewpm']);


		//
		// Die Antwortform
		//
		if($pm_data['pm_type'] == 0) {
			multimutate('p_recipient','p_subject','p_message');

			$c = ' checked';
			$checked['smilies'] = ($p_smilies == 1) ? $c : '';
			$checked['signature'] = ($p_signature == 1) ? $c : '';
			$checked['bbcode'] = ($p_bbcode == 1) ? $c : '';
			$checked['htmlcode'] = ($p_htmlcode == 1) ? $c : '';
			$checked['saveoutbox'] = ($p_saveoutbox == 1) ? $c : '';
			$checked['rconfirmation'] = ($p_rconfirmation == 1) ? $c : '';


			//
			// Die Bloecke...
			//
			if($error != '') $pms_tpl->blocks['errorrow']->parse_code(); //...Fehler...
			if($CONFIG['allow_pms_smilies'] == 1) $pms_tpl->blocks['replyform']->blocks['smiliescheck']->parse_code(); //...Smilies...
			if($CONFIG['enable_sig'] == 1 && $CONFIG['allow_pms_signature']) $pms_tpl->blocks['replyform']->blocks['sigcheck']->parse_code(); //...Signatur...
			if($CONFIG['allow_pms_bbcode'] == 1) { //...BBCode...
				$bbcode_box = get_bbcode_box();

				$pms_tpl->blocks['replyform']->blocks['bbcoderow']->parse_code();
				$pms_tpl->blocks['replyform']->blocks['bbcodecheck']->parse_code();
			}
			if($CONFIG['allow_pms_htmlcode'] == 1) $pms_tpl->blocks['replyform']->blocks['htmlcodecheck']->parse_code(); //...HTML...
			if($CONFIG['enable_outbox'] == 1) $pms_tpl->blocks['replyform']->blocks['saveoutboxcheck']->parse_code(); //...Postausgang...
			if($CONFIG['allow_pms_rconfirmation'] == 1) $pms_tpl->blocks['replyform']->blocks['rconfirmationcheck']->parse_code(); //...Lesebestaetigung


			$pms_tpl->blocks['replyform']->parse_code();
		}

		$pm_data['pm_subject'] = mutate($pm_data['pm_subject']);
		$pm_data['pm_text'] = nlbr(mutate($pm_data['pm_text']));


		add_navbar_items(array($pm_data['pm_folder_name'],"index.php?faction=pms&amp;mode=viewfolder&amp;folder_id=".$pm_data['folder_id']."&amp;$MYSID"),array($LNG['View_private_message'],"index.php?faction=pms&amp;mode=viewpm&amp;pm_id=$pm_id&amp;$MYSID"));

		include_once('pheader.php');
		show_navbar();
		$pms_tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;
}

?>