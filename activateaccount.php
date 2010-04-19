<?php
/**
*
* Tritanium Bulletin Board 2 - activateaccount.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$account_id = isset($_REQUEST['account_id']) ? $_REQUEST['account_id'] : '';
$activation_code = isset($_REQUEST['activation_code']) ? $_REQUEST['activation_code'] : '';

$error = '';

add_navbar_items(array($lng['Account_activation'],"index.php?faction=activateaccount&amp;$MYSID"));

if(isset($_GET['doit'])) {
	if(!$account_id = get_user_id($account_id)) $error = $lng['error_unknown_user'];
	else {
		$account_data = get_user_data($account_id);
		if($account_data['user_status'] != 0 || $account_data['user_hash'] == '') $error = $lng['error_no_inactive_account'];
		elseif($account_data['user_hash'] != $activation_code) $error = $lng['error_wrong_activation_code'];
		else {
			$db->query("UPDATE ".TBLPFX."users SET user_status='1', user_hash='' WHERE user_id='$account_id'");

			$_SESSION['last_place_url'] = "index.php?$MYSID";

			add_navbar_items(array($lng['Account_activated'],''));

			include_once('pheader.php');
			show_navbar();
			show_message($lng['Account_activated'],$lng['message_account_activated'].'<br />'.$lng['click_here_login']);
			include_once('ptail.php'); exit;
		}
	}
}

$aaccount_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['activateaccount']);

if($error != '') $aaccount_tpl->blocks['errorrow']->parse_code();

include_once('pheader.php');
show_navbar();
$aaccount_tpl->parse_code(TRUE);
include_once('ptail.php');

?>