<?php
/**
*
* Tritanium Bulletin Board 2 - ad_config.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');


$p_config = array(); // Beinhaltet spaeter saemtliche Konfigurationswerte
$checked = array(); // Beinhaltet den Status der Auswahllisten (welches Feld angewaehlt ist, z.B. "Ja" oder "Nein")


$config_values = array('posts_per_page','topics_per_page','maximum_sig_length','board_name','enable_wio','enable_sig','allow_sig_bbcode',
	'allow_sig_html','standard_language','allow_select_lng','show_wio_forumindex','wio_timeout','enable_gzip','search_status',
	'show_boardstats_forumindex','board_address','enable_registration','maximum_registrations','verify_email_address','guests_enter_board',
	'board_logo','maximum_pms_folders','maximum_pms','allow_pms_smilies','allow_pms_bbcode','allow_pms_htmlcode','allow_pms_signature',
	'enable_pms','enable_outbox','show_latest_posts_forumindex','max_latest_posts','require_accept_boardrules','admin_rank_pic',
	'mod_rank_pic','srgc_probability','enable_avatars','enable_avatar_upload','max_avatar_file_size','avatar_image_height',
	'avatar_image_width','enable_email_functions','board_email_address','allow_pms_rconfirmation','allow_sig_smilies','email_signature',
	'enable_topic_subscription','show_techstats','sr_timeout','standard_tz','news_forum','enable_news_module','show_news_forumindex',
	'enable_email_formular','path_to_forum'
);

$checked_config_values = array('enable_sig'=>2,'allow_sig_bbcode'=>2,'allow_sig_html'=>2,'enable_wio'=>2,'allow_select_lng'=>2,
	'show_wio_forumindex'=>2,'enable_gzip'=>2,'search_status'=>3,'show_boardstats_forumindex'=>2,'enable_registration'=>2,
	'verify_email_address'=>3,'guests_enter_board'=>2,'allow_pms_smilies'=>2,'allow_pms_bbcode'=>2,'allow_pms_htmlcode'=>2,
	'allow_pms_signature'=>2,'enable_outbox'=>2,'enable_pms'=>2,'show_latest_posts_forumindex'=>2,'require_accept_boardrules'=>2,
	'enable_avatars'=>2,'enable_avatar_upload'=>2,'enable_email_functions'=>2,'allow_pms_rconfirmation'=>2,'allow_sig_smilies'=>2,
	'enable_topic_subscription'=>2,'show_techstats'=>2,'enable_news_module'=>2,'show_news_forumindex'=>2,'enable_email_formular'=>2
);


while(list(,$akt_config_value) = each($config_values))
	$p_config[$akt_config_value] = isset($_POST['p_config'][$akt_config_value]) ? $_POST['p_config'][$akt_config_value] : $CONFIG[$akt_config_value];


//
// Spezifische Dinge fuer einzelne Konfigurationswerde
//
$p_config['email_signature'] = str_replace("\r\n","\n",$p_config['email_signature']); // Da die Emailsignatur nur in Emails verwendet wird und dort \n vorherrschend ist sollte das auch verwendet werden


if(isset($_GET['doit'])) { // Falls Formular gesendet wurde
	while(list($akt_key,$akt_value) = each($p_config))
		$DB->query("UPDATE ".TBLPFX."config SET config_value='$akt_value' WHERE config_name='$akt_key'"); // Aktuellen Konfigurationswert speichern

	include_once('ad_pheader.php');
	show_message($LNG['Board_config_updated'],$LNG['message_board_config_updated'],FALSE); // Meldung ausgeben
	include_once('ad_ptail.php'); exit;
}


while(list($akt_checked_config_value,$akt_options_counter) = each($checked_config_values)) {
	for($i = 0; $i < $akt_options_counter; $i++)
		$checked[$akt_checked_config_value][] = '';
}
reset($checked);


$c = ' selected="selected"';
while(list($akt_key) = each($checked)) {
	$checked[$akt_key][$p_config[$akt_key]] = $c;
}


$adconfig_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_config']); // Templateobjekt erstellen


//
// Die Sprachpakete
//
if(@$fp = opendir('language')) { // Verzeichnis "language" oeffnen
	while($akt_dir = readdir($fp)) {
		if($akt_dir != '.' && $akt_dir != '..') { // Falls das aktuelle Verzeichnis keine Referenz auf "language" selbst oder das uebergeordnete Verzeichnis ist
			$akt_c = ($akt_dir == $p_config['standard_language']) ? ' selected="selected"' : ''; // Falls aktuelles Verzeichnis ausgewaehlt ist, dort "checked" angeben
			$adconfig_tpl->blocks['lng_optionrow']->parse_code(FALSE,TRUE); // Templateblock fuer aktuelles Verzeichnis erstellen
		}
	}
	closedir($fp); // Verzeichnis schliessen
}


//
// Die Zeitzonen
//
while(list($akt_tz_id) = each($TIMEZONES)) {
	$akt_tz_name = $LNG['tz_'.$akt_tz_id];
	$akt_checked = ($akt_tz_id == $p_config['standard_tz']) ? ' selected="selected"' : '';
	$adconfig_tpl->blocks['tzrow']->parse_code(FALSE,TRUE);
}
reset($TIMEZONES);


//
// Die Newsforum-Auswahl
//
$DB->query("SELECT forum_id,forum_name FROM ".TBLPFX."forums ORDER BY order_id ASC");
while($akt_forum = $DB->fetch_array()) {
	$adconfig_tpl->blocks['forumrow']->parse_code(FALSE,TRUE);
}

include_once('ad_pheader.php'); // Seitenkopf ausgeben

$adconfig_tpl->parse_code(TRUE); // Seite ausgeben

include_once('ad_ptail.php'); // Seitenende ausgeben

?>