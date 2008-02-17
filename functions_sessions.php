<?php
/**
*
* Tritanium Bulletin Board 2 - functions_sessions.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

function session_data_handler_open($save_path,$session_name) {
	return TRUE;
}

function session_data_handler_close() {
	return TRUE;
}

function session_data_handler_read($session_id) {
	global $DB,$USER_ID;

	$DB->query("SELECT session_data FROM ".TBLPFX."sessions WHERE session_id='$session_id'");
	if($DB->affected_rows == 0) {
		$DB->query("INSERT INTO ".TBLPFX."sessions (session_id,session_user_id) VALUES ('$session_id','$USER_ID')");
		return "";
	}

	list($session_data) = $DB->fetch_array();
	return $session_data;
}

function session_data_handler_write($session_id,$session_data) {
	global $DB;

	$session_data = mysql_escape_string($session_data);

	$DB->query("UPDATE ".TBLPFX."sessions SET session_data='$session_data', session_last_update=NOW() WHERE session_id='$session_id'");
	if($DB->affected_rows == 0)
		return FALSE;

	return TRUE;
}

function session_data_handler_destroy($session_id) {
	global $DB;

	$DB->query("DELETE FROM ".TBLPFX."sessions WHERE session_id='$session_id'");
	return ($DB->affected_rows == 0) ? FALSE : TRUE;
}

function session_data_handler_gc($session_max_lifetime) {
	global $DB;

	$DB->query("DELETE FROM ".TBLPFX."sessions WHERE session_last_update<".unixtstamp2sqltstamp(time()-$session_max_lifetime));

	return TRUE;
}

?>