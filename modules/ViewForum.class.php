<?php

class ViewForum extends ModuleTemplate {
	protected $RequiredModules = array(
		'Auth',
		'Config',
		'DB',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
		$ForumID = isset($_GET['ForumID']) ? intval($_GET['ForumID']) : 0;
		$Page = isset($_GET['Page']) ? $_GET['Page'] : 1;

		if(!$ForumData = Functions::getForumData($ForumID)) die('Cannot load data: Forum');

		$this->Modules['Language']->addFile('ViewForum');

		// Authentifizierung
		$this->_authenticateUser($ForumData);

		//update_forum_cookie($forum_id);

		if(isset($_GET['mark'])) {
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
		}


		//
		// Die Seitenanzeige
		//
		$TopicsCounter = Functions::getTopicsCounter($ForumID);
		$PageListing = Functions::createPageListing($TopicsCounter,$this->Modules['Config']->getValue('topics_per_page'),$Page,"<a href=\"".INDEXFILE."?Action=ViewForum&amp;ForumID=$ForumID&amp;Page=%1\$s&amp;".MYSID."\">%2\$s</a>");
		$Start = $Page*$this->Modules['Config']->getValue('topics_per_page')-$this->Modules['Config']->getValue('topics_per_page');


			/*$c_forums = array();
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
			}*/

		$AnnouncementsForumID = $this->Modules['Config']->getValue('AnnouncementsForumID');

		// Die Ankuendigungen laden
		$TopicsData = array();
		if($AnnouncementsForumID != 0 && $ForumID != $AnnouncementsForumID)
			$TopicsData = $this->_loadAnnouncementsData($AnnouncementsForumID);

		// Die normalen Themen laden
		$TopicsData = array_merge($TopicsData,$this->_loadTopicsData($ForumID,$Start));
		$TopicsCounter = count($TopicsData);

		// Jetzt koennen die Themen angezeigt werden
		for($i = 0; $i < $TopicsCounter; $i++) {
			$curPrefix = $curLastPost = '';
			$curTopic = &$TopicsData[$i];

			if($curTopic['TopicMovedID'] != 0) { // Falls das Thema nur eine Referenz zu einem verschobenem Thema ist...
				$curPrefix .= $this->Modules['Language']->getString('Prefix_moved'); // ...das hinschreiben...

				$curTopic['TopicRepliesCounter'] = '-'; // ...den Antwortenzaehler auf "nichts" setzen...
				$curTopic['TopicViewsCounter'] = '-'; // ...den Viewszaehler auf "nichts" setzen...
				$curLastPost = '-'; // ...und den neuesten Beitrag auf "nichts" setzen
			}
			else { // Falls es sich um ein normales Thema handelt die normalen Sachen erledigen
				if($curTopic['ForumID'] == $AnnouncementsForumID && $AnnouncementsForumID != $ForumID) $curPrefix .= $this->Modules['Language']->getString('Prefix_announcement');
				if($curTopic['TopicIsPinned'] == 1) $curPrefix .= $this->Modules['Language']->getString('Prefix_important');
				if($curTopic['TopicHasPoll'] == 1) $curPrefix .= $this->Modules['Language']->getString('Prefix_poll');

				if($curTopic['TopicLastPostPosterID'] == 0)
					$curLastPostPoster = $curTopic['TopicLastPostPosterNick'];
				else $curLastPostPoster = '<a href="'.INDEXFILE.'?Action=ViewProfile&amp;ProfileID='.$curTopic['TopicLastPostPosterID'].'&amp;'.MYSID.'">'.$curTopic['TopicLastPostPosterNick'].'</a>';
				$curLastPost = Functions::toDateTime($curTopic['TopicLastPostTimestamp']).'<br/>'.$this->Modules['Language']->getString('by').' '.$curLastPostPoster.' <a href="'.INDEXFILE.'?Action=ViewTopic&amp;TopicID='.$curTopic['TopicID'].'&amp;Page=last&amp;'.MYSID.'#Post'.$curTopic['TopicLastPostID'].'">&#187;</a>';

				$curTopic['TopicRepliesCounter'] = number_format($curTopic['TopicRepliesCounter'],0,',','.');
				$curTopic['TopicViewsCounter'] = number_format($curTopic['TopicViewsCounter'],0,',','.');
			}


			//
			// Der Themen-Author
			//
			$curPosterNick = '';
			if($curTopic['PosterID'] == 0) $curPosterNick = $curTopic['TopicGuestNick']; // Falls es ein Gast ist...
			else $curPosterNick = "<a href=\"".INDEXFILE."?Action=ViewProfile&amp;ProfileID=".$curTopic['PosterID']."&amp;".MYSID."\">".$curTopic['TopicPosterNick'].'</a>'; // ...und falls nicht

			if(isset($c_topics[$curTopic['TopicID']]) == FALSE && isset($c_forums[$ForumID]) == TRUE && $c_forums[$forum_id] < $curTopic['topic_post_time']) {
				update_topic_cookie($curTopic['forum_id'],$curTopic['topic_id'],0);
				$c_topics[$curTopic['topic_id']] = 0;
			}


			//
			// Der "Neue Beitraege"-Status
			//
			if($curTopic['TopicMovedID'] != 0) $curStatus = ''; // Falls das Thema verschoben wurde...
			elseif(isset($c_topics[$curTopic['TopicID']]) == TRUE && $c_topics[$curTopic['topic_id']] < $curTopic['topic_last_post_time'])
				$curStatus = ''; // ...falls es neue Beitraege gibt
			else $curStatus = ''; // und falls nicht


			//
			// Das Themen-Bild
			//
			$curTopicPic = '';
			if($curTopic['TopicSmileyFileName'] != '')
				$curTopicPic = '<img src="'.$curTopic['TopicSmileyFileName'].'" alt="" />';

			$curTopic['_TopicPrefix'] = $curPrefix;
			$curTopic['_TopicStatus'] = $curStatus;
			$curTopic['_TopicPosterNick'] = $curPosterNick;
			$curTopic['_TopicLastPost'] = $curLastPost;
			$curTopic['_TopicPic'] = $curTopicPic;
		}

		$this->Modules['Navbar']->addCategories($ForumData['CatID']);
		$this->Modules['Navbar']->addElement(Functions::HTMLSpecialChars($ForumData['ForumName']),INDEXFILE.'?Action=ViewForum&amp;ForumID='.$ForumID.'&amp;'.MYSID);
		$this->Modules['Navbar']->setRightArea('<a href="'.INDEXFILE.'?Action=ViewForum&amp;ForumID='.$ForumID.'&amp;MarkAll=1&amp;'.MYSID.'">'.$this->Modules['Language']->getString('Mark_topics_read').'</a>');

		$this->Modules['Template']->assign(array(
			'ForumID'=>$ForumID,
			'TopicsData'=>$TopicsData,
			'PageListing'=>$PageListing
		));
		$this->Modules['PageParts']->printPage('ViewForum.tpl');
	}

