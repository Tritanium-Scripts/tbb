<?php
/**
*
* Tritanium Bulletin Board 2 - index.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('startup.php');
require_once('auth.php');


//
// Template- und Styleverwaltung
//
$TEMPLATE_PATH = 'templates/'.$CONFIG['standard_tpl'];
$TCONFIG = parse_ini_file($TEMPLATE_PATH.'/template_config.cfg',TRUE);
$TEMPLATE_STYLE = $TCONFIG['basic_info']['standard_style'];
$STYLE_PATH = $TEMPLATE_PATH.'/styles/'.$TEMPLATE_STYLE;


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



//
// Navigationsleiste
//
add_navbar_items(array($CONFIG['board_name'],"index.php?$MYSID"));


//
// Die momentane Aktion bestimmen
//
isset($_GET['faction']) ? $faction = $_GET['faction'] : $faction = 'forumindex'; // Falls keine Aktion ausgewaehlt wurde, die Forenuebersicht waehlen
if(isset($_GET['p'])) {
	$faction = 'viewtopic';
	$_GET['post_id'] = intval($_GET['p']);
}
elseif(isset($_GET['t'])) {
	$faction = 'viewtopic';
	$_GET['topic_id'] = intval($_GET['t']);
}
elseif(isset($_GET['f'])) {
	$faction = 'viewforum';
	$_GET['forum_id'] = intval($_GET['f']);
}
elseif(isset($_GET['c'])) {
	$faction = 'viewcat';
	$_GET['cat_id'] = intval($_GET['c']);
}
elseif(isset($_GET['u'])) {
	$faction = 'viewprofile';
	$_GET['profile_id'] = intval($_GET['u']);
}


//
// "Wer ist online?" updaten
//
if($CONFIG['enable_wio'] == 1) // Falls "Wer ist online?" aktiviert ist...
	$DB->query("UPDATE ".TBLPFX."sessions SET session_last_location='$faction' WHERE session_id='".session_id()."'"); // ... den aktuellen Ort updaten


//
// Link fuer die Weiterleitung nach dem Einloggen erstelllen
//
if(isset($_SERVER['QUERY_STRING']) && $faction != 'login') { // Falls der Server den vom Browser uebergebenen Query-String anzeigen kann und man sich nicht beim Einloggen befindet...
	$_SESSION['last_place_url'] = ($_SERVER['QUERY_STRING'] == '') ? "index.php?$MYSID" : 'index.php?'.str_replace('&doit=1','',$_SERVER['QUERY_STRING']); // ...standardmaessig zur Forenuebersicht weiterleiten oder eben das, was als letztes der Aufenthaltsort war
}


//
// Ueberpruefung, ob User das Board ueberhaupt betreten darf
//
if($USER_LOGGED_IN != 1 && $CONFIG['guests_enter_board'] != 1 && $faction != 'register' && $faction != 'login') { // Falls der User nicht eingeloggt ist, das Forum aber nicht als Gast betreten darf und der User sich nicht gerade einloggt oder registriert...
	add_navbar_items(array($LNG['Not_logged_in'],''));

	include_once('pheader.php');
		show_message($LNG['Not_logged_in'],$LNG['message_enter_board_not_logged_in'].'<br />'.$LNG['click_here_register'].'<br />'.$LNG['click_here_login']); // ...Meldung ausgeben, dass der User sich registrieren soll
	include_once('ptail.php'); exit;
}


//
// Die entsprechende Aktion auswaehlen
//
switch($faction) {
	case 'activateaccount':
		include('activateaccount.php');
	break;

	case 'forumindex':
		include('forumindex.php');
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

	case 'register':
		include('register.php');
	break;

	case 'requestpassword':
		include('requestpassword.php');
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

	case 'memberlist':
		include('memberlist.php');
	break;

	case 'viewcat':
		include('viewcat.php');
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

	case 'subscribetopic':
		include('subscribetopic.php');
	break;
}

?>