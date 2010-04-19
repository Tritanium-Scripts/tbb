<?php
/**
*
* Tritanium Bulletin Board 2 - login.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$error = '';

$p_nick = isset($_POST['p_nick']) ? $_POST['p_nick'] : '';
$p_pw = isset($_POST['p_pw']) ? $_POST['p_pw'] :'';

$p_hide_from_wio = 0;

if(isset($_GET['doit'])) {
	$p_hide_from_wio = isset($_POST['p_hide_from_wio']) ? 1 : 0;

	if(!$p_user_data = get_user_data($p_nick)) $error = $lng['error_unknown_user'];
	elseif($p_user_data['user_status'] == 0) $error = sprintf($lng['error_inactive_account'],$p_user_data['user_nick']);
	elseif(mycrypt($p_pw) != $p_user_data['user_pw']) $error = $lng['error_wrong_password'];
	else {

		$_SESSION['tbb_user_id'] = $p_user_data['user_id'];
		$_SESSION['tbb_user_pw'] = $p_user_data['user_pw'];

		$_SESSION['s_hide_from_wio'] = $p_hide_from_wio;

		$db->query("UPDATE ".TBLPFX."sessions SET session_user_id='".$p_user_data['user_id']."', session_is_ghost='".$p_hide_from_wio."' WHERE session_id='".session_id()."'",TRUE);

		isset($_SESSION['last_place_url']) ? header("Location: ".$_SESSION['last_place_url']) : header("Location: index.php?$MYSID");

		exit;
	}
}

$checked['hide_from_wio'] = ($p_hide_from_wio == 1) ? ' checked="checked"' : '';

$login_tpl = new template;
$login_tpl->load($template_path.'/'.$tpl_config['tpl_login']);

if($error != '') $login_tpl->blocks['errorrow']->parse_code();
else $login_tpl->unset_block('errorrow');

include_once('pheader.php');

show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Login']);

$login_tpl->parse_code(TRUE);

include_once('ptail.php');

?>