<?php
/**
*
* Tritanium Bulletin Board 2 - index.php
* Ruft zentral, abgesehen von der Administration, die verschiedenen Bereiche des Forums auf
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
*
**/

error_reporting(E_ALL);

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

isset($_GET['faction']) ? $faction = $_GET['faction'] : $faction = 'forumindex';

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

if(isset($_SERVER['QUERY_STRING']) && $faction != 'login') {
	$_SESSION['last_place_url'] = ($_SERVER['QUERY_STRING'] == '') ? "index.php?$MYSID" : 'index.php?'.$_SERVER['QUERY_STRING'];
}

switch($faction) {
	case 'forumindex':
		include('forumindex.php');
	break;

	case 'register':
		include('register.php');
	break;

	case 'login':
		include('login.php');
	break;

	case 'logout':
		include('logout.php');
	break;

	case 'viewtopic':
		include('viewtopic.php');
	break;

	case 'viewforum':
		include('viewforum.php');
	break;

	case 'posttopic':
		include('posttopic.php');
	break;

	case 'postreply':
		include('postreply.php');
	break;

	case 'edittopic':
		include('edittopic.php');
	break;

	case 'editprofile':
		include('editprofile.php');
	break;

	case 'viewwio':
		include('viewwio.php');
	break;

	case 'viewprofile':
		include('viewprofile.php');
	break;

	case 'editpost':
		include('editpost.php');
	break;
}

?>