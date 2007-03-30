<?php

class ViewTopic extends ModuleTemplate {
	protected $RequiredModules = array(
		'Auth',
		'Cache',
		'Config',
		'DB',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
		$this->Modules['Language']->addFile('ViewTopic');

		$TopicID = isset($_GET['TopicID']) ? $_GET['TopicID'] : 0; // ID des Themas
		$PostID = isset($_GET['PostID']) ? $_GET['PostID'] : 0; // ID des Beitrags
		$Page = isset($_GET['Page']) ? $_GET['Page'] : 1; // Seite

		unset($TopicPostsCounter);

		// Thema und Seite eventuell ueber Beitrags-ID bestimmen
		if($TopicID == 0) {
			$this->Modules['DB']->query("SELECT TopicID FROM ".TBLPFX."posts WHERE PostID='$PostID'"); // Laedt eventuell die ID des Themas
			if($this->Modules['DB']->getAffectedRows() != 1) die('Kann Beitragsdaten nicht laden/Beitrag existiert nicht!'); // Falls nicht Meldung ausgeben
			list($TopicID) = $this->Modules['DB']->fetchArray(); // ID des Themas verfuegbar machen

			$this->Modules['DB']->query("SELECT PostID FROM ".TBLPFX."posts WHERE TopicID='$TopicID' ORDER BY PostTimestamp"); // Die IDs aller Beitraege des Themas laden
			$PostIDs = $this->Modules['DB']->Raw2FVArray(); // DB-Daten in Array umwandeln
			$TopicPostsCounter = count($PostIDs); // Anzahl der IDs (Beitraege)

			$Page = 1; // Standardseite ist Seite 1
			for($i = 0; $i < $TopicPostsCounter; $i++) {
				if($PostIDs[$i] == $PostID) break; // Falls die gewuenschte ID gefunden wurde kann die Schleife beendet werden (damit ist die Seite gefunden)
				if(($i + 1) % $this->Modules['Config']->getValue('posts_per_page') == 0) $Page++; // Falls die Anzahl der Beitraege pro Seite erreicht wurde, naechste Seite angeben
			}
		}

		// Thema- und Forumdaten laden
		if(!$TopicData = Functions::getTopicData($TopicID)) die('Kann Daten nicht laden: Thema'); // Themendaten laden
		if($TopicData['TopicMovedID'] != 0 && ($TopicData = Functions::getTopicData($TopicData['TopicMovedID'])) == FALSE) die('Thema wurde verschoben/kann neues Thema nicht laden!'); // Falls das Thema verschoben wurde und die neuen Daten nicht gefunden werden koennen
		elseif($TopicData['ForumID'] != 0 && ($ForumData = Functions::getForumData($TopicData['ForumID'])) == FALSE) die('Kann Daten nicht laden: Forum');

		$TopicID = &$TopicData['TopicID']; // ID des Themas, ist wichtig, falls es ein verschobenes Thema ist
		$ForumID = &$TopicData['ForumID']; // ID des Forums


		// User-IDs aller Moderatoren laden
		$ForumModIDs = $this->_loadForumModIDs($ForumID);


		// Authehtifizierung
		$AuthData = $this->_authenticateUser($ForumData);


		//update_topic_cookie($forum_id,$TopicID,time());

		if(!isset($_SESSION['TopicViews'][$TopicID])) { // Falls dieses Thema in dieser Session noch nicht besucht wurde...
			$this->Modules['DB']->query("UPDATE ".TBLPFX."topics SET TopicViewsCounter=TopicViewsCounter+1 WHERE TopicID='$TopicID'"); // ...Anzahl der Views um 1 erhoehen...
			$_SESSION['TopicViews'][$TopicID] = TRUE; // ...Und Thema in dieser Session vermerken
		}


		//
		// Seitenanzeige erstellen
		//
		if(!isset($TopicPostsCounter)) $TopicPostsCounter = Functions::getPostsCounter($TopicID); // Anzahl der Beitraege bestimmen (kann eventuell aus schon vorhandenen Daten geschehen)
		$PageListing = Functions::createPageListing($TopicPostsCounter,$this->Modules['Config']->getValue('posts_per_page'),$Page,"<a href=\"".INDEXFILE."?Action=ViewTopic&amp;TopicID=$TopicID&amp;Page=%1\$s&amp;".MYSID."\">%2\$s</a>"); // Die Seitenansicht erstellen
		$Start = $Page*$this->Modules['Config']->getValue('posts_per_page')-$this->Modules['Config']->getValue('posts_per_page'); // Startbeitrag


		//
		// Die Moderatorenwerkzeuge bestimmen
		//
		$ModTools = array(); // Beinhaltet spaeter pro Element eine Moderationsoption
		if($this->Modules['Auth']->getValue('UserIsAdmin') == 1 || $this->Modules['Auth']->getValue('UserIsSuperMod') == 1 || $TopicData['PosterID'] != 0 && USERID == $TopicData['PosterID'] && $AuthData['AuthEditPosts'] == 1 || $AuthData['AuthIsMod'] == 1) $ModTools[] = "<a href=\"".INDEXFILE."?Action=EditTopic&amp;Mode=Edit&amp;TopicID=$TopicID&amp;".MYSID."\">".$this->Modules['Language']->getString('Edit_topic').'</a>'; // Thema bearbeiten (duerfen auch User, die das Thema erstellt haben)
		if($this->Modules['Auth']->getValue('UserIsAdmin') == 1 || $this->Modules['Auth']->getValue('UserIsSupermod') == 1 || $AuthData['AuthIsMod'] == 1) {
			if($ForumID != 0) $ModTools[] = "<a href=\"".INDEXFILE."?action=edittopic&amp;mode=move&amp;topic_id=$TopicID&amp;".MYSID."\">".$this->Modules['Language']->getString('Move_topic').'</a>';
			$ModTools[] = "<a href=\"".INDEXFILE."?action=edittopic&amp;mode=delete&amp;topic_id=$TopicID&amp;".MYSID."\">".$this->Modules['Language']->getString('Delete_topic').'</a>';

			$Temp = ($TopicData['TopicIsPinned'] == 1) ? $this->Modules['Language']->getString('Mark_topic_unimportant') : $this->Modules['Language']->getString('Mark_topic_important');
			$ModTools[] = "<a href=\"".INDEXFILE."?action=edittopic&amp;mode=pinn&amp;topic_id=$TopicID&amp;".MYSID."\">".$Temp.'</a>';

			$Temp = ($TopicData['TopicStatus'] == TOPIC_STATUS_CLOSED) ? $this->Modules['Language']->getString('Open_topic') : $this->Modules['Language']->getString('Close_topic');
			$ModTools[] = "<a href=\"".INDEXFILE."?action=edittopic&amp;mode=openclose&amp;topic_id=$TopicID&amp;".MYSID."\">".$Temp.'</a>';
		}
		$ModTools = implode(' | ',$ModTools);


		//
		// Die Umfrage
		//
		$PollData = FALSE;
		if($TopicData['TopicHasPoll'] == 1) { // Falls fuer das Thema eine Umfrage angegeben wurde...
			$this->Modules['DB']->query("SELECT * FROM ".TBLPFX."polls WHERE TopicID='$TopicID' LIMIT 1"); // ...versuchen die Daten der Umfrage zu laden...
			if($PollData = $this->Modules['DB']->fetchArray()) { // ...und falls diese existiert...
				if($this->Modules['Auth']->isLoggedIn() == 1) { // Falls User eingeloggt ist
					$this->Modules['DB']->query("SELECT VoterID FROM ".TBLPFX."polls_votes WHERE PollID='".$PollData['PollID']."' AND VoterID='".USERID."'"); // Ueberpruefen, ob User shcon abgestimmt hat...
					if($this->Modules['DB']->getAffectedRows() == 0) // ...falls nicht...
						$poll_tpl = new Template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewtopic_poll_voting']); // ...Abstimmungsboxtemplate laden...
					else { // ...andernfalls...
						$info_text = $this->Modules['Language']->getString('poll_already_voted_info'); // ...und Infotext fuer "schon abgestimmt" erzeugen
					}
				}
				else { // Falls User nicht eingeloggt ist...
					$poll_tpl = new Template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewtopic_poll_results']); // ...Ergebnisboxtemplate laden...
					$info_text = $this->Modules['Language']->getString('poll_not_logged_in_info'); // ...und Infotext fuer "nicht eingeloggt" erzeugen
				}

				$this->Modules['DB']->query("SELECT OptionID,OptionTitle,OptionVotesCounter FROM ".TBLPFX."polls_options WHERE TopicID='$TopicID' ORDER BY OptionID"); // Die Auswahlmoeglichkeiten fuer die Umfrage laden
				while($akt_option = $this->Modules['DB']->fetchArray()) {
					$akt_fraction = ($poll_data['poll_votes'] == 0) ? 0 : round($akt_option['option_votes']/$poll_data['poll_votes'],2); // Der Anteil an Stimmen (0,xx)
					$akt_percent = $akt_fraction*100; // Stimmenanteil in Prozent
					$akt_votes = ($akt_option['option_votes'] == 1) ? $this->Modules['Language']->getString('one_vote') : sprintf($this->Modules['Language']->getString('x_votes'),$akt_option['option_votes']); // Anzahl der Stimmen
					$akt_checked = ($akt_option['option_id'] == 1) ? ' checked="checked"' : ''; // checked="checked" fuer den ersten Radiobutton erzeugen (damit auf jeden Fall was ausgewaehlt ist)
				}
			}
		}


		// Smilies laden
		$SmiliesData = array();
		if($ForumData['ForumEnableSmilies'] == 1 || $CONFIG['enable_sig'] == 1 && $CONFIG['allow_sig_smilies'] == 1)
			$SmiliesData = $this->Modules['Cache']->getSmiliesData('write');


		// Rangdaten laden
		$RanksData = $this->Modules['Cache']->getRanksData();


		$ParsedSignatures = array(); // Hier werden spaeter eventuell die geparsten Signaturen gespeichert um das nicht mehrfach machen zu muessen


		// Beitraege laden
		$PostsData = $this->_loadPostsData($TopicID,$Start);
		$PostsCounter = count($PostsData);

		//$akt_cell_class = $TCONFIG['cell_classes']['start_class'];
		for($i = 0; $i < $PostsCounter; $i++) {
			$curPost = &$PostsData[$i];

			$curEditedText = '';
			if($curPost['PostEditedCounter'] > 0 && $curPost['PostShowEditings'] == 1)
				$curEditedText = sprintf($this->Modules['Language']->getString('edited_post_text'),$curPost['PostEditedCounter'],$curPost['PostLastEditorID']);

			$Show = array(
				'EditButton'=>FALSE,
				'DeleteButton'=>FALSE
			);

			if($this->Modules['Auth']->isLoggedIn() == 1) {
				if($this->Modules['Auth']->getValue('UserIsAdmin') == 1 || $this->Modules['Auth']->getValue('UserIsSupermod') == 1 || $AuthData['AuthIsMod'] == 1 || (($ForumData['AuthMembersEditPosts'] == 1 && $AuthData['AuthEditPosts'] == 1 || $ForumData['AuthMembersEditPosts'] != 1 && $AuthData['AuthEditPosts'] == 1) && USER_ID == $curPost['PosterID'])) {
					$Show['EditButton'] = TRUE;
					if($curPost['PostID'] != $TopicData['TopicFirstPostID'])
						$Show['DeleteButton'] = TRUE;
				}
			}

			$curPostDateTime = Functions::toDateTime($curPost['PostTimestamp']);


			//
			// Angaben ueber den Beitragsersteller
			//
			$curPosterNick = $curPosterRankText = $curPosterRankPic = $curPosterIDText = $curPosterAvatar = '';
			if($curPost['PosterID'] == 0) {
				$curPosterNick = $curPost['PostGuestNick'];
				$curPosterRankText = $this->Modules['Language']->getString('Guest');
			} else {
				$curPosterNick = '<a href="'.INDEXFILE.'?Action=ViewProfile&amp;ProfileID='.$curPost['PosterID'].'&amp;'.MYSID.'">'.$curPost['PostPosterNick'].'</a>';
				$curPosterIDText = sprintf($this->Modules['Language']->getString('ID_x'),$curPost['PosterID']);


				//
				// Ueberpruefung, ob die Emailadresse des Users angezeigt werden soll
				// Zur Sicherheit wird es auch hier geloescht, damit der Templatebauer die Emailadresse
				// nicht doch aus Versehen anzeigen l?sst
				//
				if($curPost['PostPosterHideEmail'] == 1) $curPost['PosterEmail'] = '';


				//
				// Avatar
				//
				if($this->Modules['Config']->getValue('enable_avatars') == 1 && $curPost['PostPosterAvatarAddress'] != '')
					$curPosterAvatar = '<img src="'.$curPost['PostPosterAvatarAddress'].'" alt="" border="0"/>';


				//
				// Rangbild und Rangtext des Users festlegen
				//
				if($curPost['PostPosterRankID'] != 0) { // Falls der User einen speziellen Rang zugewiessen bekommen hat...
					$curPosterRankText = $RanksData[1][$curPost['PostPosterRankID']]['RankName']; // ...den Namen des Rang verwenden...
					$curPosterRankPic = $RanksData[1][$curPost['PostPosterRankID']]['RankGfx']; // ...und das Bild des Rangs verwenden
				}
				elseif($curPost['PostPosterIsAdmin'] == 1) { // Falls der User Administrator ist...
					$curPosterRankText = $this->Modules['Language']->getString('rank_administrator'); // ...seinen Rang darauf setzen...
					$curPosterRankPic = '<img src="'.$this->Modules['Config']->getValue('admin_rank_pic').'" alt="" border="0"/>'; // ...und das entsprechende Bild verwenden
				}
				elseif($curPost['PostPosterIsSupermod'] == 1) { // Falls der User Supermoderator ist...
					$curPosterRankText = $this->Modules['Language']->getString('rank_supermoderator'); // ...seinen Rang darauf setzen...
					$curPosterRankPic = '<img src="'.$this->Modules['Config']->getValue('supermod_rank_pic').'" alt="" border="0"/>'; // ...und das entsprechende Bild verwenden
				}
				elseif(isset($ForumModIDs[$curPost['PosterID']]) == TRUE) { // Falls der User Moderator ist...
					$curPosterRankText = $this->Modules['Language']->getString('rank_moderator'); // ...seinen Rang darauf setzen...
					$curPosterRankPic = '<img src="'.$this->Modules['Config']->getValue('mod_rank_pic').'" alt="" border="0"/>'; // ...und das entsprechende Bild verwenden
				}
				else { // Falls der User ein ganz normaler User ist...
					foreach($RanksData[0] AS $curRank) { // Die Rangliste durchlaufen
						if($curRank['RankPosts'] > $curPost['PostPosterPosts']) break;

						$curPosterRankText = $curRank['RankName']; // ...den Namen das Rangs verwenden...
						$curPosterRankPic = $curRank['RankGfx']; // ...und das Bild des Rangs verwenden
					}
				}
			}


			//
			// Den Beitrag entsprechend formatieren
			//
			$curPost['_PostText'] = $curPost['PostText'];
			if($curPost['PostEnableHtmlCode'] != 1 || $ForumID != 0 && $ForumData['ForumEnableHtmlCode'] != 1) $curPost['_PostText'] = Functions::HTMLSpecialChars($curPost['_PostText']);
			if($curPost['PostEnableSmilies'] == 1 && ($ForumID == 0 || $ForumData['ForumEnableSmilies'] == 1)) $curPost['_PostText'] = strtr($curPost['_PostText'],$SmiliesData);
			$curPost['_PostText'] = nl2br($curPost['_PostText']);
			//if($curPost['post_enable_urltransformation'] == 1  && ($forum_id == 0 || $ForumData['forum_enable_urltransformation'] == 1)) $curPost['post_text'] = transform_urls($curPost['post_text']);
			//if($curPost['post_enable_bbcode'] == 1 && ($forum_id == 0 || $ForumData['forum_enable_bbcode'] == 1)) $curPost['post_text'] = bbcode($curPost['post_text']);


			//
			// Die Signatur entsprechend formatieren
			//
			$curSignature = '';
			if($curPost['PostShowSignature'] == 1 && $this->Modules['Config']->getValue('enable_sig') == 1 && $curPost['PostPosterSignature'] != '') {
				if(!isset($ParsedSignatures[$curPost['PosterID']])) { // Falls die Signatur nicht schonmal formatiert wurde
					if($this->Modules['Config']->getValue('allow_sig_html') != 1) $ParsedSignatures[$curPost['PosterID']] = Functions::HTMLSpecialChars($curPost['PostPosterSignature']);
					if($this->Modules['Config']->getValue('allow_sig_smilies') == 1) $ParsedSignatures[$curPost['PosterID']] = strtr($ParsedSignatures[$curPost['PosterID']],$SmiliesData);
					$ParsedSignatures[$curPost['PosterID']] = nl2br($ParsedSignatures[$curPost['PosterID']]);
					//if($this->Modules['Config']->getValue('allow_sig_bbcode') == 1) $parsed_signatures[$curPost['PosterID']] = bbcode($parsed_signatures[$curPost['PosterID']]);
				}
				$curSignature = $ParsedSignatures[$curPost['PosterID']];
			}

			$curPost['_PostEditBoxText'] = Functions::HTMLSpecialChars($curPost['PostText']);
			$curPost['_PostSignature'] = $curSignature;
			$curPost['_PostEditedText'] = $curEditedText;
			$curPost['_PostPosterIDText'] = $curPosterIDText;
			$curPost['_PostDateTime'] = $curPostDateTime;
			$curPost['_PostPosterAvatar'] = $curPosterAvatar;
			$curPost['_PostPosterNick'] = $curPosterNick;
			$curPost['_PostPosterRankPic'] = $curPosterRankPic;
			$curPost['_PostPosterRankText'] = $curPosterRankText;
			$curPost['Show'] = $Show;
		}


		$SubscribeText = '';
		if($this->Modules['Auth']->isLoggedIn() == 1 && $this->Modules['Config']->getValue('enable_email_functions') == 1 && $this->Modules['Config']->getValue('enable_topic_subscription') == 1) {
			$this->Modules['DB']->query("SELECT UserID FROM ".TBLPFX."topics_subscriptions WHERE TopicID='$TopicID' AND UserID='".USERID."'");
			$SubscribeText = ($this->Modules['DB']->getAffectedRows() == 0) ? $this->Modules['Language']->getString('Subscribe_topic') : $this->Modules['Language']->getString('Unsubscribe_topic');
		}

		// TODO Navibar
		$this->Modules['Navbar']->addCategories($ForumData['CatID']);
		$this->Modules['Navbar']->addElements(
			array(Functions::HTMLSpecialChars($ForumData['ForumName']),INDEXFILE.'?Action=ViewForum&amp;ForumID='.$ForumID.'&amp;'.MYSID),
			array(Functions::HTMLSpecialChars($TopicData['TopicTitle']),INDEXFILE.'?Action=ViewTopic&amp;TopicID='.$TopicID.'&amp;'.MYSID)
		);

		// Seite ausgeben
		$this->Modules['Template']->assign(array(
			'PostsData'=>$PostsData,
			'PageListing'=>$PageListing,
			'ModTools'=>$ModTools,
			'TopicID'=>$TopicID,
			'ForumID'=>$ForumID,
			'SubscribeText'=>$SubscribeText
		));
		$this->Modules['PageParts']->printPage('ViewTopic.tpl');
	}

