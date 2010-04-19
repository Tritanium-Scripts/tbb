<?php
/**
*
* Tritanium Bulletin Board 2 - ad_forums.php
* Dient zum Verwalten der Foren und Kategorien
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
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
								'AKT_CLASS'=>$tpl_config['akt_class'],
								'FORUM_NAME'=>$appendix.'-- '.$forums_data[$j]['forum_name'],
								'FORUM_ID'=>$forums_data[$j]['forum_id'],
								'MOVEUP'=>$up,
								'MOVEDOWN'=>$down,
								'LNG_EDIT'=>$lng['Edit'],
								'APPENDIX'=>$appendix.'--',
								'MYSID'=>$MYSID

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
						'CAT_NAME'=>$appendix.' '.$cats_data[$i]['cat_name'],
						'CAT_ID'=>$cats_data[$i]['cat_id'],
						'MYSID'=>$MYSID,
						'MOVEUP'=>$up,
						'MOVEDOWN'=>$down,
						'APPENDIX'=>$appendix,
						'LNG_ADD_FORUM'=>$lng['Add_forum'],
						'LNG_ADD_SUB_CATEGORY'=>$lng['Add_sub_category'],
						'LNG_EDIT'=>$lng['Edit']
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
					'AKT_CLASS'=>$tpl_config['akt_class'],
					'LNG_EDIT'=>$lng['Edit'],
					'FORUM_NAME'=>$forums_data[$i]['forum_name'],
					'FORUM_ID'=>$forums_data[$i]['forum_id'],
					'MYSID'=>$MYSID
				);
				$adforums_tpl->blocks['forumrow']->parse_code(FALSE,TRUE);
				$tpl_config['akt_class'] = ($tpl_config['akt_class'] == $tpl_config['td1_class']) ? $tpl_config['td2_class'] : $tpl_config['td1_class'];
				$x = TRUE;
			}
		}
		if($x == FALSE) $adforums_tpl->blocks['forumrow']->blank_tpl();

		$adforums_tpl->values = array(
			'MYSID'=>$MYSID,
			'LNG_ADD_CATEGORY'=>$lng['Add_category'],
			'LNG_ADD_FORUM'=>$lng['Add_forum'],
			'LNG_OTHER_OPTIONS'=>$lng['Other_options'],
			'LNG_MANAGE_FORUMS'=>$lng['Manage_forums'],
			'LNG_FORUMS_WITHOUT_CATEGORY'=>$lng['Forums_without_category']
		);

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
			$adforums_tpl->blocks['errorrow']->values = array(
				'ERROR'=>$error
			);
			$adforums_tpl->blocks['errorrow']->parse_code();
		}
		else $adforums_tpl->unset_block('errorrow');

		$cats_data = get_cats_data();

		$selected = ($p_cat_id == 0) ? ' selected="selected"' : '';

		$adforums_tpl->blocks['optionrow']->values = array(
			'VALUE'=>0,
			'TEXT'=>$lng['No_category'],
			'SELECTED'=>$selected
		);
		$adforums_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);

		while(list(,$akt_cat) = each($cats_data)) {
			$selected = ($p_cat_id == $akt_cat['cat_id']) ? ' selected="selected"' : '';
			$adforums_tpl->blocks['optionrow']->values = array(
			'VALUE'=>$akt_cat['cat_id'],
			'TEXT'=>htmlspecialchars($akt_cat['cat_name']),
			'SELECTED'=>$selected
			);
			$adforums_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);
		}

		$adforums_tpl->values = array(
			'FORUM_ID'=>$forum_id,
			'MYSID'=>$MYSID,
			'P_FORUM_NAME'=>mutate($p_forum_name),
			'P_FORUM_DESCRIPTION'=>mutate($p_forum_description),
			'C_SMILIES'=>$checked['smilies'],
			'C_MODERATED'=>$checked['moderated'],
			'C_HTMLCODE'=>$checked['htmlcode'],
			'C_BBCODE'=>$checked['bbcode'],
			'C_MEMBERS_VIEW_FORUM'=>$checked['members_view_forum'],
			'C_MEMBERS_POST_TOPIC'=>$checked['members_post_topic'],
			'C_MEMBERS_POST_REPLY'=>$checked['members_post_reply'],
			'C_MEMBERS_POST_POLL'=>$checked['members_post_poll'],
			'C_MEMBERS_EDIT_POSTS'=>$checked['members_edit_posts'],
			'C_GUESTS_VIEW_FORUM'=>$checked['guests_view_forum'],
			'C_GUESTS_POST_TOPIC'=>$checked['guests_post_topic'],
			'C_GUESTS_POST_REPLY'=>$checked['guests_post_reply'],
			'C_GUESTS_POST_POLL'=>$checked['guests_post_poll'],
			'LNG_EDIT_FORUM'=>$lng['Edit_forum'],
			'LNG_DESCRIPTION'=>$lng['Description'],
			'LNG_NAME'=>$lng['Name'],
			'LNG_CATEGORY'=>$lng['Category'],
			'LNG_MODERATE_FORUM'=>$lng['Moderate_forum'],
			'LNG_ENABLE_BBCODE'=>$lng['Enable_bbcode'],
			'LNG_ENABLE_HTMLCODE'=>$lng['Enable_html_code'],
			'LNG_ENABLE_SMILIES'=>$lng['Enable_smilies'],
			'LNG_GENERAL_INFORMATION'=>$lng['General_information'],
			'LNG_OTHER_OPTIONS'=>$lng['Other_options'],
			'LNG_GENERAL_RIGHTS'=>$lng['General_rights'],
			'LNG_EDIT_SPECIAL_RIGTHS'=>$lng['Edit_special_rights'],
			'LNG_RESET'=>$lng['Reset'],
			'LNG_MEMBERS_VIEW_FORUM'=>$lng['Members_view_forum'],
			'LNG_MEMBERS_POST_TOPIC'=>$lng['Members_post_topic'],
			'LNG_MEMBERS_POST_REPLY'=>$lng['Members_post_reply'],
			'LNG_MEMBERS_POST_POLL'=>$lng['Members_post_poll'],
			'LNG_MEMBERS_EDIT_POSTS'=>$lng['Members_edit_posts'],
			'LNG_GUESTS_VIEW_FORUM'=>$lng['Guests_view_forum'],
			'LNG_GUESTS_POST_TOPIC'=>$lng['Guests_post_topic'],
			'LNG_GUESTS_POST_REPLY'=>$lng['Guests_post_reply'],
			'LNG_GUESTS_POST_POLL'=>$lng['Guests_post_poll']
		);

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
			if($akt_cat['cat_id'] != $cat_id) {
				$selected = ($p_parent_id == $akt_cat['cat_id']) ? ' selected="selected"' : '';
				$adforums_tpl->blocks['optionrow']->values = array(
					'VALUE'=>$akt_cat['cat_id'],
					'TEXT'=>htmlspecialchars($akt_cat['cat_name']),
					'SELECTED'=>$selected
				);
				$adforums_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);
			}
		}

		$adforums_tpl->values = array(
			'CAT_ID'=>$cat_id,
			'MYSID'=>$MYSID,
			'P_CAT_NAME'=>mutate($p_cat_name),
			'P_CAT_DESCRIPTION'=>mutate($p_cat_description),
			'LNG_EDIT_CATEGORY'=>$lng['Edit_category'],
			'LNG_NAME'=>$lng['Name'],
			'LNG_DESCRIPTION'=>$lng['Description'],
			'LNG_PARENT_CATEGORY'=>$lng['Parent_category'],
			'LNG_RESET'=>$lng['Reset']
		);

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

	case 'move_forum_down':
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
		die('Funktion noch nicht verfügbar!');
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

		$selected = ($p_cat_id == 0) ? ' selected="selected"' : '';

		$adforums_tpl->blocks['optionrow']->values = array(
			'VALUE'=>0,
			'TEXT'=>$lng['No_category'],
			'SELECTED'=>$selected
		);
		$adforums_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);

		while(list(,$akt_cat) = each($cats_data)) {
			$selected = ($p_cat_id == $akt_cat['cat_id']) ? ' selected="selected"' : '';
			$adforums_tpl->blocks['optionrow']->values = array(
				'VALUE'=>$akt_cat['cat_id'],
				'TEXT'=>htmlspecialchars($akt_cat['cat_name']),
				'SELECTED'=>$selected
			);
			$adforums_tpl->blocks['optionrow']->parse_code(FALSE,TRUE);
		}

		$adforums_tpl->values = array(
			'MYSID'=>$MYSID,
			'P_FORUM_NAME'=>mutate($p_forum_name),
			'P_FORUM_DESCRIPTION'=>mutate($p_forum_description),
			'C_SMILIES'=>$checked['smilies'],
			'C_MODERATED'=>$checked['moderated'],
			'C_HTMLCODE'=>$checked['htmlcode'],
			'C_BBCODE'=>$checked['bbcode'],
			'C_MEMBERS_VIEW_FORUM'=>$checked['members_view_forum'],
			'C_MEMBERS_POST_TOPIC'=>$checked['members_post_topic'],
			'C_MEMBERS_POST_REPLY'=>$checked['members_post_reply'],
			'C_MEMBERS_POST_POLL'=>$checked['members_post_poll'],
			'C_MEMBERS_EDIT_POSTS'=>$checked['members_edit_posts'],
			'C_GUESTS_VIEW_FORUM'=>$checked['guests_view_forum'],
			'C_GUESTS_POST_TOPIC'=>$checked['guests_post_topic'],
			'C_GUESTS_POST_REPLY'=>$checked['guests_post_reply'],
			'C_GUESTS_POST_POLL'=>$checked['guests_post_poll'],
			'LNG_ADD_FORUM'=>$lng['Add_forum'],
			'LNG_DESCRIPTION'=>$lng['Description'],
			'LNG_NAME'=>$lng['Name'],
			'LNG_CATEGORY'=>$lng['Category'],
			'LNG_MODERATE_FORUM'=>$lng['Moderate_forum'],
			'LNG_ENABLE_BBCODE'=>$lng['Enable_bbcode'],
			'LNG_ENABLE_HTMLCODE'=>$lng['Enable_html_code'],
			'LNG_ENABLE_SMILIES'=>$lng['Enable_smilies'],
			'LNG_GENERAL_INFORMATION'=>$lng['General_information'],
			'LNG_OTHER_OPTIONS'=>$lng['Other_options'],
			'LNG_GENERAL_RIGHTS'=>$lng['General_rights'],
			'LNG_RESET'=>$lng['Reset'],
			'LNG_MEMBERS_VIEW_FORUM'=>$lng['Members_view_forum'],
			'LNG_MEMBERS_POST_TOPIC'=>$lng['Members_post_topic'],
			'LNG_MEMBERS_POST_REPLY'=>$lng['Members_post_reply'],
			'LNG_MEMBERS_POST_POLL'=>$lng['Members_post_poll'],
			'LNG_MEMBERS_EDIT_POSTS'=>$lng['Members_edit_posts'],
			'LNG_GUESTS_VIEW_FORUM'=>$lng['Guests_view_forum'],
			'LNG_GUESTS_POST_TOPIC'=>$lng['Guests_post_topic'],
			'LNG_GUESTS_POST_REPLY'=>$lng['Guests_post_reply'],
			'LNG_GUESTS_POST_POLL'=>$lng['Guests_post_poll']
		);

		include_once('ad_pheader.php');

		$adforums_tpl->parse_code(TRUE);

		include_once('ad_ptail.php');
	break;
}

?>