<?php
/**
*
* Tritanium Bulletin Board 2 - auth.php
* Stellt fest, ob ein User eingeloggt ist
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
*
**/

$user_logged_in = 0;
$user_data = array('user_is_admin'=>0);
$user_id = 0;

session_name('sid');

session_start();

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