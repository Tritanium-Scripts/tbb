<?php
/**
*
* Tritanium Bulletin Board 2 - language/ts_german/lng_install.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

$lng['language_info'] = 'Deutsch - Sie - by Tritanium Scripts';
$lng['db_access_data_info'] = 'Bitte geben Sie nun die Zugangsdaten zu Ihrer Datenbank an. Falls Sie nicht wissen, was ein(e) Datenbank(server) ist oder wie die Zugangsdaten dazu sind, kontaktieren Sie bitte Ihren Webspaceprovider oder besuchen Sie uns unter <a href="http://www.tritanium-scripts.com" target="_blank">http://www.tritanium-scripts.com</a>.';

$lng['Next_step'] = 'Zum n&auml;chsten Schritt';
$lng['Try_again'] = 'Erneut versuchen';

$lng['Database_server'] = 'Datenbankserver';
$lng['Database_password'] = 'Datenbankpasswort';
$lng['Database_name'] = 'Datenbankname';
$lng['Database_user'] = 'Datenbankuser';
$lng['Table_prefix'] = 'Tabellenprefix';
$lng['Creating_tables'] = 'Erstelle Tabellen...';
$lng['successful'] = 'erfolgreich';
$lng['Inserting_basic_data'] = 'F&uuml;ge Basisdaten ein...';
$lng['Creating_config_file'] = 'Erstelle Konfigurationsdatei &quot;dbconfig.php&quot;...';
$lng['Cannot_open_config_file'] = 'Kann Konfigurationsdatei nicht zum Schreiben &ouml;ffnen (bitte &uuml;berpr&uuml;fen Sie die Rechte des Verzeichnisses, in das Sie das Forum hochgeladen haben (sollten <u>tempor&auml;r</u> 777 sein))';
$lng['Cannot_write_config_file'] = 'Kann keine Daten in die Konfigurationsdatei schreiben (bitte &uuml;berpr&uuml;fen Sie die Rechte des Verzeichnisses, in das Sie das Forum hochgeladen haben (sollten <u>tempor&auml;r</u> 777 sein))';
$lng['Cannot_set_chmod'] = '<b>Bitte beachten Sie:</b> Die Rechte der Konfigurationsdatei &quot;dbconfig.php&quot; konnten nicht gesetzt werden (bitte setzen Sie die Rechte der Datei &quot;dbconfig.php&quot; z.B. per FTP manuell auf 755 bzw. unter Windows auf &quot;schreibgesch&uuml;tzt&quot;)';
$lng['installation_successful'] = 'Herzlichen Gl&uuml;ckwunsch! Die Installation wurde erfolgreich abgeschlossen, damit ist Ihr Tritanium Bulletin Board 2 nun einsatzbereit. Sie k&ouml;nnen das Forum &uuml;ber <a href="index.php">index.php</a> erreichen, allerdings m&uuml;ssen Sie erste diese Installationsdatei (&quot;install.php&quot;) l&ouml;schen. Hilfestelllungen zu ersten Schritten entnehmen Sie bitte der Dokumentation. Es wird <u>dringend empfohlen</u> die Rechte der Datei &quot;dbconfig.php&quot; auf 755 zu setzen.';
$lng['File_test'] = 'Datei&uuml;berpr&uuml;fung';
$lng['File_upload_test'] = 'Dateiupload&uuml;berpr&uuml;fung';
$lng['there_were_errors'] = 'Es gab Fehler w&auml;hrend der &Uuml;berpr&uuml;fung des Systems! Bitte beheben Sie die M&auml;ngel und versuchen es erneut!';

$lng['warning_file_upload_disabled'] = 'WARNUNG: Der Dateiupload wurde deaktiviert! Falls Sie oder Ihr Provider diesen nicht aktivieren k&ouml;nnen/wollen, deaktivieren Sie bitte sp&auml;ter die Optionen &quot;Avatarupload aktivieren&quot; und &quot;Dateiupload aktivieren&quot;!';

$lng['error_no_database_server'] = 'Bitte geben Sie einen Datenbankserver an!';
$lng['error_no_database_user'] = 'Bitte geben Sie einen Datenbankuser an!';
$lng['error_no_database_name'] = 'Bitte geben Sie einen Datenbankname an!';
$lng['error_invalid_unknown_database_name'] = 'Ung&uuml;tiger/unbekannter Datenbankname: %s';
$lng['error_connecting_database_server'] = 'Fehler beim Verbinden mit dem Datenbankserver: %s';
$lng['error_invalid_table_prefix'] = 'Ung&uuml;ltiger Tabellenprefix!';
$lng['error_creating_tables'] = 'Fehler beim Erstellen der Tabellen!';
$lng['error_inserting_basic_data'] = 'Fehler beim Einf&uuml;gen der Basisdaten';
$lng['error_cannot_write_config_file'] = 'FEHLER: Es kann in die Konfigurationsdatei &quot;dbconfig.php&quot; nicht geschrieben werden! Bitte setzen Sie die Rechte der Datei <u>f&uuml;r die Dauer der Installation</u> auf 777!';

?>