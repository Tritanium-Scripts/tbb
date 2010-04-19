<?php
/**
*
* Tritanium Bulletin Board 2 - search.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

if($CONFIG['search_status'] == 0) {
	include_once('pheader.php');
	show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Function_deactivated']);
	show_message('Function_deactivated','message_function_deactivated');
	include_once('ptail.php'); exit;
}
elseif($user_logged_in != 1 && $CONFIG['search_status'] == 1) {
	include_once('pheader.php');
	show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Not_logged_in']);
	show_message('Not_logged_in','message_not_logged_in');
	include_once('ptail.php'); exit;
}

die('Funktion noch nicht verfügbar! <a href="javascript:history.back(0);">Zurück</a>');

?>