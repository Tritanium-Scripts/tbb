<?php
/**
*
* Tritanium Bulletin Board 2 - administration.php
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
require_once($language_path.'/lng_admin.php');

if($user_logged_in != 1 || $user_data['user_is_admin'] != 1) die('Sie sind kein Administrator');

$faction = (isset($_GET['faction']) ? $_GET['faction'] : 'ad_index');

if($CONFIG['enable_wio'] == 1)
	$db->query("UPDATE ".TBLPFX."wio SET wio_last_location='$faction', wio_last_action='".time()."', wio_user_id='$user_id', wio_is_ghost='".$_SESSION['s_hide_from_wio']."' WHERE wio_session_id='".session_id()."'");

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

	case 'ad_smilies':
		include_once('ad_smilies.php');
	break;

	case 'ad_templates':
		include_once('ad_templates.php');
	break;

	case 'ad_groups':
		include_once('ad_groups.php');
	break;
}

?>