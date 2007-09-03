<?php

class ForumIndex extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Cache',
		'Config',
		'BBCode',
		'DB',
		'Language',
		'Navbar',
		'Template'
	);

	public function executeMe() {
		$baseCatID = isset($_GET['baseCatID']) ? intval($_GET['baseCatID']) : 1;
		$catID = isset($_GET['catID']) ? intval($_GET['catID']) : $baseCatID;

		// Kategoriedaten laden
		if(($catsData = FuncCats::getCatsData($catID)) === FALSE) die('Wrong base cat id');
		$catsCounter = count($catsData);

		// Sprachstrings laden
		$this->modules['Language']->addFile('ForumIndex');

		// Forendaten laden
		$forumsData = $this->_loadForumsData();
		$forumsCounter = count($forumsData);

		// Moderatorendaten laden
		$modsUsersData = $this->_loadModsUsersData();
		$modsGroupsData = $this->_loadModsGroupsData();

		// Zugriffsrechte auf die Foren laden
		$forumsAuthData = $this->_loadForumsAuthData();

		$newsData = $this->_loadNewsData();
		$wioData = $this->_loadWIOData();
		$boardStatsData = $this->_loadBoardStatsData();


		$closedCatIDs = array();
		if(!isset($_COOKIE['closedCatIDs'])) {
			for($i = 0; $i < $catsCounter; $i++) {
				if($catsData[$i]['catStandardStatus'] != 1) $closedCatIDs[] = $catsData[$i]['catID'];
			}
			setcookie('closedCatIDs',implode('.',$closedCatIDs),time()+31536000);
		}
		else
			$closedCatIDs = explode('.',$_COOKIE['closedCatIDs']);

		for($i = 0; $i < $catsCounter; $i++) {
			$curCat = &$catsData[$i];

			if(in_array($curCat['catID'],$closedCatIDs)) $curCat['catIsOpen'] = 0;
			else $curCat['catIsOpen'] = 1;

			$curCat['catName'] = Functions::HTMLSpecialChars($curCat['catName']);
		}

		for($i = 0; $i < $forumsCounter; $i++) {
			$curForum = &$forumsData[$i];

			//
			// Der Zugriff zu diesem Forum
			//
			$curAuthViewForum = 1;
			if($this->modules['Auth']->isLoggedIn() == 0) {
				if($forumsData[$i]['authViewForumGuests'] == 0) $curAuthViewForum = 0;
			}
			elseif($this->modules['Auth']->getValue('userIsAdmin')!= 1 && $this->modules['Auth']->getValue('userIsSupermod') != 1) {
				if($forumsData[$i]['authViewForumMembers'] == 1) {
					while(list($curKey,$curData) = each($forumsAuthData)) {
						if($curData['forumID'] != $forumsData[$i]['forumID']) continue;

						unset($forumsAuthData[$curKey]);

						if($curData['authViewForum'] == 0) {
							$curAuthViewForum = 0;
							break;
						}
					}
				}
				else {
					$curAuthViewForum = 0;
					while(list($curKey,$curData) = each($forumsAuthData)) {
						if($curData['forumID'] != $forumsData[$i]['forumID']) continue;

						unset($forumsAuthData[$curKey]);

						if($curData['authViewForum'] == 1) {
							$curAuthViewForum = 1;
							break;
						}
					}
				}
				reset($forumsAuthData);
			}
			$forumsData[$i]['forumIsAccessible'] = $curAuthViewForum;


			if($curAuthViewForum == 1 || $this->modules['Config']->getValue('hideNotAccessibleForums') == 0) {
				//
				// Die Moderatoren (Mitglieder und Gruppen) des aktuellen Forums
				//
				$curForumMods = array(); // Array fuer die Moderatoren
				while(list($curKey) = each($modsUsersData)) { // Erst werden alle Mitglieder-Moderatoren ueberprueft
					if($modsUsersData[$curKey]['forumID'] != $forumsData[$i]['forumID']) continue;

					$curForumMods[] = '<a href="'.INDEXFILE.'?action=ViewProfile&amp;ProfileID='.$modsUsersData[$curKey]['userID'].'&amp;'.MYSID.'">'.$modsUsersData[$curKey]['userNick'].'</a>'; // Aktuelles Mitglied zu Array mit Moderatoren des aktuellen Forums hinzufuegen
					unset($modsUsersData[$curKey]); // Mitglied kann aus Array geloescht werden
				}
				reset($modsUsersData);

				while(list($curKey) = each($modsGroupsData)) { // Erst werden alle Gruppen-Moderatoren ueberprueft
					if($modsGroupsData[$curKey]['forumID'] != $forumsData[$i]['forumID']) continue;

					$curForumMods[] = '<a href="'.INDEXFILE.'?action=ViewGroup&amp;groupID='.$modsGroupsData[$curKey]['groupID'].'&amp;'.MYSID.'">'.$modsGroupsData[$curKey]['groupName'].'</a>'; // Aktuelle Gruppe zu Array mit Moderatoren des aktuellen Forums hinzufuegen
					unset($modsGroupsData[$curKey]); // Mitglied kann aus Array geloescht werden
				}
				reset($modsGroupsData); // Array resetten (Pointer auf Position 1 setzen)

				$forumsData[$i]['forumMods'] = implode(', ',$curForumMods);


				//
				// Die Anzeige, ob neue Beitraege vorhanden sind
				//
				//$akt_new_post_status = '<img src="'.(($forums_data[$j]['forum_last_post_id'] != 0 && isset($c_forums[$forums_data[$j]['forum_id']]) == TRUE && $c_forums[$forums_data[$j]['forum_id']] < $forums_data[$j]['forum_last_post_time']) ? $tEMPLATE_PATH.'/'.$tCONFIG['images']['forum_on'] : $tEMPLATE_PATH.'/'.$tCONFIG['images']['forum_off']).'" alt="" />';


				//
				// Der neueste Beitrag
				//
				$curLastPostPic = $curLastPostText = '';
				if($forumsData[$i]['forumLastPostID'] != 0) {
					if($curAuthViewForum == 1) {
						$curLastPostPic = ($forumsData[$i]['forumLastPostSmileyFileName'] == '') ? '' : '<img src="'.$forumsData[$i]['forumLastPostSmileyFileName'].'" alt="" border="0"/>';
						if(strlen($forumsData[$i]['forumLastPostTitle']) > 22) $curLastPostLink = '<a href="'.INDEXFILE.'?action=ViewTopic&amp;postID='.$forumsData[$i]['forumLastPostID'].'&amp;'.MYSID.'#post'.$forumsData[$i]['forumLastPostID'].'" title="'.Functions::HTMLSpecialChars(($forumsData[$i]['forumLastPostTitle'])).'">'.Functions::HTMLSpecialChars(substr($forumsData[$i]['forumLastPostTitle'],0,22)).'...</a>';
						else $curLastPostLink = '<a href="'.INDEXFILE.'?action=ViewTopic&amp;postID='.$forumsData[$i]['forumLastPostID'].'&amp;'.MYSID.'#post'.$forumsData[$i]['forumLastPostID'].'">'.Functions::HTMLSpecialChars($forumsData[$i]['forumLastPostTitle']).'</a>';

						if($forumsData[$i]['forumLastPostPosterID'] == 0) $curLastPostPosterNick = $forumsData[$i]['forumLastPostGuestNick'];
						else $curLastPostPosterNick = '<a href="index.php?action=ViewProfile&amp;profileID='.$forumsData[$i]['forumLastPostPosterID'].'&amp;'.MYSID.'">'.$forumsData[$i]['forumLastPostPosterNick'].'</a>';

						$curLastPostText = $curLastPostLink.' ('.$this->modules['Language']->getString('by').' '.$curLastPostPosterNick.')<br/>'.Functions::toDateTime($forumsData[$i]['forumLastPostTimestamp']);
					}
				}
				else $curLastPostText = $this->modules['Language']->getString('No_last_post');

				$forumsData[$i]['forumLastPostPic'] = $curLastPostPic;
				$forumsData[$i]['forumLastPostText'] = $curLastPostText;


				//
				// Sonstiges...
				//
				$curForum['forumName'] = Functions::HTMLSpecialChars($curForum['forumName']);
				$curForum['forumDescription'] = Functions::HTMLSpecialChars($curForum['forumDescription']);
			}
		}

		$catsData = array_merge(array(array('catID'=>$catID,'catIsOpen'=>1)),$catsData);

		$this->modules['Navbar']->addCategories($catID);

		$this->modules['Template']->assign(array(
			'catID'=>$catID,
			'catsData'=>$catsData,
			'forumsData'=>$forumsData,
			'newsData'=>$newsData,
			'wioData'=>$wioData,
			'boardStatsData'=>$boardStatsData
		));
		$this->modules['Template']->printPage('ForumIndex.tpl');
	}

	protected function _loadForumsData() {
		$this->modules['DB']->query("SELECT
			t1.*,
			t2.posterID AS forumLastPostPosterID,
			t2.postTimestamp AS forumLastPostTimestamp,
			t2.postTitle AS forumLastPostTitle,
			t2.postGuestNick AS forumLastPostGuestNick,
			t3.userNick AS forumLastPostPosterNick,
			t5.smileyFileName AS forumLastPostSmileyFileName
		FROM
			".TBLPFX."forums AS t1
		LEFT JOIN ".TBLPFX."posts AS t2 ON t2.postID=t1.forumLastPostID
		LEFT JOIN ".TBLPFX."users AS t3 ON t2.posterID=t3.userID
		LEFT JOIN ".TBLPFX."smilies AS t5 ON t2.smileyID=t5.smileyID
		ORDER BY t1.orderID
		");
		return $this->modules['DB']->raw2Array();
	}

	/**
	 * Laedt alle User, die in Foren als Moderatoren
	 * eingesetzt sind
	 *
	 * @return array
	 */
	protected function _loadModsUsersData() {
		$this->modules['DB']->query("SELECT
			t1.authID AS userID,
			t1.forumID,
			t2.userNick
		FROM (
			".TBLPFX."forums_auth AS t1,
			".TBLPFX."users AS t2
		)
		WHERE
			t1.authType='0'
			AND t1.authIsMod='1'
			AND t2.userID=t1.authID
		");
		return $this->modules['DB']->raw2Array();
	}

	/**
	 * Laedt alle Gruppe, die in Foren als Moderatoren
	 * eingesetzt sind.
	 *
	 * @return array
	 */
	protected function _loadModsGroupsData() {
		$this->modules['DB']->query("SELECT
			t1.authID AS groupID,
			t1.forumID,
			t2.groupName
		FROM (
			".TBLPFX."forums_auth AS t1,
			".TBLPFX."groups AS t2
		)
		WHERE
			t1.authType='1'
			AND t1.authIsMod='1'
			AND t2.groupID=t1.authID
		");
		return $this->modules['DB']->raw2Array();
	}

	/**
	 * Laedt von allen Foren eventuell vorhandenen individuelle
	 * Betreten-Rechte fuer den User (falls dieser eingeloggt).
	 *
	 * @return array
	 */
	protected function _loadForumsAuthData() {
		$forumsAuthData = array();

		if($this->modules['Auth']->isLoggedIn() == 1 && $this->modules['Auth']->getValue('UserIsAdmin') != 1 && $this->modules['Auth']->getValue('UserIsSupermod') != 1) {
			$this->modules['DB']->query("SELECT
				t1.forumID,
				t1.authViewForum
			FROM
				".TBLPFX."forums_auth AS t1
			WHERE
				t1.authType='0'
				AND t1.authID='".USERID."'
			");
			$forumsAuthData = $this->modules['DB']->raw2Array();

			$this->modules['DB']->query("SELECT
				t1.forumID,
				t1.authViewForum
			FROM
				".TBLPFX."forums_auth AS t1,
				".TBLPFX."groups_members AS t2
			WHERE
				t1.authType='1'
				AND t1.authID=t2.groupID
				AND t2.memberID='".USERID."'");
			while($curData = $this->modules['DB']->fetchArray())
				$forumsAuthData[] = $curData;
		}

		return $forumsAuthData;
	}

	/**
	 * Laedt falls erwuenscht und vorhanden den aktuellen
	 * Newsbeitrag aus dem entsprechenden Forum
	 *
	 * @return mixed
	 */
	protected function _loadNewsData() {
		$newsData = FALSE;

		if($this->modules['Config']->getValue('news_forum') != 0 && $this->modules['Config']->getValue('show_news_forumindex') == 1) {
			$this->modules['DB']->query("
				SELECT
					t2.postText AS newsText,
					t2.postID,
					t1.topicRepliesCounter AS newsCommentsCounter,
					t1.topicTitle AS newsTitle,
					t2.postEnableHtmlCode,
					t2.postEnableSmilies,
					t2.postEnableBBCode
				FROM (
					".TBLPFX."topics AS t1,
					".TBLPFX."posts AS t2
				)
				WHERE
					t1.forumID='".$this->modules['Config']->getValue('news_forum')."'
					AND t2.postID=t1.topicFirstPosID
				ORDER BY t1.topicPostTimestamp
				DESC LIMIT 1
			");

			if($this->modules['DB']->getAffectedRows() == 1) {
				$newsData = $this->modules['DB']->fetchArray();
				//$news_comments_link = "<a href=\"index.php?action=viewtopic&amp;post_id=".$news_data['post_id']."&amp;$mYSID\">".sprintf($lNG['x_comments'],$news_data['news_comments_counter']).'</a>';

				$newsData['newsTitle'] = Functions::HTMLSpecialChars($newsData['newsTitle']);

				if($newsData['postEnableHtmlCode'] != 1) $newsData['newsText'] = Functions::HTMLSpecialChars($newsData['newsText']);
				if($newsData['postEnableSmilies'] == 1 && $forumData['forumEnableSmilies'] == 1) $newsData['newsText'] = strtr($newsData['newsText']);
				$newsData['newsText'] = nl2br($newsData['newsText']);
				if($newsData['postEnableBBCode'] == 1) $newsData['newsText'] = $this->modules['BBCode']->parse($newsData['newsText']);
			}
		}

		return $newsData;
	}

	/**
	 * Falls vorhanden und erwuenscht
	 *
	 * @return mixed
	 */
	protected function _loadWIOData() {
		$wioData = FALSE;

		if($this->modules['Config']->getValue('enable_wio') == 1 && $this->modules['Config']->getValue('show_wio_forumindex') == 1) {
			$onlineGuestsCounter = $onlineMembersCounter = $onlineGhostsCounter = $onlineUsersCounter = 0;
			$members = array();
			$membersChecks = array();
			$guests = '';

			$this->modules['DB']->query("
				SELECT
					t1.*,
					t2.userNick AS sessionUserNick
				FROM ".TBLPFX."sessions AS t1
				LEFT JOIN ".TBLPFX."users AS t2 ON t1.sessionUserID=t2.userID
				WHERE sessionLastUpdate>'".($this->modules['DB']->fromUnixTimestamp(time()-$this->modules['Config']->getValue('wio_timeout')*60))."'
			");
			while($curData = $this->modules['DB']->fetchArray()) {
				if($curData['sessionUserID'] == 0) $onlineGuestsCounter++;
				elseif($curData['sessionIsGhost'] == 1) $onlineGhostsCounter++;
				else {
					if(in_array($curData['sessionUserID'],$membersChecks) == FALSE) {
						$onlineMembersCounter++;
						$members[] = '<a href="'.INDEXFILE.'?action=ViewProfile&amp;profileID='.$curData['sessionUserID'].'&amp;'.MYSID.'">'.$curData['sessionUserNick'].'</a>';
						$membersChecks[] = $curData['sessionUserID'];
					}
				}
			}

			$onlineUsersCounter = $onlineGuestsCounter+$onlineGhostsCounter+$onlineMembersCounter;
			if($this->modules['Config']->getValue('online_users_record') == '')
				$onlineUsersRecord = array(0,0);
			else
				$onlineUsersRecord = explode(',',$this->modules['Config']->getValue('online_users_record'));

			if($onlineUsersCounter > $onlineUsersRecord[0]) {
				$onlineUsersRecord = array($onlineUsersCounter,time());
				$this->modules['Config']->updateValue('online_users_record',implode(',',$onlineUsersRecord));
			}

			if($onlineMembersCounter == 0) $onlineMembersCounter = $this->modules['Language']->getString('no_members');
			elseif($onlineMembersCounter == 1) $onlineMembersCounter = $this->modules['Language']->getString('one_member');
			else $onlineMembersCounter = sprintf($this->modules['Language']->getString('x_members'),$onlineMembersCounter);

			if($onlineGhostsCounter == 0) $onlineGhostsCounter = $this->modules['Language']->getString('no_ghosts');
			elseif($onlineGhostsCounter == 1) $onlineGhostsCounter = $this->modules['Language']->getString('one_ghost');
			else $onlineGhostsCounter = sprintf($this->modules['Language']->getString('x_ghosts'),$onlineGhostsCounter);

			if($onlineGuestsCounter == 0) $onlineGuestsCounter = $this->modules['Language']->getString('no_guests');
			elseif($onlineGuestsCounter == 1) $onlineGuestsCounter = $this->modules['Language']->getString('one_guest');
			else $onlineGuestsCounter = sprintf($this->modules['Language']->getString('x_guests'),$onlineGuestsCounter);

			$wioData['text'] = sprintf($this->modules['Language']->getString('wio_text'),$onlineGuestsCounter,$onlineGhostsCounter,$onlineMembersCounter,$onlineUsersCounter,Functions::toDateTime($onlineUsersRecord[1],TRUE),$onlineUsersRecord[0]);
			$wioData['members'] = implode(', ',$members);
		}

		return $wioData;
	}

	protected function _loadBoardStatsData() {
		$boardStatsData = FALSE;
		if($this->modules['Config']->getValue('show_boardstats_forumindex') == 1) {
			$membersCounter = Functions::getUsersCounter();
			$topicsCounter = Functions::getTopicsCounter();
			$postsCounter = Functions::getPostsCounter();

			$boardStatsData['text'] = sprintf($this->modules['Language']->getString('board_stats_text'),$membersCounter,$postsCounter,$topicsCounter,'<a href="index.php?action=viewprofile&amp;profile_id='.$this->modules['Config']->getValue('newest_user_id').'&amp;'.MYSID.'">'.$this->modules['Config']->getValue('newest_user_nick').'</a>');
		}
		return $boardStatsData;
	}
}

?>