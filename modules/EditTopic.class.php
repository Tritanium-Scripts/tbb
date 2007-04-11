<?php

class EditTopic extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'DB',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
		$topicID = isset($_GET['topicID']) ? $_GET['topicID'] : 0;
		$mode = isset($_GET['mode']) ? $_GET['mode'] : 'edit';

		if($this->modules['Auth']->isLoggedIn() != 1) die('Access denied: not logged in');
		elseif(!$topicData = Functions::getTopicData($topicID)) die('Cannot load data: topic');
		elseif($topicData['topicMovedID'] != 0) die('Cannot edit topic: moved topic');
		elseif(!$forumData = Functions::getForumData($topicData['forumID'])) die('Cannot load data: forum');

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

			if(isset($_GET['doit'])) {
				if(trim($p['topicTitle']) == '') $error = $this->modules['Language']->getString('error_no_title');
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

					Functions::myHeader(INDEXFILE."?action=ViewTopic&topicID=$topicID&".MYSID);
				}
			}

			$postPicsBox = Functions::getPostPicsBox($p['smileyID']);

			$this->modules['Navbar']->addElement($this->modules['Language']->getString('Edit_topic'),'');

			$this->modules['Template']->assign(array(
				'p'=>Functions::HTMLSpecialChars(Functions::stripSlashes($p)),
				'error'=>$error,
				'postPicsBox'=>$postPicsBox,
				'topicID'=>$topicID
			));
			$this->modules['PageParts']->printPage('EditTopicEdit.tpl');
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
					// TODO: Daran denken, eventuelle Referenzen wegen Themenverschiebungen zu loeschen
					$topic_posts_ids = array();
					$this->modules['DB']->query("SELECT post_id FROM ".TBLPFX."posts WHERE topicID='$topicID'");
					while(list($akt_post_id) = $this->modules['DB']->fetch_array())
						$topic_posts_ids[] = $akt_post_id;

					$topic_posts_counter = count($topic_posts_ids);

					$this->modules['DB']->query("SELECT COUNT(*) AS poster_posts_counter, poster_id FROM ".TBLPFX."posts WHERE topicID='$topicID' GROUP BY poster_id");
					$DB_data = $this->modules['DB']->raw2array();
					while(list(,$akt_data) = each($DB_data)) {
						$this->modules['DB']->query("UPDATE ".TBLPFX."users SET user_posts=user_posts-".$akt_data['poster_posts_counter']." WHERE user_id='".$akt_data['poster_id']."'");
					}

					$this->modules['DB']->query("UPDATE ".TBLPFX."forums SET forum_posts_counter=forum_posts_counter-$topic_posts_counter, forum_topics_counter=forum_topics_counter-1 WHERE forumID='$forumID'");
					$this->modules['DB']->query("DELETE FROM ".TBLPFX."topics WHERE topicID='$topicID'");
					$this->modules['DB']->query("DELETE FROM ".TBLPFX."posts WHERE post_id IN ('".implode("','",$topic_posts_ids)."')");
					$this->modules['DB']->query("DELETE FROM ".TBLPFX."topics_subscriptions WHERE topicID='$topicID'");

					if($topicData['topic_poll'] == 1) {
						$this->modules['DB']->query("SELECT poll_id FROM ".TBLPFX."polls WHERE topicID='$topicID'");
						if($this->modules['DB']->affected_rows == 1) {
							list($topic_poll_id) = $this->modules['DB']->fetch_array();

							$this->modules['DB']->query("DELETE FROM ".TBLPFX."polls WHERE poll_id='$topic_poll_id'");
							$this->modules['DB']->query("DELETE FROM ".TBLPFX."polls_options WHERE poll_id='$topic_poll_id'");
							$this->modules['DB']->query("DELETE FROM ".TBLPFX."polls_votes WHERE poll_id='$topic_poll_id'");
						}
					}

					if($forumID != 0) {
						if(in_array($forumData['forum_last_post_id'],$topic_posts_ids) == TRUE) update_forum_last_post($forumID);
						header("Location: index.php?action=viewforum&forumID=$forumID&$MYSID"); exit;
					}
					header("Location: index.php?$MYSID"); exit;
				break;

				case 'Move':
					$p = Functions::getSGValues($_POST['p'],array('targetForumID'),0);
					$c = Functions::getSGValues($_POST['c'],array('createReference'),1);

					$error = '';

					$this->modules['Navbar']->addElement($this->modules['Language']->getString('Move_topic'),INDEXFILE."action=EditTopic&amp;topicID=$topicID&amp;mode=Move&amp;".MYSID);

					if(isset($_GET['doit'])) {
						if(!$targetForumData = Functions::getForumData($p['targetForumID'])) $error = $this->modules['Language']->getString('error_invalid_forum');
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

							$this->modules['PageParts']->printMessage('topic_moved',array(sprintf($this->modules['Language']->getString('message_link_click_here_moved_topic'),'<a href="'.INDEXFILE."?action=ViewTopic&amp;topicID=$topicID&amp;".MYSID.'">','</a>')));
							exit;
						}
					}


					//
					// Kategorie- und Forendaten laden
					//
					$catsData = Functions::getCatsData();
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

					$this->modules['PageParts']->printPage('EditTopicMove.tpl');
					break;
			}
		}
	}
}

?>