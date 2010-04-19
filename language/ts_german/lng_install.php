<?php
/**
*
* Tritanium Bulletin Board 2 - language/ts_german/lng_install.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

//
// Standard things...
//
$LNG['Tbb2_installation'] = 'Tritanium Bulletin Board 2 - Installation';

$LNG['language_info'] = 'Deutsch - Sie - by Tritanium Scripts';
$LNG['db_access_data_info'] = 'Tritanium Bulletin Board 2 verwendet zum Speichern der Daten eine sogenannte <a href="http://www.mysql.de" target="_blank">MySQL-Datenbank</a>, die von Ihrem Webspace unterst&uuml;zt werden muss. Falls Sie nicht wissen, ob Sie &uuml;ber eine solche MySQL-Datenbank verf&uuml;gen oder Ihre Zugangsdaten dazu nicht kennen, nehmen Sie bitte Kontakt mit Ihrem Webspaceprovider auf.';

$LNG['Next'] = 'Weiter &#187;';
$LNG['Back'] = '&#171; Zur&uuml;ck';


//
// Language selection
//
$LNG['Select_language'] = 'Bitte w&auml;hlen Sie Ihre Sprache<br />Please select your language';



//
// Overview
//
$LNG['Language_selection'] = 'Sprachauswahl';
$LNG['Introduction'] = 'Einleitung';
$LNG['System_test'] = 'System&uuml;berpr&uuml;fung';
$LNG['Database_configuration'] = 'Datenbankeinrichtung';
$LNG['Search_for_existing_installation'] = 'Suche nach vorhandener Installation';
$LNG['Base_data_insertion'] = 'Einf&uuml;gen der Basisdaten';
$LNG['Board_configuration'] = 'Konfiguration des Boards';
$LNG['Administrator_creation'] = 'Erstellen des Administrators';
$LNG['Installation_finish'] = 'Installationsabschluss';


//
// Introduction
//
$LNG['introduction_text'] = 'Willkommen bei der Einrichtung des Tritanium Bulletin Board 2! Dieser Installationsassistent wird Ihnen helfen die Software korrekt einzurichten und eventuelle Fehlerquellen von Anfang an auszuschließen. Im ersten Schritt wird dazu die Konfiguration Ihres Webservers &uuml;berpr&uuml;ft und gegebenenfalls angezeigt, was Sie &auml;ndern m&uuml;ssen oder sollten. Um fortzufahren klicken Sie bitte auf &quot;Weiter&quot;, oder, falls Sie eine andere verf&uuml;gbare Sprache ausw&auml;hlen wollen, auf &quot;Zur&uuml;ck&quot;.';


//
// System test
//
$LNG['Test_again'] = 'Erneut &uuml;berpr&uuml;fen';
$LNG['File_test'] = 'Datei&uuml;berpr&uuml;fung';
$LNG['File_upload_test'] = 'Dateiupload&uuml;berpr&uuml;fung';
$LNG['Directory_test'] = 'Verzeichnis&uuml;berpr&uuml;fung';
$LNG['Php_test'] = 'PHP-&Uuml;berpr&uuml;fung';
$LNG['there_were_errors'] = '<b>Achtung:</b> W&auml;hrend der System&uuml;berpr&uuml;fung wurden Probleme festgestellt. Es wird empfohlen diese Probleme zu beseitigen, da sonst ein korrektes Arbeiten der Software nicht sichergestellt werden kann. Um den Test zu wiederholen klicken Sie bitte auf &quot;Erneut &uuml;berpr&uuml;fen&quot;, um mit der Installation fortzufahren auf &quot;Weiter&quot; und um die Einleitung nochmals durchzulesen auf &quot;Zur&uuml;ck&quot;.';

$LNG['warning_old_php_version'] = 'WARNUNG: Ihre PHP-Version (%s) ist &auml;lter als empfohlen (%s). Es wird dringend geraten auf die neueste verf&uuml;gbare zu updaten, da sonst Fehler w&auml;hrend des Betriebs der Software auftreten k&ouml;nnen!';
$LNG['warning_file_upload_disabled'] = 'WARNUNG: Der Dateiupload wurde deaktiviert! Falls Sie oder Ihr Provider diesen nicht aktivieren k&ouml;nnen/wollen, deaktivieren Sie bitte sp&auml;ter die Optionen &quot;Avatarupload aktivieren&quot; und &quot;Dateiupload aktivieren&quot;!';
$LNG['warning_file_upload_dir_not_writable'] = 'WARNUNG: In dem Verzeichnis &quot;upload/files&quot; kann nicht geschrieben werden! Bitte setzen Sie die Rechte des Verzeichnisses auf &quot;777&quot; oder deaktivieren Sie die Option &quot;Dateiupload aktivieren&quot;!';
$LNG['warning_avatar_upload_dir_not_writable'] = 'WARNUNG: In dem Verzeichnis &quot;upload/avatars&quot; kann nicht geschrieben werden! Bitte setzen Sie die Rechte des Verzeichnisses auf &quot;777&quot; oder deaktivieren Sie die Option &quot;Avatarupload aktivieren&quot;!';
$LNG['warning_cache_dir_not_writable'] = 'WARNUNG: In dem Verzeichnis &quot;cache&quot; kann nicht geschrieben werden! Bitte setzen Sie die Rechte des Verzeichnisses auf &quot;777&quot; um Datenbankabfragen zu sparen.';



//
// Database setup
//
$LNG['Database_server'] = 'Datenbankserver';
$LNG['Database_password'] = 'Datenbankpasswort';
$LNG['Database_name'] = 'Datenbankname';
$LNG['Database_user'] = 'Datenbankuser';
$LNG['Table_prefix'] = 'Tabellenprefix';
$LNG['search_for_installation_preinfo'] = 'Falls die &Uuml;berpr&uuml;fung der Zugangsdaten erfolgreich ist, wird im folgenden Schritt unter dem angegebenen Tabellenpr&auml;fix nach einer vorhandenen Installation gesucht. Falls dabei etwas gefunden wird, k&ouml;nnen Sie entscheiden, ob Sie die Daten &uuml;bernehmen wollen oder ob Sie sie l&ouml;schen wollen.';


//
// Search for existing installation
//
$LNG['existing_installation_not_found'] = 'Es wurde keine vorhandene Installation gefunden. Bitte klicken sie auf &quot;weiter&quot; um mit der Installation forzufahren und mit dem Einf&uuml;gen der Basisdaten zu beginnen.';
$LNG['existing_installation_found'] = 'In der gew&auml;hlten Datenbank wurde unter dem gew&auml;hlten Tabellenpr&auml;fix eine schon existierende Installation des Tritanium Bulletin Board 2 gefunden.<br /><br />%s<br /><br />Wie soll mit den vorhandenen Daten verfahren werden?';
$LNG['existing_installation_unknown'] = 'Es konnte nicht festgestellt werden, um welche Version des Tritanium Bulletin Board 2 es sich dabei handelt. Wahrscheinlich sind die Daten beschädigt oder es handelt sich gar nicht um eine Installation des Tritanium Bulletin Board 2. Sie k&ouml;nnen nun w&auml;hlen, ob sie eine andere Datenbankkonfiguration w&auml;hlen wollen (empfohlen) oder ob sie die vorhandenen Daten l&ouml;schen wollen.';
$LNG['existing_installation_good'] = 'Sie k&ouml;nnen nun w&auml;hlen, ob Sie die Daten &uuml;bernehmen wollen, die vorhandene Installation l&ouml;schen wollen (dabei gehen s&auml;mtliche vorhandenen Daten unwiderruflich verloren!!) oder die Datenbankkonfiguration &auml;ndern wollen (z.B. eine andere Datenbank ausw&auml;hlen oder den Tabellenpr&auml;fix &auml;ndern).';
$LNG['existing_installation_old_unknown'] = 'Es handelt sich dabei allerdings um eine &auml;ltere Version (<b>%s</b>). Dies ist eine <b>unbekannte Version</b>, d.h. es steht kein offizielles Updatescript zur Verf&uuml;gung. Wie soll mit den Daten verfahren werden?';
$LNG['existing_installation_old_known'] = 'Es handelt sich dabei allerdings um eine &auml;ltere Version (<b>%s</b>). Sie k&ouml;nnen nun im Folgenden die Daten automatisch aktualisieren. Dabei sollten keinerlei Informationen verloren gehen, allerdings wird dringend empfohlen, vorher trotzdem ein Backup anzufertigen!';

$LNG['Update_existing_data'] = 'Vorhandene Daten automatisch aktualisieren';
$LNG['Use_existing_data'] = 'Vorhandene Daten &uuml;bernehmen';
$LNG['Delete_existing_data'] = 'Vorhandene Daten unwiderruflich l&ouml;schen';
$LNG['Change_database_configuration'] = 'Datenbankkonfiguration &auml;ndern';

$LNG['old_data_successfully_updated'] = 'Die veralteten Daten wurden erfolgreich auf den neuesten Stand gebracht! Bitte klicken Sie auf &quot;weiter&quot; um mit der Installation fortzufahren.';


//
// Basic data insertion
//
$LNG['basic_data_insertion_info'] = 'Im Folgenden wird in der angegebenen Datenbank die Tabellenstruktur f&uuml;r das Tritanium Bulletin Board 2 angelegt (haben Sie es im vorherigen Schritt ausgew&auml;hlt, werden erst die alten Tabellen gel&ouml;scht) und anschlie&szlig;end f&uuml;r den Betrieb notwendige Basisdaten in die Datenbank eingelesen. Ist dieser Vorgang abgeschlossen haben Sie im n&auml;chsten Schritt die M&ouml;glichkeit einige Grundeinstellungen f&uuml;r das Board vorzunehmen.';
$LNG['Deleting_old_tables'] = 'L&ouml;sche alte Tabellen...';
$LNG['Creating_tables'] = 'Erstelle Tabellen...';
$LNG['successful'] = 'erfolgreich';
$LNG['Inserting_basic_data'] = 'F&uuml;ge Basisdaten ein...';


//
// Board config
//
$LNG['board_configuration_info'] = 'Die folgenden Angaben sind f&uuml;r den Betrieb des Boards sehr wichtig, deswegen &uuml;berpr&uuml;fen Sie die Vorgaben bitte sehr sorgf&auml;ltig auf Korrektheit, da sonst ein einwandfreies Arbeiten der Software nicht garantiert werden kann. Falls Sie sich bei bestimmten Angaben nicht sicher sind, kontaktieren Sie bitte Ihren Webspaceprovider, welcher Ihnen sicher weiterhelfen kann oder besuchen Sie uns in unserem Forum auf <a href="http://www.tritanium-scripts.com" target="_blank">http://www.tritanium-scripts.com</a>. Genauere Einstellungen k&ouml;nnen Sie sp&auml;ter in der Administration vornehmen.';
$LNG['Yes'] = 'Ja';
$LNG['No'] = 'Nein';
$LNG['Path_to_forum'] = 'Pfad zum Forum';
$LNG['path_to_forum_info'] = 'Der komplette Pfad zu dem Ordner, in dem sich das Forum, also auch dieses Script befindet.';
$LNG['Board_address'] = 'Adresse des Boards';
$LNG['board_address_info'] = 'z.B. http://www.meinedomain.de/forum; http:// nicht vergessen; am Ende ohne / oder index.php!';
$LNG['Enable_file_upload'] = 'Dateiupload aktivieren';
$LNG['enable_file_upload_info'] = 'Falls der Test zum Dateiupload bei der System&uuml;berpr&uuml;fung fehlgeschlagen ist, sollten Sie hier &quot;nein&quot; w&auml;hlen';
$LNG['Enable_avatar_upload'] = 'Avatarupload aktivieren';
$LNG['enable_avatar_upload_info'] = 'Falls der Test zum Avatarupload bei der System&uuml;berpr&uuml;fung fehlgeschlagen ist, sollten Sie hier &quot;nein&quot; w&auml;hlen';
$LNG['create_admin_info'] = 'Im n&auml;chsten Schritt wird der Administrator f&uuml;r das Forum erstellt. Bitte klicken Sie dazu auf &quot;weiter&quot;.';
$LNG['create_admin_keep_data_info'] = 'Da Sie die Daten einer vorherigen Installation &uuml;bernehmen wollen, wird der n&auml;chste Punkt &quot;Erstellen des Administrators&quot; &uuml;bersprungen und Sie werden direkt zum Abschluss der Installation weitergeleitet. Falls Sie trotzdem einen Administrator erstellen wollen, w&auml;hlen Sie im n&auml;chsten Feld bitte &quot;Ja&quot;. Klicken Sie anschlie&szlig;end auf &quot;weiter&quot;.';
$LNG['Create_another_admin'] = 'Administrator erstellen';

$LNG['error_no_database_server'] = 'Bitte geben Sie einen Datenbankserver an!';
$LNG['error_no_database_user'] = 'Bitte geben Sie einen Datenbankuser an!';
$LNG['error_no_database_name'] = 'Bitte geben Sie einen Datenbankname an!';
$LNG['error_invalid_unknown_database_name'] = 'Ung&uuml;tiger/unbekannter Datenbankname: %s';
$LNG['error_connecting_database_server'] = 'Fehler beim Verbinden mit dem Datenbankserver: %s';
$LNG['error_invalid_table_prefix'] = 'Ung&uuml;ltiger Tabellenprefix!';
$LNG['error_deleting_old_tables'] = 'Fehler beim l&ouml;schen der alten Tabellen!';
$LNG['error_creating_tables'] = 'Fehler beim Erstellen der Tabellen!';
$LNG['error_inserting_basic_data'] = 'Fehler beim Einf&uuml;gen der Basisdaten!';
$LNG['error_cannot_write_config_file'] = 'FEHLER: Es kann in die Konfigurationsdatei &quot;dbconfig.php&quot; nicht geschrieben werden! Bitte setzen Sie die Rechte der Datei <u>f&uuml;r die Dauer der Installation</u> auf 777!';


//
// Admin creation
//
$LNG['administrator_creation_info'] = 'In diesem Schritt erstellen Sie einen Administrator f&uuml;r das Board. Dieser Benutzer hat s&auml;mtliche Rechte und hat zus&auml;tzlich Zugriff auf alle Punkte der Administration. Bitte w&auml;hlen Sie im Folgenden einen Benutzernamen, ein Passwort und eine Emailadresse f&uuml;r diesen Administrator. Sobald die Installation abgeschlossen ist, haben Sie die M&ouml;glichkeit weitere Administratoren zu erstellen oder zu ernennen.';
$LNG['User_name'] = 'Benutzername';
$LNG['user_name_info'] = 'maximal 15 Zeichen; nur Zahlen, Buchstaben und Unterstriche erlaubt';
$LNG['Password'] = 'Passwort';
$LNG['Password_confirmation'] = 'Passwort Wiederholung';
$LNG['Email_address'] = 'Emailadresse';
$LNG['Email_address_confirmation'] = 'Emailadresse Wiederholung';

$LNG['error_invalid_user_name'] = 'Der gew&auml;hlte Benutzername ist ung&uuml;ltig. Bitte beachten Sie, dass Benutzernamen nur Zahlen, Buchstaben und Unterstriche enthalten d&uuml;rfen.';
$LNG['error_existing_user_name'] = 'Der gew&auml;hlte Benutzername existiert schon in der Datenbank. W&auml;hlen Sie bitte einen anderen Benutzernamen oder l&ouml;schen Sie die vorhandenen Daten ind er Datenbank';
$LNG['error_invalid_email_address'] = 'Die Emailadresse ist ung&uuml;ltig.';
$LNG['error_invalid_password'] = 'Das Passwort ist ung&uuml;ltig.';
$LNG['error_pws_no_match'] = 'Die Passwort und die Passwort Wiederholung stimmen nicht &uuml;berein.';
$LNG['error_email_addresses_no_match'] = 'Die Emailadresse und die Emailadresse Wiederholung stimmen nicht &uuml;berein.';


//
// Installationsabschluss
//
$LNG['installation_finish_info'] = 'Im Folgenden muss nur noch die Konfigurationsdatei &quot;dbconfig.php&quot; geschrieben werden. Bitte stellen Sie sicher, dass in diese Datei geschrieben werden kann, und dr&uuml;cken Sie dann auf &quot;weiter&quot;.';

$LNG['Creating_config_file'] = 'Erstelle Konfigurationsdatei &quot;dbconfig.php&quot;...';
$LNG['Cannot_open_config_file'] = 'Kann Konfigurationsdatei nicht zum Schreiben &ouml;ffnen (bitte &uuml;berpr&uuml;fen Sie die Rechte des Verzeichnisses, in das Sie das Forum hochgeladen haben (sollten <u>tempor&auml;r</u> 777 sein))';
$LNG['Cannot_write_config_file'] = 'Kann keine Daten in die Konfigurationsdatei schreiben (bitte &uuml;berpr&uuml;fen Sie die Rechte des Verzeichnisses, in das Sie das Forum hochgeladen haben (sollten <u>tempor&auml;r</u> 777 sein))';
$LNG['Cannot_set_chmod'] = '<b>Bitte beachten Sie:</b> Die Rechte der Konfigurationsdatei &quot;dbconfig.php&quot; konnten nicht gesetzt werden (bitte setzen Sie die Rechte der Datei &quot;dbconfig.php&quot; z.B. per FTP manuell auf 755 bzw. unter Windows auf &quot;schreibgesch&uuml;tzt&quot;)';


$LNG['installation_successful'] = 'Herzlichen Gl&uuml;ckwunsch! Die Installation wurde erfolgreich abgeschlossen, damit ist Ihr Tritanium Bulletin Board 2 nun einsatzbereit. Sie k&ouml;nnen das Forum &uuml;ber <a href="index.php">index.php</a> erreichen, allerdings m&uuml;ssen Sie erste diese Installationsdatei (&quot;install.php&quot;) l&ouml;schen. Hilfestelllungen zu ersten Schritten entnehmen Sie bitte der Dokumentation.';


?>