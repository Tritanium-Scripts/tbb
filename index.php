<?php
/**
*
* Tritanium Bulletin Board 2 - index.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('startup.php');
require_once('auth.php');


//
// Template- und Styleverwaltung
//
$template_path = 'templates/'.$CONFIG['standard_tpl'];
require_once('templates.class.php');
require_once($template_path.'/template_config.php');
$template_style = $tpl_config['standard_style'];
$style_path = $template_path.'/styles/'.$template_style;


//
// Sprachverwaltung
//
$language_path = 'language/'.$CONFIG['standard_language'];
require_once($language_path.'/lng_main.php');
require_once($language_path.'/lng_messages.php');


isset($_GET['faction']) ? $faction = $_GET['faction'] : $faction = 'forumindex'; // Falls keine Aktion ausgewaehlt wurde, die Forenuebersicht waehlen


//
// "Wer ist online?" updaten
//
if($CONFIG['enable_wio'] == 1) // Falls "Wer ist online?" aktiviert ist...
	$db->query("UPDATE ".TBLPFX."sessions SET session_last_location='$faction' WHERE session_id='".session_id()."'"); // ... den aktuellen Ort updaten


//
// Link fuer die Weiterleitung nach dem Einloggen erstelllen
//
if(isset($_SERVER['QUERY_STRING']) && $faction != 'login') { // Falls der Server den vom Browser uebergebenen Query-String anzeigen kann und man sich nicht beim Einloggen befindet...
	$_SESSION['last_place_url'] = ($_SERVER['QUERY_STRING'] == '') ? "index.php?$MYSID" : 'index.php?'.str_replace('&doit=1','',$_SERVER['QUERY_STRING']); // ...standardmaessig zur Forenuebersicht weiterleiten oder eben das, was als letztes der Aufenthaltsort war
}


//
// Ueberpruefung, ob User das Board ueberhaupt betreten darf
//
if($USER_LOGGED_IN != 1 && $CONFIG['guests_enter_board'] != 1 && $faction != 'register' && $faction != 'login') { // Falls der User nicht eingeloggt ist, das Forum aber nicht als Gast betreten darf und der User sich nicht gerade einloggt oder registrert...
	include_once('pheader.php');
	show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Not_logged_in']);
	show_message('Not_logged_in','message_enter_board_not_logged_in','<br />'.$lng['click_here_register'].'<br />'.$lng['click_here_login']); // ...Meldung ausgeben, dass der User sich registrieren soll
	include_once('ptail.php'); exit;
}


//
// Die entsprechende Aktion auswaehlen
//
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

	case 'activateaccount':
		include('activateaccount.php');
	break;

	case 'subscribetopic':
		include('subscribetopic.php');
	break;
}

?>