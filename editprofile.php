<?php
/**
*
* Tritanium Bulletin Board 2 - editprofile.php
* Hiermit kann ein User sein Profil bearbeiten
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
*
**/

require_once('auth.php');

if($user_logged_in != 1) die('Nicht eingeloggt!');

$title_add .= ' &#187; '.$lng['Edit_profile'];

$p_email = isset($_POST['p_email']) ? $_POST['p_email'] : $user_data['user_email'];
$p_name = isset($_POST['p_name']) ? $_POST['p_name'] : $user_data['user_realname'];
$p_location = isset($_POST['p_location']) ? $_POST['p_location'] : $user_data['user_location'];
$p_hp = isset($_POST['p_hp']) ? $_POST['p_hp'] : $user_data['user_hp'];
$p_interests = isset($_POST['p_interests']) ? $_POST['p_interests'] : $user_data['user_interests'];
$p_icq = isset($_POST['p_icq']) ? $_POST['p_icq'] : $user_data['user_icq'];
$p_yahoo = isset($_POST['p_yahoo']) ? $_POST['p_yahoo'] : $user_data['user_yahoo'];
$p_aim = isset($_POST['p_aim']) ? $_POST['p_aim'] : $user_data['user_aim'];
$p_msn = isset($_POST['p_msn']) ? $_POST['p_msn'] : $user_data['user_msn'];
$p_signature = isset($_POST['p_signature']) ? $_POST['p_signature'] : $user_data['user_signature'];
$p_pw1 = isset($_POST['p_pw1']) ? $_POST['p_pw1'] : '';
$p_pw2 = isset($_POST['p_pw2']) ? $_POST['p_pw2'] : '';

$error = '';
$pwerror = '';

if(isset($_GET['doit'])) {
	if(trim($p_email) == '' || verify_email($p_email) != TRUE) $error = $lng['error_bad_email'];
	elseif(trim($p_pw1) != '' && $p_pw1 != $p_pw2) $pwerror = $lng['error_pws_no_match'];
	elseif($p_icq != '' && verify_icq_uin($p_icq) == FALSE) $error = $lng['error_bad_icq'];
	else {
		if(trim($p_pw1) != '') {
			$p_pwc = mycrypt($p_pw1);
			$_SESSION['tbb_user_pw'] = $p_pwc;
		}
		else $p_pwc = $user_data['user_pw'];

		update_user_data($user_id,array(
			'user_email'=>array('STR',$p_email),
			'user_realname'=>array('STR',$p_name),
			'user_location'=>array('STR',$p_location),
			'user_hp'=>array('STR',$p_hp),
			'user_interests'=>array('STR',$p_interests),
			'user_icq'=>array('STR',$p_icq),
			'user_yahoo'=>array('STR',$p_yahoo),
			'user_aim'=>array('STR',$p_aim),
			'user_msn'=>array('STR',$p_msn),
			'user_signature'=>array('STR',$p_signature),
			'user_pw'=>array('STR',$p_pwc)
		));

		include_once('pheader.php');

		show_navbar("<a href=\"index.php?$MYSID\">".$lng['Forumindex']."</a>\r<a href=\"index.php?faction=editprofile&amp;$MYSID\">".$lng['View_change_my_profile']."</a>\r".$lng['Profile_saved']);

		show_message('Profile_saved','message_profile_saved','<br />'.sprintf($lng['click_here_back_profile'],"<a href=\"index.php?faction=editprofile&amp;$MYSID\">",'</a>').'<br />'.sprintf($lng['click_here_back_forumindex'],"<a href=\"index.php?$MYSID\">",'</a>'));

		include_once('ptail.php'); exit;
	}
}

$editprofile_tpl = new template;
$editprofile_tpl->load($template_path.'/'.$tpl_config['tpl_editprofile']);

if($error != '') {
	$editprofile_tpl->blocks['errorrow']->values = array(
		'ERROR'=>$error
	);
	$editprofile_tpl->blocks['errorrow']->parse_code();
}
else $editprofile_tpl->unset_block('errorrow');

if($pwerror != '') {
	$editprofile_tpl->blocks['pwerrorrow']->values = array(
		'PWERROR'=>$pwerror
	);
	$editprofile_tpl->blocks['pwerrorrow']->parse_code();
}
else $editprofile_tpl->unset_block('pwerrorrow');

$editprofile_tpl->values = array(
	'MYSID'=>$MYSID,
	'USER_NICK'=>$user_data['user_nick'],
	'USER_POSTS'=>$user_data['user_posts'],
	'P_EMAIL'=>mutate($p_email),
	'P_NAME'=>mutate($p_name),
	'P_LOCATION'=>mutate($p_location),
	'P_HP'=>mutate($p_hp),
	'P_INTERESTS'=>mutate($p_interests),
	'P_ICQ'=>mutate($p_icq),
	'P_YAHOO'=>mutate($p_yahoo),
	'P_AIM'=>mutate($p_aim),
	'P_MSN'=>mutate($p_msn),
	'P_SIGNATURE'=>mutate($p_signature),
	'LNG_POSTS'=>$lng['Posts'],
	'LNG_USER_NAME'=>$lng['User_name'],
	'LNG_EMAILADDRESS'=>$lng['Emailaddress'],
	'LNG_REAL_NAME'=>$lng['Real_name'],
	'LNG_LOCATION'=>$lng['Location'],
	'LNG_HOMEPAGE'=>$lng['Homepage'],
	'LNG_INTERESTS'=>$lng['Interests'],
	'LNG_ICQ'=>$lng['ICQ'],
	'LNG_YAHOO'=>$lng['Yahoo'],
	'LNG_AIM'=>$lng['AIM'],
	'LNG_MSN'=>$lng['MSN'],
	'LNG_SIGNATURE'=>$lng['Signature'],
	'LNG_EDIT_PROFILE'=>$lng['Edit_profile'],
	'LNG_GENERAL_INFORMATION'=>$lng['General_information'],
	'LNG_CHANGE_PASSWORD'=>$lng['Change_password'],
	'LNG_NEW_PASSWORD'=>$lng['New_password'],
	'LNG_CONFIRM_NEW_PASSWORD'=>$lng['Confirm_new_password'],
	'LNG_RESET'=>$lng['Reset'],
	'LNG_NEW_PASSWORD_INFO'=>$lng['new_password_info'],
	'LNG_VIEW_CHANGE_MY_PROFILE'=>$lng['View_change_my_profile'],
	'LNG_DELETE_ACCOUNT'=>$lng['Delete_account']
);

include_once('pheader.php');

show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['View_change_my_profile']);

$editprofile_tpl->parse_code(TRUE);

include_once('ptail.php');

?>