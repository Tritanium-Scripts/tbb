<?php
/**
*
* Tritanium Bulletin Board 2 - register.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');
require_once($language_path.'/lng_boardrules.php');

$user_counter = get_user_counter();

if($CONFIG['enable_registration'] != 1) {
	include_once('pheader.php');
	show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Registration_disabled']);
	show_message('Registration_disabled','message_registration_disabled');
	include_once('ptail.php'); exit;
}
elseif($CONFIG['maximum_registrations'] != -1 && $CONFIG['maximum_registrations'] <= $user_counter) {
	include_once('pheader.php');
	show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Too_many_registrations']);
	show_message('Too_many_registrations','message_too_many_registrations');
	include_once('ptail.php'); exit;
}


$mode = isset($_GET['mode']) ? $_GET['mode'] : 'register';


if($CONFIG['require_accept_boardrules'] == 1) {
	if(!isset($_SESSION['s_accept_boardrules'])) {
		$mode = 'boardrules';
		if(!isset($_SESSION['s_register_hash']))
			$_SESSION['s_register_hash'] = get_rand_string(32);
	}
}


switch($mode) {
	default:
		$error = '';

		$p_nick = isset($_POST['p_nick']) ? $_POST['p_nick'] : '';
		$p_email = isset($_POST['p_email']) ? $_POST['p_email'] : '';
		$p_pw1 = isset($_POST['p_pw1']) ? $_POST['p_pw1'] : '';
		$p_pw2 = isset($_POST['p_pw2']) ? $_POST['p_pw2'] : '';

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
			if($p_nick == '' || verify_nick($p_nick) == FALSE) $error = $lng['error_bad_nick'];
			elseif(unify_nick($p_nick) == FALSE) $error = $lng['error_nick_already_in_use'];
			elseif($p_email == '' || verify_email($p_email) == FALSE) $error = $lng['error_bad_email'];
			elseif(trim($p_pw1) == '' && ($CONFIG['verify_email_address'] != 1 || $CONFIG['enable_email_functions'] != 1)) $error = $lng['error_no_pw'];
			elseif($p_pw1 != $p_pw2 && ($CONFIG['verify_email_address'] != 1 || $CONFIG['enable_email_functions'] != 1)) $error = $lng['error_pws_no_match'];
			elseif($p_icq != '' && verify_icq_uin($p_icq) == FALSE) $error = $lng['error_bad_icq'];
			else {
				$user_counter = get_user_counter();
				$p_is_admin = ($user_counter == 0) ? 1 : 0;

				$p_status = 1;

				$p_user_hash = '';

				if($p_is_admin != 1) {
					if($CONFIG['verify_email_address'] == 1  && $CONFIG['enable_email_functions'] == 1)
						$p_pw1 = get_rand_string(8);
					elseif($CONFIG['verify_email_address'] == 2 && $CONFIG['enable_email_functions'] == 1) {
						$p_status = 0;
						$p_user_hash = get_rand_string(32);
					}
				}

				$p_pwc = mycrypt($p_pw1);

				$db->query("INSERT INTO ".TBLPFX."users (user_status,user_is_admin,user_is_supermod,user_hash,user_nick,user_email,user_pw,user_posts,user_hp,user_icq,user_aim,user_yahoo,user_msn,user_signature,user_group_id,user_special_status,user_interests,user_realname,user_location,user_regtime)
					VALUES ('$p_status','$p_is_admin','0','$p_user_hash','$p_nick','$p_email','$p_pwc','0','$p_hp','$p_icq','$p_aim','$p_yahoo','$p_msn','$p_signature','0','0','$p_interests','$p_name','$p_location',NOW())");

				$_SESSION['last_place_url'] = "index.php?$MYSID";


				if($p_is_admin != 1 && $CONFIG['enable_email_functions'] == 1) {
					$email_tpl = new template;
					$email_tpl->load($language_path.'/emails/email_welcome.tpl');
					mymail($CONFIG['board_name'].' <'.$CONFIG['board_email_address'].'>',$p_email,$lng['email_subject_welcome'],$email_tpl->parse_code());

					if($CONFIG['verify_email_address'] == 2) {
						$activation_link = $CONFIG['board_address'].'/index.php?faction=activateaccount&account_id='.$p_nick.'&activation_code='.$p_user_hash.'&doit=1';
						$email_tpl->load($language_path.'/emails/email_account_activation.tpl');
						mymail('"'.$CONFIG['board_name'].'" <'.$CONFIG['board_email_address'].'>',$p_email,$lng['email_subject_account_activation'],$email_tpl->parse_code());
					}
				}

				include_once('pheader.php');
				show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=register&amp;$MYSID\">".$lng['Register']."</a>\r".$lng['Registration_successful']);
				show_message('Registration_successful','message_registration_successful','<br />'.sprintf($lng['click_here_login'],"<a href=\"index.php?faction=login&amp;$MYSID\">",'</a>'));
				include_once('ptail.php'); exit;
			}
		}

		multimutate('p_nick','p_email','p_name','p_location','p_hp','p_interests','p_icq','p_yahoo','p_aim','p_msn','p_signature');

		$title_add[] = $lng['Register'];

		$register_tpl = new template;
		$register_tpl->load($template_path.'/'.$tpl_config['tpl_register_form']);

		if($error != '') $register_tpl->blocks['errorrow']->parse_code();
		else $register_tpl->unset_block('errorrow');

		if($CONFIG['verify_email_address'] != 1 || $CONFIG['enable_email_functions'] != 1) $register_tpl->blocks['userpw']->parse_code();
		else $register_tpl->unset_block('userpw');

		include_once('pheader.php');
		show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Register']);
		$register_tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;

	case 'boardrules':
		$p_register_hash = isset($_POST['p_register_hash']) ? $_POST['p_register_hash'] : '';

		if(isset($_GET['doit'])) {
			$p_not_accept = isset($_POST['p_not_accept']) ? 1 : 0;
			if($p_not_accept == 1) {
				include_once('pheader.php');
				show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Register']);
				show_message('Board_rules_not_accepted','message_board_rules_not_accepted');
				include_once('ptail.php'); exit;
			}
			elseif($p_register_hash != $_SESSION['s_register_hash']) die('Ungueltiger Registrierungshash!');
			else {
				unset($_SESSION['s_register_hash']);
				$_SESSION['s_accept_boardrules'] = TRUE;
				header("Location: index.php?faction=register&mode=register&$MYSID"); exit;
			}
		}

		$register_tpl = new template;
		$register_tpl->load($template_path.'/'.$tpl_config['tpl_register_boardrules']);

		include_once('pheader.php');
		show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Register']);
		$register_tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;
}

?>