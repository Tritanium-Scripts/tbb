<?php
/**
*
* Tritanium Bulletin Board 2 - viewwio.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$title_add[] = $lng['Who_is_online'];

if($CONFIG['enable_wio'] != 1) {
	include_once('pheader.php');
	show_navbar("<a href=\"index.php?$MYSID\">".$lng['Forumindex']."</a>\r".$lng['Who_is_online']);
	show_message('Function_deactivated','message_function_deactivated');
	include_once('ptail.php'); exit;
}

$viewwio_tpl = new template;
$viewwio_tpl->load($template_path.'/'.$tpl_config['tpl_viewwio']);

$db->query("SELECT t1.session_user_id,t1.session_last_location,t1.session_is_ghost,t2.user_nick AS session_user_nick FROM ".TBLPFX."sessions AS t1 LEFT JOIN ".TBLPFX."users AS t2 ON t1.session_user_id=t2.user_id WHERE t1.session_last_update>".unixtstamp2sqltstamp(time()-$CONFIG['wio_timeout']*60));
if($db->affected_rows > 0) {
	while($akt_session = $db->fetch_array()) {
		if($akt_session['session_is_ghost'] != 1) {
			if($akt_session['session_user_id'] == 0) $akt_session['session_user_nick'] = $lng['Guest'];

			$akt_session_location = isset($lng['wio_'.$akt_session['session_last_location']]) ? $lng['wio_'.$akt_session['session_last_location']] : $lng['wio_forumindex'];

			$viewwio_tpl->blocks['wiorow']->parse_code(FALSE,TRUE);
			$tpl_config['akt_class'] = ($tpl_config['akt_class'] == $tpl_config['td1_class']) ? $tpl_config['td2_class'] : $tpl_config['td1_class'];
		}
	}
}
else $viewwio_tpl->unset_block('wiorow');

include_once('pheader.php');

show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Who_is_online']);

$viewwio_tpl->parse_code(TRUE);

include_once('ptail.php');

?>