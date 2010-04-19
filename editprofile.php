<?php
/**
*
* Tritanium Bulletin Board 2 - editprofile.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

if($USER_LOGGED_IN != 1) die('Nicht eingeloggt!');

add_navbar_items(array($lng['View_change_my_profile'],"index.php?faction=editprofile&amp;$MYSID"));

switch(@$_GET['mode']) {
	default:
		$p_email = isset($_POST['p_email']) ? $_POST['p_email'] : $USER_DATA['user_email'];
		$p_name = isset($_POST['p_name']) ? $_POST['p_name'] : $USER_DATA['user_realname'];
		$p_location = isset($_POST['p_location']) ? $_POST['p_location'] : $USER_DATA['user_location'];
		$p_hp = isset($_POST['p_hp']) ? $_POST['p_hp'] : $USER_DATA['user_hp'];
		$p_interests = isset($_POST['p_interests']) ? $_POST['p_interests'] : $USER_DATA['user_interests'];
		$p_icq = isset($_POST['p_icq']) ? $_POST['p_icq'] : $USER_DATA['user_icq'];
		$p_yahoo = isset($_POST['p_yahoo']) ? $_POST['p_yahoo'] : $USER_DATA['user_yahoo'];
		$p_aim = isset($_POST['p_aim']) ? $_POST['p_aim'] : $USER_DATA['user_aim'];
		$p_msn = isset($_POST['p_msn']) ? $_POST['p_msn'] : $USER_DATA['user_msn'];
		$p_signature = isset($_POST['p_signature']) ? $_POST['p_signature'] : $USER_DATA['user_signature'];
		$p_avatar_address = isset($_POST['p_avatar_address']) ? $_POST['p_avatar_address'] : $USER_DATA['user_avatar_address'];
		$p_pw1 = isset($_POST['p_pw1']) ? $_POST['p_pw1'] : '';
		$p_pw2 = isset($_POST['p_pw2']) ? $_POST['p_pw2'] : '';
		$p_user_tz = isset($_POST['p_user_tz']) ? $_POST['p_user_tz'] : $USER_DATA['user_tz'];
		$p_user_hide_email = isset($_POST['p_user_hide_email']) ? $_POST['p_user_hide_email'] : $USER_DATA['user_hide_email'];
		$p_user_receive_emails = isset($_POST['p_user_receive_emails']) ? $_POST['p_user_receive_emails'] : $USER_DATA['user_receive_emails'];

		$error = '';
		$pwerror = '';

		if($p_hp != '') $p_hp = addhttp($p_hp);

		if(isset($_GET['doit'])) {
			if(trim($p_email) == '' || verify_email($p_email) != TRUE) $error = $lng['error_bad_email'];
			elseif(trim($p_pw1) != '' && $p_pw1 != $p_pw2) $pwerror = $lng['error_pws_no_match'];
			elseif($p_icq != '' && verify_icq_uin($p_icq) == FALSE) $error = $lng['error_bad_icq'];
			else {
				if(!isset($TIMEZONES[$p_user_tz]))
					$p_user_tz = $CONFIG['standard_tz'];

				if(trim($p_pw1) != '') {
					$p_pwc = mycrypt($p_pw1);
					$_SESSION['tbb_user_pw'] = $p_pwc;
				}
				else $p_pwc = $USER_DATA['user_pw'];

				$db->query("UPDATE ".TBLPFX."users SET user_email='$p_email', user_realname='$p_name', user_location='$p_location', user_hp='$p_hp', user_interests='$p_interests', user_icq='$p_icq', user_yahoo='$p_yahoo', user_aim='$p_aim', user_msn='$p_msn', user_signature='$p_signature', user_pw='$p_pwc', user_tz='$p_user_tz', user_hide_email='$p_user_hide_email', user_receive_emails='$p_user_receive_emails' WHERE user_id='$USER_ID'");

				add_navbar_items(array($lng['Profile_saved'],''));

				include_once('pheader.php');
				show_navbar();
				show_message($lng['Profile_saved'],$lng['message_profile_saved'].'<br />'.sprintf($lng['click_here_back_profile'],"<a href=\"index.php?faction=editprofile&amp;$MYSID\">",'</a>').'<br />'.sprintf($lng['click_here_back_forumindex'],"<a href=\"index.php?$MYSID\">",'</a>'));
				include_once('ptail.php'); exit;
			}
		}

		$editprofile_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editprofile_index']);

		if($error != '') $editprofile_tpl->blocks['errorrow']->parse_code();
		if($pwerror != '') $editprofile_tpl->blocks['pwerrorrow']->parse_code();
		if($CONFIG['enable_avatars'] == 1) $editprofile_tpl->blocks['avatarrow']->parse_code();

		while(list($akt_tz_id) = each($TIMEZONES)) {
			$akt_tz_name = $lng['tz_'.$akt_tz_id];
			$akt_checked = ($akt_tz_id == $p_user_tz) ? ' selected="selected"' : '';
			$editprofile_tpl->blocks['tzrow']->parse_code(FALSE,TRUE);
		}

		$p_email = mutate($p_email);
		$p_name = mutate($p_name);
		$p_location = mutate($p_location);
		$p_hp = mutate($p_hp);
		$p_interests = mutate($p_interests);
		$p_icq = mutate($p_icq);
		$p_yahoo = mutate($p_yahoo);
		$p_aim = mutate($p_aim);
		$p_msn = mutate($p_msn);
		$p_signature = mutate($p_signature);

		include_once('pheader.php');
		show_navbar();
		$editprofile_tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;

	case 'uploadavatar':
		if($CONFIG['enable_avatar_upload'] != 1) {
			include_once('pop_pheader.php');
			show_message($lng['Avatar_upload_disabled'],$lng['message_avatar_upload_disabled']);
			include_once('pop_ptail.php'); exit;
		}

		$error = '';

		if(isset($_GET['doit'])) {
			if(isset($_FILES['p_avatar_file']) == FALSE || $_FILES['p_avatar_file']['name'] == '') $error = $lng['error_invalid_file'];
			elseif($_FILES['p_avatar_file']['size'] > $CONFIG['max_avatar_file_size']*1024) $error = $lng['error_file_too_big'];
			else {
				preg_match("/^(.*)\.([^.]*)/i",strtolower($_FILES['p_avatar_file']['name']),$file_extension);
				$file_extension = $file_extension[2];

				$good_file_extensions = array(
					'jpg',
					'jpeg',
					'bmp',
					'png',
					'gif'
				);

				if(in_array($file_extension,$good_file_extensions) != TRUE) $error = $lng['error_invalid_file_extension'];
				else {

					//
					// Erst muss ueberprueft werden, ob der User nicht schon ein Avatar hochgeladen hat, und falls ja diesen loeschen
					//
					while(list(,$akt_extension) = each($good_file_extensions)) { // Die Dateiendungen durchgehen
						if(file_exists('upload/avatars/'.$USER_ID.'.'.$akt_extension) == TRUE) { // Falls eine Datei mit der aktuellen Dateiendung existiert...
							unlink('upload/avatars/'.$USER_ID.'.'.$akt_extension); // ...diese loeschen...
							break; // ...und die Schleife beenden, da der User maximal ein Avatar haben kann
						}
					}

					$remote_avatar_file_name = 'upload/avatars/'.$USER_ID.'.'.$file_extension;


					//
					// Jetzt kann der Avatar verschoben werden...
					//
					move_uploaded_file($_FILES['p_avatar_file']['tmp_name'],$remote_avatar_file_name); // Datei verschieben
					chmod('upload/avatars/'.$USER_ID.'.'.$file_extension,0777); // Datei aenderbar/loeschbar machen
					$db->query("UPDATE ".TBLPFX."users SET user_avatar_address='$remote_avatar_file_name' WHERE user_id='$USER_ID'"); // Neuen Avatar in der Datenbank aktualisieren

					$editprofile_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editprofile_avatarresult']);

					$avatar_address = $remote_avatar_file_name;
					$avatar_selected_text = sprintf($lng['avatar_selected_text'],'<img src="'.$remote_avatar_file_name.'" width="'.$CONFIG['avatar_image_width'].'" height="'.$CONFIG['avatar_image_height'].'" border="0" alt="" />');

					include_once('pop_pheader.php');
					$editprofile_tpl->parse_code(TRUE);
					include_once('pop_ptail.php'); exit;
				}
			}
		}

		$editprofile_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editprofile_uploadavatar']);

		if($error != '') $editprofile_tpl->blocks['errorrow']->parse_code();


		include_once('pop_pheader.php');
		$editprofile_tpl->parse_code(TRUE);
		include_once('pop_ptail.php');
	break;

	case 'selectavatar':
		$avatar_address = isset($_GET['avatar_address']) ? $_GET['avatar_address'] : '';

		if(isset($_GET['doit'])) {
			$db->query("UPDATE ".TBLPFX."users SET user_avatar_address='$avatar_address' WHERE user_id='$USER_ID'");

			$editprofile_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editprofile_avatarresult']);

			$avatar_selected_text = sprintf($lng['avatar_selected_text'],'<img src="'.$avatar_address.'" width="'.$CONFIG['avatar_image_width'].'" height="'.$CONFIG['avatar_image_height'].'" border="0" alt="" />');

			include_once('pop_pheader.php');
			$editprofile_tpl->parse_code(TRUE);
			include_once('pop_ptail.php'); exit;
		}

		$editprofile_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editprofile_selectavatar']);

		$db->query("SELECT avatar_address FROM ".TBLPFX."avatars");
		$avatars_data = $db->raw2array();
		$avatars_counter = count($avatars_data);

		if($avatars_counter > 0) {
			for($i = 0; $i < $avatars_counter; $i++) {
				$akt_avatar = &$avatars_data[$i];
				$akt_encoded_avatar_address = urlencode($akt_avatar['avatar_address']);

				$editprofile_tpl->blocks['avatarrow']->blocks['avatarcol']->parse_code(FALSE,TRUE);

				if(($i+1) % 5 == 0 && $i != $smilies_counter-1) {
					$editprofile_tpl->blocks['avatarrow']->parse_code(FALSE,TRUE);
					$editprofile_tpl->blocks['avatarrow']->blocks['avatarcol']->reset_tpl();
				}
			}
			$editprofile_tpl->blocks['avatarrow']->parse_code(FALSE,TRUE);
		}


		include_once('pop_pheader.php');
		$editprofile_tpl->parse_code(TRUE);
		include_once('pop_ptail.php');
	break;
}

?>