<?php
/**
*
* Tritanium Bulletin Board 2 - ptail.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$tbb_version = SCRIPTVERSION; // Wird im Template benoetigt, da das Template noch keine Konstanten unterst&uuml;zt

$ptail_tpl = new template(); // Template-Objekt erstellen


//
// Falls sich der User im "Profil bearbeiten"-Bereich befindet
//
if(defined('IN_EDITPROFILE')) {
	$ptail_tpl->load($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editprofile_tail']);
	$ptail_tpl->parse_code(TRUE);
}
$ptail_tpl->parse_code(TRUE);


//
// Falls sich der User in der Administration befindet
//
if(defined('IN_ADMINISTRATION')) { // Der User befindet sich in der Administration
	$ptail_tpl->load($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_ptail']);
}
else { // Der User befindet sich sich nicht in der Adminisration
	$ptail_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ptail']);

	$STATS['end_time'] = get_mtime_counter(); // Die jetzige Zeit in Mikrosekunden
	$STATS['site_creation_time'] = $STATS['end_time']-$STATS['start_time']; // Die Dauer der Seitenerstellung (Endzeit-Anfangszeit)
	
	if($CONFIG['show_techstats'] == 1) {
		$gzip_status = ($STATS['gzip_status'] == 1) ? $LNG['enabled'] : $LNG['disabled']; // Der Status der GZIP-Komprimierung (eingeschaltet oder ausgeschaltet)
		$techstats_text = sprintf($LNG['technical_stats_text'],$DB->query_counter,$gzip_status,round($STATS['site_creation_time'],4),round($STATS['site_creation_time']-$DB->query_time,4),round($DB->query_time,4));
		$ptail_tpl->blocks['techstats']->parse_code(); // Falls die technischen Statistiken angezeigt werden diesen Templateblock erstellen...
	}
	

	if($USER_DATA['user_is_admin'] == 1) $admin_link = "<a href=\"administration.php?$MYSID\">".$LNG['Administration'].'</a>'; // Falls User Admin ist kann der Link zur Administration erstellt werden...
	else $admin_link = ''; // ...ansonsten nicht
}
$ptail_tpl->parse_code(TRUE);

?>