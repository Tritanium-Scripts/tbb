<?php
/**
*
* Tritanium Bulletin Board 2 - ad_index.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$adindex_tpl = new template;
$adindex_tpl->load($template_path.'/'.$tpl_config['tpl_ad_index']);


include_once('ad_pheader.php');

$adindex_tpl->parse_code(TRUE);

include_once('ad_ptail.php');

?>