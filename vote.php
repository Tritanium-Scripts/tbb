<?php
/**
*
* Tritanium Bulletin Board 2 - vote.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$poll_id = isset($_GET['poll_id']) ? $_GET['poll_id'] : 0;
$p_option_id = isset($_POST['p_option_id']) ? $_POST['p_option_id'] : 0;
$z = isset($_GET['z']) ? $_GET['z'] : 1;

if(!$poll_data = get_poll_data($poll_id)) die('Kann Umfragedaten nicht laden!');
if(!$topic_data = get_topic_data($poll_data['topic_id'])) die('Kann Themendaten nicht laden!');
if(!$forum_data = get_forum_data($topic_data['forum_id'])) die('Kann Forumdaten nicht laden!');

$forum_id = $topic_data['forum_id'];
$topic_id = $topic_data['topic_id'];

if($USER_LOGGED_IN == 1) {
	//
	// Beginn Authentifizierung
	//
	if($USER_DATA['user_is_admin'] != 1) {
		if(!$auth_data = get_auth_forum_user($forum_id,$USER_ID,array('auth_view_forum','auth_is_mod'))) {
			$auth_data = array(
				'auth_view_forum'=>$forum_data['auth_members_view_forum'],
				'auth_is_mod'=>0
			);
		}
		if($auth_data['auth_is_mod'] != 1) {
			if($forum_data['auth_members_view_forum'] != 1 && $auth_data['auth_view_forum'] != 1 || $forum_data['auth_members_view_forum'] == 1 && $auth_data['auth_view_forum'] == 0) {
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

	$db->query("SELECT voter_id FROM ".TBLPFX."polls_voters WHERE poll_id='$poll_id' AND voter_id='$USER_ID'",TRUE);
	if($db->affected_rows == 0) {
		$db->query("UPDATE ".TBLPFX."polls_options SET option_votes=option_votes+1 WHERE poll_id='$poll_id' AND option_id='$p_option_id'",TRUE);
		if($db->affected_rows == 1) {
			$db->query("UPDATE ".TBLPFX."polls SET poll_votes=poll_votes+1 WHERE poll_id='$poll_id'",TRUE);
			$db->query("INSERT INTO ".TBLPFX."polls_voters (poll_id,voter_id) VALUES ('$poll_id','$USER_ID')",TRUE);
		}
	}
}

header("Location: index.php?faction=viewtopic&topic_id=$topic_id&z=$z&$MYSID"); exit;

?>