<?php
/**
*
* Tritanium Bulletin Board 2 - subscribetopic.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : 0;
$z = isset($_GET['z']) ? $_GET['z'] : 1;

if(!$topic_data = get_topic_data($topic_id)) die('Kann Themendaten nicht laden!');
elseif(!$forum_data = get_forum_data($topic_data['forum_id'])) die('Kann Forumdaten nicht laden!');

$forum_id = $forum_data['forum_id'];

if($USER_LOGGED_IN == 1 && $CONFIG['enable_email_functions'] == 1 && $CONFIG['enable_topic_subscription'] == 1) {
	$db->query("SELECT user_id FROM ".TBLPFX."topics_subscriptions WHERE user_id='$USER_ID' AND topic_id='$topic_id'");
	if($db->affected_rows == 1) {
		$db->query("DELETE FROM ".TBLPFX."topics_subscriptions WHERE user_id='$USER_ID' AND topic_id='$topic_id'");
		$message_title = $lng['Topic_unsubscription'];
		$message_text = $lng['message_topic_unsubscription_successful'];
	}
	else {
		$db->query("INSERT INTO ".TBLPFX."topics_subscriptions (topic_id,user_id) VALUES ('$topic_id','$USER_ID')");
		$message_title = $lng['Topic_subscription'];
		$message_text = $lng['message_topic_subscription_successful'];
	}

	add_navbar_items(array(myhtmlentities($forum_data['forum_name']),"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID"),array(myhtmlentities($topic_data['topic_title']),"index.php?faction=viewtopic&amp;topic_id=$topic_id&amp;$MYSID"),array($message_title,''));

	include_once('pheader.php');
	show_navbar();
	show_message($message_title,$message_text.'<br />'.sprintf($lng['click_here_back_topic'],"<a href=\"index.php?faction=viewtopic&amp;topic_id=$topic_id&amp;z=$z&amp;$MYSID\">",'</a>'));
	include_once('ptail.php'); exit;
}

header("Location: index.php?faction=viewtopic&topic_id=$topic_id&z=$z&$MYSID"); exit;

?>