<?php
/**
*
* Tritanium Bulletin Board 2 - ad_index.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$adindex_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_index']);

include_once('pheader.php');
$adindex_tpl->parse_code(TRUE);
include_once('ptail.php');

?>