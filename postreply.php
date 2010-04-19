<?php
/**
*
* Tritanium Bulletin Board 2 - postreply.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
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
		add_navbar_items(array($LNG['Not_logged_in'],''));

		include_once('pheader.php');
		show_navbar();
		show_message($LNG['Not_logged_in'],$LNG['message_not_logged_in'].'<br />'.$LNG['click_here_login'].'<br />'.$LNG['click_here_register']);
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

add_navbar_items(array($forum_data['forum_name'],"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID"),array($topic_data['topic_title'],"index.php?faction=viewtopic&amp;topic_id=$topic_id&amp;$MYSID"));

if($topic_data['topic_status'] == 1 && $USER_DATA['user_is_admin'] != 1 && $auth_data['auth_is_mod'] != 1) { // Falls Thema geschlossen ist
	add_navbar_items(array($LNG['Closed_topic'],''));

	include_once('pheader.php');
	show_navbar();
	show_message($LNG['Closed_topic'],$LNG['message_closed_topic'].'<br />'.sprintf($LNG['click_here_back_topic'],"<a href=\"index.php?faction=viewtopic&amp;topic_id=$topic_id&amp;$MYSID\">",'</a>'));
	include_once('ptail.php'); exit;
}


$p_post_text = isset($_POST['p_message_text']) ? $_POST['p_message_text'] : '';
$p_post_title = isset($_POST['p_post_title']) ? $_POST['p_post_title'] : 'Re: '.$topic_data['topic_title'];
$p_preview = isset($_POST['p_preview']) ? 1 : 0;
$p_guest_nick = isset($_POST['p_guest_nick']) ? $_POST['p_guest_nick'] : '';
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
		if(trim($p_post_title) == '') $error = $LNG['error_no_title'];
		elseif(trim($p_post_text) == '') $error = $LNG['error_no_post'];
		elseif(strlen($p_post_title) > 60) $error = $LNG['error_title_too_long'];
		elseif($USER_LOGGED_IN != 1 && verify_nick($p_guest_nick) == FALSE) $error = $LNG['error_invalid_name'];
		elseif($USER_LOGGED_IN != 1 && unify_nick($p_guest_nick) == FALSE) $error = $LNG['error_existing_user_name'];
		else {
			if($USER_LOGGED_IN == 1)
				$p_guest_nick = '';

			$DB->query("INSERT INTO ".TBLPFX."posts (topic_id,forum_id,poster_id,post_time,post_ip,post_pic,post_enable_bbcode,post_enable_smilies,post_enable_html,post_show_sig,post_title,post_text,post_guest_nick) VALUES ('$topic_id','$forum_id','$USER_ID','".time()."','".$_SERVER['REMOTE_ADDR']."','$p_ppic_id','$p_bbcode','$p_smilies','$p_htmlcode','$p_signature','$p_post_title','$p_post_text','$p_guest_nick')");
			$new_post_id = $DB->insert_id;

			$DB->query("UPDATE ".TBLPFX."topics SET topic_last_post_id='$new_post_id', topic_replies_counter=topic_replies_counter+1 WHERE topic_id='$topic_id'");
			$DB->query("UPDATE ".TBLPFX."forums SET forum_last_post_id='$new_post_id', forum_posts_counter=forum_posts_counter+1 WHERE forum_id='$forum_id'");
			$DB->query("UPDATE ".TBLPFX."users SET user_posts=user_posts+1 WHERE user_id='$USER_ID'");

			if($USER_LOGGED_IN == 1 && ($USER_DATA['user_is_admin'] == 1 || $auth_data['auth_is_mod'] == 1))
				$DB->query("UPDATE ".TBLPFX."topics SET topic_status='$p_close', topic_is_pinned='$p_important' WHERE topic_id='$topic_id'");

			if($USER_LOGGED_IN == 1 && $CONFIG['enable_email_functions'] == 1 && $CONFIG['enable_topic_subscription'] == 1 && $p_subscribe == 1) {
				$DB->query("SELECT user_id FROM ".TBLPFX."topics_subscriptions WHERE topic_id='$topic_id' AND user_id='$USER_ID'");
				if($DB->affected_rows == 0)
					$DB->query("INSERT INTO ".TBLPFX."topics_subscriptions (topic_id,user_id) VALUES ('$topic_id','$USER_ID')");
			}

			if($CONFIG['enable_email_functions'] == 1 && $CONFIG['enable_topic_subscription'] == 1) {
				$email_addresses = array();
				$DB->query("SELECT t2.user_email FROM ".TBLPFX."topics_subscriptions AS t1, ".TBLPFX."users AS t2 WHERE t1.topic_id='$topic_id' AND t1.user_id<>'$USER_ID' AND t2.user_id=t1.user_id");
				while(list($akt_email_address) = $DB->fetch_array())
					$email_addresses[] = $akt_email_address;

				if(count($email_addresses) > 0) {
					$email_addresses = implode(', ',$email_addresses);
					$post_link = $CONFIG['board_address'].'/index.php?faction=viewtopic&post_id='.$new_post_id.'#post'.$new_post_id;

					$add_headers = 'Bcc: '.$email_addresses."\r\n";

					$email_tpl = new template($LANGUAGE_PATH.'/emails/email_new_reply_posted.tpl');
					mymail('"'.$CONFIG['board_name'].'" <'.$CONFIG['board_email_address'].'>','',$LNG['email_subject_new_reply_posted'],$email_tpl->parse_code(),$add_headers);
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
$DB->query("SELECT smiley_gfx,smiley_synonym FROM ".TBLPFX."smilies WHERE smiley_type='0'"); // Daten aller Smilies laden
while($akt_smiley = $DB->fetch_array())
	$smilies[$akt_smiley['smiley_synonym']] = '<img src="'.$akt_smiley['smiley_gfx'].'" border="0" alt="'.$akt_smiley['smiley_synonym'].'" />'; // Smiley in Array einfuegen


//
// Falls ein Beitrag zitiert werden soll...
//
if(isset($_GET['quote'])) {
	$DB->query("SELECT t1.post_text, t2.user_nick AS poster_nick FROM ".TBLPFX."posts AS t1 LEFT JOIN ".TBLPFX."users AS t2 ON t1.poster_id=t2.user_id WHERE t1.post_id='".intval($_GET['quote'])."'");
	if($quote_post_data = $DB->fetch_array())
		$p_post_text .= '[quote='.$quote_post_data['poster_nick'].']'.$quote_post_data['post_text'].'[/quote]'."\n";
}


$preply_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['postreply']);


$smilies_box = ($forum_data['forum_enable_smilies'] == 1) ? get_smilies_box() : ''; // Smiliesbox
$ppics_box = get_ppics_box($p_ppic_id); // Beitragsbilderbox


//
// Die Bloecke...
//
if($p_preview == 1) { // ...Vorschau...
	$preview_post = $p_post_text;
	if($p_htmlcode != 1 || $forum_data['forum_enable_htmlcode'] != 1) $preview_post = myhtmlentities($preview_post);
	if($p_smilies == 1 && $forum_data['forum_enable_smilies'] == 1) $preview_post = strtr($preview_post,$smilies);
	$preview_post = nlbr($preview_post);
	if($p_bbcode == 1 && $forum_data['forum_enable_bbcode'] == 1) $preview_post = bbcode($preview_post);

	$preply_tpl->blocks['preview']->parse_code();
}

multimutate('p_post_title','p_post_text','p_guest_nick');

if($error != '') $preply_tpl->blocks['errorrow']->parse_code(); // ...Fehler...
if($USER_LOGGED_IN != 1) $preply_tpl->blocks['namerow']->parse_code(); // ...Namensfeld...
if($forum_data['forum_enable_smilies'] == 1) $preply_tpl->blocks['smiliescheck']->parse_code(); // ...Smilies...
if($CONFIG['enable_sig'] == 1 && $USER_LOGGED_IN == 1) $preply_tpl->blocks['sigcheck']->parse_code(); // ...Signatur...
if($forum_data['forum_enable_bbcode'] == 1)	{ // ...BBCode...
	$bbcode_box = get_bbcode_box();

	$preply_tpl->blocks['bbcoderow']->parse_code();
	$preply_tpl->blocks['bbcodecheck']->parse_code();
}
if($forum_data['forum_enable_htmlcode'] == 1) $preply_tpl->blocks['htmlcodecheck']->parse_code(); // ...HTML-Code...
if($USER_LOGGED_IN == 1 && $CONFIG['enable_email_functions'] == 1 && $CONFIG['enable_topic_subscription'] == 1) { // ...Thema-Abonnement
	$DB->query("SELECT user_id FROM ".TBLPFX."topics_subscriptions WHERE topic_id='$topic_id' AND user_id='$USER_ID'");
	if($DB->affected_rows == 0)
		$preply_tpl->blocks['subscribecheck']->parse_code();
}
if($USER_LOGGED_IN == 1 && ($USER_DATA['user_is_admin'] == 1 || $auth_data['auth_is_mod'] == 1)) {
	$preply_tpl->blocks['importantcheck']->parse_code();
	$preply_tpl->blocks['closecheck']->parse_code();
}


//
// Der Themenrueckblick
//
$akt_cell_class = $TCONFIG['cell_classes']['start_class'];
$DB->query("SELECT t1.post_enable_bbcode, t1.post_enable_smilies, t1.post_enable_html, t1.post_text, t1.poster_id, t1.post_guest_nick, t2.user_nick AS poster_nick FROM ".TBLPFX."posts AS t1 LEFT JOIN ".TBLPFX."users AS t2 ON t1.poster_id=t2.user_id WHERE t1.topic_id='$topic_id' ORDER BY t1.post_time DESC LIMIT 5");
while($akt_post = $DB->fetch_array()) {
	if($akt_post['post_enable_html'] != 1 || $forum_data['forum_enable_htmlcode'] != 1) $akt_post['post_text'] = myhtmlentities($akt_post['post_text']);
	if($akt_post['post_enable_smilies'] == 1 && $forum_data['forum_enable_smilies'] == 1) $akt_post['post_text'] = strtr($akt_post['post_text'],$smilies);
	$akt_post['post_text'] = nlbr($akt_post['post_text']);
	if($akt_post['post_enable_bbcode'] == 1 && $forum_data['forum_enable_bbcode'] == 1) $akt_post['post_text'] = bbcode($akt_post['post_text']);

	if($akt_post['poster_id'] == 0) $akt_post_poster_nick = $akt_post['post_guest_nick'];
	else $akt_post_poster_nick = $akt_post['poster_nick'];

	$preply_tpl->blocks['reviewpostrow']->parse_code(FALSE,TRUE);
	$akt_cell_class = ($akt_cell_class == $TCONFIG['cell_classes']['td1_class']) ? $TCONFIG['cell_classes']['td2_class'] : $TCONFIG['cell_classes']['td1_class'];
}


//
// Der Rest...
//
$max_nick_chars = sprintf($LNG['Maximum_x_chars'],60);

add_navbar_items(array($LNG['Post_reply'],"index.php?faction=postreply&amp;topic_id=$topic_id&amp;$MYSID"));

include_once('pheader.php');
show_navbar();
$preply_tpl->parse_code(TRUE);
include_once('ptail.php');

?>