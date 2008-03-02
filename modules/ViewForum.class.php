<?php

class ViewForum extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Config',
		'DB',
		'Language',
		'Navbar',
		'Template'
	);

	public function executeMe() {
		$forumID = isset($_GET['forumID']) ? intval($_GET['forumID']) : 0;
		$page = isset($_GET['page']) ? $_GET['page'] : 1;

		if(!$forumData = FuncForums::getForumData($forumID)) die('Cannot load data: Forum');

		$this->modules['Language']->addFile('ViewForum');


		// Authentifizierung
		$this->_authenticateUser($forumData);


		// Mark forum read
		if(isset($_GET['mark'])) {
			$_SESSION['forumVisits'][$forumID] = time();

			$tmp = array();
			foreach($_SESSION['forumVisits'] AS $forumID => $visitTime)
				$tmp[] = $forumID.'.'.$visitTime;

			Functions::set1YearCookie('forumVisits',implode(',',$tmp));
		}


		// Last visit
		$userLastVisit = $this->modules['Auth']->isLoggedIn() ? $this->modules['Auth']->getValue('userLastVisit') : $_COOKIE['tbbLastVisit'];


		/**
		 * Page listing
		 */
		$topicsCounter = Functions::getTopicsCounter($forumID);
		$pageListing = Functions::createPageListing($topicsCounter,$this->modules['Config']->getValue('topics_per_page'),$page,"<a href=\"".INDEXFILE."?action=ViewForum&amp;forumID=$forumID&amp;page=%1\$s&amp;".MYSID."\">%2\$s</a>");
		$start = $page*$this->modules['Config']->getValue('topics_per_page')-$this->modules['Config']->getValue('topics_per_page');

		$announcementsForumID = $this->modules['Config']->getValue('announcementsForumID');

		// Die Ankuendigungen laden
		$topicsData = array();
		if($announcementsForumID != 0 && $forumID != $announcementsForumID)
			$topicsData = $this->_loadAnnouncementsData($announcementsForumID);

		// Die normalen Themen laden
		$topicsData = array_merge($topicsData,$this->_loadTopicsData($forumID,$start));
		$topicsCounter = count($topicsData);

		// Jetzt koennen die Themen angezeigt werden
		for($i = 0; $i < $topicsCounter; $i++) {
			$curPrefix = $curLastPost = '';
			$curTopic = &$topicsData[$i];

			if($curTopic['topicMovedID'] != 0)
				$curPrefix .= $this->modules['Language']->getString('Prefix_moved'); // ...das hinschreiben...

			if($curTopic['forumID'] == $announcementsForumID && $announcementsForumID != $forumID) $curPrefix .= $this->modules['Language']->getString('Prefix_announcement');
			if($curTopic['topicIsPinned'] == 1) $curPrefix .= $this->modules['Language']->getString('Prefix_important');
			if($curTopic['topicHasPoll'] == 1) $curPrefix .= $this->modules['Language']->getString('Prefix_poll');

			if($curTopic['topicLastPostPosterID'] == 0)
				$curLastPostPoster = $curTopic['topicLastPostPosterNick'];
			else $curLastPostPoster = '<a href="'.INDEXFILE.'?action=ViewProfile&amp;profileID='.$curTopic['topicLastPostPosterID'].'&amp;'.MYSID.'">'.$curTopic['topicLastPostPosterNick'].'</a>';
			$curLastPost = Functions::toDateTime($curTopic['topicLastPostTimestamp']).'<br/>'.$this->modules['Language']->getString('by').' '.$curLastPostPoster.' <a href="'.INDEXFILE.'?action=ViewTopic&amp;topicID='.$curTopic['topicID'].'&amp;page=last&amp;'.MYSID.'#Post'.$curTopic['topicLastPostID'].'">&#187;</a>';

			$curTopic['topicRepliesCounter'] = number_format($curTopic['topicRepliesCounter'],0,',','.');
			$curTopic['topicViewsCounter'] = number_format($curTopic['topicViewsCounter'],0,',','.');

			//
			// Der Themen-Author
			//
			$curPosterNick = '';
			if($curTopic['posterID'] == 0) $curPosterNick = $curTopic['topicGuestNick']; // Falls es ein Gast ist...
			else $curPosterNick = "<a href=\"".INDEXFILE."?action=ViewProfile&amp;profileID=".$curTopic['posterID']."&amp;".MYSID."\">".$curTopic['topicPosterNick'].'</a>'; // ...und falls nicht


			$curTopic['_newPostsAvailable'] = 0;

			if($curTopic['topicMovedID'] == 0 && $userLastVisit < $curTopic['topicLastPostTimestamp'] && (!isset($_SESSION['forumVisits'][$curTopic['forumID']]) || $_SESSION['forumVisits'][$curTopic['forumID']] < $curTopic['topicLastPostTimestamp']) && (!isset($_SESSION['topicVisits'][$curTopic['topicID']]) || $_SESSION['topicVisits'][$curTopic['topicID']] < $curTopic['topicLastPostTimestamp']))
				$curTopic['_newPostsAvailable'] = 1;


			//
			// Das Themen-Bild
			//
			$curTopicPic = '';
			if($curTopic['topicSmileyFileName'] != '')
				$curTopicPic = '<img src="'.$curTopic['topicSmileyFileName'].'" alt=""/>';

			$curTopic['_topicPrefix'] = $curPrefix;
			//$curTopic['_topicStatus'] = $curStatus;
			$curTopic['_topicPosterNick'] = $curPosterNick;
			$curTopic['_topicLastPost'] = $curLastPost;
			$curTopic['_topicPic'] = $curTopicPic;
		}

		$this->modules['Navbar']->addCategories($forumData['catID']);
		$this->modules['Navbar']->addElement(Functions::HTMLSpecialChars($forumData['forumName']),INDEXFILE.'?action=ViewForum&amp;forumID='.$forumID.'&amp;'.MYSID);
		$this->modules['Navbar']->setRightArea('<a href="'.INDEXFILE.'?action=ViewForum&amp;forumID='.$forumID.'&amp;markAll=1&amp;'.MYSID.'">'.$this->modules['Language']->getString('Mark_topics_read').'</a>');

		$this->modules['Template']->assign(array(
			'forumID'=>$forumID,
			'topicsData'=>$topicsData,
			'pageListing'=>$pageListing
		));
		$this->modules['Template']->printPage('ViewForum.tpl');
	}

	protected function _loadAnnouncementsData($forumID) {
		$this->modules['DB']->queryParams('
			SELECT
				t1.*,
				t2."postTimestamp" AS "topicLastPostTimestamp",
				t2."posterID" AS "topicLastPostPosterID",
				t3."userNick" AS "topicPosterNick",
				t2."postGuestNick" AS "topicLastPostGuestNick",
				t4."userNick" AS "topicLastPostPosterNick",
				t5."smileyFileName" AS "topicSmileyFileName"
			FROM (
				'.TBLPFX.'posts t2,
				'.TBLPFX.'topics t1
			)
			LEFT JOIN '.TBLPFX.'users t3 ON t1."posterID"=t3."userID"
			LEFT JOIN '.TBLPFX.'users t4 ON t2."posterID"=t4."userID"
			LEFT JOIN '.TBLPFX.'smilies t5 ON t1."smileyID"=t5."smileyID"
			WHERE
				t1."forumID"=$1
				AND t1."topicLastPostID"=t2."postID"
			ORDER BY
				t1."topicIsPinned" DESC,
				t2."postTimestamp" DESC
		',array(
			$forumID
		));
		return $this->modules['DB']->raw2Array();
	}

	protected function _loadTopicsData($forumID,$start) {
		$this->modules['DB']->queryParams('
			SELECT
				t1.*,
				t2."postTimestamp" AS "topicLastPostTimestamp",
				t2."posterID" AS "topicLastPostPosterID",
				t3."userNick" AS "topicPosterNick",
				t2."postGuestNick" AS "topicLastPostGuestNick",
				t4."userNick" AS "topicLastPostPosterNick",
				t5."smileyFileName" AS "topicSmileyFileName"
			FROM (
				'.TBLPFX.'posts t2,
				'.TBLPFX.'topics t1
			)
			LEFT JOIN '.TBLPFX.'users t3 ON t1."posterID"=t3."userID"
			LEFT JOIN '.TBLPFX.'users t4 ON t2."posterID"=t4."userID"
			LEFT JOIN '.TBLPFX.'smilies t5 ON t1."smileyID"=t5."smileyID"
			WHERE
				t1."forumID"=$1
				AND t1."topicLastPostID"=t2."postID"
			ORDER BY
				t1."topicIsPinned" DESC,
				t2."postTimestamp" DESC
			LIMIT
				$2, $3
		',array(
			$forumID,
            (int) $start,
            //TODO: Siehe Ticket #37
            (int) $this->modules['Config']->getValue('topics_per_page')
		));
		return $this->modules['DB']->raw2Array();
	}

	protected function _authenticateUser(&$forumData) {
		$authData = Functions::getAuthData($forumData,array('authViewForum','authPostTopic','authIsMod'));

		if($authData['authViewForum'] != 1) {
			FuncMisc::printMessage('access_denied');
			exit;
		}

		return $authData;
	}
}

?>