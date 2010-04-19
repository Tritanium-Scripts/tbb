<?php
/**
*
* Tritanium Bulletin Board 2 - pop_ptail.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$pop_ptail_tpl = new template;
$pop_ptail_tpl->load($template_path.'/'.$tpl_config['tpl_pop_ptail']);

$pop_ptail_tpl->parse_code(TRUE);

?>