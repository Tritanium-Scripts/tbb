<?php
/**
*
* Tritanium Bulletin Board 2 - register.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');
require_once($LANGUAGE_PATH.'/lng_boardrules.php'); // Der Boardregeln-Text


//
// Zuerst einige Ueberpruefungen...
//
if($USER_LOGGED_IN == 1) { // Ist der User schon registriert/eingeloggt?
	header("Location: index.php?$MYSID"); exit;
}
elseif($CONFIG['enable_registration'] != 1) { // Ist die Registrierung ueberhaupt aktiviert?
	add_navbar_items(array($LNG['Registration_disabled'],''));

	include_once('pheader.php');
	show_navbar();
	show_message($LNG['Registration_disabled'],$LNG['message_registration_disabled']);
	include_once('ptail.php'); exit;
}
elseif($CONFIG['maximum_registrations'] != -1 && $CONFIG['maximum_registrations'] <= get_user_counter()) { // Gibt es eine Grenze an maximalen Registrierungen/ist diese ueberschritten?
	add_navbar_items(array($LNG['Too_many_registrations'],''));

	include_once('pheader.php');
	show_navbar();
	show_message($LNG['Too_many_registrations'],$LNG['message_too_many_registrations']);
	include_once('ptail.php'); exit;
}

$error = '';

//
// Die Profilfelder laden, die bei der Registrierung angezeigt werden sollen
//
$DB->query("SELECT * FROM ".TBLPFX."profile_fields WHERE field_show_registration='1'");
$profile_fields = $DB->raw2array();
$profile_fields_counter = $DB->affected_rows;


//
// Jetzt werden eventuelle $_POST-Daten uebernommen
//
while(list(,$cur_field) = each($profile_fields)) {
	switch($cur_field['field_type']) {
		case '0': $p_fields_data[$cur_field['field_id']] = isset($_POST['p_fields_data'][$cur_field['field_id']]) ? $_POST['p_fields_data'][$cur_field['field_id']] : ''; break;
		case '1': $p_fields_data[$cur_field['field_id']] = isset($_POST['p_fields_data'][$cur_field['field_id']]) ? $_POST['p_fields_data'][$cur_field['field_id']] : ''; break;
		case '2': $p_fields_data[$cur_field['field_id']] = isset($_POST['p_fields_data'][$cur_field['field_id']]) ? intval($_POST['p_fields_data'][$cur_field['field_id']]) : ''; break;
		case '3': $p_fields_data[$cur_field['field_id']] = (isset($_POST['p_fields_data'][$cur_field['field_id']]) == TRUE && is_array($_POST['p_fields_data'][$cur_field['field_id']]) == TRUE) ? $_POST['p_fields_data'][$cur_field['field_id']] : array(); break;
	}
}
reset($profile_fields);

$p_user_nick = isset($_POST['p_user_nick']) ? $_POST['p_user_nick'] : '';
$p_user_email = isset($_POST['p_user_email']) ? $_POST['p_user_email'] : '';
$p_user_email_confirmation = isset($_POST['p_user_email_confirmation']) ? $_POST['p_user_email_confirmation'] : '';
$p_user_pw = isset($_POST['p_user_pw']) ? $_POST['p_user_pw'] : '';
$p_user_pw_confirmation = isset($_POST['p_user_pw_confirmation']) ? $_POST['p_user_pw_confirmation'] : '';


//
// Falls das Formular abgeschickt wurde
//
if(isset($_GET['doit'])) {
	$field_missing = FALSE;
	while(list(,$cur_field) = each($profile_fields)) {
		if($cur_field['field_is_required'] == 1 && ($cur_field['field_type'] != 3 && $p_fields_data[$cur_field['field_id']] === '' || $cur_field['field_type'] == 3 && count($p_fields_data[$cur_field['field_id']]) == 0)) {
			$field_missing = TRUE;
			break;
		}
	}
	reset($profile_fields);

	$field_bad = FALSE;
	while(list(,$cur_field) = each($profile_fields)) {
		if(($cur_field['field_type'] == 0 || $cur_field['field_type'] == 1) && $cur_field['field_regex_verification'] != '' && preg_match($cur_field['field_regex_verification'],$p_fields_data[$cur_field['field_id']]) == FALSE) {
			$field_bad = TRUE;
			break;
		}
	}
	reset($profile_fields);

	if($field_missing == TRUE) $error = $LNG['error_required_fields_missing']; // Fehlt ein benoetigtes Feld?
	elseif($field_bad == TRUE) $error = $LNG['error_bad_information']; // Hat ein Feld ein falsches Format?
	elseif($p_user_nick == '' || verify_nick($p_user_nick) == FALSE) $error = $LNG['error_bad_nick']; // Hat der Nick ein falsches Format?
	elseif(unify_nick($p_user_nick) == FALSE) $error = $LNG['error_nick_already_in_use']; // Wird der Nick schon verwendet?
	elseif($p_user_email == '' || verify_email($p_user_email) == FALSE) $error = $LNG['error_bad_email']; // Hat die Emailadresse das richtige Format?
	elseif($p_user_email != $p_user_email_confirmation) $error = $LNG['error_emails_no_match']; // Stimmen die Emailadressen ueberein?
	elseif(trim($p_user_pw) == '' && ($CONFIG['verify_email_address'] != 1 || $CONFIG['enable_email_functions'] != 1)) $error = $LNG['error_no_pw']; // Wurde ein Passwort angegeben?
	elseif($p_user_pw != $p_user_pw_confirmation && ($CONFIG['verify_email_address'] != 1 || $CONFIG['enable_email_functions'] != 1)) $error = $LNG['error_pws_no_match']; // Stimmen die Passworter ueberein?
	else {
		$user_counter = get_user_counter();
		$p_is_admin = ($user_counter == 0) ? 1 : 0;

		$p_status = 1;

		$p_user_hash = '';

		if($p_is_admin != 1) {
			if($CONFIG['verify_email_address'] == 1  && $CONFIG['enable_email_functions'] == 1)
				$p_user_pw = get_rand_string(8);
			elseif($CONFIG['verify_email_address'] == 2 && $CONFIG['enable_email_functions'] == 1) {
				$p_status = 0;
				$p_user_hash = get_rand_string(32);
			}
		}

		$p_user_pwc = mycrypt($p_user_pw);

		$DB->query("INSERT INTO ".TBLPFX."users (user_status,user_is_admin,user_is_supermod,user_hash,user_nick,user_email,user_pw,user_posts,user_signature,user_group_id,user_special_status,user_regtime,user_tz)
			VALUES ('$p_status','$p_is_admin','0','$p_user_hash','$p_user_nick','$p_user_email','$p_user_pwc','0','','0','0','".time()."','".$CONFIG['standard_tz']."')");

		$new_user_id = $DB->insert_id;

		while(list(,$cur_field) = each($profile_fields)) {
			$cur_value = ($cur_field['field_type'] == 3) ? implode(',',$p_fields_data[$cur_field['field_id']]) : $p_fields_data[$cur_field['field_id']];
			$DB->query("INSERT INTO ".TBLPFX."profile_fields_data (field_id,user_id,field_value) VALUES ('".$cur_field['field_id']."','$new_user_id','$cur_value')");
		}
		reset($profile_fields);

		$_SESSION['last_place_url'] = "index.php?$MYSID";

		if($p_is_admin != 1 && $CONFIG['enable_email_functions'] == 1) {
			$email_tpl = new template($LANGUAGE_PATH.'/emails/email_welcome.tpl');
			mymail($CONFIG['board_name'].' <'.$CONFIG['board_email_address'].'>',$p_user_email,sprintf($LNG['email_subject_welcome'],$CONFIG['board_name']),$email_tpl->parse_code());

			if($CONFIG['verify_email_address'] == 2) {
				$activation_link = $CONFIG['board_address'].'/index.php?faction=activateaccount&account_id='.$p_user_name.'&activation_code='.$p_user_hash.'&doit=1';
				$email_tpl->load($LANGUAGE_PATH.'/emails/email_account_activation.tpl');
				mymail('"'.$CONFIG['board_name'].'" <'.$CONFIG['board_email_address'].'>',$p_user_email,sprintf($LNG['email_subject_account_activation'],$CONFIG['board_name']),$email_tpl->parse_code());
			}
		}

		$DB->query("UPDATE ".TBLPFX."config SET config_value='$new_user_id' WHERE config_name='newest_user_id'");
		$DB->query("UPDATE ".TBLPFX."config SET config_value='$p_user_nick' WHERE config_name='newest_user_nick'");

		add_navbar_items(array($LNG['Registration_successful'],''));

		include_once('pheader.php');
		show_navbar();
		show_message($LNG['Registration_successful'],$LNG['message_registration_successful'].'<br />'.sprintf($LNG['click_here_login'],"<a href=\"index.php?faction=login&amp;$MYSID\">",'</a>'));
		include_once('ptail.php'); exit;
	}
}


//
// Ab hier steht fest, dass das Formular angezeigt werden soll
//
add_navbar_items(array($LNG['Register'],"index.php?faction=register&amp;$MYSID"));

$tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['register']);


//
// Die Spezial-Profilfelder
//
if($profile_fields_counter > 0) {

	$fields_groups = array(
		array('group_name'=>$LNG['Required_information'],'group_type'=>1),
		array('group_name'=>$LNG['Other_information'],'group_type'=>0)
	);

	while(list(,$akt_group_data) = each($fields_groups)) {
		$tpl->blocks['fieldsgroup']->blocks['fields']->reset_tpl();
		$X = FALSE;

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
			$X = TRUE;
		}
		reset($profile_fields);

		if($X == TRUE) $tpl->blocks['fieldsgroup']->parse_code(FALSE,TRUE);
	}
}


include_once('pheader.php');
show_navbar();
$tpl->parse_code(TRUE);
include_once('ptail.php');

/*switch($mode) {
	default:
		$error = '';

		$p_user_name = isset($_POST['p_user_name']) ? $_POST['p_user_name'] : '';
		$p_user_email = isset($_POST['p_user_email']) ? $_POST['p_user_email'] : '';
		$p_user_pw1 = isset($_POST['p_user_pw1']) ? $_POST['p_user_pw1'] : '';
		$p_user_pw2 = isset($_POST['p_user_pw2']) ? $_POST['p_user_pw2'] : '';

		$p_name = isset($_POST['p_name']) ? $_POST['p_name'] : '';
		$p_location = isset($_POST['p_location']) ? $_POST['p_location'] : '';
		$p_hp = isset($_POST['p_hp']) ? $_POST['p_hp'] : '';
		$p_interests = isset($_POST['p_interests']) ? $_POST['p_interests'] : '';
		$p_icq = isset($_POST['p_icq']) ? $_POST['p_icq'] : '';
		$p_yahoo = isset($_POST['p_yahoo']) ? $_POST['p_yahoo'] : '';
		$p_aim = isset($_POST['p_aim']) ? $_POST['p_aim'] : '';
		$p_msn = isset($_POST['p_msn']) ? $_POST['p_msn'] : '';
		$p_signature = isset($_POST['p_signature']) ? $_POST['p_signature'] :'';

		if($p_hp != '') $p_hp = addhttp($p_hp);

		if(isset($_GET['doit'])) {
			if($p_user_name == '' || verify_nick($p_user_name) == FALSE) $error = $LNG['error_bad_nick'];
			elseif(unify_nick($p_user_name) == FALSE) $error = $LNG['error_nick_already_in_use'];
			elseif($p_user_email == '' || verify_email($p_user_email) == FALSE) $error = $LNG['error_bad_email'];
			elseif(trim($p_user_pw1) == '' && ($CONFIG['verify_email_address'] != 1 || $CONFIG['enable_email_functions'] != 1)) $error = $LNG['error_no_pw'];
			elseif($p_user_pw1 != $p_user_pw2 && ($CONFIG['verify_email_address'] != 1 || $CONFIG['enable_email_functions'] != 1)) $error = $LNG['error_pws_no_match'];
			elseif($p_icq != '' && verify_icq_uin($p_icq) == FALSE) $error = $LNG['error_bad_icq'];
			else {
				$user_counter = get_user_counter();
				$p_is_admin = ($user_counter == 0) ? 0 : 0;

				$p_status = 1;

				$p_user_hash = '';

				if($p_is_admin != 1) {
					if($CONFIG['verify_email_address'] == 1  && $CONFIG['enable_email_functions'] == 1)
						$p_user_pw1 = get_rand_string(8);
					elseif($CONFIG['verify_email_address'] == 2 && $CONFIG['enable_email_functions'] == 1) {
						$p_status = 0;
						$p_user_hash = get_rand_string(32);
					}
				}

				$p_user_pwc = mycrypt($p_user_pw1);

				$DB->query("INSERT INTO ".TBLPFX."users (user_status,user_is_admin,user_is_supermod,user_hash,user_nick,user_email,user_pw,user_posts,user_hp,user_icq,user_aim,user_yahoo,user_msn,user_signature,user_group_id,user_special_status,user_interests,user_realname,user_location,user_regtime,user_tz)
					VALUES ('$p_status','$p_is_admin','0','$p_user_hash','$p_user_name','$p_user_email','$p_user_pwc','0','$p_hp','$p_icq','$p_aim','$p_yahoo','$p_msn','$p_signature','0','0','$p_interests','$p_name','$p_location','".time()."','".$CONFIG['standard_tz']."')");

				$_SESSION['last_place_url'] = "index.php?$MYSID";


				if($p_is_admin != 1 && $CONFIG['enable_email_functions'] == 1) {
					$email_tpl = new template($LANGUAGE_PATH.'/emails/email_welcome.tpl');
					mymail($CONFIG['board_name'].' <'.$CONFIG['board_email_address'].'>',$p_user_email,$LNG['email_subject_welcome'],$email_tpl->parse_code());

					if($CONFIG['verify_email_address'] == 2) {
						$activation_link = $CONFIG['board_address'].'/index.php?faction=activateaccount&account_id='.$p_user_name.'&activation_code='.$p_user_hash.'&doit=1';
						$email_tpl->load($LANGUAGE_PATH.'/emails/email_account_activation.tpl');
						mymail('"'.$CONFIG['board_name'].'" <'.$CONFIG['board_email_address'].'>',$p_user_email,$LNG['email_subject_account_activation'],$email_tpl->parse_code());
					}
				}

				add_navbar_items(array($LNG['Registration_successful'],''));

				include_once('pheader.php');
				show_navbar();
				show_message($LNG['Registration_successful'],$LNG['message_registration_successful'].'<br />'.sprintf($LNG['click_here_login'],"<a href=\"index.php?faction=login&amp;$MYSID\">",'</a>'));
				include_once('ptail.php'); exit;
			}
		}

		multimutate('p_user_name','p_user_email','p_name','p_location','p_hp','p_interests','p_icq','p_yahoo','p_aim','p_msn','p_signature');

		$register_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['register_form']);

		if($error != '') $register_tpl->blocks['errorrow']->parse_code();
		if($CONFIG['verify_email_address'] != 1 || $CONFIG['enable_email_functions'] != 1) $register_tpl->blocks['userpw']->parse_code();

		include_once('pheader.php');
		show_navbar();
		$register_tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;

	case 'boardrules':
		$p_register_hash = isset($_POST['p_register_hash']) ? $_POST['p_register_hash'] : '';

		if(isset($_GET['doit'])) {
			$p_not_accept = isset($_POST['p_not_accept']) ? 1 : 0;
			if($p_not_accept == 1) {
				include_once('pheader.php');
				show_navbar();
				show_message($LNG['Board_rules_not_accepted'],$LNG['message_board_rules_not_accepted']);
				include_once('ptail.php'); exit;
			}
			elseif($p_register_hash != $_SESSION['s_register_hash']) die('Ungueltiger Registrierungshash!');
			else {
				unset($_SESSION['s_register_hash']);
				$_SESSION['s_accept_boardrules'] = TRUE;
				header("Location: index.php?faction=register&mode=register&$MYSID"); exit;
			}
		}

		$register_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['register_boardrules']);

		include_once('pheader.php');
		show_navbar();
		$register_tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;
}*/

?>