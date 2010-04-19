<?php
/**
*
* Tritanium Bulletin Board 2 - postreply.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');
require_once('bbcode.php');

isset($_GET['topic_id']) ? $topic_id = $_GET['topic_id'] : $topic_id = 0;

if(!$topic_data = get_topic_data($topic_id)) die('Kann Themendaten nicht laden!');
elseif(!$forum_data = get_forum_data($topic_data['forum_id'])) die('Kann Forumdaten nicht laden!');

$forum_id = $forum_data['forum_id'];


//
// Beginn Authentifizierung
//
if($USER_LOGGED_IN != 1) {
	if($forum_data['auth_guests_post_reply'] != 1) {
		include_once('pheader.php');
		show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Not_logged_in']);
		show_message('Not_logged_in','message_forum_not_logged_in','<br />'.$lng['click_here_login'].'<br />'.$lng['click_here_register']);
		include_once('ptail.php'); exit;
	}

	$auth_data = array(
		'auth_post_reply'=>$forum_data['auth_guests_post_reply'],
		'auth_is_mod'=>0
	);
}
elseif($USER_DATA['user_is_admin'] != 1) {
	if(!$auth_data = get_auth_forum_user($forum_id,$USER_ID,array('auth_post_reply','auth_is_mod'))) {
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


if($topic_data['topic_status'] == 1 && $USER_DATA['user_is_admin'] != 1 && $auth_data['auth_is_mod'] != 1) { // Falls Thema geschlossen ist
	include_once('pheader.php');
	show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID\">".$forum_data['forum_name']."</a>\r<a href=\"index.php?faction=viewtopic&amp;topic_id=$topic_id&amp;$MYSID\">".$topic_data['topic_title']."</a>\r".$lng['Closed_topic']);
	show_message('Closed_topic','message_closed_topic','<br />'.sprintf($lng['click_here_back_topic'],"<a href=\"index.php?faction=viewtopic&amp;topic_id=$topic_id&amp;$MYSID\">",'</a>'));
	include_once('ptail.php'); exit;
}


$p_post = isset($_POST['p_post']) ? $_POST['p_post'] : '';
$p_title = isset($_POST['p_title']) ? $_POST['p_title'] : 'Re: '.$topic_data['topic_title'];
$p_preview = isset($_POST['p_preview']) ? 1 : 0;
$p_name = isset($_POST['p_name']) ? $_POST['p_name'] : '';
$p_ppic_id = isset($_POST['p_ppic_id']) ? $_POST['p_ppic_id'] : 0;

$error = '';

$p_smilies = $p_bbcode = $p_signature = 1;
$p_htmlcode = $p_subscribe = 0;

$p_important = $topic_data['topic_is_pinned'];
$p_close = $topic_data['topic_status'];

if(isset($_GET['doit'])) {
	$p_bbcode = isset($_POST['p_bbcode']) ? 1 : 0;
	$p_smilies = isset($_POST['p_smilies']) ? 1 : 0;
	$p_htmlcode = isset($_POST['p_htmlcode']) ? 1 : 0;
	$p_signature = isset($_POST['p_signature']) ? 1 : 0;
	$p_subscribe = isset($_POST['p_subscribe']) ? 1 : 0;
	$p_important = isset($_POST['p_important']) ? 1 : 0;
	$p_close = isset($_POST['p_close']) ? 1 : 0;

	if($p_preview != 1) {
		if(trim($p_title) == '') $error = $lng['error_no_title'];
		elseif(trim($p_post) == '') $error = $lng['error_no_post'];
		elseif(strlen($p_title) > 60) $error = $lng['error_title_too_long'];
		else {
			$db->query("INSERT INTO ".TBLPFX."posts (topic_id,forum_id,poster_id,post_time,post_ip,post_pic,post_enable_bbcode,post_enable_smilies,post_enable_html,post_show_sig) VALUES ('$topic_id','$forum_id','$USER_ID',NOW(),'".$_SERVER['REMOTE_ADDR']."','$p_ppic_id','$p_bbcode','$p_smilies','$p_htmlcode','$p_signature')");
			$new_post_id = $db->insert_id;

			$db->query("INSERT INTO ".TBLPFX."posts_text (post_id,post_title,post_text) VALUES ('$new_post_id','$p_title','$p_post')");

			$db->query("UPDATE ".TBLPFX."topics SET topic_last_post_id='$new_post_id', topic_replies_counter=topic_replies_counter+1 WHERE topic_id='$topic_id'");
			$db->query("UPDATE ".TBLPFX."forums SET forum_last_post_id='$new_post_id', forum_posts_counter=forum_posts_counter+1 WHERE forum_id='$forum_id'");
			$db->query("UPDATE ".TBLPFX."users SET user_posts=user_posts+1 WHERE user_id='$USER_ID'");

			if($USER_LOGGED_IN == 1 && ($USER_DATA['user_is_admin'] == 1 || $auth_data['auth_is_mod'] == 1))
				$db->query("UPDATE ".TBLPFX."topics SET topic_status='$p_close', topic_is_pinned='$p_important' WHERE topic_id='$topic_id'");

			if($USER_LOGGED_IN == 1 && $CONFIG['enable_email_functions'] == 1 && $CONFIG['enable_topic_subscription'] == 1 && $p_subscribe == 1) {
				$db->query("SELECT user_id FROM ".TBLPFX."topics_subscriptions WHERE topic_id='$topic_id' AND user_id='$USER_ID'");
				if($db->affected_rows == 0)
					$db->query("INSERT INTO ".TBLPFX."topics_subscriptions (topic_id,user_id) VALUES ('$topic_id','$USER_ID')");
			}

			if($CONFIG['enable_email_functions'] == 1 && $CONFIG['enable_topic_subscription'] == 1) {
				$email_addresses = array();
				$db->query("SELECT t2.user_email FROM ".TBLPFX."topics_subscriptions AS t1, ".TBLPFX."users AS t2 WHERE t1.topic_id='$topic_id' AND t1.user_id<>'$USER_ID' AND t2.user_id=t1.user_id");
				while(list($akt_email_address) = $db->fetch_array())
					$email_addresses[] = $akt_email_address;

				if(count($email_addresses) > 0) {
					$email_addresses = implode(', ',$email_addresses);
					$post_link = $CONFIG['board_address'].'/index.php?faction=viewtopic&post_id='.$new_post_id.'&'.$MYSID.'#post'.$new_post_id;

					$add_headers = 'Bcc: '.$email_addresses."\r\n";

					$email_tpl = new template;
					$email_tpl->load($language_path.'/emails/email_new_reply_posted.tpl');
					mymail('"'.$CONFIG['board_name'].'" <'.$CONFIG['board_email_address'].'>','',$lng['email_subject_new_reply_posted'],$email_tpl->parse_code(),$add_headers);
				}
			}

			header("Location: index.php?faction=viewtopic&topic_id=$topic_id&z=last&$MYSID#post$new_post_id"); exit;
		}
	}
}


$checked = array(
	'smilies'=>($p_smilies == 1) ? ' checked="checked"' : '',
	'htmlcode'=>($p_htmlcode == 1) ? ' checked="checked"' : '',
	'bbcode'=>($p_bbcode == 1) ? ' checked="checked"' : '',
	'signature'=>($p_signature == 1) ? ' checked="checked"' : '',
	'subscribe'=>($p_subscribe == 1) ? ' checked="checked"' : '',
	'important'=>($p_important == 1) ? ' checked="checked"' : '',
	'close'=>($p_close == 1) ? ' checked="checked"' : ''
);


//
// Smilies (werden später an verschiedenen Stellen benötigt)
//
$smilies = array(); // Beinhaltet spaeter die Smilies mit dem Synonym als Key und dem Bild (HTML) als Wert
$db->query("SELECT smiley_gfx,smiley_synonym FROM ".TBLPFX."smilies WHERE smiley_type='0'"); // Daten aller Smilies laden
while($akt_smiley = $db->fetch_array())
	$smilies[$akt_smiley['smiley_synonym']] = '<img src="'.$akt_smiley['smiley_gfx'].'" border="0" alt="'.$akt_smiley['smiley_synonym'].'" />'; // Smiley in Array einfuegen


//
// Falls ein Beitrag zitiert werden soll...
//
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
if($p_preview == 1) { // ...Vorschau...
	$preview_post = $p_post;
	if($p_htmlcode != 1 || $forum_data['forum_enable_htmlcode'] != 1) $preview_post = myhtmlentities($preview_post);
	if($p_smilies == 1 && $forum_data['forum_enable_smilies'] == 1) $preview_post = strtr($preview_post,$smilies);
	$preview_post = nlbr($preview_post);
	if($p_bbcode == 1 && $forum_data['forum_enable_bbcode'] == 1) $preview_post = bbcode($preview_post);

	$preply_tpl->blocks['preview']->parse_code();
}
else $preply_tpl->unset_block('preview');

multimutate('p_title','p_post','p_name');

if($error != '') $preply_tpl->blocks['errorrow']->parse_code(); // ...Fehler...
else $preply_tpl->unset_block('errorrow');

if($USER_LOGGED_IN != 1) $preply_tpl->blocks['namerow']->parse_code(); // ...Namensfeld...
else $preply_tpl->unset_block('namerow');

if($forum_data['forum_enable_smilies'] == 1) $preply_tpl->blocks['smiliescheck']->parse_code(); // ...Smilies...
else $preply_tpl->unset_block('smiliescheck');

if($CONFIG['enable_sig'] == 1 && $USER_LOGGED_IN == 1) $preply_tpl->blocks['sigcheck']->parse_code(); // ...Signatur...
else $preply_tpl->unset_block('sigcheck');

if($forum_data['forum_enable_bbcode'] == 1)	{ // ...BBCode...
	$bbcode_box = get_bbcode_box();

	$preply_tpl->blocks['bbcoderow']->parse_code();
	$preply_tpl->blocks['bbcodecheck']->parse_code();
}
else {
	$preply_tpl->unset_block('bbcodecheck');
	$preply_tpl->unsert_block('bbcoderow');
}

if($forum_data['forum_enable_htmlcode'] == 1) $preply_tpl->blocks['htmlcodecheck']->parse_code(); // ...HTML-Code...
else $preply_tpl->unset_block('htmlcodecheck');

if($USER_LOGGED_IN == 1 && $CONFIG['enable_email_functions'] == 1 && $CONFIG['enable_topic_subscription'] == 1) { // ...Thema-Abonnement
	$db->query("SELECT user_id FROM ".TBLPFX."topics_subscriptions WHERE topic_id='$topic_id' AND user_id='$USER_ID'");
	if($db->affected_rows == 0)
		$preply_tpl->blocks['subscribecheck']->parse_code();
	else $preply_tpl->unset_block('subscribecheck');
}
else $preply_tpl->unset_block('subscribecheck');

if($USER_LOGGED_IN == 1 && ($USER_DATA['user_is_admin'] == 1 || $auth_data['auth_is_mod'] == 1)) {
	$preply_tpl->blocks['importantcheck']->parse_code();
	$preply_tpl->blocks['closecheck']->parse_code();
}
else {
	$preply_tpl->unset_block('importantcheck');
	$preply_tpl->unset_block('closecheck');
}


//
// Der Themenrueckblick
//
$db->query("SELECT t1.post_enable_bbcode, t1.post_enable_smilies, t1.post_enable_html, t2.post_text, t3.user_nick AS poster_nick FROM ".TBLPFX."posts AS t1, ".TBLPFX."posts_text AS t2 LEFT JOIN ".TBLPFX."users AS t3 ON t1.poster_id=t3.user_id WHERE t1.topic_id='$topic_id' AND t2.post_id=t1.post_id ORDER BY post_time DESC LIMIT 5");
while($akt_post = $db->fetch_array()) {
	if($akt_post['post_enable_html'] != 1 || $forum_data['forum_enable_htmlcode'] != 1) $akt_post['post_text'] = myhtmlentities($akt_post['post_text']);
	if($akt_post['post_enable_smilies'] == 1 && $forum_data['forum_enable_smilies'] == 1) $akt_post['post_text'] = strtr($akt_post['post_text'],$smilies);
	$akt_post['post_text'] = nlbr($akt_post['post_text']);
	if($akt_post['post_enable_bbcode'] == 1 && $forum_data['forum_enable_bbcode'] == 1) $akt_post['post_text'] = bbcode($akt_post['post_text']);

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