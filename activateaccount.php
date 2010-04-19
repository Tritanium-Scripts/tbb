<?php
/**
*
* Tritanium Bulletin Board 2 - activateaccount.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$account_id = isset($_REQUEST['account_id']) ? $_REQUEST['account_id'] : '';
$activation_code = isset($_REQUEST['activation_code']) ? $_REQUEST['activation_code'] : '';

$error = '';

if(isset($_GET['doit'])) {
	if(!$account_id = get_user_id($account_id)) $error = $lng['error_unknown_user'];
	else {
		$account_data = get_user_data($account_id);
		if($account_data['user_status'] != 0 || $account_data['user_hash'] == '') $error = $lng['error_no_inactive_account'];
		elseif($account_data['user_hash'] != $activation_code) $error = $lng['error_wrong_activation_code'];
		else {
			$db->query("UPDATE ".TBLPFX."users SET user_status='1', user_hash='' WHERE user_id='$account_id'");

			$_SESSION['last_place_url'] = "index.php?$MYSID";

			include_once('pheader.php');

			show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=activateaccount&amp;$MYSID\">".$lng['Account_activation']."</a>\r".$lng['Account_activated']);

			show_message('Account_activated','message_account_activated','<br />'.$lng['click_here_login']);

			include_once('ptail.php'); exit;
		}
	}
}

$aaccount_tpl = new template;
$aaccount_tpl->load($template_path.'/'.$tpl_config['tpl_activateaccount']);

if($error != '') $aaccount_tpl->blocks['errorrow']->parse_code();
else $aaccount_tpl->unset_block('errorrow');

$title_add[] = $lng['Account_activation'];

include_once('pheader.php');

show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Account_activation']);

$aaccount_tpl->parse_code(TRUE);

include_once('ptail.php');

?>