<?php
/**
*
* Tritanium Bulletin Board 2 - functions.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

//*
//* Ueberprueft, ob ein Nickname ein gueltiges Format hat (nur Buchstaben und Zahlen, erstes Zeichen muss Buchstabe sein, maximal 15 Zeichen)
//*
function verify_nick($nick) {
	if(strlen($nick) <= 15 && preg_match('/^[a-z_]{1}[a-z0-9_]{1,}$/si',$nick) == TRUE) return TRUE;
	return FALSE;
}


//*
//* Ueberprueft, ob eine ICQ-Nummer ein gueltiges Format hat (nur Zahlen)
//*
function verify_icq_uin($icq_uin) {
	if(preg_match('/^[0-9]{1,}$/si',$icq_uin)) return TRUE;
	return FALSE;
}


//*
//* Ueberprueft, ob eine Emailadresse ein gueltiges Format hat (xxx@xxx.xx)
//*
function verify_email($email) {
	if(preg_match('/^[\.0-9a-z_-]{1,}@[\.0-9a-z-]{1,}\.[a-z]{1,}$/si',$email)) return TRUE;
	return FALSE;
}


//*
//* Entfernt Backslashes (\) aus einem String (mysslashes()) und verhindert HTML-Code (myhtmlentities())
//*
function mutate($text) {
	$text = myhtmlentities(mysslashes($text),TRUE);
	return $text;
}


//*
//* Wendet die Funktion mutate() auf mehrere Variablen an (Uebergeben werden die Namen (!) der Variablen)
//*
function multimutate() {
	$args = func_get_args();
	while(list(,$akt_arg) = each($args))
		$GLOBALS[$akt_arg] = mutate($GLOBALS[$akt_arg]);
}


//*
//* Verschluesselt einen String per md5()
//*
function mycrypt($string) {
	return md5($string);
}


//*
//* Entfernt Backslashes (\) aus einem String
//*
function mysslashes($text) {
	$text = str_replace("\\\"","\"",$text);
	$text = str_replace("\\\\","\\",$text);
	$text = str_replace("\\'","'",$text);
	return $text;
}


//*
//* Entfernt Wagenruecklaeufe (\r) aus einem String
//*
function kill_r($data) {
	return str_replace("\r",'',$data);
}


//*
//* Wandelt neue Zeilen (\n) in Wagenruecklaeufe (\r) um
//*
function nl2r($data) {
	return str_replace("\n","\r",$data);
}


//*
//* Wandelt Wagenruecklaeufe (\r) in neue Zeilen (\n) um
//*
function r2nl($data) {
	return str_replace("\r","\n",$data);
}


//*
//* Wandelt <br /> und <br> in neue Zeilen (\n) um
//*
function br2nl($data) {
	$data = str_replace('<br>',"\n",$data);
	return str_replace('<br />',"\n",$data);
}


//*
//* Entfernt Tabulatoren (\t) aus einem String
//*
function kill_t($data) {
	return str_replace("\t",'',$data);
}


//*
//* Erzeugt eine zufaellige Zeichenfolge aus Zahlen und Buchstaben mit einer maximalen Laenge von 32 Zeichen (da md5() verwendet wird...)
//*
function get_rand_string($length) {
	return substr(md5(uniqid(rand(),1)),0,$length);
}


//*
//* Gibt eine Meldung aus
//*
function show_message($message_title,$message_text,$append = '') {
	global $lng,$template_path,$tpl_config;
	$message_tpl = new template;
	$message_tpl->load($template_path.'/'.$tpl_config['tpl_message']);

	$message_tpl->values = array(
		'MESSAGE_TITLE'=>$lng[$message_title],
		'MESSAGE_TEXT'=>$lng[$message_text].$append
	);

	$message_tpl->parse_code(TRUE);
}


//*
//* Zeigt die Navigationsleiste an
//*
function show_navbar($left = '', $center = '', $right = '') {
	global $template_path,$tpl_config;

	$left = str_replace("\r","&nbsp;&#187;&nbsp;",$left);

	$navbar_tpl = new template;
	$navbar_tpl->load($template_path.'/'.$tpl_config['tpl_navbar']);
	$navbar_tpl->values = array(
		'LEFT'=>$left,
		'CENTER'=>$center,
		'RIGHT'=>$right
	);
	$navbar_tpl->parse_code(TRUE);
}


//*
//* Formatiert einen UNIX-Timestamp (Zeitstempel) (nur Zeit)
//*
function format_time($timestamp) {
	global $lng;

	return date($lng['time_format'],$timestamp);
}


//*
//* Formatiert einen UNIX-Timestamp (Zeitstempel; time()) gemae� dem gewaehlten date()-Format (Datum und Zeit)
//*
function format_date($timestamp,$mode = FALSE) {
	global $lng;

	if($mode == TRUE)
		return date($lng['date_format'],$timestamp);

	$compare_date = date('d.m.Y',$timestamp);

	if(date('d.m.Y') == $compare_date) return date($lng['today_date_format'],$timestamp);
	if(date('d.m.Y',time()-86400) == $compare_date) return date($lng['yesterday_date_format'],$timestamp);
	return date($lng['date_format'],$timestamp);
}


//*
//* Konvertiert einen UNIX-Timestamp (Zeitstempel; time()) in das SQL "timestamp"-Format
//*
function unixtstamp2sqltstamp($timestamp) {
	global $db;


	return date($db->TIMESTAMP_FORMAT,$timestamp);

}


//*
//* Konvertiert einen UNIX-Timestamp (Zeitstempel; time()) in das SQL "datetime"-Format
//*
function unixtstamp2sqldatetime($timestamp) {
	global $db;

	return date($db->DATETIME_FORMAT,$timestamp);
}


//*
//* Wandelt neue Zeilen in <br /> um (aehnlich zu nl2br(), dieses laesst allerdings das \n)
//*
function nlbr($text) {
	return str_replace("\n",'<br />',$text);
}


//*
//* Wandelt & (falls es kein Unicode ist), < und > in &amp;, &lt; in &gt; um, damit wird HTML-Code verhindert
//*
function myhtmlentities($string,$specialchr = TRUE) {
	global $html_trans_table;

	$chars_search = array('/&(?!\#[0-9]+;)/','/</','/>/');
	$chars_replace = array('&amp;','&lt;','&gt');

	return preg_replace($chars_search,$chars_replace,$string);
}


//*
//* Die Zeit des letzten Besuchs eines Forums aktualisieren
//*
function update_forum_cookie($forum_id) {
	$cookie_forums = isset($_COOKIE['c_forums']) ? explode('x',$_COOKIE['c_forums']) : array();
	$x = FALSE;
	while(list($akt_key,$akt_forum_cookie) = each($cookie_forums)) {
		$akt_forum_cookie = explode('_',$akt_forum_cookie);

		if($akt_forum_cookie[0] == $forum_id) {
			$x = TRUE;
			$akt_forum_cookie[1] = time();
			$cookie_forums[$akt_key] = $akt_forum_cookie[0].'_'.$akt_forum_cookie[1];
			break;
		}
	}
	if($x == FALSE)
		$cookie_forums[] = $forum_id.'_'.time();

	$cookie_forums = implode('x',$cookie_forums);

	setcookie('c_forums',$cookie_forums,time()+31536000,'/');
}


//*
//* Die Zeit des letzten Besuchs eines Themas aktualisieren
//*
function update_topic_cookie($forum_id,$topic_id,$when) {
	$cookie_topics = isset($_COOKIE['c_topics']) ? explode('x',$_COOKIE['c_topics']) : array();
	$x = $y = FALSE;
	while(list($akt_forum_key,$akt_forum_value) = each($cookie_topics)) {
		$akt_forum_value = explode('y',$akt_forum_value);
		if($akt_forum_value[0] == $forum_id) {
			$x = TRUE;
			$akt_forum_topics = explode('z',$akt_forum_value[1]);
			while(list($akt_topic_key,$akt_topic_value) = each($akt_forum_topics)) {
				$akt_topic_value = explode('_',$akt_topic_value);
				if($akt_topic_value[0] == $topic_id) {
					$y = TRUE;
					$akt_topic_value[1] = $when;
					$akt_forum_topics[$akt_topic_key] = implode('_',$akt_topic_value);
					break;
				}
			}
			if($y == FALSE)
				$akt_forum_topics[] = $topic_id.'_'.$when;
			$akt_forum_value[1] = implode('z',$akt_forum_topics);
			$cookie_topics[$akt_forum_key] = implode('y',$akt_forum_value);
			break;
		}
	}
	if($x == FALSE)
		$cookie_topics[] = $forum_id.'y'.$topic_id.'_'.$when;

	$cookie_topics = implode('x',$cookie_topics);

	setcookie('c_topics',$cookie_topics,time()+31536000,'/');
	$_COOKIE['c_topics'] = $cookie_topics;
}


//*
//* Die Auswahl der Smilies erzeugen
//*
function get_smilies_box() {
	global $tpl_config,$template_path,$db;

	$smilies_tpl = new template;
	$smilies_tpl->load($template_path.'/'.$tpl_config['tpl_smiliesbox']);

	$db->query("SELECT smiley_id,smiley_gfx,smiley_synonym FROM ".TBLPFX."smilies WHERE smiley_type='0' AND smiley_status='1' LIMIT ".$tpl_config['smiliesbox_maximum_smilies']);
	$smilies_data = $db->raw2array();

	$smilies_counter = count($smilies_data);

	if($smilies_counter > 0) {
		for($i = 0; $i < $smilies_counter; $i++) {
			$smilies_tpl->blocks['smileyrow']->blocks['smileycol']->values = array(
				'akt_smiley'=>$smilies_data[$i]
			);
			$smilies_tpl->blocks['smileyrow']->blocks['smileycol']->parse_code(FALSE,TRUE);
			if(($i+1) % $tpl_config['smiliesbox_smilies_per_row'] == 0 && $i != $smilies_counter-1) {
				$smilies_tpl->blocks['smileyrow']->parse_code(FALSE,TRUE);
				$smilies_tpl->blocks['smileyrow']->blocks['smileycol']->reset_tpl();
			}
		}
		$smilies_tpl->blocks['smileyrow']->parse_code(FALSE,TRUE);
	}
	else $smilies_tpl->unset_block('smileyrow');

	return $smilies_tpl->parse_code();
}


//*
//* Die Auswahl der Beitragsbilder erzeugen
//*
function get_ppics_box($checked_id = 0) {
	global $tpl_config,$template_path,$db;

	$ppics_tpl = new template;
	$ppics_tpl->load($template_path.'/'.$tpl_config['tpl_ppicsbox']);

	$db->query("SELECT smiley_id,smiley_gfx FROM ".TBLPFX."smilies WHERE smiley_type='1'");
	$ppics_data = $db->raw2array();

	$ppics_counter = count($ppics_data);

	if($ppics_counter > 0) {
		for($i = 0; $i < $ppics_counter; $i++) {
			$akt_ppic = &$ppics_data[$i];

			$akt_checked = ($akt_ppic['smiley_id'] == $checked_id) ? ' checked="checked"' : '';

			$ppics_tpl->blocks['ppicrow']->blocks['ppiccol']->values = array(
				'akt_ppic'=>$akt_ppic,
				'akt_checked'=>$akt_checked
			);
			$ppics_tpl->blocks['ppicrow']->blocks['ppiccol']->parse_code(FALSE,TRUE);
			if(($i+1) % 7 == 0 && $i != $ppics_counter-1) {
				$ppics_tpl->blocks['ppicrow']->parse_code(FALSE,TRUE);
				$ppics_tpl->blocks['ppicrow']->blocks['ppiccol']->reset_tpl();
			}
		}
		$ppics_tpl->blocks['ppicrow']->parse_code(FALSE,TRUE);
	}
	else $ppics_tpl->unset_block('ppicrow');

	return $ppics_tpl->parse_code();
}


//*
//* Das BBCode-Menue erzeugen (um die Anwendung von BBCode zu erleichtern)
//*
function get_bbcode_box() {
	global $tpl_config,$template_path;

	$bbcode_tpl = new template;
	$bbcode_tpl->load($template_path.'/'.$tpl_config['tpl_bbcode_buttons']);

	return $bbcode_tpl->parse_code();
}


//*
//* addslashes() auf die einzelnen Elemente eines Array anwenden, falls Element wiederum ein Array ist, arbeitet die Funktion *rekursiv*, d.h. ruft sich selbst auf
//*
function array_addslashes(&$array) {
	while(list($akt_key) = each($array)) {
		if(is_array($array[$akt_key]) == TRUE) array_addslashes($array[$akt_key]);
		else $array[$akt_key] = addslashes($array[$akt_key]);
	}
	reset($array);
}


//*
//* Die aktuelle Zeit im Mikrosekunden (10^-6) bestimmen
//*
function get_mtime_counter() {
	$mtime = explode(" ",microtime());
	return $mtime[1] + $mtime[0];
}


//*
//* Ein User fuer ein Forum autorisieren
//*
function get_auth_forum_user($forum_id,$user_id,$what) {
	global $db; // Datenbank verfuegbar machen

	$what = implode(', ',$what); // Die einzelnen Rechte fuer den Query vorbereiten

	$db->query("SELECT $what FROM ".TBLPFX."forums_auth WHERE forum_id='$forum_id' AND auth_type='0' AND auth_id='$user_id'",TRUE); // Rechte eventuell laden
	if($db->affected_rows > 0) return $db->fetch_array(); // Falls Rechte gefunden wurden diese zurueckgeben
	else { // Ansonsten muss nach Gruppenrechten gesucht werden
		$group_ids = array(); // Hier stehen spaeter die IDs der Gruppen drin, in denen der User Mitglied ist
		$db->query("SELECT group_id FROM ".TBLPFX."groups_members WHERE member_id='$user_id'"); // IDs der Gruppen laden, in denen der User Mitglied ist
		if($db->affected_rows > 0) { // Falls der User in einer Gruppe Mitglied ist
			while(list($akt_group_id) = $db->fetch_array())
				$group_ids[] = $akt_group_id; // Die ID der Gruppe im Array speichern

			$db->query("SELECT $what FROM ".TBLPFX."forums_auth WHERE forum_id='$forum_id' AND auth_type='1' AND auth_id IN ('".implode("','",$group_ids)."') LIMIT 1"); // Rechte fuer Gruppen laden, in denen User Mitglied ist, allerdings maximal 1
			if($db->affected_rows > 0) return $db->fetch_array(); // Falls Rechte gefunden wurden diese zurueckgeben
		}
	}

	return FALSE; // Es wurden keine Rechte gefunden -> FALSE zurueckgeben
}


//*
//* Die IDs aller Foren laden, zu denen der User Zugriff hat
//*
function get_authed_forums() {
	global $db,$USER_ID,$USER_DATA,$USER_LOGGED_IN;


	$authed_forums_ids = array(); // Hier stehen spaeter die IDs der Foren drin, zu denen der User Zugriff hat
	if($USER_LOGGED_IN != 1) { // Falls der User nicht eingeloggt
		$db->query("SELECT forum_id FROM ".TBLPFX."forums WHERE auth_guests_view_forum='1'"); // Die IDs aller Foren laden, zu denen Gaeste Zugriff haben
		while(list($akt_forum_id) = $db->fetch_array())
			$authed_forums_ids[] = $akt_forum_id;
	}
	else { // Falls der User doch eingeloggt ist
		$not_authed_forums_ids = array(); // Hier stehen spaeter die IDs der Foren drin, zu denen der User KEINEN Zugriff hat

		if($USER_DATA['user_is_admin'] != 1 && $USER_DATA['user_is_supermod'] != 1) { // Falls User weder Admin noch Supermod ist
			$user_group_ids = array(); // Hier stehen spaeter die IDs der Gruppen drin, in denen der User Mitglied ist
			$db->query("SELECT group_id FROM ".TBLPFX."groups_members WHERE member_id='$USER_ID'"); // IDs aller Gruppen laden, in denen der User Mitglied ist
			while(list($akt_group_id) = $db->fetch_array())
				$user_group_ids[] = $akt_group_id;


			$db->query("SELECT forum_id FROM ".TBLPFX."forums_auth WHERE ((auth_type='0' AND auth_id='$USER_ID') OR (auth_type='0' AND auth_id IN ('".implode("','",$user_group_ids)."'))) AND auth_is_mod='0' AND auth_view_forum='0'"); // Die IDs der Foren laden, zu denen der User keinen Zugriff hat
			while(list($akt_forum_id) = $db->fetch_array())
				$not_authed_forums_ids[] = $akt_forum_id;
		}

		$db->query("SELECT forum_id FROM ".TBLPFX."forums WHERE forum_id NOT IN ('".implode("','",$not_authed_forums_ids)."')"); // Die IDs der Foren laden zu denen der User nicht keinen Zugriff hat (bzw. zu denen der User Zugriff hat)
		while(list($akt_forum_id) = $db->fetch_array())
			$authed_forums_ids[] = $akt_forum_id;
	}

	return $authed_forums_ids;
}


//*
//* Fuegt gegebenenfalls http:// vor einem String ein
//*
function addhttp($text) {
	if(substr($text,0,7) != "http://") $text = "http://".$text;
	return $text;
}


//*
//* Versendet eine Mail
//*
function mymail($from,$to,$subject,$message,$add_headers = '') {
	global $lng;

	$add_headers .= "From: $from\r\n".
		"Reply-To: $from\r\n".
		"Content-type: text/plain; charset=".$lng['email_encoding'];


	return @mail($to,$subject,$message,$add_headers);
}


//*
//* Erstellt ein Array aus Werten aus $array1, die auch in $array2 vorkommen
//*
function get_common_values($array1,$array2) {
	$return_array = array();

	while(list(,$akt_value) = each($array1)) {
		if(in_array($akt_value,$array2) == TRUE && in_array($akt_value,$return_array) == FALSE)
			$return_array[] = $akt_value;
	}

	return $return_array;
}


//*
//* Erstellt die Kategorienabfolge fuer die Navigationsleiste
//*
function get_navbar_cats($cat_id,$self = TRUE) {
	global $MYSID;

	$navbar_cats = '';
	$navbar_cats_data = cats_get_parent_cats_data($cat_id,$self);

	while(list(,$akt_navbar_cat) = each($navbar_cats_data))
		$navbar_cats .= "\r<a href=\"index.php?faction=viewcat&amp;cat_id=".$akt_navbar_cat['cat_id']."&amp;$MYSID\">".myhtmlentities($akt_navbar_cat['cat_name'])."</a>";

	return $navbar_cats;
}


?>