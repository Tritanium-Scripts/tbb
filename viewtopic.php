<?php
/**
*
* Tritanium Bulletin Board 2 - viewtopic.php
* Zeigt ein Thema an
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
*
**/

$topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : 0;
$z = isset($_GET['z']) ? $_GET['z'] : 1;

require_once('auth.php');

if(!$topic_data = get_topic_data($topic_id)) die('Kann Themendaten nicht laden/Thema existiert nicht!');
elseif(!$forum_data = get_forum_data($topic_data['forum_id'])) die('Kann Forendaten nicht laden/Forum existiert nicht!');

$forum_id = $topic_data['forum_id'];

if($user_logged_in != 1) {
	if($forum_data['auth_guests_view_forum'] != 1) {
		include_once('pheader.php');
		show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['Not_logged_in']);
		show_message('Not_logged_in','message_forum_not_logged_in','<br />'.$lng['click_here_login'].'<br />'.$lng['click_here_register']);
		include_once('ptail.php'); exit;
	}
}
elseif($user_data['user_is_admin'] != 1) {
	if($forum_data['auth_members_view_forum'] != 1) {
		include_once('pheader.php');
		show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r".$lng['No_access']);
		show_message('No_access','message_forum_no_access');
		include_once('ptail.php'); exit;
	}
}

update_topic_cookie($forum_id,$topic_id,time());

if(!isset($_SESSION['topic_views'][$topic_id])) {
	update_topic_data($topic_id,array(
		'topic_views'=>array('INT',1)
	));
	$_SESSION['topic_views'][$topic_id] = TRUE;
}

$posts_counter = get_posts_counter($topic_id);

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

while(list(,$akt_post) = each($posts_data)) {
	$smilies = array(';)'=>'<img src="images/smilies/smile.gif" border="0" alt="" />');

	$strtr_array = array();

	if($akt_post['post_enable_smilies'] == 1 && $forum_data['forum_enable_smilies'] == 1) $strtr_array = array_merge($strtr_array,$smilies);
	if($akt_post['post_enable_html'] != 1 || $forum_data['forum_enable_htmlcode'] != 1) $strtr_array = array_merge($strtr_array,$html_schars_table);

	$post_tools = array();
	$post_tools[] = "<a href=\"index.php?faction=postreply&amp;topic_id=$topic_id&amp;quote=".$akt_post['post_id']."&amp;$MYSID\">".$lng['Quote_post'].'</a>';
	if($user_logged_in == 1) {
		if($akt_post['poster_id'] == $user_id || $user_data['user_is_admin'] == 1) {
			$post_tools[] = "<a href=\"index.php?faction=editpost&amp;mode=edit&amp;topic_id=$topic_id&amp;post_id=".$akt_post['post_id']."&amp;$MYSID\">".$lng['Edit_post'].'</a>';
			if($topic_data['topic_first_post_id'] != $akt_post['post_id']) $post_tools[] = "<a href=\"index.php?faction=deletepost&amp;mode=edit&amp;topic_id=$topic_id&amp;post_id=".$akt_post['post_id']."&amp;$MYSID\">".$lng['Delete_post'].'</a>';
		}
	}

	$post_tools = implode(' | ',$post_tools);

	$viewtopic_tpl->blocks['postrow']->values = array(
		'AKT_CLASS'=>$tpl_config['akt_class'],
		'POSTER_NICK'=>$akt_post['poster_nick'],
		'POSTER_STATUS'=>'',
		'POSTER_GROUP_NAME'=>'',
		'POSTER_RANK_PIC'=>'',
		'POSTER_ID'=>$akt_post['poster_id'],
		'POSTER_AVATAR'=>'',
		'POSTER_ICQ'=>'',
		'POST_ID'=>$akt_post['post_id'],
		'POST_PIC'=>'',
		'POST_DATE'=>format_date($akt_post['post_time']),
		'TOPIC_ID'=>$topic_id,
		'FORUM_ID'=>$forum_id,
		'MYSID'=>$MYSID,
		'SEND_PM'=>'',
		'POST_TOOLS'=>$post_tools,
		'SEND_EMAIL'=>'',
		'POSTER_HP'=>'',
		'POST'=>nlbr(strtr($akt_post['post_text'],$strtr_array)),
		'POSTER_SIGNATUR'=>'',
		'POSTER_POSTS'=>'',
		'POSTER_REGDATE'=>'',
		'POSTER_IP'=>'',
		'POST_TITLE'=>myhtmlentities($akt_post['post_title']),
		'CONFIG_TEMPLATE_PATH'=>$template_path,
		'LNG_POSTED'=>$lng['Posted']
	);
	$viewtopic_tpl->blocks['postrow']->parse_code(FALSE,TRUE);
	$tpl_config['akt_class'] = ($tpl_config['akt_class'] == $tpl_config['td1_class']) ? $tpl_config['td2_class'] : $tpl_config['td1_class'];
}

$viewtopic_tpl->values = array(
	'MYSID'=>$MYSID,
	'TOPIC_ID'=>$topic_id,
	'FORUM_ID'=>$forum_id,
	'LNG_POST_NEW_REPLY'=>$lng['Post_new_reply'],
	'LNG_POST_NEW_TOPIC'=>$lng['Post_new_topic'],
	'LNG_AUTHOR'=>$lng['Author'],
	'LNG_TOPIC'=>$lng['Topic'],
	'TOPIC_TITLE'=>$topic_data['topic_title'],
	'PAGE_LISTING'=>$page_listing
);

$title_add .= ' &#187; '.$forum_data['forum_name'].' &#187; '.$topic_data['topic_title'];

include_once('pheader.php');
show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID\">".myhtmlentities($forum_data['forum_name'])."</a>\r".myhtmlentities($topic_data['topic_title']));

$viewtopic_tpl->parse_code(TRUE);

include_once('ptail.php');

?>