<?php
/**
*
* Tritanium Bulletin Board 2 - viewwio.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

if($CONFIG['enable_wio'] != 1) {
	add_navbar_items(array($LNG['Function_deactivated'],''));

	include_once('pheader.php');
		show_message($LNG['Function_deactivated'],$LNG['message_function_deactivated']);
	include_once('ptail.php'); exit;
}

$viewwio_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewwio']);

$akt_cell_class = $TCONFIG['cell_classes']['start_class'];
$DB->query("SELECT t1.session_user_id,t1.session_last_location,t1.session_is_ghost,t2.user_nick AS session_user_nick FROM ".TBLPFX."sessions AS t1 LEFT JOIN ".TBLPFX."users AS t2 ON t1.session_user_id=t2.user_id WHERE t1.session_last_update>".unixtstamp2sqltstamp(time()-$CONFIG['wio_timeout']*60));
if($DB->affected_rows > 0) {
	while($akt_session = $DB->fetch_array()) {
		if($akt_session['session_is_ghost'] != 1) {
			if($akt_session['session_user_id'] == 0) $akt_session['session_user_nick'] = $LNG['Guest'];

			$akt_session_location = isset($LNG['wio_'.$akt_session['session_last_location']]) ? $LNG['wio_'.$akt_session['session_last_location']] : $LNG['wio_forumindex'];

			$viewwio_tpl->blocks['wiorow']->parse_code(FALSE,TRUE);
			$akt_cell_class = ($akt_cell_class == $TCONFIG['cell_classes']['td1_class']) ? $TCONFIG['cell_classes']['td2_class'] : $TCONFIG['cell_classes']['td1_class'];
		}
	}
}

add_navbar_items(array($LNG['Who_is_online'],''));

include_once('pheader.php');
$viewwio_tpl->parse_code(TRUE);
include_once('ptail.php');

?>