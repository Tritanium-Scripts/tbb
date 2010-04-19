<?php
/**
*
* Tritanium Bulletin Board 2 - auth.php
* version #2004-01-01-18-38-43
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

if(defined('SECURITY') == FALSE) {
	header("Location: index.php"); exit;
}

$user_logged_in = 0;
$user_data = array('user_is_admin'=>0);
$user_id = 0;

session_name('sid');

session_start();

if(!isset($_SESSION['session_ip']))	$_SESSION['session_ip'] = $_SERVER['REMOTE_ADDR'];
elseif($_SESSION['session_ip'] != $_SERVER['REMOTE_ADDR'])
	die('Nicht erlaubt diese Session zu verwenden');

$MYSID = 'sid='.session_id();

if(isset($_SESSION['tbb_user_id'])) {
	if(($t_user_data = get_user_data($_SESSION['tbb_user_id'])) && ($t_user_data['user_pw'] == $_SESSION['tbb_user_pw'])) {
		$user_logged_in = 1;
		$user_data = &$t_user_data;
		$user_id = &$user_data['user_id'];
		$db->query("UPDATE ".TBLPFX."users SET user_last_action='".time()."' WHERE user_id='$user_id'");
		unset($t_user_data);
	}
}

if(!isset($_SESSION['s_hide_from_wio'])) $_SESSION['s_hide_from_wio'] = 0;

if($CONFIG['enable_wio'] == 1) {
	$timeout = $CONFIG['wio_timeout']*60;
	$time = time();
	$db->query("DELETE FROM ".TBLPFX."wio WHERE wio_last_action+$timeout < $time");

	$db->query("SELECT wio_session_id FROM ".TBLPFX."wio WHERE wio_session_id='".session_id()."'");
	if($db->affected_rows == 0)
		$db->query("INSERT INTO ".TBLPFX."wio (wio_session_id,wio_user_id,wio_last_action,wio_last_location,wio_is_ghost) VALUES ('".session_id()."','$user_id','$time','forumindex','".$_SESSION['s_hide_from_wio']."')");
}


if($CONFIG['enable_pms'] == 1 && $user_logged_in == 1) {
	if(!isset($_SESSION['s_last_pm_time'])) $_SESSION['s_last_pm_time'] = 0;
	$db->query("SELECT UNIX_TIMESTAMP(pm_send_time) AS last_pm_time FROM ".TBLPFX."pms WHERE pm_to_id='$user_id' AND pm_read_status='0' AND UNIX_TIMESTAMP(pm_send_time)>'".$_SESSION['s_last_pm_time']."' ORDER BY pm_send_time DESC LIMIT 1");
	if($db->affected_rows != 0) {
		list($last_send_time) = $db->fetch_array();
		$_SESSION['s_last_pm_time'] = $last_send_time;
		$STATS['new_pm'] = 1;
	}
}

?>