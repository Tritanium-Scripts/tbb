<?php
/**
*
* Tritanium Bulletin Board 2 - forumindex.php
* Erstellt eine Übersicht der Kategorien und Foren
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
*
**/

$title_add = ' &#187; Foren&uuml;bersicht';

$cats_data = get_cats_data();
$forums_data = get_forums_data();


$c_forums = array();
if(isset($_GET['mark']))
	setcookie('c_forums_all',time(),time()+31536000,'/');

$c_forums_temp = isset($_COOKIE['c_forums']) ? explode('x',$_COOKIE['c_forums']) : array();
while(list(,$akt_value) = each($c_forums_temp)) {
	$akt_value = explode('_',$akt_value);
	$c_forums[$akt_value[0]] = $akt_value[1];
}

$open_cats = array();

if(!isset($_SESSION['s_open_cats'])) {
	for($i = 0; $i < sizeof($cats_data); $i++) {
		if($cats_data[$i]['parent_id'] == 0) $open_cats[] = $cats_data[$i]['cat_id'];
	}
	$_SESSION['s_open_cats'] = implode(',',$open_cats);
}

$open_cats = explode(',',$_SESSION['s_open_cats']);

if(isset($_GET['open_cat'])) {
	if(in_array($_GET['open_cat'],$open_cats) == FALSE) {
		$open_cats[] = $_GET['open_cat'];
	}
	$_SESSION['s_open_cats'] = implode(',',$open_cats);
}

if(isset($_GET['close_cat'])) {
	if(in_array($_GET['close_cat'],$open_cats) == TRUE) {
		while(list($akt_key,$akt_cat) = each($open_cats)) {
			if($akt_cat == $_GET['close_cat']) {
				unset($open_cats[$akt_key]); break;
			}
		}
	}
	$_SESSION['s_open_cats'] = implode(',',$open_cats);
}

function show_sub_cats($parent_id,$depth) {
	global $findex_tpl,$cats_data,$forums_data,$open_cats,$MYSID,$lng,$template_path,$tpl_config,$c_forums;

	$appendix = '';

	for($i = 0; $i < $depth; $i++) {
		$appendix .= '<img src="'.$template_path.'/'.$tpl_config['img_blank'].'" border="0" alt="" />';
	}

	for($j = 0; $j < sizeof($cats_data); $j++) {

		if($cats_data[$j]['parent_id'] == $parent_id) {

			$findex_tpl->blocks['catrow']->blocks['forumrow']->reset_tpl();

			$x = FALSE;

			if(in_array($cats_data[$j]['cat_id'],$open_cats) == TRUE) {
				for($i = 0; $i < sizeof($forums_data); $i++) {
					if($forums_data[$i]['cat_id'] == $cats_data[$j]['cat_id']) {
						$x = TRUE;
						if($forums_data[$i]['forum_last_post_post_id'] != 0) {
							$last_post_pic = '';
							if(strlen($forums_data[$i]['forum_last_post_title']) > 22) $last_post_link = '<a href="index.php?faction=viewtopic&amp;topic_id='.$forums_data[$i]['forum_last_post_topic_id'].'&amp;z=last&amp;'.$MYSID.'#post'.$forums_data[$i]['forum_last_post_post_id'].'" title="'.myhtmlentities($forums_data[$i]['forum_last_post_title']).'">'.myhtmlentities(substr($forums_data[$i]['forum_last_post_title'],0,22)).'...</a>';
							else $last_post_link = '<a href="index.php?faction=viewtopic&amp;topic_id='.$forums_data[$i]['forum_last_post_topic_id'].'&amp;z=last&amp;'.$MYSID.'#post'.$forums_data[$i]['forum_last_post_post_id'].'">'.myhtmlentities($forums_data[$i]['forum_last_post_title']).'</a>';

							$last_post_text = $last_post_link.' ('.$lng['by'].' '.$forums_data[$i]['forum_last_post_poster_nick'].')<br />'.format_date($forums_data[$i]['forum_last_post_time']);
						}
						else {
							$last_post_pic = '';
							$last_post_text = $lng['No_last_post'];
						}

						$new_post_status = ($forums_data[$i]['forum_last_post_post_id'] != 0 && isset($c_forums[$forums_data[$i]['forum_id']]) == TRUE && $c_forums[$forums_data[$i]['forum_id']] < $forums_data[$i]['forum_last_post_time']) ? $template_path.'/'.$tpl_config['img_forum_on'] : $template_path.'/'.$tpl_config['img_forum_off'];

						$findex_tpl->blocks['catrow']->blocks['forumrow']->values = array(
							'APPENDIX'=>$appendix,
							'MYSID'=>$MYSID,
							'NEW_POST_STATUS'=>'<img src="'.$new_post_status.'" border="0" alt="" />',
							'FORUM_ID'=>$forums_data[$i]['forum_id'],
							'FORUM_NAME'=>myhtmlentities($forums_data[$i]['forum_name']),
							'FORUM_DESCRIPTION'=>myhtmlentities($forums_data[$i]['forum_description']),
							'FORUM_TOPICS_COUNTER'=>$forums_data[$i]['forum_topics_counter'],
							'FORUM_POSTS_COUNTER'=>$forums_data[$i]['forum_posts_counter'],
							'FORUM_MODS'=>'',
							'LAST_POST_PIC'=>$last_post_pic,
							'LAST_POST_TEXT'=>$last_post_text
						);
						$findex_tpl->blocks['catrow']->blocks['forumrow']->parse_code(FALSE,TRUE);
					}
				}
				if($x == FALSE) $findex_tpl->blocks['catrow']->blocks['forumrow']->blank_tpl();

				$findex_tpl->blocks['catrow']->values = array(
					'APPENDIX'=>$appendix,
					'CAT_NAME'=>htmlspecialchars($cats_data[$j]['cat_name']),
					'PLUS_MINUS_PIC'=>'<a href="index.php?faction=forumindex&amp;close_cat='.$cats_data[$j]['cat_id'].'&amp;'.$MYSID.'"><img src="'.$template_path.'/'.$tpl_config['img_minus'].'" border="0" alt="" /></a>'
				);
				$findex_tpl->blocks['catrow']->parse_code(FALSE,TRUE);

				show_sub_cats($cats_data[$j]['cat_id'],$depth+1);
			}
			else {
				$findex_tpl->blocks['catrow']->blocks['forumrow']->blank_tpl();
				$findex_tpl->blocks['catrow']->values = array(
					'APPENDIX'=>$appendix,
					'CAT_NAME'=>myhtmlentities($cats_data[$j]['cat_name']),
					'PLUS_MINUS_PIC'=>'<a href="index.php?faction=forumindex&amp;open_cat='.$cats_data[$j]['cat_id'].'&amp;'.$MYSID.'"><img src="'.$template_path.'/'.$tpl_config['img_plus'].'" border="0" alt="" /></a>'
				);
				$findex_tpl->blocks['catrow']->parse_code(FALSE,TRUE);
			}
		}
	}
}

