<?php
/**
*
* Tritanium Bulletin Board 2 - pop_pheader.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$title_add = implode(' &#187; ',$title_add);

$pop_pheader_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['pop_pheader']);

$pop_pheader_tpl->parse_code(TRUE);

?>