<?php
/**
*
* Tritanium Bulletin Board 2 - auth.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

$user_logged_in = 0;
$user_data = array('user_is_admin'=>0);
$user_id = 0;

session_name('sid');

session_start();

if(!isset($_SESSION['session_ip']))	$_SESSION['session_ip'] = $_SERVER['REMOTE_ADDR'];
elseif($_SESSION['session_ip'] != $_SERVER['REMOTE_ADDR']) {
	session_destroy();
	die('Nicht erlaubt diese Session zu verwenden');
}

$MYSID = 'sid='.session_id();

if(isset($_SESSION['tbb_user_id'])) {
	if(($t_user_data = get_user_data($_SESSION['tbb_user_id'])) && ($t_user_data['user_pw'] == $_SESSION['tbb_user_pw'])) {
		$user_logged_in = 1;
		$user_data = &$t_user_data;
		$user_id = &$user_data['user_id'];
		unset($t_user_data);
	}
}

if(!isset($_SESSION['s_hide_from_wio'])) $_SESSION['s_hide_from_wio'] = 0;

?>