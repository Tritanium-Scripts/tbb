<?php
/**
*
* Tritanium Bulletin Board 2 - viewcat.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$cat_id = isset($_GET['cat_id']) ? $_GET['cat_id'] : 1;
if($cat_id == 1 || ($cat_data = cats_get_cat_data($cat_id)) == FALSE) die('Kann Kategoriedaten nicht laden!'); // Kategoriedaten laden


//
// (Sub-)Kategoriedaten laden
//
$cats_data = cats_get_cats_data($cat_id); // Die Daten der Kategorien
$cats_counter = count($cats_data); // Anzahl der Kategorien

$cat_ids = array($cat_id); // Array fuer IDs aller Kategorien
for($i = 0; $i < $cats_counter; $i++) {
	$cat_ids[] = $cats_data[$i]['cat_id']; // ID zu Array hinzufuegen
	$cats_data[$i]['cat_depth'] -= $cat_data['cat_depth']; // Die Tiefe der Kategorie zwecks Uebersicht senken (damit die gewaehlte Kategorie die "Wurzel"kategorie ist)
}


//
// Forendaten laden
//
$db->query("SELECT t1.*, t2.poster_id AS forum_last_post_poster_id, t2.post_time AS forum_last_post_time, t2.post_title AS forum_last_post_title, t3.user_nick AS forum_last_post_poster_nick, t4.smiley_gfx AS last_post_pic_gfx FROM ".TBLPFX."forums AS t1 LEFT JOIN ".TBLPFX."posts AS t2 ON t2.post_id=t1.forum_last_post_id LEFT JOIN ".TBLPFX."users AS t3 ON t2.poster_id=t3.user_id LEFT JOIN ".TBLPFX."smilies AS t4 ON t2.post_pic=t4.smiley_id WHERE t1.cat_id IN ('".implode("','",$cat_ids)."') ORDER BY t1.order_id");
$forums_data = $db->raw2array();
$forums_counter = count($forums_data);

$forum_ids = array();
for($i = 0; $i < $forums_counter; $i++)
	$forum_ids[] = $forums_data[$i]['forum_id'];


// Moderatorendaten laden (User)
//
$db->query("SELECT t1.auth_id AS user_id, t1.forum_id, t2.user_nick FROM ".TBLPFX."forums_auth AS t1, ".TBLPFX."users AS t2 WHERE t1.auth_type='0' AND t1.forum_id IN ('".implode("','",$forum_ids)."') AND t1.auth_is_mod='1' AND t2.user_id=t1.auth_id");
$mods_users = $db->raw2array();


//
// Moderatorendaten laden (Gruppen)
//
$db->query("SELECT t1.auth_id AS group_id, t1.forum_id, t2.group_name FROM ".TBLPFX."forums_auth AS t1, ".TBLPFX."groups AS t2 WHERE t1.auth_type='1' AND t1.forum_id IN ('".implode("','",$forum_ids)."') AND t1.auth_is_mod='1' AND t2.group_id=t1.auth_id");
$mods_groups = $db->raw2array();


//
// Die "offenen" Kategorien
//
$open_cats = array(); // Array fuer die IDs der Kategorien, die offen sein sollen
if(!isset($_SESSION['s_open_cats'])) { // Falls noch keine offenen Kategorien existieren
	$db->query("SELECT cat_id FROM ".TBLPFX."cats WHERE cat_standard_status='1'"); // Laedt alle IDs der Kategorien, die standardmaeßig offen sind

	while(list($akt_cat_id) = $db->fetch_array())
		$open_cats[] = $akt_cat_id; // ID zu Array hinzufuegen

	$_SESSION['s_open_cats'] = implode(',',$open_cats); // Die IDs in der Session speichern
}

$open_cats = explode(',',$_SESSION['s_open_cats']);

if(isset($_GET['open_cat'])) { // Falls eine Kategorie geoeffnet werden soll
	if(in_array($_GET['open_cat'],$open_cats) == FALSE) // Falls ID noch nicht in dem Array ist...
		$open_cats[] = $_GET['open_cat']; // ...ID zu Array hinzufuegen
}
elseif(isset($_GET['close_cat'])) { // Falls eine Kategorie geschlossen werden soll
	if(in_array($_GET['close_cat'],$open_cats) == TRUE) { // Falls die Kategorie auch in dem Array ist...
		while(list($akt_key,$akt_cat) = each($open_cats)) { // Das Array nach der ID durchsuchen
			if($akt_cat == $_GET['close_cat']) { // Falls das die gewuenschte ID ist...
				unset($open_cats[$akt_key]); break; // ...loeschen und die Schleife unterbrechen
			}
		}
	}
}
elseif(in_array($cat_id,$open_cats) == FALSE) { // Falls die gewaehlte Kategorie nicht in dem Array ist...
	$open_cats[] = $cat_id; // sie hinzufuegen

}

$_SESSION['s_open_cats'] = implode(',',$open_cats); // Die IDs auf jeden Fall in der Session speichern (da sie sich eventuell geaendert haben)


//
// Template laden
//
$viewcat_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewcat']);


//
// Foren- und Kategorieansicht erstellen
//
for($i = 0; $i < $cats_counter; $i++) { // Jede Kategorie durchlaufen lassen
	$viewcat_tpl->blocks['catrow']->blocks['forumrow']->reset_tpl(); // Template fuer Foren zuruecksetzen

	$akt_cat = &$cats_data[$i]; // Zur Vereinfachung (vor allem im Template) eine Referenz anlegen
	$akt_appendix = ''; // Hier wird spaeter der Leerraum eingefuegt, um Kategorien/Foren einzuruecken

	for($j = 0; $j < $akt_cat['cat_depth']; $j++)
		$akt_appendix .= '<img src="'.$TEMPLATE_PATH.'/'.$TCONFIG['images']['blank'].'" border="0" alt="" />';

	$akt_cat_childs_counter = ($akt_cat['cat_r'] - $akt_cat['cat_l'] - 1) / 2; // Anzahl der Unterkategorien dieser Kategorie

	if(in_array($akt_cat['cat_id'],$open_cats) == TRUE) { // Falls diese Kategorie geöffnet sein soll...
		$x = FALSE;

		$akt_plus_minus_pic = '<a href="index.php?faction=viewcat&amp;cat_id='.$cat_id.'&amp;close_cat='.$akt_cat['cat_id'].'&amp;'.$MYSID.'"><img src="'.$TEMPLATE_PATH.'/'.$TCONFIG['images']['minus'].'" border="0" alt="" /></a>';

		for($j = 0; $j < $forums_counter; $j++) {
			if($forums_data[$j]['cat_id'] == $akt_cat['cat_id']) {
				$akt_forum = &$forums_data[$j];
				$akt_forum_mods = array(); // Array fuer die Moderatoren

				$x = TRUE;

				while(list($akt_key) = each($mods_users)) { // Erst werden alle Mitglieder-Moderatoren ueberprueft
					if($mods_users[$akt_key]['forum_id'] != $akt_forum['forum_id']) continue; // Falls das Mitglied Moderator des aktuellen Forums ist

					$akt_forum_mods[] = '<a href="index.php?faction=viewprofile&amp;profile_id='.$mods_users[$akt_key]['user_id'].'&amp;'.$MYSID.'">'.$mods_users[$akt_key]['user_nick'].'</a>'; // Aktuelles Mitglied zu Array mit Moderatoren des aktuellen Forums hinzufuegen
					unset($mods_users[$akt_key]); // Mitglied kann aus Array geloescht werden
				}
				reset($mods_users); // Array resetten (Pointer auf Position 1 setzen)



				while(list($akt_key) = each($mods_groups)) { // Erst werden alle Gruppen-Moderatoren ueberprueft
					if($mods_groups[$akt_key]['forum_id'] != $akt_forum['forum_id']) continue; // Falls die Gruppe Moderator des aktuellen Forums ist

					$akt_forum_mods[] = '<a href="index.php?faction=viewgroup&amp;group_id='.$mods_groups[$akt_key]['group_id'].'&amp;'.$MYSID.'">'.$mods_groups[$akt_key]['group_name'].'</a>'; // Aktuelle Gruppe zu Array mit Moderatoren des aktuellen Forums hinzufuegen
					unset($mods_groups[$akt_key]); // Mitglied kann aus Array geloescht werden
				}
				reset($mods_groups); // Array resetten (Pointer auf Position 1 setzen)

				$akt_forum_mods = implode(', ',$akt_forum_mods);

				$akt_new_post_status = '<img src="'.(($forums_data[$i]['forum_last_post_id'] != 0 && isset($c_forums[$forums_data[$i]['forum_id']]) == TRUE && $c_forums[$forums_data[$i]['forum_id']] < $forums_data[$i]['forum_last_post_time']) ? $TEMPLATE_PATH.'/'.$TCONFIG['images']['forum_on'] : $TEMPLATE_PATH.'/'.$TCONFIG['images']['forum_off']).'" alt="" />';

				if($akt_forum['forum_last_post_id'] != 0) {
					$akt_last_post_pic = ($akt_forum['last_post_pic_gfx'] == '') ? '' : '<img src="'.$akt_forum['last_post_pic_gfx'].'" alt="" border="" />';
					if(strlen($akt_forum['forum_last_post_title']) > 22) $akt_last_post_link = '<a href="index.php?faction=viewtopic&amp;post_id='.$akt_forum['forum_last_post_id'].'&amp;'.$MYSID.'#post'.$akt_forum['forum_last_post_id'].'" title="'.myhtmlentities($akt_forum['forum_last_post_title']).'">'.myhtmlentities(substr($akt_forum['forum_last_post_title'],0,22)).'...</a>';
					else $akt_last_post_link = '<a href="index.php?faction=viewtopic&amp;post_id='.$akt_forum['forum_last_post_id'].'&amp;'.$MYSID.'#post'.$akt_forum['forum_last_post_id'].'">'.myhtmlentities($akt_forum['forum_last_post_title']).'</a>';

					$akt_last_post_text = $akt_last_post_link.' ('.$lng['by'].' <a href="index.php?faction=viewprofile&amp;profile_id='.$akt_forum['forum_last_post_poster_id'].'&amp;'.$MYSID.'">'.$akt_forum['forum_last_post_poster_nick'].'</a>)<br />'.format_date($akt_forum['forum_last_post_time']);
				}
				else {
					$akt_last_post_pic = '';
					$akt_last_post_text = $lng['No_last_post'];
				}

				$viewcat_tpl->blocks['catrow']->blocks['forumrow']->parse_code(FALSE,TRUE);
			}
		}

		if($x == FALSE) $viewcat_tpl->blocks['catrow']->blocks['forumrow']->blank_tpl();
	}
	else { // Falls die Kategorie _nicht_ geoeffnet sein soll...
		$akt_plus_minus_pic = '<a href="index.php?faction=viewcat&amp;cat_id='.$cat_id.'&amp;open_cat='.$akt_cat['cat_id'].'&amp;'.$MYSID.'"><img src="'.$TEMPLATE_PATH.'/'.$TCONFIG['images']['plus'].'" border="0" alt="" /></a>'; // ...Bild fuer "Oeffnen" bestimmen
		$i += $akt_cat_childs_counter; // Saemtliche Unterkategorien ueberspringen
		$viewcat_tpl->blocks['catrow']->blocks['forumrow']->blank_tpl(); // Keien Foren ausgeben
	}

	$viewcat_tpl->blocks['catrow']->parse_code(FALSE,TRUE); // Templateblock erstellen
}

get_navbar_cats($cat_id);

include_once('pheader.php');
show_navbar();
$viewcat_tpl->parse_code(TRUE);
include_once('ptail.php');

?>