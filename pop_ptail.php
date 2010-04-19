<?php
/**
*
* Tritanium Bulletin Board 2 - pop_ptail.php
* version #2004-01-01-18-38-43
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$tbb_version = TBBVERSION;

$pop_ptail_tpl = new template;
$pop_ptail_tpl->load($template_path.'/'.$tpl_config['tpl_pop_ptail']);

$pop_ptail_tpl->parse_code(TRUE);

?>