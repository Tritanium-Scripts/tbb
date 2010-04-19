<?php
/**
*
* Tritanium Bulletin Board 2 - pop_ptail.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$tbb_version = SCRIPTVERSION;

$pop_ptail_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['pop_ptail']);

$pop_ptail_tpl->parse_code(TRUE);

?>