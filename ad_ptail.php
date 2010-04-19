<?php
/**
*
* Tritanium Bulletin Board 2 - ad_ptail.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$ad_ptail_tpl = new template;
$ad_ptail_tpl->load($template_path.'/'.$tpl_config['tpl_ad_ptail']);

$ad_ptail_tpl->parse_code(TRUE);

?>