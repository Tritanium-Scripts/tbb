<?php
/**
*
* Tritanium Bulletin Board 2 - ad_ptail.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$ad_ptail_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_ptail']);

$ad_ptail_tpl->parse_code(TRUE);

?>