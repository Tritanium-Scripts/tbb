<?php
/**
*
* Tritanium Bulletin Board 2 - functions_data.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

function get_user_counter() {
	global $db;

	$user_counter = 0;

	$db->query("SELECT COUNT(*) FROM ".TBLPFX."users");
	list($user_counter) = $db->fetch_array();

	return $user_counter;
}

function unify_nick($nick) {
	global $db;

	$db->query("SELECT user_id FROM ".TBLPFX."users WHERE user_nick='$nick'");
	return ($db->affected_rows > 0) ? FALSE : TRUE;
}

function get_user_data($user_id) {
	global $db;

	if(!preg_match('/^[0-9]{1,}$/si',$user_id))
		$db->query("SELECT * FROM ".TBLPFX."users WHERE user_nick='$user_id'");
	else $db->query("SELECT * FROM ".TBLPFX."users WHERE user_id='$user_id'");

	return ($db->affected_rows == 1) ? $db->fetch_array() : FALSE;
}

function get_cat_data($cat_id) {
	return cats_get_cat_data($cat_id);
}

function get_forum_data($forum_id) {
	global $db;

	$db->query("SELECT * FROM ".TBLPFX."forums WHERE forum_id='$forum_id'");
	return ($db->affected_rows == 1) ? $db->fetch_array() : FALSE;
}

function get_user_id($user_id) {
	global $db;

	if(!preg_match('/^[0-9]{1,}$/si',$user_id))
		$db->query("SELECT user_id FROM ".TBLPFX."users WHERE user_nick='$user_id'");
	else $db->query("SELECT user_id FROM ".TBLPFX."users WHERE user_id='$user_id'");

	if($db->affected_rows == 1) {
		list($user_id) = $db->fetch_array();
		return $user_id;
	}
	else return FALSE;
}

function get_topics_counter() {
	global $db;

	$db->query("SELECT COUNT(*) AS topics_counter FROM ".TBLPFX."topics");
	list($topics_counter) = $db->fetch_array();

	return $topics_counter;
}

function get_posts_counter() {
	global $db;

	$db->query("SELECT COUNT(*) AS psots_counter FROM ".TBLPFX."posts");
	list($posts_counter) = $db->fetch_array();

	return $posts_counter;
}

function get_members_counter() {
	global $db;

	$db->query("SELECT COUNT(*) AS members_counter FROM ".TBLPFX."members");
	list($members_counter) = $db->fetch_array();

	return $members_counter;
}

function get_forum_topics_counter($forum_id) {
	global $db;

	$db->query("SELECT COUNT(*) AS topics_counter FROM ".TBLPFX."topics WHERE forum_id='$forum_id'");
	list($topics_counter) = $db->fetch_array();

	return $topics_counter;
}

function get_topic_data($topic_id) {
	global $db;

	$db->query("SELECT * FROM ".TBLPFX."topics WHERE topic_id='$topic_id'");
	return ($db->affected_rows == 1) ? $db->fetch_array() : FALSE;
}

function get_topic_posts_counter($topic_id) {
	global $db;

	$db->query("SELECT COUNT(*) FROM ".TBLPFX."posts WHERE topic_id='$topic_id'");
	list($topic_posts_counter) = $db->fetch_array();

	return $topic_posts_counter;
}

function get_smiley_data($smiley_id) {
	global $db;

	$db->query("SELECT * FROM ".TBLPFX."smilies WHERE smiley_id='$smiley_id'");
	return ($db->affected_rows == 1) ? $db->fetch_array() : FALSE;
}

function get_post_data($post_id) {
	global $db;

	$db->query("SELECT t1.*,t2.post_title,t2.post_text FROM ".TBLPFX."posts AS t1, ".TBLPFX."posts_text AS t2 WHERE t1.post_id='$post_id' AND t2.post_id=t1.post_id");
	return ($db->affected_rows == 1) ? $db->fetch_array() : FALSE;
}

function get_group_data($group_id) {
	global $db;

	$db->query("SELECT * FROM ".TBLPFX."groups WHERE group_id='$group_id'");
	return ($db->affected_rows == 1) ? $db->fetch_array() : FALSE;
}

function get_poll_data($poll_id) {
	global $db;

	$db->query("SELECT * FROM ".TBLPFX."polls WHERE poll_id='$poll_id'");
	return ($db->affected_rows == 1) ? $db->fetch_array() : FALSE;
}

function get_rank_data($rank_id) {
	global $db;

	$db->query("SELECT * FROM ".TBLPFX."ranks WHERE rank_id='$rank_id'");
	return ($db->affected_rows == 1) ? $db->fetch_array() : FALSE;
}

function get_ranks_data() {
	global $db;

	$ranks_data = array(array(),array());


	$db->query("SELECT * FROM ".TBLPFX."ranks ORDER BY rank_posts");
	while($akt_rank = $db->fetch_array()) {
		$akt_rank_gfx = '';

		if($akt_rank['rank_gfx'] != '') {
			$akt_rank_gfx = explode(';',$akt_rank['rank_gfx']);
			while(list($akt_key) = each($akt_rank_gfx))
				$akt_rank_gfx[$akt_key] = '<img src="'.$akt_rank_gfx[$akt_key].'" border="0" alt="" />';
			$akt_rank_gfx = implode('',$akt_rank_gfx);
		}

		if($akt_rank['rank_type'] == 0) {
			$ranks_data[0][] = array(
				'rank_name'=>$akt_rank['rank_name'],
				'rank_posts'=>$akt_rank['rank_posts'],
				'rank_gfx'=>$akt_rank_gfx
			);
		}
		else {
			$ranks_data[1][$akt_rank['rank_id']] = array(
				'rank_name'=>$akt_rank['rank_name'],
				'rank_gfx'=>$akt_rank_gfx
			);
		}
	}

	return $ranks_data;
}

function get_avatar_data($avatar_id) {
	global $db;

	$db->query("SELECT * FROM ".TBLPFX."avatars WHERE avatar_id='$avatar_id'");
	return ($db->affected_rows == 1) ? $db->fetch_array() : FALSE;
}

function update_topic_last_post($topic_id) {
	global $db;

	if(!$db->query("SELECT post_id FROM ".TBLPFX."posts WHERE topic_id='$topic_id' ORDER BY post_time DESC LIMIT 1")) return FALSE;
	list($topic_last_post_id) = $db->fetch_array();

	if(!$db->query("UPDATE ".TBLPFX."topics SET topic_last_post_id='$topic_last_post_id' WHERE topic_id='$topic_id'")) return FALSE;

	return TRUE;
}

function update_forum_last_post($forum_id) {
	global $db;

	if(!$db->query("SELECT post_id FROM ".TBLPFX."posts WHERE forum_id='$forum_id' ORDER BY post_time DESC LIMIT 1")) return FALSE;
	($db->affected_rows == 0) ? $forum_last_post_id = 0 : list($forum_last_post_id) = $db->fetch_array();

	if(!$db->query("UPDATE ".TBLPFX."forums SET forum_last_post_id='$forum_last_post_id' WHERE forum_id='$forum_id'")) return FALSE;

	return TRUE;
}

?>