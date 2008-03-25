<?php

class ViewTopic extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'BBCode',
		'Cache',
		'Constants',
		'Config',
		'DB',
		'Language',
		'Navbar',
		'Template'
	);

	public function executeMe() {
		$this->modules['Language']->addFile('ViewTopic');

		$topicID = isset($_GET['topicID']) ? $_GET['topicID'] : 0; // ID des Themas
		$postID = isset($_GET['postID']) ? $_GET['postID'] : 0; // ID des Beitrags
		$page = isset($_GET['page']) ? $_GET['page'] : 1; // Seite

		unset($topicPostsCounter);

		// Thema und Seite eventuell ueber Beitrags-ID bestimmen
		if($topicID == 0) {
            $this->modules['DB']->queryParams('SELECT "topicID" FROM '.TBLPFX.'posts WHERE "postID"=$1', array($postID)); // Laedt eventuell die ID des Themas
			if($this->modules['DB']->numRows() != 1) die('Kann Beitragsdaten nicht laden/Beitrag existiert nicht!'); // Falls nicht Meldung ausgeben
			list($topicID) = $this->modules['DB']->fetchArray(); // ID des Themas verfuegbar machen

            $this->modules['DB']->queryParams('SELECT "postID" FROM '.TBLPFX.'posts WHERE "topicID"=$1 ORDER BY "postID"', array($topicID)); // Die IDs aller Beitraege des Themas laden
			$postIDs = $this->modules['DB']->raw2FVArray(); // DB-Daten in Array umwandeln
			$topicPostsCounter = count($postIDs); // Anzahl der IDs (Beitraege)

			$page = 1; // Standardseite ist Seite 1
			for($i = 0; $i < $topicPostsCounter; $i++) {
				if($postIDs[$i] == $postID) break; // Falls die gewuenschte ID gefunden wurde kann die Schleife beendet werden (damit ist die Seite gefunden)
				if(($i + 1) % $this->modules['Config']->getValue('posts_per_page') == 0) $page++; // Falls die Anzahl der Beitraege pro Seite erreicht wurde, naechste Seite angeben
			}
		}

		// Thema- und Forumdaten laden
		if(!$topicData = FuncTopics::getTopicData($topicID)) die('Kann Daten nicht laden: Thema'); // Themendaten laden
		if($topicData['topicMovedID'] != 0 && ($topicData = FuncTopics::getTopicData($topicData['topicMovedID'])) == FALSE) die('Thema wurde verschoben/kann neues Thema nicht laden!'); // Falls das Thema verschoben wurde und die neuen Daten nicht gefunden werden koennen
		elseif($topicData['forumID'] != 0 && ($forumData = FuncForums::getForumData($topicData['forumID'])) == FALSE) die('Kann Daten nicht laden: Forum');

		$topicID = &$topicData['topicID']; // ID des Themas, ist wichtig, falls es ein verschobenes Thema ist
		$forumID = &$topicData['forumID']; // ID des Forums


		// User-IDs aller Moderatoren laden
		$forumModIDs = $this->_loadForumModIDs($forumID);


		// Authentifizierung
		$authData = $this->_authenticateUser($forumData);


		// Topic visit
		$_SESSION['topicVisits'][$topicID] = time();

		$tmp = array();
		foreach($_SESSION['topicVisits'] AS $tmpTopicID => $visitTime)
			$tmp[] = $tmpTopicID.'.'.$visitTime;

		Functions::set1YearCookie('topicVisits',implode(',',$tmp));


		//
		// Seitenanzeige erstellen
		//
		if(!isset($topicPostsCounter)) $topicPostsCounter = Functions::getPostsCounter($topicID); // Anzahl der Beitraege bestimmen (kann eventuell aus schon vorhandenen Daten geschehen)
		$pageListing = Functions::createPageListing($topicPostsCounter,$this->modules['Config']->getValue('posts_per_page'),$page,"<a href=\"".INDEXFILE."?action=ViewTopic&amp;topicID=$topicID&amp;page=%1\$s&amp;".MYSID."\">%2\$s</a>"); // Die Seitenansicht erstellen
		$start = $page*$this->modules['Config']->getValue('posts_per_page')-$this->modules['Config']->getValue('posts_per_page'); // Startbeitrag


		//
		// Die Moderatorenwerkzeuge bestimmen
		//
		$modTools = array(); // Beinhaltet spaeter pro Element eine Moderationsoption
		if($this->modules['Auth']->getValue('userIsAdmin') == 1 || $this->modules['Auth']->getValue('userIsSuperMod') == 1 || $topicData['posterID'] != 0 && USERID == $topicData['posterID'] && $authData['authEditPosts'] == 1 || $authData['authIsMod'] == 1)
			$modTools[] = "<a href=\"".INDEXFILE."?action=EditTopic&amp;mode=Edit&amp;topicID=$topicID&amp;".MYSID."\">".$this->modules['Language']->getString('Edit_topic').'</a>'; // Thema bearbeiten (duerfen auch User, die das Thema erstellt haben)
		if($this->modules['Auth']->getValue('userIsAdmin') == 1 || $this->modules['Auth']->getValue('userIsSupermod') == 1 || $authData['authIsMod'] == 1) {
			$modTools[] = "<a href=\"".INDEXFILE."?action=EditTopic&amp;mode=Move&amp;topicID=$topicID&amp;".MYSID."\">".$this->modules['Language']->getString('Move_topic').'</a>';
			$modTools[] = "<a href=\"".INDEXFILE."?action=EditTopic&amp;mode=Delete&amp;topicID=$topicID&amp;".MYSID."\">".$this->modules['Language']->getString('Delete_topic').'</a>';

			$temp = ($topicData['topicIsPinned'] == 1) ? $this->modules['Language']->getString('Mark_topic_unimportant') : $this->modules['Language']->getString('Mark_topic_important');
			$modTools[] = '<a href="'.INDEXFILE."?action=EditTopic&amp;mode=Pinn&amp;topicID=$topicID&amp;".MYSID.'">'.$temp.'</a>';

			$temp = ($topicData['topicIsClosed'] == 1) ? $this->modules['Language']->getString('Open_topic') : $this->modules['Language']->getString('Close_topic');
			$modTools[] = "<a href=\"".INDEXFILE."?action=EditTopic&amp;mode=OpenClose&amp;topicID=$topicID&amp;".MYSID."\">".$temp.'</a>';
		}
		$modTools = implode(' | ',$modTools);


		/**
		 * Poll
		 */
		if($topicData['topicHasPoll'] == 1) {
			// Get poll data
            $this->modules['DB']->queryParams('SELECT * FROM '.TBLPFX.'polls WHERE "topicID"=$1', array($topicID));
			$pollData = $this->modules['DB']->fetchArray();
			$pollHasEnded = (time()-$pollData['pollEndTimestamp']) > 0;

			// Check if user already voted
			if($this->modules['Auth']->isLoggedIn() == 1) {
                $this->modules['DB']->queryParams('SELECT "voterID" FROM '.TBLPFX.'polls_votes WHERE "pollID"=$1 AND "voterID"=$2', array($pollData['pollID'], USERID));
				$userAlreadyVoted = $this->modules['DB']->getAffectedRows() != 0;
			}
			else {
				$userAlreadyVoted = isset($_SESSION['pollVotes']) && in_array($pollData['pollID'],explode(',',$_SESSION['pollVotes'])) || isset($_COOKIE['pollVotes']) && in_array($pollData['pollID'],explode(',',$_COOKIE['pollVotes']));
			}

			// Get poll options
            $this->modules['DB']->queryParams('SELECT "optionID", "optionTitle", "optionVotesCounter" FROM '.TBLPFX.'polls_options WHERE "pollID"=$1 ORDER BY "optionID"', array($pollData['pollID']));
			$pollOptionsData = $this->modules['DB']->raw2Array();

			foreach($pollOptionsData AS &$curOption) {
				$curFraction = ($pollData['pollVotesCounter'] == 0) ? 0 : round($curOption['optionVotesCounter']/$pollData['pollVotesCounter'],2);
				$curOption['_optionPercent'] = $curFraction*100;
				$curOption['_optionVotesCounterText'] = ($curOption['optionVotesCounter'] == 1) ? $this->modules['Language']->getString('one_vote') : sprintf($this->modules['Language']->getString('x_votes'),$curOption['optionVotesCounter']);
			}

			$this->modules['Template']->assign(array(
				'pollData'=>$pollData,
				'pollOptionsData'=>$pollOptionsData,
				'userAlreadyVoted'=>$userAlreadyVoted,
				'pollHasEnded'=>$pollHasEnded
			));
		}


		// Smilies laden
		$smiliesData = array();
		if($forumData['forumEnableSmilies'] == 1 || $this->Modules['Config']->getValue('enable_sig') == 1 && $this->Modules['Config']->getValue('allow_sig_smilies') == 1)
			$smiliesData = $this->modules['Cache']->getSmiliesData('write');


		// Rangdaten laden
		$ranksData = $this->modules['Cache']->getRanksData();


		$parsedSignatures = array(); // Hier werden spaeter eventuell die geparsten Signaturen gespeichert um das nicht mehrfach machen zu muessen


		// Beitraege laden
		$postsData = $this->_loadPostsData($topicID,$start);
		$postsCounter = count($postsData);

		for($i = 0; $i < $postsCounter; $i++) {
			$curPost = &$postsData[$i];

			$curEditedText = '';
			if($curPost['postEditedCounter'] > 0 && $curPost['postShowEditings'] == 1)
				$curEditedText = sprintf($this->modules['Language']->getString('edited_post_text'),$curPost['postEditedCounter'],$curPost['postLastEditorNick']);

			$show = array(
				'editButton'=>FALSE,
				'deleteButton'=>FALSE
			);

			if($this->modules['Auth']->isLoggedIn() == 1) {
				if($this->modules['Auth']->getValue('userIsAdmin') == 1 || $this->modules['Auth']->getValue('userIsSupermod') == 1 || $authData['authIsMod'] == 1 || ($authData['authEditPosts'] == 1 && USERID == $curPost['posterID'])) {
					$show['editButton'] = TRUE;
					if($curPost['postID'] != $topicData['topicFirstPostID'])
						$show['deleteButton'] = TRUE;
				}
			}

			$curPostDateTime = Functions::toDateTime($curPost['postTimestamp']);


			//
			// Angaben ueber den Beitragsersteller
			//
			$curPosterNick = $curPosterRankText = $curPosterRankPic = $curPosterIDText = $curPosterAvatar = '';
			if($curPost['posterID'] == 0) {
				$curPosterNick = $curPost['postGuestNick'];
				$curPosterRankText = $this->modules['Language']->getString('Guest');
			} else {
				$curPosterNick = '<a href="'.INDEXFILE.'?action=ViewProfile&amp;profileID='.$curPost['posterID'].'&amp;'.MYSID.'">'.$curPost['postPosterNick'].'</a>';
				$curPosterIDText = sprintf($this->modules['Language']->getString('ID_x'),$curPost['posterID']);


				//
				// Ueberpruefung, ob die Emailadresse des Users angezeigt werden soll
				// Zur Sicherheit wird es auch hier geloescht, damit der Templatebauer die Emailadresse
				// nicht doch aus Versehen anzeigen l?sst
				//
				if($curPost['postPosterHideEmailAddress'] == 1) $curPost['posterEmailAddress'] = '';


				//
				// Avatar
				//
				if($this->modules['Config']->getValue('enable_avatars') == 1 && $curPost['postPosterAvatarAddress'] != '')
					$curPosterAvatar = '<img width="'.$this->modules['Config']->getValue('avatar_image_width').'" height="'.$this->modules['Config']->getValue('avatar_image_height').'" src="'.$curPost['postPosterAvatarAddress'].'" alt=""/>';


				//
				// Rangbild und Rangtext des Users festlegen
				//
				if($curPost['postPosterRankID'] != 0) { // Falls der User einen speziellen Rang zugewiessen bekommen hat...
					$curPosterRankText = $ranksData[1][$curPost['postPosterRankID']]['rankName']; // ...den Namen des Rang verwenden...
					$curPosterRankPic = $ranksData[1][$curPost['postPosterRankID']]['rankGfx']; // ...und das Bild des Rangs verwenden
				}
				elseif($curPost['postPosterIsAdmin'] == 1) { // Falls der User Administrator ist...
					$curPosterRankText = $this->modules['Language']->getString('rank_administrator'); // ...seinen Rang darauf setzen...
					$curPosterRankPic = '<img src="'.$this->modules['Config']->getValue('admin_rank_pic').'" alt=""/>'; // ...und das entsprechende Bild verwenden
				}
				elseif($curPost['postPosterIsSupermod'] == 1) { // Falls der User Supermoderator ist...
					$curPosterRankText = $this->modules['Language']->getString('rank_supermoderator'); // ...seinen Rang darauf setzen...
					$curPosterRankPic = '<img src="'.$this->modules['Config']->getValue('supermod_rank_pic').'" alt=""/>'; // ...und das entsprechende Bild verwenden
				}
				elseif(isset($forumModIDs[$curPost['posterID']]) == TRUE) { // Falls der User Moderator ist...
					$curPosterRankText = $this->modules['Language']->getString('rank_moderator'); // ...seinen Rang darauf setzen...
					$curPosterRankPic = '<img src="'.$this->modules['Config']->getValue('mod_rank_pic').'" alt=""/>'; // ...und das entsprechende Bild verwenden
				}
				else { // Falls der User ein ganz normaler User ist...
					foreach($ranksData[0] AS $curRank) { // Die Rangliste durchlaufen
						if($curRank['rankPosts'] > $curPost['postPosterPosts']) break;

						$curPosterRankText = $curRank['rankName']; // ...den Namen das Rangs verwenden...
						$curPosterRankPic = $curRank['rankGfx']; // ...und das Bild des Rangs verwenden
					}
				}
			}


			//
			// Den Beitrag entsprechend formatieren
			//
			$curPost['_postText'] = $curPost['postText'];
			if($curPost['postEnableHtmlCode'] != 1 || $forumData['forumEnableHtmlCode'] != 1) $curPost['_postText'] = Functions::HTMLSpecialChars($curPost['_postText']);
			if($curPost['postEnableSmilies'] == 1 && $forumData['forumEnableSmilies'] == 1) $curPost['_postText'] = strtr($curPost['_postText'],$smiliesData);
			$curPost['_postText'] = nl2br($curPost['_postText']);
			//if($curPost['post_enable_urltransformation'] == 1  && ($forum_id == 0 || $forumData['forum_enable_urltransformation'] == 1)) $curPost['post_text'] = transform_urls($curPost['post_text']);
			if($curPost['postEnableBBCode'] == 1 && $forumData['forumEnableBBCode'] == 1) $curPost['_postText'] = $this->modules['BBCode']->parse($curPost['_postText']);


			//
			// Die Signatur entsprechend formatieren
			//
			$curSignature = '';
			if($curPost['postShowSignature'] == 1 && $this->modules['Config']->getValue('enable_sig') == 1 && $curPost['postPosterSignature'] != '') {
				if(!isset($parsedSignatures[$curPost['posterID']])) { // Falls die Signatur nicht schonmal formatiert wurde
					if($this->modules['Config']->getValue('allow_sig_html') != 1) $parsedSignatures[$curPost['posterID']] = Functions::HTMLSpecialChars($curPost['postPosterSignature']);
					if($this->modules['Config']->getValue('allow_sig_smilies') == 1) $parsedSignatures[$curPost['posterID']] = strtr($parsedSignatures[$curPost['posterID']],$smiliesData);
					$parsedSignatures[$curPost['posterID']] = nl2br($parsedSignatures[$curPost['posterID']]);
					if($this->modules['Config']->getValue('allow_sig_bbcode') == 1) $parsedSignatures[$curPost['posterID']] = $this->modules['BBCode']->parse($parsedSignatures[$curPost['posterID']]);
				}
				$curSignature = $parsedSignatures[$curPost['posterID']];
			}

			$curPost['_postEditBoxText'] = Functions::HTMLSpecialChars($curPost['postText']);
			$curPost['_postSignature'] = $curSignature;
			$curPost['_postEditedText'] = $curEditedText;
			$curPost['_postPosterIDText'] = $curPosterIDText;
			$curPost['_postDateTime'] = $curPostDateTime;
			$curPost['_postPosterAvatar'] = $curPosterAvatar;
			$curPost['_postPosterNick'] = $curPosterNick;
			$curPost['_postPosterRankPic'] = $curPosterRankPic;
			$curPost['_postPosterRankText'] = $curPosterRankText;
			$curPost['show'] = $show;
		}


		if($this->modules['Auth']->isLoggedIn() == 1 && $this->modules['Config']->getValue('enable_email_functions') == 1 && $this->modules['Config']->getValue('enable_topic_subscription') == 1) {
            $this->modules['DB']->queryParams('SELECT "userID" FROM '.TBLPFX.'topics_subscriptions WHERE "topicID"=$1 AND "userID"=$2', array($topicID, USERID));
			$subscribeText = ($this->modules['DB']->getAffectedRows() == 0) ? $this->modules['Language']->getString('Subscribe_topic') : $this->modules['Language']->getString('Unsubscribe_topic');
			$this->modules['Navbar']->setRightArea('<a href="'.INDEXFILE.'?action=SubscribeTopic&amp;topicID='.$topicID.'&amp;'.MYSID.'">'.$subscribeText.'</a>');
		}

		$this->modules['Navbar']->addCategories($forumData['catID']);
		$this->modules['Navbar']->addElements(
			array(Functions::HTMLSpecialChars($forumData['forumName']),INDEXFILE.'?action=ViewForum&amp;forumID='.$forumID.'&amp;'.MYSID),
			array(Functions::HTMLSpecialChars($topicData['topicTitle']),INDEXFILE.'?action=ViewTopic&amp;topicID='.$topicID.'&amp;'.MYSID)
		);

		// Seite ausgeben
		$this->modules['Template']->assign(array(
			'postsData'=>$postsData,
			'pageListing'=>$pageListing,
			'modTools'=>$modTools,
			'topicID'=>$topicID,
			'forumID'=>$forumID,
			'topicData'=>$topicData,
			'page'=>$page
		));
		$this->modules['Template']->printPage('ViewTopic.tpl');
	}

	protected function _loadForumModIDs($forumID) {
		$forumModIDs = array();

		if($forumID != 0) {
            $this->modules['DB']->queryParams('
                SELECT
                    "authID"
                FROM
                    '.TBLPFX.'forums_auth
                WHERE
                    "authType"=$1
                    AND "forumID"=$2
                    AND "authIsMod"=1
            ', array(
                AUTH_TYPE_USER,
                $forumID
            ));
			while(list($curUserID) = $this->modules['DB']->fetchArray())
				$forumModIDs[intval($curUserID)] = TRUE;

            $this->modules['DB']->queryParams('
                SELECT
                    t2."memberID"
                FROM (
                    '.TBLPFX.'forums_auth AS t1,
                    '.TBLPFX.'groups_members AS t2
                )
                WHERE
                    t1."forumID"=$1
                    AND t1."authIsMod"=1
                    AND t1."authType"=$2
                    AND t2."groupID"=t1."authID"
                GROUP BY
                    t2."memberID"
            ', array(
                $forumID,
                AUTH_TYPE_GROUP
            ));
			while(list($curUserID) = $this->modules['DB']->fetchArray())
				$forumModIDs[intval($curUserID)] = TRUE;
		}

		return $forumModIDs;
	}

	protected function _loadPostsData($topicID,$start) {
        $this->modules['DB']->queryParams('
            SELECT
                t1.*,
                t2."userEmailAddress" AS "postPosterEmailAddress",
                t2."userNick" AS "postPosterNick",
                t2."userSignature" AS "postPosterSignature",
                t2."userIsAdmin" AS "postPosterIsAdmin",
                t2."userIsSupermod" AS "postPosterIsSupermod",
                t2."userPostsCounter" AS "postPosterPosts",
                t2."rankID" AS "postPosterRankID",
                t2."userAvatarAddress" AS "postPosterAvatarAddress",
                t2."userHideEmailAddress" AS "postPosterHideEmailAddress",
                t2."userReceiveEmails" AS "postPosterReceiveEmails",
                t3."smileyFileName" AS "postSmileyFileName"
            FROM '.TBLPFX.'posts AS t1
            LEFT JOIN '.TBLPFX.'users AS t2 ON t1."posterID"=t2."userID"
            LEFT JOIN '.TBLPFX.'smilies AS t3 ON t3."smileyID"=t1."smileyID"
            WHERE t1."topicID"=$1
            ORDER BY t1."postID" LIMIT $2, $3
        ', array(
            $topicID,
            (int) $start,
            (int) $this->modules['Config']->getValue('posts_per_page')
        ));

		return $this->modules['DB']->raw2Array();
	}

	protected function _authenticateUser(&$forumData) {
		$authData = Functions::getAuthData($forumData,array('authViewForum','authPostTopic','authPostReply','authEditPosts','authIsMod'));
		if($authData['authViewForum'] != 1) {
			FuncMisc::printMessage('access_denied');
			exit;
		}

		return $authData;
	}
}

?>