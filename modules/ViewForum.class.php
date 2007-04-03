<?php

class ViewForum extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Config',
		'DB',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
		$forumID = isset($_GET['forumID']) ? intval($_GET['forumID']) : 0;
		$page = isset($_GET['page']) ? $_GET['page'] : 1;

		if(!$forumData = Functions::getForumData($forumID)) die('Cannot load data: Forum');

		$this->modules['Language']->addFile('ViewForum');

		// Authentifizierung
		$this->_authenticateUser($forumData);

		//update_forum_cookie($forum_id);

		/*/if(isset($_GET['mark'])) {
			$c_topics = isset($_COOKIE['c_topics']) ? explode('x',$_COOKIE['c_topics']) : array();
			while(list($akt_key,$akt_value) = each($c_topics)) {
				$akt_value = explode('y',$akt_value);
				if($akt_value[0] == $forum_id) {
					unset($c_topics[$akt_key]);
					break;
				}
			}
			$c_topics = implode('x',$c_topics);
			setcookie('c_topics',$c_topics,time()+31536000,'/');
			$_COOKIE['c_topics'] = $c_topics;
		}/**/

		/**
		 * Page listing
		 */
		$topicsCounter = Functions::getTopicsCounter($forumID);
		$pageListing = Functions::createPageListing($topicsCounter,$this->modules['Config']->getValue('topics_per_page'),$page,"<a href=\"".INDEXFILE."?action=ViewForum&amp;forumID=$forumID&amp;page=%1\$s&amp;".MYSID."\">%2\$s</a>");
		$start = $page*$this->modules['Config']->getValue('topics_per_page')-$this->modules['Config']->getValue('topics_per_page');


		/*/$c_forums = array();
		$c_forums_temp = isset($_COOKIE['c_forums']) ? explode('x',$_COOKIE['c_forums']) : array();
		while(list(,$akt_value) = each($c_forums_temp)) {
			$akt_value = explode('_',$akt_value);
			$c_forums[$akt_value[0]] = $akt_value[1];
		}

		$c_topics = array();
		$c_topics_temp = isset($_COOKIE['c_topics']) ? explode('x',$_COOKIE['c_topics']) : $c_topics_temp = array();
		while(list($akt_key,$akt_value_2) = each($c_topics_temp)) {
			$akt_value_2 = explode('y',$akt_value_2);
			if($akt_value_2[0] == $forum_id) {
				$akt_value_2[1] = explode('z',$akt_value_2[1]);
				while(list(,$akt_value) = each($akt_value_2[1])) {
					$akt_value = explode('_',$akt_value);
					$c_topics[$akt_value[0]] = $akt_value[1];
				}
			}
		}/**/

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

			if($curTopic['topicMovedID'] != 0) { // Falls das Thema nur eine Referenz zu einem verschobenem Thema ist...
				$curPrefix .= $this->modules['Language']->getString('Prefix_moved'); // ...das hinschreiben...

				$curTopic['TopicRepliesCounter'] = '-'; // ...den Antwortenzaehler auf "nichts" setzen...
				$curTopic['TopicViewsCounter'] = '-'; // ...den Viewszaehler auf "nichts" setzen...
				$curLastPost = '-'; // ...und den neuesten Beitrag auf "nichts" setzen
			}
			else { // Falls es sich um ein normales Thema handelt die normalen Sachen erledigen
				if($curTopic['forumID'] == $announcementsForumID && $announcementsForumID != $forumID) $curPrefix .= $this->modules['Language']->getString('Prefix_announcement');
				if($curTopic['topicIsPinned'] == 1) $curPrefix .= $this->modules['Language']->getString('Prefix_important');
				if($curTopic['topicHasPoll'] == 1) $curPrefix .= $this->modules['Language']->getString('Prefix_poll');

				if($curTopic['topicLastPostPosterID'] == 0)
					$curLastPostPoster = $curTopic['topicLastPostPosterNick'];
				else $curLastPostPoster = '<a href="'.INDEXFILE.'?action=ViewProfile&amp;profileID='.$curTopic['topicLastPostPosterID'].'&amp;'.MYSID.'">'.$curTopic['topicLastPostPosterNick'].'</a>';
				$curLastPost = Functions::toDateTime($curTopic['topicLastPostTimestamp']).'<br/>'.$this->modules['Language']->getString('by').' '.$curLastPostPoster.' <a href="'.INDEXFILE.'?action=ViewTopic&amp;topicID='.$curTopic['topicID'].'&amp;page=last&amp;'.MYSID.'#Post'.$curTopic['topicLastPostID'].'">&#187;</a>';

				$curTopic['topicRepliesCounter'] = number_format($curTopic['topicRepliesCounter'],0,',','.');
				$curTopic['topicViewsCounter'] = number_format($curTopic['topicViewsCounter'],0,',','.');
			}

			//
			// Der Themen-Author
			//
			$curPosterNick = '';
			if($curTopic['posterID'] == 0) $curPosterNick = $curTopic['topicGuestNick']; // Falls es ein Gast ist...
			else $curPosterNick = "<a href=\"".INDEXFILE."?action=ViewProfile&amp;profileID=".$curTopic['posterID']."&amp;".MYSID."\">".$curTopic['topicPosterNick'].'</a>'; // ...und falls nicht

			/*/if(isset($c_topics[$curTopic['topicID']]) == FALSE && isset($c_forums[$forumID]) == TRUE && $c_forums[$forum_id] < $curTopic['topic_post_time']) {
				update_topic_cookie($curTopic['forum_id'],$curTopic['topic_id'],0);
				$c_topics[$curTopic['topic_id']] = 0;
			}/**/


			//
			// Der "Neue Beitraege"-Status
			//
			$curStatus = '';
			if($curTopic['topicMovedID'] != 0) $curStatus = ''; // Falls das Thema verschoben wurde...
			/*/elseif(isset($c_topics[$curTopic['topicID']]) == TRUE && $c_topics[$curTopic['topic_id']] < $curTopic['topic_last_post_time'])
				$curStatus = ''; // ...falls es neue Beitraege gibt
			else $curStatus = ''; // und falls nicht/**/


			//
			// Das Themen-Bild
			//
			$curTopicPic = '';
			if($curTopic['topicSmileyFileName'] != '')
				$curTopicPic = '<img src="'.$curTopic['topicSmileyFileName'].'" alt="" border="0"/>';

			$curTopic['_topicPrefix'] = $curPrefix;
			$curTopic['_topicStatus'] = $curStatus;
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
		$this->modules['PageParts']->printPage('ViewForum.tpl');
	}

	protected function _loadAnnouncementsData($forumID) {
		$this->modules['DB']->query("SELECT
			t1.*,
			t2.postTimestamp AS topicLastPostTimestamp,
			t2.posterID AS topicLastPostPosterID,
			t3.userNick AS topicPosterNick,
			t2.postGuestNick AS topicLastPostGuestNick,
			t4.userNick AS topicLastPostPosterNick,
			t5.smileyFileName AS topicSmileyFileName
		FROM (
			".TBLPFX."posts AS t2,
			".TBLPFX."topics AS t1
		)
		LEFT JOIN ".TBLPFX."users AS t3 ON t1.posterID=t3.userID
		LEFT JOIN ".TBLPFX."users AS t4 ON t2.posterID=t4.userID
		LEFT JOIN ".TBLPFX."smilies AS t5 ON t1.smileyID=t5.smileyID
		WHERE
			t1.forumID='".$forumID."'
			AND t1.topicLastPostID=t2.postID
		ORDER BY
			t1.topicIsPinned DESC,
			t2.postTimestamp DESC
		");
		return $this->modules['DB']->raw2Array();
	}

	protected function _loadTopicsData($forumID,$start) {
		$this->modules['DB']->query("SELECT
			t1.*,
			t2.postTimestamp AS topicLastPostTimestamp,
			t2.posterID AS topicLastPostPosterID,
			t3.userNick AS topicPosterNick,
			t2.postGuestNick AS topicLastPostGuestNick,
			t4.userNick AS topicLastPostPosterNick,
			t5.smileyFileName AS topicSmileyFileName
		FROM (
			".TBLPFX."posts AS t2,
			".TBLPFX."topics AS t1 )
		LEFT JOIN ".TBLPFX."users AS t3 ON t1.posterID=t3.userID
		LEFT JOIN ".TBLPFX."users AS t4 ON t2.posterID=t4.userID
		LEFT JOIN ".TBLPFX."smilies AS t5 ON t1.smileyID=t5.smileyID
		WHERE
			t1.forumID='$forumID'
			AND t1.topicLastPostID=t2.PostID
		ORDER BY
			t1.topicIsPinned DESC,
			t2.postTimestamp DESC
		LIMIT
			$start,".$this->modules['Config']->getValue('topics_per_page')
		);
		return $this->modules['DB']->raw2Array();
	}

	protected function _authenticateUser(&$forumData) {
		$authData = Functions::getAuthData($forumData,array('authViewForum','authPostTopic','authIsMod'));

		if($authData['authViewForum'] != 1) {
			die('Kein Zugriff');
			// TODO
			exit;
		}

		return $authData;
	}
}

?>