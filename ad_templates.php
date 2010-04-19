<?php
/**
*
* Tritanium Bulletin Board 2 - ad_templates.php
* Hier kann man sowohl ein Standardtemplate als auch ein Standardstyle aussuchen
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
*
**/

require_once('auth.php');

$p_standard_tpl = isset($_POST['p_standard_tpl']) ? $_POST['p_standard_tpl'] : $CONFIG['standard_tpl'];
$p_standard_style = isset($_POST['p_standard_style']) ? $_POST['p_standard_style'] : $CONFIG['standard_style'];
$p_allow_select_tpl = isset($_POST['p_allow_select_tpl']) ? $_POST['p_allow_select_tpl'] : $CONFIG['allow_select_tpl'];
$p_allow_select_style = isset($_POST['p_allow_select_style']) ? $_POST['p_allow_select_style'] : $CONFIG['allow_select_style'];

$checked = array('allow_select_tpl_0'=>'','allow_select_tpl_1'=>'','allow_select_style_0'=>'','allow_select_style_1'=>'');

if(isset($_GET['doit'])) {
	if($p_standard_tpl != $CONFIG['standard_tpl']) {
		update_config_data('standard_tpl',array(
			'config_value'=>array('STR',$p_standard_tpl)
		));

		include('templates/'.$p_standard_tpl.'/template_config.php');
		update_config_data('standard_style',array(
			'config_value'=>array('STR',$tpl_config['standard_style'])
		));
		header("Location: administration.php?faction=ad_templates&$MYSID"); exit;
	}
	else {
		update_config_data('standard_style',array(
			'config_value'=>array('STR',$p_standard_style)
		));
	}
	update_config_data('allow_select_tpl',array(
		'config_value'=>array('STR',$p_allow_select_tpl)
	));
	update_config_data('allow_select_style',array(
		'config_value'=>array('STR',$p_allow_select_style)
	));

	include_once('ad_pheader.php');
	show_message('Template_config_updated','message_template_config_updated');
	include_once('ad_ptail.php'); exit;
}

$x = ' selected="selected"';
$p_allow_select_tpl == 1 ? $checked['allow_select_tpl_1'] = $x : $checked['allow_select_tpl_0'] = $x;
$p_allow_select_style == 1 ? $checked['allow_select_style_1'] = $x : $checked['allow_select_style_0'] = $x;

$adtemplates_tpl = new template;
$adtemplates_tpl->load($template_path.'/'.$tpl_config['tpl_ad_templates_default']);

$temp_tpl_config = $tpl_config;

if(@$fp = opendir('templates')) {
	while($akt_dir = readdir($fp)) {
		if($akt_dir != '.' && $akt_dir != '..') {
			include('templates/'.$akt_dir.'/template_config.php');

			$akt_author = ($tpl_config['template_author_url'] == '') ? $tpl_config['template_author'] : '<a target="_blank" href="'.$tpl_config['template_author_url'].'">'.$tpl_config['template_author'].'</a>';

			$adtemplates_tpl->blocks['tplrow']->values = array(
				'TEMPLATE_NAME'=>$tpl_config['template_name'],
				'TEMPLATE_AUTHOR'=>$akt_author,
				'TEMPLATE_AUTHOR_COMMENT'=>$tpl_config['template_author_comment']

			);
			$adtemplates_tpl->blocks['tplrow']->parse_code(FALSE,TRUE);

			$akt_c = ($akt_dir == $p_standard_tpl) ? ' selected="selected"' : '';

			$adtemplates_tpl->blocks['tploptionrow']->values = array(
				'CHECKED'=>$akt_c,
				'TPL_FOLDER'=>$akt_dir,
				'TPL_NAME'=>$tpl_config['template_name']
			);
			$adtemplates_tpl->blocks['tploptionrow']->parse_code(FALSE,TRUE);
		}
	}
	closedir($fp);
}

$tpl_config = $temp_tpl_config;

if(@$fp = opendir('templates/'.$p_standard_tpl.'/styles')) {
	while($akt_dir = readdir($fp)) {
		if($akt_dir != '.' && $akt_dir != '..') {
			$akt_c = ($akt_dir == $p_standard_style) ? ' selected="selected"' : '';
			$adtemplates_tpl->blocks['stylerow']->values = array(
				'STYLE_NAME'=>$akt_dir,
				'CHECKED'=>$akt_c
			);
			$adtemplates_tpl->blocks['stylerow']->parse_code(FALSE,TRUE);
		}
	}
	closedir($fp);
}

$adtemplates_tpl->values = array(
	'MYSID'=>$MYSID,
	'C_ALLOW_SELECT_TPL_1'=>$checked['allow_select_tpl_1'],
	'C_ALLOW_SELECT_TPL_0'=>$checked['allow_select_tpl_0'],
	'C_ALLOW_SELECT_STYLE_1'=>$checked['allow_select_style_1'],
	'C_ALLOW_SELECT_STYLE_0'=>$checked['allow_select_style_0'],
	'LNG_YES'=>$lng['Yes'],
	'LNG_NO'=>$lng['No'],
	'LNG_TEMPLATE_NAME'=>$lng['Template_name'],
	'LNG_TEMPLATE_AUTHOR'=>$lng['Template_author'],
	'LNG_AUTHOR_COMMENT'=>$lng['Author_comment'],
	'LNG_TEMPLATE_SETTINGS'=>$lng['Template_settings'],
	'LNG_STANDARD_TEMPLATE'=>$lng['Standard_template'],
	'LNG_ALLOW_SELECT_STYLE'=>$lng['Allow_select_style'],
	'LNG_TEMPLATE_STANDARD_STYLE'=>$lng['Template_standard_style'],
	'LNG_ALLOW_SELECT_TEMPLATE'=>$lng['Allow_select_template'],
	'LNG_UPDATE_TEMPLATE_CONFIG'=>$lng['Update_template_config'],
	'LNG_RESET'=>$lng['Reset']
);

include_once('ad_pheader.php');

$adtemplates_tpl->parse_code(TRUE);

include_once('ad_ptail.php');

?>