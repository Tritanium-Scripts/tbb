<?php
/**
*
* Tritanium Bulletin Board 2 - edittopic.php
* version #2004-01-01-18-38-43
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : 0;
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'edit';

if($user_logged_in != 1) die('Nit eingeloggt!');
elseif(!$topic_data = get_topic_data($topic_id)) die('Kann Themendaten nicht laden!');
elseif(!$forum_data = get_forum_data($topic_data['forum_id'])) die('Kann Forendaten nicht laden!');

$forum_id = $forum_data['forum_id'];

if($mode == 'edit') {
	if($user_id != $topic_data['poster_id'] && $user_data['user_is_admin'] != 1) die('Kein Zugriff!');

	$error = '';

	$p_title = isset($_POST['p_title']) ? $_POST['p_title'] : $topic_data['topic_title'];
	$p_ppic_id = isset($_POST['p_ppic_id']) ? $_POST['p_ppic_id'] : $topic_data['topic_pic'];

	if(isset($_GET['doit'])) {
		if(trim($p_title) == '') $error = $lng['error_no_title'];
		else {
			$db->query("UPDATE ".TBLPFX."topics SET topic_title='$p_title', topic_pic='$p_ppic_id' WHERE topic_id='$topic_id'");
			header("Location: index.php?faction=viewtopic&topic_id=$topic_id&$MYSID"); exit;
		}
	}

	$edittopic_tpl = new template;
	$edittopic_tpl->load($template_path.'/'.$tpl_config['tpl_edittopic_edit']);

	$ppics_box = get_ppics_box($p_ppic_id);

	if($error != '') $edittopic_tpl->blocks['errorrow']->parse_code();
	else $edittopic_tpl->unset_block('errorrow');

	$title_add[] = $forum_data['forum_name'];
	$title_add[] = $topic_data['topic_title'];
	$title_add[] = $lng['Edit_topic'];

	include_once('pheader.php');

	show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID\">".$forum_data['forum_name']."</a>\r<a href=\"index.php?faction=viewtopic&amp;topic_id=$topic_id&amp;$MYSID\">".$topic_data['topic_title']."</a>\r".$lng['Edit_topic']);

	$edittopic_tpl->parse_code(TRUE);

	include_once('ptail.php');
}
else {
	if($user_data['user_is_admin'] != 1) die('Kein Zugriff!');
	switch(@$_GET['mode']) {
		case 'pinn':
			$new_pinned_status = ($topic_data['topic_is_pinned'] == 1) ? 0 : 1;

			$db->query("UPDATE ".TBLPFX."topics SET topic_is_pinned='$new_pinned_status' WHERE topic_id='$topic_id'");

			header("Location: index.php?faction=viewtopic&topic_id=$topic_id&$MYSID"); exit;
		break;

		case 'delete':
			$topic_posts_ids = array();
			$db->query("SELECT post_id FROM ".TBLPFX."posts WHERE topic_id='$topic_id'");
			while(list($akt_post_id) = $db->fetch_array())
				$topic_posts_ids[] = $akt_post_id;

			$topic_posts_counter = sizeof($topic_posts_ids);

			$db->query("SELECT COUNT(*) AS poster_posts_counter, poster_id FROM ".TBLPFX."posts WHERE topic_id='$topic_id' GROUP BY poster_id");
			$db_data = $db->raw2array();
			while(list(,$akt_data) = each($db_data)) {
				$db->query("UPDATE ".TBLPFX."users SET user_posts=user_posts-".$akt_data['poster_posts_counter']." WHERE user_id='".$akt_data['poster_id']."'");
			}

			$db->query("UPDATE ".TBLPFX."forums SET forum_posts_counter=forum_posts_counter-$topic_posts_counter, forum_topics_counter=forum_topics_counter-1 WHERE forum_id='$forum_id'");
			$db->query("DELETE FROM ".TBLPFX."topics WHERE topic_id='$topic_id'");
			$db->query("DELETE FROM ".TBLPFX."posts WHERE post_id IN ('".implode("','",$topic_posts_ids)."')");
			$db->query("DELETE FROM ".TBLPFX."posts_text WHERE post_id IN ('".implode("','",$topic_posts_ids)."')");

			if($topic_data['topic_poll'] == 1) {
				$db->query("SELECT poll_id FROM ".TBLPFX."polls WHERE topic_id='$topic_id'");
				if($db->affected_rows == 1) {
					list($topic_poll_id) = $db->fetch_array();

					$db->query("DELETE FROM ".TBLPFX."polls WHERE poll_id='$topic_poll_id'");
					$db->query("DELETE FROM ".TBLPFX."polls_options WHERE poll_id='$topic_poll_id'");
					$db->query("DELETE FROM ".TBLPFX."polls_voters WHERE poll_id='$topic_poll_id'");
				}
			}

			if(in_array($forum_data['forum_last_post_id'],$topic_posts_ids) == TRUE) update_forum_last_post($forum_id);
			header("Location: index.php?faction=viewforum&forum_id=$forum_id&$MYSID"); exit;
		break;
	}
}
?>