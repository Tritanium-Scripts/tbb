<?php
/**
*
* Tritanium Bulletin Board 2 - pop_pheader.php
* version #2004-01-01-18-38-43
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$title_add = implode(' &#187; ',$title_add);

$pop_pheader_tpl = new template;
$pop_pheader_tpl->load($template_path.'/'.$tpl_config['tpl_pop_pheader']);

$pop_pheader_tpl->parse_code(TRUE);

?>