<?php
/**
*
* Tritanium Bulletin Board 2 - posttopic.php
* Dient zum Erstellen eines Themas in einem Forum
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
*
**/

require_once('auth.php');

$forum_id = isset($_GET['forum_id']) ? $_GET['forum_id'] : 0;

if(!$forum_data = get_forum_data($forum_id)) die('Kann Forendaten nicht laden/Forum existiert nicht!');

if($user_logged_in != 1) {
	if($forum_data['auth_guests_post_topic'] != 1) {
		include_once('pheader.php');
		show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Not_logged_in']);
		show_message('Not_logged_in','message_not_logged_in','<br />'.$lng['click_here_login'].'<br />'.$lng['click_here_register']);
		include_once('ptail.php'); exit;
	}
}
elseif($user_data['user_is_admin'] != 1) {
	if($forum_data['auth_members_post_topic'] != 1) {
		include_once('pheader.php');
		show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['No_access']);
		show_message('No_access','message_no_access');
		include_once('ptail.php'); exit;
	}
}

$error = '';

$p_post = (isset($_POST['p_post']) ? $_POST['p_post'] : '');
$p_title = (isset($_POST['p_title']) ? $_POST['p_title'] : '');
$p_preview = (isset($_POST['p_preview']) ? 1 : 0);
$p_name = isset($_POST['p_name']) ? $_POST['p_name'] : '';

$p_smilies = $p_signature = $p_bbcode = 1;
$p_htmlcode = 0;

if(isset($_GET['doit']) && $p_preview == 0) {
	isset($_POST['p_signature']) ? $p_signature = 1 : $p_signature = 0;
	isset($_POST['p_smilies']) ? $p_smilies = 1 : $p_smilies = 0;
	isset($_POST['p_bbcode']) ? $p_bbcode = 1 : $p_bbcode = 0;
	isset($_POST['p_htmlcode']) ? $p_htmlcode = 1 : $p_htmlcode = 0;

	if($p_title == '') $error = $lng['error_no_title'];
	elseif(strlen($p_title) > 60) $error = $lng['error_title_too_long'];
	elseif($p_post == '') $error = $lng['error_no_post'];
	else {
		$new_topic_data = array(
			'topic_title'=>$p_title,
			'forum_id'=>$forum_id,
			'topic_status'=>0,
			'topic_is_pinned'=>0,
			'topic_poster_id'=>$user_id,
			'topic_pic'=>0,
			'topic_replies'=>0,
			'topic_views'=>0,
			'topic_poll_id'=>0,
			'topic_first_post_id'=>0,
			'topic_last_post_id'=>0,
			'topic_is_moved'=>0
		);
		$new_topic_data = add_topic_data($new_topic_data);

		$new_post_data = array(
			'topic_id'=>$new_topic_data['topic_id'],
			'forum_id'=>$forum_id,
			'poster_id'=>$user_id,
			'post_ip'=>$_SERVER['REMOTE_ADDR'],
			'post_pic'=>1,
			'post_enable_bbcode'=>$p_bbcode,
			'post_enable_smilies'=>$p_smilies,
			'post_enable_html'=>$p_htmlcode,
			'post_show_sig'=>$p_signature,
			'post_title'=>$p_title,
			'post_text'=>$p_post
		);
		$new_post_data = add_post_data($new_post_data);

		update_topic_data($new_topic_data['topic_id'],array(
			'topic_first_post_id'=>array('STR',$new_post_data['post_id']),
			'topic_last_post_id'=>array('STR',$new_post_data['post_id'])
		));

		update_forum_data($forum_id,array(
			'forum_last_post_topic_id'=>array('STR',$new_topic_data['topic_id']),
			'forum_last_post_post_id'=>array('STR',$new_post_data['post_id']),
			'forum_posts_counter'=>array('INT',1),
			'forum_topics_counter'=>array('INT',1)
		));

		update_user_data($user_id,array(
			'user_posts'=>array('INT',1)
		));

		header("Location: index.php?faction=viewtopic&topic_id=".$new_topic_data['topic_id']."&$MYSID"); exit;
	}
}

$checked = array(
	'smilies'=>$p_smilies == 1 ? ' checked="checked"' : '',
	'signature'=>$p_signature == 1 ? ' checked="checked"' : '',
	'bbcode'=>$p_bbcode == 1 ? ' checked="checked"' : '',
	'htmlcode'=>$p_htmlcode == 1 ? ' checked="checked"' : ''
);

