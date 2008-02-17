<?php
/**
*
* Tritanium Bulletin Board 2 - administration.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('startup.php');
require_once('auth.php');

define('IN_ADMINISTRATION',TRUE); // Legt fuer die pheader.php fest, dass man sich in der Administration befindet

//
// Einige Pfade bestimmen und Template-Konfiguration laden
//
$TEMPLATE_PATH = 'templates/'.$CONFIG['standard_tpl']; // Pfad zum Template
$TCONFIG = parse_ini_file($TEMPLATE_PATH.'/template_config.cfg',TRUE); // Templatekonfiguration laden
$TEMPLATE_STYLE = $TCONFIG['basic_info']['standard_style']; // Dateiname des Standardstyles des Templates
$STYLE_PATH = $TEMPLATE_PATH.'/styles/'.$TEMPLATE_STYLE; // Pfad zum Standardstyle des Templates


//
// Sprachverwaltung
//
if(!isset($_SESSION['language'])) {
	$_SESSION['language'] = $CONFIG['standard_language'];
	if($USER_LOGGED_IN == 1 && $USER_DATA['user_language'] != '' && $CONFIG['allow_select_lng'] == 1)
		$_SESSION['language'] = $USER_DATA['user_language'];
	elseif($CONFIG['use_language_detection'] == 1) {
		cache_get_languages();
		if(isset($LANGUAGE_IDS[$_SERVER['HTTP_ACCEPT_LANGUAGE']]) == TRUE && file_exists('languages/'.$LANGUAGE_IDS[$_SERVER['HTTP_ACCEPT_LANGUAGE']].'/language.cfg') == TRUE)
			$_SESSION['language'] = $LANGUAGE_IDS[$_SERVER['HTTP_ACCEPT_LANGUAGE']];
	}
}

if(file_exists('languages/'.$_SESSION['language'].'/language.cfg') == FALSE) {
	if(file_exists('languages/'.$CONFIG['standard_language'].'/language.cfg') == FALSE) die('Es liegt ein Problem mit der Boardsoftware vor. Bitte haben Sie einen Moment Geduld und versuchen Sie es spaeter nochmal.');
	$_SESSION['language'] = $CONFIG['standard_language'];
}

$LANGUAGE_PATH = 'languages/'.$_SESSION['language'];
include($LANGUAGE_PATH.'/lng_main.php');
include($LANGUAGE_PATH.'/lng_messages.php');
include($LANGUAGE_PATH.'/lng_admin.php');


//
// Ueberpruefen, ob der User eingeloggt ist und Administrator ist
//
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