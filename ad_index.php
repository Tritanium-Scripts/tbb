<?php
/**
*
* Tritanium Bulletin Board 2 - ad_index.php
* Zeigt eine Übersicht zur Administration an
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
*
**/

require_once('auth.php');

$adindex_tpl = new template;
$adindex_tpl->load($template_path.'/'.$tpl_config['tpl_ad_index']);

$adindex_tpl->values = array(
	'LNG_OVERVIEW'=>$lng['Overview'],
	'LNG_AD_WELCOME_TEXT'=>$lng['ad_welcome_text'],
	'TEMPLATE_PATH'=>$template_path
);

include_once('ad_pheader.php');

$adindex_tpl->parse_code(TRUE);

include_once('ad_ptail.php');

?>