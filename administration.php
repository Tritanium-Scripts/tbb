<?php
/**
*
* Tritanium Bulletin Board 2 - administration.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
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

if($USER_LOGGED_IN != 1 || $USER_DATA['user_is_admin'] != 1) die('Sie sind kein Administrator');

$faction = (isset($_GET['faction']) ? $_GET['faction'] : 'ad_index');

if($CONFIG['enable_wio'] == 1)
	$db->query("UPDATE ".TBLPFX."sessions SET session_last_location='$faction' WHERE session_id='".session_id()."'");

$title_add = '';

switch($faction) {
	case 'ad_index':
		include('ad_index.php');
	break;

	case 'ad_forums';
		include('ad_forums.php');
	break;

	case 'ad_config':
		include('ad_config.php');
	break;

	case 'ad_smilies':
		include('ad_smilies.php');
	break;

	case 'ad_templates':
		include('ad_templates.php');
	break;

	case 'ad_groups':
		include('ad_groups.php');
	break;

	case 'ad_ranks':
		include('ad_ranks.php');
	break;

	case 'ad_avatars':
		include('ad_avatars.php');
	break;
}

?>