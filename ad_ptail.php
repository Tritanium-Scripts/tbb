<?php
/**
*
* Tritanium Bulletin Board 2 - ad_ptail.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$ad_ptail_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_ptail']);

$ad_ptail_tpl->parse_code(TRUE);

?>