<?php

class ForumIndex extends ModuleTemplate {
	protected $requiredModules = array(
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
		$baseCatID = isset($_GET['baseCatID']) ? intval($_GET['baseCatID']) : 1;
		$catID = isset($_GET['CatID']) ? intval($_GET['CatID']) : $baseCatID;

		// Kategoriedaten laden
		if(($catsData = Functions::getCatsData($catID)) === FALSE) die('Wrong base cat id');
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
		$ForumsAuthData = $this->_loadForumsAuthData();

		$NewsData = $this->_loadNewsData();
		$WIOData = $this->_loadWIOData();
		$BoardStatsData = $this->_loadBoardStatsData();


		$ClosedCatIDs = array();
		if(!isset($_COOKIE['ClosedCatIDs'])) {
			for($i = 0; $i < $catsCounter; $i++) {
				if($catsData[$i]['CatStandardStatus'] != 1) $ClosedCatIDs[] = $catsData[$i]['CatID'];
			}
			setcookie('ClosedCatIDs',implode('.',$ClosedCatIDs),time()+31536000);
		}
		else
			$ClosedCatIDs = explode('.',$_COOKIE['ClosedCatIDs']);

		for($i = 0; $i < $catsCounter; $i++) {
			$curCat = &$catsData[$i];

			if(in_array($curCat['CatID'],$ClosedCatIDs) == TRUE) $curCat['CatIsOpen'] = 0;
			else $curCat['CatIsOpen'] = 1;

			$curCat['CatName'] = Functions::HTMLSpecialChars($curCat['CatName']);
		}

		for($i = 0; $i < $forumsCounter; $i++) {
			$curForum = &$forumsData[$i];

			//
			// Der Zugriff zu diesem Forum
			//
			$curAuthViewForum = 1;
			if($this->modules['Auth']->isLoggedIn() == 0) {
				if($forumsData[$i]['GuestsAuthViewForum'] == 0) $curAuthViewForum = 0;
			}
			elseif($this->modules['Auth']->getValue('UserIsAdmin')!= 1 && $this->modules['Auth']->getValue('UserIsSupermod') != 1) {
				if($forumsData[$i]['MembersAuthViewForum'] == 1) {
					while(list($curKey,$curData) = each($ForumsAuthData)) {
						if($curData['ForumID'] != $forumsData[$i]['ForumID']) continue;

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
						if($curData['ForumID'] != $forumsData[$i]['ForumID']) continue;

						unset($ForumsAuthData[$akt_key]);

						if($curData['AuthViewForum'] == 1) {
							$curAuthViewForum = 1;
							break;
						}
					}
				}
				reset($ForumsAuthData);
			}
			$forumsData[$i]['ForumIsAccessible'] = $curAuthViewForum;


			if($curAuthViewForum == 1 || $this->modules['Config']->getValue('HideNotAccessibleForums') == 0) {
				//
				// Die Moderatoren (Mitglieder und Gruppen) des aktuellen Forums
				//
				$curForumMods = array(); // Array fuer die Moderatoren
				while(list($curKey) = each($modsUsersData)) { // Erst werden alle Mitglieder-Moderatoren ueberprueft
					if($modsUsersData[$curKey]['ForumID'] != $forumsData[$i]['ForumID']) continue;

					$curForumMods[] = '<a href="'.INDEXFILE.'?Action=ViewProfile&amp;ProfileID='.$modsUsersData[$curKey]['UserID'].'&amp;'.MYSID.'">'.$modsUsersData[$curKey]['UserNick'].'</a>'; // Aktuelles Mitglied zu Array mit Moderatoren des aktuellen Forums hinzufuegen
					unset($modsUsersData[$curKey]); // Mitglied kann aus Array geloescht werden
				}
				reset($modsUsersData);

				while(list($curKey) = each($modsGroupsData)) { // Erst werden alle Gruppen-Moderatoren ueberprueft
					if($modsGroupsData[$curKey]['ForumID'] != $forumsData[$i]['ForumID']) continue;

					$curForumMods[] = '<a href="'.INDEXFILE.'?Action=ViewGroup&amp;GroupID='.$modsGroupsData[$curKey]['GroupID'].'&amp;'.MYSID.'">'.$modsGroupsData[$curKey]['GroupName'].'</a>'; // Aktuelle Gruppe zu Array mit Moderatoren des aktuellen Forums hinzufuegen
					unset($modsGroupsData[$curKey]); // Mitglied kann aus Array geloescht werden
				}
				reset($modsGroupsData); // Array resetten (Pointer auf Position 1 setzen)

				$forumsData[$i]['ForumMods'] = implode(', ',$curForumMods);


				//
				// Die Anzeige, ob neue Beitraege vorhanden sind
				//
				//$akt_new_post_status = '<img src="'.(($forums_data[$j]['forum_last_post_id'] != 0 && isset($c_forums[$forums_data[$j]['forum_id']]) == TRUE && $c_forums[$forums_data[$j]['forum_id']] < $forums_data[$j]['forum_last_post_time']) ? $TEMPLATE_PATH.'/'.$TCONFIG['images']['forum_on'] : $TEMPLATE_PATH.'/'.$TCONFIG['images']['forum_off']).'" alt="" />';


				//
				// Der neueste Beitrag
				//
				$curLastPostPic = $curLastPostText = '';
				if($forumsData[$i]['ForumLastPostID'] != 0) {
					if($curAuthViewForum == 1) {
						$curLastPostPic = ($forumsData[$i]['ForumLastPostSmileyFileName'] == '') ? '' : '<img src="'.$forumsData[$i]['ForumLastPostSmileyFileName'].'" alt="" border="" />';
						if(strlen($forumsData[$i]['ForumLastPostTitle']) > 22) $curLastPostLink = '<a href="'.INDEXFILE.'?Action=ViewTopic&amp;PostID='.$forumsData[$i]['ForumLastPostID'].'&amp;'.MYSID.'#Post'.$forumsData[$i]['ForumLastPostID'].'" title="'.Functions::HTMLSpecialChars(($forumsData[$i]['ForumLastPostTitle'])).'">'.Functions::HTMLSpecialChars(substr($forumsData[$i]['ForumLastPostTitle'],0,22)).'...</a>';
						else $curLastPostLink = '<a href="'.INDEXFILE.'?Action=ViewTopic&amp;PostID='.$forumsData[$i]['ForumLastPostID'].'&amp;'.MYSID.'#Post'.$forumsData[$i]['ForumLastPostID'].'">'.Functions::HTMLSpecialChars($forumsData[$i]['ForumLastPostTitle']).'</a>';

						if($forumsData[$i]['ForumLastPostPosterID'] == 0) $curLastPostPosterNick = $forumsData[$i]['ForumLastPostGuestNick'];
						else $curLastPostPosterNick = '<a href="index.php?action=viewprofile&amp;profile_id='.$forumsData[$i]['ForumLastPostPosterID'].'&amp;'.MYSID.'">'.$forumsData[$i]['ForumLastPostPosterNick'].'</a>';

						$curLastPostText = $curLastPostLink.' ('.$this->modules['Language']->getString('by').' '.$curLastPostPosterNick.')<br/>'.Functions::toDateTime($forumsData[$i]['ForumLastPostTimestamp']);
					}
				}
				else $curLastPostText = $this->modules['Language']->getString('No_last_post');

				$forumsData[$i]['ForumLastPostPic'] = $curLastPostPic;
				$forumsData[$i]['ForumLastPostText'] = $curLastPostText;


				//
				// Sonstiges...
				//
				$curForum['ForumName'] = Functions::HTMLSpecialChars($curForum['ForumName']);
				$curForum['ForumDescription'] = Functions::HTMLSpecialChars($curForum['ForumDescription']);
			}
		}

		$catsData = array_merge(array(array('CatID'=>$catID,'CatIsOpen'=>1)),$catsData);

		$this->modules['Navbar']->addCategories($catID);

		$this->modules['Template']->assign(array(
			'CatID'=>$catID,
			'CatsData'=>$catsData,
			'ForumsData'=>$forumsData,
			'NewsData'=>$NewsData,
			'WIOData'=>$WIOData,
			'BoardStatsData'=>$BoardStatsData
		));
		$this->modules['PageParts']->printPage('ForumIndex.tpl');
	}

