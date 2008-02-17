<?php
/**
*
* Tritanium Bulletin Board 2 - viewforum.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$forum_id = isset($_GET['forum_id']) ? intval($_GET['forum_id']) : 0;
$z = isset($_GET['z']) ? $_GET['z'] : 1;

if(!$forum_data = get_forum_data($forum_id)) die('Forum existiert nicht/Kann Forendaten nicht laden!');


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
}
elseif($USER_DATA['user_is_admin'] != 1 && $USER_DATA['user_is_supermod'] != 1) {
	if(!$auth_data = get_auth_forum_user($forum_id,$USER_ID,array('auth_view_forum','auth_is_mod'))) {
		$auth_data = array(
			'auth_view_forum'=>$forum_data['auth_members_view_forum'],
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


update_forum_cookie($forum_id);

if(isset($_GET['mark'])) {
	$c_topics = isset($_COOKIE['c_topics']) ? explode('x',$_COOKIE['c_topics']) : array();
	while(list($akt_key,$akt_value) = each($c_topics)) {
		$akt_value = explode('y',$akt_value);
		if($akt_value[0] == $forum_id) {
			unset($c_topics[$akt_key]);
			break;
		}
	}
	$c_topics = implode('x',$c_topics);
	setcookie('c_topics',$c_topics,time()+31536000,'/');
	$_COOKIE['c_topics'] = $c_topics;
}


//
// Die Seitenanzeige
//
$topics_counter = get_forum_topics_counter($forum_id);

$page_listing = create_page_listing($topics_counter,$CONFIG['topics_per_page'],$z,"<a href=\"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;z=%1\$s&amp;$MYSID\">%2\$s</a>");
$start = $z*$CONFIG['topics_per_page']-$CONFIG['topics_per_page'];


//
// Template laden
//
$viewforum_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewforum']);


if($topics_counter == 0) $viewforum_tpl->blocks['no_topics']->parse_code(); // Falls es keine Themen gibt...
else { // ...und falls doch
	$c_forums = array();
	$c_forums_temp = isset($_COOKIE['c_forums']) ? explode('x',$_COOKIE['c_forums']) : array();
	while(list(,$akt_value) = each($c_forums_temp)) {
		$akt_value = explode('_',$akt_value);
		$c_forums[$akt_value[0]] = $akt_value[1];
	}

	$c_topics = array();
	$c_topics_temp = isset($_COOKIE['c_topics']) ? explode('x',$_COOKIE['c_topics']) : $c_topics_temp = array();
	while(list($akt_key,$akt_value_2) = each($c_topics_temp)) {
		$akt_value_2 = explode('y',$akt_value_2);
		if($akt_value_2[0] == $forum_id) {
			$akt_value_2[1] = explode('z',$akt_value_2[1]);
			while(list(,$akt_value) = each($akt_value_2[1])) {
				$akt_value = explode('_',$akt_value);
				$c_topics[$akt_value[0]] = $akt_value[1];
			}
		}
	}


	//
	// Themenbilder laden (werden spaeter oefters gebraucht)
	//
	$PPICS_DATA = cache_get_ppics_data();


	//
	// Die Themen-Daten laden und weiter...
	//
	$DB->query("SELECT t1.*,t2.post_time AS topic_last_post_time, t2.poster_id AS topic_last_post_poster_id,t3.user_nick AS topic_poster_nick, t2.post_guest_nick AS topic_last_post_guest_nick, t4.user_nick AS topic_last_post_poster_nick FROM ".TBLPFX."topics AS t1, ".TBLPFX."posts AS t2 LEFT JOIN ".TBLPFX."users AS t3 ON t1.poster_id=t3.user_id LEFT JOIN ".TBLPFX."users AS t4 ON t2.poster_id=t4.user_id WHERE t1.forum_id='$forum_id' AND t1.topic_last_post_id=t2.post_id ORDER BY t1.topic_is_pinned DESC, t2.post_time DESC LIMIT $start,".$CONFIG['topics_per_page']);
	while($akt_topic_data = $DB->fetch_array()) {
		$akt_topic_prefix = '';

		if($akt_topic_data['topic_moved_id'] != 0) { // Falls das Thema nur eine Referenz zu einem verschobenem Thema ist...
			$akt_topic_prefix .= $LNG['Moved'].': '; // ...das hinschreiben...

			$akt_topic_data['topic_replies_counter'] = '-'; // ...den Antwortenzaehler auf "nichts" setzen...
			$akt_topic_data['topic_views_counter'] = '-'; // ...den Viewszaehler auf "nichts" setzen...
			$topic_last_post = '-'; // ...und den neuesten Beitrag auf "nichts" setzen
		}
		else { // Falls es sich um ein normales Thema handelt die normalen Sachen erledigen
			if($akt_topic_data['topic_is_pinned'] == 1) $akt_topic_prefix .= $LNG['Important'].': ';
			if($akt_topic_data['topic_poll'] == 1) $akt_topic_prefix .= $LNG['Poll'].': ';

			if($akt_topic_data['topic_last_post_poster_id'] == 0)
				$topic_last_post_poster = $akt_topic_data['topic_last_post_guest_nick'];
			else $topic_last_post_poster = '<a href="index.php?faction=viewprofile&amp;profile_id='.$akt_topic_data['topic_last_post_poster_id'].'&amp;'.$MYSID.'">'.$akt_topic_data['topic_last_post_poster_nick'].'</a>';
			$topic_last_post = format_date($akt_topic_data['topic_last_post_time']).'<br />'.$LNG['by'].' '.$topic_last_post_poster.' <a href="index.php?faction=viewtopic&amp;topic_id='.$akt_topic_data['topic_id'].'&amp;z=last&amp;'.$MYSID.'#post'.$akt_topic_data['topic_last_post_id'].'">&#187;</a>';

			$akt_topic_data['topic_replies_counter'] = number_format($akt_topic_data['topic_replies_counter'],0,',','.');
			$akt_topic_data['topic_views_counter'] = number_format($akt_topic_data['topic_views_counter'],0,',','.');
		}


		//
		// Der Themen-Author
		//
		$akt_topic_poster_nick = '';
		if($akt_topic_data['poster_id'] == 0) $akt_topic_poster_nick = $akt_topic_data['topic_guest_nick']; // Falls es ein Gast ist...
		else $akt_topic_poster_nick = "<a href=\"index.php?faction=viewprofile&amp;profile_id=".$akt_topic_data['poster_id']."&amp;$MYSID\">".$akt_topic_data['topic_poster_nick'].'</a>'; // ...und falls nicht



		if(isset($c_topics[$akt_topic_data['topic_id']]) == FALSE && isset($c_forums[$forum_id]) == TRUE && $c_forums[$forum_id] < $akt_topic_data['topic_post_time']) {
			update_topic_cookie($akt_topic_data['forum_id'],$akt_topic_data['topic_id'],0);
			$c_topics[$akt_topic_data['topic_id']] = 0;
		}


		//
		// Der "Neue Beitraege"-Status
		//
		if($akt_topic_data['topic_moved_id'] != 0) $akt_topic_status = $TEMPLATE_PATH.'/'.$TCONFIG['images']['blank']; // Falls das Thema verschoben wurde...
		elseif(isset($c_topics[$akt_topic_data['topic_id']]) == TRUE && $c_topics[$akt_topic_data['topic_id']] < $akt_topic_data['topic_last_post_time'])
			$akt_topic_status = $TEMPLATE_PATH.'/'.$TCONFIG['images']['topic_on_open']; // ...falls es neue Beitraege gibt
		else $akt_topic_status = $TEMPLATE_PATH.'/'.$TCONFIG['images']['topic_off_open']; // und falls nicht


		//
		// Das Themen-Bild
		//
		$akt_topic_pic = '';
		while(list(,$akt_ppic) = each($PPICS_DATA)) {
			if($akt_ppic['smiley_id'] == $akt_topic_data['topic_pic']) {
				$akt_topic_pic = '<img src="'.$akt_ppic['smiley_gfx'].'" alt="" />';
				break;
			}
		}
		reset($PPICS_DATA);


		$akt_topic_data['topic_title'] = myhtmlentities($akt_topic_data['topic_title']);

		$viewforum_tpl->blocks['topicrow']->parse_code(FALSE,TRUE);
	}
}

get_navbar_cats($forum_data['cat_id']);
$NAVBAR->addElements('left',array(myhtmlentities($forum_data['forum_name']),''));
$NAVBAR->addElements('right',array($LNG['Mark_topics_read'],"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;mark=all&amp;$MYSID"));

//
// Seite ausgeben
//
include_once('pheader.php');
$viewforum_tpl->parse_code(TRUE);
include_once('ptail.php');

?>