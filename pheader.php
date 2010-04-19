<?php
/**
*
* Tritanium Bulletin Board 2 - pheader.php
* Zeigt den allgemeinen HTML-Kopf des Forums an
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
*
**/

require_once('auth.php');

$pheader_tpl = new template;
$pheader_tpl->load($template_path.'/'.$tpl_config['tpl_pheader']);

if($user_logged_in != 1) {
	$pheader_tpl->unset_block('user_logged_in');
	$pheader_tpl->blocks['user_not_logged_in']->values = array(
		'MYSID'=>$MYSID,
		'LNG_WHO_IS_ONLINE'=>$lng['Who_is_online'],
		'LNG_REGISTER'=>$lng['Register'],
		'LNG_LOGIN'=>$lng['Login'],
		'WELCOME_NOT_LOGGED_IN'=>sprintf($lng['welcome_not_logged_in'],$CONFIG['board_name']),
		'LNG_NICK'=>$lng['Nick'],
		'LNG_PW'=>$lng['PW']
	);
	$pheader_tpl->blocks['user_not_logged_in']->parse_code();
}
else {
	$pheader_tpl->unset_block('user_not_logged_in');
	$pheader_tpl->blocks['user_logged_in']->values = array(
		'MYSID'=>$MYSID,
		'LNG_MY_PROFILE'=>$lng['My_profile'],
		'LNG_WHO_IS_ONLINE'=>$lng['Who_is_online'],
		'LNG_LOGOUT'=>sprintf($lng['Logout_nick'],$user_data['user_nick']),
		'WELCOME_LOGGED_IN'=>sprintf($lng['welcome_logged_in'],$user_data['user_nick'])
	);
	$pheader_tpl->blocks['user_logged_in']->parse_code();
}

$pheader_tpl->values = array(
	'TITLE_ADD'=>$title_add,
	'STYLE_PATH'=>$template_path.'/styles/'.$template_style,
	'TEMPLATE_PATH'=>$template_path,
	'CONFIG_BOARD_NAME'=>$CONFIG['board_name']
);

$pheader_tpl->parse_code(TRUE);

unset($pheader_tpl);

?>