	protected function _loadForumModIDs($ForumID) {
		$ForumModIDs = array();

		if($ForumID != 0) {
			$this->Modules['DB']->query("
				SELECT
					AuthID
				FROM
					".TBLPFX."forums_auth
				WHERE
					AuthType='".AUTH_TYPE_USER."'
					AND ForumID='$ForumID'
					AND AuthIsMod='1'
			");
			while(list($curUserID) = $this->Modules['DB']->fetchArray())
				$ForumModIDs[intval($curUserID)] = TRUE;

			$this->Modules['DB']->query("
				SELECT
					t2.MemberID
				FROM
					".TBLPFX."forums_auth AS t1,
					".TBLPFX."groups_members AS t2
				WHERE
					t1.ForumID='$ForumID'
					AND t1.AuthIsMod=1
					AND t1.AuthType='".AUTH_TYPE_GROUP."'
					AND t2.GroupID=t1.AuthID
				GROUP BY
					t2.MemberID
			");
			while(list($curUserID) = $this->Modules['DB']->fetchArray())
				$ForumModIDs[intval($curUserID)] = TRUE;
		}

		return $ForumModIDs;
	}

	protected function _loadPostsData($TopicID,$Start) {
		$this->Modules['DB']->query("
			SELECT
				t1.*,
				t2.UserEmail AS PostPosterEmail,
				t2.UserNick AS PostPosterNick,
				t2.UserSignature AS PostPosterSignature,
				t2.UserIsAdmin AS PostPosterIsAdmin,
				t2.UserIsSupermod AS PostPosterIsSupermod,
				t2.UserPostsCounter AS PostPosterPosts,
				t2.RankID AS PostPosterRankID,
				t2.userAvatarAddress AS PostPosterAvatarAddress,
				t2.UserHideEmail AS PostPosterHideEmail,
				t2.UserReceiveEmails AS PostPosterReceiveEmails,
				t3.SmileyFileName AS PostSmileyFileName
			FROM ".TBLPFX."posts AS t1
			LEFT JOIN ".TBLPFX."users AS t2 ON t1.PosterID=t2.UserID
			LEFT JOIN ".TBLPFX."smilies AS t3 ON t3.SmileyID=t1.SmileyID
			WHERE t1.TopicID='$TopicID'
			ORDER BY t1.PostTimestamp LIMIT $Start,".$this->Modules['Config']->getValue('posts_per_page')
		);

		return $this->Modules['DB']->Raw2Array();
	}

	protected function _authenticateUser(&$ForumData) {
		$AuthData = Functions::getAuthData($ForumData,array('AuthViewForum','AuthPostTopic','AuthPostReply','AuthEditPosts','AuthIsMod'));
		if($AuthData['AuthViewForum'] != 1) {
			// TODO
			exit;
		}

		return $AuthData;
	}
}

?>