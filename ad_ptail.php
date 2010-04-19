<?php
/**
*
* Tritanium Bulletin Board 2 - ad_ptail.php
* Zeigt den HTML-Fuß der Administration an
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
*
**/

require_once('auth.php');

$ad_ptail_tpl = new template;
$ad_ptail_tpl->load($template_path.'/'.$tpl_config['tpl_ad_ptail']);

$ad_ptail_tpl->parse_code(TRUE);

?>