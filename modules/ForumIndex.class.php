<?php

class ForumIndex extends ModuleTemplate {
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
		$BaseCatID = isset($_GET['BaseCatID']) ? intval($_GET['BaseCatID']) : 1;
		$CatID = isset($_GET['CatID']) ? intval($_GET['CatID']) : $BaseCatID;

		// Kategoriedaten laden
		if(($CatsData = Functions::getCatsData($CatID)) === FALSE) die('Wrong base cat id');
		$CatsCounter = count($CatsData);

		// Sprachstrings laden
		$this->Modules['Language']->addFile('ForumIndex');

		// Forendaten laden
		$ForumsData = $this->_loadForumsData();
		$ForumsCounter = count($ForumsData);

		// Moderatorendaten laden
		$ModsUsersData = $this->_loadModsUsersData();
		$ModsGroupsData = $this->_loadModsGroupsData();

		// Zugriffsrechte auf die Foren laden
		$ForumsAuthData = $this->_loadForumsAuthData();

		$NewsData = $this->_loadNewsData();
		$WIOData = $this->_loadWIOData();
		$BoardStatsData = $this->_loadBoardStatsData();


		$ClosedCatIDs = array();
		if(!isset($_COOKIE['ClosedCatIDs'])) {
			for($i = 0; $i < $CatsCounter; $i++) {
				if($CatsData[$i]['CatStandardStatus'] != 1) $ClosedCatIDs[] = $CatsData[$i]['CatID'];
			}
			setcookie('ClosedCatIDs',implode('.',$ClosedCatIDs),time()+31536000);
		}
		else
			$ClosedCatIDs = explode('.',$_COOKIE['ClosedCatIDs']);

		for($i = 0; $i < $CatsCounter; $i++) {
			$curCat = &$CatsData[$i];

			if(in_array($curCat['CatID'],$ClosedCatIDs) == TRUE) $curCat['CatIsOpen'] = 0;
			else $curCat['CatIsOpen'] = 1;

			$curCat['CatName'] = Functions::HTMLSpecialChars($curCat['CatName']);
		}

