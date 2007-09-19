<?php

class EditTopic extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'DB',
		'Language',
		'Navbar',
		'Template'
	);

	public function executeMe() {
		$topicID = isset($_GET['topicID']) ? $_GET['topicID'] : 0;
		$mode = isset($_GET['mode']) ? $_GET['mode'] : 'edit';

		if($this->modules['Auth']->isLoggedIn() != 1) die('Access denied: not logged in');
		elseif(!$topicData = FuncTopics::getTopicData($topicID)) die('Cannot load data: topic');
		elseif($topicData['topicMovedID'] != 0) die('Cannot edit topic: moved topic');
		elseif(!$forumData = FuncForums::getForumData($topicData['forumID'])) die('Cannot load data: forum');

		$forumID = &$topicData['forumID'];

		$authData = Functions::getAuthData($forumData,array('authEditPosts','authIsMod'));

		$this->modules['Navbar']->addElements(
			array(Functions::HTMLSpecialChars($forumData['forumName']),INDEXFILE."?action=ViewForum&amp;forumID=$forumID&amp;".MYSID),
			array(Functions::HTMLSpecialChars($topicData['topicTitle']),INDEXFILE."?action=ViewTopic&amp;topicID=$topicID&amp;".MYSID)
		);

		$this->modules['Language']->addFile('EditTopic');

		if($mode == 'Edit') {
			if((USERID != $topicData['posterID'] || $authData['authEditPosts'] != 1) && $this->modules['Auth']->getValue('userIsAdmin') != 1 && $this->modules['Auth']->getValue('userIsSupermod') != 1 && $authData['authIsMod'] != 1) die('Access denied: unsufficient rights');

			$error = '';

			$p = Functions::getSGValues($_POST['p'],array('topicTitle','smileyID'),'',Functions::addSlashes($topicData));
			$c = array();

			if($topicData['topicHasPoll'] == 1) {
				$this->modules['Language']->addFile('Posting');

				$this->modules['DB']->query("SELECT * FROM ".TBLPFX."polls WHERE topicID='$topicID'");
				$pollData = $this->modules['DB']->fetchArray();
				$pollID = &$pollData['pollID'];

				$this->modules['DB']->query("SELECT * FROM ".TBLPFX."polls_options WHERE pollID='$pollID' ORDER BY optionID ASC");
				$optionsData = $this->modules['DB']->raw2Array();

				$p['optionsData'] = array();

				foreach($optionsData AS $curOption)
					$p['optionsData'][$curOption['optionID']] = isset($_POST['p']['optionsData'][$curOption['optionID']]) ? $_POST['p']['optionsData'][$curOption['optionID']] : Functions::addSlashes($curOption['optionTitle']);

				$p['pollDuration'] = isset($_POST['p']['pollDuration']) ? intval($_POST['p']['pollDuration']) : (($pollData['pollEndTimestamp']-$pollData['pollStartTimestamp'])/86400);

				$p += Functions::getSGValues($_POST['p'],array('pollTitle'),'',Functions::addSlashes($pollData));
				$c = Functions::getSGValues($pollData,array('pollGuestsVote','pollShowResultsAfterEnd','pollGuestsViewResults'),0);

				$this->modules['Template']->assign('optionsData',$optionsData);
			}

			if(isset($_GET['doit'])) {
				if($topicData['topicHasPoll'] == 1) {
					$optionTitleMissing = FALSE;
					foreach($p['optionsData'] AS $curOption) {
						if(trim($curOption) == '') {
							$optionTitleMissing = TRUE;
							break;
						}
					}
				}

				$c = Functions::getSGValues($_POST['c'],array('pollGuestsVote','pollShowResultsAfterEnd','pollGuestsViewResults'),0);

				if(trim($p['topicTitle']) == '') $error = $this->modules['Language']->getString('error_no_title');
				elseif($topicData['topicHasPoll'] == 1 && trim($p['pollTitle']) == '') $error = $this->modules['Language']->getString('error_poll_title_missing');
				elseif($topicData['topicHasPoll'] == 1 && $optionTitleMissing) $error = $this->modules['Language']->getString('error_poll_option_missing');
				elseif($topicData['topicHasPoll'] == 1 && $p['pollDuration'] <= 0) $error = $this->modules['Language']->getString('error_invalid_poll_duration');
				else {
					$this->modules['DB']->query("
						UPDATE
							".TBLPFX."topics
						SET
							topicTitle='".$p['topicTitle']."',
							smileyID='".$p['smileyID']."'
						WHERE
							topicID='$topicID'
					");

					$this->modules['DB']->query("
						UPDATE
							".TBLPFX."posts
						SET
							postTitle='".$p['topicTitle']."',
							smileyID='".$p['smileyID']."'
						WHERE
							postID='".$topicData['topicFirstPostID']."'
					");

					if($topicData['topicHasPoll'] == 1) {
						$this->modules['DB']->query("
							UPDATE
								".TBLPFX."polls
							SET
								pollTitle='".$p['pollTitle']."',
								pollEndTimestamp='".($pollData['pollStartTimestamp']+$p['pollDuration']*86400)."',
								pollGuestsVote='".$c['pollGuestsVote']."',
								pollGuestsViewResults='".$c['pollGuestsViewResults']."',
								pollShowResultsAfterEnd='".$c['pollShowResultsAfterEnd']."'
							WHERE
								pollID='$pollID'
						");

						foreach($p['optionsData'] AS $curKey => $curValue)
							$this->modules['DB']->query("UPDATE ".TBLPFX."polls_options SET optionTitle='".$curValue."' WHERE pollID='$pollID' AND optionID='$curKey'");
					}

					Functions::myHeader(INDEXFILE."?action=ViewTopic&topicID=$topicID&".MYSID);
				}
			}

			$postPicsBox = Functions::getPostPicsBox($p['smileyID']);

			$this->modules['Navbar']->addElement($this->modules['Language']->getString('Edit_topic'),'');

			$this->modules['Template']->assign(array(
				'p'=>Functions::HTMLSpecialChars(Functions::stripSlashes($p)),
				'c'=>$c,
				'error'=>$error,
				'postPicsBox'=>$postPicsBox,
				'topicID'=>$topicID,
				'topicData'=>$topicData
			));
			$this->modules['Template']->printPage('EditTopicEdit.tpl');
		}
		else {
			if($this->modules['Auth']->getValue('userIsAdmin') != 1 && $authData['authIsMod'] != 1 && $this->modules['Auth']->getValue('userIsSupermod') != 1) die('Access denied: insufficient rights');
			switch(@$_GET['mode']) {
				case 'Pinn':
					$this->modules['DB']->query("
						UPDATE
							".TBLPFX."topics
						SET
							topicIsPinned='".(($topicData['topicIsPinned'] == 1) ? 0 : 1)."'
						WHERE
							topicID='$topicID'
					");

					Functions::myHeader(INDEXFILE."?action=ViewTopic&topicID=$topicID&".MYSID);
				break;

				case 'OpenClose':
					$topicIsClosed = ($topicData['topicIsClosed'] == 1) ? 0 : 1;

					$this->modules['DB']->query("
						UPDATE
							".TBLPFX."topics
						SET
							topicIsClosed='$topicIsClosed'
						WHERE
							topicID='$topicID'
					");

					Functions::myHeader(INDEXFILE."?action=ViewTopic&topicID=$topicID&".MYSID);
				break;

				case 'Delete':
					$this->modules['DB']->query("SELECT postID FROM ".TBLPFX."posts WHERE topicID='$topicID'");
					$postIDs = $this->modules['DB']->raw2FVArray();

					$postsCounter = count($postIDs);

					$this->modules['DB']->query("SELECT COUNT(*) AS posterPostsCounter, posterID FROM ".TBLPFX."posts WHERE topicID='$topicID' GROUP BY posterID");
					$postsCounter = $this->modules['DB']->raw2Array();
					foreach($postsCounter AS $curCounter) {
						$this->modules['DB']->query("UPDATE ".TBLPFX."users SET userPostsCounter=userPostsCounter-".$curCounter['posterPostsCounter']." WHERE userID='".$curCounter['posterID']."'");
					}

					$this->modules['DB']->query("UPDATE ".TBLPFX."forums SET forumPostsCounter=forumPostsCounter-$postsCounter, forumTopicsCounter=forumTopicsCounter-1 WHERE forumID='$forumID'");
					$this->modules['DB']->query("DELETE FROM ".TBLPFX."topics WHERE topicID='$topicID'");
					$this->modules['DB']->query("DELETE FROM ".TBLPFX."topics WHERE topicMovedID='$topicID'");
					$this->modules['DB']->query("DELETE FROM ".TBLPFX."posts WHERE postID IN ('".implode("','",$postIDs)."')");
					$this->modules['DB']->query("DELETE FROM ".TBLPFX."topics_subscriptions WHERE topicID='$topicID'");

					if($topicData['topicHasPoll'] == 1) {
						$this->modules['DB']->query("SELECT pollID FROM ".TBLPFX."polls WHERE topicID='$topicID'");
						if($this->modules['DB']->getAffectedRows() == 1) {
							list($topicPollID) = $this->modules['DB']->fetchArray();

							$this->modules['DB']->query("DELETE FROM ".TBLPFX."polls WHERE pollID='$topicPollID'");
							$this->modules['DB']->query("DELETE FROM ".TBLPFX."polls_options WHERE pollID='$topicPollID'");
							$this->modules['DB']->query("DELETE FROM ".TBLPFX."polls_votes WHERE pollID='$topicPollID'");
						}
					}

					if(in_array($forumData['forumLastPostID'],$postIDs)) {
						// TODO: Neue Funktion
						update_forum_last_post($forumID);
					}
					Functions::myHeader(INDEXFILE."?action=ViewForum&forumID=$forumID&".MYSID);
				break;

				case 'Move':
					$p = Functions::getSGValues($_POST['p'],array('targetForumID'),0);
					$c = Functions::getSGValues($_POST['c'],array('createReference'),1);

					$error = '';

					$this->modules['Navbar']->addElement($this->modules['Language']->getString('Move_topic'),INDEXFILE."action=EditTopic&amp;topicID=$topicID&amp;mode=Move&amp;".MYSID);

					if(isset($_GET['doit'])) {
						if(!$targetForumData = FuncForums::getForumData($p['targetForumID'])) $error = $this->modules['Language']->getString('error_invalid_forum');
						else {
							$this->modules['DB']->query("UPDATE ".TBLPFX."topics SET forumID='".$p['targetForumID']."' WHERE topicID='$topicID'");
							$this->modules['DB']->query("UPDATE ".TBLPFX."posts SET forumID='".$p['targetForumID']."' WHERE topicID='$topicID'");

							if($topicData['topicHasPoll'] == 1)
								$this->modules['DB']->query("UPDATE ".TBLPFX."polls SET forumID='".$p['targetForumID']."' WHERE topicID='$topicID'");

							$this->modules['DB']->query("SELECT COUNT(*) AS topicPostsCounter FROM ".TBLPFX."posts WHERE topicID='$topicID'");
							list($topicPostsCounter) = $this->modules['DB']->fetchArray();

							$this->modules['DB']->query("UPDATE ".TBLPFX."forums SET forumTopicsCounter=forumTopicsCounter-1, forumPostsCounter=forumPostsCounter-$topicPostsCounter WHERE forumID='$forumID'");
							$this->modules['DB']->query("UPDATE ".TBLPFX."forums SET forumTopicsCounter=forumTopicsCounter+1, forumPostsCounter=forumPostsCounter+$topicPostsCounter WHERE forumID='".$p['targetForumID']."'");

							if($c['createReference'] == 1) {
								$slashedTopicData = Functions::addSlashes($topicData);
								$this->modules['DB']->query("
									INSERT INTO
										".TBLPFX."topics
									SET
										forumID='$forumID',
										posterID='".$slashedTopicData['posterID']."',
										topicIsClosed='".$slashedTopicData['topicIsClosed']."',
										topicIsPinned='".$slashedTopicData['topicIsPinned']."',
										smileyID='".$slashedTopicData['smileyID']."',
										topicRepliesCounter='".$slashedTopicData['topicRepliesCounter']."',
										topicViewsCounter='".$slashedTopicData['topicViewsCounter']."',
										topicHasPoll='".$slashedTopicData['topicHasPoll']."',
										topicFirstPostID='".$slashedTopicData['topicFirstPostID']."',
										topicLastPostID='".$slashedTopicData['topicLastPostID']."',
										topicMovedID='".$slashedTopicData['topicID']."',
										topicMovedTimestamp='".time()."',
										topicPostTimestamp='".$slashedTopicData['topicPostTimestamp']."',
										topicTitle='".$slashedTopicData['topicTitle']."',
										topicGuestNick='".$slashedTopicData['topicGuestNick']."'
								");
							}


							// TODO: Letzten Beitrag updaten
							//update_forum_last_post($forumID);
							//update_forum_last_post($p_target_forumID);

							$this->modules['Template']->printMessage('topic_moved',array(sprintf($this->modules['Language']->getString('message_link_click_here_moved_topic'),'<a href="'.INDEXFILE."?action=ViewTopic&amp;topicID=$topicID&amp;".MYSID.'">','</a>')));
							exit;
						}
					}


					//
					// Kategorie- und Forendaten laden
					//
					$catsData = FuncCats::getCatsData();
					$this->modules['DB']->query("SELECT forumID,forumName,catID FROM ".TBLPFX."forums WHERE forumID<>'$forumID'");
					$forumsData = $this->modules['DB']->raw2Array();


					//
					// Auswahlmenue fuer das Zielforum erstellen
					//
					$selectOptions = array();
					foreach($catsData AS $curCat) {
						$curPrefix = '';
						for($i = 1; $i < $curCat['catDepth']; $i++)
							$curPrefix .= '--';

						$selectOptions[] = array('',$curPrefix.' ('.$curCat['catName'].')');

						while(list($curKey,$curForum) = each($forumsData)) {
							if($curForum['catID'] == $curCat['catID']) {
								$selectOptions[] = array($curForum['forumID'],$curPrefix.'-- '.$curForum['forumName']);
								unset($forumsData[$curKey]);
							}
						}
						reset($forumsData);
					}

					// Foren ohne Kategorie an den Schluss haengen
					if(count($forumsData) > 0) {
						$selectOptions[] = array('','');

						foreach($forumsData AS $curForum)
							$selectOptions[] = array($curForum['forumID'],$curForum['forumName']);
					}

					$this->modules['Template']->assign(array(
						'c'=>$c,
						'p'=>$p,
						'error'=>$error,
						'selectOptions'=>$selectOptions,
						'topicID'=>$topicID
					));

					$this->modules['Template']->printPage('EditTopicMove.tpl');
					break;
			}
		}
	}
}

?>