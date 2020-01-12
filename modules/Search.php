<?php
/**
 * Searches for user defined terms in posts and titles with additional options and displays results.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2020 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1.7
 */
class Search implements Module
{
	/**
	 * Detected errors during search operation.
	 *
	 * @var array Error messages
	 */
	private $errors = array();

	/**
	 * Maximum age to consider while searching.
	 *
	 * @var int Age in days (0 = no limit)
	 */
	private $searchAge;

	/**
	 * Terms to search for.
	 *
	 * @var string Search term(s)
	 */
	private $searchFor;

	/**
	 * Contains an unique search ID to continue an ongoing search while reaching the execution time limit of PHP.
	 * Also used to identify an already performed search to avoid searching again.
	 *
	 * @var string Search hash ID
	 */
	private $searchID;

	/**
	 * Forum ID to crawl through or "all" to search entire board.
	 *
	 * @var int|string Target forum ID or "all" forums
	 */
	private $searchIn;

	/**
	 * Defines how to handle the search term(s):
	 * <ul>
	 *  <li>and: All terms need to be found</li>
	 *  <li>or: One of the terms is enough</li>
	 *  <li>exact: The exact search term is required</li>
	 *  <li>user: Look for topics/posts of a specific user</li>
	 * </ul>
	 *
	 * @var string Search option
	 */
	private $searchOption;

	/**
	 * Defines the scope: Both, posts only or titles only.
	 *
	 * @var int Search scope
	 */
	private $searchScope;

	/**
	 * Maximal execution time for searching to require a break to continue.
	 *
	 * @var int Seconds timeout
	 */
	private $timeout;

	/**
	 * Sets search parameters and execution timeout.
	 *
	 * @return Search New instance of this class
	 */
	function __construct()
	{
		$this->searchAge = intval(Functions::getValueFromGlobals('age'));
		$this->searchFor = htmlspecialchars(trim(Functions::getValueFromGlobals('searchfor')));
		$this->searchID = Functions::getValueFromGlobals('searchID');
		$this->searchIn = Functions::getValueFromGlobals('auswahl');
		$this->searchOption = Functions::getValueFromGlobals('searchOption');
		$this->searchScope = intval(Functions::getValueFromGlobals('soption1'));
		if(($this->timeout = ini_get('max_execution_time')) > 10)
			$this->timeout -= 10;
	}

	/**
	 * Checks current execution time of the search progress and reloads it, if needed.
	 *
	 * @param bool $check Check the run time or reload script anyway
	 */
	private function checkTime($check=true)
	{
		//Check execution time limit
		if(!$check || microtime(true)-SCRIPTSTART > $this->timeout)
		{
			header('Location: ' . INDEXFILE . '?faction=search&searchID=' . $this->searchID . SID_AMPER_RAW);
			$_SESSION[$this->searchID]['sTime'] += microtime(true)-SCRIPTSTART;
			exit('<a href="' . INDEXFILE . '?faction=search&amp;searchID=' . $this->searchID . SID_AMPER . '">Go on</a>');
		}
	}

