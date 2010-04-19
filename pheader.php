<?php
/**
*
* Tritanium Bulletin Board 2 - pheader.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

create_header_title();

$pheader_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['pheader']);

if($CONFIG['board_logo'] != '') $board_banner = '<img src="'.$CONFIG['board_logo'].'" alt="'.$CONFIG['board_name'].'" />';
else $board_banner = $CONFIG['board_name'];

$welcome_text = ($USER_LOGGED_IN == 1) ? sprintf($LNG['welcome_logged_in'],$USER_DATA['user_nick']) : sprintf($LNG['welcome_not_logged_in'],$CONFIG['board_name']);

$pheader_tpl->parse_code(TRUE);

unset($pheader_tpl);

?>