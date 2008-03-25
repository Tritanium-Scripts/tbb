<?php

class Search extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Config',
		'DB',
		'Language',
		'Navbar',
		'Template'
	);
	
	public function executeMe() {
		//FuncMisc::printMessage('function_deactivated');
		//exit;
		
		if($this->modules['Config']->getValue('search_status') == 0) {
			FuncMisc::printMessage('function_deactivated');
			exit;
		}
		elseif(!$this->modules['Auth']->isLoggedIn() && $this->modules['Config']->getValue('search_status') == 1) {
			FuncMisc::printMessage('not_logged_in');
			exit;
		}

		$this->modules['Language']->addFile('Search');
		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Search'),INDEXFILE.'?action=Search&amp;'.MYSID);

		switch(@$_GET['mode']) {
			default:
				$p = Functions::getSGValues($_POST['p'],array('searchWords','searchAuthor','displayResults'),'');
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
							if($p['searchMethod'] == 0 || $p['searchMethod'] == 2) $queryWords[] = '"postTitle" LIKE \''.$curWord.'\'';
							if($p['searchMethod'] == 1 || $p['searchMethod'] == 2) $queryWords[] = '"postText" LIKE \''.$curWord.'\'';
						}
		
						$queryWords = implode(' OR ',$queryWords);
		
						$this->modules['DB']->query('SELECT "postID" FROM '.TBLPFX.'posts WHERE '.$queryWords);
						$foundPostsIDs = $this->modules['DB']->raw2FVArray();
		
						$queryWords = ' AND "postID" IN (\''.implode("','",$foundPostsIDs).'\')';
					}
		
					$queryAuthor = '';
					if($p['searchAuthor'] != '') {
						if($authorID = FuncUsers::getUserID($p['searchAuthor']))
							$queryAuthor = ' AND "posterID"=\''.$authorID.'\'';
					}
		
					$this->modules['DB']->query('
						SELECT
							"postID"
						FROM
							'.TBLPFX.'posts
						WHERE
							"forumID" IN (\''.implode("','",$targetForumsIDs).'\')'.$queryAuthor.$queryWords
					);
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
						Functions::myHeader(INDEXFILE.'?action=Search&mode=ViewResults&searchID='.$newSearchID.'&'.MYSID); exit;
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
				
				$catsData = FuncCats::getCatsData();
		
				$this->modules['Template']->assign(array(
					'error'=>$error,
					'p'=>$p,
					'forumsData'=>$authedForumsData,
					'catsData'=>$catsData
				));
				$this->modules['Template']->printPage('Search.tpl');
			break;
		
			case 'ViewResults':
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
				//if($search_data['session_id'] != session_id()) die('Sie sind nicht berechtigt diese Suchergebnisse zu sehen!'); // ...und ueberpruefen, ob die Session die Suchergebnisse auswerten darf
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
		
				if(!in_array($sortType,array('time','author','title'))) $sortType = 'time';
				if(!in_array($displayResults,array('topics','posts'))) $displayResults = 'topics';
				if(!in_array($sortMethod,array('ASC','DESC'))) $sortMethod = 'DESC';

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
					if($sortType == 'time') $querySortType = 't2."postID"';
					elseif($sortType == 'title') $querySortType = 't1."topicTitle"';
					else $querySortType = 't1."posterID"';

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
						ORDER BY '.$querySortType.' '.$sortMethod.'
						LIMIT 0,'.intval($resultsPerPage).'
					',array(
						explode(',',$searchData['searchResults'])
					));

					while($curTopic = $this->modules['DB']->fetchArray()) {
						$curTopicPrefix = '';
						if($curTopic['topicIsPinned'] == 1) $curTopicPrefix .= $this->modules['Language']->getString('Important').': ';
						if($curTopic['topicHasPoll'] == 1) $curTopicPrefix .= $this->modules['Language']->getString('Poll').': ';
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
					// TODO: View search results as posts
				}
				break;
		}
	}
}
?>