		for($i = 0; $i < $ForumsCounter; $i++) {
			$curForum = &$ForumsData[$i];

			//
			// Der Zugriff zu diesem Forum
			//
			$curAuthViewForum = 1;
			if($this->Modules['Auth']->isLoggedIn() == 0) {
				if($ForumsData[$i]['GuestsAuthViewForum'] == 0) $curAuthViewForum = 0;
			}
			elseif($this->Modules['Auth']->getValue('UserIsAdmin')!= 1 && $this->Modules['Auth']->getValue('UserIsSupermod') != 1) {
				if($ForumsData[$i]['MembersAuthViewForum'] == 1) {
					while(list($curKey,$curData) = each($ForumsAuthData)) {
						if($curData['ForumID'] != $ForumsData[$i]['ForumID']) continue;

						unset($ForumsAuthData[$curKey]);

						if($curData['AuthViewForum'] == 0) {
							$curAuthViewForum = 0;
							break;
						}
					}
				}
				else {
					$curAuthViewForum = 0;
					while(list($akt_key,$akt_data) = each($ForumsAuthData)) {
						if($curData['ForumID'] != $ForumsData[$i]['ForumID']) continue;

						unset($ForumsAuthData[$akt_key]);

						if($curData['AuthViewForum'] == 1) {
							$curAuthViewForum = 1;
							break;
						}
					}
				}
				reset($ForumsAuthData);
			}
			$ForumsData[$i]['ForumIsAccessible'] = $curAuthViewForum;


			if($curAuthViewForum == 1 || $this->Modules['Config']->getValue('HideNotAccessibleForums') == 0) {
				//
				// Die Moderatoren (Mitglieder und Gruppen) des aktuellen Forums
				//
				$curForumMods = array(); // Array fuer die Moderatoren
				while(list($curKey) = each($ModsUsersData)) { // Erst werden alle Mitglieder-Moderatoren ueberprueft
					if($ModsUsersData[$curKey]['ForumID'] != $ForumsData[$i]['ForumID']) continue;

					$curForumMods[] = '<a href="'.INDEXFILE.'?Action=ViewProfile&amp;ProfileID='.$ModsUsersData[$curKey]['UserID'].'&amp;'.MYSID.'">'.$ModsUsersData[$curKey]['UserNick'].'</a>'; // Aktuelles Mitglied zu Array mit Moderatoren des aktuellen Forums hinzufuegen
					unset($ModsUsersData[$curKey]); // Mitglied kann aus Array geloescht werden
				}
				reset($ModsUsersData);

				while(list($curKey) = each($ModsGroupsData)) { // Erst werden alle Gruppen-Moderatoren ueberprueft
					if($ModsGroupsData[$curKey]['ForumID'] != $ForumsData[$i]['ForumID']) continue;

					$curForumMods[] = '<a href="'.INDEXFILE.'?Action=ViewGroup&amp;GroupID='.$ModsGroupsData[$curKey]['GroupID'].'&amp;'.MYSID.'">'.$ModsGroupsData[$curKey]['GroupName'].'</a>'; // Aktuelle Gruppe zu Array mit Moderatoren des aktuellen Forums hinzufuegen
					unset($ModsGroupsData[$curKey]); // Mitglied kann aus Array geloescht werden
				}
				reset($ModsGroupsData); // Array resetten (Pointer auf Position 1 setzen)

				$ForumsData[$i]['ForumMods'] = implode(', ',$curForumMods);


				//
				// Die Anzeige, ob neue Beitraege vorhanden sind
				//
				//$akt_new_post_status = '<img src="'.(($forums_data[$j]['forum_last_post_id'] != 0 && isset($c_forums[$forums_data[$j]['forum_id']]) == TRUE && $c_forums[$forums_data[$j]['forum_id']] < $forums_data[$j]['forum_last_post_time']) ? $TEMPLATE_PATH.'/'.$TCONFIG['images']['forum_on'] : $TEMPLATE_PATH.'/'.$TCONFIG['images']['forum_off']).'" alt="" />';


				//
				// Der neueste Beitrag
				//
				$curLastPostPic = $curLastPostText = '';
				if($ForumsData[$i]['ForumLastPostID'] != 0) {
					if($curAuthViewForum == 1) {
						$curLastPostPic = ($ForumsData[$i]['ForumLastPostSmileyFileName'] == '') ? '' : '<img src="'.$ForumsData[$i]['ForumLastPostSmileyFileName'].'" alt="" border="" />';
						if(strlen($ForumsData[$i]['ForumLastPostTitle']) > 22) $curLastPostLink = '<a href="'.INDEXFILE.'?Action=ViewTopic&amp;PostID='.$ForumsData[$i]['ForumLastPostID'].'&amp;'.MYSID.'#Post'.$ForumsData[$i]['ForumLastPostID'].'" title="'.Functions::HTMLSpecialChars(($ForumsData[$i]['ForumLastPostTitle'])).'">'.Functions::HTMLSpecialChars(substr($ForumsData[$i]['ForumLastPostTitle'],0,22)).'...</a>';
						else $curLastPostLink = '<a href="'.INDEXFILE.'?Action=ViewTopic&amp;PostID='.$ForumsData[$i]['ForumLastPostID'].'&amp;'.MYSID.'#Post'.$ForumsData[$i]['ForumLastPostID'].'">'.Functions::HTMLSpecialChars($ForumsData[$i]['ForumLastPostTitle']).'</a>';

						if($ForumsData[$i]['ForumLastPostPosterID'] == 0) $curLastPostPosterNick = $ForumsData[$i]['ForumLastPostGuestNick'];
						else $curLastPostPosterNick = '<a href="index.php?action=viewprofile&amp;profile_id='.$ForumsData[$i]['ForumLastPostPosterID'].'&amp;'.MYSID.'">'.$ForumsData[$i]['ForumLastPostPosterNick'].'</a>';

						$curLastPostText = $curLastPostLink.' ('.$this->Modules['Language']->getString('by').' '.$curLastPostPosterNick.')<br/>'.Functions::toDateTime($ForumsData[$i]['ForumLastPostTimestamp']);
					}
				}
				else $curLastPostText = $this->Modules['Language']->getString('No_last_post');

				$ForumsData[$i]['ForumLastPostPic'] = $curLastPostPic;
				$ForumsData[$i]['ForumLastPostText'] = $curLastPostText;


				//
				// Sonstiges...
				//
				$curForum['ForumName'] = Functions::HTMLSpecialChars($curForum['ForumName']);
				$curForum['ForumDescription'] = Functions::HTMLSpecialChars($curForum['ForumDescription']);
			}
		}