	/**
	 * Performs the search and displays results.
	 */
	public function execute()
	{
		Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('search'), INDEXFILE . '?faction=search' . SID_AMPER);
		//Start or continue a created search
		if(isset($_SESSION[$this->searchID]))
		{
			//Search done?
			if(empty($_SESSION[$this->searchID]['sIn']))
			{
				Main::getModule('NavBar')->addElement(Main::getModule('Language')->getString('search_results'), INDEXFILE . '?faction=search&amp;searchID=' . $this->searchID . SID_AMPER);
				$results = $idTable = array();
				$topicCounter = 0;
				//Refining the results was more complex than the search itself -.O
				foreach($_SESSION[$this->searchID]['sHits'] as $curForumID => $curTopics)
				{
					$idTable[$curForumID][0] = @next(Functions::getForumData($curForumID));
					foreach($curTopics as $curTopicID => $curPosts)
					{
						$curTopicFile = Functions::file('foren/' . $curForumID . '-' . $curTopicID . '.xbb', FILE_IGNORE_NEW_LINES);
						$curTopic = Functions::explodeByTab(array_shift($curTopicFile));
						$idTable[$curForumID][$curTopicID] = Functions::censor($curTopic[1]);
						foreach($curPosts as $curPostID)
						{
							if($curPostID == 0)
							{
								//A topic title was found during search
								$curLastPost = Functions::explodeByTab(end($curTopicFile));
								$results[$curForumID][$curTopicID][0] = array('creator' => Functions::getProfileLink($curTopic[2], true),
									'replies' => count($curTopicFile)-1, //Not counting the first post
									'views' => $curTopic[6],
									'lastPoster' => Functions::getProfileLink($curLastPost[1], true),
									'lastDate' => Functions::formatDate($curLastPost[2]));
							}
							else
								//At least one post was found in current topic during search
								foreach(array_map(array('Functions', 'explodeByTab'), $curTopicFile) as $curKey => $curPost)
								{
									//A post was found during search
									if($curPost[0] == $curPostID)
									{
										$results[$curForumID][$curTopicID][$curPostID] = array('post' => Functions::shorten(Functions::censor(Functions::br2nl(preg_replace("/\[lock\](.*?)\[\/lock\]/si", '', $curPost[3]))), 50),
											'creator' => Functions::getProfileLink($curPost[1], true),
											'date' => Functions::formatDate($curPost[2]),
											'page' => ceil(($curKey+1) / Main::getModule('Config')->getCfgVal('posts_per_page')));
									}
								}
							$topicCounter++;
						}
					}
				}
				Main::getModule('Template')->printPage('SearchResults', array('results' => $results,
					'idTable' => $idTable,
					'forumCounter' => count($_SESSION[$this->searchID]['sHits']),
					'topicCounter' => $topicCounter,
					'isFullScope' => $_SESSION[$this->searchID]['sScp'] == 1,
					'seconds' => $_SESSION[$this->searchID]['sTime']));
			}
			//Search not yet done...
			$switch = intval($_SESSION[$this->searchID]['sScp'] . $_SESSION[$this->searchID]['sOpt']);
			$andCounter = 0;
			$andSize = count($_SESSION[$this->searchID]['sFor']);
			//Search forums
			while(!empty($_SESSION[$this->searchID]['sIn']))
			{
				$this->checkTime();
				$curForumID = key($_SESSION[$this->searchID]['sIn']);
				//Get topics
				if(!isset($_SESSION[$this->searchID]['sIn'][$curForumID][0]))
					$_SESSION[$this->searchID]['sIn'][$curForumID] = array_reverse(Functions::file('foren/' . $curForumID . '-threads.xbb'));
				//Search topics / posts
				while(!empty($_SESSION[$this->searchID]['sIn'][$curForumID]))
				{
					$this->checkTime();
					$curTopicID = current($_SESSION[$this->searchID]['sIn'][$curForumID]);
					$curTopicFile = Functions::file('foren/' . $curForumID . '-' . $curTopicID . '.xbb');
					$curTopicData = Functions::explodeByTab(array_shift($curTopicFile));
					//Only consider non-moved topics and valid time ranges
					if($curTopicData[0] != 'm' && ($_SESSION[$this->searchID]['sAge'] == 0 || $curTopicData[5]+$_SESSION[$this->searchID]['sAge'] >= time()))
					{
						switch($switch)
						{
							//Search text in posts + titles
							case 10:
							//Search text in titles
							case 30:
							foreach($_SESSION[$this->searchID]['sFor'] as $curTerm)
								if(Functions::stripos($curTopicData[1], $curTerm) !== false)
								{
									$andCounter++;
									if(!$_SESSION[$this->searchID]['sAnd'] || ($andCounter == $andSize))
									{
										$_SESSION[$this->searchID]['sHits'][$curForumID][$curTopicID][] = 0;
										break;
									}
								}
							$andCounter = 0;
							if($switch == 30)
								//Exit title search...
								break;
							//...otherwise continue with posts

							//Search text in posts
							case 20:
							foreach($curTopicFile as $curPost)
							{
								$curPost = Functions::explodeByTab($curPost);
								foreach($_SESSION[$this->searchID]['sFor'] as $curTerm)
								{
									if(Functions::stripos($curPost[3], $curTerm) !== false)
									{
										$andCounter++;
										if(!$_SESSION[$this->searchID]['sAnd'] || ($andCounter == $andSize))
											$_SESSION[$this->searchID]['sHits'][$curForumID][$curTopicID][] = $curPost[0];
									}
								}
								$andCounter = 0;
							}
							break;

							//Search user in posts + titles
							case 11:
							//Search user in titles
							case 31:
							foreach($_SESSION[$this->searchID]['sFor'] as $curUserID)
								if($curTopicData[2] == $curUserID)
								{
									$_SESSION[$this->searchID]['sHits'][$curForumID][$curTopicID][] = 0;
									break;
								}
							if($switch == 31)
								//Exit title search...
								break;
							//...otherwise continue with posts

							//Search user in posts
							case 21:
							foreach($_SESSION[$this->searchID]['sFor'] as $curUserID)
								foreach($curTopicFile as $curPost)
								{
									$curPost = Functions::explodeByTab($curPost);
									if($curPost[1] == $curUserID)
										$_SESSION[$this->searchID]['sHits'][$curForumID][$curTopicID][] = $curPost[0];
								}
							break;
						}
					}
					//Topic searched, remove from list
					array_shift($_SESSION[$this->searchID]['sIn'][$curForumID]);
				}
				//Forum searched, remove from list and keep keys (no shift!)
				unset($_SESSION[$this->searchID]['sIn'][key($_SESSION[$this->searchID]['sIn'])]);
			}
			//Search done
			$this->checkTime(false);
		}
		//Create search
		else
		{
			//Build forum list to choose from
			$forums = array();
			foreach(array_map(array('Functions', 'explodeByTab'), Functions::file('vars/foren.var')) as $curForum)
				if(Functions::checkUserAccess($curForum, 0))
					$forums[] = array('forumID' => $curForum[0],
						'forumName' => $curForum[1],
						'catID' => $curForum[5]);
			if(Functions::getValueFromGlobals('search') == 'yes')
			{
				if(empty($this->searchFor))
					$this->errors[] = Main::getModule('Language')->getString('please_enter_a_search_term');
				elseif($this->searchOption != 'user' && Functions::strlen($this->searchFor) < 3)
					$this->errors[] = Main::getModule('Language')->getString('search_term_is_too_short');
				if(empty($this->searchIn))
					$this->errors[] = Main::getModule('Language')->getString('please_select_a_forum');
				if(empty($this->errors))
				{
					//Detect search id and look for previous search, which is already done
					$this->searchID = md5($this->searchAge . $this->searchFor . $this->searchIn . $this->searchOption . $this->searchScope);
					if(!isset($_SESSION[$this->searchID]))
						//Compile search parameters
						$_SESSION[$this->searchID] = array('sAge' => $this->searchAge*3600*24,
							'sFor' => $this->searchOption == 'exact' ? array($this->searchFor) : explode(' ', $this->searchOption == 'user' ? '0' . $this->searchFor : $this->searchFor),
							'sIn' => $this->searchIn == 'all' ? array_map(create_function('$oldKey', 'return array();'), array_flip(array_map('current', $forums))) : array($this->searchIn => array()),
							'sOpt' => $this->searchOption == 'user' ? 1 : 0,
							'sScp' => $this->searchScope,
							'sAnd' => $this->searchOption == 'and',
							'sHits' => array(),
							'sTime' => 0);
					//Here we go
					$this->checkTime(false);
				}
			}
			Main::getModule('Template')->printPage('Search', array('searchAge' => $this->searchAge,
				'searchFor' => htmlspecialchars($this->searchFor),
				'searchIn' => $this->searchIn,
				'searchOption' => $this->searchOption,
				'searchScope' => $this->searchScope,
				'cats' => array_map(array('Functions', 'explodeByTab'), Functions::file('vars/kg.var')),
				'forums' => $forums,
				'errors' => $this->errors));
		}
	}
}
?>