<?php
/**
*
* Tritanium Bulletin Board 2 - postreply.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

isset($_GET['topic_id']) ? $topic_id = $_GET['topic_id'] : $topic_id = 0;

if(!$topic_data = get_topic_data($topic_id)) die('Kann Themendaten nicht laden!');
elseif(!$forum_data = get_forum_data($topic_data['forum_id'])) die('Kann Forumdaten nicht laden!');

$forum_id = $forum_data['forum_id'];


//
// Beginn Authentifizierung
//
if($user_logged_in != 1) {
	if($forum_data['auth_guests_post_reply'] != 1) {
		include_once('pheader.php');
		show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Not_logged_in']);
		show_message('Not_logged_in','message_forum_not_logged_in','<br />'.$lng['click_here_login'].'<br />'.$lng['click_here_register']);
		include_once('ptail.php'); exit;
	}
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
		if($forum_data['auth_members_post_reply'] != 1 && $auth_data['auth_post_reply'] != 1 || $forum_data['auth_members_post_reply'] == 1 && $auth_data['auth_post_reply'] == 0) {
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


$p_post = isset($_POST['p_post']) ? $_POST['p_post'] : '';
$p_title = isset($_POST['p_title']) ? $_POST['p_title'] : 'Re: '.$topic_data['topic_title'];
$p_preview = isset($_POST['p_preview']) ? 1 : 0;
$p_name = isset($_POST['p_name']) ? $_POST['p_name'] : '';
$p_ppic_id = isset($_POST['p_ppic_id']) ? $_POST['p_ppic_id'] : 0;

$error = '';

$p_smilies = $p_bbcode = $p_signature = 1;
$p_htmlcode = '';

if(isset($_GET['doit'])) {
	$p_bbcode = (isset($_POST['p_bbcode']) ? 1 : 0);
	$p_smilies = (isset($_POST['p_smilies']) ? 1 : 0);
	$p_htmlcode = (isset($_POST['p_htmlcode']) ? 1 : 0);
	$p_signature = (isset($_POST['p_signature']) ? 1 : 0);

	if($p_preview != 1) {
		if(trim($p_title) == '') $error = $lng['error_no_title'];
		elseif(trim($p_post) == '') $error = $lng['error_no_post'];
		elseif(strlen($p_title) > 60) $error = $lng['error_title_too_long'];
		else {
			$new_post_data = array(
				'topic_id'=>$topic_id,
				'forum_id'=>$forum_id,
				'poster_id'=>$user_id,
				'post_ip'=>$_SERVER['REMOTE_ADDR'],
				'post_pic'=>$p_ppic_id,
				'post_enable_bbcode'=>$p_bbcode,
				'post_enable_smilies'=>$p_smilies,
				'post_enable_html'=>$p_htmlcode,
				'post_show_sig'=>$p_signature,
				'post_title'=>$p_title,
				'post_text'=>$p_post
			);
			$new_post_data = add_post_data($new_post_data);

			update_topic_data($topic_id,array(
				'topic_last_post_id'=>array('STR',$new_post_data['post_id']),
				'topic_replies'=>array('INT',1)
			));

			update_forum_data($forum_id,array(
				'forum_last_post_topic_id'=>array('STR',$topic_id),
				'forum_last_post_post_id'=>array('STR',$new_post_data['post_id']),
				'forum_posts_counter'=>array('INT',1)
			));

			if($user_logged_in == 1) {
				update_user_data($user_id,array(
					'user_posts'=>array('INT',1)
				));
			}

			if($topic_data['topic_is_pinned'] != 1) rank_topic($forum_id,$topic_id);

			header("Location: index.php?faction=viewtopic&topic_id=$topic_id&z=last&$MYSID#post".$new_post_data['post_id']); exit;
		}
	}
}


$checked = array(
	'smilies'=>$p_smilies == 1 ? ' checked="checked"' : '',
	'htmlcode'=>$p_htmlcode == 1 ? ' checked="checked"' : '',
	'bbcode'=>$p_bbcode == 1 ? ' checked="checked"' : '',
	'signature'=>$p_signature == 1 ? ' checked="checked"' : ''
);


if(isset($_GET['quote'])) {
	if($quote_post_data = get_post_data($topic_id,$_GET['quote']))
		$p_post .= '[quote='.$quote_post_data['poster_nick'].']'.$quote_post_data['post_text'].'[/quote]'."\n";
}

$preply_tpl = new template;
$preply_tpl->load($template_path.'/'.$tpl_config['tpl_postreply']);

$preply_tpl->unset_block('fcoderow');
$preply_tpl->unset_block('reviewpostrow');


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
	$preply_tpl->blocks['preview']->values = array(
		'LNG_PREVIEW'=>$lng['Preview'],
		'PREVIEW_POST'=>$p_post
	);
	$preply_tpl->blocks['preview']->parse_code();
}
else $preply_tpl->unset_block('preview');


//
// Fehler
//
if($error != '') {
	$preply_tpl->blocks['errorrow']->values = array(
		'ERROR'=>$error
	);
	$preply_tpl->blocks['errorrow']->parse_code();
}
else $preply_tpl->unset_block('errorrow');


//
// Namensfeld
//
if($user_logged_in != 1) {
	$preply_tpl->blocks['namerow']->values = array(
		'LNG_NICK_CONVENTIONS'=>$lng['nick_conventions'],
		'LNG_YOUR_NAME'=>$lng['Your_name'],
		'P_NAME'=>$p_name
	);
	$preply_tpl->blocks['namerow']->parse_code();
}
else $preply_tpl->unset_block('namerow');


//
// Smilies
//
if($forum_data['forum_enable_smilies'] == 1) {
	$preply_tpl->blocks['smiliescheck']->values = array(
		'LNG_ENABLE_SMILIES'=>$lng['Enable_smilies'],
		'C_SMILIES'=>$checked['smilies']
	);
	$preply_tpl->blocks['smiliescheck']->parse_code();
}
else $preply_tpl->unset_block('smiliescheck');


//
// Signatur
//
if($CONFIG['enable_sig'] == 1 && $user_logged_in == 1) {
	$preply_tpl->blocks['sigcheck']->values = array(
		'C_SIGNATURE'=>$checked['signature'],
		'LNG_SHOW_SIGNATURE'=>$lng['Show_signature']
	);
	$preply_tpl->blocks['sigcheck']->parse_code();
}
else $preply_tpl->unset_block('sigcheck');


//
// BBCode
//
if($forum_data['forum_enable_bbcode'] == 1) {
	$preply_tpl->blocks['bbcodecheck']->values = array(
		'LNG_ENABLE_BBCODE'=>$lng['Enable_bbcode'],
		'C_BBCODE'=>$checked['bbcode']
	);
	$preply_tpl->blocks['bbcodecheck']->parse_code();
}
else $preply_tpl->unset_block('bbcodecheck');


//
// HTML-Code
//
if($forum_data['forum_enable_htmlcode'] == 1) {
	$preply_tpl->blocks['htmlcodecheck']->values = array(
		'LNG_ENABLE_HTMLCODE'=>$lng['Enable_html_code'],
		'C_HTMLCODE'=>$checked['htmlcode']
	);
	$preply_tpl->blocks['htmlcodecheck']->parse_code();
}
else $preply_tpl->unset_block('htmlcodecheck');


//
// Der Rest...
//

$preply_tpl->values = array(
	'P_TITLE'=>$p_title,
	'P_POST'=>$p_post,
	'TOPIC_ID'=>$topic_id,
	'MYSID'=>$MYSID,
	'C_SMILIES'=>$checked['smilies'],
	'LNG_POST_REPLY'=>$lng['Post_reply'],
	'LNG_PREVIEW'=>$lng['Preview'],
	'LNG_YOUR_NAME'=>$lng['Your_name'],
	'LNG_TITLE'=>$lng['Title'],
	'LNG_MAXIMUM_CHARS'=>sprintf($lng['Maximum_x_chars'],60),
	'LNG_POST'=>$lng['Post'],
	'LNG_OPTIONS'=>$lng['Options'],
	'LNG_POSTPIC'=>'',
	'LNG_TOPIC_REVIEW'=>$lng['Topic_review']
);

$title_add .= ' &#187; '.$forum_data['forum_name'].' &#187; '.$topic_data['topic_title'].' &#187; '.$lng['Post_reply'];

include_once('pheader.php');

show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID\">".$forum_data['forum_name']."</a>\r<a href=\"index.php?faction=viewtopic&amp;topic_id=$topic_id&amp;$MYSID\">".$topic_data['topic_title']."</a>\r".$lng['Post_reply']);

$preply_tpl->parse_code(TRUE);

include_once('ptail.php');

?>