<?php
/**
*
* Tritanium Bulletin Board 2 - viewwio.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$title_add .= ' &#187; '.$lng['Who_is_online'];

if($CONFIG['enable_wio'] != 1) {
	include_once('pheader.php');
	show_navbar("<a href=\"index.php?$MYSID\">".$lng['Forumindex']."</a>\r".$lng['Who_is_online']);
	show_message('Function_deactivated','message_function_deactivated');
	include_once('ptail.php'); exit;
}

$wio_data = get_wio_data(array('wio_is_ghost'=>0));

$viewwio_tpl = new template;
$viewwio_tpl->load($template_path.'/'.$tpl_config['tpl_viewwio']);

if(sizeof($wio_data) > 0) {
	while(list(,$akt_wio) = each($wio_data)) {

		$akt_wio_location = isset($lng['wio_'.$akt_wio['wio_last_location']]) ? $lng['wio_'.$akt_wio['wio_last_location']] : $lng['wio_forumindex'];

		$viewwio_tpl->blocks['wiorow']->parse_code(FALSE,TRUE);
		$tpl_config['akt_class'] = ($tpl_config['akt_class'] == $tpl_config['td1_class']) ? $tpl_config['td2_class'] : $tpl_config['td1_class'];
	}
}
else $viewwio_tpl->unset_block('wiorow');

include_once('pheader.php');

show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Who_is_online']);

$viewwio_tpl->parse_code(TRUE);

include_once('ptail.php');

?>