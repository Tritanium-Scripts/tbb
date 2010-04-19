<?php
/**
*
* Tritanium Bulletin Board 2 - edittopic.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/
/**
*
* Tritanium Bulletin Board 2 - edittopic.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : 0;
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'edit';

if($USER_LOGGED_IN != 1) die('Nit eingeloggt!');
elseif(!$topic_data = get_topic_data($topic_id)) die('Kann Themendaten nicht laden!');
elseif(!$forum_data = get_forum_data($topic_data['forum_id'])) die('Kann Forendaten nicht laden!');

$forum_id = $forum_data['forum_id'];


//
// Beginn Authentifizierung
//
if($USER_DATA['user_is_admin'] != 1) {
	if(!$auth_data = get_auth_forum_user($forum_id,$USER_ID,array('auth_edit_posts','auth_is_mod'))) {
		$auth_data = array(
			'auth_edit_posts'=>$forum_data['auth_members_edit_posts'],
			'auth_is_mod'=>0
		);
	}
}
//
// Ende Authentifizierung
//

add_navbar_items(array($forum_data['forum_name'],"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID"),array($topic_data['topic_title'],"index.php?faction=viewtopic&amp;topic_id=$topic_id&amp;$MYSID"));

if($mode == 'edit') {
	if($USER_ID != $topic_data['poster_id'] && $USER_DATA['user_is_admin'] != 1 && $auth_data['auth_is_mod'] != 1) die('Kein Zugriff!');

	$error = '';

	$p_title = isset($_POST['p_title']) ? $_POST['p_title'] : $topic_data['topic_title'];
	$p_ppic_id = isset($_POST['p_ppic_id']) ? $_POST['p_ppic_id'] : $topic_data['topic_pic'];

	if(isset($_GET['doit'])) {
		if(trim($p_title) == '') $error = $LNG['error_no_title'];
		else {
			$DB->query("UPDATE ".TBLPFX."topics SET topic_title='$p_title', topic_pic='$p_ppic_id' WHERE topic_id='$topic_id'");
			header("Location: index.php?faction=viewtopic&topic_id=$topic_id&$MYSID"); exit;
		}
	}

	multimutate('p_title');

	$edittopic_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['edittopic_edit']);

	$ppics_box = get_ppics_box($p_ppic_id);

	if($error != '') $edittopic_tpl->blocks['errorrow']->parse_code();

	add_navbar_items(array($LNG['Edit_topic'],''));

	include_once('pheader.php');
	show_navbar();
	$edittopic_tpl->parse_code(TRUE);
	include_once('ptail.php');
}
else {
	if($USER_DATA['user_is_admin'] != 1 && $auth_data['auth_is_mod'] != 1) die('Kein Zugriff!');
	switch(@$_GET['mode']) {
		case 'pinn':
			$new_pinned_status = ($topic_data['topic_is_pinned'] == 1) ? 0 : 1;

			$DB->query("UPDATE ".TBLPFX."topics SET topic_is_pinned='$new_pinned_status' WHERE topic_id='$topic_id'");

			header("Location: index.php?faction=viewtopic&topic_id=$topic_id&$MYSID"); exit;
		break;

		case 'openclose':
			$new_topic_status = ($topic_data['topic_status'] == 1) ? 0 : 1;

			$DB->query("UPDATE ".TBLPFX."topics SET topic_status='$new_topic_status' WHERE topic_id='$topic_id'");

			header("Location: index.php?faction=viewtopic&topic_id=$topic_id&$MYSID"); exit;
		break;

		case 'delete':
			$topic_posts_ids = array();
			$DB->query("SELECT post_id FROM ".TBLPFX."posts WHERE topic_id='$topic_id'");
			while(list($akt_post_id) = $DB->fetch_array())
				$topic_posts_ids[] = $akt_post_id;

			$topic_posts_counter = count($topic_posts_ids);

			$DB->query("SELECT COUNT(*) AS poster_posts_counter, poster_id FROM ".TBLPFX."posts WHERE topic_id='$topic_id' GROUP BY poster_id");
			$DB_data = $DB->raw2array();
			while(list(,$akt_data) = each($DB_data)) {
				$DB->query("UPDATE ".TBLPFX."users SET user_posts=user_posts-".$akt_data['poster_posts_counter']." WHERE user_id='".$akt_data['poster_id']."'");
			}

			$DB->query("UPDATE ".TBLPFX."forums SET forum_posts_counter=forum_posts_counter-$topic_posts_counter, forum_topics_counter=forum_topics_counter-1 WHERE forum_id='$forum_id'");
			$DB->query("DELETE FROM ".TBLPFX."topics WHERE topic_id='$topic_id'");
			$DB->query("DELETE FROM ".TBLPFX."posts WHERE post_id IN ('".implode("','",$topic_posts_ids)."')");
			$DB->query("DELETE FROM ".TBLPFX."topics_subscriptions WHERE topic_id='$topic_id'");

			if($topic_data['topic_poll'] == 1) {
				$DB->query("SELECT poll_id FROM ".TBLPFX."polls WHERE topic_id='$topic_id'");
				if($DB->affected_rows == 1) {
					list($topic_poll_id) = $DB->fetch_array();

					$DB->query("DELETE FROM ".TBLPFX."polls WHERE poll_id='$topic_poll_id'");
					$DB->query("DELETE FROM ".TBLPFX."polls_options WHERE poll_id='$topic_poll_id'");
					$DB->query("DELETE FROM ".TBLPFX."polls_votes WHERE poll_id='$topic_poll_id'");
				}
			}

			if(in_array($forum_data['forum_last_post_id'],$topic_posts_ids) == TRUE) update_forum_last_post($forum_id);
			header("Location: index.php?faction=viewforum&forum_id=$forum_id&$MYSID"); exit;
		break;

		case 'move':
			$p_target_forum_id = isset($_POST['p_target_forum_id']) ? $_POST['p_target_forum_id'] : 0;
			$p_create_reference = 1;

			add_navbar_items(array($LNG['Move_topic'],''));

			$error = '';

			if(isset($_GET['doit'])) {
				$p_create_reference = isset($_POST['p_create_reference']) ? 1 : 0;

				if(!$target_forum_data = get_forum_data($p_target_forum_id)) $error = $LNG['error_invalid_forum'];
				else {
					$DB->query("UPDATE ".TBLPFX."topics SET forum_id='$p_target_forum_id' WHERE topic_id='$topic_id'");
					$DB->query("UPDATE ".TBLPFX."posts SET forum_id='$p_target_forum_id' WHERE topic_id='$topic_id'");

					if($topic_data['topic_poll'] != 0)
						$DB->query("UPDATE ".TBLPFX."polls SET forum_id='$p_target_forum_id' WHERE topic_id='$topic_id'");

					$DB->query("SELECT COUNT(*) AS topic_posts_counter FROM ".TBLPFX."posts WHERE topic_id='$topic_id'");
					list($topic_posts_counter) = $DB->fetch_array();

					$DB->query("UPDATE ".TBLPFX."forums SET forum_topics_counter=forum_topics_counter-1, forum_posts_counter=forum_posts_counter-$topic_posts_counter WHERE forum_id='$forum_id'");
					$DB->query("UPDATE ".TBLPFX."forums SET forum_topics_counter=forum_topics_counter+1, forum_posts_counter=forum_posts_counter+$topic_posts_counter WHERE forum_id='$p_target_forum_id'");

					if($p_create_reference == 1)
						$DB->query("INSERT INTO ".TBLPFX."topics (forum_id,poster_id,topic_status,topic_pic,topic_poll,topic_first_post_id,topic_last_post_id,topic_moved_id,topic_post_time,topic_title,topic_guest_nick) VALUES ('$forum_id','".$topic_data['poster_id']."','".$topic_data['topic_status']."','".$topic_data['topic_pic']."','".$topic_data['topic_poll']."','".$topic_data['topic_first_post_id']."','".$topic_data['topic_first_post_id']."','$topic_id','".$topic_data['topic_post_time']."','".$topic_data['topic_title']."','".$topic_data['topic_guest_nick']."')");

					update_forum_last_post($forum_id);
					update_forum_last_post($p_target_forum_id);

					include_once('pheader.php');
					show_navbar();
					show_message($LNG['Topic_moved'],$LNG['message_topic_moved'].'<br />'.sprintf($LNG['click_here_moved_topic'],"<a href=\"index.php?faction=viewtopic&amp;topic_id=$topic_id&amp;$MYSID\">",'</a>'));
					include_once('ptail.php'); exit;
				}
			}


			$c = ' checked="checked"';
			$checked['reference'] = ($p_create_reference == 1) ? $c : '';


			//
			// Template laden
			//
			$edittopic_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['edittopic_move']);


			//
			// Kategorie- und Forendaten laden
			//
			$cats_data = cats_get_cats_data();
			$DB->query("SELECT forum_id,forum_name,cat_id FROM ".TBLPFX."forums WHERE forum_id<>'$forum_id'");
			$forums_data = $DB->raw2array();


			//
			// Auswahlmenue fuer das Zielforum erstellen
			//
			while(list(,$akt_cat) = each($cats_data)) {
				$akt_prefix = '';
				for($i = 1; $i < $akt_cat['cat_depth']; $i++)
					$akt_prefix .= '--';

				$akt_option_value = '';
				$akt_option_text = $akt_prefix.' ('.$akt_cat['cat_name'].')';
				$edittopic_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);

				while(list($akt_key,$akt_forum) = each($forums_data)) {
					if($akt_forum['cat_id'] == $akt_cat['cat_id']) {
						$akt_option_value = $akt_forum['forum_id'];
						$akt_option_text = $akt_prefix.'-- '.$akt_forum['forum_name'];
						$edittopic_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);

						unset($forums_data[$akt_key]);
					}
				}
				reset($forums_data);
			}

			if(count($forums_data) > 0) { // Falls noch mehr als ein Forum uebrig ist (es also noch Foren ohne Kategorie gibt)
				$akt_option_values = $akt_option_text = '';
				$edittopic_tpl->blocks['optionrow']->parse_code(FALSE,TRUE); // Leerzeile einfuegen

				while(list(,$akt_forum) = each($forums_data)) {
					$akt_option_value = $akt_forum['forum_id'];
					$akt_option_text = $akt_forum['forum_name'];
					$edittopic_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);
				}
			}


			//
			// Seite ausgeben
			//
			include_once('pheader.php');
			show_navbar();
			$edittopic_tpl->parse_code(TRUE);
			include_once('ptail.php');
		break;
	}
}
?>