		$CatsData = array_merge(array(array('CatID'=>$CatID,'CatIsOpen'=>1)),$CatsData);

		$this->Modules['Navbar']->addCategories($CatID);

		$this->Modules['Template']->assign(array(
			'CatID'=>$CatID,
			'CatsData'=>$CatsData,
			'ForumsData'=>$ForumsData,
			'NewsData'=>$NewsData,
			'WIOData'=>$WIOData,
			'BoardStatsData'=>$BoardStatsData
		));
		$this->Modules['PageParts']->printPage('ForumIndex.tpl');
	}

	protected function _loadForumsData() {
		$this->Modules['DB']->query("SELECT
			t1.*,
			t2.PosterID AS ForumLastPostPosterID,
			t2.PostTimestamp AS ForumLastPostTimestamp,
			t2.PostTitle AS ForumLastPostTitle,
			t2.PostGuestNick AS ForumLastPostGuestNick,
			t3.UserNick AS ForumLastPostPosterNick,
			t5.SmileyFileName AS ForumLastPostSmileyFileName
		FROM
			".TBLPFX."forums AS t1
		LEFT JOIN ".TBLPFX."posts AS t2 ON t2.PostID=t1.ForumLastPostID
		LEFT JOIN ".TBLPFX."users AS t3 ON t2.PosterID=t3.UserID
		LEFT JOIN ".TBLPFX."smilies AS t5 ON t2.SmileyID=t5.SmileyID
		ORDER BY t1.OrderID
		");
		return $this->Modules['DB']->Raw2Array();
	}

	/**
	 * Laedt alle User, die in Foren als Moderatoren
	 * eingesetzt sind
	 *
	 * @return array
	 */
	protected function _loadModsUsersData() {
		$this->Modules['DB']->query("SELECT
			t1.AuthID AS UserID,
			t1.ForumID,
			t2.UserNick
		FROM
			".TBLPFX."forums_auth AS t1,
			".TBLPFX."users AS t2
		WHERE
			t1.AuthType='0'
			AND t1.AuthIsMod='1'
			AND t2.UserID=t1.AuthID
		");
		return $this->Modules['DB']->Raw2Array();
	}

	/**
	 * Laedt alle Gruppe, die in Foren als Moderatoren
	 * eingesetzt sind.
	 *
	 * @return array
	 */
	protected function _loadModsGroupsData() {
		$this->Modules['DB']->query("SELECT
			t1.AuthID AS GroupID,
			t1.ForumID,
			t2.GroupName
		FROM
			".TBLPFX."forums_auth AS t1,
			".TBLPFX."groups AS t2
		WHERE
			t1.AuthType='1'
			AND t1.AuthIsMod='1'
			AND t2.GroupID=t1.AuthID
		");
		return $this->Modules['DB']->Raw2Array();
	}

	/**
	 * Laedt von allen Foren eventuell vorhandenen individuelle
	 * Betreten-Rechte fuer den User (falls dieser eingeloggt).
	 *
	 * @return array
	 */
	protected function _loadForumsAuthData() {
		$ForumsAuthData = array();

		if($this->Modules['Auth']->isLoggedIn() == 1 && $this->Modules['Auth']->getValue('UserIsAdmin') != 1 && $this->Modules['Auth']->getValue('UserIsSupermod') != 1) {
			$this->Modules['DB']->query("SELECT
				t1.ForumID,
				t1.AuthViewForum
			FROM
				".TBLPFX."forums_auth AS t1
			WHERE
				t1.AuthType='0'
				AND t1.AuthID='".USERID."'
			");
			$ForumsAuthData = $this->Modules['DB']->Raw2Array();

			$this->Modules['DB']->query("SELECT
				t1.ForumID,
				t1.AuthViewForum
			FROM
				".TBLPFX."forums_auth AS t1,
				".TBLPFX."groups_members AS t2
			WHERE
				t1.AuthType='1'
				AND t1.AuthID=t2.GroupID
				AND t2.MemberID='".USERID."'");
			while($curData = $this->Modules['DB']->fetchArray())
				$ForumsAuthData[] = $curData;
		}

		return $ForumsAuthData;
	}

	/**
	 * Laedt falls erwuenscht und vorhanden den aktuellen
	 * Newsbeitrag aus dem entsprechenden Forum
	 *
	 * @return mixed
	 */
	protected function _loadNewsData() {
		$NewsData = FALSE;

		if($this->Modules['Config']->getValue('news_forum') != 0 && $this->Modules['Config']->getValue('show_news_forumindex') == 1) {
			$this->Modules['DB']->query("
				SELECT
					t2.PostText AS NewsText,
					t2.PostID,
					t1.TopicRepliesCounter AS NewsCommentsCounter,
					t1.TopicTitle AS NewsTitle,
					t2.PostEnableHtmlCode,
					t2.PostEnableSmilies,
					t2.PostEnableBBCode
				FROM (
					".TBLPFX."topics AS t1,
					".TBLPFX."posts AS t2
				)
				WHERE
					t1.ForumID='".$this->Modules['Config']->getValue('news_forum')."'
					AND t2.PostID=t1.TopicFirstPosID
				ORDER BY t1.TopicTimestamp
				DESC LIMIT 1
			");

			if($this->Modules['DB']->getAffectedRows() == 1) {
				$NewsData = $this->Modules['DB']->fetchArray();
				//$news_comments_link = "<a href=\"index.php?action=viewtopic&amp;post_id=".$news_data['post_id']."&amp;$MYSID\">".sprintf($LNG['x_comments'],$news_data['news_comments_counter']).'</a>';

				$NewsData['NewsTitle'] = Functions::HTMLSpecialChars($NewsData['NewsTitle']);

				if($NewsData['PostEnableHtmlCode'] != 1) $NewsData['NewsText'] = Functions::HTMLSpecialChars($NewsData['NewsText']);
				if($NewsData['PostEnableSmilies'] == 1 && $ForumData['ForumEnableSmilies'] == 1) $NewsData['NewsText'] = strtr($NewsData['NewsText']);
				$NewsData['NewsText'] = nl2br($NewsData['NewsText']);
				//if($NewsData['PostEnableBBCode'] == 1) $NewsData['NewsText'] = bbcode($news_data['news_text']);
			}
		}

		return $NewsData;
	}

	/**
	 * Falls vorhanden und erwuenscht
	 *
	 * @return mixed
	 */
	protected function _loadWIOData() {
		$WIOData = FALSE;

		if($this->Modules['Config']->getValue('enable_wio') == 1 && $this->Modules['Config']->getValue('show_wio_forumindex') == 1) {
			$OnlineGuestsCounter = $OnlineMembersCounter = $OnlineGhostsCounter = $OnlineUsersCounter = 0;
			$Members = array();
			$MembersChecks = array();
			$Guests = '';

			$this->Modules['DB']->query("
				SELECT
					t1.*,
					t2.UserNick AS SessionUserNick
				FROM ".TBLPFX."sessions AS t1
				LEFT JOIN ".TBLPFX."users AS t2 ON t1.SessionUserID=t2.UserID
				WHERE SessionLastUpdate>'".($this->Modules['DB']->fromUnixTimestamp(time()-$this->Modules['Config']->getValue('wio_timeout')*60))."'
			");
			while($curData = $this->Modules['DB']->fetchArray()) {
				if($curData['SessionUserID'] == 0) $OnlineGuestsCounter++;
				elseif($curData['SessionIsGhost'] == 1) $OnlineGhostsCounter++;
				else {
					if(in_array($curData['SessionUserID'],$MembersChecks) == FALSE) {
						$OnlineMembersCounter++;
						$Members[] = '<a href="'.INDEXFILE.'?Action=ViewProfile&amp;ProfileID='.$curData['SessionUserID'].'&amp;'.MYSID.'">'.$curData['SessionUserNick'].'</a>';
						$MembersChecks[] = $curData['SessionUserID'];
					}
				}
			}

			$OnlineUsersCounter = $OnlineGuestsCounter+$OnlineGhostsCounter+$OnlineMembersCounter;
			if($this->Modules['Config']->getValue('online_users_record') == '')
				$OnlineUsersRecord = array(0,0);
			else
				$OnlineUsersRecord = explode(',',$this->Modules['Config']->getValue('online_users_record'));

			if($OnlineUsersCounter > $OnlineUsersRecord[0]) {
				$OnlineUsersRecord = array($OnlineUsersCounter,time());
				$this->Modules['Config']->updateValue('online_users_record',implode(',',$OnlineUsersRecord));
			}

			if($OnlineMembersCounter == 0) $OnlineMembersCounter = $this->Modules['Language']->getString('no_members');
			elseif($OnlineMembersCounter == 1) $OnlineMembersCounter = $this->Modules['Language']->getString('one_member');
			else $OnlineMembersCounter = sprintf($this->Modules['Language']->getString('x_members'),$OnlineMembersCounter);

			if($OnlineGhostsCounter == 0) $OnlineGhostsCounter = $this->Modules['Language']->getString('no_ghosts');
			elseif($OnlineGhostsCounter == 1) $OnlineGhostsCounter = $this->Modules['Language']->getString('one_ghost');
			else $OnlineGhostsCounter = sprintf($this->Modules['Language']->getString('x_ghosts'),$OnlineGhostsCounter);

			if($OnlineGuestsCounter == 0) $OnlineGuestsCounter = $this->Modules['Language']->getString('no_guests');
			elseif($OnlineGuestsCounter == 1) $OnlineGuestsCounter = $this->Modules['Language']->getString('one_guest');
			else $OnlineGuestsCounter = sprintf($this->Modules['Language']->getString('x_guests'),$OnlineGuestsCounter);

			$WIOData['Text'] = sprintf($this->Modules['Language']->getString('wio_text'),$OnlineGuestsCounter,$OnlineGhostsCounter,$OnlineMembersCounter,$OnlineUsersCounter,Functions::toDateTime($OnlineUsersRecord[1],TRUE),$OnlineUsersRecord[0]);
			$WIOData['Members'] = implode(', ',$Members);
		}

		return $WIOData;
	}

	protected function _loadBoardStatsData() {
		$BoardStatsData = FALSE;
		if($this->Modules['Config']->getValue('show_boardstats_forumindex') == 1) {
			$MembersCounter = Functions::getUsersCounter();
			$TopicsCounter = Functions::getTopicsCounter();
			$PostsCounter = Functions::getPostsCounter();

			$BoardStatsData['Text'] = sprintf($this->Modules['Language']->getString('board_stats_text'),$MembersCounter,$PostsCounter,$TopicsCounter,'<a href="index.php?action=viewprofile&amp;profile_id='.$this->Modules['Config']->getValue('newest_user_id').'&amp;'.MYSID.'">'.$this->Modules['Config']->getValue('newest_user_nick').'</a>');
		}
		return $BoardStatsData;
	}
}

?>