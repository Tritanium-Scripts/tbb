<?php
/**
*
* Tritanium Bulletin Board 2 - pms.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

if($USER_LOGGED_IN != 1) die('Nicht eingeloggt!');
elseif($CONFIG['enable_pms'] != 1) {
	include_once('pheader.php');
	show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Function_deactivated']);
	show_message('Function_deactivated','message_function_deactivated');
	include_once('ptail.php'); exit;
}

switch(@$_GET['mode']) {
	default:
		$title_add[] = $lng['Private_messages'];

		$pms_tpl = new template;
		$pms_tpl->load($template_path.'/'.$tpl_config['tpl_pms_overview']);

		$pms_unread_counter = array();
		$db->query("SELECT folder_id,COUNT(*) AS pms_counter FROM ".TBLPFX."pms WHERE pm_to_id='$USER_ID' AND pm_read_status='0' GROUP BY folder_id");
		while($akt_counter = $db->fetch_array())
			$pms_unread_counter[$akt_counter['folder_id']] = $akt_counter['pms_counter'];

		$pms_read_counter = array();
		$db->query("SELECT folder_id,COUNT(*) AS pms_counter FROM ".TBLPFX."pms WHERE pm_to_id='$USER_ID' AND pm_read_status='1' GROUP BY folder_id");
		while($akt_counter = $db->fetch_array())
			$pms_read_counter[$akt_counter['folder_id']] = $akt_counter['pms_counter'];

		$db->query("SELECT folder_id,folder_name FROM ".TBLPFX."pms_folders WHERE user_id='$USER_ID' ORDER BY folder_name");
		$folders_data = $db->raw2array();

		array_unshift($folders_data, // Fuegt an den Anfang die Standardordner hinzu...
			array('folder_id'=>0,'folder_name'=>$lng['Inbox']), // ...Posteingang...
			array('folder_id'=>1,'folder_name'=>$lng['Outbox']) // ...Postausgang
		);

		reset($folders_data);

		while(list(,$akt_folder) = each($folders_data)) {
			$akt_read_messages = isset($pms_read_counter[$akt_folder['folder_id']]) ? $pms_read_counter[$akt_folder['folder_id']] : 0;
			$akt_unread_messages = isset($pms_unread_counter[$akt_folder['folder_id']]) ? $pms_unread_counter[$akt_folder['folder_id']] : 0;

			$akt_read_messages = ($akt_read_messages == 0) ? $lng['No_read_messages'] : sprintf($lng['x_read_messages'],$akt_read_messages);
			$akt_unread_messages = ($akt_unread_messages == 0) ? $lng['No_unread_messages'] : sprintf($lng['x_unread_messages'],$akt_unread_messages);

			$pms_tpl->blocks['foldertbl']->parse_code(FALSE,TRUE);
		}

		include_once('pheader.php');

		show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Private_messages']);

		$pms_tpl->parse_code(TRUE);

		include_once('ptail.php');
	break;

	case 'addfolder':
		$title_add[] = $lng['Private_messages'];
		$title_add[] = $lng['Add_folder'];

		$db->query("SELECT COUNT(*) AS folders_counter FROM ".TBLPFX."pms_folders WHERE user_id='$USER_ID'");
		list($folders_counter) = $db->fetch_array();

		if($CONFIG['maximum_pms_folders'] != -1 && $CONFIG['maximum_pms_folders'] <= $folders_counter) {
			include_once('pheader.php');
			show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=pms&amp;$MYSID\">".$lng['Private_messages']."</a>\r".$lng['No_more_folders_allowed']);
			show_message('No_more_folders_allowed','message_no_more_folders_allowed');
			include_once('ptail.php'); exit;
		}

		$error = '';

		$p_name = isset($_POST['p_name']) ? $_POST['p_name'] : '';

		if(isset($_GET['doit'])) {
			if(trim($p_name) == '') $error = $lng['error_no_name'];
			else {
				$db->query("SELECT MAX(folder_id) AS max_folder_id FROM ".TBLPFX."pms_folders WHERE user_id='$USER_ID'");
				list($max_folder_id) = $db->fetch_array();

				$new_folder_id = ($max_folder_id < 3) ? 3 : $max_folder_id + 1;

				$db->query("INSERT INTO ".TBLPFX."pms_folders (folder_id,user_id,folder_name) VALUES ('$new_folder_id','$USER_ID','$p_name')");

				header("Location: index.php?faction=pms&$MYSID"); exit;
			}
		}

		$pms_tpl = new template;
		$pms_tpl->load($template_path.'/'.$tpl_config['tpl_pms_addfolder']);

		if($error != '') $pms_tpl->blocks['errorrow']->parse_code();
		else $pms_tpl->unset_block('errorrow');

		include_once('pheader.php');

		show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=pms&amp;$MYSID\">".$lng['Private_messages']."</a>\r".$lng['Add_folder']);

		$pms_tpl->parse_code(TRUE);

		include_once('ptail.php');
	break;

	case 'newpm':
		$title_add[] = $lng['Private_messages'];
		$title_add[] = $lng['New_private_message'];

		$error = '';

		$p_recipient = isset($_POST['p_recipient']) ? $_POST['p_recipient'] : '';
		$p_subject = isset($_POST['p_subject']) ? $_POST['p_subject'] : '';
		$p_message = isset($_POST['p_post']) ? $_POST['p_post'] : '';

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

			if(count($recipients) == 0) $error = $lng['error_no_recipient'];
			elseif(trim($p_subject) == '') $error = $lng['error_no_subject'];
			elseif(trim($p_message) == '') $error = $lng['error_no_messsage'];
			else {
				while(list(,$akt_recipient_id) = each($recipients)) {
					$db->query("INSERT INTO ".TBLPFX."pms (folder_id,pm_from_id,pm_to_id,pm_read_status,pm_type,pm_subject,pm_send_time,pm_enable_bbcode,pm_enable_smilies,pm_enable_htmlcode,pm_show_sig,pm_request_rconfirmation) VALUES ('0','$USER_ID','$akt_recipient_id','0','0','$p_subject',NOW(),'$p_bbcode','$p_smilies','$p_htmlcode','$p_signature','$p_rconfirmation')");
					$new_pm_id = $db->insert_id;
					$db->query("INSERT INTO ".TBLPFX."pms_text (pm_id,pm_text) VALUES ('$new_pm_id','$p_message')");

					if($p_saveoutbox == 1 && $CONFIG['enable_outbox'] == 1) {
						$db->query("INSERT INTO ".TBLPFX."pms (folder_id,pm_from_id,pm_to_id,pm_read_status,pm_type,pm_subject,pm_send_time,pm_enable_bbcode,pm_enable_smilies,pm_enable_htmlcode,pm_show_sig,pm_request_rconfirmation) VALUES ('1','$akt_recipient_id','$USER_ID','1','1','$p_subject',NOW(),'$p_bbcode','$p_smilies','$p_htmlcode','$p_signature','$p_rconfirmation')");
						$new_pm_id = $db->insert_id;
						$db->query("INSERT INTO ".TBLPFX."pms_text (pm_id,pm_text) VALUES ('$new_pm_id','$p_message')");
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

		$pms_tpl = new template;
		$pms_tpl->load($template_path.'/'.$tpl_config['tpl_pms_newpm']);


		//
		// Die Bloecke...
		//
		if($error != '') $pms_tpl->blocks['errorrow']->parse_code(); //...Fehler...
		else $pms_tpl->unset_block('errorrow');

		if($CONFIG['allow_pms_smilies'] == 1) $pms_tpl->blocks['smiliescheck']->parse_code(); //...Smilies...
		else $pms_tpl->unset_block('smiliescheck');

		if($CONFIG['enable_sig'] == 1 && $CONFIG['allow_pms_signature']) $pms_tpl->blocks['sigcheck']->parse_code(); //...Signatur...
		else $pms_tpl->unset_block('sigcheck');

		if($CONFIG['allow_pms_bbcode'] == 1) { //...BBCode...
			$bbcode_box = get_bbcode_box();

			$pms_tpl->blocks['bbcoderow']->parse_code();
			$pms_tpl->blocks['bbcodecheck']->parse_code();
		}
		else {
			$pms_tpl->unset_block('bbcodecheck');
			$pms_tpl->unset_block('bbcoderow');
		}

		if($CONFIG['allow_pms_htmlcode'] == 1) $pms_tpl->blocks['htmlcodecheck']->parse_code(); //...HTML...
		else $pms_tpl->unset_block('htmlcodecheck');

		if($CONFIG['enable_outbox'] == 1) $pms_tpl->blocks['saveoutboxcheck']->parse_code(); //...Postausgang...
		else $pms_tpl->unset_block('saveoutboxcheck');

		if($CONFIG['allow_pms_rconfirmation'] == 1) $pms_tpl->blocks['rconfirmationcheck']->parse_code(); //...Lesebestaetigung
		else $pms_tpl->unset_block('rconfirmationcheck');


		include_once('pheader.php');

		show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=pms&amp;$MYSID\">".$lng['Private_messages']."</a>\r".$lng['New_private_message']);

		$pms_tpl->parse_code(TRUE);

		include_once('ptail.php');
	break;

	case 'newpmreceived':

		$pms_tpl = new template;
		$pms_tpl->load($template_path.'/'.$tpl_config['tpl_pms_newpmreceived']);

		include_once('pop_pheader.php');

		$pms_tpl->parse_code(TRUE);

		include_once('pop_ptail.php');
	break;

	case 'viewfolder':
		$folder_id = isset($_GET['folder_id']) ? $_GET['folder_id'] : 0;
		$z = isset($_GET['z']) ? $_GET['z'] : 1;

		if($folder_id == 0) $folder_data = array('folder_id'=>0,'folder_name'=>$lng['Inbox']);
		elseif($folder_id == 1) $folder_data = array('folder_id'=>1,'folder_name'=>$lng['Outbox']);
		else {
			$db->query("SELECT folder_id, folder_name FROM ".TBLPFX."pms_folders WHERE user_id='$USER_ID' AND folder_id='$folder_id'");
			if($db->affected_rows == 0) {
				$folder_data = array('folder_id'=>0,'folder_name'=>$lng['Inbox']);
				$folder_id = 0;
			}
			else $folder_data = $db->fetch_array();
		}

		$title_add[] = $lng['Private_messages'];
		$title_add[] = $folder_data['folder_name'];

		$pms_tpl = new template;
		$pms_tpl->load($template_path.'/'.$tpl_config['tpl_pms_viewfolder']);

		$db->query("SELECT folder_name,folder_id FROM ".TBLPFX."pms_folders WHERE user_id='$USER_ID' ORDER BY folder_name");
		$folders_data = $db->raw2array();

		array_unshift($folders_data, // Fuegt an den Anfang die Standardordner hinzu...
			array('folder_id'=>0,'folder_name'=>$lng['Inbox']), // ...Posteingang...
			array('folder_id'=>1,'folder_name'=>$lng['Outbox']) // ...Postausgang
		);
		reset($folders_data);

		while(list(,$akt_folder) = each($folders_data))
			$pms_tpl->blocks['folderrow']->parse_code(FALSE,TRUE);

		$db->query("SELECT COUNT(*) FROM ".TBLPFX."pms WHERE pm_to_id='$USER_ID' AND folder_id='$folder_id'");
		list($pms_counter) = $db->fetch_array();

		$CONFIG['pms_per_page'] = 10;

		$page_counter = ceil($pms_counter/$CONFIG['pms_per_page']);

		if($z == 'last' || $z > $page_counter && $page_counter != 0) $z = $page_counter;

		$start = $z*$CONFIG['pms_per_page']-$CONFIG['pms_per_page'];
		$page_listing = array();

		$pre = $suf = '';

		if($page_counter > 0) {

			if($page_counter > 5) {
				if($z > 2 && $z < $page_counter-2) {
					$page_listing = array($z-2,$z-1,$z,$z+1,$z+2);
				}
				elseif($z <= 2) {
					$page_listing = array(1,2,3,4,5);
				}
				elseif($z >= $page_counter-2) {
					$page_listing = array($page_counter-4,$page_counter-3,$page_counter-2,$page_counter-1,$page_counter);
				}
			}
			else {
				for($i = 1; $i < $page_counter+1; $i++) {
					$page_listing[] = $i;
				}
			}
		}
		else $page_listing[] = 1;
		for($i = 0; $i < count($page_listing); $i++) {
			if($page_listing[$i] != $z) $page_listing[$i] = "<a href=\"index.php?faction=pms&amp;mode=viewfolder&amp;folder_id=$folder_id&amp;z=".$page_listing[$i]."&amp;$MYSID\">".$page_listing[$i].'</a>';
		}


		if($z > 1) $pre = '<a href="index.php?faction=pms&amp;mode=viewfolder&amp;folder_id='.$folder_id.'&amp;z=1&amp;'.$MYSID.'">&#171;</a>&nbsp;<a href="index.php?faction=pms&amp;mode=viewfolder&amp;folder_id='.$folder_id.'&amp;z='.($z-1).'&amp;'.$MYSID.'">&#8249;</a>&nbsp;&nbsp;';
		if($z < $page_counter) $suf = '&nbsp;&nbsp;<a href="index.php?faction=pms&amp;mode=viewfolder&amp;folder_id='.$folder_id.'&amp;z='.($z+1).'&'.$MYSID.'">&#8250;</a>&nbsp;<a href="index.php?faction=pms&amp;mode=viewfolder&amp;folder_id='.$folder_id.'&amp;z=last&amp;'.$MYSID.'">&#187;</a>';

		$page_listing = sprintf($lng['Pages'],$pre.implode(' | ',$page_listing).$suf);


		$db->query("SELECT t1.pm_id,t1.pm_subject,UNIX_TIMESTAMP(t1.pm_send_time) AS pm_send_time, t1.pm_from_id, t1.pm_type, t1.pm_read_status, t2.user_nick AS pm_from_nick FROM ".TBLPFX."pms AS t1 LEFT JOIN ".TBLPFX."users AS t2 ON t1.pm_from_id=t2.user_id WHERE pm_to_id='$USER_ID' AND folder_id='$folder_id' ORDER BY pm_send_time DESC LIMIT $start,".$CONFIG['pms_per_page']);
		if($db->affected_rows == 0) {
			$pms_tpl->blocks['nopms']->parse_code();
			$pms_tpl->unset_block('pmrow');
		}
		else {
			$pms_tpl->unset_block('nopms');
			while($akt_pm = $db->fetch_array()) {
				$akt_sender = ($akt_pm['pm_type'] == 0) ? sprintf($lng['from_x'],$akt_pm['pm_from_nick']) : sprintf($lng['to_x'],$akt_pm['pm_from_nick']);
				$akt_date = format_date($akt_pm['pm_send_time']);
				if($akt_pm['pm_read_status'] == 0) $akt_pm['pm_subject'] = '<b>'.$akt_pm['pm_subject'].'</b>';
				$pms_tpl->blocks['pmrow']->parse_code(FALSE,TRUE);
			}
		}

		include_once('pheader.php');

		show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=pms&amp;$MYSID\">".$lng['Private_messages']."</a>\r".$folder_data['folder_name']);

		$pms_tpl->parse_code(TRUE);

		include_once('ptail.php');

	break;

	case 'markread':
		$pm_ids = isset($_POST['pm_ids']) ? $_POST['pm_ids'] : array();

		$return_z = isset($_GET['return_z']) ? $_GET['return_z'] : 1; // Die Seite, zu der zurueckgekehrt werden soll
		$return_f = isset($_GET['return_f']) ? $_GET['return_f'] : 0; // Der Ordner, in den zurueckgekehrt werden soll

		if(count($pm_ids) != 0) {
			$pm_ids = implode("','",$pm_ids);
			$db->query("UPDATE ".TBLPFX."pms SET pm_read_status='1' WHERE pm_id IN ('$pm_ids') AND pm_to_id='$USER_ID' AND pm_request_rconfirmation<>'1'");
		}

		header("Location: index.php?faction=pms&mode=viewfolder&folder_id=$return_f&z=$return_z&$MYSID"); exit;
	break;

	case 'deletepms':
		$pm_id = isset($_GET['pm_id']) ? $_GET['pm_id'] : 0;
		$pm_ids = isset($_POST['pm_ids']) ? $_POST['pm_ids'] : array();

		$return_z = isset($_GET['return_z']) ? $_GET['return_z'] : 1; // Die Seite, zu der zurueckgekehrt werden soll
		$return_f = isset($_GET['return_f']) ? $_GET['return_f'] : 0; // Der Ordner, in den zurueckgekehrt werden soll

		if($pm_id != 0) {
			$db->query("DELETE FROM ".TBLPFX."pms WHERE pm_id='$pm_id' AND pm_to_id='$USER_ID'");
			if($db->affected_rows > 0)
				$db->query("DELETE FROM ".TBLPFX."pms_text WHERE pm_id='$pm_id'");
		}

		if(count($pm_ids) != 0) {
			$pm_ids = implode("','",$pm_ids);
			$db->query("SELECT pm_id FROM ".TBLPFX."pms WHERE pm_id IN ('$pm_ids') AND pm_to_id='$USER_ID'");

			$pm_ids = array();
			while(list($akt_pm_id) = $db->fetch_array())
				$pm_ids[] = $akt_pm_id;

			$pm_ids = implode("','",$pm_ids);
			$db->query("DELETE FROM ".TBLPFX."pms WHERE pm_id IN ('$pm_ids')");
			$db->query("DELETE FROM ".TBLPFX."pms_text WHERE pm_id IN ('$pm_ids')");
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
		$db->query("SELECT t1.pm_subject, t1.pm_request_rconfirmation, UNIX_TIMESTAMP(t1.pm_send_time) AS pm_send_time, t1.folder_id, t1.pm_type, t1.pm_to_id, t1.pm_from_id, t1.pm_read_status, t2.user_nick AS pm_from_nick, t3.pm_text, t4.folder_name AS pm_folder_name FROM ".TBLPFX."pms AS t1, ".TBLPFX."pms_text AS t3 LEFT JOIN ".TBLPFX."users AS t2 ON t1.pm_from_id=t2.user_id LEFT JOIN ".TBLPFX."pms_folders AS t4 ON t4.folder_id=t1.folder_id AND t4.user_id='$USER_ID' WHERE t1.pm_id='$pm_id' AND t3.pm_id=t1.pm_id");
		if($db->affected_rows == 0) die('Kann PM-Daten nicht laden!');
		$pm_data = $db->fetch_array();


		//
		// Ueberpruefen ob...
		//
		if($pm_data['pm_to_id'] != $USER_ID) die('Kein Zugriff auf diese Nachricht!'); // ...User Zugriff auf PM hat...
		if($pm_data['pm_read_status'] == 0) {  // ...die PM schon gelesen ist...
			$db->query("UPDATE ".TBLPFX."pms SET pm_read_status='1' WHERE pm_id='$pm_id'");
			if($pm_data['pm_request_rconfirmation'] == 1 && $CONFIG['allow_pms_rconfirmation'] == 1 && $pm_data['pm_from_id'] != 0) { // ...und eine Lesebestaetigung angefordert wurde
				$db->query("INSERT INTO ".TBLPFX."pms (folder_id,pm_from_id,pm_to_id,pm_read_status,pm_type,pm_subject,pm_send_time,pm_enable_bbcode,pm_enable_smilies,pm_enable_htmlcode,pm_show_sig,pm_request_rconfirmation) VALUES ('0','$USER_ID','".$pm_data['pm_from_id']."','0','0','".$lng['read_confirmation_subject']."',NOW(),'0','0','0','0','0')");
				$new_pm_id = $db->insert_id;
				$db->query("INSERT INTO ".TBLPFX."pms_text (pm_id,pm_text) VALUES ('$new_pm_id','".sprintf($lng['read_confirmation_message'],$pm_data['pm_from_nick'],$pm_data['pm_subject'])."')");
			}
		}


		//
		// Ein paar Standardsachen
		//
		$p_recipient = isset($_POST['p_recipient']) ? $_POST['p_recipient'] : $pm_data['pm_from_nick'];
		$p_subject = isset($_POST['p_subject']) ? $_POST['p_subject'] : $pm_data['pm_subject'];
		$p_message = isset($_POST['p_post']) ? $_POST['p_post'] : '';

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

			if(count($recipients) == 0) $error = $lng['error_no_recipient'];
			elseif(trim($p_subject) == '') $error = $lng['error_no_subject'];
			elseif(trim($p_message) == '') $error = $lng['error_no_messsage'];
			else {
				while(list(,$akt_recipient_id) = each($recipients)) {
					$db->query("INSERT INTO ".TBLPFX."pms (folder_id,pm_from_id,pm_to_id,pm_read_status,pm_type,pm_subject,pm_send_time,pm_enable_bbcode,pm_enable_smilies,pm_enable_htmlcode,pm_show_sig,pm_request_rconfirmation) VALUES ('0','$USER_ID','$akt_recipient_id','0','0','$p_subject',NOW(),'$p_bbcode','$p_smilies','$p_htmlcode','$p_signature','$p_rconfirmation')");
					$new_pm_id = $db->insert_id;
					$db->query("INSERT INTO ".TBLPFX."pms_text (pm_id,pm_text) VALUES ('$new_pm_id','$p_message')");

					if($p_saveoutbox == 1 && $CONFIG['enable_outbox'] == 1) {
						$db->query("INSERT INTO ".TBLPFX."pms (folder_id,pm_from_id,pm_to_id,pm_read_status,pm_type,pm_subject,pm_send_time,pm_enable_bbcode,pm_enable_smilies,pm_enable_htmlcode,pm_show_sig,pm_request_rconfirmation) VALUES ('1','$akt_recipient_id','$USER_ID','1','1','$p_subject',NOW(),'$p_bbcode','$p_smilies','$p_htmlcode','$p_signature','$p_rconfirmation')");
						$new_pm_id = $db->insert_id;
						$db->query("INSERT INTO ".TBLPFX."pms_text (pm_id,pm_text) VALUES ('$new_pm_id','$p_message')");
					}
				}
				header("Location: index.php?faction=pms&$MYSID"); exit;
			}
		}

		$pm_send_date = format_date($pm_data['pm_send_time']); // Das Datum
		$pm_sender = ($pm_data['pm_type'] == 0) ? sprintf($lng['from_x'],$pm_data['pm_from_nick']) : sprintf($lng['to_x'],$pm_data['pm_from_nick']); // Der Sender bzw. Empfaenger

		if($pm_data['folder_id'] == 0) $pm_data['pm_folder_name'] = $lng['Inbox'];
		elseif($pm_data['folder_id'] == 1) $pm_data['pm_folder_name'] = $lng['Outbox'];


		//
		// Template laden
		//
		$pms_tpl = new template;
		$pms_tpl->load($template_path.'/'.$tpl_config['tpl_pms_viewpm']);


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
			else $pms_tpl->blocks['replyform']->unset_block('errorrow');

			if($CONFIG['allow_pms_smilies'] == 1) $pms_tpl->blocks['replyform']->blocks['smiliescheck']->parse_code(); //...Smilies...
			else $pms_tpl->blocks['replyform']->unset_block('smiliescheck');

			if($CONFIG['enable_sig'] == 1 && $CONFIG['allow_pms_signature']) $pms_tpl->blocks['replyform']->blocks['sigcheck']->parse_code(); //...Signatur...
			else $pms_tpl->blocks['replyform']->unset_block('sigcheck');

			if($CONFIG['allow_pms_bbcode'] == 1) { //...BBCode...
				$bbcode_box = get_bbcode_box();

				$pms_tpl->blocks['replyform']->blocks['bbcoderow']->parse_code();
				$pms_tpl->blocks['replyform']->blocks['bbcodecheck']->parse_code();
			}
			else {
				$pms_tpl->blocks['replyform']->unset_block('bbcodecheck');
				$pms_tpl->blocks['replyform']->unset_block('bbcoderow');
			}

			if($CONFIG['allow_pms_htmlcode'] == 1) $pms_tpl->blocks['replyform']->blocks['htmlcodecheck']->parse_code(); //...HTML...
			else $pms_tpl->blocks['replyform']->unset_block('htmlcodecheck');

			if($CONFIG['enable_outbox'] == 1) $pms_tpl->blocks['replyform']->blocks['saveoutboxcheck']->parse_code(); //...Postausgang...
			else $pms_tpl->blocks['replyform']->unset_block('saveoutboxcheck');

			if($CONFIG['allow_pms_rconfirmation'] == 1) $pms_tpl->blocks['replyform']->blocks['rconfirmationcheck']->parse_code(); //...Lesebestaetigung
			else $pms_tpl->blocks['replyform']->unset_block('rconfirmationcheck');


			$pms_tpl->blocks['replyform']->parse_code();
		}
		else $pms_tpl->unset_block('replyform');

		$pm_data['pm_subject'] = mutate($pm_data['pm_subject']);
		$pm_data['pm_text'] = nlbr(mutate($pm_data['pm_text']));


		//
		// Der <title>-Bereich
		//
		$title_add[] = $lng['Private_messages'];
		$title_add[] = $pm_data['pm_folder_name'];
		$title_add[] = $lng['View_private_message'];

		include_once('pheader.php');

		show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=pms&amp;$MYSID\">".$lng['Private_messages']."</a>\r<a href=\"index.php?faction=pms&amp;mode=viewfolder&amp;folder_id=".$pm_data['folder_id']."&amp;z=$return_z&amp;$MYSID\">".$pm_data['pm_folder_name']."</a>\r".$lng['View_private_message']);

		$pms_tpl->parse_code(TRUE);

		include_once('ptail.php');

	break;
}

?>