<?php

require_once('auth.php');

$title_add .= ' &#187; '.$lng['Who_is_online'];

if($CONFIG['enable_wio'] != 1) {
	include_once('pheader.php');
	show_navbar("<a href=\"index.php?$MYSID\">".$lng['Forumindex']."</a>\r".$lng['Who_is_online']);
	show_message('Function_deactivated','message_function_deactivated');
	include_once('ptail.php'); exit;
}

$wio_data = get_wio_data();

$viewwio_tpl = new template;
$viewwio_tpl->load($template_path.'/'.$tpl_config['tpl_viewwio']);

while(list(,$akt_wio) = each($wio_data)) {

	$wio_location = isset($lng['wio_'.$akt_wio['wio_last_location']]) ? $lng['wio_'.$akt_wio['wio_last_location']] : $lng['wio_forumindex'];

	$viewwio_tpl->blocks['wiorow']->values = array(
		'AKT_CLASS'=>$tpl_config['akt_class'],
		'WIO_USER_NICK'=>$akt_wio['wio_user_nick'],
		'WIO_LOCATION'=>$wio_location
	);
	$viewwio_tpl->blocks['wiorow']->parse_code(FALSE,TRUE);
	$tpl_config['akt_class'] = ($tpl_config['akt_class'] == $tpl_config['td1_class']) ? $tpl_config['td2_class'] : $tpl_config['td1_class'];
}

$viewwio_tpl->values = array(
	'LNG_WHO_IS_ONLINE'=>$lng['Who_is_online'],
);

include_once('pheader.php');

show_navbar("<a href=\"index.php?$MYSID\">".$lng['Forumindex']."</a>\r".$lng['Who_is_online']);

$viewwio_tpl->parse_code(TRUE);

include_once('ptail.php');

?>