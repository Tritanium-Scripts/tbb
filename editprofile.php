<?php
/**
*
* Tritanium Bulletin Board 2 - editprofile.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

if($USER_LOGGED_IN != 1) die('Nicht eingeloggt!');

add_navbar_items(array($LNG['User_administration'],"index.php?faction=editprofile&amp;$MYSID"));

switch(@$_GET['mode']) {
	default:
		$p_user_email = isset($_POST['p_user_email']) ? $_POST['p_user_email'] : $USER_DATA['user_email'];
		$p_user_signature = isset($_POST['p_user_signature']) ? $_POST['p_user_signature'] : $USER_DATA['user_signature'];
		$p_user_old_pw = isset($_POST['p_user_old_pw']) ? $_POST['p_user_old_pw'] : '';
		$p_user_new_pw = isset($_POST['p_user_new_pw']) ? $_POST['p_user_new_pw'] : '';
		$p_user_new_pw_cfm = isset($_POST['p_user_new_pw_cfm']) ? $_POST['p_user_new_pw_cfm'] : '';

		$error = '';

		if(isset($_GET['doit'])) {
			if(verify_email($p_user_email) == FALSE) $error = $LNG['error_bad_email'];
			elseif(trim($p_user_new_pw) != '' && mycrypt($p_user_old_pw) != $USER_DATA['user_pw']) $error = $LNG['error_wrong_password'];
			elseif(trim($p_user_new_pw) != '' && $p_user_new_pw != $p_user_new_pw_cfm) $error = $LNG['error_pws_no_match'];
			else {
				$DB->query("UPDATE ".TBLPFX."users SET user_email='$p_user_email', user_signature='$p_user_signature' WHERE user_id='$USER_ID'");

				if(trim($p_user_new_pw) != '') {
					$DB->query("UPDATE ".TBLPFX."users SET user_pw='".mycrypt($p_user_new_pw)."' WHERE user_id='$USER_ID'");
					$_SESSION['tbb_user_pw'] = mycrypt($p_user_new_pw);
				}

				die();
			}
		}

		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editprofile_general']);

		include_once('pheader.php');
		show_navbar();
		$tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;

	case 'extendedprofile':
		$error = '';

		//
		// Erst werden die einzelnen Profilfelder geladen
		//
		$DB->query("SELECT * FROM ".TBLPFX."profile_fields");
		$profile_fields = $DB->raw2array();


		//
		// Jetzt werde die eventuell vorhandenen Profildaten geladen
		//
		$fields_data = array();
		$DB->query("SELECT field_id,field_value FROM ".TBLPFX."profile_fields_data WHERE user_id='$USER_ID'");
		while($cur_field_data = $DB->fetch_array())
			$fields_data[$cur_field_data['field_id']] = $cur_field_data['field_value'];


		//
		// Jetzt werden eventuelle $_POST-Daten uebernommen
		//
		while(list(,$cur_field) = each($profile_fields)) {
			switch($cur_field['field_type']) {
				case '0': $p_fields_data[$cur_field['field_id']] = isset($_POST['p_fields_data'][$cur_field['field_id']]) ? $_POST['p_fields_data'][$cur_field['field_id']] : (isset($fields_data[$cur_field['field_id']]) ? addslashes($fields_data[$cur_field['field_id']]) : ''); break;
				case '1': $p_fields_data[$cur_field['field_id']] = isset($_POST['p_fields_data'][$cur_field['field_id']]) ? $_POST['p_fields_data'][$cur_field['field_id']] : (isset($fields_data[$cur_field['field_id']]) ? addslashes($fields_data[$cur_field['field_id']]) : ''); break;
				case '2': $p_fields_data[$cur_field['field_id']] = isset($_POST['p_fields_data'][$cur_field['field_id']]) ? intval($_POST['p_fields_data'][$cur_field['field_id']]) : (isset($fields_data[$cur_field['field_id']]) ? $fields_data[$cur_field['field_id']] : ''); break;
				case '3': $p_fields_data[$cur_field['field_id']] = (isset($_POST['p_fields_data'][$cur_field['field_id']]) == TRUE && is_array($_POST['p_fields_data'][$cur_field['field_id']]) == TRUE) ? $_POST['p_fields_data'][$cur_field['field_id']] : (isset($fields_data[$cur_field['field_id']]) ? explode(',',$fields_data[$cur_field['field_id']]) : array()); break;
			}
		}
		reset($profile_fields);


		if(isset($_GET['doit'])) {
			$field_missing = FALSE;
			while(list(,$cur_field) = each($profile_fields)) {
				if($cur_field['field_is_required'] == 1 && ($cur_field['field_type'] != 3 && $p_fields_data[$cur_field['field_id']] === '' || $cur_field['field_type'] == 3 && count($p_fields_data[$cur_field['field_id']]) == 0)) {
					$field_missing = TRUE;
					break;
				}
			}
			reset($profile_fields);

			if($field_missing == TRUE) $error = $LNG['error_required_fields_missing'];
			else {
				$delete_ids = array();

				while(list(,$cur_field) = each($profile_fields)) {
					$cur_value = ($cur_field['field_type'] == 3) ? implode(',',$p_fields_data[$cur_field['field_id']]) : $p_fields_data[$cur_field['field_id']];

					if($cur_value === '' && isset($fields_data[$cur_field['field_id']]) == TRUE) $delete_ids[] = $cur_field['field_id'];
					elseif($cur_value === '' && isset($fields_data[$cur_field['field_id']]) == FALSE) {}
					elseif(isset($fields_data[$cur_field['field_id']]) == TRUE) $DB->query("UPDATE ".TBLPFX."profile_fields_data SET field_value='$cur_value' WHERE user_id='$USER_ID' AND field_id='".$cur_field['field_id']."'");
					else $DB->query("INSERT INTO ".TBLPFX."profile_fields_data (field_id,user_id,field_value) VALUES ('".$cur_field['field_id']."','$USER_ID','$cur_value')");
				}
				reset($profile_fields);

				$DB->query("DELETE FROM ".TBLPFX."profile_fields_data WHERE user_id='$USER_ID' AND field_id IN ('".implode(',',$delete_ids)."')");
			}
		}

		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editprofile_extendedprofile']);

		$fields_groups = array(
			array('group_name'=>$LNG['Required_information'],'group_type'=>1),
			array('group_name'=>$LNG['Other_information'],'group_type'=>0)
		);

		while(list(,$akt_group_data) = each($fields_groups)) {
			$tpl->blocks['fieldsgroup']->blocks['fields']->reset_tpl();

			while(list($cur_field_key,$cur_field_data) = each($profile_fields)) {
				if($akt_group_data['group_type'] != $cur_field_data['field_is_required']) continue;
				$tpl->blocks['fieldsgroup']->blocks['fields']->blocks['fieldtext']->reset_tpl();
				$tpl->blocks['fieldsgroup']->blocks['fields']->blocks['fieldtextarea']->reset_tpl();
				$tpl->blocks['fieldsgroup']->blocks['fields']->blocks['fieldsingleselection']->reset_tpl();
				$tpl->blocks['fieldsgroup']->blocks['fields']->blocks['fieldmultiselection']->reset_tpl();

				if($cur_field_data['field_type'] == 0) {
					$akt_field_value = mutate($p_fields_data[$cur_field_data['field_id']]);
					$tpl->blocks['fieldsgroup']->blocks['fields']->blocks['fieldtext']->parse_code();
				}
				elseif($cur_field_data['field_type'] == 1) {
					$akt_field_value = mutate($p_fields_data[$cur_field_data['field_id']]);
					$tpl->blocks['fieldsgroup']->blocks['fields']->blocks['fieldtextarea']->parse_code();
				}
				elseif($cur_field_data['field_type'] == 2) {
					$selected_id = $p_fields_data[$cur_field_data['field_id']];
					$field_options = unserialize($cur_field_data['field_data']);
					while(list($cur_option_key,$cur_option_value) = each($field_options))
						$tpl->blocks['fieldsgroup']->blocks['fields']->blocks['fieldsingleselection']->blocks['optionrow']->parse_code(FALSE,TRUE);
					$tpl->blocks['fieldsgroup']->blocks['fields']->blocks['fieldsingleselection']->parse_code();
				}
				elseif($cur_field_data['field_type'] == 3) {
					$selected_ids = $p_fields_data[$cur_field_data['field_id']];
					$field_options = unserialize($cur_field_data['field_data']);
					while(list($cur_option_key,$cur_option_value) = each($field_options))
						$tpl->blocks['fieldsgroup']->blocks['fields']->blocks['fieldmultiselection']->blocks['optionrow']->parse_code(FALSE,TRUE);
					$tpl->blocks['fieldsgroup']->blocks['fields']->blocks['fieldmultiselection']->parse_code();
				}

				$tpl->blocks['fieldsgroup']->blocks['fields']->parse_code(FALSE,TRUE);

				unset($profile_fields[$cur_field_key]);
			}
			reset($profile_fields);

			$tpl->blocks['fieldsgroup']->parse_code(FALSE,TRUE);
		}

		include_once('pheader.php');
		show_navbar();
		$tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;

	case 'settings':
		$p_user_tz = isset($_POST['p_user_tz']) ? $_POST['p_user_tz'] : $USER_DATA['user_tz'];
		$p_user_hide_email = isset($_POST['p_user_hide_email']) ? $_POST['p_user_hide_email'] : $USER_DATA['user_hide_email'];
		$p_user_receive_emails = isset($_POST['p_user_receive_emails']) ? $_POST['p_user_receive_emails'] : $USER_DATA['user_receive_emails'];

		if(isset($_GET['doit'])) {
		}

		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editprofile_settings']);

		while(list($akt_tz_id) = each($TIMEZONES)) {
			$akt_tz_name = $LNG['tz_'.$akt_tz_id];
			$tpl->blocks['tzrow']->parse_code(FALSE,TRUE);
		}

		add_navbar_items(array($LNG['Settings'],''));

		include_once('pheader.php');
		show_navbar();
		$tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;

	case 'topicsubscriptions':
		$topic_id = isset($_GET['topic_id']) ? intval($_GET['topic_id']) : 0;
		$topic_ids = isset($_POST['topic_ids']) ? $_POST['topic_ids'] : array();

		if(isset($_GET['doit'])) {
			if($topic_id != 0)
				$DB->query("DELETE FROM ".TBLPFX."topics_subscriptions WHERE user_id='$USER_ID' AND topic_id='$topic_id'");

			if(is_array($topic_ids) == TRUE && count($topic_ids) > 0)
				$DB->query("DELETE FROM ".TBLPFX."topics_subscriptions WHERE user_id='$USER_ID' AND topic_id IN('".implode("','",$topic_ids)."')");
		}

		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editprofile_topicsubscriptions']);

		$DB->query("SELECT t2.topic_title,t1.topic_id FROM ".TBLPFX."topics_subscriptions AS t1, ".TBLPFX."topics AS t2 WHERE t1.user_id='$USER_ID' AND t2.topic_id=t1.topic_id");
		$subscriptions_counter = $DB->affected_rows;
		while($akt_topic_subscription = $DB->fetch_array()) {
			$tpl->blocks['subscriptionrow']->parse_code(FALSE,TRUE);
		}

		add_navbar_items(array($LNG['Topic_subscriptions'],''));

		include_once('pheader.php');
		show_navbar();
		$tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;

	case 'avatar':
		$p_avatar_address = isset($_POST['p_avatar_address']) ? $_POST['p_avatar_address'] : addslashes($USER_DATA['user_avatar_address']);

		if(isset($_GET['doit'])) {
			$DB->query("UPDATE ".TBLPFX."users SET user_avatar_address='$p_avatar_address' WHERE user_id='$USER_ID'");
			$USER_DATA['user_avatar_address'] = mysslashes($p_avatar_address);
		}

		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editprofile_avatar']);

		$DB->query("SELECT avatar_address FROM ".TBLPFX."avatars");
		$avatars_data = $DB->raw2array();
		$avatars_counter = count($avatars_data);

		if($avatars_counter > 0) {
			for($i = 0; $i < $avatars_counter; $i++) {
				$akt_avatar = &$avatars_data[$i];
				$akt_encoded_avatar_address = urlencode($akt_avatar['avatar_address']);

				$tpl->blocks['selectavatar']->blocks['avatarrow']->blocks['avatarcol']->parse_code(FALSE,TRUE);

				if(($i+1) % 5 == 0 && $i != $avatars_counter-1) {
					$tpl->blocks['selectavatar']->blocks['avatarrow']->parse_code(FALSE,TRUE);
					$tpl->blocks['selectavatar']->blocks['avatarrow']->blocks['avatarcol']->reset_tpl();
				}
			}
			$tpl->blocks['selectavatar']->blocks['avatarrow']->parse_code(FALSE,TRUE);
			$tpl->blocks['selectavatar']->parse_code();
		}

		add_navbar_items(array($LNG['Avatar'],''));

		include_once('pheader.php');
		show_navbar();
		$tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;

	case 'memo':
		$p_user_memo = isset($_POST['p_user_memo']) ? $_POST['p_user_memo'] : addslashes($USER_DATA['user_memo']);

		if(isset($_GET['doit'])) {
			$DB->query("UPDATE ".TBLPFX."users SET user_memo='$p_user_memo' WHERE user_id='$USER_ID'");
		}

		$p_user_memo = myhtmlentities($p_user_memo);

		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editprofile_memo']);

		add_navbar_items(array($LNG['Memo'],"index.php?faction=editprofile&amp;mode=memo&amp;$MYSID"));

		include_once('pheader.php');
		show_navbar();
		$tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;

	case 'xxx':

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
			if(trim($p_email) == '' || verify_email($p_email) != TRUE) $error = $LNG['error_bad_email'];
			elseif(trim($p_pw1) != '' && $p_pw1 != $p_pw2) $pwerror = $LNG['error_pws_no_match'];
			elseif($p_icq != '' && verify_icq_uin($p_icq) == FALSE) $error = $LNG['error_bad_icq'];
			else {
				if(!isset($TIMEZONES[$p_user_tz]))
					$p_user_tz = $CONFIG['standard_tz'];

				if(trim($p_pw1) != '') {
					$p_pwc = mycrypt($p_pw1);
					$_SESSION['tbb_user_pw'] = $p_pwc;
				}
				else $p_pwc = $USER_DATA['user_pw'];

				$DB->query("UPDATE ".TBLPFX."users SET user_email='$p_email', user_realname='$p_name', user_location='$p_location', user_hp='$p_hp', user_interests='$p_interests', user_icq='$p_icq', user_yahoo='$p_yahoo', user_aim='$p_aim', user_msn='$p_msn', user_signature='$p_signature', user_pw='$p_pwc', user_tz='$p_user_tz', user_hide_email='$p_user_hide_email', user_receive_emails='$p_user_receive_emails' WHERE user_id='$USER_ID'");

				add_navbar_items(array($LNG['Profile_saved'],''));

				include_once('pheader.php');
				show_navbar();
				show_message($LNG['Profile_saved'],$LNG['message_profile_saved'].'<br />'.sprintf($LNG['click_here_back_profile'],"<a href=\"index.php?faction=editprofile&amp;$MYSID\">",'</a>').'<br />'.sprintf($LNG['click_here_back_forumindex'],"<a href=\"index.php?$MYSID\">",'</a>'));
				include_once('ptail.php'); exit;
			}
		}

		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editprofile']);

		if($error != '') $tpl->blocks['errorrow']->parse_code();
		if($pwerror != '') $tpl->blocks['pwerrorrow']->parse_code();
		if($CONFIG['enable_avatars'] == 1) $tpl->blocks['avatarrow']->parse_code();

		while(list($akt_tz_id) = each($TIMEZONES)) {
			$akt_tz_name = $LNG['tz_'.$akt_tz_id];
			$akt_checked = ($akt_tz_id == $p_user_tz) ? ' selected="selected"' : '';
			$tpl->blocks['tzrow']->parse_code(FALSE,TRUE);
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
		$tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;

	case 'uploadavatar':
		if($CONFIG['enable_avatar_upload'] != 1) {
			include_once('pop_pheader.php');
			show_message($LNG['Avatar_upload_disabled'],$LNG['message_avatar_upload_disabled']);
			include_once('pop_ptail.php'); exit;
		}

		$error = '';

		if(isset($_GET['doit'])) {
			if(isset($_FILES['p_avatar_file']) == FALSE || $_FILES['p_avatar_file']['name'] == '') $error = $LNG['error_invalid_file'];
			elseif($_FILES['p_avatar_file']['size'] > $CONFIG['max_avatar_file_size']*1024) $error = $LNG['error_file_too_big'];
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

				if(in_array($file_extension,$good_file_extensions) != TRUE) $error = $LNG['error_invalid_file_extension'];
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
					$DB->query("UPDATE ".TBLPFX."users SET user_avatar_address='$remote_avatar_file_name' WHERE user_id='$USER_ID'"); // Neuen Avatar in der Datenbank aktualisieren

					$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editprofile_avatarresult']);

					$avatar_address = $remote_avatar_file_name;
					$avatar_selected_text = sprintf($LNG['avatar_selected_text'],'<img src="'.$remote_avatar_file_name.'" width="'.$CONFIG['avatar_image_width'].'" height="'.$CONFIG['avatar_image_height'].'" border="0" alt="" />');

					include_once('pop_pheader.php');
					$tpl->parse_code(TRUE);
					include_once('pop_ptail.php'); exit;
				}
			}
		}

		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editprofile_uploadavatar']);

		if($error != '') $tpl->blocks['errorrow']->parse_code();


		include_once('pop_pheader.php');
		$tpl->parse_code(TRUE);
		include_once('pop_ptail.php');
	break;

	case 'selectavatar':
		$avatar_address = isset($_GET['avatar_address']) ? $_GET['avatar_address'] : '';

		if(isset($_GET['doit'])) {
			$DB->query("UPDATE ".TBLPFX."users SET user_avatar_address='$avatar_address' WHERE user_id='$USER_ID'");

			$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editprofile_avatarresult']);

			$avatar_selected_text = sprintf($LNG['avatar_selected_text'],'<img src="'.$avatar_address.'" width="'.$CONFIG['avatar_image_width'].'" height="'.$CONFIG['avatar_image_height'].'" border="0" alt="" />');

			include_once('pop_pheader.php');
			$tpl->parse_code(TRUE);
			include_once('pop_ptail.php'); exit;
		}

		$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editprofile_selectavatar']);

		$DB->query("SELECT avatar_address FROM ".TBLPFX."avatars");
		$avatars_data = $DB->raw2array();
		$avatars_counter = count($avatars_data);

		if($avatars_counter > 0) {
			for($i = 0; $i < $avatars_counter; $i++) {
				$akt_avatar = &$avatars_data[$i];
				$akt_encoded_avatar_address = urlencode($akt_avatar['avatar_address']);

				$tpl->blocks['avatarrow']->blocks['avatarcol']->parse_code(FALSE,TRUE);

				if(($i+1) % 5 == 0 && $i != $avatars_counter-1) {
					$tpl->blocks['avatarrow']->parse_code(FALSE,TRUE);
					$tpl->blocks['avatarrow']->blocks['avatarcol']->reset_tpl();
				}
			}
			$tpl->blocks['avatarrow']->parse_code(FALSE,TRUE);
		}


		include_once('pop_pheader.php');
		$tpl->parse_code(TRUE);
		include_once('pop_ptail.php');
	break;
}

?>