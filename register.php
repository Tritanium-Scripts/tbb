<?php
/**
*
* Tritanium Bulletin Board 2 - register.php
* Registriert einen neuen User
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
*
**/

require_once('auth.php');

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

		$new_user_data = array(
			'user_status'=>$p_status,
			'user_is_admin'=>$p_is_admin,
			'user_nick'=>$p_nick,
			'user_email'=>$p_email,
			'user_pw'=>$p_pwc,
			'user_posts'=>0,
			'user_hp'=>$p_hp,
			'user_icq'=>$p_icq,
			'user_aim'=>$p_aim,
			'user_yahoo'=>$p_yahoo,
			'user_msn'=>$p_msn,
			'user_signature'=>$p_signature,
			'user_group_id'=>'',
			'user_special_status'=>'',
			'user_interests'=>$p_interests,
			'user_realname'=>$p_name,
			'user_location'=>$p_location
		);

		add_user_data($new_user_data);

		$_SESSION['last_place_url'] = "index.php?$MYSID";

		include_once('pheader.php');

		show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=register&amp;$MYSID\">".$lng['Register']."</a>\r".$lng['Registration_successful']);

		show_message('Registration_successful','message_registration_successful','<br />'.sprintf($lng['click_here_login'],"<a href=\"index.php?faction=login&amp;$MYSID\">",'</a>'));

		include_once('ptail.php'); exit;
	}
}

$title_add .= ' &#187; '.$lng['Register'];

$register_tpl = new template;
$register_tpl->load($template_path.'/'.$tpl_config['tpl_register']);

if($error != '') {
	$register_tpl->blocks['errorrow']->values = array(
		'LNG_ERROR'=>$lng['Error'],
		'ERROR'=>$error
	);
	$register_tpl->blocks['errorrow']->parse_code();
}
else $register_tpl->unset_block('errorrow');

$register_tpl->blocks['userpw']->values = array(
	'LNG_PASSWORD'=>$lng['Password'],
	'LNG_CONFIRM_PASSWORD'=>$lng['Confirm_Password']
);
$register_tpl->blocks['userpw']->parse_code();

$register_tpl->values = array(
	'MYSID'=>$MYSID,
	'LNG_REGISTER'=>$lng['Register'],
	'LNG_USER_NAME'=>$lng['User_name'],
	'LNG_NICK_CONVENTIONS'=>$lng['nick_conventions'],
	'LNG_EMAIL'=>$lng['Emailaddress'],
	'LNG_LOCATION'=>$lng['Location'],
	'LNG_INTERESTS'=>$lng['Interests'],
	'LNG_SIGNATURE'=>$lng['Signature'],
	'LNG_ICQ'=>$lng['ICQ'],
	'LNG_REAL_NAME'=>$lng['Real_name'],
	'LNG_HOMEPAGE'=>$lng['Homepage'],
	'LNG_REQUIRED_INFORMATION'=>$lng['Required_information'],
	'LNG_OTHER_INFORMATION'=>$lng['Other_information'],
	'LNG_AIM'=>$lng['AIM'],
	'LNG_MSN'=>$lng['MSN'],
	'LNG_YAHOO'=>$lng['Yahoo'],
	'LNG_RESET'=>$lng['Reset'],
	'P_NICK'=>$p_nick,
	'P_EMAIL'=>$p_email,
	'P_NAME'=>$p_name,
	'P_LOCATION'=>$p_location,
	'P_HP'=>$p_hp,
	'P_INTERESTS'=>$p_interests,
	'P_ICQ'=>$p_icq,
	'P_YAHOO'=>$p_yahoo,
	'P_AIM'=>$p_aim,
	'P_MSN'=>$p_msn,
	'P_SIGNATURE'=>$p_signature
);

include_once('pheader.php');

show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Register']);

$register_tpl->parse_code(TRUE);

include_once('ptail.php');

?>