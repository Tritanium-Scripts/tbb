<?php
/**
*
* Tritanium Bulletin Board 2 - administration.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('startup.php');
require_once('auth.php');

$TEMPLATE_PATH = 'templates/'.$CONFIG['standard_tpl'];
$TCONFIG = parse_ini_file($TEMPLATE_PATH.'/template_config.cfg',TRUE);
$TEMPLATE_STYLE = $TCONFIG['basic_info']['standard_style'];
$STYLE_PATH = $TEMPLATE_PATH.'/styles/'.$TEMPLATE_STYLE;

require_once($LANGUAGE_PATH.'/lng_admin.php');

if($USER_LOGGED_IN != 1 || $USER_DATA['user_is_admin'] != 1) die('Sie sind kein Administrator');

$faction = (isset($_GET['faction']) ? $_GET['faction'] : 'ad_index');

if($CONFIG['enable_wio'] == 1)
	$DB->query("UPDATE ".TBLPFX."sessions SET session_last_location='$faction' WHERE session_id='".session_id()."'");

add_navbar_items(array($CONFIG['board_name'],"index.php?$MYSID"),array($LNG['Administration'],"administration.php?$MYSID"));

switch($faction) {
	case 'ad_avatars':
		include('ad_avatars.php');
	break;

	case 'ad_config':
		include('ad_config.php');
	break;

	case 'ad_forums';
		include('ad_forums.php');
	break;

	case 'ad_groups':
		include('ad_groups.php');
	break;

	case 'ad_index':
		include('ad_index.php');
	break;

	case 'ad_profile':
		include('ad_profile.php');
	break;

	case 'ad_ranks':
		include('ad_ranks.php');
	break;

	case 'ad_smilies':
		include('ad_smilies.php');
	break;

	case 'ad_templates':
		include('ad_templates.php');
	break;

	case 'ad_users':
		include('ad_users.php');
	break;
}

?>