<?php
/**
*
* Tritanium Bulletin Board 2 - edittopic.php
* Bearbeitet ein Thema
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
*
**/

require_once('auth.php');

$topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : 0;
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'edit';

if($user_logged_in != 1) die('Nit eingeloggt!');
elseif(!$topic_data = get_topic_data($topic_id)) die('Kann Themendaten nicht laden!');
elseif(!$forum_data = get_forum_data($topic_data['forum_id'])) die('Kann Forendaten nicht laden!');

$forum_id = $forum_data['forum_id'];

if($mode == 'edit') {
	if($user_id != $topic_data['topic_poster_id'] || $user_data['user_is_admin'] != 1) die('Kein Zugriff!');

	$error = '';

	$p_title = (isset($_POST['p_title']) ? $_POST['p_title'] : $topic_data['topic_title']);

	if(isset($_GET['doit'])) {
		if(trim($p_title) == '') $error = $lng['error_no_title'];
		else {
			update_topic_data($topic_id,array(
				'topic_title'=>array('STR',$p_title)
			));
			header("Location: index.php?faction=viewtopic&topic_id=$topic_id&$MYSID"); exit;
		}
	}

	$edittopic_tpl = new template;
	$edittopic_tpl->load($template_path.'/'.$tpl_config['tpl_edittopic_edit']);

	if($error != '') {
		$edittopic_tpl->blocks['errorrow']->values = array(
			'ERROR'=>$error
		);
		$edittopic_tpl->blocks['errorrow']->parse_code();
	}
	else $edittopic_tpl->unset_block('errorrow');

	$edittopic_tpl->values = array(
		'MYSID'=>$MYSID,
		'LNG_TITLE'=>$lng['Title'],
		'LNG_EDIT_TOPIC'=>$lng['Edit_topic'],
		'LNG_RESET'=>$lng['Reset'],
		'P_TITLE'=>mutate($p_title),
		'TOPIC_ID'=>$topic_id
	);

	$title_add .= ' &#187; '.$forum_data['forum_name'].' &#187; '.$topic_data['topic_title'].' &#187; '.$lng['Edit_topic'];

	include_once('pheader.php');

	show_navbar("<a href=\"index.php?$MYSID\">".$CONFIG['board_name']."</a>\r<a href=\"index.php?faction=viewforum&amp;forum_id=$forum_id&amp;$MYSID\">".$forum_data['forum_name']."</a>\r<a href=\"index.php?faction=viewtopic&amp;topic_id=$topic_id&amp;$MYSID\">".$topic_data['topic_title']."</a>\r".$lng['Edit_topic']);

	$edittopic_tpl->parse_code(TRUE);

	include_once('ptail.php');
}
else {
	if($user_data['user_is_admin'] != 1) die('Kein Zugriff!');
	switch(@$_GET['mode']) {
		case 'pinn':
			$new_pinned_status = ($topic_data['topic_is_pinned'] == 1) ? 0 : 1;
			update_topic_data($topic_id,array(
				'topic_is_pinned'=>array('STR',$new_pinned_status)
			));

			header("Location: index.php?faction=viewtopic&topic_id=$topic_id&$MYSID"); exit;
		break;

		case 'delete':
			delete_topic_data($topic_id);
			if($topic_id == $forum_data['forum_last_post_topic_id']) update_forum_last_post($forum_id);
			header("Location: index.php?faction=viewforum&forum_id=$forum_id&$MYSID"); exit;
		break;
	}
}
?>