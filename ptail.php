<?php
/**
*
* Tritanium Bulletin Board 2 - ptail.php
* Zeigt den allgemeinen HTML-Fuß des Forums an
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
*
**/

require_once('auth.php');

if($user_data['user_is_admin'] == 1) $admin_link = "<a href=\"administration.php?$MYSID\">Administration</a>";
else $admin_link = '';

$ptail_tpl = new template;
$ptail_tpl->load($template_path.'/'.$tpl_config['tpl_ptail']);

$mtime = explode(" ",microtime());
$STATS['end_time'] = $mtime[1] + $mtime[0];

$ptail_tpl->values = array(
	'VERSION'=>'Tritanium Bulletin Board 2.0 Alpha 3 (#09082003)',
	'COPYRIGHT'=>'&copy; 2003',
	'TRITANIUM_SCRIPTS'=>'Tritanium Scripts',
	'LNG_DB_QUERIES'=>$lng['db_queries'],
	'LNG_SITE_CREATION_TIME'=>$lng['site_creation_time'],
	'STATS_DB_QUERIES'=>$STATS['query_counter'],
	'STATS_SITE_CREATION_TIME'=>round($STATS['end_time']-$STATS['start_time'],5),
	'ADMINISTRATION_LINK'=>$admin_link
);

$ptail_tpl->parse_code(TRUE);

?>