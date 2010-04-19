<?php
/**
*
* Tritanium Bulletin Board 2 - pheader.php
* version #2004-01-01-18-38-43
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$title_add = implode(' &#187; ',$title_add);

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