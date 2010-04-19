<?php
/**
*
* Tritanium Bulletin Board 2 - editpost.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

$topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : 0;
$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : 0;
$return_to = isset($_GET['return_to']) ? $_GET['return_to'] : 1;

if(!$topic_data = get_topic_data($topic_id)) die('Kann Themendaten nicht laden!');
elseif(!$post_data = get_post_data($topic_id,$post_id)) die('Kann Beitragsdaten nicht laden!');
elseif(!$forum_data = get_forum_data($topic_data['forum_id'])) die('Kann Forumdaten nicht laden!');

$forum_id = $forum_data['forum_id'];

//
// Beginn Authentifizierung
//
if($user_logged_in != 1) {
	include_once('pheader.php');
	show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Not_logged_in']);
	show_message('Not_logged_in','message_forum_not_logged_in','<br />'.$lng['click_here_login'].'<br />'.$lng['click_here_register']);
	include_once('ptail.php'); exit;
}
elseif($user_data['user_is_admin'] != 1) {
	if(!$auth_data = get_auth_data(array('auth_type'=>0,'auth_id'=>$user_id,'auth_forum_id'=>$forum_id))) {
		$auth_data = array(
			'auth_view_forum'=>$forum_data['auth_members_view_forum'],
			'auth_post_topic'=>$forum_data['auth_members_post_topic'],
			'auth_post_reply'=>$forum_data['auth_members_post_reply'],
			'auth_post_poll'=>$forum_data['auth_members_post_poll'],
			'auth_edit_posts'=>$forum_data['auth_members_edit_posts'],
			'auth_is_mod'=>0
		);
	}
	if($auth_data['auth_is_mod'] != 1) {
		if($user_id != $post_data['poster_id'] || $forum_data['auth_members_edit_posts'] != 1 && $auth_data['auth_edit_posts'] != 1 || $forum_data['auth_members_edit_posts'] == 1 && $auth_data['auth_edit_posts'] == 0) {
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

switch(@$_GET['mode']) {
	case 'edit':
		$p_post = isset($_POST['p_post']) ? $_POST['p_post'] : $post_data['post_text'];
		$p_title = isset($_POST['p_title']) ? $_POST['p_title'] : $post_data['post_title'];
		$p_preview = isset($_POST['p_preview']) ? 1 : 0;
		$p_name = isset($_POST['p_name']) ? $_POST['p_name'] : '';
		$p_ppic_id = isset($_POST['p_ppic_id']) ? $_POST['p_ppic_id'] : $post_data['post_pic'];

		$error = '';

		$p_smilies = $post_data['post_enable_smilies'];
		$p_bbcode = $post_data['post_enable_bbcode'];
		$p_signature = $post_data['post_show_sig'];
		$p_htmlcode = $post_data['post_enable_html'];

		if(isset($_GET['doit'])) {
			echo "HALLO";
			$p_bbcode = (isset($_POST['p_bbcode']) ? 1 : 0);
			$p_smilies = (isset($_POST['p_smilies']) ? 1 : 0);
			$p_htmlcode = (isset($_POST['p_htmlcode']) ? 1 : 0);
			$p_signature = (isset($_POST['p_signature']) ? 1 : 0);

			if($p_preview != 1) {
				if(trim($p_title) == '') $error = $lng['error_no_title'];
				elseif(trim($p_post) == '') $error = $lng['error_no_post'];
				elseif(strlen($p_title) > 60) $error = $lng['error_title_too_long'];
				else {
					update_posts_data(array('post_id'=>$post_id,'topic_id'=>$topic_id),array(
						'post_pic'=>array('STR',$p_ppic_id),
						'post_enable_bbcode'=>array('STR',$p_bbcode),
						'post_enable_smilies'=>arraY('STR',$p_smilies),
						'post_enable_html'=>array('STR',$p_htmlcode),
						'post_show_sig'=>array('STR',$p_signature),
						'post_title'=>array('STR',$p_title),
						'post_text'=>array('STR',$p_post)
					));

					header("Location: index.php?faction=viewtopic&topic_id=$topic_id&z=$return_to&$MYSID#post$post_id"); exit;
				}
			}
		}


		$checked = array(
			'smilies'=>($p_smilies == 1) ? ' checked="checked"' : '',
			'htmlcode'=>($p_htmlcode == 1) ? ' checked="checked"' : '',
			'bbcode'=>($p_bbcode == 1) ? ' checked="checked"' : '',
			'signature'=>($p_signature == 1) ? ' checked="checked"' : ''
		);


		$editpost_tpl = new template;
		$editpost_tpl->load($template_path.'/'.$tpl_config['tpl_editpost_edit']);

		$editpost_tpl->unset_block('fcoderow');


		//
		// Smilies Box
		//
		$smilies_box = ($forum_data['forum_enable_smilies'] == 1) ? get_smilies_box() : '';


		//
		// BeitragsbildBox
		//
		$ppics_box = get_ppics_box($p_ppic_id);


		//
		// Vorschau
		//
		if($p_preview == 1) {
			$preview_post = $p_post;
			$editpost_tpl->blocks['preview']->parse_code();
		}
		else $editpost_tpl->unset_block('preview');


		//
		// Fehler
		//
		if($error != '') $editpost_tpl->blocks['errorrow']->parse_code();
		else $editpost_tpl->unset_block('errorrow');


/*		//
		// Namensfeld
		//
		if($user_logged_in != 1) {
			$editpost_tpl->blocks['namerow']->values = array(
				'LNG_NICK_CONVENTIONS'=>$lng['nick_conventions'],
				'LNG_YOUR_NAME'=>$lng['Your_name'],
				'P_NAME'=>$p_name
			);
			$editpost_tpl->blocks['namerow']->parse_code();
		}
		else $editpost_tpl->unset_block('namerow');*/
		$editpost_tpl->unset_block('namerow');


		//
		// Smilies
		//
		if($forum_data['forum_enable_smilies'] == 1) $editpost_tpl->blocks['smiliescheck']->parse_code();
		else $editpost_tpl->unset_block('smiliescheck');


		//
		// Signatur
		//
		if($CONFIG['enable_sig'] == 1 && $user_logged_in == 1) $editpost_tpl->blocks['sigcheck']->parse_code();
		else $editpost_tpl->unset_block('sigcheck');


		//
		// BBCode
		//
		if($forum_data['forum_enable_bbcode'] == 1) $editpost_tpl->blocks['bbcodecheck']->parse_code();
		else $editpost_tpl->unset_block('bbcodecheck');


		//
		// HTML-Code
		//
		if($forum_data['forum_enable_htmlcode'] == 1) $editpost_tpl->blocks['htmlcodecheck']->parse_code();
		else $editpost_tpl->unset_block('htmlcodecheck');

		$title_max_chars = sprintf($lng['Maximum_x_chars'],60);

		$title_add .= ' &#187; '.$forum_data['forum_name'].' &#187; '.$topic_data['topic_title'].' &#187; '.$lng['Edit_post'];

		include_once('pheader.php');

		show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID\">".$forum_data['forum_name']."</a>\r<a href=\"index.php?faction=viewtopic&amp;topic_id=$topic_id&amp;$MYSID\">".$topic_data['topic_title']."</a>\r".$lng['Edit_post']);

		$editpost_tpl->parse_code(TRUE);

		include_once('ptail.php');
	break;

	case 'delete':
		if($post_id == $topic_data['topic_first_post_id']) {
			include_once('pheader.php');
			show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID\">".$forum_data['forum_name']."</a>\r<a href=\"index.php?faction=viewtopic&amp;topic_id=$topic_id&amp;$MYSID\">".$topic_data['topic_title']."</a>\r".$lng['Cannot_delete_first_post']);
			show_message('Cannot_delete_first_post','message_cannot_delete_first_post');
			include_once('ptail.php'); exit;
		}

		delete_posts_data(array(
			'topic_id'=>$topic_id,
			'post_id'=>$post_id
		));

		if($post_id == $topic_data['topic_last_post_id']) update_topic_last_post($topic_id);
		if($post_id == $forum_data['forum_last_post_post_id']) update_forum_last_post($forum_id);

		header("Location: index.php?faction=viewtopic&topic_id=$topic_id&z=$return_to&$MYSID"); exit;

	break;
}

?>