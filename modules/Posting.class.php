<?php

class Posting extends ModuleTemplate {
	protected $RequiredModules = array(
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
		$Mode = isset($_GET['Mode']) ? $_GET['Mode'] : '';
		if(in_array($Mode,array('Topic','Reply','Edit')) == FALSE) $Mode = 'Topic';

		// Alle angegebenen IDs bestimmen (normalerweise ist immer nur eine ID wichtig
		$ForumID = isset($_GET['ForumID']) ? intval($_GET['ForumID']) : 0;
		$TopicID = isset($_GET['TopicID']) ? intval($_GET['TopicID']) : 0;
		$PostID = isset($_GET['PostID']) ? intval($_GET['PostID']) : 0;

		switch($Mode) {
			case 'Edit':
				if(!$PostData = Functions::getPostData($PostID)) die('Kann Daten nicht laden: Beitrag');
				$TopicID = &$PostData['TopicID'];
			case 'Reply':
				if(!$TopicData = Functions::getTopicData($TopicID)) die('Kann Daten nicht laden: Thema');
				$ForumID = &$TopicData['ForumID'];
			case 'Topic':
				if(!$ForumData = Functions::getForumData($ForumID)) die('Kann Daten nicht laden: Forum');
				break;
		}

		$this->Modules['Language']->addFile('Posting');

		$AuthData = $this->_authenticateUser($Mode,$ForumData);

		$Error = '';

		//
		// Alle uebergebenen Daten laden
		//
		$p = array();

		$p['MessageText'] = isset($_POST['p']['MessageText']) ? $_POST['p']['MessageText'] : (($Mode == 'Edit') ? addslashes($PostData['PostText']) : '');
		$p['MessageTitle'] = isset($_POST['p']['MessageTitle']) ? $_POST['p']['MessageTitle'] : (($Mode == 'Edit') ? addslashes($PostData['PostTitle']) : (($Mode == 'Reply') ? 'Re: '.addslashes($TopicData['TopicTitle']) : ''));
		$p['GuestNick'] = isset($_POST['p']['GuestNick']) ? $_POST['p']['GuestNick'] : '';
		$p['SmileyID'] = isset($_POST['p']['SmileyID']) ? intval($_POST['p']['SmileyID']) : 0;
		$p['PollTitle'] = isset($_POST['p']['PollTitle']) ? $_POST['p']['PollTitle'] : '';
		$p['PollOptions'] = (isset($_POST['p']['PollOptions']) == TRUE && is_array($_POST['p']['PollOptions']) == TRUE) ? $_POST['p']['PollOptions'] : array();

		$SubscriptionStatus = ($Mode == 'Reply' && Functions::getSubscriptionStatus(SUBSCRIPTION_TYPE_TOPIC,USERID,$TopicID) == TRUE) ? 1 : 0;

		$c['ShowEditings'] = ($Mode == 'Edit') ? $PostData['PostShowEditings'] : 1;
		$c['EnableURITransformation'] = ($Mode == 'Edit') ? $PostData['PostEnableURITransformation'] : 1;
		$c['EnableSmilies'] = ($Mode == 'Edit') ? $PostData['PostEnableSmilies'] : 1;
		$c['ShowSignature'] = ($Mode == 'Edit') ? $PostData['PostShowSignature'] : 1;
		$c['EnableBBCode'] = ($Mode == 'Edit') ? $PostData['PostEnableBBCode'] : 1;
		$c['EnableHtmlCode'] = ($Mode == 'Edit') ? $PostData['PostEnableHtmlCode'] : 0;

		$c['PinTopic'] = ($Mode == 'Reply') ? $TopicData['TopicIsPinned'] : 0;
		$c['CloseTopic'] = ($Mode == 'Reply') ? $TopicData['TopicStatus'] : 0;
		$c['SubscribeTopic'] = $SubscriptionStatus;

		if(isset($_GET['Doit'])) {
			$c['EnableBBCode'] = (isset($_POST['c']['EnableBBCode']) && $ForumData['ForumEnableBBCode'] == 1) ? 1 : 0;
			$c['EnableSmilies'] = (isset($_POST['c']['EnableSmilies']) && $ForumData['ForumEnableSmilies'] == 1) ? 1 : 0;
			$c['EnableHtmlCode'] = (isset($_POST['c']['EnableHtmlCode']) && $ForumData['ForumEnableHtmlCode'] == 1) ? 1 : 0;
			$c['ShowSignature'] = (isset($_POST['c']['ShowSignature']) && $this->Modules['Config']->getValue('enable_sig') == 1 && $this->Modules['Auth']->isLoggedIn() == 1) ? 1 : 0;
			$c['SubscribeTopic'] = isset($_POST['c']['SubscribeTopic']) ? 1 : 0;
			$c['EnableURITransformation'] = (isset($_POST['c']['EnableURITransformation']) && $ForumData['ForumEnableURITransformation'] == 1) ? 1 : 0;

			if($this->Modules['Auth']->isLoggedIn() == 1 && ($this->Modules['Auth']->getValue('UserIsAdmin') == 1 || $this->Modules['Auth']->getValue('UserIsSupermod') == 1 || $AuthData['AuthIsMod'] == 1)) {
				$c['ShowEditings'] = isset($_POST['c']['ShowEditings']) ? 1 : 0;
				$c['PinTopic'] = isset($_POST['c']['PinTopic']) ? 1 : 0;
				$c['CloseTopic'] = isset($_POST['c']['CloseTopic']) ? 1 : 0;
			}

			if(!isset($_POST['ShowPreview'])) {
				if(trim($p['MessageTitle']) == '') $Error = $this->Modules['Language']->getString('error_no_title');
				elseif(strlen($p['MessageTitle']) > 100) $Error = $this->Modules['Language']->getString('error_title_too_long');
				elseif(trim($p['MessageText']) == '') $Error = $this->Modules['Language']->getString('error_no_post');
				elseif($Mode != 'Edit' && $this->Modules['Auth']->isLoggedIn() != 1 && Functions::verifyEmail($p['GuestNick']) == FALSE) $Error = $this->Modules['Language']->getString('error_invalid_name');
				elseif($Mode != 'Edit' && $this->Modules['Auth']->isLoggedIn() != 1 && Functions::unifyNick($p['GuestNick']) == FALSE) $Error = $this->Modules['Language']->getString('error_existing_user_name');
				elseif($Mode == 'Edit') {
					$this->Modules['DB']->query("
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
							PostLastEditorNick='".$this->Modules['Auth']->getValue('UserNick')."',
							PostTitle='".$p['MessageTitle']."',
							PostText='".$p['MessageText']."'
						WHERE
							PostID='$PostID'
					");
					//Functions::myHeader("index.php?p=$PostID&".MYSID."#post$PostID"); exit;
					Functions::myHeader(INDEXFILE."?Action=ViewTopic&PostID=$PostID&".MYSID."#Post$PostID"); exit;
				}
				else {
					if(USERID != 0)
						$p['GuestNick'] = '';

					if($Mode == 'Topic') {
						// Das Thema in die Datenbank eintragen
						$this->Modules['DB']->query("
							INSERT INTO
								".TBLPFX."topics
							SET
								TopicTitle='".$p['MessageTitle']."',
								ForumID='$ForumID',
								TopicStatus='".$c['CloseTopic']."',
								TopicIsPinned='".$c['PinTopic']."',
								PosterID='".USERID."',
								SmileyID='$SmileyID',
								TopicTimestamp='".time()."',
								TopicGuestNick='".$p['GuestNick']."'
						");
						$TopicID = $this->Modules['DB']->getInsertID();

						// Eventuell die Umfrage zum Thema hinzufuegen
						if(($this->Modules['Auth']->getValue('UserIsAdmin') == 1 || $this->Modules['Auth']->getValue('UserIsSupermod') == 1 || $AuthData['AuthIsMod'] == 1 || $AuthData['AuthPostPoll'] == 1) && trim($p['PollTitle']) != '') {
							while(list($curKey) = each($p['PollOptions'])) {
								if(trim($p['PollOptions'][$curKey]) == '')
									unset($p['PollOptions'][$curKey]);
							}
							reset($p['PollOptions']);

							if(count($p['PollOptions']) > 1) {
								$this->Modules['DB']->query("
									INSERT INTO
										".TBLPFX."polls
									SET
										TopicID='$TopicID',
										PosterID='".USERID.",
										PollTitle='".$p['PollTitle']."',
										PollGuestNick='".$p['GuestNick']."'
								");

								$i = 1;
								foreach($p['PollOptions'] AS $curOption) {
									$this->Modules['DB']->query("
										INSERT INTO
											".TBLPFX."polls_options
										SET
											TopicID='$TopicID',
											OptionID='$i',
											OptionTitle='$curOption'
									");
									$i++;
								}

								$this->Modules['DB']->query("UPDATE ".TBLPFX."topics SET TopicHasPoll='1' WHERE TopicID='$TopicID'");
							}
						}
					}

					// Den Beitrag in die Datenbank eintragen
					$this->Modules['DB']->query("
						INSERT INTO
							".TBLPFX."posts
						SET
							TopicID='$TopicID',
							ForumID='$ForumID',
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
					$PostID = $this->Modules['DB']->getInsertID();

					// Verschiedene Dinge updaten (Beitragszahl, erster/letzter Beitrag usw.)
					if($Mode == 'Topic') $this->Modules['DB']->query("UPDATE ".TBLPFX."topics SET TopicFirstPostID='$PostID', TopicLastPostID='$PostID' WHERE TopicID='$TopicID'");
					else $this->Modules['DB']->query("UPDATE ".TBLPFX."topics SET TopicLastPostID='$PostID', TopicRepliesCounter=TopicRepliesCounter+1, TopicStatus='".$c['CloseTopic']."', TopicIsPinned='".$c['PinTopic']."' WHERE TopicID='$TopicID'");

					$this->Modules['DB']->query("UPDATE ".TBLPFX."forums SET ForumLastPostID='$PostID', ForumPostsCounter=ForumPostsCounter+1, ForumTopicsCounter=ForumTopicsCounter+1 WHERE ForumID='$ForumID'");
					$this->Modules['DB']->query("UPDATE ".TBLPFX."users SET UserPostsCounter=UserPostsCounter+1 WHERE UserID='".USERID."'");

					// Eventuell Themenabo entfernen oder hinzufuegen
					if($Mode != 'Edit' && $this->Modules['Auth']->isLoggedIn() == 1 && $this->Modules['Config']->getValue('enable_email_functions') == 1 && $this->Modules['Config']->getValue('enable_topic_subscription') == 1 && $c['SubscribeTopic'] != $SubscriptionStatus) {
						if($c['SubscribeTopic'] == 0) $this->Modules['DB']->query("DELETE FROM ".TBLPFX."topics_subscriptions WHERE TopicID='$TopicID' AND UserID='$USER_ID'");
						else $this->Modules['DB']->query("INSERT INTO ".TBLPFX."topics_subscriptions SET TopicID='$TopicID', UserID='".USERID."'");
					}
					//Functions::myHeader("index.php?t=$TopicID&".MYSID); exit;
					exit;
				}
			}
		}

		$Show = array();

		$Show['EnableSmilies'] = $ForumData['ForumEnableSmilies'] == 1;
		$Show['ShowSignature'] = $this->Modules['Config']->getValue('enable_sig') == 1 && $this->Modules['Auth']->isLoggedIn() == 1;
		$Show['EnableBBCode'] = $ForumData['ForumEnableBBCode'] == 1;
		$Show['EnableURITransformation'] = $ForumData['ForumEnableURITransformation'];
		$Show['EnableHtmlCode'] = $ForumData['ForumEnableHtmlCode'] == 1;
		$Show['SubscribeTopic'] = $Mode != 'Edit' && $this->Modules['Auth']->isLoggedIn() == 1 && $this->Modules['Config']->getValue('enable_email_functions') == 1 && $this->Modules['Config']->getValue('enable_topic_subscription') == 1;
		$Show['CloseTopic'] = $Mode != 'Edit' && $this->Modules['Auth']->isLoggedIn() == 1 && ($this->Modules['Auth']->getValue('UserIsAdmin') == 1 || $this->Modules['Auth']->getValue('UserIsSupermod') == 1 || $AuthData['AuthIsMod'] == 1);
		$Show['PinTopic'] = $Mode != 'Edit' && $this->Modules['Auth']->isLoggedIn() == 1 && ($this->Modules['Auth']->getValue('UserIsAdmin') == 1 || $this->Modules['Auth']->getValue('UserIsSupermod') == 1 || $AuthData['AuthIsMod'] == 1);
		$Show['ShowEditings'] = $this->Modules['Auth']->isLoggedIn() == 1 && ($this->Modules['Auth']->getValue('UserIsAdmin') == 1 || $this->Modules['Auth']->getValue('UserIsSupermod') == 1 || $AuthData['auth_is_mod'] == 1);
		$Show['PollBox'] = $Mode == 'Topic' && ($this->Modules['Auth']->getValue('UserIsAdmin') == 1 || $this->Modules['Auth']->getValue('UserIsSupermod') == 1 || $AuthData['AuthIsMod'] == 1 || $AuthData['AuthPostPoll'] == 1);
		$Show['PreviewBox'] = isset($_POST['ShowPreview']);

		// Smilies und Beitragsbilder laden
		$Smilies = array(); $SmiliesBox = '';
		if($Show['EnableSmilies'] == TRUE) {
			$Smilies = $this->Modules['Cache']->getSmiliesData('write');
			$SmiliesBox = Functions::getSmiliesBox();
		}
		$PPicsBox = Functions::getPPicsBox($p['SmileyID']);

		// Die Vorschau
		$PreviewData = array();
		if($Show['PreviewBox'] == TRUE) {
			if($c['EnableHtmlCode'] != 1 || $Show['EnableHtmlCode'] == FALSE) $PreviewData['MessageText'] = Functions::HTMLSpecialChars($p['MessageText']);
			if($c['EnableSmilies'] == 1 && $Show['EnableSmilies'] == TRUE) $PreviewData['MessageText'] = strtr($PreviewData['MessageText'],$Smilies);
			$PreviewData['MessageText'] = nl2br($PreviewData['MessageText']);
			if($c['EnableBBCode'] == 1 && $Show['EnableBBCode'] == TRUE) $PreviewData['MessageText'] = Functions::BBCode($PreviewData['MessageText']);
			$PreviewData['MessageTitle'] = Functions::HTMLSpecialChars($p['MessageTitle']);
		}

		// Fuer die richtige Anzeige des Navileiste usw.
		$this->Modules['Navbar']->addCategories($ForumData['CatID']);
		$this->Modules['Navbar']->addElement(Functions::HTMLSpecialChars($ForumData['ForumName']),INDEXFILE.'?Action=ViewForum&amp;ForumID='.$ForumID.'&amp;'.MYSID);

		if($Mode == 'Topic') {
			$ActionText = $this->Modules['Language']->getString('Post_topic');
			$this->Modules['Navbar']->addElement($this->Modules['Language']->getString('Post_topic'),INDEXFILE.'?Action=Posting&amp;Mode=Topic&amp;ForumID='.$ForumID.'&amp;'.MYSID);
		}
		elseif($Mode == 'Reply') {
			$ActionText = $this->Modules['Language']->getString('Post_reply');
			$this->Modules['Navbar']->addElements(
				array(Functions::HTMLSpecialChars($TopicData['TopicTitle']),INDEXFILE.'?Action=ViewTopic&amp;TopicID='.$TopicID.'&amp;'.MYSID),
				array($this->Modules['Language']->getString('Post_reply'),INDEXFILE.'?Action=Posting&amp;Mode=Reply&amp;TopicID='.$TopicID.'&amp;'.MYSID)
			);
		}
		elseif($Mode == 'Edit') {
			$ActionText = $this->Modules['Language']->getString('Edit_post');
			$this->Modules['Navbar']->addElements(
				array(Functions::HTMLSpecialChars($TopicData['TopicTitle']),INDEXFILE.'?Action=ViewTopic&amp;TopicID='.$TopicID.'&amp;'.MYSID),
				array($this->Modules['Language']->getString('Edit_post'),INDEXFILE.'?Action=Posting&amp;Mode=Edit&amp;PostID='.$PostID.'&amp;'.MYSID)
			);
		}


		//
		// Der Rest...
		//
		$title_max_chars = sprintf($this->Modules['Language']->getString('Maximum_x_chars'),100);

		$this->Modules['PageParts']->printStdHeader();

		$this->Modules['Template']->assign(array(
			'p'=>Functions::HTMLSpecialChars(Functions::StripSlashes($p)),
			'c'=>$c,
			'ActionText'=>$ActionText,
			'Show'=>$Show,
			'PollOptionsCounter'=>count($p['PollOptions']),
			'ForumID'=>$ForumID,
			'TopicID'=>$TopicID,
			'PostID'=>$PostID,
			'Mode'=>$Mode,
			'Error'=>$Error,
			'PPicsBox'=>$PPicsBox,
			'SmiliesBox'=>$SmiliesBox
		));
		$this->Modules['Template']->display('Posting.tpl');

		$this->Modules['PageParts']->printStdTail();
	}

	protected function _authenticateUser(&$Mode,&$ForumData) {
		$AuthData = Functions::getAuthData($ForumData,array('AuthPostTopic','AuthPostReply','AuthPostPoll','AuthEditPosts','AuthIsMod'));
		if($Mode == 'Reply' && $AuthData['AuthPostReply'] == 0 || $Mode == 'Edit' && $AuthData['AuthEditPosts'] == 0 || $Mode == 'Topic' && $AuthData['AuthPostTopic'] == 0) {
			// TODO
			die('Leider kein Zugriff');
		}

		return $AuthData;
	}
}

?>