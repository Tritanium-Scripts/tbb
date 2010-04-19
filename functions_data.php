<?php
/**
*
* Tritanium Bulletin Board 2 - functions_data.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

function get_user_counter() {
	global $DB;

	$user_counter = 0;

	$DB->query("SELECT COUNT(*) FROM ".TBLPFX."users");
	list($user_counter) = $DB->fetch_array();

	return $user_counter;
}

function unify_nick($nick) {
	global $DB;

	$DB->query("SELECT user_id FROM ".TBLPFX."users WHERE user_nick='$nick'");
	return ($DB->affected_rows > 0) ? FALSE : TRUE;
}

function get_user_data($user_id) {
	global $DB;

	if(!preg_match('/^[0-9]{1,}$/si',$user_id))
		$DB->query("SELECT * FROM ".TBLPFX."users WHERE user_nick='$user_id'");
	else $DB->query("SELECT * FROM ".TBLPFX."users WHERE user_id='$user_id'");

	return ($DB->affected_rows == 1) ? $DB->fetch_array() : FALSE;
}

function get_cat_data($cat_id) {
	return cats_get_cat_data($cat_id);
}

function get_forum_data($forum_id) {
	global $DB;

	$DB->query("SELECT * FROM ".TBLPFX."forums WHERE forum_id='$forum_id'");
	return ($DB->affected_rows == 1) ? $DB->fetch_array() : FALSE;
}

function get_user_id($user_id) {
	global $DB;

	if(!preg_match('/^[0-9]{1,}$/si',$user_id))
		$DB->query("SELECT user_id FROM ".TBLPFX."users WHERE user_nick='$user_id'");
	else $DB->query("SELECT user_id FROM ".TBLPFX."users WHERE user_id='$user_id'");

	if($DB->affected_rows == 1) {
		list($user_id) = $DB->fetch_array();
		return $user_id;
	}
	else return FALSE;
}

function get_topics_counter() {
	global $DB;

	$DB->query("SELECT COUNT(*) AS topics_counter FROM ".TBLPFX."topics");
	list($topics_counter) = $DB->fetch_array();

	return $topics_counter;
}

function get_posts_counter() {
	global $DB;

	$DB->query("SELECT COUNT(*) AS posts_counter FROM ".TBLPFX."posts");
	list($posts_counter) = $DB->fetch_array();

	return $posts_counter;
}

function get_members_counter() {
	global $DB;

	$DB->query("SELECT COUNT(*) AS members_counter FROM ".TBLPFX."members");
	list($members_counter) = $DB->fetch_array();

	return $members_counter;
}

function get_forum_topics_counter($forum_id) {
	global $DB;

	$DB->query("SELECT COUNT(*) AS topics_counter FROM ".TBLPFX."topics WHERE forum_id='$forum_id'");
	list($topics_counter) = $DB->fetch_array();

	return $topics_counter;
}

function get_topic_data($topic_id) {
	global $DB;

	$DB->query("SELECT * FROM ".TBLPFX."topics WHERE topic_id='$topic_id'");
	return ($DB->affected_rows == 1) ? $DB->fetch_array() : FALSE;
}

function get_topic_posts_counter($topic_id) {
	global $DB;

	$DB->query("SELECT COUNT(*) FROM ".TBLPFX."posts WHERE topic_id='$topic_id'");
	list($topic_posts_counter) = $DB->fetch_array();

	return $topic_posts_counter;
}

function get_smiley_data($smiley_id) {
	global $DB;

	$DB->query("SELECT * FROM ".TBLPFX."smilies WHERE smiley_id='$smiley_id'");
	return ($DB->affected_rows == 1) ? $DB->fetch_array() : FALSE;
}

function get_post_data($post_id) {
	global $DB;

	$DB->query("SELECT * FROM ".TBLPFX."posts WHERE post_id='$post_id'");
	return ($DB->affected_rows == 1) ? $DB->fetch_array() : FALSE;
}

function get_group_data($group_id) {
	global $DB;

	$DB->query("SELECT * FROM ".TBLPFX."groups WHERE group_id='$group_id'");
	return ($DB->affected_rows == 1) ? $DB->fetch_array() : FALSE;
}

function get_poll_data($poll_id) {
	global $DB;

	$DB->query("SELECT * FROM ".TBLPFX."polls WHERE poll_id='$poll_id'");
	return ($DB->affected_rows == 1) ? $DB->fetch_array() : FALSE;
}

function get_rank_data($rank_id) {
	global $DB;

	$DB->query("SELECT * FROM ".TBLPFX."ranks WHERE rank_id='$rank_id'");
	return ($DB->affected_rows == 1) ? $DB->fetch_array() : FALSE;
}

function get_ranks_data() {
	global $DB;

	$ranks_data = array(array(),array());


	$DB->query("SELECT * FROM ".TBLPFX."ranks ORDER BY rank_posts");
	while($akt_rank = $DB->fetch_array()) {
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
	global $DB;

	$DB->query("SELECT * FROM ".TBLPFX."avatars WHERE avatar_id='$avatar_id'");
	return ($DB->affected_rows == 1) ? $DB->fetch_array() : FALSE;
}

function update_topic_last_post($topic_id) {
	global $DB;

	if(!$DB->query("SELECT post_id FROM ".TBLPFX."posts WHERE topic_id='$topic_id' ORDER BY post_time DESC LIMIT 1")) return FALSE;
	list($topic_last_post_id) = $DB->fetch_array();

	if(!$DB->query("UPDATE ".TBLPFX."topics SET topic_last_post_id='$topic_last_post_id' WHERE topic_id='$topic_id'")) return FALSE;

	return TRUE;
}

function update_forum_last_post($forum_id) {
	global $DB;

	if(!$DB->query("SELECT post_id FROM ".TBLPFX."posts WHERE forum_id='$forum_id' ORDER BY post_time DESC LIMIT 1")) return FALSE;
	($DB->affected_rows == 0) ? $forum_last_post_id = 0 : list($forum_last_post_id) = $DB->fetch_array();

	if(!$DB->query("UPDATE ".TBLPFX."forums SET forum_last_post_id='$forum_last_post_id' WHERE forum_id='$forum_id'")) return FALSE;

	return TRUE;
}

?>