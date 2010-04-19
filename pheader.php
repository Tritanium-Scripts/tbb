<?php
/**
*
* Tritanium Bulletin Board 2 - pheader.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$pheader_tpl = new template;
$pheader_tpl->load($template_path.'/'.$tpl_config['tpl_pheader']);

if($CONFIG['board_logo'] != '') $board_banner = '<img src="'.$CONFIG['board_logo'].'" alt="'.$CONFIG['board_name'].'" />';
else $board_banner = $CONFIG['board_name'];

if($user_logged_in != 1) {
	$pheader_tpl->unset_block('user_logged_in');
	$welcome_text = sprintf($lng['welcome_not_logged_in'],$CONFIG['board_name']);
	$pheader_tpl->blocks['user_not_logged_in']->parse_code();
}
else {
	$pheader_tpl->unset_block('user_not_logged_in');
	$welcome_text = sprintf($lng['welcome_logged_in'],$user_data['user_nick']);
	$pheader_tpl->blocks['user_logged_in']->parse_code();
}

$pheader_tpl->parse_code(TRUE);

unset($pheader_tpl);

?>