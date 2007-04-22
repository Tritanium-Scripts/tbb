<?php

class AdminForums extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'AuthAdmin',
		'Config',
		'DB',
		'GlobalsAdmin',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
		$this->modules['Language']->addFile('AdminForums');
		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Manage_forums'),INDEXFILE.'?action=AdminForums&amp;'.MYSID);

		switch(@$_GET['mode']) {
			default:
				$catsData = FuncCats::getCatsData();

				$this->modules['DB']->query("SELECT * FROM ".TBLPFX."forums ORDER BY orderID");
				$forumsData = $this->modules['DB']->raw2Array();

				$forumsCounter = count($forumsData);
				$catsCounter = count($catsData);

				$maxDepth = 0;

				for($i = 0; $i < $catsCounter; $i++) {
					$curCat = &$catsData[$i];

					if($curCat['catDepth'] > $maxDepth) $maxDepth = $curCat['catDepth'];

					$curPrefix = '';
					for($j = 0; $j < $curCat['catDepth']; $j++)
						$curPrefix .= '--';

					$curCat['_catPrefix'] = $curPrefix;

					$curForumsData = array();
					foreach($forumsData AS &$curForum) {
						if($curForum['catID'] != $curCat['catID']) continue;
						$curForumsData[] = &$curForum;
					}

					$curForumsCounter = count($curForumsData);

					for($j = 0; $j < $curForumsCounter; $j++) {
						if($j == 0) $curForumsData[$j]['_forumUp'] = $this->modules['Language']->getString('moveup');
						else $curForumsData[$j]['_forumUp'] = '<a href="'.INDEXFILE.'?action=AdminForums&amp;mode=FlipForums&amp;forumID1='.$curForumsData[$j]['forumID']."&amp;forumID2=".$curForumsData[$j-1]['forumID'].'&amp;'.MYSID.'">'.$this->modules['Language']->getString('moveup').'</a>';

						if($j == $curForumsCounter-1) $curForumsData[$j]['_forumDown'] = $this->modules['Language']->getString('movedown');
						else $curForumsData[$j]['_forumDown'] = '<a href="'.INDEXFILE.'?action=AdminForums&amp;mode=FlipForums&amp;forumID1='.$curForumsData[$j]['forumID']."&amp;forumID2=".$curForumsData[$j+1]['forumID'].'&amp;'.MYSID.'">'.$this->modules['Language']->getString('movedown').'</a>';
					}

				}

				for($i = 0; $i <= $maxDepth; $i++) {
					$curCatsData = array();
					for($j = 0; $j < $catsCounter; $j++)
						if($catsData[$j]['catDepth'] == $i)
							$curCatsData[] = &$catsData[$j];

					$curCatsCounter = count($curCatsData);
					for($j = 0; $j < $curCatsCounter; $j++) {
						if($j == 0) $curCatsData[$j]['_catUp'] = $this->modules['Language']->getString('moveup');
						else $curCatsData[$j]['_catUp'] = '<a href="'.INDEXFILE.'?action=AdminForums&amp;mode=MoveCatUp&amp;catID='.$curCatsData[$j]['catID'].'&amp;'.MYSID.'">'.$this->modules['Language']->getString('moveup').'</a>';

						if($j == $curCatsCounter-1) $curCatsData[$j]['_catDown'] = $this->modules['Language']->getString('movedown');
						else $curCatsData[$j]['_catDown'] = '<a href="'.INDEXFILE.'?action=AdminForums&amp;mode=MoveCatDown&amp;catID='.$curCatsData[$j]['catID'].'&amp'.MYSID.'">'.$this->modules['Language']->getString('movedown').'</a>';
					}
				}

				$this->modules['Template']->assign(array(
					'forumsData'=>$forumsData,
					'catsData'=>$catsData
				));

				$this->modules['PageParts']->printPage('AdminForums.tpl');
				break;

			case 'EditForum';
				$forumID = isset($_GET['forumID']) ? intval($_GET['forumID']) : 0;
				if(!$forumData = FuncForums::getForumData($forumID)) die('Cannot load data: forum');

				$p = Functions::getSGValues($_POST['p'],array('forumName','forumDescription','catID'),'',Functions::addSlashes($forumData));
				$c = Functions::getSGValues($_POST['c'],array('authViewForumMembers','authPostTopicMembers','authPostReplyMembers','authPostPollMembers','authEditPostsMembers','authViewForumGuests','authPostTopicGuests','authPostReplyGuests','authPostPollGuests','forumIsModerated','forumEnableBBCode','forumEnableHtmlCode','forumEnableSmilies','forumShowLatestPosts'),1,$forumData);

				$error = '';

				if(isset($_GET['doit'])) {
					$c = Functions::getSGValues($_POST['c'],array('authViewForumMembers','authPostTopicMembers','authPostReplyMembers','authPostPollMembers','authEditPostsMembers','authViewForumGuests','authPostTopicGuests','authPostReplyGuests','authPostPollGuests','forumIsModerated','forumEnableBBCode','forumEnableHtmlCode','forumEnableSmilies','forumShowLatestPosts'),0);

					if(trim($p['forumName']) == '') $error = $this->modules['Language']->getString('error_no_forum_name');
					else {
						$this->modules['DB']->query("
							UPDATE
								".TBLPFX."forums
							SET
								catID='".$p['catID']."',
								forumName='".$p['forumName']."',
								forumDescription='".$p['forumDescription']."',
								forumIsModerated='".$c['forumIsModerated']."',
								forumEnableBBCode='".$c['forumEnableBBCode']."',
								forumEnableHtmlCode='".$c['forumEnableHtmlCode']."',
								forumEnableSmilies='".$c['forumEnableSmilies']."',
								forumShowLatestPosts='".$c['forumShowLatestPosts']."',
								authViewForumMembers='".$c['authViewForumMembers']."',
								authPostTopicMembers='".$c['authPostTopicMembers']."',
								authPostReplyMembers='".$c['authPostReplyMembers']."',
								authPostPollMembers='".$c['authPostPollMembers']."',
								authEditPostsMembers='".$c['authEditPostsMembers']."',
								authViewForumGuests='".$c['authViewForumGuests']."',
								authPostTopicGuests='".$c['authPostTopicGuests']."',
								authPostReplyGuests='".$c['authPostReplyGuests']."',
								authPostPollGuests='".$c['authPostPollGuests']."'
							WHERE
								forumID='$forumID'
						");
						Functions::myHeader(INDEXFILE."?action=AdminForums&".MYSID);
					}
				}

				$catsData = FuncCats::getCatsData();
				array_unshift($catsData,array('catID'=>1,'catDepth'=>0,'catName'=>$this->modules['Language']->getString('No_category')));

				foreach($catsData AS &$curCat) {
					$curPrefix = '';
					for($i = 0; $i < $curCat['catDepth']; $i++)
						$curPrefix .= '--';

					$curCat['_catPrefix'] = $curPrefix;
				}

				$this->modules['Template']->assign(array(
					'catsData'=>$catsData,
					'forumData'=>$forumData,
					'p'=>Functions::HTMLSpecialChars(Functions::stripSlashes($p)),
					'c'=>$c,
					'error'=>$error,
					'forumID'=>$forumID
				));
				$this->modules['PageParts']->printPage('AdminForumsEditForum.tpl');
				break;

			case 'EditCat':
				$catID = isset($_GET['catID']) ? intval($_GET['catID']) : 0;
				if($catID == 1 || ($catData = FuncCats::getCatData($catID)) == FALSE) die('Cannot load data: category');

				$parentCatData = FuncCats::getParentCatData($catID);

				$p = Functions::getSGValues($_POST['p'],array('catName','catDescription','catStandardStatus'),'',Functions::addSlashes($catData));
				$p['parentID'] = isset($_POST['p']['parentID']) ? intval($_POST['p']['parentID']) : $parentCatData['catID'];

				$error = '';

				if(isset($_GET['doit'])) {
					if(trim($p['catName']) == '') $error = $this->modules['Language']->getStirng('error_no_category_name');
					else {
						$this->modules['DB']->query("
							UPDATE
								".TBLPFX."cats
							SET
								catName='".$p['catName']."',
								catDescription='".$p['catDescription']."',
								catStandardStatus='".$p['catStandardStatus']."'
							WHERE
								catID='$catID'
						");

						if($p['parentID'] != $parentCatData['catID'])
							FuncCats::moveCat($catID,$p['parentID']);

						Functions::myHeader(INDEXFILE."?action=AdminForums&".MYSID);
					}
				}

				$catsData = FuncCats::getCatsData();
				array_unshift($catsData,array('catID'=>1,'catDepth'=>0,'catName'=>$this->modules['Language']->getString('No_parent_category')));
				$catsCounter = count($catsData);

				$relevantCatsData = array();
				for($i = 0; $i < $catsCounter; $i++) {
					$curCat = &$catsData[$i];
					if($curCat['catID'] != $catID) {
						$curPrefix = '';
						for($j = 0; $j < $curCat['catDepth']; $j++)
							$curPrefix .= '--';

						$curCat['_catPrefix'] = $curPrefix;
						$relevantCatsData[] = &$curCat;
					}
					else $i += $curCat['catChildsCounter'];
				}

				$this->modules['Template']->assign(array(
					'catsData'=>$relevantCatsData,
					'catData'=>$catData,
					'catID'=>$catID,
					'p'=>Functions::stripSlashes(Functions::HTMLSpecialChars($p)),
					'error'=>$error,
					'parentCatData'=>$parentCatData
				));

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Edit_category'),INDEXFILE.'?action=EditCat&amp;catID='.$catID.'&amp;'.MYSID);
				$this->modules['PageParts']->printPage('AdminForumsEditCat.tpl');
				break;

			case 'MoveCatUp':
				$catID = isset($_GET['catID']) ? intval($_GET['catID']) : 0;

				if($catData = FuncCats::getCatData($catID))
					FuncCats::moveCatUp($catID);

				Functions::myHeader(INDEXFILE."?action=AdminForums&".MYSID);
				break;

			case 'MoveCatDown':
				$catID = isset($_GET['catID']) ? intval($_GET['catID']) : 0;

				if($catData = FuncCats::getCatData($catID))
					FuncCats::moveCatDown($catID);

				Functions::myHeader(INDEXFILE."?action=AdminForums&".MYSID);
				break;

			case 'FlipForums':
				$forumID1 = isset($_GET['forumID1']) ? intval($_GET['forumID1']) : 0;
				$forumID2 = isset($_GET['forumID2']) ? intval($_GET['forumID2']) : 0;

				if(($forumData1 = FuncForums::getForumData($forumID1)) && ($forumData2 = FuncForums::getForumData($forumID2))) {
					$this->modules['DB']->query("UPDATE ".TBLPFX."forums SET orderID='".$forumData2['orderID']."' WHERE forumID='$forumID1'");
					$this->modules['DB']->query("UPDATE ".TBLPFX."forums SET orderID='".$forumData1['orderID']."' WHERE forumID='$forumID2'");
				}
				Functions::myHeader(INDEXFILE."?action=AdminForums&".MYSID);
				break;

			case 'EditSpecialRights':
				$forumID = isset($_GET['forumID']) ? intval($_GET['forumID']) : 0;

				if(!$forumData = FuncForums::getForumData($forumID)) die('Cannot load data: forum');

				$p['rightsData'] = (isset($_POST['p']['rightsData']) && is_array($_POST['p']['rightsData'])) ? $_POST['p']['rightsData'] : array();

				if(isset($_GET['doit'])) {
					foreach($p['rightsData'] AS $curRight) {
						$curRight['authIsMod'] = isset($curRight['authIsMod']) ? 1 : 0;
						$curRight['authViewForum'] = isset($curRight['authViewForum']) ? 1 : 0;
						$curRight['authPostTopic'] = isset($curRight['authPostTopic']) ? 1 : 0;
						$curRight['authPostReply'] = isset($curRight['authPostReply']) ? 1 : 0;
						$curRight['authPostPoll'] = isset($curRight['authPostPoll']) ? 1 : 0;
						$curRight['authEditPosts'] = isset($curRight['authEditPosts']) ? 1 : 0;

						$this->modules['DB']->query("
							UPDATE
								".TBLPFX."forums_auth
							SET
								authIsMod='".$curRight['authIsMod']."',
								authViewForum='".$curRight['authViewForum']."',
								authPostTopic='".$curRight['authPostTopic']."',
								authPostReply='".$curRight['authPostReply']."',
								authPostPoll='".$curRight['authPostPoll']."',
								authEditPosts='".$curRight['authEditPosts']."'
							WHERE
								forumID='$forumID'
								AND authType='".$curRight['authType']."'
								AND authID='".$curRight['authID']."'
						");
					}

					// TODO: Add link to message
					// show_message($LNG['Special_rights_updated'],$LNG['message_special_rights_updated'].'<br />'.sprintf($LNG['click_here_back'],"<a href=\"administration.php?action=ad_forums&amp;mode=editforum&amp;forumID=$forumID&amp;$MYSID\">",'</a>'),FALSE);
					$this->modules['PageParts']->printMessage('special_rights_updated');
				}

				$this->modules['DB']->query("
					SELECT
						t1.*,
						t2.userNick AS authUserNick
					FROM (
						".TBLPFX."forums_auth AS t1,
						".TBLPFX."users AS t2
					) WHERE
						forumID='$forumID'
						AND authType='".AUTH_TYPE_USER."'
						AND t2.userID=t1.authID
					ORDER BY
						t2.userNick ASC
				");
				$rightsDataUsers = $this->modules['DB']->raw2Array();

				$this->modules['DB']->query("
					SELECT
						t1.*,
						t2.groupName AS authGroupName
					FROM (
						".TBLPFX."forums_auth AS t1,
						".TBLPFX."groups AS t2
					) WHERE
						forumID='$forumID'
						AND authType='".AUTH_TYPE_GROUP."'
						AND t2.groupID=t1.authID
					ORDER BY
						t2.groupName ASC
				");
				$rightsDataGroups = $this->modules['DB']->raw2Array();

				$this->modules['Template']->assign(array(
					'rightsDataGroups'=>$rightsDataGroups,
					'rightsDataUsers'=>$rightsDataUsers,
					'forumID'=>$forumID
				));
				$this->modules['PageParts']->printPage('AdminForumsEditSpecialRights.tpl');
			break;


			//*
			//* Kategorie hinzufuegen
			//*
			case 'addcat':
				$p_parent_id = isset($_GET['parent_id']) ? $_GET['parent_id'] : 1;
				if(isset($_POST['p_parent_id'])) $p_parent_id = $_POST['p_parent_id'];

				$p_cat_name = isset($_POST['p_cat_name']) ? $_POST['p_cat_name'] : '';
				$p_cat_description = isset($_POST['p_cat_description']) ? $_POST['p_cat_description'] : '';
				$p_cat_standard_status = isset($_POST['p_cat_standard_status']) ? $_POST['p_cat_standard_status'] : 1;

				$error = '';

				if(isset($_GET['doit'])) {
					if(trim($p_cat_name) == '') $error = $LNG['error_no_category_name'];
					else {
						if($new_catID = cats_add_cat_data($p_parent_id)) {
							$this->modules['DB']->query("UPDATE ".TBLPFX."cats SET cat_standard_status='$p_cat_standard_status', cat_name='$p_cat_name', cat_description='$p_cat_description' WHERE catID='$new_catID'");
						}
						header("Location: administration.php?action=ad_forums&$MYSID"); exit;
					}
				}

				$c = ' selected="selected"';
				$checked = array(
					'open'=>'',
					'closed'=>''
				);
				($p_cat_standard_status == 0) ? $checked['closed'] = $c : $checked['open'] = $c;

				$adforums_tpl = new Template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_forums_addcat']);

				if($error != '') $adforums_tpl->Blocks['errorrow']->parseCode();

				$catsData = cats_get_catsData();
				array_unshift($catsData,array('catID'=>1,'cat_depth'=>0,'cat_name'=>$LNG['No_parent_category'])); // "Keine uebergeordnete Kategorie" zum Array hinzufuegen
				$catsCounter = count($catsData);


				while(list(,$akt_cat) = each($catsData)) {
					$akt_prefix = '';
					for($j = 0; $j < $akt_cat['cat_depth']; $j++)
						$akt_prefix .= '--';

					$akt_selected = ($p_parent_id == $akt_cat['catID']) ? ' selected="selected"' : '';
					$adforums_tpl->Blocks['optionrow']->parseCode(FALSE,TRUE);
				}


				include_once('pheader.php');

				$adforums_tpl->parseCode(TRUE);

				include_once('ptail.php');
			break;


			//*
			//* Forum hinzufuegen
			//*
			case 'addforum':

				$p_catID = isset($_GET['catID']) ? $_GET['catID'] : 0;
				if(isset($_POST['p_catID'])) $p_catID = $_POST['p_catID'];

				$p_forum_name = isset($_POST['p_forum_name']) ? $_POST['p_forum_name'] : '';
				$p_forum_description = isset($_POST['p_forum_description']) ? $_POST['p_forum_description'] : '';

				$p_forum_enable_bbcode = $p_forum_enable_smilies = $p_members_view_forum = $p_members_post_topic = $p_members_post_reply = $p_members_post_poll = $p_members_edit_posts = $p_guests_view_forum = $p_forum_show_latest_posts = 1;
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

					$p_forum_show_latest_posts = isset($_POST['p_forum_show_latest_posts']) ? 1 : 0;
					$p_forum_is_moderated = isset($_POST['p_forum_is_moderated']) ? 1 : 0;
					$p_forum_enable_bbcode = isset($_POST['p_forum_enable_bbcode']) ? 1 : 0;
					$p_forum_enable_htmlcode = isset($_POST['p_forum_enable_htmlcode']) ? 1 : 0;
					$p_forum_enable_smilies = isset($_POST['p_forum_enable_smilies']) ? 1 : 0;

					if(trim($p_forum_name) == '') $error = $LNG['error_no_forum_name'];
					else {

						$this->modules['DB']->query("SELECT MAX(order_id) AS max_ord_id FROM ".TBLPFX."forums");
						list($p_order_id) = $this->modules['DB']->fetch_array();
						$p_order_id++;

						$this->modules['DB']->query("INSERT INTO ".TBLPFX."forums (catID,order_id,forum_name,forum_description,forum_topics_counter,forum_posts_counter,forum_last_post_id,forum_enable_bbcode,forum_enable_htmlcode,forum_enable_smilies,forum_is_moderated,forum_show_latest_posts,auth_members_view_forum,auth_members_post_topic,auth_members_post_reply,auth_members_post_poll,auth_members_edit_posts,auth_guests_view_forum,auth_guests_post_topic,auth_guests_post_reply,auth_guests_post_poll)
							VALUES ('$p_catID','$p_order_id','$p_forum_name','$p_forum_description','0','0','0','$p_forum_enable_bbcode','$p_forum_enable_htmlcode','$p_forum_enable_smilies','$p_forum_is_moderated','$p_forum_show_latest_posts','$p_members_view_forum','$p_members_post_topic','$p_members_post_reply','$p_members_post_poll','$p_members_edit_posts','$p_guests_view_forum','$p_guests_post_topic','$p_guests_post_reply','$p_guests_post_poll')");

						header("Location: administration.php?action=ad_forums&$MYSID"); exit;
					}
				}

				$c = ' checked="checked"';

				$checked['smilies'] = ($p_forum_enable_smilies == 1) ? $c : '';
				$checked['bbcode'] = ($p_forum_enable_bbcode == 1) ? $c : '';
				$checked['htmlcode'] = ($p_forum_enable_htmlcode == 1) ? $c : '';
				$checked['moderated'] = ($p_forum_is_moderated == 1) ? $c : '';
				$checked['latestposts'] = ($p_forum_show_latest_posts == 1) ? $c : '';
				$checked['members_view_forum'] = ($p_members_view_forum == 1) ? $c : '';
				$checked['members_post_topic'] = ($p_members_post_topic == 1) ? $c : '';
				$checked['members_post_reply'] = ($p_members_post_reply == 1) ? $c : '';
				$checked['members_post_poll'] = ($p_members_post_poll == 1) ? $c : '';
				$checked['members_edit_posts'] = ($p_members_edit_posts == 1) ? $c : '';
				$checked['guests_view_forum'] = ($p_guests_view_forum == 1) ? $c : '';
				$checked['guests_post_topic'] = ($p_guests_post_topic == 1) ? $c : '';
				$checked['guests_post_reply'] = ($p_guests_post_reply== 1) ? $c : '';
				$checked['guests_post_poll'] = ($p_guests_post_poll == 1) ? $c : '';

				$adforums_tpl = new Template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_forums_addforum']);

				if($error != '') $adforums_tpl->Blocks['errorrow']->parseCode();


				$catsData = cats_get_catsData();
				array_unshift($catsData,array('catID'=>1,'cat_depth'=>0,'cat_name'=>$LNG['No_category'])); // "Keine uebergeordnete Kategorie" zum Array hinzufuegen

				while(list(,$akt_cat) = each($catsData)) {
					$akt_prefix = '';
					for($i = 0; $i < $akt_cat['cat_depth']; $i++)
						$akt_prefix .= '--';

					$akt_selected = ($p_catID == $akt_cat['catID']) ? ' selected="selected"' : '';
					$adforums_tpl->Blocks['optionrow']->parseCode(FALSE,TRUE);
				}


				include_once('pheader.php');

				$adforums_tpl->parseCode(TRUE);

				include_once('ptail.php');
			break;


			//*
			//* Spezialrecht fuer einzelne User hinzufuegen
			//*
			case 'adduserright':
				$forumID = isset($_GET['forumID']) ? $_GET['forumID'] : 0;

				if(!$forumData = get_forumData($forumID)) die('Kann Forumdaten nicht laden!');

				$p_users = isset($_POST['p_users']) ? $_POST['p_users'] : '';

				$p_view_forum = $forumData['auth_members_view_forum'];
				$p_post_topic = $forumData['auth_members_post_topic'];
				$p_post_reply = $forumData['auth_members_post_reply'];
				$p_post_poll = $forumData['auth_members_post_poll'];
				$p_edit_posts = $forumData['auth_members_edit_posts'];
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
						if(($akt_user_id = get_user_id(trim($akt_user))) != FALSE) {
							$this->modules['DB']->query("SELECT auth_id FROM ".TBLPFX."forums_auth WHERE forumID='$forumID' AND auth_type='0' AND auth_id='$akt_user_id'");
							if($this->modules['DB']->affected_rows == 0) $this->modules['DB']->query("INSERT INTO ".TBLPFX."forums_auth (forumID,auth_type,auth_id,auth_view_forum,auth_post_topic,auth_post_reply,auth_post_poll,auth_edit_posts,auth_is_mod) VALUES ('$forumID','0','$akt_user_id','$p_view_forum','$p_post_topic','$p_post_reply','$p_post_poll','$p_edit_posts','$p_is_mod')");
						}
					}
					header("Location: administration.php?action=ad_forums&mode=editsrights&forumID=$forumID&$MYSID"); exit;
				}

				$c = ' checked="checked"';
				$checked['view_forum'] = ($p_view_forum == 1) ? $c : '';
				$checked['post_topic'] = ($p_post_topic == 1) ? $c : '';
				$checked['post_reply'] = ($p_post_reply == 1) ? $c : '';
				$checked['post_poll'] = ($p_post_poll == 1) ? $c : '';
				$checked['edit_posts'] = ($p_edit_posts == 1) ? $c : '';
				$checked['is_mod'] = ($p_is_mod == 1) ? $c : '';

				$adforums_tpl = new Template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_forums_adduserright']);

				include_once('pheader.php');
				$adforums_tpl->parseCode(TRUE);
				include_once('ptail.php');
			break;


			//*
			//* Spezialrecht fuer Gruppe hinzufuegen
			//*
			case 'addgroupright':
				$forumID = isset($_GET['forumID']) ? $_GET['forumID'] : 0; // ID des Forums
				$p_group_id = isset($_POST['p_group_id']) ? $_POST['p_group_id'] : 0; // ID der Gruppe

				if(!$forumData = get_forumData($forumID)) die('Kann Forumdaten nicht laden!'); // Ueberpruefen, ob Forum existiert

				$p_view_forum = $forumData['auth_members_view_forum'];
				$p_post_topic = $forumData['auth_members_post_topic'];
				$p_post_reply = $forumData['auth_members_post_reply'];
				$p_post_poll = $forumData['auth_members_post_poll'];
				$p_edit_posts = $forumData['auth_members_edit_posts'];
				$p_is_mod = 0;

				if(isset($_GET['doit'])) { // Falls Formular abgeschickt wurde
					$p_view_forum = isset($_POST['p_view_forum']) ? 1 : 0;
					$p_post_topic = isset($_POST['p_post_topic']) ? 1 : 0;
					$p_post_reply = isset($_POST['p_post_reply']) ? 1 : 0;
					$p_post_poll = isset($_POST['p_post_poll']) ? 1 : 0;
					$p_edit_posts = isset($_POST['p_edit_posts']) ? 1 : 0;
					$p_is_mod = isset($_POST['p_is_mod']) ? 1 : 0;

					if($group_data = get_group_data($p_group_id)) { // Falls die Gruppe existiert
						$this->modules['DB']->query("SELECT auth_id FROM ".TBLPFX."forums_auth WHERE forumID='$forumID' AND auth_type='1' AND auth_id='$p_group_id'"); // Ueberpruefen, ob diese Gruppe in diesem Forum schon Spezialrechte hat
						if($this->modules['DB']->affected_rows == 0) $this->modules['DB']->query("INSERT INTO ".TBLPFX."forums_auth (forumID,auth_type,auth_id,auth_view_forum,auth_post_topic,auth_post_reply,auth_post_poll,auth_edit_posts,auth_is_mod) VALUES ('$forumID','1','$p_group_id','$p_view_forum','$p_post_topic','$p_post_reply','$p_post_poll','$p_edit_posts','$p_is_mod')"); // Falls nicht, die neuen Spezialrechte speichern
					}

					header("Location: administration.php?action=ad_forums&mode=editsrights&forumID=$forumID&$MYSID"); exit; // Zurueck zur Spezialrechteuebersicht
				}

				$c = ' checked="checked"';
				$checked['view_forum'] = ($p_view_forum == 1) ? $c : '';
				$checked['post_topic'] = ($p_post_topic == 1) ? $c : '';
				$checked['post_reply'] = ($p_post_reply == 1) ? $c : '';
				$checked['post_poll'] = ($p_post_poll == 1) ? $c : '';
				$checked['edit_posts'] = ($p_edit_posts == 1) ? $c : '';
				$checked['is_mod'] = ($p_is_mod == 1) ? $c : '';

				$ad_forums_tpl = new Template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_forums_addgroupright']); // Neue Templateklasse erzeugen

				$group_ids = array(); // Array fuer die IDs der Gruppen, die schon Spezialrechte haben
				$this->modules['DB']->query("SELECT auth_id FROM ".TBLPFX."forums_auth WHERE auth_type='1' AND forumID='$forumID'"); // IDs der Gruppen laden, die schon Spezialrechte haben
				while(list($akt_group_id) = $this->modules['DB']->fetch_array())
					$group_ids[] = $akt_group_id; // Aktuelle ID zum Array hinzufuegen

				$this->modules['DB']->query("SELECT * FROM ".TBLPFX."groups WHERE group_id NOT IN ('".implode("','",$group_ids)."')"); // Die IDs der Gruppen laden, die noch keine Spezialrechte in diesem Forum haben
				if($this->modules['DB']->affected_rows != 0) { // Falls Gruppen existieren
					while($akt_group = $this->modules['DB']->fetch_array())
						$ad_forums_tpl->Blocks['grouprow']->parseCode(FALSE,TRUE); // Templateblock fuer eine Option mit der aktuellen Gruppe erzeugen
				}

				include_once('pheader.php'); // Seitenkopf ausgeben
				$ad_forums_tpl->parseCode(TRUE); // Seite ausgeben
				include_once('ptail.php'); // Seitenende ausgeben
			break;

			case 'deletesright':
				$forumID = isset($_GET['forumID']) ? $_GET['forumID'] : 0; // ID des Forums
				$sright_type = isset($_GET['sright_type']) ? $_GET['sright_type'] : 2; // Spezialrechttyp (0 = User, 1 = Gruppe (, 2 = ungueltig))
				$sright_id = isset($_GET['sright_id']) ? $_GET['sright_id'] : 0; // ID des Spezialrechts

				$this->modules['DB']->query("DELETE FROM ".TBLPFX."forums_auth WHERE forumID='$forumID' AND auth_type='$sright_type' AND auth_id='$sright_id'"); // Loeschen des entsprechenden Spezialrechts

				header("Location: administration.php?action=ad_forums&mode=editsrights&forumID=$forumID&$MYSID"); exit; // Zurueck zur Spezialrechteuebersicht
			break;
		}

	}
}

?>