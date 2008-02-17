<?php
/**
*
* Tritanium Bulletin Board 2 - viewprofile.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');
require_once('bbcode.php');

$profile_id = isset($_GET['profile_id']) ? intval($_GET['profile_id']) : 0;

if(!$profile_data = get_user_data($profile_id)) die('Kann Profildaten nicht laden!');

$NAVBAR->addElements('left',(array($LNG['View_profile'],"index.php?faction=viewprofile&amp;profile_id=$profile_id&amp$MYSID")));

switch(@$_GET['mode']) {
	default:
		$RANKS_DATA = cache_get_ranks_data();

		$profile_rank_text = $profile_rank_pic = '';

		//
		// Rangbild und Rangtext des Users festlegen
		//
		if($profile_data['user_rank_id'] != 0) { // Falls der User einen speziellen Rang zugewiessen bekommen hat...
			$profile_rank_text = $RANKS_DATA[1][$profile_data['user_rank_id']]['rank_name']; // ...den Namen des Rang verwenden...
			$profile_rank_pic = $RANKS_DATA[1][$profile_data['user_rank_id']]['rank_gfx']; // ...und das Bild des Rangs verwenden
		}
		elseif($profile_data['user_is_admin'] == 1) { // Falls der User Admnistrator ist...
			$profile_rank_text = $LNG['Administrator']; // ...seinen Rang darauf setzen...
			$profile_rank_pic = '<img src="'.$CONFIG['admin_rank_pic'].'" alt="" border="0" />'; // ...und das entsprechende Bild verwenden
		}
		elseif($profile_data['user_is_supermod'] == 1) { // Falls der User Supermoderator ist...
			$profile_rank_text = $LNG['Supermoderator']; // ...seinen Rang darauf setzen...
			$profile_rank_pic = '<img src="'.$CONFIG['supermod_rank_pic'].'" alt="" border="0" />'; // ...und das entsprechende Bild verwenden
		}
		else { // Falls der User ein ganz normaler User ist...
			foreach($RANKS_DATA[0] AS $cur_rank) {
				if($cur_rank['rank_posts'] > $profile_data['user_posts']) break;

				$profile_rank_text = $cur_rank['rank_name']; // ...den Namen das Rangs verwenden...
				$profile_rank_pic = $cur_rank['rank_gfx']; // ...und das Bild des Rangs verwenden
			}
			reset($RANKS_DATA[0]); // Das Array fuer den naechsten User vorbereiten
		}

		$profile_register_date = format_date($profile_data['user_regtime']);
		
		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewprofile']);

		$USER_DATA['user_mod_status'] = check_mod_status($USER_ID);
		if($USER_DATA['user_auth_profile_notes'] == 1 || $USER_DATA['user_is_admin'] == 1 || $USER_DATA['user_is_supermod'] == 1 || $USER_DATA['user_mod_status'] == TRUE) {
			if($USER_DATA['user_is_admin'] == 1 || $USER_DATA['user_is_supermod'] == 1 || $USER_DATA['user_mod_status'] == TRUE) $DB->query("SELECT t1.*,t2.user_nick FROM ".TBLPFX."profile_notes AS t1 LEFT JOIN ".TBLPFX."users AS t2 ON t1.user_id=t2.user_id WHERE t1.profile_id='$profile_id' AND (t1.user_id='$USER_ID' OR t1.note_is_public='1') ORDER BY t1.note_time DESC");
			else $DB->query("SELECT * FROM ".TBLPFX."profile_notes WHERE profile_id='$profile_id' AND user_id='$USER_ID' ORDER BY note_time DESC");
			
			$profile_notes_counter = $DB->affected_rows;
			while($cur_note = $DB->fetch_array()) {
				$cur_note_date = format_date($cur_note['note_time']);
				$cur_note['note_text'] = nlbr(bbcode(myhtmlentities($cur_note['note_text'])));
				$tpl->blocks['notestable']->blocks['noterow']->parse_code(FALSE,TRUE);
			}
			
			$tpl->blocks['notestable']->parse_code();
		}
		
		include_once('pheader.php');
		$tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;
	
	case 'addnote':
		$USER_DATA['user_mod_status'] = check_mod_status($USER_ID);
		if($USER_DATA['user_auth_profile_notes'] != 1 && $USER_DATA['user_is_admin'] != 1 && $USER_DATA['user_is_supermod'] != 1 && $USER_DATA['user_mod_status'] != TRUE) die('Kein Zugriff');
		
		$p_note_text = isset($_POST['p_note_text']) ? $_POST['p_note_text'] : '';
		$p_note_is_public = 0;
		
		if(isset($_GET['doit'])) {
			$p_note_is_public = isset($_POST['p_note_is_public']) ? 1 : 0;
			
			// Oeffentlich darf man nur als Admin oder Mod posten...
			if($USER_DATA['user_is_admin'] != 1 && $USER_DATA['user_is_supermod'] != 1 && $USER_DATA['user_mod_status'] != TRUE) $p_note_is_public = 0;
			
			$DB->query("INSERT INTO ".TBLPFX."profile_notes (user_id,profile_id,note_time,note_is_public,note_text) VALUES ('$USER_ID','$profile_id','".time()."','$p_note_is_public','$p_note_text')");
			
			header("Location: index.php?faction=viewprofile&profile_id=$profile_id&{$MYSID}"); exit;
		}
		
		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewprofile_addnote']);
		
		$NAVBAR->addElements('left',array($LNG['Add_note'],"index.php?faction=viewprofile&amp;profile_id=$profile_id&amp;{$MYSID}"));
		
		include_once('pheader.php');
		$tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;
	
	case 'editnote':
		$note_id = isset($_GET['note_id']) ? intval($_GET['note_id']) : 0;
		if(!$note_data = get_profile_note_data($note_id)) die('Kann Daten nicht laden: Profilnotiz');
		if($USER_DATA['user_is_admin'] != 1 && $note_data['user_id'] != $USER_ID) die('Kein Zugriff');
		$USER_DATA['user_mod_status'] = check_mod_status($USER_ID);
		
		$p_note_text = isset($_POST['p_note_text']) ? $_POST['p_note_text'] : addslashes($note_data['note_text']);
		$p_note_is_public = $note_data['note_is_public'];
		
		if(isset($_GET['doit'])) {
			$p_note_is_public = isset($_POST['p_note_is_public']) ? 1 : 0;
			
			// Oeffentlich darf man nur als Admin oder Mod posten...
			if($USER_DATA['user_is_admin'] != 1 && $USER_DATA['user_is_supermod'] != 1 && $USER_DATA['user_mod_status'] != TRUE) $p_note_is_public = 0;
			
			$DB->query("UPDATE ".TBLPFX."profile_notes SET note_is_public='$p_note_is_public', note_text='$p_note_text' WHERE note_id='$note_id'");
			
		}
		
		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewprofile_editnote']);

		$NAVBAR->addElements('left',array($LNG['Edit_note'],"index.php?faction=viewprofile&amp;profile_id=$profile_id&amp;{$MYSID}"));
		
		include('pheader.php');
		$tpl->parse_code(TRUE);
		include('ptail.php');
	break;
	
	case 'deletenote':
		$note_id = isset($_GET['note_id']) ? intval($_GET['note_id']) : 0;
		if(!$note_data = get_profile_note_data($note_id)) die('Kann Daten nicht laden: Profilnotiz');
		if($USER_DATA['user_is_admin'] != 1 && $note_data['user_id'] != $USER_ID) die('Kein Zugriff');
		
		$DB->query("DELETE FROM ".TBLPFX."profile_notes WHERE note_id='$note_id'");

		header("Location: index.php?faction=viewprofile&profile_id=$profile_id&{$MYSID}"); exit;		
	break;

	case 'sendmail':
		if($USER_LOGGED_IN == 0 || $CONFIG['enable_email_formular'] == 0) die('Das geht wohl so nicht...!');

		add_navbar_items(array($LNG['Send_email'],"index.php?faction=viewprofile&amp;profile_id=$profile_id&ampmode=sendmail&amp;$MYSID"));

		$p_mail_subject = isset($_POST['p_mail_subject']) ? $_POST['p_mail_subject'] : '';
		$p_mail_message = isset($_POST['p_mail_message']) ? $_POST['p_mail_message'] : '';

		$error = '';

		if(isset($_GET['doit'])) {
			$p_mail_message = mysslashes($p_mail_message);
			$p_mail_subject = mysslashes($p_mail_subject);

			if(trim($p_mail_subject) == '') $error = $LNG['error_no_subject'];
			elseif(trim($p_mail_message) == '') $error = $LNG['error_no_message'];
			else {
				mymail($USER_DATA['user_nick'].' <'.$USER_DATA['user_email'].'>',$profile_data['user_nick'].' <'.$profile_data['user_email'].'>',$p_mail_subject,$p_mail_message);
				add_navbar_items(array($LNG['Email_sent'],''));

				include_once('pheader.php');
				show_message($LNG['Email_sent'],$LNG['message_email_sent'].'<br />'.sprintf($LNG['click_here_back_profile'],"<a href=\"index.php?faction=viewprofile&amp;profile_id=$profile_id&amp;$MYSID\">",'</a>'));
				include_once('ptail.php'); exit;
			}
		}

		$p_mail_message = myhtmlentities($p_mail_message);
		$p_mail_subject = myhtmlentities($p_mail_subject);

		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewprofile_sendmail']);

		include_once('pheader.php');
		$tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;
}

?>