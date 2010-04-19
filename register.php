<?php
/**
*
* Tritanium Bulletin Board 2 - register.php
* version #2004-01-01-18-38-43
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

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

$error = '';

isset($_POST['p_nick']) ? $p_nick = $_POST['p_nick'] : $p_nick = '';
isset($_POST['p_email']) ? $p_email = $_POST['p_email'] : $p_email = '';
isset($_POST['p_pw1']) ? $p_pw1 = $_POST['p_pw1'] : $p_pw1 = '';
isset($_POST['p_pw2']) ? $p_pw2 = $_POST['p_pw2'] : $p_pw2 = '';

isset($_POST['p_name']) ? $p_name = $_POST['p_name'] : $p_name = '';
isset($_POST['p_location']) ? $p_location = $_POST['p_location'] : $p_location = '';
isset($_POST['p_hp']) ? $p_hp = $_POST['p_hp'] : $p_hp = '';
isset($_POST['p_interests']) ? $p_interests = $_POST['p_interests'] : $p_interests = '';
isset($_POST['p_icq']) ? $p_icq = $_POST['p_icq'] : $p_icq = '';
isset($_POST['p_yahoo']) ? $p_yahoo = $_POST['p_yahoo'] : $p_yahoo = '';
isset($_POST['p_aim']) ? $p_aim = $_POST['p_aim'] : $p_aim = '';
isset($_POST['p_msn']) ? $p_msn = $_POST['p_msn'] : $p_msn = '';
isset($_POST['p_signature']) ? $p_signature = $_POST['p_signature'] : $p_signature = '';

if(isset($_GET['doit'])) {
	if($p_nick == '' || verify_nick($p_nick) == FALSE) $error = $lng['error_bad_nick'];
	elseif(unify_nick($p_nick) == FALSE) $error = $lng['error_nick_already_in_use'];
	elseif($p_email == '' || verify_email($p_email) == FALSE) $error = $lng['error_bad_email'];
	elseif(trim($p_pw1) == '') $error = $lng['error_no_pw'];
	elseif($p_pw1 != $p_pw2) $error = $lng['error_pws_no_match'];
	elseif($p_icq != '' && verify_icq_uin($p_icq) == FALSE) $error = $lng['error_bad_icq'];
	else {
		$user_counter = get_user_counter();
		$p_is_admin = ($user_counter == 0) ? 1 : 0;

		$p_status = 1;

		$p_pwc = mycrypt($p_pw1);

		$db->query("INSERT INTO ".TBLPFX."users (user_status,user_is_admin,user_nick,user_email,user_pw,user_posts,user_hp,user_icq,user_aim,user_yahoo,user_msn,user_signature,user_group_id,user_special_status,user_interests,user_realname,user_location,user_regtime)
			VALUES ('$p_status','$p_is_admin','$p_nick','$p_email','$p_pwc','0','$p_hp','$p_icq','$p_aim','$p_yahoo','$p_msn','$p_signature','0','0','$p_interests','$p_name','$p_location',NOW())");

		$_SESSION['last_place_url'] = "index.php?$MYSID";

		include_once('pheader.php');

		show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=register&amp;$MYSID\">".$lng['Register']."</a>\r".$lng['Registration_successful']);

		show_message('Registration_successful','message_registration_successful','<br />'.sprintf($lng['click_here_login'],"<a href=\"index.php?faction=login&amp;$MYSID\">",'</a>'));

		include_once('ptail.php'); exit;
	}
}

multimutate('p_nick','p_email','p_name','p_location','p_hp','p_interests','p_icq','p_yahoo','p_aim','p_msn','p_signature');

$title_add[] = $lng['Register'];

$register_tpl = new template;
$register_tpl->load($template_path.'/'.$tpl_config['tpl_register']);

if($error != '') $register_tpl->blocks['errorrow']->parse_code();
else $register_tpl->unset_block('errorrow');

$register_tpl->blocks['userpw']->parse_code();

include_once('pheader.php');

show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Register']);

$register_tpl->parse_code(TRUE);

include_once('ptail.php');

?>