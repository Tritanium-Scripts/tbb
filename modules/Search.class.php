<?php

class Search extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'BBCode',
		'Cache',
		'Config',
		'DB',
		'Language',
		'Navbar',
		'Template'
	);
	
	public function executeMe() {
		if($this->modules['Config']->getValue('search_status') == 0) {
			FuncMisc::printMessage('function_deactivated');
			exit;
		}
		elseif(!$this->modules['Auth']->isLoggedIn() && $this->modules['Config']->getValue('search_status') == 1) {
			FuncMisc::printMessage('not_logged_in');
			exit;
		}

		$this->modules['Language']->addFile('Search');
		$this->modules['Language']->addFile('ViewForum');

		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Search'),INDEXFILE.'?action=Search&amp;'.MYSID);

		switch(@$_GET['mode']) {
			default:
				$this->modeDefault();
				break;
		
			case 'ViewResults':
				$this->modeViewResults();
				break;
		}
	}
	
	protected function modeDefault() {
		$p = Functions::getSGValues($_REQUEST['p'],array('searchWords','displayResults','searchAuthorPosts','searchAuthorTopics'),'');
		$p['searchForums'] = isset($_POST['p']['searchForums']) && is_array($_POST['p']['searchForums']) ? $_POST['p']['searchForums'] : array('all');
		$p['searchMethod'] = isset($_POST['p']['searchMethod']) ? $_POST['p']['searchMethod'] : 2;
		$p += Functions::getSGValues($_POST['p'],array('searchWordsExact','searchSortMethod'),0);

		// 0: nur titel
		// 1: nur Beitraege
		// 2: posts und titel

		$authedForumsIDs = $this->modules['Auth']->getAuthedForumsIDs();
		$targetForumsIDs = array();
		
		$error = '';

		if(isset($_GET['doit'])) {
			$p += Functions::getSGValues($_POST['p'],array('searchWordsExact'),0);

			if(in_array('all',$p['searchForums'])) $targetForumsIDs = $authedForumsIDs;
			else $targetForumsIDs = array_intersect($p['searchForums'],$authedForumsIDs);
			if(count($targetForumsIDs) == 0) $targetForumsIDs = $authedForumsIDs;

			$searchWords = explode(' ',preg_replace('/[ ]{2,}/',' ',trim($p['searchWords'])));

			foreach($searchWords AS $key => &$curWord) {
				if(strlen($curWord) < 4 || preg_match('/^[*]{1,}$/',$curWord)) {
					unset($searchWords[$key]);
					continue;
				}

				$curWord = $this->modules['DB']->escapeString($curWord);
				$curWord = str_replace('%','\%',$curWord);
				$curWord = str_replace('*','%',$curWord);

				if($p['searchWordsExact'] != 1)
					$curWord = '%'.$curWord.'%';
			}

			$queryWords = '';
			if(count($searchWords) != 0) {
				$queryWords = array();
				foreach($searchWords AS &$curWord) {
					if($p['searchMethod'] == 0 || $p['searchMethod'] == 2) $queryWords[] = 't1."postTitle" LIKE \''.$curWord.'\'';
					if($p['searchMethod'] == 1 || $p['searchMethod'] == 2) $queryWords[] = 't1."postText" LIKE \''.$curWord.'\'';
				}
				$queryWords = ' AND ('.implode(' OR ',$queryWords).')';
			}

			$queryAuthor = '';
			if($p['searchAuthorPosts'] != '' && ($authorIDPosts = FuncUsers::getUserID($p['searchAuthorPosts']))) {
				$queryAuthor = ' AND t1."posterID"=\''.$authorIDPosts.'\'';
			}

			if($p['searchAuthorTopics'] != '' && ($authorIDTopics = FuncUsers::getUserID($p['searchAuthorTopics']))) {
				$this->modules['DB']->query('
					SELECT
						t1."postID"
					FROM (
						'.TBLPFX.'posts t1,
						'.TBLPFX.'topics t2
					) WHERE
						t1."forumID" IN (\''.implode("','",$targetForumsIDs).'\')'.$queryAuthor.$queryWords.'
						AND t1."topicID"=t2."topicID"
						AND t2."posterID"=\''.$authorIDTopics.'\'
				');
			} else {
				$this->modules['DB']->query('
					SELECT
						t1."postID"
					FROM
						'.TBLPFX.'posts t1
					WHERE
						t1."forumID" IN (\''.implode("','",$targetForumsIDs).'\')'.$queryAuthor.$queryWords
				);
			}
			$foundPostsIDs = $this->modules['DB']->raw2FVArray();


			if(count($foundPostsIDs) == 0) $error = $this->modules['Language']->getString('error_no_search_results');
			else {
				$newSearchID = Functions::getRandomString(32,TRUE);
				$this->modules['DB']->queryParams('
					INSERT INTO '.TBLPFX.'search_results SET
						"searchID"=$1,
						"sessionID"=$2,
						"searchLastAccess"=NOW(),
						"searchResults"=$3
				',array(
					$newSearchID,
					session_id(),
					implode(',',$foundPostsIDs)
				));
				Functions::myHeader(INDEXFILE.'?action=Search&mode=ViewResults&searchID='.$newSearchID.'&displayResults='.$p['displayResults'].'&'.MYSID); exit;
			}
		}

		$this->modules['DB']->queryParams('
			SELECT
				"forumID",
				"catID",
				"forumName"
			FROM
				'.TBLPFX.'forums
			WHERE
				"forumID" IN $1
		',array(
			$authedForumsIDs
		));
		$authedForumsData = $this->modules['DB']->raw2Array();
		foreach($authedForumsData AS &$curForum)
			$curForum['forumName'] = Functions::HTMLSpecialChars($curForum['forumName']);
		
		$catsData = FuncCats::getCatsData();
		foreach($catsData AS &$curCat)
			$curCat['catName'] = Functions::HTMLSpecialChars($curCat['catName']);

		$this->modules['Template']->assign(array(
			'error'=>$error,
			'p'=>$p,
			'forumsData'=>$authedForumsData,
			'catsData'=>$catsData
		));
		$this->modules['Template']->printPage('Search.tpl');
	}
	
	protected function modeViewResults() {
		$searchID = isset($_GET['searchID']) ? $_GET['searchID'] : '';
		
		$this->modules['DB']->queryParams('
			SELECT
				*
			FROM
				'.TBLPFX.'search_results
			WHERE
				"searchID"=$1
		',array(
			$searchID
		));
		($this->modules['DB']->numRows() == 0) ? die('Cannot load data: search results') : $searchData = $this->modules['DB']->fetchArray();

		$this->modules['DB']->queryParams('
			UPDATE
				'.TBLPFX.'search_results
			SET
				"searchLastAccess"=NOW()
			WHERE
				"searchID"=$1
		',array(
			$searchID
		));
	
		$displayResults = isset($_REQUEST['displayResults']) ? $_REQUEST['displayResults'] : 'topics';
		$sortMethod = isset($_REQUEST['sortMethod']) ? $_REQUEST['sortMethod'] : 'DESC';
		$sortType = isset($_REQUEST['sortType']) ? $_REQUEST['sortType'] : 'time';
		$resultsPerPage = isset($_REQUEST['resultsPerPage']) ? intval($_REQUEST['resultsPerPage']) : 20;
	
		if(!in_array($sortType,array('time','timeCreation','author','title'))) $sortType = 'time';
		if(!in_array($displayResults,array('topics','posts'))) $displayResults = 'topics';
		if(!in_array($sortMethod,array('ASC','DESC'))) $sortMethod = 'DESC';
		
		$authedForumsIDs = $this->modules['Auth']->getAuthedForumsIDs();
	
		$this->modules['Navbar']->addElement($this->modules['Language']->getString('View_search_results'),INDEXFILE.'?action=Search&amp;mode=ViewResults&amp;searchID='.$searchID.'&amp;'.MYSID);
		
		$this->modules['Template']->assign(array(
			'displayResults'=>$displayResults,
			'sortMethod'=>$sortMethod,
			'sortType'=>$sortType,
			'resultsPerPage'=>$resultsPerPage,
			'searchID'=>$searchID
		));
	
		if($displayResults == 'topics') {
			$querySortType = '';
			if($sortType == 'time') $querySortType = 't2."postTimestamp"';
			elseif($sortType == 'timeCreation') $querySortType = 't1."topicPostTimestamp"';
			elseif($sortType == 'title') $querySortType = 't1."topicTitle"';
			else $querySortType = 't3."userNick"'.' '.$sortMethod.', t1."topicGuestNick"';
	
			$topicsData = array();
			$this->modules['DB']->queryParams('
				SELECT
					t1."topicID",
					t1."topicTitle",
					t1."topicLastPostID",
					t1."topicRepliesCounter",
					t1."topicViewsCounter",
					t1."topicIsPinned",
					t1."topicGuestNick",
					t1."topicHasPoll",
					t1."posterID",
					t3."userNick" AS "posterNick",
					t2."posterID" AS "topicLastPostPosterID",
					t2."postGuestNick" AS "topicLastPostGuestNick",
					t2."postTimestamp" AS "topicLastPostTimestamp",
					t4."userNick" AS "topicLastPostPosterNick"
				FROM (
					'.TBLPFX.'topics t1
				)
				LEFT JOIN '.TBLPFX.'posts t2 ON t2."postID"=t1."topicLastPostID"
				LEFT JOIN '.TBLPFX.'users t3 ON t1."posterID"=t3."userID"
				LEFT JOIN '.TBLPFX.'users t4 ON t2."posterID"=t4."userID"
				WHERE
					t1."topicID" IN (
						SELECT DISTINCT
							t5."topicID"
						FROM
							'.TBLPFX.'posts t5
						WHERE
							t5."postID" IN $1
					)
					AND t1."forumID" IN $2
				ORDER BY '.$querySortType.' '.$sortMethod.'
				LIMIT 0,'.intval($resultsPerPage).'
			',array(
				explode(',',$searchData['searchResults']),
				$authedForumsIDs
			));
	
			while($curTopic = $this->modules['DB']->fetchArray()) {
				$curTopicPrefix = '';
				if($curTopic['topicIsPinned'] == 1) $curTopicPrefix .= $this->modules['Language']->getString('Prefix_important');
				if($curTopic['topicHasPoll'] == 1) $curTopicPrefix .= $this->modules['Language']->getString('Prefix_poll');
				$curTopic['_topicPrefix'] = $curTopicPrefix;
	
				$curTopic['_topicPoster'] = ($curTopic['posterID'] == 0) ? $curTopic['topicGuestNick'] : '<a href="'.INDEXFILE.'?action=ViewProfile&amp;profileID='.$curTopic['posterID'].'&amp;'.MYSID.'">'.$curTopic['posterNick'].'</a>';
	
				if($curTopic['topicLastPostPosterID'] == 0)
					$curTopicLastPostPoster = $curTopic['topicLastPostGuestNick'];
				else $curTopicLastPostPoster = '<a href="'.INDEXFILE.'?action=ViewProfile&amp;profileID='.$curTopic['topicLastPostPosterID'].'&amp;'.MYSID.'">'.$curTopic['topicLastPostPosterNick'].'</a>';
				$curTopic['_topicLastPost'] = Functions::toDateTime($curTopic['topicLastPostTimestamp']).'<br/>'.$this->modules['Language']->getString('by').' '.$curTopicLastPostPoster.' <a href="'.INDEXFILE.'?action=ViewTopic&amp;topicID='.$curTopic['topicID'].'&amp;z=last&amp;'.MYSID.'#post'.$curTopic['topicLastPostID'].'">&#187;</a>';
				
				$topicsData[] = $curTopic;
			}
			
			$this->modules['Template']->assign(array(
				'topicsData'=>$topicsData
			));
			$this->modules['Template']->printPage('SearchViewResultsTopics.tpl');		
		}
		elseif($displayResults == 'posts') {
			$this->modules['Language']->addFile('ViewTopic');
			
			$querySortType = '';
			if($sortType == 'time' || $sortType == 'timeCreation') $querySortType = 't1."postTimestamp"';
			elseif($sortType == 'title') $querySortType = 't1."postTitle"';
			else $querySortType = 't2."userNick"'.' '.$sortMethod.', t1."postGuestNick"';
			
			$smiliesData = $this->modules['Cache']->getSmiliesData('write');
	
			$postsData = array();
			$this->modules['DB']->queryParams('
				SELECT
					t1."postID",
					t1."postTitle",
					t1."posterID",
					t1."postTimestamp",
					t1."postGuestNick",
					t1."postText",
					t1."postEnableHtmlCode",
					t1."postEnableSmilies",
					t1."postEnableBBCode",
					t2."userNick" AS "posterNick",
					t3."forumEnableBBCode",
					t3."forumEnableSmilies",
					t3."forumEnableHtmlCode"
				FROM (
					'.TBLPFX.'posts t1
				)
				LEFT JOIN '.TBLPFX.'users t2 ON t1."posterID"=t2."userID"
				LEFT JOIN '.TBLPFX.'forums t3 ON t1."forumID"=t3."forumID"
				WHERE
					t1."postID" IN $1
					AND t1."forumID" IN $2
				ORDER BY '.$querySortType.' '.$sortMethod.'
				LIMIT 0,'.intval($resultsPerPage).'
			',array(
				explode(',',$searchData['searchResults']),
				$authedForumsIDs
			));
			$postsData = $this->modules['DB']->raw2Array();

			foreach($postsData AS &$curPost) {
				$curPost['_postDateTime'] = Functions::toDateTime($curPost['postTimestamp']);
				$curPost['_postPoster'] = ($curPost['posterID'] == 0 ? $curPost['postGuestNick'] : '<a href="'.INDEXFILE.'?action=ViewProfile&amp;profileID='.$curPost['posterID'].'&amp;'.MYSID.'">'.$curPost['posterNick'].'</a>');
				
				$curPost['_postText'] = $this->modules['BBCode']->format($curPost['postText'], ($curPost['postEnableHtmlCode'] == 1 || $curPost['forumEnableHtmlCode'] == 1), ($curPost['postEnableSmilies'] == 1 && $curPost['forumEnableSmilies'] == 1), ($curPost['postEnableBBCode'] == 1 && $curPost['forumEnableBBCode'] == 1));
				//if($curPost['post_enable_urltransformation'] == 1  && ($forum_id == 0 || $forumData['forum_enable_urltransformation'] == 1)) $curPost['post_text'] = transform_urls($curPost['post_text']);
			}

			$this->modules['Template']->assign(array(
				'postsData'=>$postsData
			));
			$this->modules['Template']->printPage('SearchViewResultsPosts.tpl');
		}
	}
}

?>