	protected function _loadAnnouncementsData($ForumID) {
		$this->Modules['DB']->query("SELECT
			t1.*,
			t2.PostTimestamp AS TopicLastPostTimestamp,
			t2.PosterID AS TopicLastPostPosterID,
			t3.UserNick AS TopicPosterNick,
			t2.PostGuestNick AS TopicLastPostGuestNick,
			t4.UserNick AS TopicLastPostPosterNick,
			t5.SmileyFileName AS TopicSmileyFileName
		FROM (
			".TBLPFX."posts AS t2,
			".TBLPFX."topics AS t1 )
		LEFT JOIN ".TBLPFX."users AS t3 ON t1.PosterID=t3.UserID
		LEFT JOIN ".TBLPFX."users AS t4 ON t2.PosterID=t4.UserID
		LEFT JOIN ".TBLPFX."smilies AS t5 ON t1.SmileyID=t5.SmileyID
		WHERE
			t1.ForumID='".$ForumID."'
			AND t1.TopicLastPostID=t2.PostID
		ORDER BY
			t1.TopicIsPinned DESC,
			t2.PostTimestamp DESC
		");
		return $this->Modules['DB']->Raw2Array();
	}

	protected function _loadTopicsData($ForumID,$Start) {
		$this->Modules['DB']->query("SELECT
			t1.*,
			t2.PostTimestamp AS TopicLastPostTimestamp,
			t2.PosterID AS TopicLastPostPosterID,
			t3.UserNick AS TopicPosterNick,
			t2.PostGuestNick AS TopicLastPostGuestNick,
			t4.UserNick AS TopicLastPostPosterNick,
			t5.SmileyFileName AS TopicSmileyFileName
		FROM (
			".TBLPFX."posts AS t2,
			".TBLPFX."topics AS t1 )
		LEFT JOIN ".TBLPFX."users AS t3 ON t1.PosterID=t3.UserID
		LEFT JOIN ".TBLPFX."users AS t4 ON t2.PosterID=t4.UserID
		LEFT JOIN ".TBLPFX."smilies AS t5 ON t1.SmileyID=t5.SmileyID
		WHERE
			t1.ForumID='$ForumID'
			AND t1.TopicLastPostID=t2.PostID
		ORDER BY
			t1.TopicIsPinned DESC,
			t2.PostTimestamp DESC
		LIMIT
			$Start,".$this->Modules['Config']->getValue('topics_per_page')
		);
		return $this->Modules['DB']->Raw2Array();
	}

	protected function _authenticateUser(&$ForumData) {
		$AuthData = Functions::getAuthData($ForumData,array('AuthViewForum','AuthPostTopic','AuthIsMod'));
		if($AuthData['AuthViewForum'] != 1) {
			// TODO
			exit;
		}

		return $AuthData;
	}
}

?>