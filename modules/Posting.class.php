<?php

class Posting extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'DB',
		'Cache',
		'Config',
		'Constants',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
		$mode = isset($_GET['mode']) ? $_GET['mode'] : '';
		if(in_array($mode,array('Topic','Reply','Edit')) == FALSE) $mode = 'Topic';

		// Alle angegebenen IDs bestimmen (normalerweise ist immer nur eine ID wichtig
		$forumID = isset($_GET['forumID']) ? intval($_GET['forumID']) : 0;
		$topicID = isset($_GET['topicID']) ? intval($_GET['topicID']) : 0;
		$postID = isset($_GET['postID']) ? intval($_GET['postID']) : 0;

		switch($mode) {
			case 'Edit':
				if(!$postData = Functions::getPostData($postID)) die('Kann Daten nicht laden: Beitrag');
				$topicID = &$postData['topicID'];
			case 'Reply':
				if(!$topicData = Functions::getTopicData($topicID)) die('Kann Daten nicht laden: Thema');
				$forumID = &$topicData['forumID'];
			case 'Topic':
				if(!$forumData = FuncForums::getForumData($forumID)) die('Kann Daten nicht laden: Forum');
				break;
		}

		$this->modules['Language']->addFile('Posting');

		$authData = $this->_authenticateUser($mode,$forumData);
		if($mode == 'Reply' && $topicData['topicIsClosed'] == 1 && $authData['authIsMod'] != 1 && $this->modules['Auth']->getValue('userIsSuperadmin') && $this->modules['Auth']->getValue('userIsSupermod')) {
			// TODO: Richtige Meldung
			die('Kein Zugriff/Thema geschlossen');
		}

		$error = '';

		//
		// Alle uebergebenen Daten laden
		//
		$p = array();

		$p['messageText'] = isset($_POST['p']['messageText']) ? $_POST['p']['messageText'] : (($mode == 'Edit') ? addslashes($postData['postText']) : '');
		$p['messageTitle'] = isset($_POST['p']['messageTitle']) ? $_POST['p']['messageTitle'] : (($mode == 'Edit') ? addslashes($postData['postTitle']) : (($mode == 'Reply') ? 'Re: '.addslashes($topicData['topicTitle']) : ''));
		$p['guestNick'] = isset($_POST['p']['guestNick']) ? $_POST['p']['guestNick'] : '';
		$p['smileyID'] = isset($_POST['p']['smileyID']) ? intval($_POST['p']['smileyID']) : (($mode == 'Edit') ? addslashes($postData['smileyID']) : '');
		$p['pollTitle'] = isset($_POST['p']['pollTitle']) ? $_POST['p']['pollTitle'] : '';
		$p['pollOptions'] = (isset($_POST['p']['pollOptions']) && is_array($_POST['p']['pollOptions'])) ? $_POST['p']['pollOptions'] : array();

		$p['pollDuration'] = isset($_POST['p']['pollDuration']) ? intval($_POST['p']['pollDuration']) : 1;

		$subscriptionStatus = ($mode == 'Reply' && Functions::getSubscriptionStatus(SUBSCRIPTION_TYPE_TOPIC,USERID,$topicID)) ? 1 : 0;

		$c['showEditings'] = ($mode == 'Edit') ? $postData['postShowEditings'] : 1;
		$c['enableURITransformation'] = ($mode == 'Edit') ? $postData['postEnableURITransformation'] : 1;
		$c['enableSmilies'] = ($mode == 'Edit') ? $postData['postEnableSmilies'] : 1;
		$c['showSignature'] = ($mode == 'Edit') ? $postData['postShowSignature'] : 1;
		$c['enableBBCode'] = ($mode == 'Edit') ? $postData['postEnableBBCode'] : 1;
		$c['enableHtmlCode'] = ($mode == 'Edit') ? $postData['postEnableHtmlCode'] : 0;

		$c['pinTopic'] = ($mode == 'Reply') ? $topicData['topicIsPinned'] : 0;
		$c['closeTopic'] = ($mode == 'Reply') ? $topicData['topicIsClosed'] : 0;
		$c['subscribeTopic'] = $subscriptionStatus;

		$c['pollGuestsVote'] = $c['pollShowResultsAfterEnd'] = 0;
		$c['pollGuestsViewResults'] = 1;

		if(isset($_GET['doit'])) {
			$c['enableBBCode'] = (isset($_POST['c']['enableBBCode']) && $forumData['forumEnableBBCode'] == 1) ? 1 : 0;
			$c['enableSmilies'] = (isset($_POST['c']['enableSmilies']) && $forumData['forumEnableSmilies'] == 1) ? 1 : 0;
			$c['enableHtmlCode'] = (isset($_POST['c']['enableHtmlCode']) && $forumData['forumEnableHtmlCode'] == 1) ? 1 : 0;
			$c['showSignature'] = (isset($_POST['c']['showSignature']) && $this->modules['Config']->getValue('enable_sig') == 1 && $this->modules['Auth']->isLoggedIn() == 1) ? 1 : 0;
			$c['subscribeTopic'] = isset($_POST['c']['subscribeTopic']) ? 1 : 0;
			$c['enableURITransformation'] = (isset($_POST['c']['enableURITransformation']) && $forumData['forumEnableURITransformation'] == 1) ? 1 : 0;
			$c['pollGuestsVote'] = isset($_POST['c']['pollGuestsVote']) ? 1 : 0;
			$c['pollGuestsViewResults'] = isset($_POST['c']['pollGuestsViewResults']) ? 1 : 0;
			$c['pollShowResultsAfterEnd'] = isset($_POST['c']['pollShowResultsAfterEnd']) ? 1 : 0;

			if($this->modules['Auth']->isLoggedIn() == 1 && ($this->modules['Auth']->getValue('userIsAdmin') == 1 || $this->modules['Auth']->getValue('userIsSupermod') == 1 || $authData['authIsMod'] == 1)) {
				$c['showEditings'] = isset($_POST['c']['showEditings']) ? 1 : 0;
				$c['pinTopic'] = isset($_POST['c']['pinTopic']) ? 1 : 0;
				$c['closeTopic'] = isset($_POST['c']['closeTopic']) ? 1 : 0;
			}

			if(!isset($_POST['showPreview'])) {
				if(trim($p['messageTitle']) == '') $error = $this->modules['Language']->getString('error_no_title');
				elseif(strlen($p['messageTitle']) > 255) $error = $this->modules['Language']->getString('error_title_too_long');
				elseif(trim($p['messageText']) == '') $error = $this->modules['Language']->getString('error_no_post');
				elseif($mode != 'Edit' && $this->modules['Auth']->isLoggedIn() != 1 && !Functions::verifyEmail($p['guestNick'])) $error = $this->modules['Language']->getString('error_invalid_name');
				elseif($mode != 'Edit' && $this->modules['Auth']->isLoggedIn() != 1 && !Functions::unifyNick($p['guestNick'])) $error = $this->modules['Language']->getString('error_existing_user_name');
				elseif($mode == 'Edit') {
					$this->modules['DB']->query("
						UPDATE
							".TBLPFX."posts
						SET
							smileyID='".$p['smileyID']."',
							postEnableBBCode='".$c['enableBBCode']."',
							postEnableSmilies='".$c['enableSmilies']."',
							postEnableHtmlCode='".$c['enableHtmlCode']."',
							postShowSignature='".$c['showSignature']."',
							postEnableURITransformation='".$c['enableURITransformation']."',
							postShowEditings='".$c['showEditings']."',
							postEditedCounter=postEditedCounter+1,
							postLastEditorNick='".$this->modules['Auth']->getValue('userNick')."',
							postTitle='".$p['messageTitle']."',
							postText='".$p['messageText']."'
						WHERE
							postID='$postID'
					");

					if($postID == $topicData['topicFirstPostID']) {
						$this->modules['DB']->query("
							UPDATE
								".TBLPFX."topics
							SET
								topicTitle='".$p['messageTitle']."',
								smileyID='".$p['smileyID']."'
							WHERE
								topicID='$topicID'
						");
					}

					Functions::myHeader(INDEXFILE."?p=$postID&".MYSID."#post$postID");
				}
				else {
					if(USERID != 0)
						$p['guestNick'] = '';

					if($mode == 'Topic') {
						$this->modules['DB']->query("
							INSERT INTO
								".TBLPFX."topics
							SET
								topicTitle='".$p['messageTitle']."',
								forumID='$forumID',
								topicIsClosed='".$c['closeTopic']."',
								topicIsPinned='".$c['pinTopic']."',
								posterID='".USERID."',
								smileyID='".$p['smileyID']."',
								topicPostTimestamp='".time()."',
								topicGuestNick='".$p['guestNick']."'
						");
						$topicID = $this->modules['DB']->getInsertID();

						// Eventuell die Umfrage zum Thema hinzufuegen
						if(($this->modules['Auth']->getValue('userIsAdmin') == 1 || $this->modules['Auth']->getValue('userIsSupermod') == 1 || $authData['authIsMod'] == 1 || $authData['authPostPoll'] == 1) && trim($p['pollTitle']) != '' && $p['pollDuration'] > 0) {
							while(list($curKey) = each($p['pollOptions'])) {
								if(trim($p['pollOptions'][$curKey]) == '')
									unset($p['pollOptions'][$curKey]);
							}
							reset($p['pollOptions']);

							if(count($p['pollOptions']) > 1) {
								$this->modules['DB']->query("
									INSERT INTO
										".TBLPFX."polls
									SET
										topicID='$topicID',
										posterID='".USERID."',
										pollTitle='".$p['pollTitle']."',
										pollGuestNick='".$p['guestNick']."',
										pollStartTimestamp='".time()."',
										pollEndTimestamp='".(time()+86400*$p['pollDuration'])."',
										pollGuestsVote='".$c['pollGuestsVote']."',
										pollGuestsViewResults='".$c['pollGuestsViewResults']."'
								");
								$pollID = $this->modules['DB']->getInsertID();

								$i = 1;
								foreach($p['pollOptions'] AS $curOption) {
									$this->modules['DB']->query("
										INSERT INTO
											".TBLPFX."polls_options
										SET
											pollID='$pollID',
											optionID='$i',
											optionTitle='$curOption'
									");
									$i++;
								}

								$this->modules['DB']->query("UPDATE ".TBLPFX."topics SET topicHasPoll='1' WHERE topicID='$topicID'");
							}
						}
					}

					// Den Beitrag in die Datenbank eintragen
					$this->modules['DB']->query("
						INSERT INTO
							".TBLPFX."posts
						SET
							topicID='$topicID',
							forumID='$forumID',
							posterID='".USERID."',
							smileyID='".$p['smileyID']."',
							postIP='".$_SERVER['REMOTE_ADDR']."',
							postEnableBBCode='".$c['enableBBCode']."',
							postEnableSmilies='".$c['enableSmilies']."',
							postEnableHtmlCode='".$c['enableHtmlCode']."',
							postShowSignature='".$c['showSignature']."',
							postEnableURITransformation='".$c['enableURITransformation']."',
							postShowEditings='".$c['showEditings']."',
							postTimestamp='".time()."',
							postTitle='".$p['messageTitle']."',
							postText='".$p['messageText']."',
							postGuestNick='".$p['guestNick']."'
					");
					$postID = $this->modules['DB']->getInsertID();

					// Verschiedene Dinge updaten (Beitragszahl, erster/letzter Beitrag usw.)
					if($mode == 'Topic') $this->modules['DB']->query("UPDATE ".TBLPFX."topics SET topicFirstPostID='$postID', topicLastPostID='$postID' WHERE topicID='$topicID'");
					else $this->modules['DB']->query("UPDATE ".TBLPFX."topics SET topicLastPostID='$postID', topicRepliesCounter=topicRepliesCounter+1, topicIsClosed='".$c['closeTopic']."', topicIsPinned='".$c['pinTopic']."' WHERE topicID='$topicID'");

					$this->modules['DB']->query("UPDATE ".TBLPFX."forums SET forumLastPostID='$postID', forumPostsCounter=forumPostsCounter+1, forumTopicsCounter=forumTopicsCounter+1 WHERE forumID='$forumID'");
					$this->modules['DB']->query("UPDATE ".TBLPFX."users SET userPostsCounter=userPostsCounter+1 WHERE userID='".USERID."'");

					// Eventuell Themenabo entfernen oder hinzufuegen
					if($mode != 'Edit' && $this->modules['Auth']->isLoggedIn() == 1 && $this->modules['Config']->getValue('enable_email_functions') == 1 && $this->modules['Config']->getValue('enable_topic_subscription') == 1 && $c['subscribeTopic'] != $subscriptionStatus) {
						if($c['subscribeTopic'] == 0) $this->modules['DB']->query("DELETE FROM ".TBLPFX."topics_subscriptions WHERE topicID='$topicID' AND userID='".USERID."'");
						else $this->modules['DB']->query("INSERT INTO ".TBLPFX."topics_subscriptions SET topicID='$topicID', UserID='".USERID."'");
					}
					Functions::myHeader(INDEXFILE."?t=$topicID&".MYSID);
				}
			}
		}

		$show = array();

		$show['enableSmilies'] = $forumData['forumEnableSmilies'] == 1;
		$show['showSignature'] = $this->modules['Config']->getValue('enable_sig') == 1 && $this->modules['Auth']->isLoggedIn() == 1;
		$show['enableBBCode'] = $forumData['forumEnableBBCode'] == 1;
		$show['enableURITransformation'] = $forumData['forumEnableURITransformation'];
		$show['enableHtmlCode'] = $forumData['forumEnableHtmlCode'] == 1;
		$show['subscribeTopic'] = $mode != 'Edit' && $this->modules['Auth']->isLoggedIn() == 1 && $this->modules['Config']->getValue('enable_email_functions') == 1 && $this->modules['Config']->getValue('enable_topic_subscription') == 1;
		$show['closeTopic'] = $mode != 'Edit' && $this->modules['Auth']->isLoggedIn() == 1 && ($this->modules['Auth']->getValue('userIsAdmin') == 1 || $this->modules['Auth']->getValue('userIsSupermod') == 1 || $authData['authIsMod'] == 1);
		$show['pinTopic'] = $mode != 'Edit' && $this->modules['Auth']->isLoggedIn() == 1 && ($this->modules['Auth']->getValue('userIsAdmin') == 1 || $this->modules['Auth']->getValue('userIsSupermod') == 1 || $authData['authIsMod'] == 1);
		$show['showEditings'] = $this->modules['Auth']->isLoggedIn() == 1 && ($this->modules['Auth']->getValue('userIsAdmin') == 1 || $this->modules['Auth']->getValue('userIsSupermod') == 1 || $authData['authIsMod'] == 1);
		$show['pollBox'] = $mode == 'Topic' && ($this->modules['Auth']->getValue('userIsAdmin') == 1 || $this->modules['Auth']->getValue('userIsSupermod') == 1 || $authData['authIsMod'] == 1 || $authData['authPostPoll'] == 1);
		$show['previewBox'] = isset($_POST['showPreview']);

		// Smilies und Beitragsbilder laden
		$smilies = array(); $smiliesBox = '';
		if($show['enableSmilies'] == TRUE) {
			$smilies = $this->modules['Cache']->getSmiliesData('write');
			$smiliesBox = Functions::getSmiliesBox();
		}
		$postPicsBox = Functions::getPostPicsBox($p['smileyID']);

		// Die Vorschau
		$previewData = array();
		if($show['previewBox'] == TRUE) {
			if($c['enableHtmlCode'] != 1 || $show['enableHtmlCode'] == FALSE) $previewData['messageText'] = Functions::HTMLSpecialChars($p['messageText']);
			if($c['enableSmilies'] == 1 && $show['enableSmilies'] == TRUE) $previewData['messageText'] = strtr($previewData['messageText'],$smilies);
			$previewData['messageText'] = nl2br($previewData['messageText']);
			if($c['enableBBCode'] == 1 && $show['enableBBCode'] == TRUE) $previewData['messageText'] = Functions::BBCode($previewData['messageText']);
			$previewData['messageTitle'] = Functions::HTMLSpecialChars($p['messageTitle']);
		}

		// Fuer die richtige Anzeige des Navileiste usw.
		$this->modules['Navbar']->addCategories($forumData['catID']);
		$this->modules['Navbar']->addElement(Functions::HTMLSpecialChars($forumData['forumName']),INDEXFILE.'?action=ViewForum&amp;forumID='.$forumID.'&amp;'.MYSID);

		if($mode == 'Topic') {
			$actionText = $this->modules['Language']->getString('Post_topic');
			$this->modules['Navbar']->addElement($this->modules['Language']->getString('Post_topic'),INDEXFILE.'?action=Posting&amp;mode=Topic&amp;forumID='.$forumID.'&amp;'.MYSID);
		}
		elseif($mode == 'Reply') {
			$actionText = $this->modules['Language']->getString('Post_reply');
			$this->modules['Navbar']->addElements(
				array(Functions::HTMLSpecialChars($topicData['topicTitle']),INDEXFILE.'?action=ViewTopic&amp;topicID='.$topicID.'&amp;'.MYSID),
				array($this->modules['Language']->getString('Post_reply'),INDEXFILE.'?action=Posting&amp;mode=Reply&amp;topicID='.$topicID.'&amp;'.MYSID)
			);
		}
		elseif($mode == 'Edit') {
			$actionText = $this->modules['Language']->getString('Edit_post');
			$this->modules['Navbar']->addElements(
				array(Functions::HTMLSpecialChars($topicData['topicTitle']),INDEXFILE.'?action=ViewTopic&amp;topicID='.$topicID.'&amp;'.MYSID),
				array($this->modules['Language']->getString('Edit_post'),INDEXFILE.'?action=Posting&amp;mode=Edit&amp;PostID='.$postID.'&amp;'.MYSID)
			);
		}

		//
		// Der Rest...
		//
		$title_max_chars = sprintf($this->modules['Language']->getString('Maximum_x_chars'),100);

		$this->modules['Template']->assign(array(
			'p'=>Functions::HTMLSpecialChars(Functions::StripSlashes($p)),
			'c'=>$c,
			'actionText'=>$actionText,
			'show'=>$show,
			'pollOptionsCounter'=>count($p['pollOptions']),
			'forumID'=>$forumID,
			'topicID'=>$topicID,
			'postID'=>$postID,
			'mode'=>$mode,
			'error'=>$error,
			'postPicsBox'=>$postPicsBox,
			'smiliesBox'=>$smiliesBox
		));
		$this->modules['PageParts']->printPage('Posting.tpl');
	}

	protected function _authenticateUser(&$mode,&$forumData) {
		$authData = Functions::getAuthData($forumData,array('authPostTopic','authPostReply','authPostPoll','authEditPosts','authIsMod'));
		if($mode == 'Reply' && $authData['authPostReply'] == 0 || $mode == 'Edit' && $authData['authEditPosts'] == 0 || $mode == 'Topic' && $authData['authPostTopic'] == 0) {
			// TODO
			die('Leider kein Zugriff');
		}

		return $authData;
	}
}

?>