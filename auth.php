<?php
/**
*
* Tritanium Bulletin Board 2 - auth.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

if(defined('SECURITY') == FALSE) {
	header("Location: index.php"); exit;
}

$USER_LOGGED_IN = 0;
$USER_DATA = array('user_is_admin'=>0);
$USER_ID = 0;

if(!isset($_SESSION['session_ip']))	$_SESSION['session_ip'] = $_SERVER['REMOTE_ADDR'];
elseif($_SESSION['session_ip'] != $_SERVER['REMOTE_ADDR'])
	die('Nicht erlaubt diese Session zu verwenden');

if(isset($_SESSION['tbb_user_id'])) {
	if(($t_user_data = get_user_data($_SESSION['tbb_user_id'])) && ($t_user_data['user_pw'] == $_SESSION['tbb_user_pw'])) {
		if($t_user_data['user_is_locked'] == 1 && check_lock_status($t_user_data['user_id']) == TRUE) { // Falls der Benutzer gesperrt ist (sich nicht einloggen darf)...
			session_destroy(); // ...Session zerstoeren...
			header("Location: index.php"); exit; // ...und zum "Anfang" weiterleiten
		}
		$USER_LOGGED_IN = 1;
		$USER_DATA = &$t_user_data;
		$USER_ID = &$USER_DATA['user_id'];
		$db->query("UPDATE ".TBLPFX."users SET user_last_action='".time()."' WHERE user_id='$USER_ID'");
		unset($t_user_data);
	}
}

if(!isset($_SESSION['s_hide_from_wio'])) $_SESSION['s_hide_from_wio'] = 0;

if($CONFIG['enable_pms'] == 1 && $USER_LOGGED_IN == 1) {
	if(!isset($_SESSION['s_last_pm_time'])) $_SESSION['s_last_pm_time'] = 0;
	$db->query("SELECT pm_send_time AS last_pm_time FROM ".TBLPFX."pms WHERE pm_to_id='$USER_ID' AND pm_read_status='0' AND pm_send_time>'".$_SESSION['s_last_pm_time']."' ORDER BY pm_send_time DESC LIMIT 1");
	if($db->affected_rows != 0) {
		list($last_send_time) = $db->fetch_array();
		$_SESSION['s_last_pm_time'] = $last_send_time;
		$STATS['new_pm'] = 1;
	}
}

?>