<?php

class Posting extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'DB',
		'Cache',
		'Config',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
		/**
		* Diese posting.php vereinigt inzwischen die posttopic.php, postreply.php, den edit-Teil der editpost.php
		* Damit wirkt das Ganze auf den ersten Blick
		* vielleicht etwas durcheinander, aber im Prinzip ist alles doch ziemlich logisch :-)
		*/

		// Einen falschen Modus ausschliessen
		$mode = isset($_GET['Mode']) ? $_GET['Mode'] : '';
		if(in_array($mode,array('Topic','Reply','Edit')) == FALSE) $mode = 'Topic';

		// Alle angegebenen IDs bestimmen (normalerweise ist immer nur eine ID wichtig
		$forumID = isset($_GET['ForumID']) ? intval($_GET['ForumID']) : 0;
		$topicID = isset($_GET['TopicID']) ? intval($_GET['TopicID']) : 0;
		$postID = isset($_GET['PostID']) ? intval($_GET['PostID']) : 0;

		switch($mode) {
			case 'Edit':
				if(!$postData = Functions::getPostData($postID)) die('Kann Daten nicht laden: Beitrag');
				$topicID = &$postData['TopicID'];
			case 'Reply':
				if(!$topicData = Functions::getTopicData($topicID)) die('Kann Daten nicht laden: Thema');
				$forumID = &$topicData['ForumID'];
			case 'Topic':
				if(!$forumData = Functions::getForumData($forumID)) die('Kann Daten nicht laden: Forum');
				break;
		}

		$this->modules['Language']->addFile('Posting');

		$authData = $this->_authenticateUser($mode,$forumData);

		$error = '';

		//
		// Alle uebergebenen Daten laden
		//
		$p = array();

		$p['MessageText'] = isset($_POST['p']['MessageText']) ? $_POST['p']['MessageText'] : (($mode == 'Edit') ? addslashes($postData['PostText']) : '');
		$p['MessageTitle'] = isset($_POST['p']['MessageTitle']) ? $_POST['p']['MessageTitle'] : (($mode == 'Edit') ? addslashes($postData['PostTitle']) : (($mode == 'Reply') ? 'Re: '.addslashes($topicData['TopicTitle']) : ''));
		$p['GuestNick'] = isset($_POST['p']['GuestNick']) ? $_POST['p']['GuestNick'] : '';
		$p['SmileyID'] = isset($_POST['p']['SmileyID']) ? intval($_POST['p']['SmileyID']) : 0;
		$p['PollTitle'] = isset($_POST['p']['PollTitle']) ? $_POST['p']['PollTitle'] : '';
		$p['PollOptions'] = (isset($_POST['p']['PollOptions']) == TRUE && is_array($_POST['p']['PollOptions']) == TRUE) ? $_POST['p']['PollOptions'] : array();

		$subscriptionStatus = ($mode == 'Reply' && Functions::getSubscriptionStatus(SUBSCRIPTION_TYPE_TOPIC,USERID,$topicID) == TRUE) ? 1 : 0;

		$c['ShowEditings'] = ($mode == 'Edit') ? $postData['PostShowEditings'] : 1;
		$c['EnableURITransformation'] = ($mode == 'Edit') ? $postData['PostEnableURITransformation'] : 1;
		$c['EnableSmilies'] = ($mode == 'Edit') ? $postData['PostEnableSmilies'] : 1;
		$c['ShowSignature'] = ($mode == 'Edit') ? $postData['PostShowSignature'] : 1;
		$c['EnableBBCode'] = ($mode == 'Edit') ? $postData['PostEnableBBCode'] : 1;
		$c['EnableHtmlCode'] = ($mode == 'Edit') ? $postData['PostEnableHtmlCode'] : 0;

		$c['PinTopic'] = ($mode == 'Reply') ? $topicData['TopicIsPinned'] : 0;
		$c['CloseTopic'] = ($mode == 'Reply') ? $topicData['TopicStatus'] : 0;
		$c['SubscribeTopic'] = $subscriptionStatus;

		if(isset($_GET['Doit'])) {
			$c['EnableBBCode'] = (isset($_POST['c']['EnableBBCode']) && $forumData['ForumEnableBBCode'] == 1) ? 1 : 0;
			$c['EnableSmilies'] = (isset($_POST['c']['EnableSmilies']) && $forumData['ForumEnableSmilies'] == 1) ? 1 : 0;
			$c['EnableHtmlCode'] = (isset($_POST['c']['EnableHtmlCode']) && $forumData['ForumEnableHtmlCode'] == 1) ? 1 : 0;
			$c['ShowSignature'] = (isset($_POST['c']['ShowSignature']) && $this->modules['Config']->getValue('enable_sig') == 1 && $this->modules['Auth']->isLoggedIn() == 1) ? 1 : 0;
			$c['SubscribeTopic'] = isset($_POST['c']['SubscribeTopic']) ? 1 : 0;
			$c['EnableURITransformation'] = (isset($_POST['c']['EnableURITransformation']) && $forumData['ForumEnableURITransformation'] == 1) ? 1 : 0;

			if($this->modules['Auth']->isLoggedIn() == 1 && ($this->modules['Auth']->getValue('UserIsAdmin') == 1 || $this->modules['Auth']->getValue('UserIsSupermod') == 1 || $authData['AuthIsMod'] == 1)) {
				$c['ShowEditings'] = isset($_POST['c']['ShowEditings']) ? 1 : 0;
				$c['PinTopic'] = isset($_POST['c']['PinTopic']) ? 1 : 0;
				$c['CloseTopic'] = isset($_POST['c']['CloseTopic']) ? 1 : 0;
			}

			if(!isset($_POST['ShowPreview'])) {
				if(trim($p['MessageTitle']) == '') $error = $this->modules['Language']->getString('error_no_title');
				elseif(strlen($p['MessageTitle']) > 100) $error = $this->modules['Language']->getString('error_title_too_long');
				elseif(trim($p['MessageText']) == '') $error = $this->modules['Language']->getString('error_no_post');
				elseif($mode != 'Edit' && $this->modules['Auth']->isLoggedIn() != 1 && Functions::verifyEmail($p['GuestNick']) == FALSE) $error = $this->modules['Language']->getString('error_invalid_name');
				elseif($mode != 'Edit' && $this->modules['Auth']->isLoggedIn() != 1 && Functions::unifyNick($p['GuestNick']) == FALSE) $error = $this->modules['Language']->getString('error_existing_user_name');
				elseif($mode == 'Edit') {
					$this->modules['DB']->query("
						UPDATE
							".TBLPFX."posts
						SET
							SmileyID='".$p['SmileyID']."',
							PostEnableBBCode='".$c['EnableBBCode']."',
							PostEnableSmilies='".$c['EnableSmilies']."',
							PostEnableHtmlCode='".$c['EnableHtmlCode']."',
							PostShowSignature='".$c['ShowSignature']."',
							PostEnableURITransformation='".$c['EnableURITransformation']."',
							PostShowEditings='".$c['ShowEditings']."',
							PostEditedCounter=PostEditedCounter+1,
							PostLastEditorNick='".$this->modules['Auth']->getValue('UserNick')."',
							PostTitle='".$p['MessageTitle']."',
							PostText='".$p['MessageText']."'
						WHERE
							PostID='$postID'
					");
					//Functions::myHeader("index.php?p=$postID&".MYSID."#post$postID"); exit;
					Functions::myHeader(INDEXFILE."?Action=ViewTopic&PostID=$postID&".MYSID."#Post$postID"); exit;
				}
				else {
					if(USERID != 0)
						$p['GuestNick'] = '';

					if($mode == 'Topic') {
						// Das Thema in die Datenbank eintragen
						$this->modules['DB']->query("
							INSERT INTO
								".TBLPFX."topics
							SET
								TopicTitle='".$p['MessageTitle']."',
								ForumID='$forumID',
								TopicStatus='".$c['CloseTopic']."',
								TopicIsPinned='".$c['PinTopic']."',
								PosterID='".USERID."',
								SmileyID='$smileyID',
								TopicTimestamp='".time()."',
								TopicGuestNick='".$p['GuestNick']."'
						");
						$topicID = $this->modules['DB']->getInsertID();

						// Eventuell die Umfrage zum Thema hinzufuegen
						if(($this->modules['Auth']->getValue('UserIsAdmin') == 1 || $this->modules['Auth']->getValue('UserIsSupermod') == 1 || $authData['AuthIsMod'] == 1 || $authData['AuthPostPoll'] == 1) && trim($p['PollTitle']) != '') {
							while(list($curKey) = each($p['PollOptions'])) {
								if(trim($p['PollOptions'][$curKey]) == '')
									unset($p['PollOptions'][$curKey]);
							}
							reset($p['PollOptions']);

							if(count($p['PollOptions']) > 1) {
								$this->modules['DB']->query("
									INSERT INTO
										".TBLPFX."polls
									SET
										TopicID='$topicID',
										PosterID='".USERID.",
										PollTitle='".$p['PollTitle']."',
										PollGuestNick='".$p['GuestNick']."'
								");

								$i = 1;
								foreach($p['PollOptions'] AS $curOption) {
									$this->modules['DB']->query("
										INSERT INTO
											".TBLPFX."polls_options
										SET
											TopicID='$topicID',
											OptionID='$i',
											OptionTitle='$curOption'
									");
									$i++;
								}

								$this->modules['DB']->query("UPDATE ".TBLPFX."topics SET TopicHasPoll='1' WHERE TopicID='$topicID'");
							}
						}
					}

					// Den Beitrag in die Datenbank eintragen
					$this->modules['DB']->query("
						INSERT INTO
							".TBLPFX."posts
						SET
							TopicID='$topicID',
							ForumID='$forumID',
							PosterID='".USERID."',
							SmileyID='".$p['SmileyID']."',
							PostIP='".$_SERVER['REMOTE_ADDR']."',
							PostEnableBBCode='".$c['EnableBBCode']."',
							PostEnableSmilies='".$c['EnableSmilies']."',
							PostEnableHtmlCode='".$c['EnableHtmlCode']."',
							PostShowSignature='".$c['ShowSignature']."',
							PostEnableURITransformation='".$c['EnableURITransformation']."',
							PostShowEditings='".$c['ShowEditings']."',
							PostTimestamp='".time()."',
							PostTitle='".$p['MessageTitle']."',
							PostText='".$p['MessageText']."',
							PostGuestNick='".$p['GuestNick']."'
					");
					$postID = $this->modules['DB']->getInsertID();

					// Verschiedene Dinge updaten (Beitragszahl, erster/letzter Beitrag usw.)
					if($mode == 'Topic') $this->modules['DB']->query("UPDATE ".TBLPFX."topics SET TopicFirstPostID='$postID', TopicLastPostID='$postID' WHERE TopicID='$topicID'");
					else $this->modules['DB']->query("UPDATE ".TBLPFX."topics SET TopicLastPostID='$postID', TopicRepliesCounter=TopicRepliesCounter+1, TopicStatus='".$c['CloseTopic']."', TopicIsPinned='".$c['PinTopic']."' WHERE TopicID='$topicID'");

					$this->modules['DB']->query("UPDATE ".TBLPFX."forums SET ForumLastPostID='$postID', ForumPostsCounter=ForumPostsCounter+1, ForumTopicsCounter=ForumTopicsCounter+1 WHERE ForumID='$forumID'");
					$this->modules['DB']->query("UPDATE ".TBLPFX."users SET UserPostsCounter=UserPostsCounter+1 WHERE UserID='".USERID."'");

					// Eventuell Themenabo entfernen oder hinzufuegen
					if($mode != 'Edit' && $this->modules['Auth']->isLoggedIn() == 1 && $this->modules['Config']->getValue('enable_email_functions') == 1 && $this->modules['Config']->getValue('enable_topic_subscription') == 1 && $c['SubscribeTopic'] != $subscriptionStatus) {
						if($c['SubscribeTopic'] == 0) $this->modules['DB']->query("DELETE FROM ".TBLPFX."topics_subscriptions WHERE TopicID='$topicID' AND UserID='$uSER_ID'");
						else $this->modules['DB']->query("INSERT INTO ".TBLPFX."topics_subscriptions SET TopicID='$topicID', UserID='".USERID."'");
					}
					//Functions::myHeader("index.php?t=$topicID&".MYSID); exit;
					exit;
				}
			}
		}

		$show = array();

		$show['EnableSmilies'] = $forumData['ForumEnableSmilies'] == 1;
		$show['ShowSignature'] = $this->modules['Config']->getValue('enable_sig') == 1 && $this->modules['Auth']->isLoggedIn() == 1;
		$show['EnableBBCode'] = $forumData['ForumEnableBBCode'] == 1;
		$show['EnableURITransformation'] = $forumData['ForumEnableURITransformation'];
		$show['EnableHtmlCode'] = $forumData['ForumEnableHtmlCode'] == 1;
		$show['SubscribeTopic'] = $mode != 'Edit' && $this->modules['Auth']->isLoggedIn() == 1 && $this->modules['Config']->getValue('enable_email_functions') == 1 && $this->modules['Config']->getValue('enable_topic_subscription') == 1;
		$show['CloseTopic'] = $mode != 'Edit' && $this->modules['Auth']->isLoggedIn() == 1 && ($this->modules['Auth']->getValue('UserIsAdmin') == 1 || $this->modules['Auth']->getValue('UserIsSupermod') == 1 || $authData['AuthIsMod'] == 1);
		$show['PinTopic'] = $mode != 'Edit' && $this->modules['Auth']->isLoggedIn() == 1 && ($this->modules['Auth']->getValue('UserIsAdmin') == 1 || $this->modules['Auth']->getValue('UserIsSupermod') == 1 || $authData['AuthIsMod'] == 1);
		$show['ShowEditings'] = $this->modules['Auth']->isLoggedIn() == 1 && ($this->modules['Auth']->getValue('UserIsAdmin') == 1 || $this->modules['Auth']->getValue('UserIsSupermod') == 1 || $authData['auth_is_mod'] == 1);
		$show['PollBox'] = $mode == 'Topic' && ($this->modules['Auth']->getValue('UserIsAdmin') == 1 || $this->modules['Auth']->getValue('UserIsSupermod') == 1 || $authData['AuthIsMod'] == 1 || $authData['AuthPostPoll'] == 1);
		$show['PreviewBox'] = isset($_POST['ShowPreview']);

		// Smilies und Beitragsbilder laden
		$smilies = array(); $smiliesBox = '';
		if($show['EnableSmilies'] == TRUE) {
			$smilies = $this->modules['Cache']->getSmiliesData('write');
			$smiliesBox = Functions::getSmiliesBox();
		}
		$pPicsBox = Functions::getPPicsBox($p['SmileyID']);

		// Die Vorschau
		$previewData = array();
		if($show['PreviewBox'] == TRUE) {
			if($c['EnableHtmlCode'] != 1 || $show['EnableHtmlCode'] == FALSE) $previewData['MessageText'] = Functions::HTMLSpecialChars($p['MessageText']);
			if($c['EnableSmilies'] == 1 && $show['EnableSmilies'] == TRUE) $previewData['MessageText'] = strtr($previewData['MessageText'],$smilies);
			$previewData['MessageText'] = nl2br($previewData['MessageText']);
			if($c['EnableBBCode'] == 1 && $show['EnableBBCode'] == TRUE) $previewData['MessageText'] = Functions::BBCode($previewData['MessageText']);
			$previewData['MessageTitle'] = Functions::HTMLSpecialChars($p['MessageTitle']);
		}

		// Fuer die richtige Anzeige des Navileiste usw.
		$this->modules['Navbar']->addCategories($forumData['CatID']);
		$this->modules['Navbar']->addElement(Functions::HTMLSpecialChars($forumData['ForumName']),INDEXFILE.'?Action=ViewForum&amp;ForumID='.$forumID.'&amp;'.MYSID);

		if($mode == 'Topic') {
			$actionText = $this->modules['Language']->getString('Post_topic');
			$this->modules['Navbar']->addElement($this->modules['Language']->getString('Post_topic'),INDEXFILE.'?Action=Posting&amp;Mode=Topic&amp;ForumID='.$forumID.'&amp;'.MYSID);
		}
		elseif($mode == 'Reply') {
			$actionText = $this->modules['Language']->getString('Post_reply');
			$this->modules['Navbar']->addElements(
				array(Functions::HTMLSpecialChars($topicData['TopicTitle']),INDEXFILE.'?Action=ViewTopic&amp;TopicID='.$topicID.'&amp;'.MYSID),
				array($this->modules['Language']->getString('Post_reply'),INDEXFILE.'?Action=Posting&amp;Mode=Reply&amp;TopicID='.$topicID.'&amp;'.MYSID)
			);
		}
		elseif($mode == 'Edit') {
			$actionText = $this->modules['Language']->getString('Edit_post');
			$this->modules['Navbar']->addElements(
				array(Functions::HTMLSpecialChars($topicData['TopicTitle']),INDEXFILE.'?Action=ViewTopic&amp;TopicID='.$topicID.'&amp;'.MYSID),
				array($this->modules['Language']->getString('Edit_post'),INDEXFILE.'?Action=Posting&amp;Mode=Edit&amp;PostID='.$postID.'&amp;'.MYSID)
			);
		}


		//
		// Der Rest...
		//
		$title_max_chars = sprintf($this->modules['Language']->getString('Maximum_x_chars'),100);

		$this->modules['PageParts']->printStdHeader();

		$this->modules['Template']->assign(array(
			'p'=>Functions::HTMLSpecialChars(Functions::StripSlashes($p)),
			'c'=>$c,
			'ActionText'=>$actionText,
			'Show'=>$show,
			'PollOptionsCounter'=>count($p['PollOptions']),
			'ForumID'=>$forumID,
			'TopicID'=>$topicID,
			'PostID'=>$postID,
			'Mode'=>$mode,
			'Error'=>$error,
			'PPicsBox'=>$pPicsBox,
			'SmiliesBox'=>$smiliesBox
		));
		$this->modules['Template']->display('Posting.tpl');

		$this->modules['PageParts']->printStdTail();
	}

	protected function _authenticateUser(&$mode,&$forumData) {
		$authData = Functions::getAuthData($forumData,array('AuthPostTopic','AuthPostReply','AuthPostPoll','AuthEditPosts','AuthIsMod'));
		if($mode == 'Reply' && $authData['AuthPostReply'] == 0 || $mode == 'Edit' && $authData['AuthEditPosts'] == 0 || $mode == 'Topic' && $authData['AuthPostTopic'] == 0) {
			// TODO
			die('Leider kein Zugriff');
		}

		return $authData;
	}
}

?>