$ptopic_tpl = new template;
$ptopic_tpl->load($template_path.'/'.$tpl_config['tpl_posttopic']);


if($error != '') {
	$ptopic_tpl->blocks['errorrow']->values = array(
		'ERROR'=>$error
	);
	$ptopic_tpl->blocks['errorrow']->parse_code();
}
else $ptopic_tpl->unset_block('errorrow');


$ptopic_tpl->unset_block('fcoderow');
$ptopic_tpl->unset_block('notifycheck');


//
// Namensfeld für Gäste
//
if($user_logged_in != 1) {
	$ptopic_tpl->blocks['namerow']->values = array(
		'LNG_YOUR_NAME'=>$lng['Your_name'],
		'P_NAME'=>$p_name,
		'LNG_NICK_CONVENTIONS'=>$lng['nick_conventions']
	);
	$ptopic_tpl->blocks['namerow']->parse_code();
}
else $ptopic_tpl->unset_block('namerow');



//
// Vorschau
//
if($p_preview == 1) {
	$ptopic_tpl->blocks['preview']->values = array(
		'LNG_PREVIEW'=>$lng['Preview'],
		'PREVIEW_POST'=>nlbr($p_post)
	);
	$ptopic_tpl->blocks['preview']->parse_code();
}
else $ptopic_tpl->unset_block('preview');


//
// Smilies
//
if($forum_data['forum_enable_smilies'] == 1) {
	$ptopic_tpl->blocks['smiliescheck']->values = array(
		'LNG_ENABLE_SMILIES'=>$lng['Enable_smilies'],
		'C_SMILIES'=>$checked['smilies']
	);
	$ptopic_tpl->blocks['smiliescheck']->parse_code();
}
else $ptopic_tpl->unset_block('smiliescheck');


//
// Signatur
//
if($CONFIG['enable_sig'] == 1 && $user_logged_in == 1) {
	$ptopic_tpl->blocks['sigcheck']->values = array(
		'C_SIGNATURE'=>$checked['signature'],
		'LNG_SHOW_SIGNATURE'=>$lng['Show_signature']
	);
	$ptopic_tpl->blocks['sigcheck']->parse_code();
}
else $ptopic_tpl->unset_block('sigcheck');


//
// BBCode
//
if($forum_data['forum_enable_bbcode'] == 1) {
	$ptopic_tpl->blocks['bbcodecheck']->values = array(
		'LNG_ENABLE_BBCODE'=>$lng['Enable_bbcode'],
		'C_BBCODE'=>$checked['bbcode']
	);
	$ptopic_tpl->blocks['bbcodecheck']->parse_code();
}
else $ptopic_tpl->unset_block('bbcodecheck');


//
// HTML-Code
//
if($forum_data['forum_enable_htmlcode'] == 1) {
	$ptopic_tpl->blocks['htmlcodecheck']->values = array(
		'LNG_ENABLE_HTMLCODE'=>$lng['Enable_html_code'],
		'C_HTMLCODE'=>$checked['htmlcode']
	);
	$ptopic_tpl->blocks['htmlcodecheck']->parse_code();
}
else $ptopic_tpl->unset_block('htmlcodecheck');


//
// Der Rest...
//
$title_add .= ' &#187; '.$forum_data['forum_name'].' &#187; '.$lng['Post_topic'];

include_once('pheader.php');

show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID\">".$forum_data['forum_name']."</a>\r".$lng['Post_topic']);

$ptopic_tpl->values = array(
	'FORUM_ID'=>$forum_id,
	'MYSID'=>$MYSID,
	'LNG_POST_TOPIC'=>$lng['Post_topic'],
	'LNG_POST_NEW_TOPIC'=>$lng['Post_new_topic'],
	'LNG_OPTIONS'=>$lng['Options'],
	'LNG_POST'=>$lng['Post'],
	'LNG_PREVIEW'=>$lng['Preview'],
	'P_POST'=>mutate($p_post),
	'P_TITLE'=>mutate($p_title),
	'PSMILIESBOX'=>'',
	'TSMILIESBOX'=>'',
	'LNG_TITLE'=>$lng['Title'],
	'LNG_POSTPIC'=>'',
	'LNG_MAXIMUM_CHARS'=>sprintf($lng['Maximum_x_chars'],60)
);

$ptopic_tpl->parse_code(TRUE);

include_once('ptail.php');

?>