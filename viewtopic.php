<?php
/**
*
* Tritanium Bulletin Board 2 - viewtopic.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
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
	$DB->query("SELECT topic_id FROM ".TBLPFX."posts WHERE post_id='$post_id'"); // Laedt eventuell die ID des Themas
	if($DB->affected_rows != 1) die('Kann Beitragsdaten nicht laden/Beitrag existiert nicht!'); // Falls nicht Meldung ausgeben
	list($topic_id) = $DB->fetch_array(); // ID des Themas verfuegbar machen

	$DB->query("SELECT post_id FROM ".TBLPFX."posts WHERE topic_id='$topic_id' ORDER BY post_time"); // Die IDs aller Beitraege des Themas laden
	$post_ids = $DB->raw2array(); // DB-Daten in Array umwandeln
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
$DB->query("SELECT auth_id FROM ".TBLPFX."forums_auth WHERE auth_type='0' AND forum_id='$forum_id' AND auth_is_mod='1'");
while(list($akt_user_id) = $DB->fetch_array())
	$forum_mod_ids[] = $akt_user_id;

$DB->query("SELECT t2.member_id FROM ".TBLPFX."forums_auth AS t1, ".TBLPFX."groups_members AS t2 WHERE t1.forum_id='$forum_id' AND t1.auth_is_mod=1 AND t1.auth_type=1 AND t2.group_id=t1.auth_id GROUP BY t2.member_id");
while(list($akt_user_id) = $DB->fetch_array())
	$forum_mod_ids[] = $akt_user_id;

$forum_mod_ids = array_unique($forum_mod_ids);


//
// Beginn Authentifizierung
//
if($USER_LOGGED_IN != 1) {
	if($forum_data['auth_guests_view_forum'] != 1) {
		add_navbar_items(array($LNG['Not_logged_in'],''));

		include_once('pheader.php');
		show_message($LNG['Not_logged_in'],$LNG['message_forum_not_logged_in'].'<br />'.$LNG['click_here_login'].'<br />'.$LNG['click_here_register']);
		include_once('ptail.php'); exit;
	}

	$auth_data = array(
			'auth_view_forum'=>$forum_data['auth_guests_view_forum'],
			'auth_edit_posts'=>0,
			'auth_is_mod'=>0
	);
}
elseif($USER_DATA['user_is_admin'] != 1 && $USER_DATA['user_is_supermod'] != 1) {
	if(!$auth_data = get_auth_forum_user($forum_id,$USER_ID,array('auth_view_forum','auth_edit_posts','auth_is_mod'))) {
		$auth_data = array(
			'auth_view_forum'=>$forum_data['auth_members_view_forum'],
			'auth_edit_posts'=>$forum_data['auth_members_edit_posts'],
			'auth_is_mod'=>0
		);
	}
	if($auth_data['auth_is_mod'] != 1) {
		if($forum_data['auth_members_view_forum'] != 1 && $auth_data['auth_view_forum'] != 1 || $forum_data['auth_members_view_forum'] == 1 && $auth_data['auth_view_forum'] == 0) {
			add_navbar_items(array($LNG['No_access'],''));

			include_once('pheader.php');
			show_message($LNG['No_access'],$LNG['message_forum_no_access']);
			include_once('ptail.php'); exit;
		}
	}
}
//
// Ende Authentifizierung
//


update_topic_cookie($forum_id,$topic_id,time());

if(!isset($_SESSION['topic_views'][$topic_id])) { // Falls dieses Thema in dieser Session noch nicht besucht wurde...
	$DB->query("UPDATE ".TBLPFX."topics SET topic_views_counter=topic_views_counter+1 WHERE topic_id='$topic_id'"); // ...Anzahl der Views um 1 erhoehen...
	$_SESSION['topic_views'][$topic_id] = TRUE; // ...Und Thema in dieser Session vermerken
}


//
// Seitenanzeige erstellen
//
$posts_counter = isset($post_ids_counter) ? $post_ids_counter : get_topic_posts_counter($topic_id); // Anzahl der Beitraege bestimmen (kann eventuell aus schon vorhandenen Daten geschehen)

$page_listing = create_page_listing($posts_counter,$CONFIG['posts_per_page'],$z,"<a href=\"index.php?faction=viewtopic&amp;topic_id=$topic_id&amp;z=%1\$s&amp;$MYSID\">%2\$s</a>"); //sprintf($LNG['Pages'],$page_counter,$pre.implode(' | ',$page_listing).$suf); // Die Seitenansicht erstellen
$start = $z*$CONFIG['posts_per_page']-$CONFIG['posts_per_page']; // Startbeitrag


//
// Template laden
//
$viewtopic_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewtopic']); // Neue Templateklasse erzeugen


//
// Die Moderatorenwerkzeuge bestimmen
//
$modtools = array(); // Beinhaltet spaeter pro Element eine Moderationsoption
if($USER_DATA['user_is_admin'] == 1 || $USER_DATA['user_is_supermod'] == 1 || $topic_data['poster_id'] != 0 && $USER_ID == $topic_data['poster_id'] && $auth_data['auth_edit_posts'] == 1 || $auth_data['auth_is_mod'] == 1) $modtools[] = "<a href=\"index.php?faction=edittopic&amp;mode=edit&amp;topic_id=$topic_id&amp;$MYSID\">".$LNG['Edit_topic'].'</a>'; // Thema bearbeiten (duerfen auch User, die das Thema erstellt haben)
if($USER_DATA['user_is_admin'] == 1 || $USER_DATA['user_is_supermod'] == 1 || $auth_data['auth_is_mod'] == 1) {
	$modtools[] = "<a href=\"index.php?faction=edittopic&amp;mode=move&amp;topic_id=$topic_id&amp;$MYSID\">".$LNG['Move_topic'].'</a>';
	$modtools[] = "<a href=\"index.php?faction=edittopic&amp;mode=delete&amp;topic_id=$topic_id&amp;$MYSID\">".$LNG['Delete_topic'].'</a>';

	$temp = ($topic_data['topic_is_pinned'] == 1) ? $LNG['Mark_topic_unimportant'] : $LNG['Mark_topic_important'];
	$modtools[] = "<a href=\"index.php?faction=edittopic&amp;mode=pinn&amp;topic_id=$topic_id&amp;$MYSID\">".$temp.'</a>';

	$temp = ($topic_data['topic_status'] == 1) ? $LNG['Open_topic'] : $LNG['Close_topic'];
	$modtools[] = "<a href=\"index.php?faction=edittopic&amp;mode=openclose&amp;topic_id=$topic_id&amp;$MYSID\">".$temp.'</a>';
}
if(count($modtools) > 0) { // Falls es mindestens ein Moderationswerkzeug gibt...
	$modtools = implode($TCONFIG['basic_info']['separation_char'],$modtools);
	$viewtopic_tpl->blocks['modtools']->parse_code(); // ...Templateblock erzeugen
}


//
// Die Umfrage
//
$poll_box = ''; // Beinhaltet spaeter den HTML-Code fuer die Umfragebox
if($topic_data['topic_poll'] == 1) { // Falls fuer das Thema eine Umfrage angegeben wurde...
	$DB->query("SELECT * FROM ".TBLPFX."polls WHERE topic_id='$topic_id'"); // ...versuchen die Daten der Umfrage zu laden...
	if($poll_data = $DB->fetch_array()) { // ...und falls diese existiert...
		if($USER_LOGGED_IN == 1) { // Falls User eingeloggt ist
			$DB->query("SELECT voter_id FROM ".TBLPFX."polls_votes WHERE poll_id='".$poll_data['poll_id']."' AND voter_id='$USER_ID'"); // Ueberpruefen, ob User shcon abgestimmt hat...
			if($DB->affected_rows == 0) // ...falls nicht...
				$poll_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewtopic_poll_voting']); // ...Abstimmungsboxtemplate laden...
			else { // ...andernfalls...
				$poll_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewtopic_poll_results']); // ... Ergebnisboxtemplate laden...
				$info_text = $LNG['poll_already_voted_info']; // ...und Infotext fuer "schon abgestimmt" erzeugen
			}
		}
		else { // Falls User nicht eingeloggt ist...
			$poll_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewtopic_poll_results']); // ...Ergebnisboxtemplate laden...
			$info_text = $LNG['poll_not_logged_in_info']; // ...und Infotext fuer "nicht eingeloggt" erzeugen
		}

		$DB->query("SELECT option_id,option_title,option_votes FROM ".TBLPFX."polls_options WHERE poll_id='".$poll_data['poll_id']."' ORDER BY option_id"); // Die Auswahlmoeglichkeiten fuer die Umfrage laden
		while($akt_option = $DB->fetch_array()) {
			$akt_fraction = ($poll_data['poll_votes'] == 0) ? 0 : round($akt_option['option_votes']/$poll_data['poll_votes'],2); // Der Anteil an Stimmen (0,xx)
			$akt_percent = $akt_fraction*100; // Stimmenanteil in Prozent
			$akt_votes = ($akt_option['option_votes'] == 1) ? $LNG['one_vote'] : sprintf($LNG['x_votes'],$akt_option['option_votes']); // Anzahl der Stimmen
			$akt_checked = ($akt_option['option_id'] == 1) ? ' checked="checked"' : ''; // checked="checked" fuer den ersten Radiobutton erzeugen (damit auf jeden Fall was ausgewaehlt ist)

			$poll_tpl->blocks['optionrow']->parse_code(FALSE,TRUE); // Templateblock fuer Auswahlmoeglichkeit erzeugen
		}

		$poll_box = $poll_tpl->parse_code(); // Template fuer Umfragebox erzeugen und in $poll_bock speichern
		unset($poll_tpl); // Variable loeschen (wird auf keinen Fall wieder benoetigt)
	}
}


//
// Smilies und Beitragsbilder laden
//
$SMILIES_DATA = array(); // Beinhaltet spaeter die Smilies mit dem Synonym als Key und dem Bild (HTML) als Wert
if($forum_data['forum_enable_smilies'] == 1 || $CONFIG['enable_sig'] == 1 && $CONFIG['allow_sig_smilies'] == 1)
	$SMILIES_DATA = cache_get_smilies_data('write');

$ppics_data = cache_get_ppics_data(); // DB-Daten in Array umwandeln


//
// Rangdaten laden
//
$RANKS_DATA = cache_get_ranks_data();


$parsed_signatures = array(); // Hier werden spaeter eventuell die geparsten Signaturen gespeichert um das nicht mehrfach machen zu muessen


//
// Beitraege anzeigen
//
$akt_cell_class = $TCONFIG['cell_classes']['start_class'];
$DB->query("
	SELECT
		t1.post_id,
		t1.poster_id,
		t1.post_pic,
		t1.post_enable_bbcode,
		t1.post_enable_smilies,
		t1.post_enable_html,
		t1.post_show_sig,
		t1.post_guest_nick,
		t1.post_time,
		t1.post_edited_counter,
		t1.post_last_editor_id,
		t1.post_title,
		t1.post_text,
		t2.user_email AS poster_email,
		t2.user_nick AS poster_nick,
		t2.user_signature AS poster_signature,
		t2.user_is_admin AS poster_is_admin,
		t2.user_is_supermod AS poster_is_supermod,
		t2.user_posts AS poster_posts,
		t2.user_rank_id AS poster_rank_id,
		t2.user_avatar_address AS poster_avatar_address,
		t4.user_nick AS post_last_editor_nick,
		t2.user_hide_email AS poster_hide_email,
		t2.user_receive_emails AS poster_receive_emails
	FROM ".TBLPFX."posts AS t1
	LEFT JOIN ".TBLPFX."users AS t2 ON t1.poster_id=t2.user_id
	LEFT JOIN ".TBLPFX."users AS t4 ON t1.post_last_editor_id=t4.user_id
	WHERE t1.topic_id='$topic_id'
	ORDER BY t1.post_time LIMIT $start,".$CONFIG['posts_per_page']
);
while($akt_post = $DB->fetch_array()) {

	$akt_edited_text = '';
	if($akt_post['post_edited_counter'] > 0) {
		$akt_last_editor_nick = ($akt_post['post_last_editor_id'] == 0) ? $LNG['Deleted_user'] : $akt_post['post_last_editor_nick'];
		$akt_edited_text = sprintf($LNG['edited_post_text'],$akt_post['post_edited_counter'],$akt_last_editor_nick);
	}

	$edit_button = $delete_button = $user_email_button = $user_hp_button = '';

	if($USER_LOGGED_IN == 1) {
		if($USER_DATA['user_is_admin'] == 1 || $USER_DATA['user_is_supermod'] == 1 || $auth_data['auth_is_mod'] == 1 || (($forum_data['auth_members_edit_posts'] == 1 && $auth_data['auth_edit_posts'] == 1 || $forum_data['auth_members_edit_posts'] != 1 && $auth_data['auth_edit_posts'] == 1) && $USER_ID == $akt_post['poster_id'])) {
			$edit_button = "<a href=\"index.php?faction=editpost&amp;post_id=".$akt_post['post_id']."&amp;mode=edit&amp;$MYSID\"><img src=\"$TEMPLATE_PATH/".$TCONFIG['images']['edit_post']."\" alt=\"".$LNG['Edit_post']."\" border=\"0\" /></a>";
			if($akt_post['post_id'] != $topic_data['topic_first_post_id'])
				$delete_button = "<a href=\"index.php?faction=editpost&amp;post_id=".$akt_post['post_id']."&amp;mode=delete&amp;$MYSID\"><img src=\"$TEMPLATE_PATH/".$TCONFIG['images']['delete_post']."\" alt=\"".$LNG['Delete_post']."\" border=\"0\" /></a>";
		}
	}

	$quote_button = "<a href=\"index.php?faction=postreply&amp;topic_id=$topic_id&amp;quote=".$akt_post['post_id']."&amp;$MYSID\"><img src=\"$TEMPLATE_PATH/".$TCONFIG['images']['quote_post']."\" alt=\"".$LNG['Quote_post']."\" border=\"0\" /></a>";

	$akt_post_pic = '';
	if($akt_post['post_pic'] != 0) {
		foreach($ppics_data AS $cur_ppic) {
			if($cur_ppic['smiley_id'] != $akt_post['post_pic']) continue;	
			$akt_post_pic = '<img src="'.$cur_ppic['smiley_gfx'].'" alt="" />';
			break;
		}
	}

	$post_tools = array();
	$post_tools[] = "<a href=\"index.php?faction=postreply&amp;topic_id=$topic_id&amp;quote=".$akt_post['post_id']."&amp;$MYSID\">".$LNG['Quote_post'].'</a>';
	if($USER_LOGGED_IN == 1) {
		if($akt_post['poster_id'] == $USER_ID || $USER_DATA['user_is_admin'] == 1 || $USER_DATA['user_is_supermod'] == 1) {
			$post_tools[] = "<a href=\"index.php?faction=editpost&amp;mode=edit&amp;topic_id=$topic_id&amp;post_id=".$akt_post['post_id']."&amp;$MYSID\">".$LNG['Edit_post'].'</a>';
			if($topic_data['topic_first_post_id'] != $akt_post['post_id']) $post_tools[] = "<a href=\"index.php?faction=deletepost&amp;mode=edit&amp;topic_id=$topic_id&amp;post_id=".$akt_post['post_id']."&amp;$MYSID\">".$LNG['Delete_post'].'</a>';
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
		$akt_poster_rank_text = $LNG['Guest']; // ...und seinen Rang auf "Gast" setzen
	}
	else { // Andernfalls (also wenn der User nicht geloescht bzw. Gast ist)
		$akt_poster_nick = '<a href="index.php?faction=viewprofile&amp;profile_id='.$akt_post['poster_id'].'&amp;'.$MYSID.'">'.$akt_post['poster_nick'].'</a>'; // Den Nick mit einem Link zum Profil versehen
		$akt_poster_id = sprintf($LNG['ID_x'],$akt_post['poster_id']); // Die ID mit einem kleinen "Text" versehen


		//
		// Ueberpruefung, ob die Emailadresse des Users angezeigt werden soll
		// Zur Sicherheit wird es auch hier geloescht, damit der Templatebauer die Emailadresse
		// nicht doch aus Versehen anzeigen lässt
		//
		if($akt_post['poster_hide_email'] == 1) $akt_post['poster_email'] = '';


		//
		// Avatar
		//
		if($CONFIG['enable_avatars'] == 1 && $akt_post['poster_avatar_address'] != '')
			$akt_poster_avatar = '<img src="'.$akt_post['poster_avatar_address'].'" alt="" border="0" width="'.$CONFIG['avatar_image_width'].'" height="'.$CONFIG['avatar_image_height'].'" />';


		//
		// Rangbild und Rangtext des Users festlegen
		//
		if($akt_post['poster_rank_id'] != 0) { // Falls der User einen speziellen Rang zugewiessen bekommen hat...
			$akt_poster_rank_text = $RANKS_DATA[1][$akt_post['poster_rank_id']]['rank_name']; // ...den Namen des Rang verwenden...
			$akt_poster_rank_pic = $RANKS_DATA[1][$akt_post['poster_rank_id']]['rank_gfx']; // ...und das Bild des Rangs verwenden
		}
		elseif($akt_post['poster_is_admin'] == 1) { // Falls der User Administrator ist...
			$akt_poster_rank_text = $LNG['Administrator']; // ...seinen Rang darauf setzen...
			$akt_poster_rank_pic = '<img src="'.$CONFIG['admin_rank_pic'].'" alt="" border="0" />'; // ...und das entsprechende Bild verwenden
		}
		elseif($akt_post['poster_is_supermod'] == 1) { // Falls der User Supermoderator ist...
			$akt_poster_rank_text = $LNG['Supermoderator']; // ...seinen Rang darauf setzen...
			$akt_poster_rank_pic = '<img src="'.$CONFIG['supermod_rank_pic'].'" alt="" border="0" />'; // ...und das entsprechende Bild verwenden
		}
		elseif(in_array($akt_post['poster_id'],$forum_mod_ids) == TRUE) { // Falls der User Moderator ist...
			$akt_poster_rank_text = $LNG['Moderator']; // ...seinen Rang darauf setzen...
			$akt_poster_rank_pic = '<img src="'.$CONFIG['mod_rank_pic'].'" alt="" border="0" />'; // ...und das entsprechende Bild verwenden
		}
		else { // Falls der User ein ganz normaler User ist...
			foreach($RANKS_DATA[0] AS $cur_rank) { // Die Rangliste durchlaufen
				if($cur_rank['rank_posts'] > $akt_post['poster_posts']) break;

				$akt_poster_rank_text = $cur_rank['rank_name']; // ...den Namen das Rangs verwenden...
				$akt_poster_rank_pic = $cur_rank['rank_gfx']; // ...und das Bild des Rangs verwenden
			}
		}
	}


	//
	// Den Beitrag entsprechend formatieren
	//
	if($akt_post['post_enable_html'] != 1 || $forum_data['forum_enable_htmlcode'] != 1) $akt_post['post_text'] = myhtmlentities($akt_post['post_text']); //array_merge($strtr_array,$html_schars_table);
	if($akt_post['post_enable_smilies'] == 1 && $forum_data['forum_enable_smilies'] == 1) $akt_post['post_text'] = strtr($akt_post['post_text'],$SMILIES_DATA);
	$akt_post['post_text'] = nlbr($akt_post['post_text']);
	if($akt_post['post_enable_bbcode'] == 1 && $forum_data['forum_enable_bbcode'] == 1) $akt_post['post_text'] = bbcode($akt_post['post_text']);


	//
	// Die Signatur entsprechend formatieren
	//
	$akt_post_signature = '';
	if($akt_post['post_show_sig'] == 1 && $CONFIG['enable_sig'] == 1 && $akt_post['poster_signature'] != '') {
		if(!isset($parsed_signatures[$akt_post['poster_id']])) { // Falls die Signatur nicht schonmal formatiert wurde
			if($CONFIG['allow_sig_html'] != 1) $parsed_signatures[$akt_post['poster_id']] = myhtmlentities($akt_post['poster_signature']);
			if($CONFIG['allow_sig_smilies'] == 1) $parsed_signatures[$akt_post['poster_id']] = strtr($parsed_signatures[$akt_post['poster_id']],$SMILIES_DATA);
			$parsed_signatures[$akt_post['poster_id']] = nlbr($parsed_signatures[$akt_post['poster_id']]);
			if($CONFIG['allow_sig_bbcode'] == 1) $parsed_signatures[$akt_post['poster_id']] = bbcode($parsed_signatures[$akt_post['poster_id']]);
		}
		$akt_post_signature = $parsed_signatures[$akt_post['poster_id']];

		$viewtopic_tpl->blocks['postrow']->blocks['signature']->parse_code();
	}
	else $viewtopic_tpl->blocks['postrow']->blocks['signature']->blank_tpl();

	$viewtopic_tpl->blocks['postrow']->parse_code(FALSE,TRUE);
	$akt_cell_class = ($akt_cell_class == $TCONFIG['cell_classes']['td1_class']) ? $TCONFIG['cell_classes']['td2_class'] : $TCONFIG['cell_classes']['td1_class'];
}


if($USER_LOGGED_IN == 1 && $CONFIG['enable_email_functions'] == 1 && $CONFIG['enable_topic_subscription'] == 1) {
	$DB->query("SELECT user_id FROM ".TBLPFX."topics_subscriptions WHERE topic_id='$topic_id' AND user_id='$USER_ID'");
	$subscribe_text = ($DB->affected_rows == 0) ? $LNG['Subscribe_topic'] : $LNG['Unsubscribe_topic'];

	$NAVBAR->addElements('right',array($subscribe_text,"index.php?faction=subscribetopic&amp;topic_id=$topic_id&amp;z=$z&amp;$MYSID"));
}


get_navbar_cats($forum_data['cat_id']);
$NAVBAR->addElements('left',array(myhtmlentities($forum_data['forum_name']),"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID"),array(myhtmlentities($topic_data['topic_title']),''));

//
// Seite ausgeben
//
include_once('pheader.php');
$viewtopic_tpl->parse_code(TRUE);
show_navbar();
include_once('ptail.php');

?>