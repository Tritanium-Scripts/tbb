<?php
/**
*
* Tritanium Bulletin Board 2 - posttopic.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');
require_once('bbcode.php');

$forum_id = isset($_GET['forum_id']) ? $_GET['forum_id'] : 0;

if(!$forum_data = get_forum_data($forum_id)) die('Kann Forendaten nicht laden/Forum existiert nicht!');


//
// Beginn Authentifizierung
//
if($USER_LOGGED_IN != 1) {
	if($forum_data['auth_guests_post_topic'] != 1) {
		add_navbar_items(array($LNG['Not_logged_in'],''));

		include_once('pheader.php');
				show_message($LNG['Not_logged_in'],$LNG['message_not_logged_in'].'<br />'.$LNG['click_here_login'].'<br />'.$LNG['click_here_register']);
		include_once('ptail.php'); exit;
	}

	$auth_data = array(
		'auth_post_topic'=>$forum_data['auth_guests_post_topic'],
		'auth_post_poll'=>$forum_data['auth_guests_post_poll'],
		'auth_is_mod'=>0
	);
}
elseif($USER_DATA['user_is_admin'] != 1 && $USER_DATA['user_is_supermod'] != 1) {
	if(!$auth_data = get_auth_forum_user($forum_id,$USER_ID,array('auth_post_topic','auth_post_reply','auth_is_mod'))) {
		$auth_data = array(
			'auth_post_topic'=>$forum_data['auth_members_post_topic'],
			'auth_post_poll'=>$forum_data['auth_members_post_poll'],
			'auth_is_mod'=>0
		);
	}
	if($auth_data['auth_is_mod'] != 1) {
		if($forum_data['auth_members_post_topic'] != 1 && $auth_data['auth_post_topic'] != 1 || $forum_data['auth_members_post_topic'] == 1 && $auth_data['auth_post_topic'] == 0) {
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


$error = '';

$p_post_text = isset($_POST['p_message_text']) ? $_POST['p_message_text'] : '';
$p_topic_title = isset($_POST['p_topic_title']) ? $_POST['p_topic_title'] : '';
$p_preview = isset($_POST['p_preview']) ? 1 : 0;
$p_guest_nick = isset($_POST['p_guest_nick']) ? $_POST['p_guest_nick'] : '';
$p_ppic_id = isset($_POST['p_ppic_id']) ? $_POST['p_ppic_id'] : 0;
$p_poll_title = isset($_POST['p_poll_title']) ? $_POST['p_poll_title'] : '';
$p_poll_options = isset($_POST['p_poll_options']) ? $_POST['p_poll_options'] : array();
$p_poll_add_option = isset($_POST['p_poll_add_option']) ? 1 : 0;
$p_poll_option_title = isset($_POST['p_poll_option_title']) ? $_POST['p_poll_option_title'] : '';
$p_poll_delete_options = isset($_POST['p_poll_delete_options']) ? $_POST['p_poll_delete_options'] : array();

$preview_post = ($p_preview == 1) ? mysslashes($p_post_text) : '';

$p_smilies = $p_signature = $p_bbcode = 1;
$p_htmlcode = $p_subscribe = $p_important = $p_close = 0;

if($p_poll_add_option == 1) {
	if(trim($p_poll_option_title) != '')
		$p_poll_options[] = $p_poll_option_title;

	reset($p_poll_options);
}

if(count($p_poll_delete_options) > 0) {
	while(list($akt_key) = each($p_poll_delete_options)) {
		if(isset($p_poll_options[$akt_key])) unset($p_poll_options[$akt_key]);
	}
	reset($p_poll_options);
}

if(isset($_GET['doit']) && $p_preview != 1 && $p_poll_add_option != 1 && count($p_poll_delete_options) == 0) {
	$p_bbcode = isset($_POST['p_bbcode']) ? 1 : 0;
	$p_smilies = isset($_POST['p_smilies']) ? 1 : 0;
	$p_htmlcode = isset($_POST['p_htmlcode']) ? 1 : 0;
	$p_signature = isset($_POST['p_signature']) ? 1 : 0;
	$p_subscribe = isset($_POST['p_subscribe']) ? 1 : 0;
	$p_important = isset($_POST['p_important']) ? 1 : 0;
	$p_close = isset($_POST['p_close']) ? 1 : 0;

	if($p_topic_title == '') $error = $LNG['error_no_title'];
	elseif(strlen($p_topic_title) > 60) $error = $LNG['error_title_too_long'];
	elseif($p_post_text == '') $error = $LNG['error_no_post'];
	elseif($USER_LOGGED_IN != 1 && verify_nick($p_guest_nick) == FALSE) $error = $LNG['error_invalid_name'];
	elseif($USER_LOGGED_IN != 1 && unify_nick($p_guest_nick) == FALSE) $error = $LNG['error_existing_user_name'];
	else {
		$topic_pinned_status = $topic_status = 0;
		if($USER_LOGGED_IN == 1 && ($USER_DATA['user_is_admin'] == 1 || $USER_DATA['user_is_supermod'] == 1 || $auth_data['auth_is_mod'] == 1)) {
			$topic_pinned_status = $p_important;
			$topic_status = $p_close;
		}

		if($USER_ID != 0)
			$p_post_texter_nick = '';

		$DB->query("INSERT INTO ".TBLPFX."topics (topic_title,forum_id,topic_status,topic_is_pinned,poster_id,topic_pic,topic_poll,topic_post_time,topic_guest_nick) VALUES ('$p_topic_title','$forum_id','$topic_status','$topic_pinned_status','$USER_ID','$p_ppic_id','0','".time()."','$p_guest_nick')");
		$new_topic_id = $DB->insert_id;

		$DB->query("INSERT INTO ".TBLPFX."posts (topic_id,forum_id,poster_id,post_ip,post_pic,post_enable_bbcode,post_enable_smilies,post_enable_html,post_show_sig,post_time,post_title,post_text,post_guest_nick) VALUES ('$new_topic_id','$forum_id','$USER_ID','".$_SERVER['REMOTE_ADDR']."','$p_ppic_id','$p_bbcode','$p_smilies','$p_htmlcode','$p_signature','".time()."','$p_topic_title','$p_post_text','$p_guest_nick')");
		$new_post_id = $DB->insert_id;

		$DB->query("UPDATE ".TBLPFX."topics SET topic_first_post_id='$new_post_id', topic_last_post_id='$new_post_id' WHERE topic_id='$new_topic_id'");
		$DB->query("UPDATE ".TBLPFX."forums SET forum_last_post_id='$new_post_id', forum_posts_counter=forum_posts_counter+1, forum_topics_counter=forum_topics_counter+1 WHERE forum_id='$forum_id'");
		$DB->query("UPDATE ".TBLPFX."users SET user_posts=user_posts+1 WHERE user_id='$USER_ID'");

		if(($USER_DATA['user_is_admin'] == 1 || $USER_DATA['user_is_supermod'] == 1 || $auth_data['auth_is_mod'] == 1 || $auth_data['auth_post_poll'] == 1) && trim($p_poll_title) != '') {
			if(trim($p_poll_option_title) != '')
				$p_poll_options[] = $p_poll_option_title;
			reset($p_poll_options);

			while(list($akt_key) = each($p_poll_options)) {
				if(trim($p_poll_options[$akt_key]) == '')
					unset($p_poll_options[$akt_key]);
			}
			reset($p_poll_options);

			if(count($p_poll_options) > 1) {
				$DB->query("INSERT INTO ".TBLPFX."polls (topic_id,poster_id,poll_title) VALUES ('$new_topic_id','$USER_ID','$p_poll_title')");
				$new_poll_id = $DB->insert_id;

				$i = 1;

				while(list(,$akt_option) = each($p_poll_options)) {
					$DB->query("INSERT INTO ".TBLPFX."polls_options (poll_id,option_id,option_title) VALUES ('$new_poll_id','$i','$akt_option')");
					$i++;
				}

				$DB->query("UPDATE ".TBLPFX."topics SET topic_poll='1' WHERE topic_id='$new_topic_id'");
			}
		}

		if($USER_LOGGED_IN == 1 && $CONFIG['enable_email_functions'] == 1 && $CONFIG['enable_topic_subscription'] == 1 && $p_subscribe == 1)
			$DB->query("INSERT INTO ".TBLPFX."topics_subscriptions (topic_id,user_id) VALUES ('$new_topic_id','$USER_ID')");

		header("Location: index.php?faction=viewtopic&topic_id=$new_topic_id&$MYSID"); exit;
	}
}

$checked = array(
	'smilies'=>($p_smilies == 1) ? ' checked="checked"' : '',
	'signature'=>($p_signature == 1) ? ' checked="checked"' : '',
	'bbcode'=>($p_bbcode == 1) ? ' checked="checked"' : '',
	'htmlcode'=>($p_htmlcode == 1) ? ' checked="checked"' : '',
	'subscribe'=>($p_subscribe == 1) ? ' checked="checked"' : '',
	'close'=>($p_close == 1) ? ' checked="checked"' : '',
	'important'=>($p_important == 1) ? ' checked="checked"' : ''
);

multimutate('p_topic_title','p_post_text','p_guest_nick');

$ptopic_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['posttopic']);

$smilies_box = ($forum_data['forum_enable_smilies'] == 1) ? get_smilies_box() : ''; // Smiliesbox
$ppics_box = get_ppics_box($p_ppic_id); // Beitragsbildbox


//
// Smilies (werden später an verschiedenen Stellen benötigt)
//
$smilies = array(); // Beinhaltet spaeter die Smilies mit dem Synonym als Key und dem Bild (HTML) als Wert
$DB->query("SELECT smiley_gfx,smiley_synonym FROM ".TBLPFX."smilies WHERE smiley_type='0'"); // Daten aller Smilies laden
while($akt_smiley = $DB->fetch_array())
	$smilies[$akt_smiley['smiley_synonym']] = '<img src="'.$akt_smiley['smiley_gfx'].'" border="0" alt="'.$akt_smiley['smiley_synonym'].'" />'; // Smiley in Array einfuegen


//
// Die Bloecke...
//
if($error != '') $ptopic_tpl->blocks['errorrow']->parse_code(); // ...Fehler...
if($p_preview == 1) { // ...Vorschau...
	if($p_htmlcode != 1 || $forum_data['forum_enable_htmlcode'] != 1) $preview_post = myhtmlentities($preview_post);
	if($p_smilies == 1 && $forum_data['forum_enable_smilies'] == 1) $preview_post = strtr($preview_post,$smilies);
	$preview_post = nlbr($preview_post);
	if($p_bbcode == 1 && $forum_data['forum_enable_bbcode'] == 1) $preview_post = bbcode($preview_post);

	$ptopic_tpl->blocks['preview']->parse_code();
}
if($USER_LOGGED_IN != 1) $ptopic_tpl->blocks['namerow']->parse_code(); // ...Namensfeld...
if($forum_data['forum_enable_smilies'] == 1) $ptopic_tpl->blocks['smiliescheck']->parse_code(); // ...Smilies...
if($CONFIG['enable_sig'] == 1 && $USER_LOGGED_IN == 1) $ptopic_tpl->blocks['sigcheck']->parse_code(); // ...Signatur...
if($forum_data['forum_enable_bbcode'] == 1)	{ // ...BBCode...
	$bbcode_box = get_bbcode_box();

	$ptopic_tpl->blocks['bbcoderow']->parse_code();
	$ptopic_tpl->blocks['bbcodecheck']->parse_code();
}
if($forum_data['forum_enable_htmlcode'] == 1) $ptopic_tpl->blocks['htmlcodecheck']->parse_code(); // ...HTML-Code...
if($USER_LOGGED_IN == 1 && $CONFIG['enable_email_functions'] == 1 && $CONFIG['enable_topic_subscription'] == 1) $ptopic_tpl->blocks['subscribecheck']->parse_code(); // ...Thema-Abonnement
if($USER_LOGGED_IN == 1 && ($USER_DATA['user_is_admin'] == 1 || $USER_DATA['user_is_supermod'] == 1 || $auth_data['auth_is_mod'] == 1)) {
	$ptopic_tpl->blocks['closecheck']->parse_code();
	$ptopic_tpl->blocks['importantcheck']->parse_code();
}


//
// Umfrage
//
if($USER_DATA['user_is_admin'] == 1 || $USER_DATA['user_is_supermod'] == 1 || $auth_data['auth_is_mod'] == 1 || $auth_data['auth_post_poll'] == 1) {
	if(count($p_poll_options) != 0) {
		while(list($akt_option_key,$akt_option) = each($p_poll_options)) {
			$akt_option = mutate($akt_option);
			$ptopic_tpl->blocks['pollrow']->blocks['optionrow']->parse_code(FALSE,TRUE);
		}
	}
	$ptopic_tpl->blocks['pollrow']->parse_code();
}


//
// Der Rest...
//
$title_max_chars = sprintf($LNG['Maximum_x_chars'],60);

add_navbar_items(array($forum_data['forum_name'],"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID"),array($LNG['Post_topic'],"index.php?faction=posttopic&amp;forum_id=$forum_id&amp;$MYSID"));

include_once('pheader.php');
$ptopic_tpl->parse_code(TRUE);
include_once('ptail.php');

?>