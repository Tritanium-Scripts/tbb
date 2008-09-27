<?php

class AdminUsers extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'AuthAdmin',
		'Config',
		'DB',
		'GlobalsAdmin',
		'Language',
		'Navbar',
		'Template'
	);

	public function executeMe() {
		$this->modules['Language']->addFile('AdminUsers');
		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Manage_users'),INDEXFILE.'?action=AdminUsers&amp;'.MYSID);

		switch(@$_GET['mode']) {
			default:
				$this->modules['Template']->printPage('AdminUsers.tpl');
				break;

			case 'AddUser':
				$this->modules['Language']->addFile('Register');

				$p = Functions::getSGValues($_POST['p'],array('userNick','userEmailAddress','userPassword','userPasswordConfirmation'),'');
				$c = Functions::getSGValues($_POST['c'],array('notifyUser'),1);

				$error = '';

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Add_user'),INDEXFILE.'?action=AdminUsers&amp;mode=AddUser&amp;'.MYSID);

				if(isset($_GET['doit'])) {
					$c = Functions::getSGValues($_POST['c'],array('notifyUser'),0);

					if(trim($p['userNick']) == '' || !Functions::verifyUserName($p['userNick'])) $error = $this->modules['Language']->getString('error_bad_nick');
					elseif(!Functions::unifyUserName($p['userNick'])) $error = $this->modules['Language']->getString('error_nick_already_in_use');
					elseif(trim($p['userEmailAddress']) == '' || !Functions::verifyEmailAddress($p['userEmailAddress'])) $error = $this->modules['Language']->getString('error_bad_email');
					elseif(trim($p['userPassword']) == '') $error = $this->modules['Language']->getString('error_no_pw');
					elseif($p['userPassword'] != $p['userPasswordConfirmation']) $error = $this->modules['Language']->getString('error_pws_no_match');
					else {
						$userPasswordSalt = Functions::getRandomString(10);
						$userPasswordEncrypted = Functions::getSaltedHash($p['userPassword'],$userPasswordSalt);

						$this->modules['DB']->queryParams('
							INSERT INTO
								'.TBLPFX.'users
							SET
								"userNick"=$1,
								"userEmailAddress"=$2,
								"userPassword"=$3,
								"userPasswordSalt"=$4,
								"userRegistrationTimestamp"=$5,
								"userTimeZone"=$6,
								"userIsActivated"=1
						',array(
							$p['userNick'],
							$p['userEmailAddress'],
							$userPasswordEncrypted,
							$userPasswordSalt,
							time(),
							$this->modules['Config']->getValue('standard_tz')
						));
						$newUserID = $this->modules['DB']->getInsertID();

						FuncConfig::updateLatestUser($newUserID,$p['userNick']);

						if($c['notifyUser'] == 1 && $this->modules['Config']->getValue('enable_email_functions') == 1) {
							$this->modules['Template']->assign(array(
								'userNick'=>$p['userName'],
								'userID'=>$userID,
								'userEmailAddress'=>$p['userEmailAddress'],
								'userPassword'=>$p['userPassword']
							));
							Functions::myMail(
								$this->modules['Config']->getValue('board_name').' <'.$this->modules['Config']->getValue('board_email_address').'>',
								$p['userEmailAddress'],
								sprintf($this->modules['Language']->getString('email_subject_welcome'),$this->modules['Config']->getValue('board_name')),
								$this->modules['Template']->fetch('RegistrationWelcome.mail',$this->modules['Language']->getLD().'mails')
							);
						}

						FuncMisc::printMessage('new_user_added'); exit;
					}
				}

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'c'=>$c,
					'error'=>$error
				));
				$this->modules['Template']->printPage('AdminUsersAddUser.tpl');
				break;

			case 'SearchUsers':
				$p = Functions::getSGValues($_POST['p'],array('userID','userNick','userEmailAddress'),'');

				$query = array();
				if(trim($p['userID']) != '')
					$query[] = '"userID" LIKE \''.Functions::str_replace('*','%',$this->modules['DB']->escapeString($p['userID'])).'\'';
				if(trim($p['userNick']) != '')
					$query[] = '"userNick" LIKE \''.Functions::str_replace('*','%',$this->modules['DB']->escapeString($p['userNick'])).'\'';
				if(trim($p['userEmailAddress']) != '')
					$query[] = '"userEmailAddress" LIKE \''.Functions::str_replace('*','%',$this->modules['DB']->escapeString($p['userEmailAddress'])).'\'';

				$usersData = array();

				if(count($query) > 0) {
					$this->modules['DB']->query('SELECT "userID", "userNick", "userEmailAddress" FROM '.TBLPFX.'users WHERE '.implode(' AND ', $query));

					if($this->modules['DB']->getAffectedRows() == 1) {
						$result = $this->modules['DB']->fetchArray();
						Functions::myHeader(INDEXFILE.'?action=AdminUsers&mode=EditUser&userID='.$result['userID'].'&'.MYSID);
					}
					elseif($this->modules['DB']->getAffectedRows() > 0)
						$usersData = $this->modules['DB']->raw2Array();
				}

				$this->modules['Template']->assign(array(
					'usersData'=>$usersData,
					'p'=>Functions::HTMLSpecialChars($p)
				));
				$this->modules['Template']->printPage('AdminUsersSearchUsers.tpl');
				break;

			case 'EditUser':
				$userID = isset($_GET['userID']) ? intval($_GET['userID']) : 0;

				if(!$userData = FuncUsers::getUserData($userID)) die('Cannot load data: user');

				$this->modules['Language']->addFile('AdminRanks');
				$this->modules['Language']->addFile('EditProfile');
				$this->modules['Language']->addFile('ViewProfile');

				$p = Functions::getSGValues($_POST['p'],array('userEmailAddress','userSignature','userAvatarAddress','rankID','userAuthProfileNotes'),'',$userData);
				$c = Functions::getSGValues($_POST['c'],array('userIsAdmin','userIsSupermod'),0,$userData);

				$error = '';

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Edit_user'),INDEXFILE."?action=AdminUsers&amp;mode=EditUser&amp;userID=$userID&amp;".MYSID);

				if(isset($_GET['doit'])) {
					$c = Functions::getSGValues($_POST['c'],array('userIsAdmin','userIsSupermod'),0);

					if(trim($p['userEmailAddress']) == '' || !Functions::verifyEmailAddress($p['userEmailAddress'])) $error = $this->modules['Language']->getString('error_bad_email');
					else {
						if($userID == USERID)
							$c['userIsAdmin'] = 1;

						if($p['rankID'] != 0 && !FuncRanks::getRankData($p['rankID'])) $p['rankID'] = 0;

						$this->modules['DB']->queryParams('
							UPDATE
								'.TBLPFX.'users
							SET
								"userIsAdmin"=$1,
								"userIsSupermod"=$2,
								"userEmailAddress"=$3,
								"userSignature"=$4,
								"userAvatarAddress"=$5,
								"rankID"=$6
							WHERE
								"userID"=$7
						', array(
							$c['userIsAdmin'],
							$c['userIsSupermod'],
							$p['userEmailAddress'],
							$p['userSignature'],
							$p['userAvatarAddress'],
							$p['rankID'],
							$userID
						));

						FuncMisc::printMessage('user_edited'); exit;
					}
				}


				/**
				 * Handle user lock
				 */
				if($userData['userIsLocked'] != LOCK_TYPE_NO_LOCK && FuncUsers::checkLockStatus($userData)) {
					$this->modules['DB']->queryParams('SELECT * FROM '.TBLPFX.'users_locks WHERE "userID"=$1', array($userID));
					$lockData = $this->modules['DB']->fetchArray();

					if($lockData['lockStartTimestamp'] == $lockData['lockEndTimestamp'])
						$remainingLockTime = $this->modules['Language']->getString('locked_forever');
					else {
						$remainingLockTime = FuncDate::splitTime($lockData['lockEndTimestamp']-time());
						$remainingLockTime = sprintf($this->modules['Language']->getString('time_left'),$remainingLockTime['days'],$remainingLockTime['hours'],$remainingLockTime['minutes']);
					}

					$this->modules['Template']->assign(array(
						'lockData'=>$lockData,
						'remainingLockTime'=>$remainingLockTime
					));
				} else {
					$userData['userIsLocked'] = LOCK_TYPE_NO_LOCK;
				}

				$this->modules['DB']->query('SELECT "rankID", "rankName" FROM '.TBLPFX.'ranks WHERE "rankType"=1 ORDER BY "rankName" ASC');
				$ranksData = $this->modules['DB']->raw2Array();

				$this->modules['Template']->assign(array(
					'p'=>$p,
					'c'=>$c,
					'userData'=>$userData,
					'ranksData'=>$ranksData,
					'error'=>$error
				));

				$this->modules['Template']->printPage('AdminUsersEditUser.tpl');
			break;

			case 'DeleteUser':
				$userID = isset($_GET['userID']) ? intval($_GET['userID']) : 0;
				//$p_ban_nick_email = isset($_POST['p_ban_nick_email']) ? 1 : 0;
				
				$c = Functions::getSGValues($_POST['c'], array('deleteUsersPosts','deleteUsersTopics','deleteUsersSentPMs'), 1);

				if(!$userData = FuncUsers::getUserData($userID)) die('Cannot load data: user '.$userID);

				if($c['deleteUsersSentPMs'] == 1)
					$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'pms WHERE "pmFromID"=$1', array($userID));
				else
					$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'pms SET "pmFromID"=0, "pmGuestNick"=$1 WHERE "pmFromID"=$2', array($userData['userNick'], $userID));
				
				if($c['deleteUsersPosts'] == 1) {
					$affectedForumIDs = $affectedTopicIDs = array();
					
					$this->modules['DB']->queryParams('SELECT t1."postID" FROM ('.TBLPFX.'posts t1, '.TBLPFX.'topics t2) WHERE t1."topicID"=t2."topicID" AND t1."postID"<>t2."topicFirstPostID" AND t1."postID"=$1',array($userID));
					$postIDs = $this->modules['DB']->raw2FVArray();
					
					if(count($postIDs) > 0) {
						$this->modules['DB']->queryParams('SELECT COUNT(*) AS "postsCounter", "forumID" FROM '.TBLPFX.'posts WHERE "postID" IN $1 GROUP BY "forumID"', array($postIDs));
						$countersData = $this->modules['DB']->raw2Array();
						foreach($countersData AS &$curCounter) {
							$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'forums SET "forumPostsCounter"="forumPostsCounter"-$1 WHERE "forumID"=$2', array($curCounter['postsCounter'], $curCounter['forumID']));
							$affectedForumIDs[] = $curCounter['forumID'];
						}
	
						$this->modules['DB']->queryParams('SELECT COUNT(*) AS "repliesCounter", "topicID" FROM '.TBLPFX.'posts WHERE "postID" IN $1 GROUP BY "topicID"', array($postIDs));
						$countersData = $this->modules['DB']->raw2Array();
						foreach($countersData AS &$curCounter) {
							$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'topics SET "topicRepliesCounter"="topicRepliesCounter"-$1 WHERE "topicID"=$2', array($curCounter['repliesCounter'], $curCounter['topicID']));
							$affectedTopicIDs[] = $curCounter['topicID'];
						}
	
						$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'posts WHERE "postID" IN $1', array($postIDs));
	
						$affectedForumIDs = array_unique($affectedForumIDs);
						foreach($affectedForumIDs AS &$curForumID)
							FuncForums::updateLastPost($curForumID);
	
						$affectedTopicIDs = array_unique($affectedTopicIDs);
						foreach($affectedTopicIDs AS &$curTopicID)
							FuncTopics::updateLastPost($curTopicID);
					}
				} else {
					$this->modules['DB']->queryParams('UPDATE ('.TBLPFX.'posts t1, '.TBLPFX.'topics t2) SET t1."posterID"=0, t1."postGuestNick"=$1 WHERE t1."posterID"=$2 AND t1."topicID"=t2."topicID" AND t1."postID"=t2."topicFirstPostID"', array($userData['userNick'], $userID));					
				}
				
				if($c['deleteUsersTopics'] == 1) {
					$affectedForumIDs = array();

					// determine topic ids
					$this->modules['DB']->queryParams('SELECT "topicID" FROM '.TBLPFX.'topics WHERE "posterID"=$1', array($userID));
					$topicIDs = $this->modules['DB']->raw2FVArray();

					if(count($topicIDs) > 0) {
						// Jetzt muessen die Beitragszahlen der User entsprechend gesenkt werden
						/*$this->modules['DB']->queryParams('SELECT COUNT(*) AS "posts_counter", "poster_id" FROM '.TBLPFX.'posts WHERE "topic_id" IN ($1) GROUP BY "poster_id"', array($topic_idsi));
						$posts_counter = $this->modules['DB']->raw2array();
						while(list(,$akt_posts_counter) = each($posts_counter))
							$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'users SET "user_posts"="user_posts"-$1 WHERE "userID"=$2', array($akt_posts_counter['posts_counter'], $akt_posts_counter['poster_id']));
						*/
	
						// forums post counters
						$this->modules['DB']->queryParams('SELECT COUNT(*) AS "postsCounter", "forumID" FROM '.TBLPFX.'posts WHERE "topicID" IN $1 GROUP BY "forumID"', array($topicIDs));
						$countersData = $this->modules['DB']->raw2Array();
						foreach($countersData AS &$curCounter) {
							$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'forums SET "forumPostsCounter"="forumPostsCounter"-$1 WHERE "forumID"=$2', array($curCounter['postsCounter'], $curCounter['forumID']));
							$affectedForumIDs[] = $curCounter['forumID'];
						}

						// forums topic counters
						$this->modules['DB']->queryParams('SELECT COUNT(*) AS "topicsCounter", "forumID" FROM '.TBLPFX.'topics WHERE "topicID" IN $1 GROUP BY "forumID"', array($topicIDs));
						$countersData = $this->modules['DB']->raw2Array();
						foreach($countersData AS &$curCounter)
							$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'forums SET "forumTopicsCounter"="forumTopicsCounter"-$1 WHERE "forumID"=$2', array($curCounter['topicsCounter'], $curCounter['forumID']));
	
						// polls
						$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'polls_options WHERE "topicID" IN ($1)', array($topicIDs));
						$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'polls_votes WHERE "topicID" IN ($1)', array($topicIDs));
							
						// topic subscriptions, posts, topics
						$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'topics_subscriptions WHERE "topicID" IN $1', array($topicIDs));
						$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'posts WHERE "topicID" IN $1', array($topicIDs));
						$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'topics WHERE "topicID" IN $1', array($topicIDs));

						$affectedForumIDs = array_unique($affectedForumIDs);
						foreach($affectedForumIDs AS &$curForumID)
							FuncForums::updateLastPost($curForumID);
					}
				} else {
					$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'topics SET "posterID"=0, "topicGuestNick"=$1 WHERE "posterID"=$2', array($userData['userNick'], $userID));					
				}

				// delete misc stuff
				$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'pms WHERE "pmToID"=$1', array($userID)); // Eigene PMs
				$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'pms_folders WHERE "userID"=$1', array($userID)); // PMs-Ordner
				$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'polls_votes WHERE "voterID"=$1', array($userID)); // Umfrageteilnahmen
				$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'groups_members WHERE "memberID"=$1', array($userID)); // Gruppenmitgliedschaften
				$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'forums_auth WHERE "authType"=0 AND "authID"=$1', array($userID)); // Forenzugriffe
				$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'topics_subscriptions WHERE "userID"=$1', array($userID)); // Themenabonnements
				
				// delete user
				$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'users WHERE "userID"=$1', array($userID)); // Userdaten
				
				FuncUsers::updateLatestUser();
				FuncMisc::printMessage('user_deleted'); exit;
				
				//
				// Themen/Beitraege
				//
				if($p_delete_posts == 1) { // Falls alles geloescht werden soll...
					$affectedForumIDs = array(); // Beinhaltet spaeter alle Foren-IDs, in denen Beitraege geloescht wurden
					$affectedTopicIDs = array(); // Beinhaltet spaeter alle Themen-IDs, in denen Beitraege geloescht wurden

					//
					// Erst muessen die Themen geloescht werden, die der User erstellt hat
					// Dazu werden erst mal die Themen-IDs bestimmt
					//
					$this->modules['DB']->queryParams('SELECT "topic_id" FROM '.TBLPFX.'topics WHERE "poster_id"=$1', array($userID));
					$topic_ids = $this->modules['DB']->raw2fvarray();
					$topic_idsi = implode("','",$topic_ids);


					//
					// Jetzt muessen die Beitragszahlen der User entsprechend gesenkt werden
					//
					/*$this->modules['DB']->queryParams('SELECT COUNT(*) AS "posts_counter", "poster_id" FROM '.TBLPFX.'posts WHERE "topic_id" IN ($1) GROUP BY "poster_id"', array($topic_idsi));
					$posts_counter = $this->modules['DB']->raw2array();
					while(list(,$akt_posts_counter) = each($posts_counter))
						$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'users SET "user_posts"="user_posts"-$1 WHERE "userID"=$2', array($akt_posts_counter['posts_counter'], $akt_posts_counter['poster_id']));
					*/


					//
					// Und nun die Beitragszahlen der entsprechenden Foren
					//
					$this->modules['DB']->queryParams('SELECT COUNT(*) AS "posts_counter", "forum_id" FROM '.TBLPFX.'posts WHERE "topic_id" IN ($1) GROUP BY "forum_id"', array($topic_ids));
					$posts_counter = $this->modules['DB']->raw2array();
					while(list(,$akt_posts_counter) = each($posts_counter)) {
						$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'forums SET "forum_posts_counter"="forum_posts_counter"-$1 WHERE "forum_id"=$2', array($akt_posts_counter['posts_counter'], $akt_posts_counter['forum_id']));
						$affectedForumIDs = $akt_posts_counter['forum_id'];
					}


					//
					// Jetzt die Themenzahlen der entsprechenden Foren
					//
					$this->modules['DB']->queryParams('SELECT COUNT(*) AS "topics_counter", "forum_id" FROM '.TBLPFX.'topics WHERE "topic_id" IN ($1) GROUP BY "forum_id"', array($topic_ids));
					$topics_counter = $this->modules['DB']->raw2array();
					while(list(,$akt_topics_counter) = each($topics_counter))
						$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'forums SET "forum_topics_counter"="forum_topics_counter"-$1 WHERE "forum_id"=$2', array($akt_topics_counter['topics_counter'], $akt_topics_counter['forum_id']));


					//
					// Jetzt werden die Themen-Abonnnements, die Themen und die Beitraege geloescht
					//
					$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'topics_subscriptions WHERE "topic_id" IN ($1)', array($topic_idsi));
					$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'posts WHERE "topic_id" IN ($1)', array($topic_idsi));
					$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'topics WHERE "topic_id" IN ($1)', array($topic_idsi));


					//
					// Jetzt noch die Umfragen, dazu die Umfrageoptionen und die Abstimmungen
					//
					$this->modules['DB']->queryParams('SELECT "poll_id" FROM '.TBLPFX.'polls WHERE "topic_id" IN ($1)', array($topic_idsi));
					$poll_ids = $this->modules['DB']->raw2fvarray();
					$poll_idsi = implode("','",$poll_ids);

					$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'polls WHERE "poll_id" IN ($1)', array($poll_idsi));
					$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'polls_options WHERE "poll_id" IN ($1)', array($poll_idsi));
					$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'polls_votes WHERE "poll_id" IN ($1)', array($poll_idsi));


					//
					// Als letztes die einzelnen Beitraege des Users, dazu erst die Beitragszahlen der entsprechenden Foren, dann die Beitragszahlen der einzelnen Themen, dann die Beitraege selbst
					//
					$this->modules['DB']->queryParams('SELECT COUNT(*) AS "posts_counter", "forum_id" FROM '.TBLPFX.'posts WHERE "poster_id"=$1 GROUP BY "forum_id"', array($userID));
					$forum_posts_counter = $this->modules['DB']->raw2array();
					while(list(,$akt_posts_counter) = each($forum_posts_counter)) {
						$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'forums SET "forum_posts_counter"="forum_posts_counter"-$1 WHERE "forum_id"=$2', array($akt_posts_counter['posts_counter'], $akt_posts_counter['forum_id']));
						$affectedForumIDs[] = $akt_posts_counter['forum_id'];
					}

					$this->modules['DB']->queryParams('SELECT COUNT(*) AS "replies_counter", "topic_id" FROM '.TBLPFX.'posts WHERE "poster_id"=$1 GROUP BY "topic_id"', array($userID));
					$replies_counter = $this->modules['DB']->raw2array();
					while(list(,$akt_replies_counter) = each($replies_counter)) {
						$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'topics SET "topic_replies_counter"="topic_replies_counter"-$1 WHERE "topic_id"=$2', array($akt_replies_counter['replies_counter'], $akt_replies_counter['topic_id']));
						$affectedTopicIDs[] = $akt_replies_counter['topic_id'];
					}

					$this->modules['DB']->queryParams('"DELETE FROM '.TBLPFX.'posts WHERE "poster_id"=$1', array($userID));


					//
					// Jetzt noch Foren und Themen mit dem letzten Beitrag updaten
					//
					$affectedForumIDs = array_unique($affectedForumIDs);
					while(list(,$akt_forum_id) = each($affectedForumIDs))
						update_forum_last_post($akt_forum_id);

					$affectedTopicIDs = array_unique($affectedTopicIDs);
					while(list(,$akt_topic_id) = each($affectedTopicIDs))
						update_topic_last_post($akt_topic_id);
				}
				else { // ...oder auch nicht
					$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'posts SET "posterID"=0, "postGuestNick"=$1 WHERE "posterID"=$2', array($userData['userNick'], $userID));
					$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'topics SET "posterID"=0, "topicGuestNick"=$1 WHERE "posterID"=$2', array($userData['userNick'], $userID));
				}
				FuncUsers::updateLatestUser();
				FuncMisc::printMessage('user_deleted');
			break;

			case 'LockUser':
				$userID = isset($_GET['userID']) ? intval($_GET['userID']) : 0;

				if(!$userData = FuncUsers::getUserData($userID)) die('Cannot load data: user');

				$p = Functions::getSGValues($_POST['p'],array('lockType','lockTime'),0);

				if(($userData['userIsLocked'] == LOCK_TYPE_NO_LOCK || !FuncUsers::checkLockStatus($userData)) && in_array($p['lockType'],array(LOCK_TYPE_NO_LOGIN,LOCK_TYPE_NO_POSTING)) && $p['lockTime'] >= -1) {
					$lockStartTime = time();
					$lockEndTime = ($p['lockTime'] == -1 ? $lockStartTime : $lockStartTime+$p['lockTime']*3600);

					$this->modules['DB']->queryParams('
						INSERT INTO
							'.TBLPFX.'users_locks
						SET
							"userID"=$1,
							"lockType"=$2,
							"lockStartTimestamp"=$3,
							"lockEndTimestamp"=$4
					', array(
						$userID,
						$p['lockType'],
						$lockStartTime,
						$lockEndTime
					));
					$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'users SET "userIsLocked"=$1 WHERE "userID"=$2', array($p['lockType'], $userID));
				}

				Functions::myHeader(INDEXFILE."?action=AdminUsers&mode=EditUser&userID=$userID&".MYSID);
			break;

			case 'UnlockUser':
				$userID = isset($_GET['userID']) ? intval($_GET['userID']) : 0;

				if(!$userData = FuncUsers::getUserData($userID)) die('Cannot load data: user');

				$this->modules['DB']->queryParams('DELETE FROM '.TBLPFX.'users_locks WHERE "userID"=$1', array($userID));
				$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'users SET "userIsLocked"=0 WHERE "userID"=$1', array($userID));

				Functions::myHeader(INDEXFILE."?action=AdminUsers&mode=EditUser&userID=$userID&".MYSID);
			break;
		}
	}
}

?>