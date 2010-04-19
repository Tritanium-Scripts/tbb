<?php
/**
*
* Tritanium Bulletin Board 2 - viewprofile.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$profile_id = isset($_GET['profile_id']) ? intval($_GET['profile_id']) : 0;

if(!$profile_data = get_user_data($profile_id)) die('Kann Profildaten nicht laden!');

add_navbar_items(array($LNG['View_profile'],"index.php?faction=viewprofile&amp;profile_id=$profile_id&amp$MYSID"));

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
		else { // Falls der User ein ganz normaler User ist...
			while(list(,$akt_rank) = each($RANKS_DATA[0])) { // Die Rangliste durchlaufen
				if($akt_rank['rank_posts'] > $profile_data['user_posts']) break;

				$profile_rank_text = $akt_rank['rank_name']; // ...den Namen das Rangs verwenden...
				$profile_rank_pic = $akt_rank['rank_gfx']; // ...und das Bild des Rangs verwenden
			}
			reset($RANKS_DATA[0]); // Das Array fuer den naechsten User vorbereiten
		}

		$profile_register_date = format_date($profile_data['user_regtime']);

		$viewprofile_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewprofile_default']);

		include_once('pheader.php');
		show_navbar();
		$viewprofile_tpl->parse_code(TRUE);
		include_once('ptail.php');
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
				show_navbar();
				show_message($LNG['Email_sent'],$LNG['message_email_sent'].'<br />'.sprintf($LNG['click_here_back_profile'],"<a href=\"index.php?faction=viewprofile&amp;profile_id=$profile_id&amp;$MYSID\">",'</a>'));
				include_once('ptail.php'); exit;
			}
		}

		$p_mail_message = myhtmlentities($p_mail_message);
		$p_mail_subject = myhtmlentities($p_mail_subject);

		$viewprofile_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewprofile_sendmail']);

		include_once('pheader.php');
		show_navbar();
		$viewprofile_tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;
}

?>