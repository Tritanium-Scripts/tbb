<?php
/**
*
* Tritanium Bulletin Board 2 - editpost.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : 0;
$return_to = isset($_GET['return_to']) ? $_GET['return_to'] : 1;

if(!$post_data = get_post_data($post_id)) die('Kann Beitragsdaten nicht laden!');
elseif(!$topic_data = get_topic_data($post_data['topic_id'])) die('Kann Themendaten nicht laden!');
elseif(!$forum_data = get_forum_data($topic_data['forum_id'])) die('Kann Forumdaten nicht laden!');

$forum_id = $forum_data['forum_id'];
$topic_id = $post_data['topic_id'];

//
// Beginn Authentifizierung
//
if($USER_LOGGED_IN != 1) {
	add_navbar_items(array($LNG['Not_logged_in'],''));


	include_once('pheader.php');
	show_navbar();
	show_message($LNG['Not_logged_in'],$LNG['message_forum_not_logged_in'].'<br />'.$LNG['click_here_login'].'<br />'.$LNG['click_here_register']);
	include_once('ptail.php'); exit;
}
elseif($USER_DATA['user_is_admin'] != 1) {
	if(!$auth_data = get_auth_forum_user($forum_id,$USER_ID,array('auth_edit_posts','auth_is_mod'))) {
		$auth_data = array(
			'auth_edit_posts'=>$forum_data['auth_members_view_forum'],
			'auth_is_mod'=>0
		);
	}
	if($auth_data['auth_is_mod'] != 1) {
		if($USER_ID != $post_data['poster_id'] || $forum_data['auth_members_edit_posts'] != 1 && $auth_data['auth_edit_posts'] != 1 || $forum_data['auth_members_edit_posts'] == 1 && $auth_data['auth_edit_posts'] == 0) {
			add_navbar_items(array($LNG['No_access'],''));

			include_once('pheader.php');
			show_navbar();
			show_message($LNG['No_access'],$LNG['message_forum_no_access']);
			include_once('ptail.php'); exit;
		}
	}
}
//
// Ende Authentifizierung
//

add_navbar_items(array(myhtmlentities($forum_data['forum_name']),"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID"),array(myhtmlentities($topic_data['topic_title']),"index.php?faction=viewtopic&amp;topic_id=$topic_id&amp;$MYSID"));

switch(@$_GET['mode']) {
	case 'edit':
		$p_post = isset($_POST['p_message_text']) ? $_POST['p_message_text'] : addslashes($post_data['post_text']);
		$p_title = isset($_POST['p_title']) ? $_POST['p_title'] : addslashes($post_data['post_title']);
		$p_preview = isset($_POST['p_preview']) ? 1 : 0;
		$p_name = isset($_POST['p_name']) ? $_POST['p_name'] : '';
		$p_ppic_id = isset($_POST['p_ppic_id']) ? $_POST['p_ppic_id'] : addslashes($post_data['post_pic']);

		$error = '';

		$p_smilies = $post_data['post_enable_smilies'];
		$p_bbcode = $post_data['post_enable_bbcode'];
		$p_signature = $post_data['post_show_sig'];
		$p_htmlcode = $post_data['post_enable_html'];

		if(isset($_GET['doit'])) {
			$p_bbcode = (isset($_POST['p_bbcode']) ? 1 : 0);
			$p_smilies = (isset($_POST['p_smilies']) ? 1 : 0);
			$p_htmlcode = (isset($_POST['p_htmlcode']) ? 1 : 0);
			$p_signature = (isset($_POST['p_signature']) ? 1 : 0);

			if($p_preview != 1) {
				if(trim($p_title) == '') $error = $LNG['error_no_title'];
				elseif(trim($p_post) == '') $error = $LNG['error_no_post'];
				elseif(strlen($p_title) > 60) $error = $LNG['error_title_too_long'];
				else {
					$DB->query("UPDATE ".TBLPFX."posts SET post_pic='$p_ppic_id', post_enable_bbcode='$p_bbcode', post_enable_smilies='$p_smilies', post_enable_html='$p_htmlcode', post_show_sig='$p_signature', post_edited_counter=post_edited_counter+1, post_last_editor_id='$USER_ID', post_title='$p_title', post_text='$p_post' WHERE post_id='$post_id'");

					header("Location: index.php?faction=viewtopic&post_id=$post_id&$MYSID#post$post_id"); exit;
				}
			}
		}


		$checked = array(
			'smilies'=>($p_smilies == 1) ? ' checked="checked"' : '',
			'htmlcode'=>($p_htmlcode == 1) ? ' checked="checked"' : '',
			'bbcode'=>($p_bbcode == 1) ? ' checked="checked"' : '',
			'signature'=>($p_signature == 1) ? ' checked="checked"' : ''
		);

		multimutate('p_post','p_name','p_title');


		$editpost_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editpost_edit']);


		$smilies_box = ($forum_data['forum_enable_smilies'] == 1) ? get_smilies_box() : ''; // Smiliesbox
		$ppics_box = get_ppics_box($p_ppic_id); // Beitragsbildbox


		//
		// Die Bloecke...
		//
		if($forum_data['forum_enable_bbcode'] == 1) { // ...BBCodebox...
			$bbcode_box = get_bbcode_box();
			$editpost_tpl->blocks['bbcoderow']->parse_code();
		}

		if($p_preview == 1) { // ...Vorschau...
			$preview_post = nlbr($p_post);
			$editpost_tpl->blocks['preview']->parse_code();
		}

		if($error != '') $editpost_tpl->blocks['errorrow']->parse_code(); // ...Fehler...
		if($USER_LOGGED_IN != 1) $editpost_tpl->blocks['namerow']->parse_code(); // ...Namensfeld...
		if($forum_data['forum_enable_smilies'] == 1) $editpost_tpl->blocks['smiliescheck']->parse_code(); // ...Smilies...
		if($CONFIG['enable_sig'] == 1 && $USER_LOGGED_IN == 1) $editpost_tpl->blocks['sigcheck']->parse_code(); // ...Signatur...
		if($forum_data['forum_enable_bbcode'] == 1)	$editpost_tpl->blocks['bbcodecheck']->parse_code(); // ...BBCode...
		if($forum_data['forum_enable_htmlcode'] == 1) $editpost_tpl->blocks['htmlcodecheck']->parse_code(); // ...HTML-Code

		$title_max_chars = sprintf($LNG['Maximum_x_chars'],60);

		add_navbar_items(array($LNG['Edit_post'],''));

		include_once('pheader.php');
		show_navbar();
		$editpost_tpl->parse_code(TRUE);
		include_once('ptail.php');
	break;

	case 'delete':
		if($post_id == $topic_data['topic_first_post_id']) {
			add_navbar_items(array($LNG['Cannot_delete_first_post'],''));

			include_once('pheader.php');
			show_navbar();
			show_message($LNG['Cannot_delete_first_post'],$LNG['message_cannot_delete_first_post']);
			include_once('ptail.php'); exit;
		}

		$DB->query("DELETE FROM ".TBLPFX."posts WHERE post_id='$post_id'");
		$DB->query("UPDATE ".TBLPFX."users SET user_posts=user_posts-1 WHERE user_id='".$post_data['poster_id']."'");

		if($post_id == $topic_data['topic_last_post_id']) update_topic_last_post($topic_id);
		if($post_id == $forum_data['forum_last_post_id']) update_forum_last_post($forum_id);

		header("Location: index.php?faction=viewtopic&topic_id=$topic_id&z=$return_to&$MYSID"); exit;
	break;
}

?>