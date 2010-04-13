<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class EditTopic extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'DB',
		'Language',
		'Navbar',
		'Template'
	);

	public function executeMe() {
		$topicID = isset($_GET['topicID']) ? $_GET['topicID'] : 0;
		$mode = isset($_GET['mode']) ? $_GET['mode'] : 'edit';

		if($this->modules['Auth']->isLoggedIn() != 1) die('Access denied: not logged in');
		elseif(!$topicData = FuncTopics::getTopicData($topicID)) die('Cannot load data: topic');
		elseif($topicData['topicMovedID'] != 0) die('Cannot edit topic: moved topic');
		elseif(!$forumData = FuncForums::getForumData($topicData['forumID'])) die('Cannot load data: forum');

		$forumID = &$topicData['forumID'];

		$authData = Functions::getAuthData($forumData,array('authEditPosts','authIsMod'));

		$this->modules['Navbar']->addCategories($forumData['catID']);
		$this->modules['Navbar']->addElements(
			array(Functions::HTMLSpecialChars($forumData['forumName']),INDEXFILE."?action=ViewForum&amp;forumID=$forumID&amp;".MYSID),
			array(Functions::HTMLSpecialChars($topicData['topicTitle']),INDEXFILE."?action=ViewTopic&amp;topicID=$topicID&amp;".MYSID)
		);

		$this->modules['Language']->addFile('EditTopic');

		if($mode == 'Edit') {
			if((USERID != $topicData['posterID'] || $authData['authEditPosts'] != 1) && $this->modules['Auth']->getValue('userIsAdmin') != 1 && $this->modules['Auth']->getValue('userIsSupermod') != 1 && $authData['authIsMod'] != 1) die('Access denied: unsufficient rights');

			$error = '';

			$p = Functions::getSGValues($_POST['p'],array('topicTitle','smileyID'),'',$topicData);
			$c = array();

			if($topicData['topicHasPoll'] == 1) {
				$this->modules['Language']->addFile('Posting');

				$this->modules['DB']->queryParams('SELECT * FROM '.TBLPFX.'polls_options WHERE "topicID"=$1 ORDER BY "optionID" ASC', array($topicID));
				$optionsData = $this->modules['DB']->raw2Array();

				$p['optionsData'] = array();

				foreach($optionsData AS $curOption)
					$p['optionsData'][$curOption['optionID']] = isset($_POST['p']['optionsData'][$curOption['optionID']]) ? $_POST['p']['optionsData'][$curOption['optionID']] : $curOption['optionTitle'];

				$p['topicPollDuration'] = isset($_POST['p']['topicPollDuration']) ? intval($_POST['p']['topicPollDuration']) : (($topicData['topicPollEndTimestamp']-$topicData['topicPollStartTimestamp'])/86400);

				$p += Functions::getSGValues($_POST['p'],array('topicPollTitle'),'',$topicData);
				$c = Functions::getSGValues($topicData,array('topicPollGuestsVote','topicPollShowResultsAfterEnd','topicPollGuestsViewResults'),0);

				$this->modules['Template']->assign('optionsData',$optionsData);
			}

			if(isset($_GET['doit'])) {
				if($topicData['topicHasPoll'] == 1) {
					$optionTitleMissing = FALSE;
					foreach($p['optionsData'] AS &$curOption) {
						if(trim($curOption) == '') {
							$optionTitleMissing = TRUE;
							break;
						}
					}
				}

				$c = Functions::getSGValues($_POST['c'],array('topicPollGuestsVote','topicPollShowResultsAfterEnd','topicPollGuestsViewResults'),0);

				if(trim($p['topicTitle']) == '') $error = $this->modules['Language']->getString('error_no_title');
				elseif($topicData['topicHasPoll'] == 1 && trim($p['topicPollTitle']) == '') $error = $this->modules['Language']->getString('error_poll_title_missing');
				elseif($topicData['topicHasPoll'] == 1 && $optionTitleMissing) $error = $this->modules['Language']->getString('error_poll_option_missing');
				elseif($topicData['topicHasPoll'] == 1 && $p['topicPollDuration'] <= 0) $error = $this->modules['Language']->getString('error_invalid_poll_duration');
				else {
					$this->modules['DB']->queryParams('
						UPDATE
							'.TBLPFX.'topics
						SET
							"topicTitle"=$1,
							"smileyID"=$2
						WHERE
							"topicID"=$3
					', array(
						$p['topicTitle'],
						$p['smileyID'],
						$topicID
					));

					$this->modules['DB']->queryParams('
						UPDATE
							'.TBLPFX.'posts
						SET
							"postTitle"=$1,
							"smileyID"=$2
						WHERE
							"postID"=$3
					', array(
						$p['topicTitle'],
						$p['smileyID'],
						$topicData['topicFirstPostID']
					));

					if($topicData['topicHasPoll'] == 1) {
						$this->modules['DB']->queryParams('
							UPDATE
								'.TBLPFX.'topics
							SET
								"topicPollTitle"=$1,
								"topicPollEndTimestamp"=$2,
								"topicPollGuestsVote"=$3,
								"topicPollGuestsViewResults"=$4,
								"topicPollShowResultsAfterEnd"=$5
							WHERE
								"topicID"=$6
						', array(
							$p['topicPollTitle'],
							$topicData['topicPollStartTimestamp']+$p['topicPollDuration']*86400,
							$c['topicPollGuestsVote'],
							$c['topicPollGuestsViewResults'],
							$c['topicPollShowResultsAfterEnd'],
							$topicID
						));

						foreach($p['optionsData'] AS $curKey => $curValue)
							$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'polls_options SET "optionTitle"=$1 WHERE "topicID"=$2 AND "optionID"=$3', array($curValue, $topicID, $curKey));
					}

					Functions::myHeader(INDEXFILE."?action=ViewTopic&topicID=$topicID&".MYSID);
				}
			}

			$postPicsBox = Functions::getPostPicsBox($p['smileyID']);

			$this->modules['Navbar']->addElement($this->modules['Language']->getString('edit_topic'),'');

			$this->modules['Template']->assign(array(
				'p'=>Functions::HTMLSpecialChars($p),
				'c'=>$c,
				'error'=>$error,
				'postPicsBox'=>$postPicsBox,
				'topicID'=>$topicID,
				'topicData'=>$topicData
			));
			$this->modules['Template']->printPage('EditTopicEdit.tpl');
		}
		else {
			if($this->modules['Auth']->getValue('userIsAdmin') != 1 && $authData['authIsMod'] != 1 && $this->modules['Auth']->getValue('userIsSupermod') != 1) die('Access denied: insufficient rights');
			switch(@$_GET['mode']) {
				case 'Pinn':
					$this->modules['DB']->queryParams('
						UPDATE
							'.TBLPFX.'topics
						SET
							"topicIsPinned"=$1
						WHERE
							"topicID"=$2
					', array(
						($topicData['topicIsPinned'] == 1) ? 0 : 1,
						$topicID
					));

					Functions::myHeader(INDEXFILE."?action=ViewTopic&topicID=$topicID&".MYSID);
				break;

				case 'OpenClose':
					$topicIsClosed = ($topicData['topicIsClosed'] == 1) ? 0 : 1;

					$this->modules['DB']->queryParams('
						UPDATE
							'.TBLPFX.'topics
						SET
							"topicIsClosed"=$1
						WHERE
							"topicID"=$2
					', array(
						$topicIsClosed,
						$topicID
					));

					Functions::myHeader(INDEXFILE."?action=ViewTopic&topicID=$topicID&".MYSID);
				break;

				case 'Delete':
					$this->modules['DB']->queryParams('SELECT "postID" FROM '.TBLPFX.'posts WHERE "topicID"=$1', array($topicID));
					$postIDs = $this->modules['DB']->raw2FVArray();
					$postsCounter = count($postIDs);

					/*$this->modules['DB']->queryParams('SELECT COUNT(*) AS "posterPostsCounter", "posterID" FROM '.TBLPFX.'posts WHERE "topicID"=$1 GROUP BY "posterID"', array($topicID));
					$usersPostsCounter = $this->modules['DB']->raw2Array();
					foreach($usersPostsCounter AS $curCounter) {
						$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'users SET "userPostsCounter"="userPostsCounter"-$1 WHERE "userID"=$2', array($curCounter['posterPostsCounter'], $curCounter['posterID']));
					}*/

					if($topicData['topicHasPoll'] == 1) {
						$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'polls_options WHERE "topicID"=$1', array($topicID));
						$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'polls_votes WHERE "topicID"=$1', array($topicID));
					}

					$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'files_posts WHERE "postID" IN $1',array($postIDs));
					$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'forums SET "forumPostsCounter"="forumPostsCounter"-$1, "forumTopicsCounter"="forumTopicsCounter"-1 WHERE "forumID"=$2', array($postsCounter, $forumID));
					$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'posts WHERE "topicID"=$1', array($topicID));
					$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'topics_subscriptions WHERE "topicID"=$1', array($topicID));
					$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'topics WHERE "topicMovedID"=$1', array($topicID));
					$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'topics WHERE "topicID"=$1', array($topicID));


					if(in_array($forumData['forumLastPostID'],$postIDs))
						FuncForums::updateLastPost($forumID);

					Functions::myHeader(INDEXFILE."?action=ViewForum&forumID=$forumID&".MYSID);
				break;

				case 'Move':
					$p = Functions::getSGValues($_POST['p'],array('targetForumID'),0);
					$c = Functions::getSGValues($_POST['c'],array('createReference'),1);

					$error = '';

					$this->modules['Navbar']->addElement($this->modules['Language']->getString('move_topic'),INDEXFILE."action=EditTopic&amp;topicID=$topicID&amp;mode=Move&amp;".MYSID);

					if(isset($_GET['doit'])) {
						if(!$targetForumData = FuncForums::getForumData($p['targetForumID'])) $error = $this->modules['Language']->getString('error_invalid_forum');
						else {
							$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'topics SET "forumID"=$1 WHERE "topicID"=$2', array($p['targetForumID'], $topicID));
							$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'posts SET "forumID"=$1 WHERE "topicID"=$2', array($p['targetForumID'], $topicID));

							$this->modules['DB']->queryParams('SELECT COUNT(*) AS "topicPostsCounter" FROM '.TBLPFX.'posts WHERE "topicID"=$1', array($topicID));
							list($topicPostsCounter) = $this->modules['DB']->fetchArray();

							$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'forums SET "forumTopicsCounter"="forumTopicsCounter"-1, "forumPostsCounter"="forumPostsCounter"-$1 WHERE "forumID"=$2', array($topicPostsCounter, $forumID));
							$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'forums SET "forumTopicsCounter"="forumTopicsCounter"+1, "forumPostsCounter"="forumPostsCounter"+$1 WHERE "forumID"=$2', array($topicPostsCounter, $p['targetForumID']));

							if($c['createReference'] == 1) {
								$slashedTopicData = $topicData;
								$this->modules['DB']->queryParams('
									INSERT INTO
										'.TBLPFX.'topics
									SET
										"forumID"=$1,
										"posterID"=$2,
										"topicIsClosed"=$3,
										"topicIsPinned"=$4,
										"smileyID"=$5,
										"topicRepliesCounter"=$6,
										"topicViewsCounter"=$7,
										"topicHasPoll"=$8,
										"topicFirstPostID"=$9,
										"topicLastPostID"=$10,
										"topicMovedID"=$11,
										"topicMovedTimestamp"=$12,
										"topicPostTimestamp"=$13,
										"topicTitle"=$14,
										"topicGuestNick"=$15
								',array(
									$forumID,
									$topicData['posterID'],
									$topicData['topicIsClosed'],
									$topicData['topicIsPinned'],
									$topicData['smileyID'],
									$topicData['topicRepliesCounter'],
									$topicData['topicViewsCounter'],
									$topicData['topicHasPoll'],
									$topicData['topicFirstPostID'],
									$topicData['topicLastPostID'],
									$topicData['topicID'],
									time(),
									$topicData['topicPostTimestamp'],
									$topicData['topicTitle'],
									$topicData['topicGuestNick']
								));
							}

							FuncForums::updateLastPost($forumID);
							FuncForums::updateLastPost($p['targetForumID']);

							FuncMisc::printMessage('topic_moved',array(sprintf($this->modules['Language']->getString('message_link_click_here_moved_topic'),'<a href="'.INDEXFILE."?action=ViewTopic&amp;topicID=$topicID&amp;".MYSID.'">','</a>')));
							exit;
						}
					}


					//
					// Kategorie- und Forendaten laden
					//
					$catsData = FuncCats::getCatsData();
					$this->modules['DB']->queryParams('SELECT "forumID", "forumName", "catID" FROM '.TBLPFX.'forums WHERE "forumID"<>$1', array($forumID));
					$forumsData = $this->modules['DB']->raw2Array();


					//
					// Auswahlmenue fuer das Zielforum erstellen
					//
					$selectOptions = array();
					foreach($catsData AS $curCat) {
						$curPrefix = '';
						for($i = 1; $i < $curCat['catDepth']; $i++)
							$curPrefix .= '--';

						$selectOptions[] = array('',$curPrefix.' ('.Functions::HTMLSpecialChars($curCat['catName']).')');

						while(list($curKey,$curForum) = each($forumsData)) {
							if($curForum['catID'] == $curCat['catID']) {
								$selectOptions[] = array($curForum['forumID'],$curPrefix.'-- '.Functions::HTMLSpecialChars($curForum['forumName']));
								unset($forumsData[$curKey]);
							}
						}
						reset($forumsData);
					}

					// Foren ohne Kategorie an den Schluss haengen
					if(count($forumsData) > 0) {
						$selectOptions[] = array('','');

						foreach($forumsData AS $curForum)
							$selectOptions[] = array($curForum['forumID'],$curForum['forumName']);
					}

					$this->modules['Template']->assign(array(
						'c'=>$c,
						'p'=>$p,
						'error'=>$error,
						'selectOptions'=>$selectOptions,
						'topicID'=>$topicID
					));

					$this->modules['Template']->printPage('EditTopicMove.tpl');
					break;
			}
		}
	}
}