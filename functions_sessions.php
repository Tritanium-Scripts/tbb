<?php
/**
*
* Tritanium Bulletin Board 2 - functions_sessions.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

function session_data_handler_open($save_path,$session_name) {
	return TRUE;
}

function session_data_handler_close() {
	return TRUE;
}

function session_data_handler_read($session_id) {
	global $db,$USER_ID;

	$db->query("SELECT session_data FROM ".TBLPFX."sessions WHERE session_id='$session_id'");
	if($db->affected_rows == 0) {
		$db->query("INSERT INTO ".TBLPFX."sessions (session_id,session_user_id) VALUES ('$session_id','$USER_ID')");
		return "";
	}

	list($session_data) = $db->fetch_array();
	return $session_data;
}

function session_data_handler_write($session_id,$session_data) {
	global $db;

	$session_data = mysql_escape_string($session_data);

	$db->query("UPDATE ".TBLPFX."sessions SET session_data='$session_data', session_last_update=NOW() WHERE session_id='$session_id'");
	if($db->affected_rows == 0)
		return FALSE;

	return TRUE;
}

function session_data_handler_destroy($session_id) {
	global $db;

	$db->query("DELETE FROM ".TBLPFX."sessions WHERE session_id='$session_id'");
	return ($db->affected_rows == 0) ? FALSE : TRUE;
}

function session_data_handler_gc($session_max_lifetime) {
	global $db;

	$db->query("DELETE FROM ".TBLPFX."sessions WHERE session_last_update<".unixtstamp2sqltstamp(time()-$session_max_lifetime));

	return TRUE;
}

?>