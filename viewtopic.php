<?php
/**
*
* Tritanium Bulletin Board 2 - viewtopic.php
* version #2004-01-01-18-38-43
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

$topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : 0;
$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : 0;
$z = isset($_GET['z']) ? $_GET['z'] : 1;

require_once('auth.php');
require_once('bbcode.php');

if($topic_id == 0) {
	$db->query("SELECT topic_id FROM ".TBLPFX."posts WHERE post_id='$post_id'"); // Prueft, ob der Beitrag vorhanden ist und bestimmt die Themen-ID
	if($db->affected_rows != 1) die('Kann Beitragsdaten nicht laden/Beitrag existiert nicht!');
	list($topic_id) = $db->fetch_array();

	$db->query("SELECT post_id FROM ".TBLPFX."posts WHERE topic_id='$topic_id' ORDER BY post_time"); // Liest alle Post-IDs dieses Themas
	$post_ids = $db->raw2array();
	$post_ids_counter = sizeof($post_ids);

	$z = 1;
	for($i = 0; $i < $post_ids_counter; $i++) {
		if($post_ids[$i]['post_id'] == $post_id) break;
		if(($i + 1) % $CONFIG['posts_per_page'] == 0) $z++;
	}
}

if(!$topic_data = get_topic_data($topic_id)) die('Kann Themendaten nicht laden/Thema existiert nicht!');
elseif(!$forum_data = get_forum_data($topic_data['forum_id'])) die('Kann Forendaten nicht laden/Forum existiert nicht!');

$forum_id = $topic_data['forum_id'];


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
	if(!$auth_data = get_auth_forum_user($forum_id,$user_id,array('auth_view_forum','auth_edit_posts','auth_is_mod'))) {
		$auth_data = array(
			'auth_view_forum'=>$forum_data['auth_members_view_forum'],
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


update_topic_cookie($forum_id,$topic_id,time());

if(!isset($_SESSION['topic_views'][$topic_id])) {
	$db->query("UPDATE ".TBLPFX."topics SET topic_views_counter=topic_views_counter+1 WHERE topic_id='$topic_id'");
	$_SESSION['topic_views'][$topic_id] = TRUE;
}

$posts_counter = get_topic_posts_counter($topic_id);
//$posts_counter = $topic_data['topic_replies']+1;

$page_counter = ceil($posts_counter/$CONFIG['posts_per_page']);

if($z == 'last') $z = $page_counter;

$start = $z*$CONFIG['posts_per_page']-$CONFIG['posts_per_page'];
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
	if($page_listing[$i] != $z) $page_listing[$i] = "<a href=\"index.php?faction=viewtopic&amp;topic_id=$topic_id&amp;z=".$page_listing[$i]."&amp;$MYSID\">".$page_listing[$i].'</a>';
}


if($z > 1) $pre = '<a href="index.php?faction=viewtopic&amp;topic_id='.$topic_id.'&amp;z=1&amp;'.$MYSID.'">&#171;</a>&nbsp;<a href="index.php?faction=viewtopic&amp;topic_id='.$topic_id.'&amp;z='.($z-1).'&amp;'.$MYSID.'">&#8249;</a>&nbsp;&nbsp;';
if($z < $page_counter) $suf = '&nbsp;&nbsp;<a href="index.php?faction=viewtopic&amp;topic_id='.$topic_id.'&z='.($z+1).'&'.$MYSID.'">&#8250;</a>&nbsp;<a href="index.php?faction=viewtopic&amp;topic_id='.$topic_id.'&amp;z=last&amp;'.$MYSID.'">&#187;</a>';

$page_listing = sprintf($lng['Pages'],$pre.implode(' | ',$page_listing).$suf);

$modtools = array();

if($user_id == $topic_data['poster_id'] || $user_data['user_is_admin'] == 1) $modtools[] = "<a href=\"index.php?faction=edittopic&amp;mode=edit&amp;topic_id=$topic_id&amp;$MYSID\">".$lng['Edit_topic'].'</a>';
if($user_data['user_is_admin'] == 1) {
	$modtools[] = "<a href=\"index.php?faction=edittopic&amp;mode=move&amp;topic_id=$topic_id&amp;$MYSID\">".$lng['Move_topic'].'</a>';
	$modtools[] = "<a href=\"index.php?faction=edittopic&amp;mode=delete&amp;topic_id=$topic_id&amp;$MYSID\">".$lng['Delete_topic'].'</a>';

	$temp = ($topic_data['topic_is_pinned'] == 1) ? $lng['Mark_topic_unimportant'] : $lng['Mark_topic_important'];
	$modtools[] = "<a href=\"index.php?faction=edittopic&amp;mode=pinn&amp;topic_id=$topic_id&amp;$MYSID\">".$temp.'</a>';
}

$viewtopic_tpl = new template;
$viewtopic_tpl->load($template_path.'/'.$tpl_config['tpl_viewtopic']);


//
// Die Umfrage
//
$poll_box = '';
if($topic_data['topic_poll'] == 1) {
	$db->query("SELECT * FROM ".TBLPFX."polls WHERE topic_id='$topic_id'");
	if($poll_data = $db->fetch_array()) {
		$poll_tpl = new template;

		if($user_logged_in == 1) {
			$db->query("SELECT voter_id FROM ".TBLPFX."polls_voters WHERE poll_id='".$poll_data['poll_id']."' AND voter_id='$user_id'");
			if($db->affected_rows == 0)
				$poll_tpl->load($template_path.'/'.$tpl_config['tpl_viewtopic_poll_voting']);
			else {
				$poll_tpl->load($template_path.'/'.$tpl_config['tpl_viewtopic_poll_results']);
				$info_text = $lng['poll_already_voted_info'];
			}
		}
		else {
			$poll_tpl->load($template_path.'/'.$tpl_config['tpl_viewtopic_poll_results']);
			$info_text = $lng['poll_not_logged_in_info'];
		}

		$db->query("SELECT option_id,option_title,option_votes FROM ".TBLPFX."polls_options WHERE poll_id='".$poll_data['poll_id']."' ORDER BY option_id");
		while($akt_option = $db->fetch_array()) {
			$akt_fraction = ($poll_data['poll_votes'] == 0) ? 0 : round($akt_option['option_votes']/$poll_data['poll_votes'],2);
			$akt_percent = $akt_fraction*100;
			$akt_votes = ($akt_option['option_votes'] == 1) ? $lng['one_vote'] : sprintf($lng['x_votes'],$akt_option['option_votes']);
			$akt_checked = ($akt_option['option_id'] == 1) ? ' checked="checked"' : '';

			$poll_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);
		}

		$poll_box = $poll_tpl->parse_code();
		unset($poll_tpl);
	}
}

if(sizeof($modtools) > 0) {
	$modtools = implode($tpl_config['separation_char'],$modtools);
	$viewtopic_tpl->blocks['modtools']->parse_code();
}
else $viewtopic_tpl->unset_block('modtools');

$smilies = array();
$db->query("SELECT * FROM ".TBLPFX."smilies WHERE smiley_type='0'");
while($akt_smiley = $db->fetch_array()) {
	$smilies[$akt_smiley['smiley_synonym']] = '<img src="'.$akt_smiley['smiley_gfx'].'" border="0" alt="'.$akt_smiley['smiley_synonym'].'" />';
}

$db->query("SELECT * FROM ".TBLPFX."smilies WHERE smiley_type='1'");
$ppics_data = $db->raw2array();

$db->query("SELECT t1.*, UNIX_TIMESTAMP(t1.post_time) AS post_time, t3.*, t2.user_hp AS poster_hp, t2.user_email AS poster_email, t2.user_nick AS poster_nick FROM ".TBLPFX."posts AS t1, ".TBLPFX."posts_text AS t3 LEFT JOIN ".TBLPFX."users AS t2 ON t1.poster_id=t2.user_id WHERE t1.topic_id='$topic_id' AND t3.post_id=t1.post_id ORDER BY t1.post_time LIMIT $start,".$CONFIG['posts_per_page']);
while($akt_post = $db->fetch_array()) {

	//$strtr_array = $html_trans_table;
	$strtr_array  = array();

	if($akt_post['post_enable_smilies'] == 1 && $forum_data['forum_enable_smilies'] == 1) $strtr_array = array_merge($strtr_array,$smilies);
	if($akt_post['post_enable_html'] != 1 || $forum_data['forum_enable_htmlcode'] != 1) $akt_post['post_text'] = myhtmlentities($akt_post['post_text']); //array_merge($strtr_array,$html_schars_table);

	$edit_button = $delete_button = $user_email_button = $user_hp_button = '';

	if($user_logged_in == 1) {
		if($user_data['user_is_admin'] == 1 || $auth_data['auth_is_mod'] == 1 || (($forum_data['auth_members_edit_posts'] == 1 && $auth_data['auth_edit_posts'] == 1 || $forum_data['auth_members_edit_posts'] != 1 && $auth_data['auth_edit_posts'] == 1) && $user_id == $akt_post['poster_id'])) {
			$edit_button = "<a href=\"index.php?faction=editpost&amp;post_id=".$akt_post['post_id']."&amp;mode=edit&amp;$MYSID\"><img src=\"$template_path/".$tpl_config['img_edit_post']."\" alt=\"".$lng['Edit_post']."\" border=\"0\" /></a>";
			if($akt_post['post_id'] != $topic_data['topic_first_post_id'])
				$delete_button = "<a href=\"index.php?faction=editpost&amp;post_id=".$akt_post['post_id']."&amp;mode=delete&amp;$MYSID\"><img src=\"$template_path/".$tpl_config['img_delete_post']."\" alt=\"".$lng['Delete_post']."\" border=\"0\" /></a>";
		}
	}

	$quote_button = "<a href=\"index.php?faction=postreply&amp;topic_id=$topic_id&amp;quote=".$akt_post['post_id']."&amp;$MYSID\"><img src=\"$template_path/".$tpl_config['img_quote_post']."\" alt=\"".$lng['Quote_post']."\" border=\"0\" /></a>";

	if($akt_post['poster_hp'] != '') $user_hp_button = '<a target="_blank" href="'.$akt_post['poster_hp'].'"><img src="'.$template_path.'/'.$tpl_config['img_user_hp'].'" alt="'.$akt_post['poster_hp'].'" border="0" /></a>';
	if($akt_post['poster_email'] != '') $user_email_button = '<a href="mailto:'.$akt_post['poster_email'].'"><img src="'.$template_path.'/'.$tpl_config['img_user_email'].'" alt="'.$akt_post['poster_email'].'" border="0" /></a>';

	$akt_post_pic = '';
	if($akt_post['post_pic'] != 0) {
		while(list(,$akt_ppic) = each($ppics_data)) {
			if($akt_ppic['smiley_id'] == $akt_post['post_pic']) {
				$akt_post_pic = '<img src="'.$akt_ppic['smiley_gfx'].'" alt="" />';
				break;
			}
		}
		reset($ppics_data);
	}

	$post_tools = array();
	$post_tools[] = "<a href=\"index.php?faction=postreply&amp;topic_id=$topic_id&amp;quote=".$akt_post['post_id']."&amp;$MYSID\">".$lng['Quote_post'].'</a>';
	if($user_logged_in == 1) {
		if($akt_post['poster_id'] == $user_id || $user_data['user_is_admin'] == 1) {
			$post_tools[] = "<a href=\"index.php?faction=editpost&amp;mode=edit&amp;topic_id=$topic_id&amp;post_id=".$akt_post['post_id']."&amp;$MYSID\">".$lng['Edit_post'].'</a>';
			if($topic_data['topic_first_post_id'] != $akt_post['post_id']) $post_tools[] = "<a href=\"index.php?faction=deletepost&amp;mode=edit&amp;topic_id=$topic_id&amp;post_id=".$akt_post['post_id']."&amp;$MYSID\">".$lng['Delete_post'].'</a>';
		}
	}

	$post_tools = implode(' | ',$post_tools);

	$akt_post_date = format_date($akt_post['post_time']);
	$akt_post['post_text'] = nlbr(strtr($akt_post['post_text'],$strtr_array));
	if($akt_post['post_enable_bbcode'] == 1 && $forum_data['forum_enable_bbcode'] == 1) $akt_post['post_text'] = bbcode($akt_post['post_text']);

	$viewtopic_tpl->blocks['postrow']->parse_code(FALSE,TRUE);
	$tpl_config['akt_class'] = ($tpl_config['akt_class'] == $tpl_config['td1_class']) ? $tpl_config['td2_class'] : $tpl_config['td1_class'];
}

$title_add[] = $forum_data['forum_name'];
$title_add[] = $topic_data['topic_title'];

include_once('pheader.php');
show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID\">".myhtmlentities($forum_data['forum_name'])."</a>\r".myhtmlentities($topic_data['topic_title']));

$viewtopic_tpl->parse_code(TRUE);

include_once('ptail.php');

?>