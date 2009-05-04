<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class Posting extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'DB',
		'Cache',
		'Config',
		'Constants',
		'BBCode',
		'Language',
		'Navbar',
		'Template'
	);

	public function executeMe() {
		$mode = isset($_GET['mode']) ? $_GET['mode'] : '';
		if(!in_array($mode,array('Topic','Reply','Edit','Delete'))) $mode = 'Topic';

		// Alle angegebenen IDs bestimmen (normalerweise ist immer nur eine ID wichtig
		$forumID = isset($_GET['forumID']) ? intval($_GET['forumID']) : 0;
		$topicID = isset($_GET['topicID']) ? intval($_GET['topicID']) : 0;
		$postID = isset($_GET['postID']) ? intval($_GET['postID']) : 0;

		switch($mode) {
			case 'Delete':
			case 'Edit':
				if(!$postData = Functions::getPostData($postID)) die('Kann Daten nicht laden: Beitrag');
				$topicID = &$postData['topicID'];
			case 'Reply':
				if(!$topicData = FuncTopics::getTopicData($topicID)) die('Kann Daten nicht laden: Thema');
				$forumID = &$topicData['forumID'];
			case 'Topic':
				if(!$forumData = FuncForums::getForumData($forumID)) die('Kann Daten nicht laden: Forum');
				break;
		}

		$this->modules['Language']->addFile('Posting');
		$this->modules['Language']->addFile('ViewTopic');
		$this->modules['Language']->addFile('Messages');

		$authData = $this->_authenticateUser($mode,$forumData);
		
		// You don't need to check if the user is still locked because if so the lock was removed in Auth.class.php
		if($this->modules['Auth']->getValue('userIsLocked') == LOCK_TYPE_NO_POSTING) {
			if($this->modules['Auth']->getValue('userLockStartTimestamp') == $this->modules['Auth']->getValue('userLockEndTimestamp'))
				FuncMisc::printMessage('locked_forever_no_posts');
			else
				FuncMisc::printMessage(array(
					$this->modules['Language']->getString('message_title_locked_until_no_posts'),
					sprintf($this->modules['Language']->getString('message_text_locked_until_no_posts'), Functions::toDateTime($this->modules['Auth']->getValue('userLockEndTimestamp')))
				));

			exit;
		}
		if($mode == 'Reply' && $topicData['topicIsClosed'] == 1 && $authData['authIsMod'] != 1 && $this->modules['Auth']->getValue('userIsSuperadmin') && $this->modules['Auth']->getValue('userIsSupermod')) {
			FuncMisc::printMessage('topic_closed'); exit;
		}

		switch($mode) {
			default:
				$error = '';

				//
				// Alle uebergebenen Daten laden
				//
				$p = array();

				$p['messageText'] = '';
				if(isset($_POST['p']['messageText']))
					$p['messageText'] = $_POST['p']['messageText'];
				else {
					if($mode == 'Edit') $p['messageText'] = $postData['postText'];
					elseif($mode == 'Reply' && isset($_GET['postIDQuote'])) {
						$this->modules['DB']->queryParams('
							SELECT
								t1."posterID",
								t1."postGuestNick",
								t1."postText",
								t2."userNick"
							FROM
								'.TBLPFX.'posts t1
							LEFT JOIN '.TBLPFX.'users t2 ON t1."posterID"=t2."userID"
							WHERE
								t1."postID"=$1
								AND t1."topicID"=$2
						',array(
							$_GET['postIDQuote'],
							$topicID
						));
						if($this->modules['DB']->numRows() == 1) {
							$postDataQuote = $this->modules['DB']->fetchArray();
							$quoteNick = ($postDataQuote['posterID'] == 0 ? $postDataQuote['postGuestNick'] : $postDataQuote['userNick']);
							$p['messageText'] = '[quote='.$quoteNick.']'.$postDataQuote['postText'].'[/quote]';
						}
					}
				}
				//$p['messageText'] = isset($_POST['p']['messageText']) ? $_POST['p']['messageText'] : (($mode == 'Edit') ? $postData['postText'] : '');
				
				$p['messageTitle'] = isset($_POST['p']['messageTitle']) ? $_POST['p']['messageTitle'] : (($mode == 'Edit') ? $postData['postTitle'] : (($mode == 'Reply') ? 'Re: '.$topicData['topicTitle'] : ''));
				$p['guestNick'] = isset($_POST['p']['guestNick']) ? $_POST['p']['guestNick'] : '';
				$p['smileyID'] = isset($_POST['p']['smileyID']) ? intval($_POST['p']['smileyID']) : (($mode == 'Edit') ? $postData['smileyID'] : '');
				$p['pollTitle'] = isset($_POST['p']['pollTitle']) ? $_POST['p']['pollTitle'] : '';
				$p['pollOptions'] = (isset($_POST['p']['pollOptions']) && is_array($_POST['p']['pollOptions'])) ? $_POST['p']['pollOptions'] : array();

				$p['pollDuration'] = isset($_POST['p']['pollDuration']) ? intval($_POST['p']['pollDuration']) : 1;

				$subscriptionStatus = ($mode == 'Reply' && Functions::getSubscriptionStatus(SUBSCRIPTION_TYPE_TOPIC,USERID,$topicID)) ? 1 : 0;

				$c['showEditings'] = ($mode == 'Edit') ? $postData['postShowEditings'] : 1;
				$c['enableURITransformation'] = ($mode == 'Edit') ? $postData['postEnableURITransformation'] : 1;
				$c['enableSmilies'] = ($mode == 'Edit') ? $postData['postEnableSmilies'] : 1;
				$c['showSignature'] = ($mode == 'Edit') ? $postData['postShowSignature'] : 1;
				$c['enableBBCode'] = ($mode == 'Edit') ? $postData['postEnableBBCode'] : 1;
				$c['enableHtmlCode'] = ($mode == 'Edit') ? $postData['postEnableHtmlCode'] : 0;

				$c['pinTopic'] = ($mode == 'Reply') ? $topicData['topicIsPinned'] : 0;
				$c['closeTopic'] = ($mode == 'Reply') ? $topicData['topicIsClosed'] : 0;
				$c['subscribeTopic'] = $subscriptionStatus;

				$c['pollGuestsVote'] = $c['pollShowResultsAfterEnd'] = 0;
				$c['pollGuestsViewResults'] = 1;

				if(isset($_GET['doit'])) {
					$c['enableBBCode'] = (isset($_POST['c']['enableBBCode']) && $forumData['forumEnableBBCode'] == 1) ? 1 : 0;
					$c['enableSmilies'] = (isset($_POST['c']['enableSmilies']) && $forumData['forumEnableSmilies'] == 1) ? 1 : 0;
					$c['enableHtmlCode'] = (isset($_POST['c']['enableHtmlCode']) && $forumData['forumEnableHtmlCode'] == 1) ? 1 : 0;
					$c['showSignature'] = (isset($_POST['c']['showSignature']) && $this->modules['Config']->getValue('enable_sig') == 1 && $this->modules['Auth']->isLoggedIn() == 1) ? 1 : 0;
					$c['subscribeTopic'] = isset($_POST['c']['subscribeTopic']) ? 1 : 0;
					$c['enableURITransformation'] = (isset($_POST['c']['enableURITransformation']) && $forumData['forumEnableURITransformation'] == 1) ? 1 : 0;
					$c['pollGuestsVote'] = isset($_POST['c']['pollGuestsVote']) ? 1 : 0;
					$c['pollGuestsViewResults'] = isset($_POST['c']['pollGuestsViewResults']) ? 1 : 0;
					$c['pollShowResultsAfterEnd'] = isset($_POST['c']['pollShowResultsAfterEnd']) ? 1 : 0;

					if($this->modules['Auth']->isLoggedIn() == 1 && ($this->modules['Auth']->getValue('userIsAdmin') == 1 || $this->modules['Auth']->getValue('userIsSupermod') == 1 || $authData['authIsMod'] == 1)) {
						$c['showEditings'] = isset($_POST['c']['showEditings']) ? 1 : 0;
						$c['pinTopic'] = isset($_POST['c']['pinTopic']) ? 1 : 0;
						$c['closeTopic'] = isset($_POST['c']['closeTopic']) ? 1 : 0;
					}
					
					while(list($curKey) = each($p['pollOptions'])) {
						if(trim($p['pollOptions'][$curKey]) == '')
							unset($p['pollOptions'][$curKey]);
					}
					reset($p['pollOptions']);

					if(!isset($_POST['showPreview'])) {
						if(trim($p['messageTitle']) == '') $error = $this->modules['Language']->getString('error_no_title');
						elseif(Functions::strlen($p['messageTitle']) > 255) $error = $this->modules['Language']->getString('error_title_too_long');
						elseif(trim($p['messageText']) == '') $error = $this->modules['Language']->getString('error_no_post');
						elseif($mode != 'Edit' && $this->modules['Auth']->isLoggedIn() != 1 && !Functions::verifyUserName($p['guestNick'])) $error = $this->modules['Language']->getString('error_invalid_name');
						elseif($mode != 'Edit' && $this->modules['Auth']->isLoggedIn() != 1 && !Functions::unifyUserName($p['guestNick'])) $error = $this->modules['Language']->getString('error_existing_user_name');
						elseif($mode == 'Topic' && trim($p['pollTitle']) != '' && $p['pollDuration'] <= 0)$error = $this->modules['Language']->getString('error_invalid_poll_duration');
						elseif($mode == 'Topic' && trim($p['pollTitle']) != '' && count($p['pollOptions']) <= 1) $error = $this->modules['Language']->getString('error_no_poll_options');
						elseif($mode == 'Edit') {
							$this->modules['DB']->queryParams('
								UPDATE
									'.TBLPFX.'posts
								SET
									"smileyID"=$1,
									"postEnableBBCode"=$2,
									"postEnableSmilies"=$3,
									"postEnableHtmlCode"=$4,
									"postShowSignature"=$5,
									"postEnableURITransformation"=$6,
									"postShowEditings"=$7,
									"postEditedCounter"="postEditedCounter"+1,
									"postLastEditorNick"=$8,
									"postTitle"=$9,
									"postText"=$10
								WHERE
									"postID"=$11
							', array(
								$p['smileyID'],
								$c['enableBBCode'],
								$c['enableSmilies'],
								$c['enableHtmlCode'],
								$c['showSignature'],
								$c['enableURITransformation'],
								$c['showEditings'],

								$this->modules['Auth']->getValue('userNick'),
								$p['messageTitle'],
								$p['messageText'],
								$postID
							));
							if($postID == $topicData['topicFirstPostID']) {
								$this->modules['DB']->queryParams('
									UPDATE
										'.TBLPFX.'topics
									SET
										"topicTitle"=$1,
										"smileyID"=$2
									WHERE
										"topicID"=$3
								', array(
									$p['messageTitle'],
									$p['smileyID'],
									$topicID
								));
							}

							Functions::myHeader(INDEXFILE.'?action=ViewTopic&postID='.$postID.'&'.MYSID.'#post'.$postID);
						}
						else {
							if(USERID != 0)
								$p['guestNick'] = '';

							if($mode == 'Topic') {
								$this->modules['DB']->queryParams('
									INSERT INTO
										'.TBLPFX.'topics
									SET
										"topicTitle"=$1,
										"forumID"=$2,
										"topicIsClosed"=$3,
										"topicIsPinned"=$4,
										"posterID"=$5,
										"smileyID"=$6,
										"topicPostTimestamp"=$7,
										"topicGuestNick"=$8
								', array(
									$p['messageTitle'],
									$forumID,
									$c['closeTopic'],
									$c['pinTopic'],
									USERID,
									$p['smileyID'],
									time(),
									$p['guestNick']
								));
								$topicID = $this->modules['DB']->getInsertID();

								// Eventuell die Umfrage zum Thema hinzufuegen
								if(($this->modules['Auth']->getValue('userIsAdmin') == 1 || $this->modules['Auth']->getValue('userIsSupermod') == 1 || $authData['authIsMod'] == 1 || $authData['authPostPoll'] == 1) && trim($p['pollTitle']) != '' && $p['pollDuration'] > 0 && count($p['pollOptions']) > 1) {
									$this->modules['DB']->queryParams('
										UPDATE
											'.TBLPFX.'topics
										SET
											"topicHasPoll"=$1,
											"topicPollTitle"=$2,
											"topicPollStartTimestamp"=$3,
											"topicPollEndTimestamp"=$4,
											"topicPollGuestsVote"=$5,
											"topicPollGuestsViewResults"=$6
										WHERE
											"topicID"=$7
									', array(
										1,
										$p['pollTitle'],
										time(),
										time()+86400*$p['pollDuration'],
										$c['pollGuestsVote'],
										$c['pollGuestsViewResults'],
										$topicID
									));

									$i = 1;
									foreach($p['pollOptions'] AS $curOption) {
										$this->modules['DB']->queryParams('
											INSERT INTO
												'.TBLPFX.'polls_options
											SET
												"topicID"=$1,
												"optionID"=$2,
												"optionTitle"=$3
										', array(
											$topicID,
											$i++,
											$curOption
										));
									}
								}
							}

							// Den Beitrag in die Datenbank eintragen
							$this->modules['DB']->queryParams('
								INSERT INTO
									'.TBLPFX.'posts
								SET
									"topicID"=$1,
									"forumID"=$2,
									"posterID"=$3,
									"smileyID"=$4,
									"postIP"=$5,
									"postEnableBBCode"=$6,
									"postEnableSmilies"=$7,
									"postEnableHtmlCode"=$8,
									"postShowSignature"=$9,
									"postEnableURITransformation"=$10,
									"postShowEditings"=$11,
									"postTimestamp"=$12,
									"postTitle"=$13,
									"postText"=$14,
									"postGuestNick"=$15
							', array(
								$topicID,
								$forumID,
								USERID,
								$p['smileyID'],
								$_SERVER['REMOTE_ADDR'],
								$c['enableBBCode'],
								$c['enableSmilies'],
								$c['enableHtmlCode'],
								$c['showSignature'],
								$c['enableURITransformation'],
								$c['showEditings'],
								time(),
								$p['messageTitle'],
								$p['messageText'],
								$p['guestNick']
							));
							$postID = $this->modules['DB']->getInsertID();

							// Verschiedene Dinge updaten (Beitragszahl, erster/letzter Beitrag usw.)
							if($mode == 'Topic') $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'topics SET "topicFirstPostID"=$1, "topicLastPostID"=$2 WHERE "topicID"=$3', array($postID, $postID, $topicID));
							else $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'topics SET "topicLastPostID"=$1, "topicRepliesCounter"="topicRepliesCounter"+1, "topicIsClosed"=$2, "topicIsPinned"=$3 WHERE "topicID"=$4', array($postID, $c['closeTopic'], $c['pinTopic'], $topicID));

							if($mode == 'Reply') $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'forums SET "forumLastPostID"=$1, "forumPostsCounter"="forumPostsCounter"+1 WHERE "forumID"=$2', array($postID, $forumID));
							else $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'forums SET "forumLastPostID"=$1, "forumPostsCounter"="forumPostsCounter"+1, "forumTopicsCounter"="forumTopicsCounter"+1 WHERE "forumID"=$2', array($postID, $forumID));
							
							$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'users SET "userPostsCounter"="userPostsCounter"+1 WHERE "userID"=$1', array(USERID));

							// Eventuell Themenabo entfernen oder hinzufuegen
							if($mode != 'Edit' && $this->modules['Auth']->isLoggedIn() == 1 && $this->modules['Config']->getValue('enable_email_functions') == 1 && $this->modules['Config']->getValue('enable_topic_subscription') == 1 && $c['subscribeTopic'] != $subscriptionStatus) {
								if($c['subscribeTopic'] == 0) $this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'topics_subscriptions WHERE "topicID"=$1 AND "userID"=$2', array($topicID, USERID));
								else $this->modules['DB']->queryParams('INSERT INTO '.TBLPFX.'topics_subscriptions SET "topicID"=$1, "userID"=$2', array($topicID, USERID));
							}
							
							// User ueber neuen Beitrag informieren
							$this->modules['DB']->queryParams('
								SELECT
									t1."userNick",
									t1."userEmailAddress",
									t1."userLanguage"
								FROM (
									'.TBLPFX.'topics_subscriptions t2,
									'.TBLPFX.'users t1
								) WHERE
									t2."topicID"=$1
									AND t1."userID"=t2."userID"
									AND t1."userID"<>$2
							',array(
								$topicID,
								USERID
							));
							$subscriptionsData = $this->modules['DB']->raw2Array();
							$emailsToSend = array();
							$this->modules['Template']->assign(array(
								'topicTitle'=>$topicData['topicTitle'],
								'linkToPost'=>$this->modules['Config']->getValue('board_address').'/'.INDEXFILE.'?action=ViewTopic&postID='.$postID.'#post'.$postID
							));
							foreach($subscriptionsData AS &$curSubscription) {
								$curLanguageString = $this->modules['Language']->getLS($curSubscription['userLanguage']);
								if(!isset($emailsToSend[$curLanguageString])) {
									// Add main language file (contains email subjects)
									$this->modules['Language']->addFile('Main',$curLanguageString);
									
									$emailsToSend[$curLanguageString] = array(
										array(),
										$this->modules['Language']->getString('email_subject_new_reply_posted',$curLanguageString),
										$this->modules['Template']->fetch('TopicSubscriptionNotification.mail',$this->modules['Language']->getLD($curLanguageString).'mails')
									);
								}
								$emailsToSend[$curLanguageString][0][] = $curSubscription['userEmailAddress'];
							}
							
							foreach($emailsToSend AS &$curEmail) {
								Functions::myMail(
									$this->modules['Config']->getValue('board_name').' <'.$this->modules['Config']->getValue('board_email_address').'>',
									'undisclosed-recipients: ;',
									$curEmail[1],
									$curEmail[2],
									array('Bcc: '.implode(', ',$curEmail[0]))
								);
							}
							
							Functions::myHeader(INDEXFILE.'?action=ViewTopic&postID='.$postID.'&'.MYSID.'#post'.$postID);
						}
					}
				}

				$show = array();

				$show['enableSmilies'] = $forumData['forumEnableSmilies'] == 1;
				$show['showSignature'] = $this->modules['Config']->getValue('enable_sig') == 1 && $this->modules['Auth']->isLoggedIn() == 1;
				$show['enableBBCode'] = $forumData['forumEnableBBCode'] == 1;
				$show['enableURITransformation'] = $forumData['forumEnableURITransformation'];
				$show['enableHtmlCode'] = $forumData['forumEnableHtmlCode'] == 1;
				$show['subscribeTopic'] = $mode != 'Edit' && $this->modules['Auth']->isLoggedIn() == 1 && $this->modules['Config']->getValue('enable_email_functions') == 1 && $this->modules['Config']->getValue('enable_topic_subscription') == 1;
				$show['closeTopic'] = $mode != 'Edit' && $this->modules['Auth']->isLoggedIn() == 1 && ($this->modules['Auth']->getValue('userIsAdmin') == 1 || $this->modules['Auth']->getValue('userIsSupermod') == 1 || $authData['authIsMod'] == 1);
				$show['adminSmilies'] = $this->modules['Auth']->isLoggedIn() == 1 && ($this->modules['Auth']->getValue('userIsAdmin') == 1 || $this->modules['Auth']->getValue('userIsSupermod') == 1 || $authData['authIsMod'] == 1);
				$show['pinTopic'] = $mode != 'Edit' && $this->modules['Auth']->isLoggedIn() == 1 && ($this->modules['Auth']->getValue('userIsAdmin') == 1 || $this->modules['Auth']->getValue('userIsSupermod') == 1 || $authData['authIsMod'] == 1);
				$show['showEditings'] = $this->modules['Auth']->isLoggedIn() == 1 && ($this->modules['Auth']->getValue('userIsAdmin') == 1 || $this->modules['Auth']->getValue('userIsSupermod') == 1 || $authData['authIsMod'] == 1);
				$show['pollBox'] = $mode == 'Topic' && ($this->modules['Auth']->getValue('userIsAdmin') == 1 || $this->modules['Auth']->getValue('userIsSupermod') == 1 || $authData['authIsMod'] == 1 || $authData['authPostPoll'] == 1);
				$show['previewBox'] = isset($_POST['showPreview']);

				// smilies, topic pics
				$smiliesBox = $adminSmiliesBox = '';
				if($show['enableSmilies'])
					$smiliesBox = Functions::getSmiliesBox();
				if($show['adminSmilies'])
					$adminSmiliesBox = Functions::getAdminSmiliesBox();

				$postPicsBox = Functions::getPostPicsBox($p['smileyID']);

				// Die Vorschau
				$previewData = array();
				if($show['previewBox']) {
					$previewData['messageText'] = $this->modules['BBCode']->format($p['messageText'], ($c['enableHtmlCode'] == 1 || $show['enableHtmlCode']), ($c['enableSmilies'] == 1 && $show['enableSmilies']), ($c['enableBBCode'] == 1 && $show['enableBBCode']), $topicID);
					$previewData['messageTitle'] = Functions::HTMLSpecialChars($p['messageTitle']);
				}

				// Fuer die richtige Anzeige des Navileiste usw.
				$this->modules['Navbar']->addCategories($forumData['catID']);
				$this->modules['Navbar']->addElement(Functions::HTMLSpecialChars($forumData['forumName']),INDEXFILE.'?action=ViewForum&amp;forumID='.$forumID.'&amp;'.MYSID);

				$latestPostsData = NULL;

				if($mode == 'Topic') {
					$actionText = $this->modules['Language']->getString('post_topic');
					$this->modules['Navbar']->addElement($this->modules['Language']->getString('post_topic'),INDEXFILE.'?action=Posting&amp;mode=Topic&amp;forumID='.$forumID.'&amp;'.MYSID);
				}
				elseif($mode == 'Reply') {
					$actionText = $this->modules['Language']->getString('post_reply');
					$this->modules['Navbar']->addElements(
						array(Functions::HTMLSpecialChars($topicData['topicTitle']),INDEXFILE.'?action=ViewTopic&amp;topicID='.$topicID.'&amp;'.MYSID),
						array($this->modules['Language']->getString('post_reply'),INDEXFILE.'?action=Posting&amp;mode=Reply&amp;topicID='.$topicID.'&amp;'.MYSID)
					);

					$this->modules['DB']->queryParams('
						SELECT
							t1.*,
							t2."userEmailAddress" AS "postPosterEmailAddress",
							t2."userNick" AS "postPosterNick",
							t2."userSignature" AS "postPosterSignature",
							t2."userIsAdmin" AS "postPosterIsAdmin",
							t2."userIsSupermod" AS "postPosterIsSupermod",
							t2."userPostsCounter" AS "postPosterPosts",
							t2."rankID" AS "postPosterRankID",
							t2."userAvatarAddress" AS "postPosterAvatarAddress",
							t2."userHideEmailAddress" AS "postPosterHideEmailAddress",
							t2."userReceiveEmails" AS "postPosterReceiveEmails",
							t3."smileyFileName" AS "postSmileyFileName"
						FROM '.TBLPFX.'posts t1
						LEFT JOIN '.TBLPFX.'users t2 ON t1."posterID"=t2."userID"
						LEFT JOIN '.TBLPFX.'smilies t3 ON t3."smileyID"=t1."smileyID"
						WHERE t1."topicID"=$1
						ORDER BY t1."postTimestamp" DESC LIMIT 10
					', array(
						$topicID
					));
					$latestPostsData = $this->modules['DB']->raw2Array();

					foreach($latestPostsData AS &$curPost) {
						$curPost['_postDateTime'] = Functions::toDateTime($curPost['postTimestamp']);
						$curPost['_postPosterNick'] = ($curPost['posterID'] == 0 ? $curPost['postGuestNick'] : $curPost['postPosterNick']);
						$curPost['_postText'] = $this->modules['BBCode']->format($curPost['postText'], ($curPost['postEnableHtmlCode'] == 1 || $forumData['forumEnableHtmlCode'] == 1), ($curPost['postEnableSmilies'] == 1 && $forumData['forumEnableSmilies'] == 1), ($curPost['postEnableBBCode'] == 1 && $forumData['forumEnableBBCode'] == 1), $topicID);
					}
				}
				elseif($mode == 'Edit') {
					$actionText = $this->modules['Language']->getString('edit_post');
					$this->modules['Navbar']->addElements(
						array(Functions::HTMLSpecialChars($topicData['topicTitle']),INDEXFILE.'?action=ViewTopic&amp;topicID='.$topicID.'&amp;'.MYSID),
						array($this->modules['Language']->getString('edit_post'),INDEXFILE.'?action=Posting&amp;mode=Edit&amp;PostID='.$postID.'&amp;'.MYSID)
					);
				}

				//
				// Der Rest...
				//
				$title_max_chars = sprintf($this->modules['Language']->getString('maximum_x_chars'),100);

				$this->modules['Template']->assign(array(
					'p'=>Functions::HTMLSpecialChars($p),
					'c'=>$c,
					'actionText'=>$actionText,
					'show'=>$show,
					'pollOptionsCounter'=>count($p['pollOptions']),
					'forumID'=>$forumID,
					'topicID'=>$topicID,
					'postID'=>$postID,
					'mode'=>$mode,
					'error'=>$error,
					'postPicsBox'=>$postPicsBox,
					'smiliesBox'=>$smiliesBox,
					'adminSmiliesBox'=>$adminSmiliesBox,
					'previewData'=>$previewData,
					'latestPostsData'=>$latestPostsData
				));
				$this->modules['Template']->printPage('Posting.tpl');
				break;

			case 'Delete':
				if($postID == $topicData['topicFirstPostID']) {
					$this->modules['Navbar']->addCategories($forumData['catID']);
					$this->modules['Navbar']->addElements(
						array(Functions::HTMLSpecialChars($forumData['forumName']),INDEXFILE.'?action=ViewForum&amp;forumID='.$forumID.'&amp;'.MYSID),
						array(Functions::HTMLSpecialChars($topicData['topicTitle']),INDEXFILE.'?action=ViewTopic&amp;topicID='.$topicID.'&amp;'.MYSID)
					);
					FuncMisc::printMessage('cannot_delete_first_post'); exit;
				}

				$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'posts WHERE "postID"=$1',array($postID));
				$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'topics SET "topicRepliesCounter"="topicRepliesCounter"-1 WHERE "topicID"=$1',array($topicID));
				// Should posts counter really be decreased?
				//$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'users SET "userPostsCounter"="userPostsCounter"-1 WHERE "userID"=$1');

				if($postID == $topicData['topicLastPostID']) FuncTopics::updateLastPost($topicID);
				if($postID == $forumData['forumLastPostID']) FuncForums::updateLastPost($forumID);

				$returnPage = isset($_GET['returnPage']) ? $_GET['returnPage'] : 1;
				Functions::myHeader(INDEXFILE.'?action=ViewTopic&topicID='.$topicID.'&page='.$returnPage.'&'.MYSID);
				break;
		}
	}

	protected function _authenticateUser(&$mode,&$forumData) {
		$authData = Functions::getAuthData($forumData,array('authPostTopic','authPostReply','authPostPoll','authEditPosts','authIsMod'));
		if($mode == 'Reply' && $authData['authPostReply'] == 0 || ($mode == 'Edit' || $mode == 'Delete') && $authData['authEditPosts'] == 0 || $mode == 'Topic' && $authData['authPostTopic'] == 0) {
			FuncMisc::printMessage('access_denied');
			exit;
		}

		return $authData;
	}
}