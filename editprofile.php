<?php
/**
*
* Tritanium Bulletin Board 2 - editprofile.php
* version #2004-01-01-18-38-43
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

if($user_logged_in != 1) die('Nicht eingeloggt!');

$title_add[] = $lng['Edit_profile'];

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

		$db->query("UPDATE ".TBLPFX."users SET user_email='$p_email', user_realname='$p_name', user_location='$p_location', user_hp='$p_hp', user_interests='$p_interests', user_icq='$p_icq', user_yahoo='$p_yahoo', user_aim='$p_aim', user_msn='$p_msn', user_signature='$p_signature', user_pw='$p_pwc' WHERE user_id='$user_id'");

		include_once('pheader.php');

		show_navbar("<a href=\"index.php?$MYSID\">".$lng['Forumindex']."</a>\r<a href=\"index.php?faction=editprofile&amp;$MYSID\">".$lng['View_change_my_profile']."</a>\r".$lng['Profile_saved']);

		show_message('Profile_saved','message_profile_saved','<br />'.sprintf($lng['click_here_back_profile'],"<a href=\"index.php?faction=editprofile&amp;$MYSID\">",'</a>').'<br />'.sprintf($lng['click_here_back_forumindex'],"<a href=\"index.php?$MYSID\">",'</a>'));

		include_once('ptail.php'); exit;
	}
}

$editprofile_tpl = new template;
$editprofile_tpl->load($template_path.'/'.$tpl_config['tpl_editprofile']);

if($error != '') $editprofile_tpl->blocks['errorrow']->parse_code();
else $editprofile_tpl->unset_block('errorrow');

if($pwerror != '') $editprofile_tpl->blocks['pwerrorrow']->parse_code();
else $editprofile_tpl->unset_block('pwerrorrow');

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

show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['View_change_my_profile']);

$editprofile_tpl->parse_code(TRUE);

include_once('ptail.php');

?>