	protected function _loadForumsData() {
		$this->modules['DB']->query("SELECT
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
		return $this->modules['DB']->Raw2Array();
	}

	/**
	 * Laedt alle User, die in Foren als Moderatoren
	 * eingesetzt sind
	 *
	 * @return array
	 */
	protected function _loadModsUsersData() {
		$this->modules['DB']->query("SELECT
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
		return $this->modules['DB']->Raw2Array();
	}

	/**
	 * Laedt alle Gruppe, die in Foren als Moderatoren
	 * eingesetzt sind.
	 *
	 * @return array
	 */
	protected function _loadModsGroupsData() {
		$this->modules['DB']->query("SELECT
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
		return $this->modules['DB']->Raw2Array();
	}

	/**
	 * Laedt von allen Foren eventuell vorhandenen individuelle
	 * Betreten-Rechte fuer den User (falls dieser eingeloggt).
	 *
	 * @return array
	 */
	protected function _loadForumsAuthData() {
		$ForumsAuthData = array();

		if($this->modules['Auth']->isLoggedIn() == 1 && $this->modules['Auth']->getValue('UserIsAdmin') != 1 && $this->modules['Auth']->getValue('UserIsSupermod') != 1) {
			$this->modules['DB']->query("SELECT
				t1.ForumID,
				t1.AuthViewForum
			FROM
				".TBLPFX."forums_auth AS t1
			WHERE
				t1.AuthType='0'
				AND t1.AuthID='".USERID."'
			");
			$ForumsAuthData = $this->modules['DB']->Raw2Array();

			$this->modules['DB']->query("SELECT
				t1.ForumID,
				t1.AuthViewForum
			FROM
				".TBLPFX."forums_auth AS t1,
				".TBLPFX."groups_members AS t2
			WHERE
				t1.AuthType='1'
				AND t1.AuthID=t2.GroupID
				AND t2.MemberID='".USERID."'");
			while($curData = $this->modules['DB']->fetchArray())
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

		if($this->modules['Config']->getValue('news_forum') != 0 && $this->modules['Config']->getValue('show_news_forumindex') == 1) {
			$this->modules['DB']->query("
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
					t1.ForumID='".$this->modules['Config']->getValue('news_forum')."'
					AND t2.PostID=t1.TopicFirstPosID
				ORDER BY t1.TopicTimestamp
				DESC LIMIT 1
			");

			if($this->modules['DB']->getAffectedRows() == 1) {
				$NewsData = $this->modules['DB']->fetchArray();
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

		if($this->modules['Config']->getValue('enable_wio') == 1 && $this->modules['Config']->getValue('show_wio_forumindex') == 1) {
			$OnlineGuestsCounter = $OnlineMembersCounter = $OnlineGhostsCounter = $OnlineUsersCounter = 0;
			$Members = array();
			$MembersChecks = array();
			$Guests = '';

			$this->modules['DB']->query("
				SELECT
					t1.*,
					t2.UserNick AS SessionUserNick
				FROM ".TBLPFX."sessions AS t1
				LEFT JOIN ".TBLPFX."users AS t2 ON t1.SessionUserID=t2.UserID
				WHERE SessionLastUpdate>'".($this->modules['DB']->fromUnixTimestamp(time()-$this->modules['Config']->getValue('wio_timeout')*60))."'
			");
			while($curData = $this->modules['DB']->fetchArray()) {
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
			if($this->modules['Config']->getValue('online_users_record') == '')
				$OnlineUsersRecord = array(0,0);
			else
				$OnlineUsersRecord = explode(',',$this->modules['Config']->getValue('online_users_record'));

			if($OnlineUsersCounter > $OnlineUsersRecord[0]) {
				$OnlineUsersRecord = array($OnlineUsersCounter,time());
				$this->modules['Config']->updateValue('online_users_record',implode(',',$OnlineUsersRecord));
			}

			if($OnlineMembersCounter == 0) $OnlineMembersCounter = $this->modules['Language']->getString('no_members');
			elseif($OnlineMembersCounter == 1) $OnlineMembersCounter = $this->modules['Language']->getString('one_member');
			else $OnlineMembersCounter = sprintf($this->modules['Language']->getString('x_members'),$OnlineMembersCounter);

			if($OnlineGhostsCounter == 0) $OnlineGhostsCounter = $this->modules['Language']->getString('no_ghosts');
			elseif($OnlineGhostsCounter == 1) $OnlineGhostsCounter = $this->modules['Language']->getString('one_ghost');
			else $OnlineGhostsCounter = sprintf($this->modules['Language']->getString('x_ghosts'),$OnlineGhostsCounter);

			if($OnlineGuestsCounter == 0) $OnlineGuestsCounter = $this->modules['Language']->getString('no_guests');
			elseif($OnlineGuestsCounter == 1) $OnlineGuestsCounter = $this->modules['Language']->getString('one_guest');
			else $OnlineGuestsCounter = sprintf($this->modules['Language']->getString('x_guests'),$OnlineGuestsCounter);

			$WIOData['Text'] = sprintf($this->modules['Language']->getString('wio_text'),$OnlineGuestsCounter,$OnlineGhostsCounter,$OnlineMembersCounter,$OnlineUsersCounter,Functions::toDateTime($OnlineUsersRecord[1],TRUE),$OnlineUsersRecord[0]);
			$WIOData['Members'] = implode(', ',$Members);
		}

		return $WIOData;
	}

	protected function _loadBoardStatsData() {
		$BoardStatsData = FALSE;
		if($this->modules['Config']->getValue('show_boardstats_forumindex') == 1) {
			$MembersCounter = Functions::getUsersCounter();
			$TopicsCounter = Functions::getTopicsCounter();
			$PostsCounter = Functions::getPostsCounter();

			$BoardStatsData['Text'] = sprintf($this->modules['Language']->getString('board_stats_text'),$MembersCounter,$PostsCounter,$TopicsCounter,'<a href="index.php?action=viewprofile&amp;profile_id='.$this->modules['Config']->getValue('newest_user_id').'&amp;'.MYSID.'">'.$this->modules['Config']->getValue('newest_user_nick').'</a>');
		}
		return $BoardStatsData;
	}
}

?>