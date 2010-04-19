<?php
/**
*
* Tritanium Bulletin Board 2 - viewwio.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

if($CONFIG['enable_wio'] != 1) {
	add_navbar_items(array($lng['Function_deactivated'],''));

	include_once('pheader.php');
	show_navbar();
	show_message($lng['Function_deactivated'],$lng['message_function_deactivated']);
	include_once('ptail.php'); exit;
}

$viewwio_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewwio']);

$akt_cell_class = $TCONFIG['cell_classes']['start_class'];
$db->query("SELECT t1.session_user_id,t1.session_last_location,t1.session_is_ghost,t2.user_nick AS session_user_nick FROM ".TBLPFX."sessions AS t1 LEFT JOIN ".TBLPFX."users AS t2 ON t1.session_user_id=t2.user_id WHERE t1.session_last_update>".unixtstamp2sqltstamp(time()-$CONFIG['wio_timeout']*60));
if($db->affected_rows > 0) {
	while($akt_session = $db->fetch_array()) {
		if($akt_session['session_is_ghost'] != 1) {
			if($akt_session['session_user_id'] == 0) $akt_session['session_user_nick'] = $lng['Guest'];

			$akt_session_location = isset($lng['wio_'.$akt_session['session_last_location']]) ? $lng['wio_'.$akt_session['session_last_location']] : $lng['wio_forumindex'];

			$viewwio_tpl->blocks['wiorow']->parse_code(FALSE,TRUE);
			$akt_cell_class = ($akt_cell_class == $TCONFIG['cell_classes']['td1_class']) ? $TCONFIG['cell_classes']['td2_class'] : $TCONFIG['cell_classes']['td1_class'];
		}
	}
}

add_navbar_items(array($lng['Who_is_online'],''));

include_once('pheader.php');
show_navbar();
$viewwio_tpl->parse_code(TRUE);
include_once('ptail.php');

?>