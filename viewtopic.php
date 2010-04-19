<?php
/**
*
* Tritanium Bulletin Board 2 - viewtopic.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');
require_once('bbcode.php');

$topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : 0; // ID des Themas
$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : 0; // ID des Beitrags
$z = isset($_GET['z']) ? $_GET['z'] : 1; // Seite

if(isset($post_ids_counter)) unset($post_ids_counter); // Diese Variable loeschen, falls sie schon existiert (aus Sicherheitsgruenden)


//
// Thema und Seite eventuell ueber Beitrags-ID bestimmen
//
if($topic_id == 0) {
	$db->query("SELECT topic_id FROM ".TBLPFX."posts WHERE post_id='$post_id'"); // Laedt eventuell die ID des Themas
	if($db->affected_rows != 1) die('Kann Beitragsdaten nicht laden/Beitrag existiert nicht!'); // Falls nicht Meldung ausgeben
	list($topic_id) = $db->fetch_array(); // ID des Themas verfuegbar machen

	$db->query("SELECT post_id FROM ".TBLPFX."posts WHERE topic_id='$topic_id' ORDER BY post_time"); // Die IDs aller Beitraege des Themas laden
	$post_ids = $db->raw2array(); // DB-Daten in Array umwandeln
	$post_ids_counter = count($post_ids); // Anzahl der IDs (Beitraege)

	$z = 1; // Standardseite ist Seite 1
	for($i = 0; $i < $post_ids_counter; $i++) {
		if($post_ids[$i]['post_id'] == $post_id) break; // Falls die gewunschte ID gefunden wurde kann die Schleife beendet werden (damit ist die Seite gefunden)
		if(($i + 1) % $CONFIG['posts_per_page'] == 0) $z++; // Falls die Anzahl der Beitraege pro Seite erreicht wurde, naechste Seite angeben
	}
}


//
// Thema- und Forumdaten laden
//
if(!$topic_data = get_topic_data($topic_id)) die('Kann Themendaten nicht laden/Thema existiert nicht!'); // Themendaten laden
if($topic_data['topic_moved_id'] != 0 && ($topic_data = get_topic_data($topic_data['topic_moved_id'])) == FALSE) die('Thema wurde verschoben/kann neues Thema nicht laden!'); // Falls das Thema verschoben wurde und die neuen Daten nicht gefunden werden koennen
elseif(!$forum_data = get_forum_data($topic_data['forum_id'])) die('Kann Forendaten nicht laden/Forum existiert nicht!'); // Forendaten laden

$topic_id = $topic_data['topic_id']; // ID des Themas, ist wichtig, falls es ein verschobenes Thema ist
$forum_id = $topic_data['forum_id']; // ID des Forums


//
// User-IDs aller Moderatoren laden
//
$forum_mod_ids = array();
$db->query("SELECT auth_id FROM ".TBLPFX."forums_auth WHERE auth_type='0' AND forum_id='$forum_id' AND auth_is_mod='1'");
while(list($akt_user_id) = $db->fetch_array())
	$forum_mod_ids[] = $akt_user_id;

$forum_mod_group_ids = array();
$db->query("SELECT auth_id FROM ".TBLPFX."forums_auth WHERE auth_type='1' AND forum_id='$forum_id' AND auth_is_mod='1'");
while(list($akt_group_id) = $db->fetch_array())
	$forum_mod_group_ids[] = $akt_group_id;

if(count($forum_mod_group_ids) > 0) {
	$db->query("SELECT member_id FROM ".TBLPFX."groups_members WHERE group_id IN ('".implode("','",$forum_mod_group_ids)."') GROUP BY member_id");
	while(list($akt_user_id) = $db->fetch_array())
		$forum_mod_ids[] = $akt_user_id;
}
unset($forum_mod_group_ids);


//
// Beginn Authentifizierung
//
if($USER_LOGGED_IN != 1) {
	if($forum_data['auth_guests_view_forum'] != 1) {
		include_once('pheader.php');
		show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Not_logged_in']);
		show_message('Not_logged_in','message_forum_not_logged_in','<br />'.$lng['click_here_login'].'<br />'.$lng['click_here_register']);
		include_once('ptail.php'); exit;
	}

	$auth_data = array(
			'auth_view_forum'=>$forum_data['auth_guests_view_forum'],
			'auth_edit_posts'=>0,
			'auth_is_mod'=>0
	);
}
elseif($USER_DATA['user_is_admin'] != 1) {
	if(!$auth_data = get_auth_forum_user($forum_id,$USER_ID,array('auth_view_forum','auth_edit_posts','auth_is_mod'))) {
		$auth_data = array(
			'auth_view_forum'=>$forum_data['auth_members_view_forum'],
			'auth_edit_posts'=>$forum_data['auth_members_edit_posts'],
			'auth_is_mod'=>0
		);
	}
	if($auth_data['auth_is_mod'] != 1) {
		if($forum_data['auth_members_view_forum'] != 1 && $auth_data['auth_view_forum'] != 1 || $forum_data['auth_members_view_forum'] == 1 && $auth_data['auth_view_forum'] == 0) {
			include_once('pheader.php');
			show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['No_access']);
			show_message('No_access','message_forum_no_access');
			include_once('ptail.php'); exit;
		}
	}
}
//
// Ende Authentifizierung
//


update_topic_cookie($forum_id,$topic_id,time());

if(!isset($_SESSION['topic_views'][$topic_id])) { // Falls dieses Thema in dieser Session noch nicht besucht wurde...
	$db->query("UPDATE ".TBLPFX."topics SET topic_views_counter=topic_views_counter+1 WHERE topic_id='$topic_id'"); // ...Anzahl der Views um 1 erhoehen...
	$_SESSION['topic_views'][$topic_id] = TRUE; // ...Und Thema in dieser Session vermerken
}


//
// Seitenanzeige erstellen
//
$posts_counter = isset($post_ids_counter) ? $post_ids_counter : get_topic_posts_counter($topic_id); // Anzahl der Beitraege bestimmen (kann eventuell aus schon vorhandenen Daten geschehen)
$page_counter = ceil($posts_counter/$CONFIG['posts_per_page']); // Anzahl der Seiten

if($z == 'last') $z = $page_counter; // Falls die Seite die letzte sein soll, das auch so festsetzen

$start = $z*$CONFIG['posts_per_page']-$CONFIG['posts_per_page']; // Startbeitrag
$page_listing = array(); // Beinhaltet spaeter die Nummern der Seite

$pre = $suf = ''; // Wird spaeter vor bzw. hinter die Seitenanzeige gehaengt

if($page_counter > 0) { // Falls eine Seite existiert
	if($page_counter > 5) { // Falls es mehr als 5 Seiten sind
		if($z > 2 && $z < $page_counter-2) // Falls die Seite groesser als 2 ist und kleiner als die Seitenanzahl-2
			$page_listing = array($z-2,$z-1,$z,$z+1,$z+2);
		elseif($z <= 2) // Falls die Seite kleiner oder gleich 2 ist
			$page_listing = array(1,2,3,4,5);
		elseif($z >= $page_counter-2) // Falls die Seite groesser oder gleich der Seitenanzahl-2 ist
			$page_listing = array($page_counter-4,$page_counter-3,$page_counter-2,$page_counter-1,$page_counter);
	}
	else { // Andernfalls...
		for($i = 1; $i < $page_counter+1; $i++) // ...die Seitennummer von 1-x erstellen (x <=5)
			$page_listing[] = $i;
	}
}
else $page_listing[] = 1; // Andernfalls gibt es nur Seite 1

for($i = 0; $i < count($page_listing); $i++) {
	if($page_listing[$i] != $z) $page_listing[$i] = "<a href=\"index.php?faction=viewtopic&amp;topic_id=$topic_id&amp;z=".$page_listing[$i]."&amp;$MYSID\">".$page_listing[$i].'</a>'; // Falls die Seitennummer nicht die aktuelle Seite ist, Nummer in Link umwandeln
}

if($z > 1) $pre = '<a href="index.php?faction=viewtopic&amp;topic_id='.$topic_id.'&amp;z=1&amp;'.$MYSID.'">&#171;</a>&nbsp;<a href="index.php?faction=viewtopic&amp;topic_id='.$topic_id.'&amp;z='.($z-1).'&amp;'.$MYSID.'">&#8249;</a>&nbsp;&nbsp;'; // Falls die Seite groesser als 1 ist, die entsprechenden Links angeben (erste. Seite / eine Seite zurueck)
if($z < $page_counter) $suf = '&nbsp;&nbsp;<a href="index.php?faction=viewtopic&amp;topic_id='.$topic_id.'&z='.($z+1).'&'.$MYSID.'">&#8250;</a>&nbsp;<a href="index.php?faction=viewtopic&amp;topic_id='.$topic_id.'&amp;z=last&amp;'.$MYSID.'">&#187;</a>'; // Falls die Seite kleiner der Gesamtseitenzahl ist, entsprechende Links angeben (eine Seite vor / letzte Seite)

$page_listing = sprintf($lng['Pages'],$pre.implode(' | ',$page_listing).$suf); // Die Seitenansicht erstellen


//
// Template laden
//
$viewtopic_tpl = new template; // Neue Templateklasse erzeugen
$viewtopic_tpl->load($template_path.'/'.$tpl_config['tpl_viewtopic']); // Template laden


//
// Die Moderatorenwerkzeuge bestimmen
//
$modtools = array(); // Beinhaltet spaeter pro Element eine Moderationsoption
if($USER_DATA['user_is_admin'] == 1 || $topic_data['poster_id'] != 0 && $USER_ID == $topic_data['poster_id'] && $auth_data['auth_edit_posts'] == 1 || $auth_data['auth_is_mod'] == 1) $modtools[] = "<a href=\"index.php?faction=edittopic&amp;mode=edit&amp;topic_id=$topic_id&amp;$MYSID\">".$lng['Edit_topic'].'</a>'; // Thema bearbeiten (duerfen auch User, die das Thema erstellt haben)
if($USER_DATA['user_is_admin'] == 1 || $auth_data['auth_is_mod'] == 1) {
	$modtools[] = "<a href=\"index.php?faction=edittopic&amp;mode=move&amp;topic_id=$topic_id&amp;$MYSID\">".$lng['Move_topic'].'</a>';
	$modtools[] = "<a href=\"index.php?faction=edittopic&amp;mode=delete&amp;topic_id=$topic_id&amp;$MYSID\">".$lng['Delete_topic'].'</a>';

	$temp = ($topic_data['topic_is_pinned'] == 1) ? $lng['Mark_topic_unimportant'] : $lng['Mark_topic_important'];
	$modtools[] = "<a href=\"index.php?faction=edittopic&amp;mode=pinn&amp;topic_id=$topic_id&amp;$MYSID\">".$temp.'</a>';

	$temp = ($topic_data['topic_status'] == 1) ? $lng['Open_topic'] : $lng['Close_topic'];
	$modtools[] = "<a href=\"index.php?faction=edittopic&amp;mode=openclose&amp;topic_id=$topic_id&amp;$MYSID\">".$temp.'</a>';
}
if(count($modtools) > 0) { // Falls es mindestens ein Moderationswerkzeug gibt...
	$modtools = implode($tpl_config['separation_char'],$modtools);
	$viewtopic_tpl->blocks['modtools']->parse_code(); // ...Templateblock erzeugen
}
else $viewtopic_tpl->unset_block('modtools'); // Andernfalls Templateblock loeschen


//
// Die Umfrage
//
$poll_box = ''; // Beinhaltet spaeter den HTML-Code fuer die Umfragebox
if($topic_data['topic_poll'] == 1) { // Falls fuer das Thema eine Umfrage angegeben wurde...
	$db->query("SELECT * FROM ".TBLPFX."polls WHERE topic_id='$topic_id'"); // ...versuchen die Daten der Umfrage zu laden...
	if($poll_data = $db->fetch_array()) { // ...und falls diese existiert...
		$poll_tpl = new template; // ...neue Templateklasse fuer die Umfragebox erzeugen

		if($USER_LOGGED_IN == 1) { // Falls User eingeloggt ist
			$db->query("SELECT voter_id FROM ".TBLPFX."polls_voters WHERE poll_id='".$poll_data['poll_id']."' AND voter_id='$USER_ID'"); // Ueberpruefen, ob User shcon abgestimmt hat...
			if($db->affected_rows == 0) // ...falls nicht...
				$poll_tpl->load($template_path.'/'.$tpl_config['tpl_viewtopic_poll_voting']); // ...Abstimmungsboxtemplate laden...
			else { // ...andernfalls...
				$poll_tpl->load($template_path.'/'.$tpl_config['tpl_viewtopic_poll_results']); // ... Ergebnisboxtemplate laden...
				$info_text = $lng['poll_already_voted_info']; // ...und Infotext fuer "schon abgestimmt" erzeugen
			}
		}
		else { // Falls User nicht eingeloggt ist...
			$poll_tpl->load($template_path.'/'.$tpl_config['tpl_viewtopic_poll_results']); // ...Ergebnisboxtemplate laden...
			$info_text = $lng['poll_not_logged_in_info']; // ...und Infotext fuer "nicht eingeloggt" erzeugen
		}

		$db->query("SELECT option_id,option_title,option_votes FROM ".TBLPFX."polls_options WHERE poll_id='".$poll_data['poll_id']."' ORDER BY option_id"); // Die Auswahlmoeglichkeiten fuer die Umfrage laden
		while($akt_option = $db->fetch_array()) {
			$akt_fraction = ($poll_data['poll_votes'] == 0) ? 0 : round($akt_option['option_votes']/$poll_data['poll_votes'],2); // Der Anteil an Stimmen (0,xx)
			$akt_percent = $akt_fraction*100; // Stimmenanteil in Prozent
			$akt_votes = ($akt_option['option_votes'] == 1) ? $lng['one_vote'] : sprintf($lng['x_votes'],$akt_option['option_votes']); // Anzahl der Stimmen
			$akt_checked = ($akt_option['option_id'] == 1) ? ' checked="checked"' : ''; // checked="checked" fuer den ersten Radiobutton erzeugen (damit auf jeden Fall was ausgewaehlt ist)

			$poll_tpl->blocks['optionrow']->parse_code(FALSE,TRUE); // Templateblock guer Auswahlmoeglichkeit erzeugen
		}

		$poll_box = $poll_tpl->parse_code(); // Template fuer Umfragebox erzeugen und in $poll_bock speichern
		unset($poll_tpl); // Variable loeschen (wird auf keinen Fall wieder benoetigt
	}
}


//
// Smilies und Beitragsbilder laden
//
$smilies = array(); // Beinhaltet spaeter die Smilies mit dem Synonym als Key und dem Bild (HTML) als Wert
if($forum_data['forum_enable_smilies'] == 1 ||$CONFIG['enable_sig'] == 1 && $CONFIG['allow_sig_smilies'] == 1) {
	$db->query("SELECT smiley_gfx,smiley_synonym FROM ".TBLPFX."smilies WHERE smiley_type='0'"); // Daten aller Smilies laden
	while($akt_smiley = $db->fetch_array())
		$smilies[$akt_smiley['smiley_synonym']] = '<img src="'.$akt_smiley['smiley_gfx'].'" border="0" alt="'.$akt_smiley['smiley_synonym'].'" />'; // Smiley in Array einfuegen
}

$db->query("SELECT * FROM ".TBLPFX."smilies WHERE smiley_type='1'"); // Daten aller Beitragsbilder laden
$ppics_data = $db->raw2array(); // DB-Daten in Array umwandeln


//
// Rangdaten laden
//
$ranks_data = get_ranks_data();


$parsed_signatures = array(); // Hier werden spaeter eventuell die geparsten Signaturen gespeichert um das nicht mehrfach machen zu muessen


//
// Beitraege anzeigen
//
$db->query("SELECT t1.post_id, t1.poster_id, t1.post_pic, t1.post_enable_bbcode, t1.post_enable_smilies, t1.post_enable_html, t1.post_show_sig, t1.post_guest_nick, UNIX_TIMESTAMP(t1.post_time) AS post_time, t1.post_edited_counter, t1.post_last_editor_id, t3.post_title, t3.post_text, t2.user_hp AS poster_hp, t2.user_email AS poster_email, t2.user_nick AS poster_nick, t2.user_signature AS poster_signature, t2.user_is_admin AS poster_is_admin, t2.user_posts AS poster_posts, t2.user_rank_id AS poster_rank_id, t2.user_avatar_address AS poster_avatar_address, t4.user_nick AS post_last_editor_nick FROM ".TBLPFX."posts AS t1, ".TBLPFX."posts_text AS t3 LEFT JOIN ".TBLPFX."users AS t2 ON t1.poster_id=t2.user_id LEFT JOIN ".TBLPFX."users AS t4 ON t1.post_last_editor_id=t4.user_id WHERE t1.topic_id='$topic_id' AND t3.post_id=t1.post_id ORDER BY t1.post_time LIMIT $start,".$CONFIG['posts_per_page']);
while($akt_post = $db->fetch_array()) {

	$akt_edited_text = '';
	if($akt_post['post_edited_counter'] > 0) {
		$akt_last_editor_nick = ($akt_post['post_last_editor_id'] == 0) ? $lng['Deleted_user'] : $akt_post['post_last_editor_nick'];
		$akt_edited_text = sprintf($lng['edited_post_text'],$akt_post['post_edited_counter'],$akt_last_editor_nick);
	}

	$edit_button = $delete_button = $user_email_button = $user_hp_button = '';

	if($USER_LOGGED_IN == 1) {
		if($USER_DATA['user_is_admin'] == 1 || $auth_data['auth_is_mod'] == 1 || (($forum_data['auth_members_edit_posts'] == 1 && $auth_data['auth_edit_posts'] == 1 || $forum_data['auth_members_edit_posts'] != 1 && $auth_data['auth_edit_posts'] == 1) && $USER_ID == $akt_post['poster_id'])) {
			$edit_button = "<a href=\"index.php?faction=editpost&amp;post_id=".$akt_post['post_id']."&amp;mode=edit&amp;$MYSID\"><img src=\"$template_path/".$tpl_config['img_edit_post']."\" alt=\"".$lng['Edit_post']."\" border=\"0\" /></a>";
			if($akt_post['post_id'] != $topic_data['topic_first_post_id'])
				$delete_button = "<a href=\"index.php?faction=editpost&amp;post_id=".$akt_post['post_id']."&amp;mode=delete&amp;$MYSID\"><img src=\"$template_path/".$tpl_config['img_delete_post']."\" alt=\"".$lng['Delete_post']."\" border=\"0\" /></a>";
		}
	}

	$quote_button = "<a href=\"index.php?faction=postreply&amp;topic_id=$topic_id&amp;quote=".$akt_post['post_id']."&amp;$MYSID\"><img src=\"$template_path/".$tpl_config['img_quote_post']."\" alt=\"".$lng['Quote_post']."\" border=\"0\" /></a>";

	if($akt_post['poster_hp'] != '') $user_hp_button = '<a target="_blank" href="'.$akt_post['poster_hp'].'"><img src="'.$template_path.'/'.$tpl_config['img_user_hp'].'" alt="'.$akt_post['poster_hp'].'" border="0" /></a>';
	if($akt_post['poster_email'] != '') $user_email_button = '<a href="mailto:'.$akt_post['poster_email'].'"><img src="'.$template_path.'/'.$tpl_config['img_user_email'].'" alt="'.$akt_post['poster_email'].'" border="0" /></a>';

	$akt_post_pic = '';
	if($akt_post['post_pic'] != 0) {
		while(list(,$akt_ppic) = each($ppics_data)) {
			if($akt_ppic['smiley_id'] == $akt_post['post_pic']) {
				$akt_post_pic = '<img src="'.$akt_ppic['smiley_gfx'].'" alt="" />';
				break;
			}
		}
		reset($ppics_data);
	}

	$post_tools = array();
	$post_tools[] = "<a href=\"index.php?faction=postreply&amp;topic_id=$topic_id&amp;quote=".$akt_post['post_id']."&amp;$MYSID\">".$lng['Quote_post'].'</a>';
	if($USER_LOGGED_IN == 1) {
		if($akt_post['poster_id'] == $USER_ID || $USER_DATA['user_is_admin'] == 1) {
			$post_tools[] = "<a href=\"index.php?faction=editpost&amp;mode=edit&amp;topic_id=$topic_id&amp;post_id=".$akt_post['post_id']."&amp;$MYSID\">".$lng['Edit_post'].'</a>';
			if($topic_data['topic_first_post_id'] != $akt_post['post_id']) $post_tools[] = "<a href=\"index.php?faction=deletepost&amp;mode=edit&amp;topic_id=$topic_id&amp;post_id=".$akt_post['post_id']."&amp;$MYSID\">".$lng['Delete_post'].'</a>';
		}
	}

	$post_tools = implode(' | ',$post_tools);

	$akt_post_date = format_date($akt_post['post_time']);



	//
	// Angaben ueber den Beitragsersteller
	//
	$akt_poster_nick = $akt_poster_rank_text = $akt_poster_rank_pic = $akt_poster_id = $akt_poster_avatar = '';
	if($akt_post['poster_id'] == 0) { // Falls der Poster Gast bzw. ein geloeschter User ist...
		$akt_poster_nick = $akt_post['post_guest_nick']; // ...seinen Nick auf den Gastnick setzen...
		$akt_poster_rank_text = $lng['Guest']; // ...und seinen Rang auf "Gast" setzen
	}
	else { // Andernfalls (also wenn der User nicht geloescht bzw. Gast ist)
		$akt_poster_nick = '<a href="index.php?faction=viewprofile&amp;profile_id='.$akt_post['poster_id'].'&amp;'.$MYSID.'">'.$akt_post['poster_nick'].'</a>'; // Den Nick mit einem Link zum Profil versehen
		$akt_poster_id = sprintf($lng['ID_x'],$akt_post['poster_id']); // Die ID mit einem kleinen "Text" versehen


		//
		// Avatar
		//
		if($CONFIG['enable_avatars'] == 1 && $akt_post['poster_avatar_address'] != '')
			$akt_poster_avatar = '<img src="'.$akt_post['poster_avatar_address'].'" alt="" border="0" width="'.$CONFIG['avatar_image_width'].'" height="'.$CONFIG['avatar_image_height'].'" />';


		//
		// Rangbild und Rangtext des Users festlegen
		//
		if($akt_post['poster_rank_id'] != 0) { // Falls der User einen speziellen Rang zugewiessen bekommen hat...
			$akt_poster_rank_text = $ranks_data[1][$akt_post['poster_rank_id']]['rank_name']; // ...den Namen des Rang verwenden...
			$akt_poster_rank_pic = $ranks_data[1][$akt_post['poster_rank_id']]['rank_gfx']; // ...und das Bild des Rangs verwenden
		}
		elseif($akt_post['poster_is_admin'] == 1) { // Falls der User Admnistrator ist...
			$akt_poster_rank_text = $lng['Administrator']; // ...seinen Rang darauf setzen...
			$akt_poster_rank_pic = '<img src="'.$CONFIG['admin_rank_pic'].'" alt="" border="0" />'; // ...und das entsprechende Bild verwenden
		}
		elseif(in_array($akt_post['poster_id'],$forum_mod_ids) == TRUE) { // Falls der User Moderator ist...
			$akt_poster_rank_text = $lng['Moderator']; // ...seinen Rang darauf setzen...
			$akt_poster_rank_pic = '<img src="'.$CONFIG['mod_rank_pic'].'" alt="" border="0" />'; // ...und das entsprechende Bild verwenden
		}
		else { // Falls der User ein ganz normaler User ist...
			while(list(,$akt_rank) = each($ranks_data[0])) { // Die Rangliste durchlaufen
				if($akt_rank['rank_posts'] <= $akt_post['poster_posts']) { // Falls der User soviele Beitraege hat, wie es der aktuelle Rang erfordert...
					$akt_poster_rank_text = $akt_rank['rank_name']; // ...den Namen das Rangs verwenden...
					$akt_poster_rank_pic = $akt_rank['rank_gfx']; // ...und das Bild des Rangs verwenden
				}
				else // Andernfalls...
					break; // ...die Schleife beenden, da der User fuer weitere Raenge auf jeden Fall noch mehr Beitraege benoetigen wuerde
			}
			reset($ranks_data[0]); // Das Array fuer den naechsten User vorbereiten
		}
	}


	//
	// Den Beitrag entsprechend formatieren
	//
	if($akt_post['post_enable_html'] != 1 || $forum_data['forum_enable_htmlcode'] != 1) $akt_post['post_text'] = myhtmlentities($akt_post['post_text']); //array_merge($strtr_array,$html_schars_table);
	if($akt_post['post_enable_smilies'] == 1 && $forum_data['forum_enable_smilies'] == 1) $akt_post['post_text'] = strtr($akt_post['post_text'],$smilies);
	$akt_post['post_text'] = nlbr($akt_post['post_text']);
	if($akt_post['post_enable_bbcode'] == 1 && $forum_data['forum_enable_bbcode'] == 1) $akt_post['post_text'] = bbcode($akt_post['post_text']);


	//
	// Die Signatur entsprechend formatieren
	//
	if($akt_post['post_show_sig'] == 1 && $CONFIG['enable_sig'] == 1 && $akt_post['poster_signature'] != '') {
		if(!isset($parsed_signatures[$akt_post['poster_id']])) { // Falls die Signatur nicht schonmal formatiert wurde
			if($CONFIG['allow_sig_html'] != 1) $parsed_signatures[$akt_post['poster_id']] = myhtmlentities($akt_post['poster_signature']);
			if($CONFIG['allow_sig_smilies'] == 1) $parsed_signatures[$akt_post['poster_id']] = strtr($parsed_signatures[$akt_post['poster_id']],$smilies);
			$parsed_signatures[$akt_post['poster_id']] = nlbr($parsed_signatures[$akt_post['poster_id']]);
			if($CONFIG['allow_sig_bbcode'] == 1) $parsed_signatures[$akt_post['poster_id']] = bbcode($parsed_signatures[$akt_post['poster_id']]);
		}
		$akt_post_signature = $parsed_signatures[$akt_post['poster_id']];

		$viewtopic_tpl->blocks['postrow']->blocks['signature']->parse_code();
	}
	else $viewtopic_tpl->blocks['postrow']->blocks['signature']->blank_tpl();

	$viewtopic_tpl->blocks['postrow']->parse_code(FALSE,TRUE);
	$tpl_config['akt_class'] = ($tpl_config['akt_class'] == $tpl_config['td1_class']) ? $tpl_config['td2_class'] : $tpl_config['td1_class'];
}

$title_add[] = $forum_data['forum_name'];
$title_add[] = $topic_data['topic_title'];

$navbar_right = '';
if($USER_LOGGED_IN == 1 && $CONFIG['enable_email_functions'] == 1 && $CONFIG['enable_topic_subscription'] == 1) {
	$db->query("SELECT user_id FROM ".TBLPFX."topics_subscriptions WHERE topic_id='$topic_id' AND user_id='$USER_ID'");
	$subscribe_text = ($db->affected_rows == 0) ? $lng['Subscribe_topic'] : $lng['Unsubscribe_topic'];

	$navbar_right = '<a href="index.php?faction=subscribetopic&amp;topic_id='.$topic_id.'&amp;z='.$z.'&amp;'.$MYSID.'">'.$subscribe_text.'</a>';
}


$navbar_cats = get_navbar_cats($forum_data['cat_id']); // Die Anzeige der Kategorien fuer die Navigationsleiste (wird hier schon geladen, da es gleich zwei mal benoetigt wird)

include_once('pheader.php'); // Seitenkopf ausgeben
show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>".$navbar_cats."\r<a href=\"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID\">".myhtmlentities($forum_data['forum_name'])."</a>\r".myhtmlentities($topic_data['topic_title']),'',$navbar_right); // Navbar anzeigen
$viewtopic_tpl->parse_code(TRUE); // Seite ausgeben
show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>".$navbar_cats."\r<a href=\"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID\">".myhtmlentities($forum_data['forum_name'])."</a>\r".myhtmlentities($topic_data['topic_title']),'',$navbar_right); // Navbar anzeigen
include_once('ptail.php'); // Seitenende ausgeben

?>