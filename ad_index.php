<?php
/**
*
* Tritanium Bulletin Board 2 - ad_index.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$adindex_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_index']);

include_once('ad_pheader.php');
$adindex_tpl->parse_code(TRUE);
include_once('ad_ptail.php');

?>