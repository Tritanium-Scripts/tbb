<?php
/**
*
* Tritanium Bulletin Board 2 - requestpassword.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$p_user_name = isset($_POST['p_user_name']) ? $_POST['p_user_name'] : '';
$p_email_address = isset($_POST['p_email_address']) ? $_POST['p_email_address'] : '';

$error = '';

add_navbar_items(array($LNG['Login'],"index.php?faction=login&amp;$MYSID"),array($LNG['Request_new_password'],"index.php?faction=requestpassword&amp;$MYSID"));

if(isset($_GET['doit'])) {
	if(!$p_user_data = get_user_data($p_user_name)) $error = $LNG['error_unknown_user'];
	elseif($p_user_data['user_email'] != $p_email_address) $error = $LNG['error_wrong_email_address'];
	else {
		$new_pw = get_rand_string(8);
		$new_pwc = mycrypt($new_pw);

		$DB->query("UPDATE ".TBLPFX."users SET user_new_pw='$new_pwc' WHERE user_id='".$p_user_data['user_id']."'");

		if($CONFIG['enable_email_functions'] == 1) {
			$email_tpl = new template($LANGUAGE_PATH.'/emails/email_password_requested.tpl');
			mymail('"'.$CONFIG['board_name'].'" <'.$CONFIG['board_email_address'].'>',$p_user_data['user_email'],$LNG['email_subject_new_password_requested'],$email_tpl->parse_code());
		}

		add_navbar_items(array($LNG['New_password_sent'],''));

		include_once('pheader.php');
		show_navbar();
		show_message($LNG['New_password_sent'],$LNG['message_new_password_sent'].'<br />'.sprintf($LNG['click_here_login'],"<a href=\"index.php?faction=login&amp;$MYSID\">",'</a>').'<br />'.sprintf($LNG['click_here_back_forumindex'],"<a href=\"index.php?$MYSID\">",'</a>'));
		include_once('ptail.php'); exit;
	}
}

$requestpw_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['requestpassword']);

if($error != '') $requestpw_tpl->blocks['errorrow']->parse_code();

include_once('pheader.php');
show_navbar();
$requestpw_tpl->parse_code(TRUE);
include_once('ptail.php');

?>