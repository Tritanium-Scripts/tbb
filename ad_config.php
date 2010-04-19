<?php
/**
*
* Tritanium Bulletin Board 2 - ad_config.php
* Legt die Einstellungen des Boards fest
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
*
**/

require_once('auth.php');

$p_config = array();

$p_config['posts_per_page'] = isset($_POST['p_config']['posts_per_page']) ? $_POST['p_config']['posts_per_page'] : $CONFIG['posts_per_page'];
$p_config['topics_per_page'] = isset($_POST['p_config']['topics_per_page']) ? $_POST['p_config']['topics_per_page'] : $CONFIG['topics_per_page'];
$p_config['maximum_sig_length'] = isset($_POST['p_config']['maximum_sig_legth']) ? $_POST['p_config']['maximum_sig_legth'] : $CONFIG['maximum_sig_length'];
$p_config['board_name'] = isset($_POST['p_config']['board_name']) ? $_POST['p_config']['board_name'] : $CONFIG['board_name'];
$p_config['time_format'] = isset($_POST['p_config']['time_format']) ? $_POST['p_config']['time_format'] : $CONFIG['time_format'];
$p_config['enable_wio'] = isset($_POST['p_config']['enable_wio']) ? $_POST['p_config']['enable_wio'] : $CONFIG['enable_wio'];
$p_config['enable_sig'] = isset($_POST['p_config']['enable_sig']) ? $_POST['p_config']['enable_sig'] : $CONFIG['enable_sig'];
$p_config['allow_sig_bbcode'] = isset($_POST['p_config']['allow_sig_bbcode']) ? $_POST['p_config']['allow_sig_bbcode'] : $CONFIG['allow_sig_bbcode'];
$p_config['allow_sig_html'] = isset($_POST['p_config']['allow_sig_html']) ? $_POST['p_config']['allow_sig_html'] : $CONFIG['allow_sig_html'];
$p_config['standard_language'] = isset($_POST['p_config']['standard_language']) ? $_POST['p_config']['standard_language'] : $CONFIG['standard_language'];
$p_config['allow_select_lng'] = isset($_POST['p_config']['allow_select_lng']) ? $_POST['p_config']['allow_select_lng'] : $CONFIG['allow_select_lng'];
$p_config['show_wio_forumindex'] = isset($_POST['p_config']['show_wio_forumindex']) ? $_POST['p_config']['show_wio_forumindex'] : $CONFIG['show_wio_forumindex'];
$p_config['wio_timeout'] = isset($_POST['p_config']['wio_timeout']) ? $_POST['p_config']['wio_timeout'] : $CONFIG['wio_timeout'];

$error = '';

$checked = array('enable_sig_0'=>'','enable_sig_1'=>'','allow_sig_bbcode_0'=>'','allow_sig_bbcode_1'=>'',
	'allow_sig_html_0'=>'','allow_sig_html_1'=>'','enable_wio_0'=>'','enable_wio_1'=>'',
	'allow_select_lng_0'=>'','allow_select_lng_1'=>'','show_wio_forumindex_0'=>'','show_wio_forumindex_1'=>''
);

if(isset($_GET['doit'])) {
	while(list($akt_key,$akt_value) = each($p_config)) {
		update_config_data($akt_key,array(
			'config_value'=>array('STR',$akt_value)
		));
	}

	include_once('ad_pheader.php');
	show_message('Board_config_updated','message_board_config_updated');
	include_once('ad_ptail.php'); exit;
}

$x = ' selected="selected"';
$p_config['enable_wio'] == 1 ? $checked['enable_wio_1'] = $x : $checked['enable_wio_0'] = $x;
$p_config['enable_sig'] == 1 ? $checked['enable_sig_1'] = $x : $checked['enable_sig_0'] = $x;
$p_config['allow_sig_bbcode'] == 1 ? $checked['allow_sig_bbcode_1'] = $x : $checked['allow_sig_bbcode_0'] = $x;
$p_config['allow_sig_html'] == 1 ? $checked['allow_sig_html_1'] = $x : $checked['allow_sig_html_0'] = $x;
$p_config['allow_select_lng'] == 1 ? $checked['allow_select_lng_1'] = $x : $checked['allow_select_lng_0'] = $x;
$p_config['show_wio_forumindex'] == 1 ? $checked['show_wio_forumindex_1'] = $x : $checked['show_wio_forumindex_0'] = $x;

$adconfig_tpl = new template;
$adconfig_tpl->load($template_path.'/'.$tpl_config['tpl_ad_config']);

