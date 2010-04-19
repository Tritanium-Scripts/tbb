<?php
/**
*
* Tritanium Bulletin Board 2 - ptail.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

if($user_data['user_is_admin'] == 1) $admin_link = "<a href=\"administration.php?$MYSID\">".$lng["Administration"].'</a>';
else $admin_link = '';

$gzip_status = ($STATS['gzip_status'] == 1) ? $lng['enabled'] : $lng['disabled'];

$ptail_tpl = new template;
$ptail_tpl->load($template_path.'/'.$tpl_config['tpl_ptail']);

$STATS['end_time'] = get_mtime_counter();
$STATS['site_creation_time'] = round($STATS['end_time']-$STATS['start_time'],5);

if($CONFIG['show_techstats'] == 1) $ptail_tpl->blocks['techstats']->parse_code();
else $ptail_tpl->unset_block('techstats');

$ptail_tpl->parse_code(TRUE);

?>