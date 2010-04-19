<?php
/**
*
* Tritanium Bulletin Board 2 - postreply.php
* version #2004-01-01-18-38-43
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
	if(!$auth_data = get_auth_forum_user($forum_id,$user_id,array('auth_post_reply','auth_is_mod'))) {
		$auth_data = array(
			'auth_post_reply'=>$forum_data['auth_members_post_reply'],
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
			$db->query("INSERT INTO ".TBLPFX."posts (topic_id,forum_id,poster_id,post_time,post_ip,post_pic,post_enable_bbcode,post_enable_smilies,post_enable_html,post_show_sig) VALUES ('$topic_id','$forum_id','$user_id',NOW(),'".$_SERVER['REMOTE_ADDR']."','$p_ppic_id','$p_bbcode','$p_smilies','$p_htmlcode','$p_signature')");
			$new_post_id = $db->insert_id;

			$db->query("INSERT INTO ".TBLPFX."posts_text (post_id,post_title,post_text) VALUES ('$new_post_id','$p_title','$p_post')");

			$db->query("UPDATE ".TBLPFX."topics SET topic_last_post_id='$new_post_id', topic_replies_counter=topic_replies_counter+1 WHERE topic_id='$topic_id'");
			$db->query("UPDATE ".TBLPFX."forums SET forum_last_post_id='$new_post_id', forum_posts_counter=forum_posts_counter+1 WHERE forum_id='$forum_id'");
			$db->query("UPDATE ".TBLPFX."users SET user_posts=user_posts+1 WHERE user_id='$user_id'");

			header("Location: index.php?faction=viewtopic&topic_id=$topic_id&z=last&$MYSID#post$new_post_id"); exit;
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
	$db->query("SELECT t2.post_text, t3.user_nick AS poster_nick FROM ".TBLPFX."posts AS t1, ".TBLPFX."posts_text AS t2 LEFT JOIN ".TBLPFX."users AS t3 ON t1.poster_id=t3.user_id WHERE t1.post_id='".$_GET['quote']."' AND t2.post_id=t1.post_id");
	if($quote_post_data = $db->fetch_array())
		$p_post .= '[quote='.$quote_post_data['poster_nick'].']'.$quote_post_data['post_text'].'[/quote]'."\n";
}

$preply_tpl = new template;
$preply_tpl->load($template_path.'/'.$tpl_config['tpl_postreply']);



$smilies_box = ($forum_data['forum_enable_smilies'] == 1) ? get_smilies_box() : ''; // Smiliesbox
$ppics_box = get_ppics_box($p_ppic_id); // Beitragsbilderbox


//
// Die Bloecke...
//
if($forum_data['forum_enable_bbcode'] == 1) { // ...BBCodebox...
	$bbcode_box = get_bbcode_box();
	$preply_tpl->blocks['bbcoderow']->parse_code();
}
else $preply_tpl->unsert_block('bbcoderow');

if($p_preview == 1) { // ...Vorschau...
	$preview_post = nlbr($p_post);
	$preply_tpl->blocks['preview']->parse_code();
}
else $preply_tpl->unset_block('preview');

if($error != '') $preply_tpl->blocks['errorrow']->parse_code(); // ...Fehler...
else $preply_tpl->unset_block('errorrow');

if($user_logged_in != 1) $preply_tpl->blocks['namerow']->parse_code(); // ...Namensfeld...
else $preply_tpl->unset_block('namerow');

if($forum_data['forum_enable_smilies'] == 1) $preply_tpl->blocks['smiliescheck']->parse_code(); // ...Smilies...
else $preply_tpl->unset_block('smiliescheck');

if($CONFIG['enable_sig'] == 1 && $user_logged_in == 1) $preply_tpl->blocks['sigcheck']->parse_code(); // ...Signatur...
else $preply_tpl->unset_block('sigcheck');

if($forum_data['forum_enable_bbcode'] == 1)	$preply_tpl->blocks['bbcodecheck']->parse_code(); // ...BBCode...
else $preply_tpl->unset_block('bbcodecheck');

if($forum_data['forum_enable_htmlcode'] == 1) $preply_tpl->blocks['htmlcodecheck']->parse_code(); // ...HTML-Code
else $preply_tpl->unset_block('htmlcodecheck');


//
// Der Topicreview
//
$db->query("SELECT t2.post_title, t2.post_text, t3.user_nick AS poster_nick FROM ".TBLPFX."posts AS t1, ".TBLPFX."posts_text AS t2 LEFT JOIN ".TBLPFX."users AS t3 ON t1.poster_id=t3.user_id WHERE t1.topic_id='$topic_id' AND t2.post_id=t1.post_id ORDER BY post_time DESC LIMIT 5");
while($akt_post = $db->fetch_array()) {

	$preply_tpl->blocks['reviewpostrow']->parse_code(FALSE,TRUE);
	$tpl_config['akt_class'] = ($tpl_config['akt_class'] == $tpl_config['td1_class']) ? $tpl_config['td2_class'] : $tpl_config['td1_class'];
}


//
// Der Rest...
//
$max_nick_chars = sprintf($lng['Maximum_x_chars'],60);

$title_add[] = $forum_data['forum_name'];
$title_add[] = $topic_data['topic_title'];
$title_add[] = $lng['Post_reply'];

include_once('pheader.php');

show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID\">".$forum_data['forum_name']."</a>\r<a href=\"index.php?faction=viewtopic&amp;topic_id=$topic_id&amp;$MYSID\">".$topic_data['topic_title']."</a>\r".$lng['Post_reply']);

$preply_tpl->parse_code(TRUE);

include_once('ptail.php');

?>