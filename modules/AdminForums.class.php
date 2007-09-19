<?php

class AdminForums extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'AuthAdmin',
		'DB',
		'GlobalsAdmin',
		'Language',
		'Navbar',
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

				$this->modules['Template']->printPage('AdminForums.tpl');
				break;

			case 'AddForum':
				$p  = Functions::getSGValues($_POST['p'],array('forumName','forumDescription'),'');
				$c  = Functions::getSGValues($_POST['c'],array('authViewForumMembers','authPostTopicMembers','authPostReplyMembers','authPostPollMembers','authEditPostsMembers','authViewForumGuests','forumEnableBBCode','forumEnableSmilies','forumShowLatestPosts'),1);
				$c += Functions::getSGValues($_POST['c'],array('authPostTopicGuests','authPostReplyGuests','authPostPollGuests','forumIsModerated','forumEnableHtmlCode'),0);

				$p['catID'] = isset($_POST['p']['catID']) ? intval($_POST['p']['catID']) : 1;
				if(isset($_GET['catID'])) $p['catID'] = intval($_GET['catID']);

				$error = '';

				if(isset($_GET['doit'])) {
					$c = Functions::getSGValues($_POST['c'],array('authViewForumMembers','authPostTopicMembers','authPostReplyMembers','authPostPollMembers','authEditPostsMembers','authViewForumGuests','authPostTopicGuests','authPostReplyGuests','authPostPollGuests','forumIsModerated','forumEnableBBCode','forumEnableHtmlCode','forumEnableSmilies','forumShowLatestPosts'),0);

					if(trim($p['forumName']) == '') $error = $this->modules['Language']->getString('error_no_forum_name');
					else {
						if(!FuncCats::getCatData($p['catID'])) $p['catID'] = 1;

						$this->modules['DB']->query("
							INSERT INTO
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
					'p'=>Functions::HTMLSpecialChars(Functions::stripSlashes($p)),
					'c'=>$c,
					'error'=>$error
				));

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Add_forum'),INDEXFILE.'?action=AdminForums&amp;mode=AddForum&amp;catID='.$p['catID'].'&amp;'.MYSID);
				$this->modules['Template']->printPage('AdminForumsAddForum.tpl');
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
						if(!FuncCats::getCatData($p['catID'])) $p['catID'] = 1;

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

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Edit_forum'),INDEXFILE.'?action=AdminForums&amp;mode=EditForum&amp;forumID='.$forumID.'&amp;'.MYSID);
				$this->modules['Template']->printPage('AdminForumsEditForum.tpl');
				break;

			case 'AddCat':
				$p = Functions::getSGValues($_POST['p'],array('catName','catDescription'),'');
				$p += Functions::getSGValues($_POST['p'],array('catStandardStatus'),1);
				$p['parentCatID'] = isset($_POST['p']['parentCatID']) ? intval($_POST['p']['parentCatID']) : 1;
				if(isset($_GET['parentCatID'])) $p['parentCatID'] = intval($_GET['parentCatID']);

				$error = '';

				if(isset($_GET['doit'])) {
					if(trim($p['catName']) == '') $error = $this->modules['Language']->getString('error_no_category_name');
					elseif(!FuncCats::getCatData($p['parentCatID'])) $error = $this->modules['Language']->getString('error_invalid_parent_category');
					else {
						if($newCatID = FuncCats::addCatData($p['parentCatID'])) {
							$this->modules['DB']->query("
								UPDATE
									".TBLPFX."cats
								SET
									catName='".$p['catName']."',
									catDescription='".$p['catDescription']."',
									catStandardStatus='".$p['catStandardStatus']."'
								WHERE
									catID='$newCatID'
							");
						}

						Functions::myHeader(INDEXFILE."?action=AdminForums&".MYSID);
					}
				}

				$catsData = FuncCats::getCatsData();
				array_unshift($catsData,array('catID'=>1,'catDepth'=>0,'catName'=>$this->modules['Language']->getString('No_parent_category')));

				foreach($catsData AS &$curCat) {
					$curPrefix = '';
					for($i = 0; $i < $curCat['catDepth']; $i++)
						$curPrefix .= '--';

					$curCat['_catPrefix'] = $curPrefix;
				}

				$this->modules['Template']->assign(array(
					'catsData'=>$catsData,
					'p'=>Functions::stripSlashes(Functions::HTMLSpecialChars($p)),
					'error'=>$error
				));

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Add_category'),INDEXFILE.'?action=AdminForums&amp;mode=AddCat&amp;parentCatID='.$p['parentCatID'].'&amp;'.MYSID);
				$this->modules['Template']->printPage('AdminForumsAddCat.tpl');
				break;

			case 'EditCat':
				$catID = isset($_GET['catID']) ? intval($_GET['catID']) : 0;
				if($catID == 1 || !($catData = FuncCats::getCatData($catID))) die('Cannot load data: category');

				$parentCatData = FuncCats::getParentCatData($catID);

				$p = Functions::getSGValues($_POST['p'],array('catName','catDescription','catStandardStatus'),'',Functions::addSlashes($catData));
				$p['parentCatID'] = isset($_POST['p']['parentCatID']) ? intval($_POST['p']['parentCatID']) : $parentCatData['catID'];

				$error = '';

				if(isset($_GET['doit'])) {
					if(trim($p['catName']) == '') $error = $this->modules['Language']->getStirng('error_no_category_name');
					elseif(!FuncCats::getCatData($p['parentCatID'])) $error = $this->modules['Language']->getString('error_invalid_parent_category');
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

						if($p['parentCatID'] != $parentCatData['catID'])
							FuncCats::moveCat($catID,$p['parentCatID']);

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

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Edit_category'),INDEXFILE.'?action=AdminForums&amp;mode=EditCat&amp;catID='.$catID.'&amp;'.MYSID);
				$this->modules['Template']->printPage('AdminForumsEditCat.tpl');
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

					FuncMisc::printMessage('special_rights_updated',array(sprintf($this->modules['Language']->getString('click_here_back'),'<a href="'.INDEXFILE.'?action=AdminForums&amp;mode=EditForum&amp;forumID='.$forumID.'&amp;'.MYSID.'">','</a>'))); exit;
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

				$this->modules['Navbar']->addElements(
					array($this->modules['Language']->getString('Edit_forum'),INDEXFILE.'?action=AdminForums&amp;mode=EditForum&amp;forumID='.$forumID.'&amp;'.MYSID),
					array($this->modules['Language']->getString('Edit_special_rights'),INDEXFILE.'?action=AdminForums&amp;mode=EditSpecialRights&amp;forumID='.$forumID.'&amp;'.MYSID)
				);
				$this->modules['Template']->printPage('AdminForumsEditSpecialRights.tpl');
				break;

			case 'AddUserRight':
				$forumID = isset($_GET['forumID']) ? $_GET['forumID'] : 0;
				if(!$forumData = FuncForums::getForumData($forumID)) die('Cannot load date: forum');

				$p = Functions::getSGValues($_POST['p'],array('users'),'');
				$c = Functions::getSGValues($_POST['c'],array('authViewForumMembers','authPostTopicMembers','authPostReplyMembers','authPostPollMembers','authEditPostsMembers'),0,Functions::addSlashes($forumData));
				$c += Functions::getSGValues($_POST['c'],array('authIsMod'),0);

				if(isset($_GET['doit'])) {
					$c = Functions::getSGValues($_POST['c'],array('authViewForumMembers','authPostTopicMembers','authPostReplyMembers','authPostPollMembers','authEditPostsMembers','authIsMod'),0);

					$users = explode(',',$p['users']);
					foreach($users AS &$curUser) {
						if($curUserID = FuncUsers::getUserID(trim($curUser))) {
							$this->modules['DB']->query("SELECT authID FROM ".TBLPFX."forums_auth WHERE forumID='$forumID' AND authType='".AUTH_TYPE_USER."' AND authID='$curUserID'");
							if($this->modules['DB']->getAffectedRows() == 0) {
								$this->modules['DB']->query("
									INSERT INTO
										".TBLPFX."forums_auth
									SET
										forumID='$forumID',
										authType='".AUTH_TYPE_USER."',
										authID='$curUserID',
										authViewForum='".$c['authViewForumMembers']."',
										authPostTopic='".$c['authPostTopicMembers']."',
										authPostReply='".$c['authPostReplyMembers']."',
										authPostPoll='".$c['authPostPollMembers']."',
										authEditPosts='".$c['authEditPostsMembers']."',
										authIsMod='".$c['authIsMod']."'
								");
							}
						}
					}
					Functions::myHeader(INDEXFILE."?action=AdminForums&mode=EditSpecialRights&forumID=$forumID&".MYSID);
				}

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'c'=>$c,
					'forumID'=>$forumID,
					'forumData'=>$forumData
				));

				$this->modules['Navbar']->addElements(
					array($this->modules['Language']->getString('Edit_forum'),INDEXFILE.'?action=AdminForums&amp;mode=EditForum&amp;forumID='.$forumID.'&amp;'.MYSID),
					array($this->modules['Language']->getString('Edit_special_rights'),INDEXFILE.'?action=AdminForums&amp;mode=EditSpecialRights&amp;forumID='.$forumID.'&amp;'.MYSID),
					array($this->modules['Language']->getString('Add_user_right'),INDEXFILE.'?action=AdminForums&amp;mode=AddUserRight&amp;forumID='.$forumID.'&amp;'.MYSID)
				);
				$this->modules['Template']->printPage('AdminForumsAddUserRight.tpl');
				break;

			case 'AddGroupRight':
				$forumID = isset($_GET['forumID']) ? $_GET['forumID'] : 0;
				if(!$forumData = FuncForums::getForumData($forumID)) die('Cannot load date: forum');

				$p = Functions::getSGValues($_POST['p'],array('groupID'),0);
				$c = Functions::getSGValues($_POST['c'],array('authViewForumMembers','authPostTopicMembers','authPostReplyMembers','authPostPollMembers','authEditPostsMembers'),0,Functions::addSlashes($forumData));
				$c += Functions::getSGValues($_POST['c'],array('authIsMod'),0);

				if(isset($_GET['doit'])) { // Falls Formular abgeschickt wurde
					$c = Functions::getSGValues($_POST['c'],array('authViewForumMembers','authPostTopicMembers','authPostReplyMembers','authPostPollMembers','authEditPostsMembers','authIsMod'),0);

					if($groupData = FuncGroups::getGroupData($p['groupID'])) {
						$this->modules['DB']->query("SELECT authID FROM ".TBLPFX."forums_auth WHERE forumID='$forumID' AND authType='".AUTH_TYPE_GROUP."' AND authID='".$groupData['groupID']."'");
						if($this->modules['DB']->getAffectedRows() == 0) {
							$this->modules['DB']->query("
								INSERT INTO
									".TBLPFX."forums_auth
								SET
									forumID='$forumID',
									authType='".AUTH_TYPE_GROUP."',
									authID='".$groupData['groupID']."',
									authViewForum='".$c['authViewForumMembers']."',
									authPostTopic='".$c['authPostTopicMembers']."',
									authPostReply='".$c['authPostReplyMembers']."',
									authPostPoll='".$c['authPostPollMembers']."',
									authEditPosts='".$c['authEditPostsMembers']."',
									authIsMod='".$c['authIsMod']."'
							");
						}
					}

					Functions::myHeader(INDEXFILE."?action=AdminForums&mode=EditSpecialRights&forumID=$forumID&".MYSID);
				}

				$this->modules['DB']->query("SELECT * FROM ".TBLPFX."groups WHERE groupID NOT IN (SELECT authID FROM ".TBLPFX."forums_auth WHERE authType='".AUTH_TYPE_GROUP."' AND forumID='$forumID')");
				$groupsData = $this->modules['DB']->raw2Array();

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'c'=>$c,
					'forumID'=>$forumID,
					'forumData'=>$forumData,
					'groupsData'=>$groupsData
				));

				$this->modules['Navbar']->addElements(
					array($this->modules['Language']->getString('Edit_forum'),INDEXFILE.'?action=AdminForums&amp;mode=EditForum&amp;forumID='.$forumID.'&amp;'.MYSID),
					array($this->modules['Language']->getString('Edit_special_rights'),INDEXFILE.'?action=AdminForums&amp;mode=EditSpecialRights&amp;forumID='.$forumID.'&amp;'.MYSID),
					array($this->modules['Language']->getString('Add_group_right'),INDEXFILE.'?action=AdminForums&amp;mode=AddGroupRight&amp;forumID='.$forumID.'&amp;'.MYSID)
				);
				$this->modules['Template']->printPage('AdminForumsAddGroupRight.tpl');
				break;

			case 'DeleteSpecialRight':
				$forumID = isset($_GET['forumID']) ? intval($_GET['forumID']) : 0;
				$authType = isset($_GET['authType']) ? intval($_GET['authType']) : 0;
				$authID = isset($_GET['authID']) ? intval($_GET['authID']) : 0;

				$this->modules['DB']->query("DELETE FROM ".TBLPFX."forums_auth WHERE forumID='$forumID' AND authType='$authType' AND authID='$authID'");

				Functions::myHeader(INDEXFILE."?action=AdminForums&mode=EditSpecialRights&forumID=$forumID&".MYSID);
				break;
		}

	}
}

?>