if(@$fp = opendir('language')) {
	while($akt_dir = readdir($fp)) {
		if($akt_dir != '.' && $akt_dir != '..') {
			$akt_c = ($akt_dir == $p_config['standard_language']) ? ' selected="selected"' : '';
			$adconfig_tpl->blocks['lng_optionrow']->values = array(
				'CHECKED'=>$akt_c,
				'DIR_NAME'=>$akt_dir
			);
			$adconfig_tpl->blocks['lng_optionrow']->parse_code(FALSE,TRUE);
		}
	}
	closedir($fp);
}


$adconfig_tpl->values = array(
	'MYSID'=>$MYSID,
	'P_CONFIG_POSTS_PER_PAGE'=>$p_config['posts_per_page'],
	'P_CONFIG_TOPICS_PER_PAGE'=>$p_config['topics_per_page'],
	'P_CONFIG_MAXIMUM_SIG_LENGTH'=>$p_config['maximum_sig_length'],
	'P_CONFIG_TIME_FORMAT'=>$p_config['time_format'],
	'P_CONFIG_BOARD_NAME'=>$p_config['board_name'],
	'P_CONFIG_WIO_TIMEOUT'=>$p_config['wio_timeout'],
	'C_ENABLE_WIO_0'=>$checked['enable_wio_0'],
	'C_ENABLE_WIO_1'=>$checked['enable_wio_1'],
	'C_ENABLE_SIG_0'=>$checked['enable_sig_0'],
	'C_ENABLE_SIG_1'=>$checked['enable_sig_1'],
	'C_ALLOW_SIG_BBCODE_0'=>$checked['allow_sig_bbcode_0'],
	'C_ALLOW_SIG_BBCODE_1'=>$checked['allow_sig_bbcode_1'],
	'C_ALLOW_SIG_HTML_0'=>$checked['allow_sig_html_0'],
	'C_ALLOW_SIG_HTML_1'=>$checked['allow_sig_html_1'],
	'C_ALLOW_SELECT_LNG_0'=>$checked['allow_select_lng_0'],
	'C_ALLOW_SELECT_LNG_1'=>$checked['allow_select_lng_1'],
	'C_SHOW_WIO_FORUMINDEX_0'=>$checked['show_wio_forumindex_0'],
	'C_SHOW_WIO_FORUMINDEX_1'=>$checked['show_wio_forumindex_1'],
	'LNG_WHO_IS_ONLINE_SETTINGS'=>$lng['Who_is_online_settings'],
	'LNG_BOARD_NAME'=>$lng['Board_name'],
	'LNG_TIME_FORMAT'=>$lng['Time_format'],
	'LNG_GENERAL_SETTINGS'=>$lng['General_settings'],
	'LNG_BOARDCONFIG'=>$lng['Boardconfig'],
	'LNG_UPDATE_CONFIG'=>$lng['Update_config'],
	'LNG_RESET'=>$lng['Reset'],
	'LNG_POSTS_PER_PAGE'=>$lng['Posts_per_page'],
	'LNG_TOPICS_PER_PAGE'=>$lng['Topics_per_page'],
	'LNG_YES'=>$lng['Yes'],
	'LNG_NO'=>$lng['No'],
	'LNG_MAXIMUM_SIGNATURE_LENGTH'=>$lng['Maximum_signature_length'],
	'LNG_ALLOW_SIGNATURE_BBCODE'=>$lng['Allow_signature_bbcode'],
	'LNG_ALLOW_SIGNATURE_HTML'=>$lng['Allow_signature_html'],
	'LNG_ENABLE_SIGNATURE'=>$lng['Enable_signature'],
	'LNG_SIGNATURE_SETTINGS'=>$lng['Signature_settings'],
	'LNG_ENABLE_WHO_IS_ONLINE'=>$lng['Enable_who_is_online'],
	'LNG_LANGUAGE_SETTINGS'=>$lng['Language_settings'],
	'LNG_STANDARD_LANGUAGE'=>$lng['Standard_language'],
	'LNG_ALLOW_SELECT_LANGUAGE'=>$lng['Allow_select_language'],
	'LNG_WHO_IS_ONLINE_TIMEOUT'=>$lng['Who_is_online_timeout'],
	'LNG_IN_MINUTES'=>$lng['in_minutes'],
	'LNG_SHOW_WIO_IS_ONLINE_BOX_FORUMINDEX'=>$lng['Show_who_is_online_box_forumindex']
);

include_once('ad_pheader.php');

$adconfig_tpl->parse_code(TRUE);

include_once('ad_ptail.php');

?>