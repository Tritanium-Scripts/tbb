<?php
/**
*
* Tritanium Bulletin Board 2 - ad_config.php
* version #2004-01-01-18-38-43
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

//*
//* Das hier ist alles sehr kompliziert geschrieben. Ich denke man kann einiges vereinfachen, allerdings
//* bin ich bisher noch nicht auf die richtige Idee gekommmen, also nicht wundern... :-)
//* Ideen sind natuerlich jederzeit gerne willkommen (julian@tritanium-scripts.com)
//*

require_once('auth.php');

$p_config = array(); // Beinhaltet saemtliche spaeteren Konfigurationswerte


//
// Im Folgenden werden die einzelnen Werte entweder vom uebertragenen Formular verwendet oder die Werte aus der Datenbank uebernommen
//
$p_config['posts_per_page'] = isset($_POST['p_config']['posts_per_page']) ? $_POST['p_config']['posts_per_page'] : $CONFIG['posts_per_page'];
$p_config['topics_per_page'] = isset($_POST['p_config']['topics_per_page']) ? $_POST['p_config']['topics_per_page'] : $CONFIG['topics_per_page'];
$p_config['maximum_sig_length'] = isset($_POST['p_config']['maximum_sig_legth']) ? $_POST['p_config']['maximum_sig_legth'] : $CONFIG['maximum_sig_length'];
$p_config['board_name'] = isset($_POST['p_config']['board_name']) ? $_POST['p_config']['board_name'] : $CONFIG['board_name'];
$p_config['enable_wio'] = isset($_POST['p_config']['enable_wio']) ? $_POST['p_config']['enable_wio'] : $CONFIG['enable_wio'];
$p_config['enable_sig'] = isset($_POST['p_config']['enable_sig']) ? $_POST['p_config']['enable_sig'] : $CONFIG['enable_sig'];
$p_config['allow_sig_bbcode'] = isset($_POST['p_config']['allow_sig_bbcode']) ? $_POST['p_config']['allow_sig_bbcode'] : $CONFIG['allow_sig_bbcode'];
$p_config['allow_sig_html'] = isset($_POST['p_config']['allow_sig_html']) ? $_POST['p_config']['allow_sig_html'] : $CONFIG['allow_sig_html'];
$p_config['standard_language'] = isset($_POST['p_config']['standard_language']) ? $_POST['p_config']['standard_language'] : $CONFIG['standard_language'];
$p_config['allow_select_lng'] = isset($_POST['p_config']['allow_select_lng']) ? $_POST['p_config']['allow_select_lng'] : $CONFIG['allow_select_lng'];
$p_config['show_wio_forumindex'] = isset($_POST['p_config']['show_wio_forumindex']) ? $_POST['p_config']['show_wio_forumindex'] : $CONFIG['show_wio_forumindex'];
$p_config['wio_timeout'] = isset($_POST['p_config']['wio_timeout']) ? $_POST['p_config']['wio_timeout'] : $CONFIG['wio_timeout'];
$p_config['enable_gzip'] = isset($_POST['p_config']['enable_gzip']) ? $_POST['p_config']['enable_gzip'] : $CONFIG['enable_gzip'];
$p_config['search_status'] = isset($_POST['p_config']['search_status']) ? $_POST['p_config']['search_status'] : $CONFIG['search_status'];
$p_config['show_boardstats_forumindex'] = isset($_POST['p_config']['show_boardstats_forumindex']) ? $_POST['p_config']['show_boardstats_forumindex'] : $CONFIG['show_boardstats_forumindex'];
$p_config['board_address'] = isset($_POST['p_config']['board_address']) ? $_POST['p_config']['board_address'] : $CONFIG['board_address'];
$p_config['enable_registration'] = isset($_POST['p_config']['enable_registration']) ? $_POST['p_config']['enable_registration'] : $CONFIG['enable_registration'];
$p_config['maximum_registrations'] = isset($_POST['p_config']['maximum_registrations']) ? $_POST['p_config']['maximum_registrations'] : $CONFIG['maximum_registrations'];
$p_config['verify_email_address'] = isset($_POST['p_config']['verify_email_address']) ? $_POST['p_config']['verify_email_address'] : $CONFIG['verify_email_address'];
$p_config['guests_enter_board'] = isset($_POST['p_config']['guests_enter_board']) ? $_POST['p_config']['guests_enter_board'] : $CONFIG['guests_enter_board'];
$p_config['board_logo'] = isset($_POST['p_config']['board_logo']) ? $_POST['p_config']['board_logo'] : $CONFIG['board_logo'];
$p_config['maximum_pms_folders'] = isset($_POST['p_config']['maximum_pms_folders']) ? $_POST['p_config']['maximum_pms_folders'] : $CONFIG['maximum_pms_folders'];
$p_config['maximum_pms'] = isset($_POST['p_config']['maximum_pms']) ? $_POST['p_config']['maximum_pms'] : $CONFIG['maximum_pms'];
$p_config['allow_pms_smilies'] = isset($_POST['p_config']['allow_pms_smilies']) ? $_POST['p_config']['allow_pms_smilies'] : $CONFIG['allow_pms_smilies'];
$p_config['allow_pms_bbcode'] = isset($_POST['p_config']['allow_pms_bbcode']) ? $_POST['p_config']['allow_pms_bbcode'] : $CONFIG['allow_pms_bbcode'];
$p_config['allow_pms_htmlcode'] = isset($_POST['p_config']['allow_pms_htmlcode']) ? $_POST['p_config']['allow_pms_htmlcode'] : $CONFIG['allow_pms_htmlcode'];
$p_config['allow_pms_signature'] = isset($_POST['p_config']['allow_pms_signature']) ? $_POST['p_config']['allow_pms_signature'] : $CONFIG['allow_pms_signature'];
$p_config['enable_pms'] = isset($_POST['p_config']['enable_pms']) ? $_POST['p_config']['enable_pms'] : $CONFIG['enable_pms'];
$p_config['enable_outbox'] = isset($_POST['p_config']['enable_outbox']) ? $_POST['p_config']['enable_outbox'] : $CONFIG['enable_outbox'];


$error = ''; // Eventueller Fehler


//
// Beinhaltet den Status der Auswahllisten (welches Feld angewaehlt ist, z.B. "Ja" oder "Nein")
//
$checked = array('enable_sig_0'=>'','enable_sig_1'=>'','allow_sig_bbcode_0'=>'','allow_sig_bbcode_1'=>'',
	'allow_sig_html_0'=>'','allow_sig_html_1'=>'','enable_wio_0'=>'','enable_wio_1'=>'',
	'allow_select_lng_0'=>'','allow_select_lng_1'=>'','show_wio_forumindex_0'=>'','show_wio_forumindex_1'=>'',
	'enable_gzip_0'=>'','enable_gzip_1'=>'','search_status_0'=>'','search_status_1'=>'','search_status_2'=>'',
	'show_boardstats_forumindex_0'=>'','show_boardstats_forumindex_1'=>'','enable_registration_0'=>'','enable_registration_1'=>'',
	'verify_email_address_0'=>'','verify_email_address_1'=>'','verify_email_address_2'=>'','guests_enter_board_0'=>'','guests_enter_board_1'=>'',
	'allow_pms_smilies_0'=>'','allow_pms_smilies_1'=>'','allow_pms_bbcode_0'=>'','allow_pms_bbcode_1'=>'',
	'allow_pms_htmlcode_0'=>'','allow_pms_htmlcode_1'=>'','enable_pms_0'=>'','enable_pms_1'=>'',
	'allow_pms_signature_0'=>'','allow_pms_signature_1'=>'','enable_outbox_0'=>'','enable_outbox_1'=>'',
);

if(isset($_GET['doit'])) { // Falls Formular gesendet wurde
	while(list($akt_key,$akt_value) = each($p_config))
		$db->query("UPDATE ".TBLPFX."config SET config_value='$akt_value' WHERE config_name='$akt_key'"); // Aktuellen Konfigurationswert speichern

	include_once('ad_pheader.php'); // Seitenkopf anzeigen
	show_message('Board_config_updated','message_board_config_updated'); // Meldung ausgeben
	include_once('ad_ptail.php'); exit; // Seitenende ausgeben
}


//
// Den "checked"-Status der einzelnen Optionen der Auswahllisten bestimmen
//
$x = ' selected="selected"';
$p_config['enable_wio'] == 1 ? $checked['enable_wio_1'] = $x : $checked['enable_wio_0'] = $x;
$p_config['enable_sig'] == 1 ? $checked['enable_sig_1'] = $x : $checked['enable_sig_0'] = $x;
$p_config['allow_sig_bbcode'] == 1 ? $checked['allow_sig_bbcode_1'] = $x : $checked['allow_sig_bbcode_0'] = $x;
$p_config['allow_sig_html'] == 1 ? $checked['allow_sig_html_1'] = $x : $checked['allow_sig_html_0'] = $x;
$p_config['allow_select_lng'] == 1 ? $checked['allow_select_lng_1'] = $x : $checked['allow_select_lng_0'] = $x;
$p_config['show_wio_forumindex'] == 1 ? $checked['show_wio_forumindex_1'] = $x : $checked['show_wio_forumindex_0'] = $x;
$p_config['enable_gzip'] == 1 ? $checked['enable_gzip_1'] = $x : $checked['enable_gzip_0'] = $x;
$p_config['search_status'] == 1 ? $checked['search_status_1'] = $x : ($p_config['search_status'] == 0 ? $checked['search_status_0'] = $x : $checked['search_status_2'] = $x);
$p_config['verify_email_address'] == 1 ? $checked['verify_email_address_1'] = $x : ($p_config['verify_email_address'] == 0 ? $checked['verify_email_address_0'] = $x : $checked['verify_email_address_2'] = $x);
$p_config['show_boardstats_forumindex'] == 1 ? $checked['show_boardstats_forumindex_1'] = $x : $checked['show_boardstats_forumindex_0'] = $x;
$p_config['enable_registration'] == 1 ? $checked['enable_registration_1'] = $x : $checked['enable_registration_0'] = $x;
$p_config['guests_enter_board'] == 1 ? $checked['guests_enter_board_1'] = $x : $checked['guests_enter_board_0'] = $x;
$p_config['allow_pms_smilies'] == 1 ? $checked['allow_pms_smilies_1'] = $x : $checked['allow_pms_smilies_0'] = $x;
$p_config['allow_pms_bbcode'] == 1 ? $checked['allow_pms_bbcode_1'] = $x : $checked['allow_pms_bbcode_0'] = $x;
$p_config['allow_pms_htmlcode'] == 1 ? $checked['allow_pms_htmlcode_1'] = $x : $checked['allow_pms_htmlcode_0'] = $x;
$p_config['enable_pms'] == 1 ? $checked['enable_pms_1'] = $x : $checked['enable_pms_0'] = $x;
$p_config['allow_pms_signature'] == 1 ? $checked['allow_pms_signature_1'] = $x : $checked['allow_pms_signature_0'] = $x;
$p_config['enable_outbox'] == 1 ? $checked['enable_outbox_1'] = $x : $checked['enable_outbox_0'] = $x;


$adconfig_tpl = new template; // Templateobjekt erstellen
$adconfig_tpl->load($template_path.'/'.$tpl_config['tpl_ad_config']); // Template laden

if(@$fp = opendir('language')) { // Verzeichnis "language" oeffnen
	while($akt_dir = readdir($fp)) {
		if($akt_dir != '.' && $akt_dir != '..') { // Falls das aktuelle Verzeichnis keine Referenz auf "language" oder das uebergeordnete Verzeichnis ist
			$akt_c = ($akt_dir == $p_config['standard_language']) ? ' selected="selected"' : ''; // Falls aktuelles Verzeichnis ausgewaehlt ist, dort "checked" angeben
			$adconfig_tpl->blocks['lng_optionrow']->parse_code(FALSE,TRUE); // Templateblock fuer aktuelles Verzeichnis erstellen
		}
	}
	closedir($fp); // Verzeichnis schliessen
}

include_once('ad_pheader.php'); // Seitenkopf ausgeben

$adconfig_tpl->parse_code(TRUE); // Seite ausgeben

include_once('ad_ptail.php'); // Seitenende ausgeben

?>