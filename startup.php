<?php
/**
*
* Tritanium Bulletin Board 2 - startup.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

//
// Einige allgemein wichtige Dinge
//
define('SECURITY',TRUE); // Dient dazu spaeter per "is_defined('SECURITY')" feststellen zu koennen, ob startup.php geladen wurde (siehe auth.php)

error_reporting(E_ALL); // Zeigt alle Fehler an
set_magic_quotes_runtime(0); // Beim Laden von Daten aus der Datenbank sollen keine Backslashes hinzugefuegt werden
ini_set('arg_separator.output','&amp;'); // Falls session.use_trans_sid ueber .htaccess nicht geaendert werden kann, wird die SID so wenigstens richtig angezeigt

if(file_exists('install.php')) die('Please delete install.php first!');


//
// Verschiedene (Statistik-)Werte
//
$STATS = array( // Ein paar allgemeine Informationen...
	'start_time'=>0, // ...Startzeit...
	'end_time'=>0, // ...Endzeit...
	'gzip_status'=>0, // ...Komprimierungsstatus...
	'new_pm'=>0 // ...ob der User eine neue PM erhalten hat
);

$CONFIG = array(); // Beinhaltet spaeter saemtliche Konfigurationsdaten des Forums


//
// Alle notwendigen Funktions-Bibliotheken und Konfiguratiosdateien laden
//
require_once('version.php'); // Beinhaltet die Version
require_once('functions.php'); // Beinhaltet viele nuetzliche Funktionen
require_once('functions_data.php'); // Beinhaltet noch mehr nuetzliche Funktionen, hauptsaechlich zum Laden von Daten aus der Datenbank
require_once('functions_sessions.php'); // Beinhaltet alle Funktionen zum datenbankgesteuerten Session-Management
require_once('functions_cats.php'); // Beinhaltet alle Funktionen zur Verwaltung der Kategorien (wegen Nested Set)
require_once('functions_files.php');
require_once('functions_cache.php');
require_once('dbconfig.php'); // Beinhaltet die Konfiguration der Datenbank
require_once('timezones.php'); // Beinhaltet die Zeitzonen


//
// Startzeit festlegen
//
$STATS['start_time'] = get_mtime_counter(); // Legt die Startzeit fest


//
// Datenbankverbindung erstellen
//
switch($CONFIG['db_type']) { // Waehlt den Datenbank Typ aus
	case 'mysql':
		include_once('db/mysql.class.php'); // MySQL
	break;
}

$db = new db; // Initialisiert der Datenbankklasse
if(!$db->connect($CONFIG['db_server'],$CONFIG['db_user'],$CONFIG['db_password'])) die('Error connecting to database server: '.$db->error()); // Falls die Verbindung zur Datenbank fehlschlaegt, Fehler ausgeben
if(!$db->select_db($CONFIG['db_name'])) die('Error selecting database: '.$db->error()); // Falls das auswaehlen der Datenbank fehlschlaegt, wiederum Fehler ausgeben


//
// Konfigwerte laden
//
$db->query("SELECT * FROM ".TBLPFX."config"); // Die Konfigurationsdaten laden...
while($akt_row = $db->fetch_array())
	$CONFIG[$akt_row['config_name']] = $akt_row['config_value']; // ...und sie im Array speichern


//
// GZIP-Komprimierung der Seite
//
if($CONFIG['enable_gzip'] == 1) { // Falls von der Boardkonfiguration her GZIP verwendet werden soll...
	if(ini_get('zlib.output_compression') != 1 && ini_get('output_handler') != 'ob_gzhandler') // ...und die Seite nicht schon von der PHP-Konfiguration her automatisch komprimiert wird...
		@ob_start('ob_gzhandler'); // ...die Seite komprimieren
	$STATS['gzip_status'] = 1; // Gibt spaeter an, dass die Seite komprimiert ist
}


//
// Veraltete Suchergebnisse loeschen
//
$rand_int = rand(1,100); // Zufallszahl zwischen 0 und 101 errechnen
if($CONFIG['srgc_probability'] >= $rand_int) // Falls die Wahrscheinlichkeit groesser oder gleich der Zufallszahl ist...
	$db->query("DELETE FROM ".TBLPFX."search_results WHERE search_last_access<".unixtstamp2sqltstamp(time()-$CONFIG['sr_timeout']*60)); // ...veraltete Suchergebnisse loeschen


//
// Session-Management
//
session_set_save_handler( // Session-Management auf Datenbank umstellen
	'session_data_handler_open',
	'session_data_handler_close',
	'session_data_handler_read',
	'session_data_handler_write',
	'session_data_handler_destroy',
	'session_data_handler_gc'
);
session_name('sid'); // Name der Session zu "sid" aendern
session_start(); // Session starten

if(strlen(session_id()) != 32) { // Falls eine ungueltige Session-ID existiert...
	session_destroy(); // ...die Session zerstoeren...
	header("Location: index.php"); exit; // ...und zur Forenuebersicht weiterleiten
}

$MYSID = (SID == '') ? 'sid=0' : 'sid='.session_id(); // Falls die Session-ID per Cookie uebergeben wird ist SID leer, man braucht also auch keine Session-ID per URL zu uebergeben


//
// Sonstige Dinge
//
$title_add = array($CONFIG['board_name']); // Beinhaltet spaeter alle Werte fuer den <title>-Bereich

if(get_magic_quotes_gpc() == 0) { // Falls Werte von "aussen" nicht automatisch mit \ escaped werden...
	array_addslashes($_POST); // ...dies mit den $_POST-Werten tun...
	array_addslashes($_GET); // ...und dies mit den $_GET-Werten tun...
	array_addslashes($_REQUEST); // ...und den $_REQUEST-Werten tun
}

$NAVBAR_ITEMS = array();
$HEADER_TITLE = '';

?>