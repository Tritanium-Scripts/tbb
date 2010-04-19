<?php
/**
*
* Tritanium Bulletin Board 2 - ad_forums.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$error = '';

switch(@$_GET['mode']) {
	default:
		$forums_data = get_forums_data();
		$cats_data = get_cats_data();

		$forums_counter = sizeof($forums_data);
		$cats_counter = sizeof($cats_data);

		$adforums_tpl = new template;
		$adforums_tpl->load($template_path.'/'.$tpl_config['tpl_ad_forums_default']);

		function build_sub_cats($parent_id = 0,$appendix = '') {
			global $forums_data,$cats_data,$forums_counter,$cats_counter,$adforums_tpl,$tpl_config,$lng,$MYSID;

			$akt_cats_counter = 0;
			$y = 1;

			for($i = 0; $i < $cats_counter; $i++) {
				if($cats_data[$i]['parent_id'] == $parent_id) $akt_cats_counter++;
			};

			for($i = 0; $i < $cats_counter; $i++) {

				if($parent_id == $cats_data[$i]['parent_id']) {

					$adforums_tpl->blocks['catrow']->blocks['forumrow']->reset_tpl();
					$akt_forums_counter = 0;
					$x = 1;

					for($j = 0; $j < $forums_counter; $j++) {
						if($forums_data[$j]['cat_id'] == $cats_data[$i]['cat_id']) $akt_forums_counter++;
					}

					for($j = 0; $j < $forums_counter; $j++) {
						if($forums_data[$j]['cat_id'] == $cats_data[$i]['cat_id']) {
							if($x == 1) $up = $lng['moveup'];
							else $up = "<a href=\"administration.php?faction=ad_forums&amp;mode=moveforumup&amp;forum_id_1=".$forums_data[$j]['forum_id']."&amp;forum_id_2=".$forums_data[$j-1]['forum_id']."&amp;$MYSID\">".$lng['moveup']."</a>";

							if($x == $akt_forums_counter) $down = $lng['movedown'];
							else $down = "<a href=\"administration.php?faction=ad_forums&amp;mode=moveforumdown&amp;forum_id_1=".$forums_data[$j]['forum_id']."&amp;forum_id_2=".$forums_data[$j+1]['forum_id']."&amp;$MYSID\">".$lng['movedown']."</a>";

							$adforums_tpl->blocks['catrow']->blocks['forumrow']->values = array(
								'appendix'=>$appendix,
								'akt_forum'=>$forums_data[$j],
								'up'=>$up,
								'down'=>$down,
							);
							$adforums_tpl->blocks['catrow']->blocks['forumrow']->parse_code(FALSE,TRUE);
							$tpl_config['akt_class'] = ($tpl_config['akt_class'] == $tpl_config['td1_class']) ? $tpl_config['td2_class'] : $tpl_config['td1_class'];
							$x++;
						}
					}

					if($y == 1) $up = $lng['moveup'];
					else $up = "<a href=\"administration.php?faction=ad_forums&amp;mode=movecatup&amp;cat_id_1=".$cats_data[$i]['cat_id']."&amp;cat_id_2=".$cats_data[$i-1]['cat_id']."&amp;$MYSID\">".$lng['moveup']."</a>";

					if($y == $akt_cats_counter) $down = $lng['movedown'];
					else $down = "<a href=\"administration.php?faction=ad_forums&amp;mode=movecatdown&amp;cat_id_1=".$cats_data[$i]['cat_id']."&amp;cat_id_2=".$cats_data[$i+1]['cat_id']."&amp;$MYSID\">".$lng['movedown']."</a>";

					$adforums_tpl->blocks['catrow']->values = array(
						'akt_cat'=>$cats_data[$i],
						'up'=>$up,
						'down'=>$down,
						'appendix'=>$appendix
					);
					if($akt_forums_counter == 0) $adforums_tpl->blocks['catrow']->blocks['forumrow']->blank_tpl();

					$adforums_tpl->blocks['catrow']->parse_code(FALSE,TRUE);
					$y++;
					build_sub_cats($cats_data[$i]['cat_id'],$appendix.'--');
				}
			}
		}

		if($cats_counter != 0) build_sub_cats(0,'--');
		else $adforums_tpl->blocks['catrow']->blank_tpl();

		$x = FALSE;

		for($i = 0; $i < $forums_counter; $i++) {
			if($forums_data[$i]['cat_id'] == 0) {
				$adforums_tpl->blocks['forumrow']->values = array(
					'akt_forum'=>$forums_data[$i]
				);
				$adforums_tpl->blocks['forumrow']->parse_code(FALSE,TRUE);
				$tpl_config['akt_class'] = ($tpl_config['akt_class'] == $tpl_config['td1_class']) ? $tpl_config['td2_class'] : $tpl_config['td1_class'];
				$x = TRUE;
			}
		}
		if($x == FALSE) $adforums_tpl->blocks['forumrow']->blank_tpl();

		include_once('ad_pheader.php');

		$adforums_tpl->parse_code(TRUE);

		include_once('ad_ptail.php');
	break;

	case 'editforum';
		$forum_id = isset($_GET['forum_id']) ? $_GET['forum_id'] : 0;
		if(!$forum_data = get_forum_data($forum_id)) die('Kann Forendaten nicht laden!');

		$p_forum_name = isset($_POST['p_forum_name']) ? $_POST['p_forum_name'] : $forum_data['forum_name'];
		$p_forum_description = isset($_POST['p_forum_description']) ? $_POST['p_forum_description'] : $forum_data['forum_description'];
		$p_cat_id = isset($_POST['p_cat_id']) ? $_POST['p_cat_id'] : $forum_data['cat_id'];

		$p_members_view_forum = $forum_data['auth_members_view_forum'];
		$p_members_post_topic = $forum_data['auth_members_post_topic'];
		$p_members_post_reply = $forum_data['auth_members_post_reply'];
		$p_members_post_poll = $forum_data['auth_members_post_poll'];
		$p_members_edit_posts = $forum_data['auth_members_edit_posts'];
		$p_guests_view_forum = $forum_data['auth_guests_view_forum'];
		$p_guests_post_topic = $forum_data['auth_guests_post_topic'];
		$p_guests_post_reply = $forum_data['auth_guests_post_reply'];
		$p_guests_post_poll = $forum_data['auth_guests_post_poll'];

		$p_forum_is_moderated = $forum_data['forum_is_moderated'];
		$p_forum_enable_bbcode = $forum_data['forum_enable_bbcode'];
		$p_forum_enable_htmlcode = $forum_data['forum_enable_htmlcode'];
		$p_forum_enable_smilies = $forum_data['forum_enable_smilies'];

		if(isset($_GET['doit'])) {
			$p_members_view_forum = isset($_POST['p_members_view_forum']) ? 1 : 0;
			$p_members_post_topic = isset($_POST['p_members_post_topic']) ? 1 : 0;
			$p_members_post_reply = isset($_POST['p_members_post_reply']) ? 1 : 0;
			$p_members_post_poll = isset($_POST['p_members_post_poll']) ? 1 : 0;
			$p_members_edit_posts = isset($_POST['p_members_edit_posts']) ? 1 : 0;
			$p_guests_view_forum = isset($_POST['p_guests_view_forum']) ? 1 : 0;
			$p_guests_post_topic = isset($_POST['p_guests_post_topic']) ? 1 : 0;
			$p_guests_post_reply = isset($_POST['p_guests_post_reply']) ? 1 : 0;
			$p_guests_post_poll = isset($_POST['p_guests_post_poll']) ? 1 : 0;

			$p_forum_is_moderated = isset($_POST['p_forum_is_moderated']) ? 1 : 0;
			$p_forum_enable_bbcode = isset($_POST['p_forum_enable_bbcode']) ? 1 : 0;
			$p_forum_enable_htmlcode = isset($_POST['p_forum_enable_htmlcode']) ? 1 : 0;
			$p_forum_enable_smilies = isset($_POST['p_forum_enable_smilies']) ? 1 : 0;

			if(trim($p_forum_name) == '') $error = $lng['error_no_forum_name'];
			else {
				update_forum_data($forum_id,array(
					'forum_is_moderated'=>array('STR',$p_forum_is_moderated),
					'forum_enable_bbcode'=>array('STR',$p_forum_enable_bbcode),
					'forum_enable_htmlcode'=>array('STR',$p_forum_enable_htmlcode),
					'forum_enable_smilies'=>array('STR',$p_forum_enable_smilies),
					'forum_name'=>array('STR',$p_forum_name),
					'forum_description'=>array('STR',$p_forum_description),
					'cat_id'=>array('STR',$p_cat_id),
					'auth_members_view_forum'=>array('STR',$p_members_view_forum),
					'auth_members_post_topic'=>array('STR',$p_members_post_topic),
					'auth_members_post_reply'=>array('STR',$p_members_post_reply),
					'auth_members_post_poll'=>array('STR',$p_members_post_poll),
					'auth_members_edit_posts'=>array('STR',$p_members_edit_posts),
					'auth_guests_view_forum'=>array('STR',$p_guests_view_forum),
					'auth_guests_post_topic'=>array('STR',$p_guests_post_topic),
					'auth_guests_post_reply'=>array('STR',$p_guests_post_reply),
					'auth_guests_post_poll'=>array('STR',$p_guests_post_poll)
				));

				header("Location: administration.php?faction=ad_forums&$MYSID"); exit;
			}
		}

		$c = ' checked="checked"';

		$checked['smilies'] = ($p_forum_enable_smilies == 1) ? $c : '';
		$checked['bbcode'] = ($p_forum_enable_bbcode == 1) ? $c : '';
		$checked['htmlcode'] = ($p_forum_enable_htmlcode == 1) ? $c : '';
		$checked['moderated'] = ($p_forum_is_moderated == 1) ? $c : '';
		$checked['members_view_forum'] = ($p_members_view_forum == 1) ? $c : '';
		$checked['members_post_topic'] = ($p_members_post_topic == 1) ? $c : '';
		$checked['members_post_reply'] = ($p_members_post_reply == 1) ? $c : '';
		$checked['members_post_poll'] = ($p_members_post_poll == 1) ? $c : '';
		$checked['members_edit_posts'] = ($p_members_edit_posts == 1) ? $c : '';
		$checked['guests_view_forum'] = ($p_guests_view_forum == 1) ? $c : '';
		$checked['guests_post_topic'] = ($p_guests_post_topic == 1) ? $c : '';
		$checked['guests_post_reply'] = ($p_guests_post_reply== 1) ? $c : '';
		$checked['guests_post_poll'] = ($p_guests_post_poll == 1) ? $c : '';

		$adforums_tpl = new template;
		$adforums_tpl->load($template_path.'/'.$tpl_config['tpl_ad_forums_editforum']);

		if($error != '') {
			$adforums_tpl->blocks['errorrow']->parse_code();
		}
		else $adforums_tpl->unset_block('errorrow');

		$cats_data = get_cats_data();

		$no_cat_selected = ($p_cat_id == 0) ? ' selected="selected"' : '';

		while(list(,$akt_cat) = each($cats_data)) {
			$selected = ($p_cat_id == $akt_cat['cat_id']) ? ' selected="selected"' : '';
			$adforums_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);
		}

		include_once('ad_pheader.php');

		$adforums_tpl->parse_code(TRUE);

		include_once('ad_ptail.php');
	break;

	case 'editcat':
		$cat_id = isset($_GET['cat_id']) ? $_GET['cat_id'] : 0;
		if(!$cat_data = get_cat_data($cat_id)) die('Kann categoriedaten nicht laden!');

		$p_cat_name = isset($_POST['p_cat_name']) ? $_POST['p_cat_name'] : $cat_data['cat_name'];
		$p_cat_description = isset($_POST['p_cat_description']) ? $_POST['p_cat_description'] : $cat_data['cat_description'];
		$p_parent_id = isset($_POST['p_parent_id']) ? $_POST['p_parent_id'] : $cat_data['parent_id'];

		$error = '';

		if(isset($_GET['doit'])) {
			if(trim($p_cat_name) == '') $error = $lng['error_no_category_name'];
			else {
				update_cat_data($cat_id,array(
					'cat_name'=>array('STR',$p_cat_name),
					'cat_description'=>array('STR',$p_cat_description),
					'parent_id'=>array('STR',$p_parent_id)
				));

				header("Location: administration.php?faction=ad_forums&$MYSID"); exit;
			}
		}

		$adforums_tpl = new template;
		$adforums_tpl->load($template_path.'/'.$tpl_config['tpl_ad_forums_editcat']);

		if($error != '') {
			$adforums_tpl->blocks['errorrow']->parse_code();
		}
		else $adforums_tpl->unset_block('errorrow');

		$cats_data = get_cats_data();

		$no_parent_cat_selected = ($p_parent_id == 0) ? ' selected="selected"' : '';

		while(list(,$akt_cat) = each($cats_data)) {
			if($akt_cat['cat_id'] != $cat_id) {
				$selected = ($p_parent_id == $akt_cat['cat_id']) ? ' selected="selected"' : '';
				$adforums_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);
			}
		}

		include_once('ad_pheader.php');

		$adforums_tpl->parse_code(TRUE);

		include_once('ad_ptail.php');
	break;

	case 'movecatup':
		$cat_id_1 = isset($_GET['cat_id_1']) ? $_GET['cat_id_1'] : 0;
		$cat_id_2 = isset($_GET['cat_id_2']) ? $_GET['cat_id_2'] : 0;

		if(($cat_1_data = get_cat_data($cat_id_1)) && ($cat_2_data = get_cat_data($cat_id_2))) {
			if($cat_1_data['order_id'] > $cat_2_data['order_id']) {
				update_cat_data($cat_id_1,array('order_id'=>array('STR',$cat_2_data['order_id'])));
				update_cat_data($cat_id_2,array('order_id'=>array('STR',$cat_1_data['order_id'])));
			}
		}
		header("Location: administration.php?faction=ad_forums&$MYSID"); exit;
	break;

	case 'movecatdown':
		$cat_id_1 = isset($_GET['cat_id_1']) ? $_GET['cat_id_1'] : 0;
		$cat_id_2 = isset($_GET['cat_id_2']) ? $_GET['cat_id_2'] : 0;

		if(($cat_1_data = get_cat_data($cat_id_1)) && ($cat_2_data = get_cat_data($cat_id_2))) {
			if($cat_1_data['order_id'] < $cat_2_data['order_id']) {
				update_cat_data($cat_id_1,array('order_id'=>array('STR',$cat_2_data['order_id'])));
				update_cat_data($cat_id_2,array('order_id'=>array('STR',$cat_1_data['order_id'])));
			}
		}
		header("Location: administration.php?faction=ad_forums&$MYSID"); exit;
	break;

	case 'moveforumup':
		$forum_id_1 = isset($_GET['forum_id_1']) ? $_GET['forum_id_1'] : 0;
		$forum_id_2 = isset($_GET['forum_id_2']) ? $_GET['forum_id_2'] : 0;

		if(($forum_1_data = get_forum_data($forum_id_1)) && ($forum_2_data = get_forum_data($forum_id_2))) {
			if($forum_1_data['order_id'] > $forum_2_data['order_id']) {
				update_forum_data($forum_id_1,array('order_id'=>array('STR',$forum_2_data['order_id'])));
				update_forum_data($forum_id_2,array('order_id'=>array('STR',$forum_1_data['order_id'])));
			}
		}
		header("Location: administration.php?faction=ad_forums&$MYSID"); exit;
	break;

	case 'moveforumdown':
		$forum_id_1 = isset($_GET['forum_id_1']) ? $_GET['forum_id_1'] : 0;
		$forum_id_2 = isset($_GET['forum_id_2']) ? $_GET['forum_id_2'] : 0;

		if(($forum_1_data = get_forum_data($forum_id_1)) && ($forum_2_data = get_forum_data($forum_id_2))) {
			if($forum_1_data['order_id'] < $forum_2_data['order_id']) {
				update_forum_data($forum_id_1,array('order_id'=>array('STR',$forum_2_data['order_id'])));
				update_forum_data($forum_id_2,array('order_id'=>array('STR',$forum_1_data['order_id'])));
			}
		}
		header("Location: administration.php?faction=ad_forums&$MYSID"); exit;
	break;

	case 'editsrights':
		$forum_id = isset($_GET['forum_id']) ? $_GET['forum_id'] : 0;

		if(!$forum_data = get_forum_data($forum_id)) die('Kann Forumdaten nicht laden!');

		$p_rights = isset($_POST['p_rights']) ? $_POST['p_rights'] : array(array(),array());

		if(isset($_GET['doit'])) {
			while(list(,$akt_data) = each($p_rights[0])) {
				$akt_data['auth_is_mod'] = isset($akt_data['auth_is_mod']) ? 1 : 0;
				$akt_data['auth_view_forum'] = isset($akt_data['auth_view_forum']) ? 1 : 0;
				$akt_data['auth_post_topic'] = isset($akt_data['auth_post_topic']) ? 1 : 0;
				$akt_data['auth_post_reply'] = isset($akt_data['auth_post_reply']) ? 1 : 0;
				$akt_data['auth_post_poll'] = isset($akt_data['auth_post_poll']) ? 1 : 0;
				$akt_data['auth_edit_posts'] = isset($akt_data['auth_edit_posts']) ? 1 : 0;

				update_auth_data(array(
						'auth_forum_id'=>$forum_id,
						'auth_type'=>0,
						'auth_id'=>$akt_data['auth_id']
					),
					array(
						'auth_is_mod'=>array('STR',$akt_data['auth_is_mod']),
						'auth_view_forum'=>array('STR',$akt_data['auth_view_forum']),
						'auth_post_topic'=>array('STR',$akt_data['auth_post_topic']),
						'auth_post_reply'=>array('STR',$akt_data['auth_post_reply']),
						'auth_post_poll'=>array('STR',$akt_data['auth_post_poll']),
						'auth_edit_posts'=>array('STR',$akt_data['auth_edit_posts'])
					)
				);
			}

			include_once('ad_pheader.php');
			echo show_message('Special_rights_updated','message_special_rights_updated','<br />'.sprintf($lng['click_here_back'],"<a href=\"administration.php?faction=ad_forums&amp;mode=editforum&amp;forum_id=$forum_id&amp;$MYSID\">",'</a>'));
			include_once('ad_ptail.php'); exit;
		}

		$adforums_tpl = new template;
		$adforums_tpl->load($template_path.'/'.$tpl_config['tpl_ad_forums_editsrights']);

		$auth_user_data = get_auth_user_data(array('auth_forum_id'=>$forum_id,'auth_type'=>0));

		if(sizeof($auth_user_data) > 0) {
			while(list(,$akt_uright) = each($auth_user_data)) {
				$akt_checked = array();
				$c = ' checked="checked"';
				$akt_checked['auth_is_mod'] = ($akt_uright['auth_is_mod'] == 1) ? $c : '';
				$akt_checked['auth_view_forum'] = ($akt_uright['auth_view_forum'] == 1) ? $c : '';
				$akt_checked['auth_post_topic'] = ($akt_uright['auth_post_topic'] == 1) ? $c : '';
				$akt_checked['auth_post_reply'] = ($akt_uright['auth_post_reply'] == 1) ? $c : '';
				$akt_checked['auth_post_poll'] = ($akt_uright['auth_post_poll'] == 1) ? $c : '';
				$akt_checked['auth_edit_posts'] = ($akt_uright['auth_edit_posts'] == 1) ? $c : '';

				$adforums_tpl->blocks['urightrow']->parse_code(FALSE,TRUE);
				$tpl_config['akt_class'] = ($tpl_config['akt_class'] == $tpl_config['td1_class']) ? $tpl_config['td2_class'] : $tpl_config['td1_class'];
			}
		}
		else $adforums_tpl->unset_block('urightrow');

		include_once('ad_pheader.php');
		$adforums_tpl->parse_code(TRUE);
		include_once('ad_ptail.php');
	break;

	case 'addcat':
		$p_parent_id = isset($_GET['parent_id']) ? $_GET['parent_id'] : 0;
		if(isset($_POST['p_parent_id'])) $p_parent_id = $_POST['p_parent_id'];

		$p_cat_name = isset($_POST['p_cat_name']) ? $_POST['p_cat_name'] : '';
		$p_cat_description = isset($_POST['p_cat_description']) ? $_POST['p_cat_description'] : '';

		$error = '';

		if(isset($_GET['doit'])) {
			if(trim($p_cat_name) == '') $error = $lng['error_no_category_name'];
			else {
				$new_cat_data = add_cat_data(array(
					'parent_id'=>$p_parent_id,
					'cat_name'=>$p_cat_name,
					'cat_description'=>$p_cat_description
				));
				header("Location: administration.php?faction=ad_forums&$MYSID"); exit;
			}
		}

		$adforums_tpl = new template;
		$adforums_tpl->load($template_path.'/'.$tpl_config['tpl_ad_forums_addcat']);

		if($error != '') {
			$adforums_tpl->blocks['errorrow']->values = array(
				'ERROR'=>$error
			);
			$adforums_tpl->blocks['errorrow']->parse_code();
		}
		else $adforums_tpl->unset_block('errorrow');

		$cats_data = get_cats_data();

		$selected = ($p_parent_id == 0) ? ' selected="selected"' : '';

		$adforums_tpl->blocks['optionrow']->values = array(
			'VALUE'=>0,
			'TEXT'=>$lng['No_parent_category'],
			'SELECTED'=>$selected
		);
		$adforums_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);

		while(list(,$akt_cat) = each($cats_data)) {
			$selected = ($p_parent_id == $akt_cat['cat_id']) ? ' selected="selected"' : '';
			$adforums_tpl->blocks['optionrow']->values = array(
				'VALUE'=>$akt_cat['cat_id'],
				'TEXT'=>htmlspecialchars($akt_cat['cat_name']),
				'SELECTED'=>$selected
			);
			$adforums_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);
		}

		$adforums_tpl->values = array(
			'MYSID'=>$MYSID,
			'P_CAT_NAME'=>mutate($p_cat_name),
			'P_CAT_DESCRIPTION'=>mutate($p_cat_description),
			'LNG_ADD_CATEGORY'=>$lng['Add_category'],
			'LNG_NAME'=>$lng['Name'],
			'LNG_DESCRIPTION'=>$lng['Description'],
			'LNG_PARENT_CATEGORY'=>$lng['Parent_category'],
			'LNG_RESET'=>$lng['Reset']
		);

		include_once('ad_pheader.php');

		$adforums_tpl->parse_code(TRUE);

		include_once('ad_ptail.php');
	break;

	case 'addforum':

		$p_cat_id = isset($_GET['cat_id']) ? $_GET['cat_id'] : 0;
		if(isset($_POST['p_cat_id'])) $p_cat_id = $_POST['p_cat_id'];

		$p_forum_name = isset($_POST['p_forum_name']) ? $_POST['p_forum_name'] : '';
		$p_forum_description = isset($_POST['p_forum_description']) ? $_POST['p_forum_description'] : '';

		$p_forum_enable_bbcode = $p_forum_enable_smilies = $p_members_view_forum = $p_members_post_topic = $p_members_post_reply = $p_members_post_poll = $p_members_edit_posts = $p_guests_view_forum = 1;
		$p_forum_enable_htmlcode = $p_forum_is_moderated = $p_guests_post_topic = $p_guests_post_reply = $p_guests_post_poll = 0;


		if(isset($_GET['doit'])) {
			$p_members_view_forum = isset($_POST['p_members_view_forum']) ? 1 : 0;
			$p_members_post_topic = isset($_POST['p_members_post_topic']) ? 1 : 0;
			$p_members_post_reply = isset($_POST['p_members_post_reply']) ? 1 : 0;
			$p_members_post_poll = isset($_POST['p_members_post_poll']) ? 1 : 0;
			$p_members_edit_posts = isset($_POST['p_members_edit_posts']) ? 1 : 0;
			$p_guests_view_forum = isset($_POST['p_guests_view_forum']) ? 1 : 0;
			$p_guests_post_topic = isset($_POST['p_guests_post_topic']) ? 1 : 0;
			$p_guests_post_reply = isset($_POST['p_guests_post_reply']) ? 1 : 0;
			$p_guests_post_poll = isset($_POST['p_guests_post_poll']) ? 1 : 0;

			$p_forum_is_moderated = isset($_POST['p_forum_is_moderated']) ? 1 : 0;
			$p_forum_enable_bbcode = isset($_POST['p_forum_enable_bbcode']) ? 1 : 0;
			$p_forum_enable_htmlcode = isset($_POST['p_forum_enable_htmlcode']) ? 1 : 0;
			$p_forum_enable_smilies = isset($_POST['p_forum_enable_smilies']) ? 1 : 0;

			if(trim($p_forum_name) == '') $error = $lng['error_no_forum_name'];
			else {
				$new_forum_data = add_forum_data(array(
					'forum_is_moderated'=>$p_forum_is_moderated,
					'forum_enable_bbcode'=>$p_forum_enable_bbcode,
					'forum_enable_htmlcode'=>$p_forum_enable_htmlcode,
					'forum_enable_smilies'=>$p_forum_enable_smilies,
					'forum_name'=>$p_forum_name,
					'forum_description'=>$p_forum_description,
					'cat_id'=>$p_cat_id,
					'forum_topics_counter'=>0,
					'forum_posts_counter'=>0,
					'forum_last_post_post_id'=>0,
					'forum_last_post_topic_id'=>0,
					'auth_members_view_forum'=>$p_members_view_forum,
					'auth_members_post_topic'=>$p_members_post_topic,
					'auth_members_post_reply'=>$p_members_post_reply,
					'auth_members_post_poll'=>$p_members_post_poll,
					'auth_members_edit_posts'=>$p_members_edit_posts,
					'auth_guests_view_forum'=>$p_guests_view_forum,
					'auth_guests_post_topic'=>$p_guests_post_topic,
					'auth_guests_post_reply'=>$p_guests_post_reply,
					'auth_guests_post_poll'=>$p_guests_post_poll
				));

				header("Location: administration.php?faction=ad_forums&$MYSID"); exit;
			}
		}

		$c = ' checked="checked"';

		$checked['smilies'] = ($p_forum_enable_smilies == 1) ? $c : '';
		$checked['bbcode'] = ($p_forum_enable_bbcode == 1) ? $c : '';
		$checked['htmlcode'] = ($p_forum_enable_htmlcode == 1) ? $c : '';
		$checked['moderated'] = ($p_forum_is_moderated == 1) ? $c : '';
		$checked['members_view_forum'] = ($p_members_view_forum == 1) ? $c : '';
		$checked['members_post_topic'] = ($p_members_post_topic == 1) ? $c : '';
		$checked['members_post_reply'] = ($p_members_post_reply == 1) ? $c : '';
		$checked['members_post_poll'] = ($p_members_post_poll == 1) ? $c : '';
		$checked['members_edit_posts'] = ($p_members_edit_posts == 1) ? $c : '';
		$checked['guests_view_forum'] = ($p_guests_view_forum == 1) ? $c : '';
		$checked['guests_post_topic'] = ($p_guests_post_topic == 1) ? $c : '';
		$checked['guests_post_reply'] = ($p_guests_post_reply== 1) ? $c : '';
		$checked['guests_post_poll'] = ($p_guests_post_poll == 1) ? $c : '';

		$adforums_tpl = new template;
		$adforums_tpl->load($template_path.'/'.$tpl_config['tpl_ad_forums_addforum']);

		if($error != '') {
			$adforums_tpl->blocks['errorrow']->values = array(
				'ERROR'=>$error
			);
			$adforums_tpl->blocks['errorrow']->parse_code();
		}
		else $adforums_tpl->unset_block('errorrow');

		$cats_data = get_cats_data();

		$no_cat_selected = ($p_cat_id == 0) ? ' selected="selected"' : '';

		while(list(,$akt_cat) = each($cats_data)) {
			$akt_selected = ($p_cat_id == $akt_cat['cat_id']) ? ' selected="selected"' : '';
			$akt_cat['cat_name'] = htmlspecialchars($akt_cat['cat_name']);
			$adforums_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);
		}

		include_once('ad_pheader.php');

		$adforums_tpl->parse_code(TRUE);

		include_once('ad_ptail.php');
	break;

	case 'adduserright':
		$forum_id = isset($_GET['forum_id']) ? $_GET['forum_id'] : 0;

		if(!$forum_data = get_forum_data($forum_id)) die('Kann Forumdaten nicht laden!');

		$p_users = isset($_POST['p_users']) ? $_POST['p_users'] : '';

		$p_view_forum = $forum_data['auth_members_view_forum'];
		$p_post_topic = $forum_data['auth_members_post_topic'];
		$p_post_reply = $forum_data['auth_members_post_reply'];
		$p_post_poll = $forum_data['auth_members_post_poll'];
		$p_edit_posts = $forum_data['auth_members_edit_posts'];
		$p_is_mod = 0;

		if(isset($_GET['doit'])) {
			$p_view_forum = isset($_POST['p_view_forum']) ? 1 : 0;
			$p_post_topic = isset($_POST['p_post_topic']) ? 1 : 0;
			$p_post_reply = isset($_POST['p_post_reply']) ? 1 : 0;
			$p_post_poll = isset($_POST['p_post_poll']) ? 1 : 0;
			$p_edit_posts = isset($_POST['p_edit_posts']) ? 1 : 0;
			$p_is_mod = isset($_POST['p_is_mod']) ? 1 : 0;

			$users_array = explode(',',$p_users);
			while(list(,$akt_user) = each($users_array)) {
				if(($akt_user_id = get_user_id(trim($akt_user))) != FALSE && get_auth_data(array('auth_type'=>0,'auth_forum_id'=>$forum_id,'auth_id'=>$akt_user_id)) == FALSE) {
					add_auth_data(array(
						'auth_forum_id'=>$forum_id,
						'auth_type'=>0,
						'auth_id'=>$akt_user_id,
						'auth_view_forum'=>$p_view_forum,
						'auth_post_topic'=>$p_post_topic,
						'auth_post_reply'=>$p_post_reply,
						'auth_post_poll'=>$p_post_poll,
						'auth_edit_posts'=>$p_edit_posts,
						'auth_is_mod'=>$p_is_mod
					));
				}
			}
			header("Location: administration.php?faction=ad_forums&mode=editsrights&forum_id=$forum_id&$MYSID"); exit;
		}

		$c = ' checked="checked"';
		$checked['view_forum'] = ($p_view_forum == 1) ? $c : '';
		$checked['post_topic'] = ($p_post_topic == 1) ? $c : '';
		$checked['post_reply'] = ($p_post_reply == 1) ? $c : '';
		$checked['post_poll'] = ($p_post_poll == 1) ? $c : '';
		$checked['edit_posts'] = ($p_edit_posts == 1) ? $c : '';
		$checked['is_mod'] = ($p_is_mod == 1) ? $c : '';

		$adforums_tpl = new template;
		$adforums_tpl->load($template_path.'/'.$tpl_config['tpl_ad_forums_adduserright']);

		include_once('ad_pheader.php');

		$adforums_tpl->parse_code(TRUE);

		include_once('ad_ptail.php');
	break;

	case 'addgroupright':
		die('Funktion noch nicht verfügbar! <a href="javascript:history.back(1)">Zurück</a>');
	break;
}

?>