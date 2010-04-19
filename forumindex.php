<?php
/**
*
* Tritanium Bulletin Board 2 - forumindex.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');
require_once('bbcode.php');

//
// Kategoriedaten laden
//
$cats_data = cats_get_cats_data(1);
$cats_counter = count($cats_data);


//
// Forendaten laden
//
$db->query("SELECT t1.*, t2.poster_id AS forum_last_post_poster_id, t2.post_time AS forum_last_post_time, t2.post_title AS forum_last_post_title, t2.post_guest_nick AS forum_last_post_guest_nick, t3.user_nick AS forum_last_post_poster_nick, t5.smiley_gfx AS last_post_pic_gfx FROM ".TBLPFX."forums AS t1 LEFT JOIN ".TBLPFX."posts AS t2 ON t2.post_id=t1.forum_last_post_id LEFT JOIN ".TBLPFX."users AS t3 ON t2.poster_id=t3.user_id LEFT JOIN ".TBLPFX."smilies AS t5 ON t2.post_pic=t5.smiley_id ORDER BY t1.order_id");
$forums_data = $db->raw2array();
$forums_counter = count($forums_data);


//
// Moderatorendaten laden (User)
//
$db->query("SELECT t1.auth_id AS user_id, t1.forum_id, t2.user_nick FROM ".TBLPFX."forums_auth AS t1, ".TBLPFX."users AS t2 WHERE t1.auth_type='0' AND t1.auth_is_mod='1' AND t2.user_id=t1.auth_id");
$mods_users = $db->raw2array();


//
// Moderatorendaten laden (Gruppen)
//
$db->query("SELECT t1.auth_id AS group_id, t1.forum_id, t2.group_name FROM ".TBLPFX."forums_auth AS t1, ".TBLPFX."groups AS t2 WHERE t1.auth_type='1' AND t1.auth_is_mod='1' AND t2.group_id=t1.auth_id");
$mods_groups = $db->raw2array();


//
// Zugriffsdaten laden
//
$forums_auth = array();
if($USER_LOGGED_IN == 1 && $USER_DATA['user_is_admin'] != 1) {
	$db->query("SELECT t1.forum_id,t1.auth_view_forum FROM ".TBLPFX."forums_auth AS t1 WHERE t1.auth_type='0' AND t1.auth_id='$USER_ID'");
	$forums_auth = $db->raw2array();

	$db->query("SELECT t1.forum_id,t1.auth_view_forum FROM ".TBLPFX."forums_auth AS t1, ".TBLPFX."groups_members AS t2 WHERE t1.auth_type='1' AND t1.auth_id=t2.group_id AND t2.member_id='$USER_ID'");
	while($akt_data = $db->fetch_array())
		$forums_auth[] = $akt_data;
}


$c_forums = array();
if(isset($_GET['mark']))
	setcookie('c_forums_all',time(),time()+31536000,'/');

$c_forums_temp = isset($_COOKIE['c_forums']) ? explode('x',$_COOKIE['c_forums']) : array();
while(list(,$akt_value) = each($c_forums_temp)) {
	$akt_value = explode('_',$akt_value);
	$c_forums[$akt_value[0]] = $akt_value[1];
}

$open_cats = array();

if(!isset($_SESSION['s_open_cats'])) {
	for($i = 0; $i < $cats_counter; $i++) {
		if($cats_data[$i]['cat_standard_status'] == 1) $open_cats[] = $cats_data[$i]['cat_id'];
	}
	$_SESSION['s_open_cats'] = implode(',',$open_cats);
}

$open_cats = explode(',',$_SESSION['s_open_cats']);

if(isset($_GET['open_cat'])) {
	if(in_array($_GET['open_cat'],$open_cats) == FALSE) {
		$open_cats[] = $_GET['open_cat'];
	}
	$_SESSION['s_open_cats'] = implode(',',$open_cats);
}

if(isset($_GET['close_cat'])) {
	if(in_array($_GET['close_cat'],$open_cats) == TRUE) {
		while(list($akt_key,$akt_cat) = each($open_cats)) {
			if($akt_cat == $_GET['close_cat']) {
				unset($open_cats[$akt_key]); break;
			}
		}
	}
	$_SESSION['s_open_cats'] = implode(',',$open_cats);
}


include_once('pheader.php');

if($cats_counter == 0) {
	include_once('pheader.php');
	show_navbar();
	show_message($lng['No_forums_cats'],$lng['message_no_forums']);
	include_once('ptail.php'); exit;
}
else {
	$findex_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['forumindex']);


	//
	// News
	//
	if($CONFIG['news_forum'] != 0 && $CONFIG['show_news_forumindex'] == 1) {
		$db->query("SELECT t2.post_text AS news_text,t2.post_id,t1.topic_replies_counter AS news_comments_counter, t1.topic_title AS news_title, t2.post_enable_html, t2.post_enable_smilies,t2.post_enable_bbcode FROM ".TBLPFX."topics AS t1, ".TBLPFX."posts AS t2 WHERE t1.forum_id='".$CONFIG['news_forum']."' AND t2.post_id=t1.topic_first_post_id ORDER BY t1.topic_post_time DESC LIMIT 1");
		if($db->affected_rows == 1) {
			$news_data = $db->fetch_array();
			$news_comments_link = "<a href=\"index.php?faction=viewtopic&amp;post_id=".$news_data['post_id']."&amp;$MYSID\">".sprintf($lng['x_comments'],$news_data['news_comments_counter']).'</a>';

			$news_data['news_title'] = myhtmlentities($news_data['news_title']);

			if($news_data['post_enable_html'] != 1) $news_data['news_text'] = myhtmlentities($news_data['news_text']); //array_merge($strtr_array,$html_schars_table);
			//if($akt_post['post_enable_smilies'] == 1 && $forum_data['forum_enable_smilies'] == 1) $news_data['news_text'] = strtr($news_data['news_text'],$smilies);
			$news_data['news_text'] = nlbr($news_data['news_text']);
			if($news_data['post_enable_bbcode'] == 1) $news_data['news_text'] = bbcode($news_data['news_text']);


			$findex_tpl->blocks['newsbox']->parse_code();
		}
	}


	//
	// Foren und Kategorien anzeigen
	//
	for($i = 0; $i < $cats_counter; $i++) { // Jede Kategorie durchlaufen lassen
		$findex_tpl->blocks['catrow']->blocks['forumrow']->reset_tpl(); // Template fuer Foren zuruecksetzen

		$akt_cat = &$cats_data[$i]; // Zur Vereinfachung (vor allem im Template) eine Referenz anlegen
		$akt_appendix = ''; // Hier wird spaeter der Leerraum eingefuegt, um Kategorien/Foren einzuruecken

		for($j = 1; $j < $akt_cat['cat_depth']; $j++)
			$akt_appendix .= '<img src="'.$TEMPLATE_PATH.'/'.$TCONFIG['images']['blank'].'" border="0" alt="" />';

		$akt_cat_childs_counter = ($akt_cat['cat_r'] - $akt_cat['cat_l'] - 1) / 2; // Anzahl der Unterkategorien dieser Kategorie

		if(in_array($akt_cat['cat_id'],$open_cats) == TRUE) { // Falls diese Kategorie geöffnet sein soll...
			$x = FALSE;

			$akt_plus_minus_pic = '<a href="index.php?faction=forumindex&amp;close_cat='.$akt_cat['cat_id'].'&amp;'.$MYSID.'"><img src="'.$TEMPLATE_PATH.'/'.$TCONFIG['images']['minus'].'" border="0" alt="" /></a>';

			for($j = 0; $j < $forums_counter; $j++) {
				if($forums_data[$j]['cat_id'] == $akt_cat['cat_id']) {
					$akt_forum = &$forums_data[$j];
					$akt_forum_mods = array(); // Array fuer die Moderatoren

					$x = TRUE;


					//
					// Die Moderatoren (Mitglieder und Gruppen) des aktuellen Forums
					//
					while(list($akt_key) = each($mods_users)) { // Erst werden alle Mitglieder-Moderatoren ueberprueft
						if($mods_users[$akt_key]['forum_id'] != $forums_data[$j]['forum_id']) continue;

						$akt_forum_mods[] = '<a href="index.php?faction=viewprofile&amp;profile_id='.$mods_users[$akt_key]['user_id'].'&amp;'.$MYSID.'">'.$mods_users[$akt_key]['user_nick'].'</a>'; // Aktuelles Mitglied zu Array mit Moderatoren des aktuellen Forums hinzufuegen
						unset($mods_users[$akt_key]); // Mitglied kann aus Array geloescht werden
					}
					reset($mods_users); // Array resetten (Pointer auf Position 1 setzen)

					while(list($akt_key) = each($mods_groups)) { // Erst werden alle Gruppen-Moderatoren ueberprueft
						if($mods_groups[$akt_key]['forum_id'] != $forums_data[$j]['forum_id']) continue;

						$akt_forum_mods[] = '<a href="index.php?faction=viewgroup&amp;group_id='.$mods_groups[$akt_key]['group_id'].'&amp;'.$MYSID.'">'.$mods_groups[$akt_key]['group_name'].'</a>'; // Aktuelle Gruppe zu Array mit Moderatoren des aktuellen Forums hinzufuegen
						unset($mods_groups[$akt_key]); // Mitglied kann aus Array geloescht werden
					}
					reset($mods_groups); // Array resetten (Pointer auf Position 1 setzen)

					$akt_forum_mods = implode(', ',$akt_forum_mods);


					//
					// Das Anzeige, ob neue Beitraege vorhanden sind
					//
					$akt_new_post_status = '<img src="'.(($forums_data[$j]['forum_last_post_id'] != 0 && isset($c_forums[$forums_data[$j]['forum_id']]) == TRUE && $c_forums[$forums_data[$j]['forum_id']] < $forums_data[$j]['forum_last_post_time']) ? $TEMPLATE_PATH.'/'.$TCONFIG['images']['forum_on'] : $TEMPLATE_PATH.'/'.$TCONFIG['images']['forum_off']).'" alt="" />';


					//
					// Der Zugriff zu diesem Forum
					//
					$akt_auth_view_forum = 1;
					if($USER_LOGGED_IN == 0) {
						if($akt_forum['auth_guests_view_forum'] == 0)
							$akt_auth_view_forum = 0;
					}
					else {
						if($USER_DATA['user_is_admin'] != 1) {
							if($akt_forum['auth_members_view_forum'] == 1) {
								while(list($akt_key,$akt_data) = each($forums_auth)) {
									if($akt_data['forum_id'] != $akt_forum['forum_id']) continue;

									unset($forums_auth[$akt_key]);

									if($akt_data['auth_view_forum'] == 0) {
										$akt_auth_view_forum = 0;
										break;
									}
								}
							}
							else {
								$akt_auth_view_forum = 0;
								while(list($akt_key,$akt_data) = each($forums_auth)) {
									if($akt_data['forum_id'] != $akt_forum['forum_id']) continue;

									unset($forums_auth[$akt_key]);

									if($akt_data['auth_view_forum'] == 1) {
										$akt_auth_view_forum = 1;
										break;
									}
								}
							}
							reset($forums_auth);
						}
					}


					//
					// Der neueste Beitrag
					//
					$akt_last_post_pic = $akt_last_post_text = '';
					if($akt_forum['forum_last_post_id'] != 0) {
						if($akt_auth_view_forum == 1) {
							$akt_last_post_pic = ($akt_forum['last_post_pic_gfx'] == '') ? '' : '<img src="'.$akt_forum['last_post_pic_gfx'].'" alt="" border="" />';
							if(strlen($akt_forum['forum_last_post_title']) > 22) $akt_last_post_link = '<a href="index.php?faction=viewtopic&amp;post_id='.$akt_forum['forum_last_post_id'].'&amp;'.$MYSID.'#post'.$akt_forum['forum_last_post_id'].'" title="'.myhtmlentities($akt_forum['forum_last_post_title']).'">'.myhtmlentities(substr($akt_forum['forum_last_post_title'],0,22)).'...</a>';
							else $akt_last_post_link = '<a href="index.php?faction=viewtopic&amp;post_id='.$akt_forum['forum_last_post_id'].'&amp;'.$MYSID.'#post'.$akt_forum['forum_last_post_id'].'">'.myhtmlentities($akt_forum['forum_last_post_title']).'</a>';

							if($akt_forum['forum_last_post_poster_id'] == 0) $akt_last_post_poster_nick = $akt_forum['forum_last_post_guest_nick'];
							else $akt_last_post_poster_nick = '<a href="index.php?faction=viewprofile&amp;profile_id='.$akt_forum['forum_last_post_poster_id'].'&amp;'.$MYSID.'">'.$akt_forum['forum_last_post_poster_nick'].'</a>';

							$akt_last_post_text = $akt_last_post_link.' ('.$lng['by'].' '.$akt_last_post_poster_nick.')<br />'.format_date($akt_forum['forum_last_post_time']);
						}
					}
					else
						$akt_last_post_text = $lng['No_last_post'];


					$findex_tpl->blocks['catrow']->blocks['forumrow']->parse_code(FALSE,TRUE);
				}
			}

			if($x == FALSE) $findex_tpl->blocks['catrow']->blocks['forumrow']->blank_tpl();
		}
		else { // Falls die Kategorie _nicht_ geoeffnet sein soll...
			$akt_plus_minus_pic = '<a href="index.php?faction=forumindex&amp;open_cat='.$akt_cat['cat_id'].'&amp;'.$MYSID.'"><img src="'.$TEMPLATE_PATH.'/'.$TCONFIG['images']['plus'].'" border="0" alt="" /></a>'; // ...Bild fuer "Oeffnen" bestimmen
			$i += $akt_cat_childs_counter; // Saemtliche Unterkategorien ueberspringen
			$findex_tpl->blocks['catrow']->blocks['forumrow']->blank_tpl(); // Keine Foren ausgeben
		}

		$findex_tpl->blocks['catrow']->parse_code(FALSE,TRUE); // Templateblock erstellen
	}


	//
	// "Wer ist online?"-Box
	//
	if($CONFIG['enable_wio'] == 1 && $CONFIG['show_wio_forumindex'] == 1) {
		$online_guests_counter = $online_members_counter = $online_ghosts_counter = $online_users_counter = 0;
		$members = array();
		$members_checks = array();
		$guests = '';

		$db->query("SELECT t1.*, t2.user_nick AS session_user_nick FROM ".TBLPFX."sessions AS t1 LEFT JOIN ".TBLPFX."users AS t2 ON t1.session_user_id=t2.user_id WHERE session_last_update>".unixtstamp2sqltstamp(time()-$CONFIG['wio_timeout']*60));

		while($akt_wio = $db->fetch_array()) {
			if($akt_wio['session_user_id'] == 0) $online_guests_counter++;
			elseif($akt_wio['session_is_ghost'] == 1) $online_ghosts_counter++;
			else {
				if(in_array($akt_wio['session_user_id'],$members_checks) == FALSE) {
					$online_members_counter++;
					$members[] = '<a href="index.php?faction=viewprofile&amp;profile_id='.$akt_wio['session_user_id'].'&amp;'.$MYSID.'">'.$akt_wio['session_user_nick'].'</a>';
					$members_checks[] = $akt_wio['session_user_id'];
				}
			}
		}

		$online_users_counter = $online_guests_counter+$online_ghosts_counter+$online_members_counter;
		if($CONFIG['online_users_record'] == '')
			$online_users_record = array('','');
		else
			$online_users_record = explode(',',$CONFIG['online_users_record']);

		if($online_users_counter > $online_users_record[0]) {
			$online_users_record = array($online_users_counter,time());
			$db->query("UPDATE ".TBLPFX."config SET config_value='".implode(',',$online_users_record)."' WHERE config_name='online_users_record'");
		}

		$members = implode(', ',$members);

		if($online_members_counter == 0) $online_members_counter = $lng['no_members'];
		elseif($online_members_counter == 1) $online_members_counter = $lng['one_member'];
		else $online_members_counter = sprintf($lng['x_members'],$online_members_counter);

		if($online_ghosts_counter == 0) $online_ghosts_counter = $lng['no_ghosts'];
		elseif($online_ghosts_counter == 1) $online_ghosts_counter = $lng['one_ghost'];
		else $online_ghosts_counter = sprintf($lng['x_ghosts'],$online_ghosts_counter);

		if($online_guests_counter == 0) $online_guests_counter = $lng['no_guests'];
		elseif($online_guests_counter == 1) $online_guests_counter = $lng['one_guest'];
		else $online_guests_counter = sprintf($lng['x_guests'],$online_guests_counter);

		$wio_text = sprintf($lng['wio_text'],$online_guests_counter,$online_ghosts_counter,$online_members_counter,$online_users_counter,format_date($online_users_record[1],TRUE),$online_users_record[0]);

		$findex_tpl->blocks['wiobox']->parse_code();
	}


	//
	// Boardstatistiken-Box
	//
	if($CONFIG['show_boardstats_forumindex'] == 1) {
		$members_counter = get_user_counter();
		$topics_counter = get_topics_counter();
		$posts_counter = get_posts_counter();

		$board_stats_text = sprintf($lng['board_stats_text'],$members_counter,$posts_counter,$topics_counter,'<a href="index.php?faction=viewprofile&amp;profile_id='.$CONFIG['newest_user_id'].'&amp;'.$MYSID.'">'.$CONFIG['newest_user_nick'].'</a>');

		$findex_tpl->blocks['boardstatsbox']->parse_code();
	}


	//
	// "Neueste Beitraege"-Box
	//
	if($CONFIG['show_latest_posts_forumindex'] == 1) {
		$latest_posts_forums = array();
		for($i = 0; $i < $forums_counter; $i++) {
			if($forums_data[$i]['forum_show_latest_posts'] == 1)
				$latest_posts_forums[] = $forums_data[$i]['forum_id'];
		}

		if(count($latest_posts_forums) > 0) {
			$db->query("SELECT t1.post_id,t1.post_guest_nick,t1.poster_id,t1.post_time,t1.post_title,t2.user_nick AS poster_nick FROM ".TBLPFX."posts AS t1 LEFT JOIN ".TBLPFX."users AS t2 ON t1.poster_id=t2.user_id WHERE t1.forum_id IN ('".implode("','",$latest_posts_forums)."') ORDER BY t1.post_time DESC LIMIT ".$CONFIG['max_latest_posts']);
			if($db->affected_rows > 0) {
				while($akt_latest_post = $db->fetch_array()) {
					$akt_poster_link = ($akt_latest_post['poster_id'] == 0) ? $akt_latest_post['post_guest_nick'] : '<a href="index.php?faction=viewprofile&amp;profile_id='.$akt_latest_post['poster_id'].'&amp;'.$MYSID.'">'.$akt_latest_post['poster_nick'].'</a>';
					$akt_latest_post_text = sprintf($lng['latest_post_text'],$akt_latest_post['post_title'],$akt_poster_link,format_date($akt_latest_post['post_time']),$akt_latest_post['post_id'],$akt_latest_post['poster_id']);
					$findex_tpl->blocks['latestpostsbox']->blocks['postrow']->parse_code(FALSE,TRUE);
				}
				$findex_tpl->blocks['latestpostsbox']->parse_code();
			}
		}
	}

	show_navbar($CONFIG['board_name'],'',"<a href=\"index.php?faction=forumindex&amp;mark=all&amp;$MYSID\">".$lng['Mark_forums_read'].'</a>');
	$findex_tpl->parse_code(TRUE);
}

include_once('ptail.php');

?>