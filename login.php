<?php
/**
*
* Tritanium Bulletin Board 2 - login.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$error = '';

$p_nick = isset($_REQUEST['p_nick']) ? $_REQUEST['p_nick'] : '';
$p_pw = isset($_POST['p_pw']) ? $_POST['p_pw'] :'';

$p_hide_from_wio = 0;

if(isset($_GET['doit'])) {
	$p_hide_from_wio = isset($_POST['p_hide_from_wio']) ? 1 : 0;
	$p_pwc = mycrypt($p_pw);

	if(!$p_user_data = get_user_data($p_nick)) $error = $LNG['error_unknown_user'];
	elseif($p_user_data['user_status'] == 0) $error = sprintf($LNG['error_inactive_account'],$p_user_data['user_nick']);
	elseif(($p_pwc != $p_user_data['user_pw']) && ($p_user_data['user_new_pw'] == '' || $p_pwc != $p_user_data['user_new_pw'])) $error = $LNG['error_wrong_password'];
	elseif($p_user_data['user_is_locked'] == 1 && check_lock_status($p_user_data['user_id']) == TRUE) { // Falls der Benutzer sich nicht mehr einloggen darf
		$DB->query("SELECT lock_start_time,lock_dur_time FROM ".TBLPFX."users_locks WHERE user_id='".$p_user_data['user_id']."'");
		$lock_data = $DB->fetch_array();

		if($lock_data['lock_dur_time'] == 0) $remaining_lock_time = $LNG['locked_forever'];
		else {
			$remaining_lock_time = split_time($lock_data['lock_start_time']+$lock_data['lock_dur_time']-time());

			$remaining_months = sprintf($LNG['x_months'],$remaining_lock_time['months']);
			$remaining_weeks = sprintf($LNG['x_weeks'],$remaining_lock_time['weeks']);
			$remaining_days = sprintf($LNG['x_days'],$remaining_lock_time['days']);
			$remaining_hours = sprintf($LNG['x_hours'],$remaining_lock_time['hours']);
			$remaining_minutes = sprintf($LNG['x_minutes'],$remaining_lock_time['minutes']);
			$remaining_seconds = sprintf($LNG['x_seconds'],$remaining_lock_time['seconds']);

			$remaining_lock_time = "$remaining_months, $remaining_weeks, $remaining_days, $remaining_hours, $remaining_minutes, $remaining_seconds";
		}

		$error = sprintf($LNG['error_locked_account'],$remaining_lock_time);
	}
	else {
		$_SESSION['tbb_user_id'] = $p_user_data['user_id'];
		$_SESSION['tbb_user_pw'] = $p_pwc;

		$_SESSION['s_hide_from_wio'] = $p_hide_from_wio;

		$DB->query("UPDATE ".TBLPFX."sessions SET session_user_id='".$p_user_data['user_id']."', session_is_ghost='".$p_hide_from_wio."' WHERE session_id='".session_id()."'",TRUE);

		if($p_pwc == $p_user_data['user_pw'] && $p_user_data['user_new_pw'] != '') // Falls ein neues Passwort da ist, das gar nicht gebraucht wurde...
			$DB->query("UPDATE ".TBLPFX."users SET user_new_pw='' WHERE user_id='".$p_user_data['user_id']."'"); // ...wird es geloescht
		elseif($p_user_data['user_new_pw'] != '' && $p_pwc == $p_user_data['user_new_pw']) // Falls ein neues Passwort verwendet wurde...
			$DB->query("UPDATE ".TBLPFX."users SET user_new_pw='', user_pw='".$p_user_data['user_new_pw']."' WHERE user_id='".$p_user_data['user_id']."'"); // ...wird das alte geloescht und das neue als Standard gespeichert

		isset($_SESSION['last_place_url']) ? header("Location: ".$_SESSION['last_place_url']) : header("Location: index.php?$MYSID");

		exit;
	}
}

$checked['hide_from_wio'] = ($p_hide_from_wio == 1) ? ' checked="checked"' : '';

$login_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['login']);

if($error != '') $login_tpl->blocks['errorrow']->parse_code();

add_navbar_items(array($LNG['Login'],''));

include_once('pheader.php');
show_navbar();
$login_tpl->parse_code(TRUE);
include_once('ptail.php');

?>