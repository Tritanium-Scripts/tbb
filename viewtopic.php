<?php
/**
*
* Tritanium Bulletin Board 2 - viewtopic.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

$topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : 0;
$z = isset($_GET['z']) ? $_GET['z'] : 1;

require_once('auth.php');

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


update_topic_cookie($forum_id,$topic_id,time());

if(!isset($_SESSION['topic_views'][$topic_id])) {
	update_topic_data($topic_id,array(
		'topic_views'=>array('INT',1)
	));
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

$posts_data = get_posts_data($topic_id,$start,$CONFIG['posts_per_page']);

$modtools = array();

if($user_id == $topic_data['topic_poster_id'] || $user_data['user_is_admin'] == 1) $modtools[] = "<a href=\"index.php?faction=edittopic&amp;mode=edit&amp;topic_id=$topic_id&amp;$MYSID\">".$lng['Edit_topic'].'</a>';
if($user_data['user_is_admin'] == 1) {
	$modtools[] = "<a href=\"index.php?faction=edittopic&amp;mode=move&amp;topic_id=$topic_id&amp;$MYSID\">".$lng['Move_topic'].'</a>';
	$modtools[] = "<a href=\"index.php?faction=edittopic&amp;mode=delete&amp;topic_id=$topic_id&amp;$MYSID\">".$lng['Delete_topic'].'</a>';

	$temp = ($topic_data['topic_is_pinned'] == 1) ? $lng['Mark_topic_unimportant'] : $lng['Mark_topic_important'];
	$modtools[] = "<a href=\"index.php?faction=edittopic&amp;mode=pinn&amp;topic_id=$topic_id&amp;$MYSID\">".$temp.'</a>';
}

$viewtopic_tpl = new template;
$viewtopic_tpl->load($template_path.'/'.$tpl_config['tpl_viewtopic']);

if(sizeof($modtools) > 0) {
	$viewtopic_tpl->blocks['modtools']->values = array(
		'MODTOOLS'=>implode(' | ',$modtools)
	);
	$viewtopic_tpl->blocks['modtools']->parse_code();
}
else $viewtopic_tpl->unset_block('modtools');

$smilies = array();
$smilies_data = get_smilies_data(array('smiley_type'=>0));
while(list($akt_key) = each($smilies_data)) {
	$smilies[$smilies_data[$akt_key]['smiley_synonym']] = '<img src="'.$smilies_data[$akt_key]['smiley_gfx'].'" border="0" alt="'.$smilies_data[$akt_key]['smiley_synonym'].'" />';
}

$ppics_data = get_smilies_data(array('smiley_type'=>1));

while(list(,$akt_post) = each($posts_data)) {

	$strtr_array = $html_trans_table;

	if($akt_post['post_enable_smilies'] == 1 && $forum_data['forum_enable_smilies'] == 1) $strtr_array = array_merge($strtr_array,$smilies);
	if($akt_post['post_enable_html'] != 1 || $forum_data['forum_enable_htmlcode'] != 1) $strtr_array = array_merge($strtr_array,$html_schars_table);

	$edit_button = $delete_button = $user_email_button = $user_hp_button = '';

	if($user_logged_in == 1) {
		if($user_data['user_is_admin'] == 1 || $auth_data['auth_is_mod'] == 1 || (($forum_data['auth_members_edit_posts'] == 1 && $auth_data['auth_edit_posts'] == 1 || $forum_data['auth_members_edit_posts'] != 1 && $auth_data['auth_edit_posts'] == 1) && $user_id == $akt_post['poster_id'])) {
			$edit_button = "<a href=\"index.php?faction=editpost&amp;topic_id=$topic_id&amp;post_id=".$akt_post['post_id']."&amp;mode=edit&amp;return_to=$z&amp;$MYSID\"><img src=\"$template_path/".$tpl_config['img_edit_post']."\" alt=\"".$lng['Edit_post']."\" border=\"0\" /></a>";
			if($akt_post['post_id'] != $topic_data['topic_first_post_id'])
				$delete_button = "<a href=\"index.php?faction=editpost&amp;topic_id=$topic_id&amp;post_id=".$akt_post['post_id']."&amp;mode=delete&amp;$MYSID\"><img src=\"$template_path/".$tpl_config['img_delete_post']."\" alt=\"".$lng['Delete_post']."\" border=\"0\" /></a>";
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

	$viewtopic_tpl->blocks['postrow']->parse_code(FALSE,TRUE);
	$tpl_config['akt_class'] = ($tpl_config['akt_class'] == $tpl_config['td1_class']) ? $tpl_config['td2_class'] : $tpl_config['td1_class'];
}

$title_add .= ' &#187; '.$forum_data['forum_name'].' &#187; '.$topic_data['topic_title'];

include_once('pheader.php');
show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID\">".myhtmlentities($forum_data['forum_name'])."</a>\r".myhtmlentities($topic_data['topic_title']));

$viewtopic_tpl->parse_code(TRUE);

include_once('ptail.php');

?>