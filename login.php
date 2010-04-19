<?php
/**
*
* Tritanium Bulletin Board 2 - login.php
* version #2004-01-01-18-38-43
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

$error = '';

$p_nick = isset($_POST['p_nick']) ? $_POST['p_nick'] : '';
$p_pw = isset($_POST['p_pw']) ? $_POST['p_pw'] :'';

$p_hide_from_wio = 0;

if(isset($_GET['doit'])) {
	$p_hide_from_wio = isset($_POST['p_hide_from_wio']) ? 1 : 0;

	if(!$user_data = get_user_data($p_nick)) $error = 'Dieser User existiert nicht!';
	elseif(mycrypt($p_pw) != $user_data['user_pw']) $error = 'Falsches Passwort!';
	else {

		$_SESSION['tbb_user_id'] = $user_data['user_id'];
		$_SESSION['tbb_user_pw'] = $user_data['user_pw'];

		$_SESSION['s_hide_from_wio'] = $p_hide_from_wio;

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