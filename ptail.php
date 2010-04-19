<?php
/**
*
* Tritanium Bulletin Board 2 - ptail.php
* version #2004-01-01-18-38-43
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$tbb_version = TBBVERSION; // Wird im Template benoetigt, da das Template noch keine Konstanten unterst&uuml;zt

if($user_data['user_is_admin'] == 1) $admin_link = "<a href=\"administration.php?$MYSID\">".$lng["Administration"].'</a>'; // Falls User Admin ist kann der Link zur Administration erstellt werden...
else $admin_link = ''; // ...ansonsten nicht

$gzip_status = ($STATS['gzip_status'] == 1) ? $lng['enabled'] : $lng['disabled']; // Der Status der GZIP-Komprimierung (eingeschaltet oder ausgeschaltet)

$ptail_tpl = new template; // Template-Objekt erstellen
$ptail_tpl->load($template_path.'/'.$tpl_config['tpl_ptail']); // Template laden

$STATS['end_time'] = get_mtime_counter(); // Die jetzige Zeit in Mikrosekunden
$STATS['site_creation_time'] = round($STATS['end_time']-$STATS['start_time'],5); // Die Dauer der Seitenerstellung (Endzeit-Anfangszeit)

if($CONFIG['show_techstats'] == 1) $ptail_tpl->blocks['techstats']->parse_code(); // Falls die technischen Statistiken angezeigt werden diesen Templateblock erstellen...
else $ptail_tpl->unset_block('techstats'); // ...ansonsten nicht

$ptail_tpl->parse_code(TRUE); // Das Template (Seitenende) ausgeben

?>