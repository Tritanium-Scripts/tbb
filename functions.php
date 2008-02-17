<?php
/**
*
* Tritanium Bulletin Board 2 - functions.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
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
function show_message($message_title,$message_text) {
	global $TEMPLATE_PATH,$TCONFIG;
	$message_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['message']);

	$message_tpl->values = array(
		'MESSAGE_TITLE'=>$message_title,
		'MESSAGE_TEXT'=>$message_text
	);

	$message_tpl->parse_code(TRUE);
}


//*
//* Zeigt die Navigationsleiste an
//*
function show_navbar($left = '', $center = '', $right = '') {
	global $TEMPLATE_PATH,$TCONFIG,$NAVBAR;

	$navbar_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['navbar']);
	$navbar_tpl->values = array(
		'LEFT'=>$NAVBAR->getArea('left')->parseElements(),
		'CENTER'=>$NAVBAR->getArea('center')->parseElements(),
		'RIGHT'=>$NAVBAR->getArea('right')->parseElements()
	);
	$navbar_tpl->parse_code(TRUE);
}


//*
//* Fuegt einen oder mehrere Links zur Navigationsleiste hinzu
//*
function add_navbar_items() {
	global $NAVBAR;

	$new_items_counter = func_num_args();

	for($i = 0; $i < $new_items_counter; $i++) {
		$cur_element = func_get_arg($i);
		$NAVBAR->addElements('left',$cur_element);
	}
}


//*
//* Formatiert einen UNIX-Timestamp (Zeitstempel) (nur Zeit)
//*
function format_time($timestamp) {
	global $LNG;

	return gmdate($LNG['time_format'],$timestamp);
}


//*
//* Formatiert einen UNIX-Timestamp (Zeitstempel; time()) gemae� dem gewaehlten date()-Format (Datum und Zeit)
//*
function format_date($timestamp,$mode = FALSE) {
	global $LNG,$CONFIG,$USER_LOGGED_IN,$USER_DATA,$TIMEZONES;

	$compare_date = gmdate('d.m.Y',$timestamp);

	if($USER_LOGGED_IN == 1)
		$timestamp += $TIMEZONES[$USER_DATA['user_tz']];
	else
		$timestamp += $TIMEZONES[$CONFIG['standard_tz']];

	if($mode == TRUE)
		return gmdate($LNG['date_format'],$timestamp);

	if(gmdate('d.m.Y') == $compare_date) return gmdate($LNG['today_date_format'],$timestamp);
	if(gmdate('d.m.Y',time()-86400) == $compare_date) return gmdate($LNG['yesterday_date_format'],$timestamp);
	return gmdate($LNG['date_format'],$timestamp);
}


//*
//* Konvertiert einen UNIX-Timestamp (Zeitstempel; time()) in das SQL "timestamp"-Format
//*
function unixtstamp2sqltstamp($timestamp) {
	global $DB;

	return date($DB->TIMESTAMP_FORMAT,$timestamp);

}


//*
//* Konvertiert einen UNIX-Timestamp (Zeitstempel; time()) in das SQL "datetime"-Format
//*
function unixtstamp2sqldatetime($timestamp) {
	global $DB;

	return date($DB->DATETIME_FORMAT,$timestamp);
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

	$chars_search = array('/&(?!\#[0-9]+;)/','/</','/>/','/"/');
	$chars_replace = array('&amp;','&lt;','&gt','&quot;');

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
	global $TCONFIG,$TEMPLATE_PATH,$DB;

	$smilies_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['smiliesbox']);

	//$DB->query("SELECT smiley_id,smiley_gfx,smiley_synonym FROM ".TBLPFX."smilies WHERE smiley_type='0' AND smiley_status='1' LIMIT ".$TCONFIG['smilies_settings']['smiliesbox_maximum_smilies']);
	$smilies_data = cache_get_smilies_data();

	$smilies_counter = count($smilies_data);

	if($smilies_counter > 0) {
		if($smilies_counter > $TCONFIG['smilies_settings']['smiliesbox_maximum_smilies']) $smilies_counter = $TCONFIG['smilies_settings']['smiliesbox_maximum_smilies'];

		for($i = 0; $i < $smilies_counter; $i++) {
			$smilies_tpl->blocks['smileyrow']->blocks['smileycol']->values = array(
				'akt_smiley'=>$smilies_data[$i]
			);
			$smilies_tpl->blocks['smileyrow']->blocks['smileycol']->parse_code(FALSE,TRUE);
			if(($i+1) % $TCONFIG['smilies_settings']['smiliesbox_smilies_per_row'] == 0 && $i != $smilies_counter-1) {
				$smilies_tpl->blocks['smileyrow']->parse_code(FALSE,TRUE);
				$smilies_tpl->blocks['smileyrow']->blocks['smileycol']->reset_tpl();
			}
		}
		$smilies_tpl->blocks['smileyrow']->parse_code(FALSE,TRUE);
	}

	return $smilies_tpl->parse_code();
}


//*
//* Die Auswahl der Beitragsbilder erzeugen
//*
function get_ppics_box($checked_id = 0) {
	global $TCONFIG,$TEMPLATE_PATH,$DB;

	$ppics_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ppicsbox']);

	$DB->query("SELECT smiley_id,smiley_gfx FROM ".TBLPFX."smilies WHERE smiley_type='1'");
	$ppics_data = $DB->raw2array();

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
			if(($i+1) % $TCONFIG['smilies_settings']['ppicsbox_ppics_per_row'] == 0 && $i != $ppics_counter-1) {
				$ppics_tpl->blocks['ppicrow']->parse_code(FALSE,TRUE);
				$ppics_tpl->blocks['ppicrow']->blocks['ppiccol']->reset_tpl();
			}
		}
		$ppics_tpl->blocks['ppicrow']->parse_code(FALSE,TRUE);
	}

	return $ppics_tpl->parse_code();
}


//*
//* Das BBCode-Menue erzeugen (um die Anwendung von BBCode zu erleichtern)
//*
function get_bbcode_box() {
	global $TCONFIG,$TEMPLATE_PATH;

	$bbcode_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['bbcode_buttons']);

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
	global $DB; // Datenbank verfuegbar machen

	$what = implode(', ',$what); // Die einzelnen Rechte fuer den Query vorbereiten

	$DB->query("SELECT $what FROM ".TBLPFX."forums_auth WHERE forum_id='$forum_id' AND auth_type='0' AND auth_id='$user_id'",TRUE); // Rechte eventuell laden
	if($DB->affected_rows > 0) return $DB->fetch_array(); // Falls Rechte gefunden wurden diese zurueckgeben
	else { // Ansonsten muss nach Gruppenrechten gesucht werden
		$group_ids = array(); // Hier stehen spaeter die IDs der Gruppen drin, in denen der User Mitglied ist
		$DB->query("SELECT group_id FROM ".TBLPFX."groups_members WHERE member_id='$user_id'"); // IDs der Gruppen laden, in denen der User Mitglied ist
		if($DB->affected_rows > 0) { // Falls der User in einer Gruppe Mitglied ist
			while(list($akt_group_id) = $DB->fetch_array())
				$group_ids[] = $akt_group_id; // Die ID der Gruppe im Array speichern

			$DB->query("SELECT $what FROM ".TBLPFX."forums_auth WHERE forum_id='$forum_id' AND auth_type='1' AND auth_id IN ('".implode("','",$group_ids)."') LIMIT 1"); // Rechte fuer Gruppen laden, in denen User Mitglied ist, allerdings maximal 1
			if($DB->affected_rows > 0) return $DB->fetch_array(); // Falls Rechte gefunden wurden diese zurueckgeben
		}
	}

	return FALSE; // Es wurden keine Rechte gefunden -> FALSE zurueckgeben
}


//*
//* Die IDs aller Foren laden, zu denen der User Zugriff hat
//*
function get_authed_forums() {
	global $DB,$USER_ID,$USER_DATA,$USER_LOGGED_IN;

	$authed_forums_ids = array(); // Hier stehen spaeter die IDs der Foren drin, zu denen der User Zugriff hat
	if($USER_LOGGED_IN != 1) { // Falls der User nicht eingeloggt
		$DB->query("SELECT forum_id FROM ".TBLPFX."forums WHERE auth_guests_view_forum='1'"); // Die IDs aller Foren laden, zu denen Gaeste Zugriff haben
		while(list($akt_forum_id) = $DB->fetch_array())
			$authed_forums_ids[] = $akt_forum_id;
	}
	else { // Falls der User doch eingeloggt ist
		$not_authed_forums_ids = array(); // Hier stehen spaeter die IDs der Foren drin, zu denen der User KEINEN Zugriff hat

		if($USER_DATA['user_is_admin'] != 1 && $USER_DATA['user_is_supermod'] != 1) { // Falls User weder Admin noch Supermod ist
			$user_group_ids = array(); // Hier stehen spaeter die IDs der Gruppen drin, in denen der User Mitglied ist
			$DB->query("SELECT group_id FROM ".TBLPFX."groups_members WHERE member_id='$USER_ID'"); // IDs aller Gruppen laden, in denen der User Mitglied ist
			while(list($akt_group_id) = $DB->fetch_array())
				$user_group_ids[] = $akt_group_id;


			$DB->query("SELECT forum_id FROM ".TBLPFX."forums_auth WHERE ((auth_type='0' AND auth_id='$USER_ID') OR (auth_type='0' AND auth_id IN ('".implode("','",$user_group_ids)."'))) AND auth_is_mod='0' AND auth_view_forum='0'"); // Die IDs der Foren laden, zu denen der User keinen Zugriff hat
			while(list($akt_forum_id) = $DB->fetch_array())
				$not_authed_forums_ids[] = $akt_forum_id;
		}

		$DB->query("SELECT forum_id FROM ".TBLPFX."forums WHERE forum_id NOT IN ('".implode("','",$not_authed_forums_ids)."')"); // Die IDs der Foren laden zu denen der User nicht keinen Zugriff hat (bzw. zu denen der User Zugriff hat)
		while(list($akt_forum_id) = $DB->fetch_array())
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
	global $LNG;

	$add_headers .= "From: $from\r\n".
		"Reply-To: $from\r\n".
		"Content-type: text/plain; charset=".$LNG['email_encoding'];

	return mail($to,$subject,$message,$add_headers);
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
	global $MYSID,$NAVBAR;

	$navbar_cats_data = cats_get_parent_cats_data($cat_id,$self);

	while(list(,$akt_navbar_cat) = each($navbar_cats_data))
		$NAVBAR->addElements('left',array(myhtmlentities($akt_navbar_cat['cat_name']),"index.php?faction=viewcat&amp;cat_id=".$akt_navbar_cat['cat_id']."&amp;$MYSID"));
}


//*
//* Zerlegt eine Sekundenzahl in Monate (30 Tage), Wochen (7 Tage), Tage, Stunden, Minuten und Sekunden
//*
function split_time($time) {
	$array = array();

	$array['months'] = floor($time/2592000);
	$time -= $array['months']*2592000;
	$array['weeks'] = floor($time/604800);
	$time -= $array['weeks']*604800;
	$array['days'] = floor($time/86400);
	$time -= $array['days']*86400;
	$array['hours'] = floor($time/3600);
	$time -= $array['hours']*3600;
	$array['minutes'] = floor($time/60);
	$time -= $array['minutes']*60;
	$array['seconds'] = $time;

	return $array;
}


//*
//* Ueberprueft, ob ein Benutzer noch gesperrt ist und entfernt die Sperre gegebenenfalls
//*
function check_lock_status($user_id) {
	global $DB;

	$DB->query("SELECT lock_start_time,lock_dur_time FROM ".TBLPFX."users_locks WHERE user_id='$user_id'");
	if($DB->affected_rows > 0) {
		$lock_data = $DB->fetch_array();
		if($lock_data['lock_dur_time'] == 0 || $lock_data['lock_start_time']+$lock_data['lock_dur_time'] > time())
			return TRUE; // Der Benutzer ist noch gesperrt, die Funktion gibt TRUE zurueck und wird beendet
	}

	$DB->query("DELETE FROM ".TBLPFX."users_locks WHERE user_id='$user_id'");
	$DB->query("UPDATE ".TBLPFX."users SET user_is_locked='0' WHERE user_id='$user_id'");

	return FALSE;
}


//*
//* Ueberprueft, ob ein User irgendwo Mod ist
//*
function check_mod_status($user_id) {
	global $DB;
	
	// Erst wird nach einem Mod-Recht des Users gesucht
	$DB->query("SELECT auth_id FROM ".TBLPFX."forums_auth WHERE auth_type='0' AND auth_id='$user_id' AND auth_is_mod='1' LIMIT 1");
	if($DB->affected_rows > 0) return TRUE;
	
	// Nichts gefunden, also muessen die Gruppen ueberprueft werden, in denen der User Mitglied ist
	$DB->query("SELECT group_id FROM ".TBLPFX."groups_members WHERE member_id='$user_id'");
	$group_ids = $DB->raw2fvarray();
	$DB->query("SELECT auth_id FROM ".TBLPFX."forums_auth WHERE auth_type='0' AND auth_id IN ('".implode("','",$group_ids)."') AND auth_is_mod='1' LIMIT 1");
	if($DB->affected_rows > 0) return TRUE;
	
	return FALSE; // User ist kein Mod
}


//*
//* Erstellt die Seitenanzeige
//*
function create_page_listing($entries_counter,$entries_per_page,&$z,$link) {
	global $LNG;

	$pages_counter = ceil($entries_counter/$entries_per_page);

	if($pages_counter == 0) $z = 1;
	elseif($z == 'last' || $z > $pages_counter) $z = $pages_counter;

	$page_listing = array();

	$pre = $suf = '';

	if($pages_counter > 0) {
		if($pages_counter > 5) {
			if($z > 2 && $z < $pages_counter-2) $page_listing = array($z-2,$z-1,$z,$z+1,$z+2);
			elseif($z <= 2) $page_listing = array(1,2,3,4,5);
			elseif($z >= $pages_counter-2) $page_listing = array($pages_counter-4,$pages_counter-3,$pages_counter-2,$pages_counter-1,$pages_counter);
		}
		else {
			for($i = 1; $i < $pages_counter+1; $i++)
				$page_listing[] = $i;
		}
	}
	else $page_listing[] = 1;
	for($i = 0; $i < count($page_listing); $i++) {
		if($page_listing[$i] != $z) $page_listing[$i] = sprintf($link,$page_listing[$i],$page_listing[$i]);//"<a href=\"index.php?faction=memberlist&amp;sort_type=$sort_type&amp;order_type=$order_type&amp;users_per_page=$users_per_page&amp;z=".$page_listing[$i]."&amp;$MYSID\">".$page_listing[$i].'</a>';
	}

	if($z > 1) $pre = sprintf($link,1,$LNG['First_page']).'&nbsp;'.sprintf($link,$z-1,$LNG['Previous_page']).'&nbsp;&nbsp;'; //'<a href="index.php?faction=memberlist&amp;sort_type='.$sort_type.'&amp;order_type='.$order_type.'&amp;users_per_page='.$users_per_page.'&amp;z=1&amp;'.$MYSID.'">'.$LNG['First_page'].'</a>&nbsp;<a href="index.php?faction=memberlist&amp;sort_type='.$sort_type.'&amp;order_type='.$order_type.'&amp;users_per_page='.$users_per_page.'&amp;z='.($z-1).'&amp;'.$MYSID.'">'.$LNG['Previous_page'].'</a>&nbsp;&nbsp;';
	if($z < $pages_counter) $suf = '&nbsp;&nbsp;'.sprintf($link,$z+1,$LNG['Next_page']).'&nbsp;'.sprintf($link,'last',$LNG['Last_page']); //'&nbsp;&nbsp;<a href="index.php?faction=memberlist&amp;sort_type='.$sort_type.'&amp;order_type='.$order_type.'&amp;users_per_page='.$users_per_page.'&amp;z='.($z+1).'&amp;'.$MYSID.'">'.$LNG['Next_page'].'</a>&nbsp;<a href="index.php?faction=memberlist&amp;sort_type='.$sort_type.'&amp;order_type='.$order_type.'&amp;users_per_page='.$users_per_page.'&amp;z=last&amp;'.$MYSID.'">'.$LNG['Last_page'].'</a>';

	return sprintf($LNG['Pages'],$pages_counter,$pre.implode(' | ',$page_listing).$suf);
}

?>