<?php
/**
*
* Tritanium Bulletin Board 2 - ad_ptail.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$ad_ptail_tpl = new template;
$ad_ptail_tpl->load($template_path.'/'.$tpl_config['tpl_ad_ptail']);

$ad_ptail_tpl->parse_code(TRUE);

?>