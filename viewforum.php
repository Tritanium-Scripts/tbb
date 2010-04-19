<?php
/**
*
* Tritanium Bulletin Board 2 - viewforum.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$forum_id = isset($_GET['forum_id']) ? $_GET['forum_id'] : 0;
$z = isset($_GET['z']) ? $_GET['z'] : 1;

if(!$forum_data = get_forum_data($forum_id)) die('Forum existiert nicht/Kann Forendaten nicht laden!');


//
// Beginn Authentifizierung
//
if($user_logged_in != 1) {
	if($forum_data['auth_guests_view_forum'] != 1) {
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


update_forum_cookie($forum_id);

if(isset($_GET['mark'])) {
	$c_topics = isset($_COOKIE['c_topics']) ? explode('x',$_COOKIE['c_topics']) : array();
	while(list($akt_key,$akt_value) = each($c_topics)) {
		$akt_value = explode('y',$akt_value);
		if($akt_value[0] == $forum_id) {
			unset($c_topics[$akt_key]);
			break;
		}
	}
	$c_topics = implode('x',$c_topics);
	setcookie('c_topics',$c_topics,time()+31536000,'/');
	$_COOKIE['c_topics'] = $c_topics;
}

$topics_counter = get_forum_topics_counter($forum_id);

$page_counter = ceil($topics_counter/$CONFIG['topics_per_page']);

if($z == 'last') $z = $page_counter;

$start = $z*$CONFIG['topics_per_page']-$CONFIG['topics_per_page'];
$page_listing = array();

$pre = $suf = '';

if($page_counter > 0) {

	if($page_counter > 5) {
		if($z > 2 && $z < $page_counter-2) {
			$page_listing = array($z-2,$z-1,$z,$z+1,$z+2);
		}
		elseif($z <= 2) {
			$page_listing = array(1,2,3,4,5);
		}
		elseif($z >= $page_counter-2) {
			$page_listing = array($page_counter-4,$page_counter-3,$page_counter-2,$page_counter-1,$page_counter);
		}
	}
	else {
		for($i = 1; $i < $page_counter+1; $i++) {
			$page_listing[] = $i;
		}
	}
}
else $page_listing[] = 1;
for($i = 0; $i < sizeof($page_listing); $i++) {
	if($page_listing[$i] != $z) $page_listing[$i] = "<a href=\"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;z=".$page_listing[$i]."&amp;$MYSID\">".$page_listing[$i].'</a>';
}


if($z > 1) $pre = '<a href="index.php?faction=viewforum&amp;forum_id='.$forum_id.'&amp;z=1&amp;'.$MYSID.'">&#171;</a>&nbsp;<a href="index.php?faction=viewforum&amp;forum_id='.$forum_id.'&amp;z='.($z-1).'&amp;'.$MYSID.'">&#8249;</a>&nbsp;&nbsp;';
if($z < $page_counter) $suf = '&nbsp;&nbsp;<a href="index.php?faction=viewforum&amp;forum_id='.$forum_id.'&z='.($z+1).'&'.$MYSID.'">&#8250;</a>&nbsp;<a href="index.php?faction=viewforum&amp;forum_id='.$forum_id.'&amp;z=last&amp;'.$MYSID.'">&#187;</a>';

$page_listing = sprintf($lng['Pages'],$pre.implode(' | ',$page_listing).$suf);

$viewforum_tpl = new template;
$viewforum_tpl->load($template_path.'/'.$tpl_config['tpl_viewforum']);

$title_add .= ' &#187; '.$forum_data['forum_name'];

if($topics_counter == 0) {
	$viewforum_tpl->blocks['no_topics']->values = array(
		'LNG_NO_TOPICS'=>$lng['No_topics']
	);
	$viewforum_tpl->blocks['no_topics']->parse_code();
	$viewforum_tpl->unset_block('topicrow');
}
else {
	$c_forums = array();
	$c_forums_temp = isset($_COOKIE['c_forums']) ? explode('x',$_COOKIE['c_forums']) : array();
	while(list(,$akt_value) = each($c_forums_temp)) {
		$akt_value = explode('_',$akt_value);
		$c_forums[$akt_value[0]] = $akt_value[1];
	}

	$c_topics = array();
	$c_topics_temp = isset($_COOKIE['c_topics']) ? explode('x',$_COOKIE['c_topics']) : $c_topics_temp = array();
	while(list($akt_key,$akt_value_2) = each($c_topics_temp)) {
		$akt_value_2 = explode('y',$akt_value_2);
		if($akt_value_2[0] == $forum_id) {
			$akt_value_2[1] = explode('z',$akt_value_2[1]);
			while(list(,$akt_value) = each($akt_value_2[1])) {
				$akt_value = explode('_',$akt_value);
				$c_topics[$akt_value[0]] = $akt_value[1];
			}
		}
	}

	$ppics_data = get_smilies_data(array('smiley_type'=>1));

	$viewforum_tpl->unset_block('no_topics');
	$topics_data = get_topics_data($forum_id,$start,$CONFIG['topics_per_page']);
	while(list(,$akt_topic_data) = each($topics_data)) {
		$akt_topic_prefix = '';
		if($akt_topic_data['topic_is_pinned'] == 1) $akt_topic_prefix .= $lng['Important'].': ';
		if($akt_topic_data['topic_is_moved'] == 1) $akt_topic_prefix .= $lng['Moved'].': ';

		if(isset($c_topics[$akt_topic_data['topic_id']]) == FALSE && isset($c_forums[$forum_id]) == TRUE && $c_forums[$forum_id] < $akt_topic_data['topic_post_time']) {
			update_topic_cookie($akt_topic_data['forum_id'],$akt_topic_data['topic_id'],0);
			$c_topics[$akt_topic_data['topic_id']] = 0;
		}

		if(isset($c_topics[$akt_topic_data['topic_id']]) == TRUE && $c_topics[$akt_topic_data['topic_id']] < $akt_topic_data['topic_last_post_time']) {
			$akt_topic_status = $template_path.'/'.$tpl_config['img_topic_on_open'];
		}
		else {
			$akt_topic_status = $template_path.'/'.$tpl_config['img_topic_off_open'];
		}

		if($akt_topic_data['topic_last_post_poster_id'] == 0)
			$topic_last_post_poster = $akt_topic_data['topic_last_post_poster_nick'];
		else $topic_last_post_poster = '<a href="index.php?faction=viewprofile&amp;profile_id='.$akt_topic_data['topic_last_post_poster_id'].'&amp;'.$MYSID.'">'.$akt_topic_data['topic_last_post_poster_nick'].'</a>';

		$topic_last_post = format_date($akt_topic_data['topic_last_post_time']).'<br />'.$lng['by'].' '.$topic_last_post_poster.' <a href="index.php?faction=viewtopic&amp;topic_id='.$akt_topic_data['topic_id'].'&amp;z=last&amp;'.$MYSID.'#post'.$akt_topic_data['topic_last_post_id'].'">&#187;</a>';

		if($akt_topic_data['topic_poster_id'] == 0)
			$topic_poster = $akt_topic_data['topic_poster_nick'];
		else $topic_poster = '<a href="index.php?faction=viewprofile&amp;profile_id='.$akt_topic_data['topic_poster_id'].'&amp;'.$MYSID.'">'.$akt_topic_data['topic_poster_nick'].'</a>';

		$akt_topic_pic = '';
		while(list(,$akt_ppic) = each($ppics_data)) {
			if($akt_ppic['smiley_id'] == $akt_topic_data['topic_pic']) {
				$akt_topic_pic = '<img src="'.$akt_ppic['smiley_gfx'].'" alt="" />';
				break;
			}
		}
		reset($ppics_data);

		$akt_topic_data['topic_replies'] = number_format($akt_topic_data['topic_replies'],0,',','.');
		$akt_topic_data['topic_views'] = number_format($akt_topic_data['topic_views'],0,',','.');
		$akt_topic_data['topic_title'] = myhtmlentities($akt_topic_data['topic_title']);

		$viewforum_tpl->blocks['topicrow']->parse_code(FALSE,TRUE);
	}
}


include_once('pheader.php');
show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".myhtmlentities($forum_data['forum_name']),'',"<a href=\"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;mark=all&amp;$MYSID\">".$lng['Mark_topics_read'].'</a>');

$viewforum_tpl->parse_code(TRUE);

include_once('ptail.php');

?>