<?php
/**
*
* Tritanium Bulletin Board 2 - administration.php
* Ruft zentral die einzelnen Bereiche der Administration auf
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
*
**/

require_once('startup.php');
require_once('dbconfig.php');
require_once('functions.php');
require_once('db/'.$CONFIG['dbfunc_path'].'/functions.php');

get_config_data();

require_once('auth.php');


$template_path = 'templates/'.$CONFIG['standard_tpl'];
require_once('templates.class.php');
require_once($template_path.'/template_config.php');
$template_style = $tpl_config['standard_style'];

$language_path = 'language/'.$CONFIG['standard_language'];
require_once($language_path.'/lng_main.php');
require_once($language_path.'/lng_messages.php');
require_once($language_path.'/lng_admin.php');

if($user_logged_in != 1 || $user_data['user_is_admin'] != 1) die('Sie sind kein Administrator');

$faction = (isset($_GET['faction']) ? $_GET['faction'] : 'ad_index');

if($CONFIG['enable_wio'] == 1) {
	delete_old_wio_data();
	if($_SESSION['s_hide_from_wio'] != 1) {
		update_wio_data(session_id(),array(
			'wio_session_id'=>array('STR',session_id()),
			'wio_user_id'=>array('STR',$user_id),
			'wio_last_location'=>array('STR',$faction)
		));
	}
}

$title_add = '';

switch($faction) {
	case 'ad_index':
		include_once('ad_index.php');
	break;

	case 'ad_forums';
		include_once('ad_forums.php');
	break;

	case 'ad_config':
		include_once('ad_config.php');
	break;

	case 'ad_templates':
		include_once('ad_templates.php');
	break;
}

?>