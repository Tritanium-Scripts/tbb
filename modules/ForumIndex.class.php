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


		// Closed categories
		$closedCatIDs = array();
		if(!isset($_COOKIE['closedCatIDs'])) {
			foreach($catsData AS &$curCat) {
				if($curCat['catStandardStatus'] != 1)
					$closedCatIDs[] = $curCat['catID'];
			}
			setcookie('closedCatIDs',implode('.',$closedCatIDs),time()+31536000);
		}
		else
			$closedCatIDs = explode('.',$_COOKIE['closedCatIDs']);

		foreach($catsData AS &$curCat) {
			if(in_array($curCat['catID'],$closedCatIDs)) $curCat['catIsOpen'] = 0;
			else $curCat['catIsOpen'] = 1;

			$curCat['catName'] = Functions::HTMLSpecialChars($curCat['catName']);
		}


		// Last visit
		$userLastVisit = $this->modules['Auth']->isLoggedIn() ? $this->modules['Auth']->getValue('userLastVisit') : $_COOKIE['tbbLastVisit'];


		// First, check acccess to all forums and get the forum ids of
		// all forums of which we have to check the on/off status
		$forumIDsOnOffCheck = array();
		$forumIDsAccessible = array();
		foreach($forumsData AS &$curForum) {
			$curAuthViewForum = 1;
			if($this->modules['Auth']->isLoggedIn() == 0) {
				if($curForum['authViewForumGuests'] == 0) $curAuthViewForum = 0;
			}
			elseif($this->modules['Auth']->getValue('userIsAdmin')!= 1 && $this->modules['Auth']->getValue('userIsSupermod') != 1) {
				if($curForum['authViewForumMembers'] == 1) {
					if(isset($forumsAuthData[$curForum['forumID']]) && $forumsAuthData[$curForum['forumID']]['authViewForum'] == 0)
						$curAuthViewForum = 0;
				}
				else {
					$curAuthViewForum = 0;
					if(isset($forumsAuthData[$curForum['forumID']]) && $forumsAuthData[$curForum['forumID']]['authViewForum'] == 1)
						$curAuthViewForum = 1;
				}
			}
			$curForum['forumIsAccessible'] = $curAuthViewForum;

			if($curAuthViewForum == 1) {
				$forumIDsAccessible[] = $curForum['forumID'];
				
				if($userLastVisit < $curForum['forumLastPostTimestamp'] && (!isset($_SESSION['forumVisits'][$curForum['forumID']]) || $_SESSION['forumVisits'][$curForum['forumID']] < $curForum['forumLastPostTimestamp']))
					$forumIDsOnOffCheck[] = $curForum['forumID'];
			}
				
		}


		// Get all new topics
		$topicsData = array();
		$this->modules['DB']->queryParams('
			SELECT
				t1."forumID",
				t1."topicID",
				t2."postTimestamp" AS "lastPostTimestamp"
			FROM
				'.TBLPFX.'topics t1
			LEFT JOIN '.TBLPFX.'posts t2 ON t1."topicLastPostID"=t2."postID"
			WHERE
				t1."forumID" IN $1
				AND t2."postTimestamp">$2
		',array(
			$forumIDsOnOffCheck,
			$userLastVisit
		));
		while($curResult = $this->modules['DB']->fetchArray())
			$topicsData[$curResult['forumID']][$curResult['topicID']] = $curResult['lastPostTimestamp'];


		// Proceed with the forums
		foreach($forumsData AS &$curForum) {
			if($curForum['forumIsAccessible'] != 1 && $this->modules['Config']->getValue('hide_not_accessible_forums') != 0) continue;

			$curForumMods = array(); // Array fuer die Moderatoren
			while(list($curKey) = each($modsUsersData)) { // Erst werden alle Mitglieder-Moderatoren ueberprueft
				if($modsUsersData[$curKey]['forumID'] != $curForum['forumID']) continue;

				$curForumMods[] = '<a href="'.INDEXFILE.'?action=ViewProfile&amp;profileID='.$modsUsersData[$curKey]['userID'].'&amp;'.MYSID.'">'.$modsUsersData[$curKey]['userNick'].'</a>'; // Aktuelles Mitglied zu Array mit Moderatoren des aktuellen Forums hinzufuegen
				unset($modsUsersData[$curKey]); // Mitglied kann aus Array geloescht werden
			}
			reset($modsUsersData);

			while(list($curKey) = each($modsGroupsData)) { // Erst werden alle Gruppen-Moderatoren ueberprueft
				if($modsGroupsData[$curKey]['forumID'] != $curForum['forumID']) continue;

				$curForumMods[] = '<a href="'.INDEXFILE.'?action=ViewGroup&amp;groupID='.$modsGroupsData[$curKey]['groupID'].'&amp;'.MYSID.'">'.$modsGroupsData[$curKey]['groupName'].'</a>'; // Aktuelle Gruppe zu Array mit Moderatoren des aktuellen Forums hinzufuegen
				unset($modsGroupsData[$curKey]); // Mitglied kann aus Array geloescht werden
			}
			reset($modsGroupsData);

			$curForum['forumMods'] = implode(', ',$curForumMods);


			$curForum['_newPostsAvailable'] = 0;
			if($curForum['forumIsAccessible'] == 1 && $userLastVisit < $curForum['forumLastPostTimestamp'] && (!isset($_SESSION['forumVisits'][$curForum['forumID']]) || $_SESSION['forumVisits'][$curForum['forumID']] < $curForum['forumLastPostTimestamp']) && isset($topicsData[$curForum['forumID']])) {
				foreach($topicsData[$curForum['forumID']] AS $curTopicID => $curTopicLastPostTimestamp) {
					if(!isset($_SESSION['topicVisits'][$curTopicID]) || $_SESSION['topicVisits'][$curTopicID] < $curTopicLastPostTimestamp) {
						$curForum['_newPostsAvailable'] = 1;
						break;
					}
				}
			}


			//
			// Der neueste Beitrag
			//
			$curLastPostPic = $curLastPostText = '';
			if($curForum['forumLastPostID'] != 0) {
				if($curForum['forumIsAccessible'] == 1) {
					$curLastPostPic = ($curForum['forumLastPostSmileyFileName'] == '') ? '' : '<img src="'.$curForum['forumLastPostSmileyFileName'].'" alt=""/>';
					if(Functions::strlen($curForum['forumLastPostTitle']) > 22) $curLastPostLink = '<a href="'.INDEXFILE.'?action=ViewTopic&amp;postID='.$curForum['forumLastPostID'].'&amp;'.MYSID.'#post'.$curForum['forumLastPostID'].'" title="'.Functions::HTMLSpecialChars(($curForum['forumLastPostTitle'])).'">'.Functions::HTMLSpecialChars(Functions::substr($curForum['forumLastPostTitle'],0,22)).'...</a>';
					else $curLastPostLink = '<a href="'.INDEXFILE.'?action=ViewTopic&amp;postID='.$curForum['forumLastPostID'].'&amp;'.MYSID.'#post'.$curForum['forumLastPostID'].'">'.Functions::HTMLSpecialChars($curForum['forumLastPostTitle']).'</a>';

					if($curForum['forumLastPostPosterID'] == 0) $curLastPostPosterNick = $curForum['forumLastPostGuestNick'];
					else $curLastPostPosterNick = '<a href="index.php?action=ViewProfile&amp;profileID='.$curForum['forumLastPostPosterID'].'&amp;'.MYSID.'">'.$curForum['forumLastPostPosterNick'].'</a>';

					$curLastPostText = $curLastPostLink.' ('.$this->modules['Language']->getString('by').' '.$curLastPostPosterNick.')<br/>'.Functions::toDateTime($curForum['forumLastPostTimestamp']);
				}
			}
			else $curLastPostText = $this->modules['Language']->getString('no_last_post');

			$curForum['forumLastPostPic'] = $curLastPostPic;
			$curForum['forumLastPostText'] = $curLastPostText;


			//
			// Sonstiges...
			//
			$curForum['forumName'] = Functions::HTMLSpecialChars($curForum['forumName']);
			$curForum['forumDescription'] = Functions::HTMLSpecialChars($curForum['forumDescription']);
		}


		$catsData = array_merge(array(array('catID'=>$catID,'catIsOpen'=>1)),$catsData);

		$this->modules['Navbar']->addCategories($catID);

		$this->modules['Template']->assign(array(
			'catID'=>$catID,
			'catsData'=>$catsData,
			'forumsData'=>$forumsData,
			'newsData'=>$newsData,
			'wioData'=>$wioData,
			'boardStatsData'=>$boardStatsData,
			'latestPostsData'=>$this->getLatestPostsData($forumIDsAccessible)
		));
		$this->modules['Template']->printPage('ForumIndex.tpl');
	}

	protected function _loadForumsData() {
		$this->modules['DB']->query('
			SELECT
				t1.*,
				t2."posterID" AS "forumLastPostPosterID",
				t2."postTimestamp" AS "forumLastPostTimestamp",
				t2."postTitle" AS "forumLastPostTitle",
				t2."postGuestNick" AS "forumLastPostGuestNick",
				t3."userNick" AS "forumLastPostPosterNick",
				t5."smileyFileName" AS "forumLastPostSmileyFileName"
			FROM
				'.TBLPFX.'forums t1
			LEFT JOIN '.TBLPFX.'posts t2 ON t2."postID"=t1."forumLastPostID"
			LEFT JOIN '.TBLPFX.'users t3 ON t2."posterID"=t3."userID"
			LEFT JOIN '.TBLPFX.'smilies t5 ON t2."smileyID"=t5."smileyID"
			ORDER BY
				t1."orderID"
		');
		return $this->modules['DB']->raw2Array();
	}

	/**
	 * Laedt alle User, die in Foren als Moderatoren
	 * eingesetzt sind
	 *
	 * @return array
	 */
	protected function _loadModsUsersData() {
		$this->modules['DB']->query('
			SELECT
				t1."authID" AS "userID",
				t1."forumID",
				t2."userNick"
			FROM (
				'.TBLPFX.'forums_auth t1,
				'.TBLPFX.'users t2
			)
			WHERE
				t1."authType"=0
				AND t1."authIsMod"=1
				AND t2."userID"=t1."authID"
		');
		return $this->modules['DB']->raw2Array();
	}

	/**
	 * Laedt alle Gruppe, die in Foren als Moderatoren
	 * eingesetzt sind.
	 *
	 * @return array
	 */
	protected function _loadModsGroupsData() {
		$this->modules['DB']->query('
			SELECT
				t1."authID" AS "groupID",
				t1."forumID",
				t2."groupName"
			FROM (
				'.TBLPFX.'forums_auth t1,
				'.TBLPFX.'groups t2
			)
			WHERE
				t1."authType"=1
				AND t1."authIsMod"=1
				AND t2."groupID"=t1."authID"
		');
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

		if($this->modules['Auth']->isLoggedIn() == 1 && $this->modules['Auth']->getValue('userIsAdmin') != 1 && $this->modules['Auth']->getValue('userIsSupermod') != 1) {
			// First we check group permissions because
			// user permissions will probably overwrite them
			$this->modules['DB']->queryParams('
				SELECT
					t1."forumID",
					t1."authViewForum"
				FROM
					'.TBLPFX.'forums_auth t1,
					'.TBLPFX.'groups_members t2
				WHERE
					t1."authType"=1
					AND t1."authID"=t2."groupID"
					AND t2."memberID"=$1
			', array(
                USERID
            ));
			while($curData = $this->modules['DB']->fetchArray())
				$forumsAuthData[$curData['forumID']] = $curData;


			$this->modules['DB']->queryParams('
				SELECT
					t1."forumID",
					t1."authViewForum"
				FROM
					'.TBLPFX.'forums_auth t1
				WHERE
					t1."authType"=0
					AND t1."authID"=$1
			', array(
                USERID
            ));
			while($curData = $this->modules['DB']->fetchArray())
				$forumsAuthData[$curData['forumID']] = $curData;
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
			$this->modules['DB']->queryParams('
				SELECT
					t2."postText" AS "newsText",
					t2."postID",
					t1."topicRepliesCounter" AS "newsCommentsCounter",
					t1."topicTitle" AS "newsTitle",
					t2."postEnableHtmlCode",
					t2."postEnableSmilies",
					t2."postEnableBBCode"
				FROM (
					'.TBLPFX.'topics t1,
					'.TBLPFX.'posts t2
				)
				WHERE
					t1."forumID"=$1
					AND t2."postID"=t1."topicFirstPosID"
				ORDER BY t1."topicPostTimestamp"
				DESC LIMIT 1
			',array(
				(int) $this->modules['Config']->getValue('news_forum')
			));

			if($this->modules['DB']->numRows() == 1) {
				$newsData = $this->modules['DB']->fetchArray();
				//$news_comments_link = "<a href=\"index.php?action=viewtopic&amp;post_id=".$news_data['post_id']."&amp;$mYSID\">".sprintf($lNG['x_comments'],$news_data['news_comments_counter']).'</a>';

				$newsData['newsTitle'] = Functions::HTMLSpecialChars($newsData['newsTitle']);
				$newsData['newsText'] = $this->modules['BBCode']->format($newsData['newsText'], ($newsData['postEnableHtmlCode'] == 1), ($newsData['postEnableSmilies'] == 1 && $forumData['forumEnableSmilies'] == 1), ($newsData['postEnableBBCode'] == 1));
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

			$this->modules['DB']->queryParams('
				SELECT
					t1.*,
					t2."userNick" AS "sessionUserNick"
				FROM '.TBLPFX.'sessions t1
				LEFT JOIN '.TBLPFX.'users t2 ON t1."sessionUserID"=t2."userID"
				WHERE
					"sessionLastUpdate">$1
			', array(
                $this->modules['DB']->fromUnixTimestamp(time()-$this->modules['Config']->getValue('wio_timeout')*60)
            ));
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
			$membersCounter = $this->modules['Config']->getValue('usersCounter');
			$topicsCounter = Functions::getTopicsCounter();
			$postsCounter = Functions::getPostsCounter();

			$boardStatsData['text'] = sprintf($this->modules['Language']->getString('board_stats_text'),$membersCounter,$postsCounter,$topicsCounter,'<a href="'.INDEXFILE.'?action=ViewProfile&amp;profileID='.$this->modules['Config']->getValue('newest_user_id').'&amp;'.MYSID.'">'.$this->modules['Config']->getValue('newest_user_nick').'</a>');
		}
		return $boardStatsData;
	}
	
	protected function getLatestPostsData(&$forumIDsAccessible) {
		if($this->modules['Config']->getValue('show_latest_posts_forumindex') != 1 || count($forumIDsAccessible) == 0)
			return NULL;
		
		// The following ugly query is just because you can forget mysql query optimization beyond "select bla from blub"
		$queryParts = array();
		foreach($forumIDsAccessible AS $curForum) {
			$queryParts[] = '
				SELECT
					t1."postID",
					t1."postGuestNick",
					t1."posterID",
					t1."postTimestamp",
					t1."postTitle",
					t2."userNick" AS "posterNick"
				FROM
					'.TBLPFX.'posts t1
				LEFT JOIN '.TBLPFX.'users t2 ON t1."posterID"=t2."userID"
				WHERE
					t1."forumID"='.$this->modules['DB']->escapeString($curForum).'
				ORDER BY
					t1."postID" DESC
				LIMIT '.intval($this->modules['Config']->getValue('max_latest_posts')).'
			';
		}
		$query = '('.implode(') UNION (',$queryParts).') ORDER BY "postID" DESC LIMIT '.intval($this->modules['Config']->getValue('max_latest_posts'));

			$latestPostsData = array();
		$this->modules['DB']->query($query);
		while($curPost = $this->modules['DB']->fetchArray()) {
			if($curPost['posterID'] == 0)
				$curPostPoster = $curPost['postGuestNick'];
			else
				$curPostPoster = '<a href="'.INDEXFILE.'?action=ViewProfile&amp;profileID='.$curPost['posterID'].'&amp;'.MYSID.'">'.$curPost['posterNick'].'</a>';
			$latestPostsData[] = sprintf($this->modules['Language']->getString('latest_post_text'), Functions::toDateTime($curPost['postTimestamp']), '"' . INDEXFILE . '?action=ViewTopic&amp;postID=' . $curPost['postID'] . '&amp;' . MYSID . '#post' . $curPost['postID'] . '"', Functions::HTMLSpecialChars($curPost['postTitle']), $curPostPoster);
		}
		
		return $latestPostsData;
	}
}