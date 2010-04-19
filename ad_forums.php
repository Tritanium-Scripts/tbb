<?php
/**
*
* Tritanium Bulletin Board 2 - ad_forums.php
* version #2004-01-01-18-38-43
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$error = '';

switch(@$_GET['mode']) {

	//*
	//* Standard: Kategorie-/Forenuebersicht
	//*
	default:
		$db->query("SELECT * FROM ".TBLPFX."cats ORDER BY order_id"); // Kategoriedaten laden
		$cats_data = $db->raw2array(); // DB-Daten in Array umwandeln

		$db->query("SELECT * FROM ".TBLPFX."forums ORDER BY order_id"); // Forendaten laden
		$forums_data = $db->raw2array(); // DB-Daten in Array umwandeln

		$forums_counter = sizeof($forums_data); // Anzahl der Foren
		$cats_counter = sizeof($cats_data); // Anzahl der Kateogieren

		$adforums_tpl = new template; // Templateobjekt erstellen
		$adforums_tpl->load($template_path.'/'.$tpl_config['tpl_ad_forums_overview']); // Template laden


		//
		// Die *rekursive* Funktion (d.h. die Funktion ruft sich selber auf) zum Erzeugen der Kategorie-/Forenuebersicht
		//
		function build_sub_cats($parent_id = 0,$appendix = '') {
			global $forums_data,$cats_data,$forums_counter,$cats_counter,$adforums_tpl,$tpl_config,$lng,$MYSID; // Die benoetigten globalen Variablen verfuegbar machen

			$akt_cats_counter = 0; // Anzahl der Unterkategorien in der aktuellen Kategorie, erst mal auf 0 setzen
			$y = 1; // "Position" der aktuellen Kategorie

			for($i = 0; $i < $cats_counter; $i++) {
				if($cats_data[$i]['parent_id'] == $parent_id) $akt_cats_counter++; // Falls Kategorie eine Unterkategorie der aktuellen Kategorie ist, Zaehler um 1 erhoehen
			}

			for($i = 0; $i < $cats_counter; $i++) {

				if($parent_id == $cats_data[$i]['parent_id']) { // Falls Kategorie eine Unterkategorie der aktuellen Kategorie ist

					$adforums_tpl->blocks['catrow']->blocks['forumrow']->reset_tpl(); // Template zuruecksetzen
					$akt_forums_counter = 0; // Anzahl der Foren in der aktuellen Kategorie
					$x = 1; // "Position" des aktuellen Forums

					for($j = 0; $j < $forums_counter; $j++) {
						if($forums_data[$j]['cat_id'] == $cats_data[$i]['cat_id']) $akt_forums_counter++; // Falls Forum zur aktuellen Kateogrie gehoert, Zaehler um 1 erhoehen
					}

					for($j = 0; $j < $forums_counter; $j++) {
						if($forums_data[$j]['cat_id'] == $cats_data[$i]['cat_id']) { // Falls Forum zur aktuellen Kategorie gehoert
							if($x == 1) $up = $lng['moveup']; // Falls Forum an 1. Stelle, kein "nach oben"-Link...
							else $up = "<a href=\"administration.php?faction=ad_forums&amp;mode=moveforumup&amp;forum_id_1=".$forums_data[$j]['forum_id']."&amp;forum_id_2=".$forums_data[$j-1]['forum_id']."&amp;$MYSID\">".$lng['moveup']."</a>"; // ...ansonsten Link erstellen

							if($x == $akt_forums_counter) $down = $lng['movedown']; // Falls Forum an letzter Stelle, kein "nach unten" Link...
							else $down = "<a href=\"administration.php?faction=ad_forums&amp;mode=moveforumdown&amp;forum_id_1=".$forums_data[$j]['forum_id']."&amp;forum_id_2=".$forums_data[$j+1]['forum_id']."&amp;$MYSID\">".$lng['movedown']."</a>"; // ...ansonsten Link erstellen

							$adforums_tpl->blocks['catrow']->blocks['forumrow']->values = array( // Variablen fuer Template verfuegbar machen (da wir uns in einer Funktion befinden)
								'appendix'=>$appendix,
								'akt_forum'=>$forums_data[$j],
								'up'=>$up,
								'down'=>$down,
							);
							$adforums_tpl->blocks['catrow']->blocks['forumrow']->parse_code(FALSE,TRUE); // Templateblock fuer aktuelles Forum erzeugen
							$tpl_config['akt_class'] = ($tpl_config['akt_class'] == $tpl_config['td1_class']) ? $tpl_config['td2_class'] : $tpl_config['td1_class']; // Die aktuelle Spaltenklasse wechseln (fuer Farbenwechsel)
							$x++; // "Position" des naechsten Forums um 1 erhoehen
						}
					}

					if($y == 1) $up = $lng['moveup']; // Falls Kategorie an 1. Stelle, kein "nach oben"-Link...
					else $up = "<a href=\"administration.php?faction=ad_forums&amp;mode=movecatup&amp;cat_id_1=".$cats_data[$i]['cat_id']."&amp;cat_id_2=".$cats_data[$i-1]['cat_id']."&amp;$MYSID\">".$lng['moveup']."</a>"; // ...ansonsten Link erzeugen

					if($y == $akt_cats_counter) $down = $lng['movedown']; // Falls Kategorie an letzter Stelle, kein "nach unten"-Link...
					else $down = "<a href=\"administration.php?faction=ad_forums&amp;mode=movecatdown&amp;cat_id_1=".$cats_data[$i]['cat_id']."&amp;cat_id_2=".$cats_data[$i+1]['cat_id']."&amp;$MYSID\">".$lng['movedown']."</a>"; // ...ansonsten Link erzeugen

					$adforums_tpl->blocks['catrow']->values = array( // Variablen fuer Template verfuegbar machen (da wir uns in einer Funktion befinden)
						'akt_cat'=>$cats_data[$i],
						'up'=>$up,
						'down'=>$down,
						'appendix'=>$appendix
					);
					if($akt_forums_counter == 0) $adforums_tpl->blocks['catrow']->blocks['forumrow']->blank_tpl(); // Falls es in dieser Kategorie kein Forum gibt, den Templateblock leer machen

					$adforums_tpl->blocks['catrow']->parse_code(FALSE,TRUE); // Templateblock fuer aktuelle Kategorie erzeugen
					$y++; // "Position" der naechsten Kategorie um 1 erhoehen
					build_sub_cats($cats_data[$i]['cat_id'],$appendix.'--'); // Unterkategorien der aktuellen Kategorie erzeugen durch *rekursiven* Aufruf der Funktion
				}
			}
		}

		if($cats_counter != 0) build_sub_cats(0,'--'); // Der erste Aufruf der Funktion, um den Foren-/Kategoriebaum zu erstellen, falls mindestens eine Kategorie existiert...
		else $adforums_tpl->blocks['catrow']->blank_tpl(); // ...ansonsten den Templateblock leer machen

		$x = FALSE; // Gibt an, ob Foren ohne Kategorie existieren

		for($i = 0; $i < $forums_counter; $i++) {
			if($forums_data[$i]['cat_id'] == 0) { // Falls Forum keine Unterkategorie hat
				$adforums_tpl->blocks['forumrow']->values = array( // Variablen fuer Template verfuegbar machen (da wir mit Laufvariablen $i arbeiten)
					'akt_forum'=>$forums_data[$i]
				);
				$adforums_tpl->blocks['forumrow']->parse_code(FALSE,TRUE); // Templateblock fuer aktuelles Forum erzeugen
				$tpl_config['akt_class'] = ($tpl_config['akt_class'] == $tpl_config['td1_class']) ? $tpl_config['td2_class'] : $tpl_config['td1_class']; // Die aktuelle Spaltenklasse wechseln (fuer Farbenwechsel)
				$x = TRUE; // Es existieren Foren ohne Kategorie
			}
		}
		if($x == FALSE) $adforums_tpl->unset_block('forumrow'); // Falls keine Foren ohne Kategorie existieren, Templateblock loeschen

		include_once('ad_pheader.php'); // Seitenkopf ausgeben

		$adforums_tpl->parse_code(TRUE); // Seite ausgeben

		include_once('ad_ptail.php'); // Seitenende ausgeben
	break;


	//*
	//* Forum bearbeiten
	//*
	case 'editforum';
		$forum_id = isset($_GET['forum_id']) ? $_GET['forum_id'] : 0; // ID des Forums
		if(!$forum_data = get_forum_data($forum_id)) die('Kann Forendaten nicht laden!'); // Ueberpruefen, ob Forum existiert

		$p_forum_name = isset($_POST['p_forum_name']) ? $_POST['p_forum_name'] : $forum_data['forum_name']; // Name des Forums
		$p_forum_description = isset($_POST['p_forum_description']) ? $_POST['p_forum_description'] : $forum_data['forum_description']; // Beschreibung des Forums
		$p_cat_id = isset($_POST['p_cat_id']) ? $_POST['p_cat_id'] : $forum_data['cat_id']; // ID der Kategorie

		$p_members_view_forum = $forum_data['auth_members_view_forum']; // Mitglieder duerfen Forum betreten
		$p_members_post_topic = $forum_data['auth_members_post_topic']; // Mitglieder duerfen Themen erstellen
		$p_members_post_reply = $forum_data['auth_members_post_reply']; // Mitglieder duerfen Antworten erstellen
		$p_members_post_poll = $forum_data['auth_members_post_poll']; // Mitglieder duerfen Umfragen erstellen
		$p_members_edit_posts = $forum_data['auth_members_edit_posts']; // Mitglieder duerfen ihre Beitraege bearbeiten
		$p_guests_view_forum = $forum_data['auth_guests_view_forum']; // Gaeste duerfen Forum betreten
		$p_guests_post_topic = $forum_data['auth_guests_post_topic']; // Gaeste duerfen Themen erstellen
		$p_guests_post_reply = $forum_data['auth_guests_post_reply']; // Gaeste duerfen Antworten erstellen
		$p_guests_post_poll = $forum_data['auth_guests_post_poll']; // Gaeste duerfen Umfragen erstellen

		$p_forum_is_moderated = $forum_data['forum_is_moderated']; // Forum ist moderiert
		$p_forum_enable_bbcode = $forum_data['forum_enable_bbcode']; // BBCode in diesem Forum aktivieren
		$p_forum_enable_htmlcode = $forum_data['forum_enable_htmlcode']; // HTML-Code in diesem Forum aktivieren
		$p_forum_enable_smilies = $forum_data['forum_enable_smilies']; // Smilies in diesem Forum aktivieren

		if(isset($_GET['doit'])) {
			$p_members_view_forum = isset($_POST['p_members_view_forum']) ? 1 : 0; // Mitglieder duerfen Forum betreten
			$p_members_post_topic = isset($_POST['p_members_post_topic']) ? 1 : 0; // Mitglieder duerfen Themen erstellen
			$p_members_post_reply = isset($_POST['p_members_post_reply']) ? 1 : 0; // Mitglieder duerfen Antworten erstellen
			$p_members_post_poll = isset($_POST['p_members_post_poll']) ? 1 : 0; // Mitglieder duerfen Umfragen erstellen
			$p_members_edit_posts = isset($_POST['p_members_edit_posts']) ? 1 : 0; // Mitglieder duerfen ihre Beitraege bearbeiten
			$p_guests_view_forum = isset($_POST['p_guests_view_forum']) ? 1 : 0; // Gaeste duerfen Forum betreten
			$p_guests_post_topic = isset($_POST['p_guests_post_topic']) ? 1 : 0; // Gaeste duerfen Themen erstellen
			$p_guests_post_reply = isset($_POST['p_guests_post_reply']) ? 1 : 0; // Gaeste duerfen Antworten erstellen
			$p_guests_post_poll = isset($_POST['p_guests_post_poll']) ? 1 : 0; // Gaeste duerfen Umfragen erstellen

			$p_forum_is_moderated = isset($_POST['p_forum_is_moderated']) ? 1 : 0; // Forum ist moderiert
			$p_forum_enable_bbcode = isset($_POST['p_forum_enable_bbcode']) ? 1 : 0; // BBCode in diesem Forum aktivieren
			$p_forum_enable_htmlcode = isset($_POST['p_forum_enable_htmlcode']) ? 1 : 0; // HTML-Code in diesem Forum aktivieren
			$p_forum_enable_smilies = isset($_POST['p_forum_enable_smilies']) ? 1 : 0; // Smilies in diesem Forum aktivieren

			if(trim($p_forum_name) == '') $error = $lng['error_no_forum_name']; // Falls kein Name fuer das Forum angegeben wurde, Fehler ausgeben
			else {
				$db->query("UPDATE ".TBLPFX."forums SET forum_is_moderated='$p_forum_is_moderated', forum_enable_bbcode='$p_forum_enable_bbcode', forum_enable_htmlcode='$p_forum_enable_htmlcode', forum_enable_smilies='$p_forum_enable_smilies', forum_name='$p_forum_name', forum_description='$p_forum_description', cat_id='$p_cat_id', auth_members_view_forum='$p_members_view_forum', auth_members_post_topic='$p_members_post_topic', auth_members_post_reply='$p_members_post_reply', auth_members_post_poll='$p_members_post_poll', auth_members_edit_posts='$p_members_edit_posts', auth_guests_view_forum='$p_guests_view_forum', auth_guests_post_topic='$p_guests_post_topic', auth_guests_post_reply='$p_guests_post_reply', auth_guests_post_poll='$p_guests_post_poll' WHERE forum_id='$forum_id'"); // Dei aktualisierten Daten speichern
				header("Location: administration.php?faction=ad_forums&$MYSID"); exit; // Zurueck zur Forenuebersicht
			}
		}

		//
		// Im den folgenden 14 Zeilen werden die "Haeckchen" fuer den HTML-Code erzeugt
		//
		$c = ' checked="checked"';
		$checked['smilies'] = ($p_forum_enable_smilies == 1) ? $c : '';
		$checked['bbcode'] = ($p_forum_enable_bbcode == 1) ? $c : '';
		$checked['htmlcode'] = ($p_forum_enable_htmlcode == 1) ? $c : '';
		$checked['moderated'] = ($p_forum_is_moderated == 1) ? $c : '';
		$checked['members_view_forum'] = ($p_members_view_forum == 1) ? $c : '';
		$checked['members_post_topic'] = ($p_members_post_topic == 1) ? $c : '';
		$checked['members_post_reply'] = ($p_members_post_reply == 1) ? $c : '';
		$checked['members_post_poll'] = ($p_members_post_poll == 1) ? $c : '';
		$checked['members_edit_posts'] = ($p_members_edit_posts == 1) ? $c : '';
		$checked['guests_view_forum'] = ($p_guests_view_forum == 1) ? $c : '';
		$checked['guests_post_topic'] = ($p_guests_post_topic == 1) ? $c : '';
		$checked['guests_post_reply'] = ($p_guests_post_reply== 1) ? $c : '';
		$checked['guests_post_poll'] = ($p_guests_post_poll == 1) ? $c : '';

		$adforums_tpl = new template; // Templateobjekt erstellen
		$adforums_tpl->load($template_path.'/'.$tpl_config['tpl_ad_forums_editforum']); // Template laden

		if($error != '') $adforums_tpl->blocks['errorrow']->parse_code(); // Falls Fehler vorhanden ist, Templateblock fuer Fehler erstellen...
		else $adforums_tpl->unset_block('errorrow'); // ...ansonsten diesen Block loeschen

		$db->query("SELECT cat_id,cat_name FROM ".TBLPFX."cats ORDER BY cat_id"); // Daten aller Kategorien laden
		$cats_data = $db->raw2array(); // DB-Daten in Array umwandeln

		$no_cat_selected = ($p_cat_id == 0) ? ' selected="selected"' : ''; // Falls keine Kategorie ausgewaehlt wurde, das hier vermerken

		if(sizeof($cats_data) != 0) { // Falls mindestens eine Kategorie existiert
			while(list(,$akt_cat) = each($cats_data)) {
				$selected = ($p_cat_id == $akt_cat['cat_id']) ? ' selected="selected"' : ''; // Falls diese Kategorie ausgewaehlt wurde, das vermerken
				$adforums_tpl->blocks['optionrow']->parse_code(FALSE,TRUE); // Templateblock fuer aktuelle Kategorie erstellen
			}
		}
		else $adforums_tpl->unset_block('optionrow'); // Wenn keine Kategorie existiert, diesen Block loeschen

		include_once('ad_pheader.php'); // Seitenkopf ausgeben

		$adforums_tpl->parse_code(TRUE); // Seite ausgeben

		include_once('ad_ptail.php'); // Seitenende ausgeben
	break;


	//*
	//* Kategorie bearbeiten
	//*
	case 'editcat':
		$cat_id = isset($_GET['cat_id']) ? $_GET['cat_id'] : 0; // ID der Kategorie
		if(!$cat_data = get_cat_data($cat_id)) die('Kann Kategoriedaten nicht laden!'); // Ueberpruefen, ob Kategorie existiert

		$p_cat_name = isset($_POST['p_cat_name']) ? $_POST['p_cat_name'] : $cat_data['cat_name']; // Name der Kategorie
		$p_cat_description = isset($_POST['p_cat_description']) ? $_POST['p_cat_description'] : $cat_data['cat_description']; // Beschreibung der Kategorie
		$p_parent_id = isset($_POST['p_parent_id']) ? $_POST['p_parent_id'] : $cat_data['parent_id']; // ID der uebergeordneten Kateogrie (0 = "keine uebergeordnete Kategorie")

		$error = ''; // eventueller spaeterer Fehler

		if(isset($_GET['doit'])) { // Falls Formular abgeschickt wurde
			if(trim($p_cat_name) == '') $error = $lng['error_no_category_name']; // Falls kein Name fuer die KAtegorie eingegeben wurde, Fehler ausgeben
			else {
				$db->query("UPDATE ".TBLPFX."cats SET cat_name='$p_cat_name', cat_description='$p_cat_description', parent_id='$p_parent_id' WHERE cat_id='$cat_id'"); // Neuen Kategoriedaten speichern
				header("Location: administration.php?faction=ad_forums&$MYSID"); exit; // Zurueck zur Foren-/Kategorieuebersicht
			}
		}

		$adforums_tpl = new template; // Neues Templateobjekt erstellen
		$adforums_tpl->load($template_path.'/'.$tpl_config['tpl_ad_forums_editcat']); // Template laden

		if($error != '') $adforums_tpl->blocks['errorrow']->parse_code(); // Falls Fehler existiert, Templateblock erstellen...
		else $adforums_tpl->unset_block('errorrow'); // ...ansonsten diesen Block loeschen

		$db->query("SELECT cat_name,cat_id FROM ".TBLPFX."cats WHERE cat_id<>'$cat_id' ORDER BY order_id"); // Daten aller Kategorien laden, ausser der gewaehlten Kategorie
		$cats_data = $db->raw2array(); // DB-Daten in Array umwandeln

		$no_parent_cat_selected = ($p_parent_id == 0) ? ' selected="selected"' : '';

		if(sizeof($cats_data) != 0) {
			while(list(,$akt_cat) = each($cats_data)) {
				$selected = ($p_parent_id == $akt_cat['cat_id']) ? ' selected="selected"' : '';
				$adforums_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);
			}
		}
		else $adforums_tpl->unset_block('optionrow');

		include_once('ad_pheader.php');

		$adforums_tpl->parse_code(TRUE);

		include_once('ad_ptail.php');
	break;


	//*
	//* Kategorie nach oben bewegen
	//*
	case 'movecatup':
		$cat_id_1 = isset($_GET['cat_id_1']) ? $_GET['cat_id_1'] : 0;
		$cat_id_2 = isset($_GET['cat_id_2']) ? $_GET['cat_id_2'] : 0;

		if(($cat_1_data = get_cat_data($cat_id_1)) && ($cat_2_data = get_cat_data($cat_id_2))) {
			if($cat_1_data['order_id'] > $cat_2_data['order_id']) {
				$db->query("UPDATE ".TBLPFX."cats SET order_id='".$cat_2_data['order_id']."' WHERE cat_id='$cat_id_1'");
				$db->query("UPDATE ".TBLPFX."cats SET order_id='".$cat_1_data['order_id']."' WHERE cat_id='$cat_id_2'");
			}
		}
		header("Location: administration.php?faction=ad_forums&$MYSID"); exit;
	break;


	//*
	//* Kategorie nach unten bewegen
	//*
	case 'movecatdown':
		$cat_id_1 = isset($_GET['cat_id_1']) ? $_GET['cat_id_1'] : 0;
		$cat_id_2 = isset($_GET['cat_id_2']) ? $_GET['cat_id_2'] : 0;

		if(($cat_1_data = get_cat_data($cat_id_1)) && ($cat_2_data = get_cat_data($cat_id_2))) {
			if($cat_1_data['order_id'] < $cat_2_data['order_id']) {
				$db->query("UPDATE ".TBLPFX."cats SET order_id='".$cat_2_data['order_id']."' WHERE cat_id='$cat_id_1'");
				$db->query("UPDATE ".TBLPFX."cats SET order_id='".$cat_1_data['order_id']."' WHERE cat_id='$cat_id_2'");
			}
		}
		header("Location: administration.php?faction=ad_forums&$MYSID"); exit;
	break;


	//*
	//* Forum nach oben bewegen
	//*
	case 'moveforumup':
		$forum_id_1 = isset($_GET['forum_id_1']) ? $_GET['forum_id_1'] : 0;
		$forum_id_2 = isset($_GET['forum_id_2']) ? $_GET['forum_id_2'] : 0;

		if(($forum_1_data = get_forum_data($forum_id_1)) && ($forum_2_data = get_forum_data($forum_id_2))) {
			if($forum_1_data['order_id'] > $forum_2_data['order_id']) {
				$db->query("UPDATE ".TBLPFX."forums SET order_id='".$forum_2_data['order_id']."' WHERE forum_id='$forum_id_1'");
				$db->query("UPDATE ".TBLPFX."forums SET order_id='".$forum_1_data['order_id']."' WHERE forum_id='$forum_id_2'");
			}
		}
		header("Location: administration.php?faction=ad_forums&$MYSID"); exit;
	break;


	//*
	//* Forum nach unten bewegen
	//*
	case 'moveforumdown':
		$forum_id_1 = isset($_GET['forum_id_1']) ? $_GET['forum_id_1'] : 0;
		$forum_id_2 = isset($_GET['forum_id_2']) ? $_GET['forum_id_2'] : 0;

		if(($forum_1_data = get_forum_data($forum_id_1)) && ($forum_2_data = get_forum_data($forum_id_2))) {
			if($forum_1_data['order_id'] < $forum_2_data['order_id']) {
				$db->query("UPDATE ".TBLPFX."forums SET order_id='".$forum_2_data['order_id']."' WHERE forum_id='$forum_id_1'");
				$db->query("UPDATE ".TBLPFX."forums SET order_id='".$forum_1_data['order_id']."' WHERE forum_id='$forum_id_2'");
			}
		}
		header("Location: administration.php?faction=ad_forums&$MYSID"); exit;
	break;


	//*
	//* Spezialrechte eines Forums bearbeiten
	//*
	case 'editsrights':
		$forum_id = isset($_GET['forum_id']) ? $_GET['forum_id'] : 0;

		if(!$forum_data = get_forum_data($forum_id)) die('Kann Forumdaten nicht laden!');

		$p_rights = isset($_POST['p_rights']) ? $_POST['p_rights'] : array(array(),array());

		if(isset($_GET['doit'])) {
			if(isset($p_rights[0])) {
				while(list(,$akt_data) = each($p_rights[0])) {
					$akt_data['auth_is_mod'] = isset($akt_data['auth_is_mod']) ? 1 : 0;
					$akt_data['auth_view_forum'] = isset($akt_data['auth_view_forum']) ? 1 : 0;
					$akt_data['auth_post_topic'] = isset($akt_data['auth_post_topic']) ? 1 : 0;
					$akt_data['auth_post_reply'] = isset($akt_data['auth_post_reply']) ? 1 : 0;
					$akt_data['auth_post_poll'] = isset($akt_data['auth_post_poll']) ? 1 : 0;
					$akt_data['auth_edit_posts'] = isset($akt_data['auth_edit_posts']) ? 1 : 0;

					$db->query("UPDATE ".TBLPFX."forums_auth SET auth_is_mod='".$akt_data['auth_is_mod']."', auth_view_forum='".$akt_data['auth_view_forum']."', auth_post_topic='".$akt_data['auth_post_topic']."', auth_post_reply='".$akt_data['auth_post_reply']."', auth_post_poll='".$akt_data['auth_post_poll']."', auth_edit_posts='".$akt_data['auth_edit_posts']."' WHERE forum_id='$forum_id' AND auth_type='0' AND auth_id='".$akt_data['auth_id']."'");
				}
			}

			if(isset($p_rights[1])) {
				while(list(,$akt_data) = each($p_rights[1])) {
					$akt_data['auth_is_mod'] = isset($akt_data['auth_is_mod']) ? 1 : 0;
					$akt_data['auth_view_forum'] = isset($akt_data['auth_view_forum']) ? 1 : 0;
					$akt_data['auth_post_topic'] = isset($akt_data['auth_post_topic']) ? 1 : 0;
					$akt_data['auth_post_reply'] = isset($akt_data['auth_post_reply']) ? 1 : 0;
					$akt_data['auth_post_poll'] = isset($akt_data['auth_post_poll']) ? 1 : 0;
					$akt_data['auth_edit_posts'] = isset($akt_data['auth_edit_posts']) ? 1 : 0;

					$db->query("UPDATE ".TBLPFX."forums_auth SET auth_is_mod='".$akt_data['auth_is_mod']."', auth_view_forum='".$akt_data['auth_view_forum']."', auth_post_topic='".$akt_data['auth_post_topic']."', auth_post_reply='".$akt_data['auth_post_reply']."', auth_post_poll='".$akt_data['auth_post_poll']."', auth_edit_posts='".$akt_data['auth_edit_posts']."' WHERE forum_id='$forum_id' AND auth_type='1' AND auth_id='".$akt_data['auth_id']."'");
				}
			}

			include_once('ad_pheader.php');

			show_message('Special_rights_updated','message_special_rights_updated','<br />'.sprintf($lng['click_here_back'],"<a href=\"administration.php?faction=ad_forums&amp;mode=editforum&amp;forum_id=$forum_id&amp;$MYSID\">",'</a>'));

			include_once('ad_ptail.php'); exit;
		}

		$adforums_tpl = new template;
		$adforums_tpl->load($template_path.'/'.$tpl_config['tpl_ad_forums_editsrights']);

		$db->query("SELECT t1.*,t2.user_nick AS auth_user_nick FROM ".TBLPFX."forums_auth AS t1, ".TBLPFX."users AS t2 WHERE forum_id='$forum_id' AND auth_type='0' AND t2.user_id=t1.auth_id ORDER BY t2.user_nick");
		if($db->affected_rows > 0) {
			while($akt_uright = $db->fetch_array()) {
				$akt_checked = array();
				$c = ' checked="checked"';
				$akt_checked['auth_is_mod'] = ($akt_uright['auth_is_mod'] == 1) ? $c : '';
				$akt_checked['auth_view_forum'] = ($akt_uright['auth_view_forum'] == 1) ? $c : '';
				$akt_checked['auth_post_topic'] = ($akt_uright['auth_post_topic'] == 1) ? $c : '';
				$akt_checked['auth_post_reply'] = ($akt_uright['auth_post_reply'] == 1) ? $c : '';
				$akt_checked['auth_post_poll'] = ($akt_uright['auth_post_poll'] == 1) ? $c : '';
				$akt_checked['auth_edit_posts'] = ($akt_uright['auth_edit_posts'] == 1) ? $c : '';

				$adforums_tpl->blocks['urightrow']->parse_code(FALSE,TRUE);
				$tpl_config['akt_class'] = ($tpl_config['akt_class'] == $tpl_config['td1_class']) ? $tpl_config['td2_class'] : $tpl_config['td1_class'];
			}
		}
		else $adforums_tpl->unset_block('urightrow');

		$db->query("SELECT t1.*, t2.group_name AS auth_group_name FROM ".TBLPFX."forums_auth AS t1, ".TBLPFX."groups AS t2 WHERE forum_id='$forum_id' AND auth_type='1' AND t2.group_id=t1.auth_id ORDER BY t2.group_name");
		if($db->affected_rows > 0) {
			while($akt_gright = $db->fetch_array()) {
				$akt_checked = array();
				$c = ' checked="checked"';
				$akt_checked['auth_is_mod'] = ($akt_gright['auth_is_mod'] == 1) ? $c : '';
				$akt_checked['auth_view_forum'] = ($akt_gright['auth_view_forum'] == 1) ? $c : '';
				$akt_checked['auth_post_topic'] = ($akt_gright['auth_post_topic'] == 1) ? $c : '';
				$akt_checked['auth_post_reply'] = ($akt_gright['auth_post_reply'] == 1) ? $c : '';
				$akt_checked['auth_post_poll'] = ($akt_gright['auth_post_poll'] == 1) ? $c : '';
				$akt_checked['auth_edit_posts'] = ($akt_gright['auth_edit_posts'] == 1) ? $c : '';

				$adforums_tpl->blocks['grightrow']->parse_code(FALSE,TRUE);
				$tpl_config['akt_class'] = ($tpl_config['akt_class'] == $tpl_config['td1_class']) ? $tpl_config['td2_class'] : $tpl_config['td1_class'];
			}
		}
		else $adforums_tpl->unset_block('grightrow');

		include_once('ad_pheader.php');

		$adforums_tpl->parse_code(TRUE);

		include_once('ad_ptail.php');
	break;


	//*
	//* Kategorie hinzufuegen
	//*
	case 'addcat':
		$p_parent_id = isset($_GET['parent_id']) ? $_GET['parent_id'] : 0;
		if(isset($_POST['p_parent_id'])) $p_parent_id = $_POST['p_parent_id'];

		$p_cat_name = isset($_POST['p_cat_name']) ? $_POST['p_cat_name'] : '';
		$p_cat_description = isset($_POST['p_cat_description']) ? $_POST['p_cat_description'] : '';

		$error = '';

		if(isset($_GET['doit'])) {
			if(trim($p_cat_name) == '') $error = $lng['error_no_category_name'];
			else {
				$db->query("SELECT MAX(order_id) AS max_ord_id FROM ".TBLPFX."cats");
				list($p_order_id) = $db->fetch_array();
				$p_order_id++;

				$db->query("INSERT INTO ".TBLPFX."cats (parent_id,cat_name,cat_description,order_id) VALUES ('$p_parent_id','$p_cat_name','$p_cat_description','$p_order_id')");
				header("Location: administration.php?faction=ad_forums&$MYSID"); exit;
			}
		}

		$adforums_tpl = new template;
		$adforums_tpl->load($template_path.'/'.$tpl_config['tpl_ad_forums_addcat']);

		if($error != '') $adforums_tpl->blocks['errorrow']->parse_code();
		else $adforums_tpl->unset_block('errorrow');

		$db->query("SELECT cat_name,cat_id FROM ".TBLPFX."cats ORDER BY order_id"); // Kategoriedaten laden
		$cats_data = $db->raw2array(); // DB-Daten in Array umwandeln

		array_unshift($cats_data,array('cat_id'=>0,'cat_name'=>$lng['No_parent_category'])); // "Keine uebergeordnete Kategorie" zum Array hinzufuegen

		while(list(,$akt_cat) = each($cats_data)) {
			$akt_selected = ($p_parent_id == $akt_cat['cat_id']) ? ' selected="selected"' : '';
			$adforums_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);
		}

		include_once('ad_pheader.php');

		$adforums_tpl->parse_code(TRUE);

		include_once('ad_ptail.php');
	break;


	//*
	//* Forum hinzufuegen
	//*
	case 'addforum':

		$p_cat_id = isset($_GET['cat_id']) ? $_GET['cat_id'] : 0;
		if(isset($_POST['p_cat_id'])) $p_cat_id = $_POST['p_cat_id'];

		$p_forum_name = isset($_POST['p_forum_name']) ? $_POST['p_forum_name'] : '';
		$p_forum_description = isset($_POST['p_forum_description']) ? $_POST['p_forum_description'] : '';

		$p_forum_enable_bbcode = $p_forum_enable_smilies = $p_members_view_forum = $p_members_post_topic = $p_members_post_reply = $p_members_post_poll = $p_members_edit_posts = $p_guests_view_forum = $p_forum_add_last_posts = 1;
		$p_forum_enable_htmlcode = $p_forum_is_moderated = $p_guests_post_topic = $p_guests_post_reply = $p_guests_post_poll = 0;


		if(isset($_GET['doit'])) {
			$p_members_view_forum = isset($_POST['p_members_view_forum']) ? 1 : 0;
			$p_members_post_topic = isset($_POST['p_members_post_topic']) ? 1 : 0;
			$p_members_post_reply = isset($_POST['p_members_post_reply']) ? 1 : 0;
			$p_members_post_poll = isset($_POST['p_members_post_poll']) ? 1 : 0;
			$p_members_edit_posts = isset($_POST['p_members_edit_posts']) ? 1 : 0;
			$p_guests_view_forum = isset($_POST['p_guests_view_forum']) ? 1 : 0;
			$p_guests_post_topic = isset($_POST['p_guests_post_topic']) ? 1 : 0;
			$p_guests_post_reply = isset($_POST['p_guests_post_reply']) ? 1 : 0;
			$p_guests_post_poll = isset($_POST['p_guests_post_poll']) ? 1 : 0;

			$p_forum_add_last_posts = isset($_POST['p_forum_add_last_posts']) ? 1 : 0;
			$p_forum_is_moderated = isset($_POST['p_forum_is_moderated']) ? 1 : 0;
			$p_forum_enable_bbcode = isset($_POST['p_forum_enable_bbcode']) ? 1 : 0;
			$p_forum_enable_htmlcode = isset($_POST['p_forum_enable_htmlcode']) ? 1 : 0;
			$p_forum_enable_smilies = isset($_POST['p_forum_enable_smilies']) ? 1 : 0;

			if(trim($p_forum_name) == '') $error = $lng['error_no_forum_name'];
			else {

				$db->query("SELECT MAX(order_id) AS max_ord_id FROM ".TBLPFX."forums");
				list($p_order_id) = $db->fetch_array();
				$p_order_id++;

				$db->query("INSERT INTO ".TBLPFX."forums (cat_id,order_id,forum_name,forum_description,forum_topics_counter,forum_posts_counter,forum_last_post_id,forum_enable_bbcode,forum_enable_htmlcode,forum_enable_smilies,forum_is_moderated,forum_add_last_posts,auth_members_view_forum,auth_members_post_topic,auth_members_post_reply,auth_members_post_poll,auth_members_edit_posts,auth_guests_view_forum,auth_guests_post_topic,auth_guests_post_reply,auth_guests_post_poll)
					VALUES ('$p_cat_id','$p_order_id','$p_forum_name','$p_forum_description','0','0','0','$p_forum_enable_bbcode','$p_forum_enable_htmlcode','$p_forum_enable_smilies','$p_forum_is_moderated','$p_forum_add_last_posts','$p_members_view_forum','$p_members_post_topic','$p_members_post_reply','$p_members_post_poll','$p_members_edit_posts','$p_guests_view_forum','$p_guests_post_topic','$p_guests_post_reply','$p_guests_post_poll')");

				header("Location: administration.php?faction=ad_forums&$MYSID"); exit;
			}
		}

		$c = ' checked="checked"';

		$checked['smilies'] = ($p_forum_enable_smilies == 1) ? $c : '';
		$checked['bbcode'] = ($p_forum_enable_bbcode == 1) ? $c : '';
		$checked['htmlcode'] = ($p_forum_enable_htmlcode == 1) ? $c : '';
		$checked['moderated'] = ($p_forum_is_moderated == 1) ? $c : '';
		$checked['members_view_forum'] = ($p_members_view_forum == 1) ? $c : '';
		$checked['members_post_topic'] = ($p_members_post_topic == 1) ? $c : '';
		$checked['members_post_reply'] = ($p_members_post_reply == 1) ? $c : '';
		$checked['members_post_poll'] = ($p_members_post_poll == 1) ? $c : '';
		$checked['members_edit_posts'] = ($p_members_edit_posts == 1) ? $c : '';
		$checked['guests_view_forum'] = ($p_guests_view_forum == 1) ? $c : '';
		$checked['guests_post_topic'] = ($p_guests_post_topic == 1) ? $c : '';
		$checked['guests_post_reply'] = ($p_guests_post_reply== 1) ? $c : '';
		$checked['guests_post_poll'] = ($p_guests_post_poll == 1) ? $c : '';

		$adforums_tpl = new template;
		$adforums_tpl->load($template_path.'/'.$tpl_config['tpl_ad_forums_addforum']);

		if($error != '') {
			$adforums_tpl->blocks['errorrow']->values = array(
				'ERROR'=>$error
			);
			$adforums_tpl->blocks['errorrow']->parse_code();
		}
		else $adforums_tpl->unset_block('errorrow');

		//
		// Kategoriedaten laden
		//
		$db->query("SELECT cat_name,cat_id FROM ".TBLPFX."cats ORDER BY order_id");
		$db->raw2array();
		$cats_data = $db->array_data;

		$no_cat_selected = ($p_cat_id == 0) ? ' selected="selected"' : '';

		while(list(,$akt_cat) = each($cats_data)) {
			$akt_selected = ($p_cat_id == $akt_cat['cat_id']) ? ' selected="selected"' : '';
			$akt_cat['cat_name'] = htmlspecialchars($akt_cat['cat_name']);
			$adforums_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);
		}

		include_once('ad_pheader.php');

		$adforums_tpl->parse_code(TRUE);

		include_once('ad_ptail.php');
	break;


	//*
	//* Spezialrecht fuer einzelne User hinzufuegen
	//*
	case 'adduserright':
		$forum_id = isset($_GET['forum_id']) ? $_GET['forum_id'] : 0;

		if(!$forum_data = get_forum_data($forum_id)) die('Kann Forumdaten nicht laden!');

		$p_users = isset($_POST['p_users']) ? $_POST['p_users'] : '';

		$p_view_forum = $forum_data['auth_members_view_forum'];
		$p_post_topic = $forum_data['auth_members_post_topic'];
		$p_post_reply = $forum_data['auth_members_post_reply'];
		$p_post_poll = $forum_data['auth_members_post_poll'];
		$p_edit_posts = $forum_data['auth_members_edit_posts'];
		$p_is_mod = 0;

		if(isset($_GET['doit'])) {
			$p_view_forum = isset($_POST['p_view_forum']) ? 1 : 0;
			$p_post_topic = isset($_POST['p_post_topic']) ? 1 : 0;
			$p_post_reply = isset($_POST['p_post_reply']) ? 1 : 0;
			$p_post_poll = isset($_POST['p_post_poll']) ? 1 : 0;
			$p_edit_posts = isset($_POST['p_edit_posts']) ? 1 : 0;
			$p_is_mod = isset($_POST['p_is_mod']) ? 1 : 0;

			$users_array = explode(',',$p_users);
			while(list(,$akt_user) = each($users_array)) {
				if(($akt_user_id = get_user_id(trim($akt_user))) != FALSE) {
					$db->query("SELECT auth_id FROM ".TBLPFX."forums_auth WHERE forum_id='$forum_id' AND auth_type='0' AND auth_id='$akt_user_id'");
					if($db->affected_rows == 0) $db->query("INSERT INTO ".TBLPFX."forums_auth (forum_id,auth_type,auth_id,auth_view_forum,auth_post_topic,auth_post_reply,auth_post_poll,auth_edit_posts,auth_is_mod) VALUES ('$forum_id','0','$akt_user_id','$p_view_forum','$p_post_topic','$p_post_reply','$p_post_poll','$p_edit_posts','p_is_mod')");
				}
			}
			header("Location: administration.php?faction=ad_forums&mode=editsrights&forum_id=$forum_id&$MYSID"); exit;
		}

		$c = ' checked="checked"';
		$checked['view_forum'] = ($p_view_forum == 1) ? $c : '';
		$checked['post_topic'] = ($p_post_topic == 1) ? $c : '';
		$checked['post_reply'] = ($p_post_reply == 1) ? $c : '';
		$checked['post_poll'] = ($p_post_poll == 1) ? $c : '';
		$checked['edit_posts'] = ($p_edit_posts == 1) ? $c : '';
		$checked['is_mod'] = ($p_is_mod == 1) ? $c : '';

		$adforums_tpl = new template;
		$adforums_tpl->load($template_path.'/'.$tpl_config['tpl_ad_forums_adduserright']);

		include_once('ad_pheader.php');

		$adforums_tpl->parse_code(TRUE);

		include_once('ad_ptail.php');
	break;


	//*
	//* Spezialrecht fuer Gruppe hinzufuegen
	//*
	case 'addgroupright':
		$forum_id = isset($_GET['forum_id']) ? $_GET['forum_id'] : 0; // ID des Forums
		$p_group_id = isset($_POST['p_group_id']) ? $_POST['p_group_id'] : 0; // ID der Gruppe

		if(!$forum_data = get_forum_data($forum_id)) die('Kann Forumdaten nicht laden!'); // Ueberpruefen, ob Forum existiert

		$p_view_forum = $forum_data['auth_members_view_forum'];
		$p_post_topic = $forum_data['auth_members_post_topic'];
		$p_post_reply = $forum_data['auth_members_post_reply'];
		$p_post_poll = $forum_data['auth_members_post_poll'];
		$p_edit_posts = $forum_data['auth_members_edit_posts'];
		$p_is_mod = 0;

		if(isset($_GET['doit'])) { // Falls Formular abgeschickt wurde
			$p_view_forum = isset($_POST['p_view_forum']) ? 1 : 0;
			$p_post_topic = isset($_POST['p_post_topic']) ? 1 : 0;
			$p_post_reply = isset($_POST['p_post_reply']) ? 1 : 0;
			$p_post_poll = isset($_POST['p_post_poll']) ? 1 : 0;
			$p_edit_posts = isset($_POST['p_edit_posts']) ? 1 : 0;
			$p_is_mod = isset($_POST['p_is_mod']) ? 1 : 0;

			if($group_data = get_group_data($p_group_id)) { // Falls die Gruppe existiert
				$db->query("SELECT auth_id FROM ".TBLPFX."forums_auth WHERE forum_id='$forum_id' AND auth_type='1' AND auth_id='$p_group_id'"); // Ueberpruefen, ob diese Gruppe in diesem Forum schon Spezialrechte hat
				if($db->affected_rows == 0) $db->query("INSERT INTO ".TBLPFX."forums_auth (forum_id,auth_type,auth_id,auth_view_forum,auth_post_topic,auth_post_reply,auth_post_poll,auth_edit_posts,auth_is_mod) VALUES ('$forum_id','1','$p_group_id','$p_view_forum','$p_post_topic','$p_post_reply','$p_post_poll','$p_edit_posts','p_is_mod')"); // Falls nicht, die neuen Spezialrechte speichern
			}

			header("Location: administration.php?faction=ad_forums&mode=editsrights&forum_id=$forum_id&$MYSID"); exit; // Zurueck zur Spezialrechteuebersicht
		}

		$c = ' checked="checked"';
		$checked['view_forum'] = ($p_view_forum == 1) ? $c : '';
		$checked['post_topic'] = ($p_post_topic == 1) ? $c : '';
		$checked['post_reply'] = ($p_post_reply == 1) ? $c : '';
		$checked['post_poll'] = ($p_post_poll == 1) ? $c : '';
		$checked['edit_posts'] = ($p_edit_posts == 1) ? $c : '';
		$checked['is_mod'] = ($p_is_mod == 1) ? $c : '';

		$ad_forums_tpl = new template; // Neue Templateklasse erzeugen
		$ad_forums_tpl->load($template_path.'/'.$tpl_config['tpl_ad_forums_addgroupright']); // Template laden

		$group_ids = array(); // Array fuer die IDs der Gruppen, die schon Spezialrechte haben
		$db->query("SELECT auth_id FROM ".TBLPFX."forums_auth WHERE auth_type='1' AND forum_id='$forum_id'"); // IDs der Gruppen laden, die schon Spezialrechte haben
		while(list($akt_group_id) = $db->fetch_array())
			$group_ids[] = $akt_group_id; // Aktuelle ID zum Array hinzufuegen

		$db->query("SELECT * FROM ".TBLPFX."groups WHERE group_id NOT IN ('".implode("','",$group_ids)."')"); // Die IDs der Gruppen laden, die noch keine Spezialrechte in diesem Forum haben
		if($db->affected_rows != 0) { // Falls Gruppen existieren
			while($akt_group = $db->fetch_array())
				$ad_forums_tpl->blocks['grouprow']->parse_code(FALSE,TRUE); // Templateblock fuer eine Option mit der aktuellen Gruppe erzeugen
		}
		else $ad_forums_tpl->unset_block('grouprow'); // Falls keine Gruppe existiert

		include_once('ad_pheader.php'); // Seitenkopf ausgeben

		$ad_forums_tpl->parse_code(TRUE); // Seite ausgeben

		include_once('ad_ptail.php'); // Seitenende ausgeben
	break;


	//*
	//* Spezialrecht fuer Gruppe hinzufuegen
	//*
	case 'deletesright':
		$forum_id = isset($_GET['forum_id']) ? $_GET['forum_id'] : 0; // ID des Forums
		$sright_type = isset($_GET['sright_type']) ? $_GET['sright_type'] : 2; // Spezialrechttyp (0 = User, 1 = Gruppe (, 2 = ungueltig))
		$sright_id = isset($_GET['sright_id']) ? $_GET['sright_id'] : 0; // ID des Spezialrechts

		$db->query("DELETE FROM ".TBLPFX."forums_auth WHERE forum_id='$forum_id' AND auth_type='$sright_type' AND auth_id='$sright_id'"); // Loeschen des entsprechenden Spezialrechts

		header("Location: administration.php?faction=ad_forums&mode=editsrights&forum_id=$forum_id&$MYSID"); exit; // Zurueck zur Spezialrechteuebersicht
	break;
}

?>