include_once('pheader.php');

if(sizeof($cats_data) == 0) {
	show_message('No_forums_cats','message_no_forums');
}
else {
	$findex_tpl = new template;
	$findex_tpl->load($template_path.'/'.$tpl_config['tpl_forumindex']);

	show_sub_cats(0,0);

	if($CONFIG['enable_wio'] == 1 && $CONFIG['show_wio_forumindex'] == 1) {
		$guests_counter = $members_counter = 0;
		$members = array();
		$guests = '';

		$wio_data = get_wio_data();
		while(list(,$akt_wio) = each($wio_data)) {
			if($akt_wio['wio_user_id'] == 0) $guests_counter++;
			else {
				$members_counter++;
				$members[] = '<a href="index.php?faction=viewprofile&amp;profile_id='.$akt_wio['wio_user_id'].'">'.$akt_wio['wio_user_nick'].'</a>';
			}
		}

		$members = implode(', ',$members);

		if($members_counter == 0) $members = $lng['No_members'];
		elseif($members_counter == 1) $members = $lng['One_member'].$members;
		else $members = sprintf($lng['x_members'],$members_counter).$members;

		if($guests_counter == 0) $guests = $lng['No_guests'];
		elseif($guests_counter == 1) $guests = $lng['One_guest'];
		else $guests = sprintf($lng['x_guests'],$guests_counter);

		$findex_tpl->blocks['wiobox']->values = array(
			'LNG_WHO_IS_ONLINE'=>$lng['Who_is_online'],
			'GUESTS_COUNTER'=>$guests,
			'MEMBERS_COUNTER'=>$members
		);
		$findex_tpl->blocks['wiobox']->parse_code();
	}
	else $findex_tpl->unset_block('wiobox');

	$findex_tpl->values = array(
		'LNG_TOPICS'=>$lng['Topics'],
		'LNG_POSTS'=>$lng['Posts'],
		'LNG_FORUM'=>$lng['Forum'],
		'LNG_LAST_POST'=>$lng['Last_post'],
		'LNG_MODERATORS'=>$lng['Moderators'],
		'TEMPLATE_PATH'=>$template_path
	);

	show_navbar($CONFIG['board_name'],'',"<a href=\"index.php?faction=forumindex&amp;mark=all%amp;$MYSID\">".$lng['Mark_forums_read'].'</a>');

	$findex_tpl->parse_code(TRUE);
}

include_once('ptail.php');


?>