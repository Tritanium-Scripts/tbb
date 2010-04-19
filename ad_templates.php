<?php
/**
*
* Tritanium Bulletin Board 2 - ad_templates.php
* version #2004-01-01-18-38-43
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
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
		$db->query("UPDATE ".TBLPFX."config SET config_value='$standard_tpl' WHERE config_name='standard_tpl'");

		include('templates/'.$p_standard_tpl.'/template_config.php');
		$db->query("UPDATE ".TBLPFX."config SET config_value='$standard_style' WHERE config_name='standard_style'");

		header("Location: administration.php?faction=ad_templates&$MYSID"); exit;
	}
	else
		$db->query("UPDATE ".TBLPFX."config SET config_value='$p_standard_style' WHERE config_name='standard_style'");

	$db->query("UPDATE ".TBLPFX."config SET config_value='$p_allow_select_tpl' WHERE config_name='allow_select_tpl'");
	$db->query("UPDATE ".TBLPFX."config SET config_value='$p_allow_select_style' WHERE config_name='allow_select_style'");

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

			$adtemplates_tpl->blocks['tplrow']->parse_code(FALSE,TRUE);

			$akt_c = ($akt_dir == $p_standard_tpl) ? ' selected="selected"' : '';

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
			$adtemplates_tpl->blocks['stylerow']->parse_code(FALSE,TRUE);
		}
	}
	closedir($fp);
}

include_once('ad_pheader.php');

$adtemplates_tpl->parse_code(TRUE);

include_once('ad_ptail.php');

?>