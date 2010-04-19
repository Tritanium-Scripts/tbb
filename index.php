<?php
/**
*
* Tritanium Bulletin Board 2 - index.php
* version #2004-01-01-18-38-43
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('startup.php');
require_once('auth.php');

$template_path = 'templates/'.$CONFIG['standard_tpl'];
require_once('templates.class.php');
require_once($template_path.'/template_config.php');
$template_style = $tpl_config['standard_style'];
$style_path = $template_path.'/styles/'.$template_style;

$language_path = 'language/'.$CONFIG['standard_language'];
require_once($language_path.'/lng_main.php');
require_once($language_path.'/lng_messages.php');

isset($_GET['faction']) ? $faction = $_GET['faction'] : $faction = 'forumindex';

if($CONFIG['enable_wio'] == 1)
	$db->query("UPDATE ".TBLPFX."wio SET wio_last_location='$faction', wio_last_action='".time()."', wio_user_id='$user_id', wio_is_ghost='".$_SESSION['s_hide_from_wio']."' WHERE wio_session_id='".session_id()."'");

if(isset($_SERVER['QUERY_STRING']) && $faction != 'login') {
	$_SESSION['last_place_url'] = ($_SERVER['QUERY_STRING'] == '') ? "index.php?$MYSID" : 'index.php?'.str_replace('&doit=1','',$_SERVER['QUERY_STRING']);
}

if($user_logged_in != 1 && $CONFIG['guests_enter_board'] != 1 && $faction != 'register' && $faction != 'login') {
	include_once('pheader.php');
	show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Not_logged_in']);
	show_message('Not_logged_in','message_enter_board_not_logged_in','<br />'.$lng['click_here_register'].'<br />'.$lng['click_here_login']);
	include_once('ptail.php'); exit;
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

	case 'search':
		include('search.php');
	break;

	case 'viewhelp':
		include('viewhelp.php');
	break;

	case 'viewsmilies':
		include('viewsmilies.php');
	break;

	case 'vote':
		include('vote.php');
	break;

	case 'pms':
		include('pms.php');
	break;
}

?>