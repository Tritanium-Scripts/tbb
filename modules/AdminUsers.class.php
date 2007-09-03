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
					elseif(trim($p['userEmailAddress']) == '' || Functions::verifyEmailAddress($p['userEmailAddress'])) $error = $this->modules['Language']->getString('error_bad_email');
					elseif(trim($p['userPassword']) == '') $error = $this->modules['Language']->getString('error_no_pw');
					elseif($p['userPassword'] != $p['userPasswordConfirmation']) $error = $this->modules['Language']->getString('error_pws_no_match');
					else {
						$userPasswordSalt = Functions::getRandomString(10);
						$userPasswordEncrypted = Functions::getSaltedHash($p['userPassword'],$userPasswordSalt);

						$this->modules['DB']->query("
							INSERT INTO
								".TBLPFX."users
							SET
								userNick='".$p['userNick']."',
								userEmailAddress='".$p['userEmailAddress']."',
								userPassword='".$userPasswordEncrypted."',
								userPasswordSalt='".Functions::addSlashes($userPasswordEncrypted)."',
								userRegistrationTimestamp='".time()."',
								userTimeZone='".$this->modules['Config']->getValue('standard_tz')."'
						");
						$newUserID = $this->modules['DB']->getInsertID();

						FuncConfig::updateLatestUser($newUserID,$p['userNick']);

						// TODO:
						// Eventuell per Email benachrichtigen
						if($p_notify_user == 1 && $CONFIG['enable_email_functions'] == 1) {
							$email_tpl = new Template($LANGUAGE_PATH.'/emails/email_welcome.tpl');
							mymail($CONFIG['board_name'].' <'.$CONFIG['board_email_address'].'>',$p_user_email,sprintf($LNG['email_subject_welcome'],$CONFIG['board_name']),$email_tpl->parseCode());
						}

						$this->modules['Template']->printMessage('new_user_added'); exit;
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
					$query[] = "userID LIKE '".str_replace('*','%',Functions::addSlashes($p['userID']))."'";
				if(trim($p['userNick']) != '')
					$query[] = "userNick LIKE '".str_replace('*','%',Functions::addSlashes($p['userNick']))."'";
				if(trim($p['userEmailAddress']) != '')
					$query[] = "userEmailAddress LIKE '".str_replace('*','%',Functions::addSlashes($p['userEmailAddress']))."'";

				$usersData = array();

				if(count($query) > 0) {
					$this->modules['DB']->query("SELECT userID,userNick,userEmailAddress FROM ".TBLPFX."users WHERE ".implode(' AND ',$query));

					if($this->modules['DB']->getAffectedRows() == 1) {
						$result = $this->modules['DB']->fetchArray();
						Functions::myHeader(INDEXFILE.'?action=AdminUsers&mode=EditUser&userID='.$result['userID'].'&'.MYSID);
					}
					elseif($this->modules['DB']->getAffectedRows() > 0)
						$usersData = $this->modules['DB']->raw2Array();
				}

				$this->modules['Template']->assign(array(
					'usersData'=>$usersData,
					'p'=>Functions::stripSlashes(Functions::HTMLSpecialChars($p))
				));
				$this->modules['Template']->printPage('AdminUsersSearchUsers.tpl');
				break;

			case 'EditUser':
				$userID = isset($_GET['userID']) ? intval($_GET['userID']) : 0;

				if(!$userData = FuncUsers::getUserData($userID)) die('Cannot load data: user');

				$this->modules['Language']->addFile('AdminRanks');
				$this->modules['Language']->addFile('EditProfile');
				$this->modules['Language']->addFile('ViewProfile');

				$p = Functions::getSGValues($_POST['p'],array('userEmailAddress','userSignature','userAvatarAddress','rankID','userAuthProfileNotes'),'',Functions::addSlashes($userData));
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

						$this->modules['DB']->query("UPDATE ".TBLPFX."users SET
							userIsAdmin='".$c['userIsAdmin']."',
							userIsSupermod='".$c['userIsSupermod']."',
							userEmailAddress='".$p['userEmailAddress']."',
							userSignature='".$p['userSignature']."',
							userAvatarAddress='".$p['userAvatarAddress']."',
							rankID='".$p['rankID']."'
						WHERE userID='$userID'");

						$this->modules['Template']->printMessage('user_edited'); exit;
					}
				}


				/**
				 * Handle user lock
				 */
				if($userData['userIsLocked'] != 0 && FuncUsers::checkLockStatus($userID)) {
					$this->modules['DB']->query("SELECT * FROM ".TBLPFX."users_locks WHERE userID='$userID'");
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
					$userData['userIsLocked'] = 0;
				}

				$this->modules['DB']->query("SELECT rankID,rankName FROM ".TBLPFX."ranks WHERE rankType='1' ORDER BY rankName ASC");
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

			case 'deleteuser':
				$userID = isset($_GET['userID']) ? intval($_GET['userID']) : 0;
				$p_delete_posts = isset($_POST['p_delete_posts']) ? 1 : 0;
				$p_ban_nick_email = isset($_POST['p_ban_nick_email']) ? 1 : 0;

				if(!$userData = get_userData($userID)) die('Benutzer existiert nicht!');

				$this->modules['DB']->query("DELETE FROM ".TBLPFX."users WHERE userID='$userID'"); // Userdaten
				$this->modules['DB']->query("DELETE FROM ".TBLPFX."pms_folders WHERE userID='$userID'"); // PMs-Ordner
				$this->modules['DB']->query("UPDATE ".TBLPFX."pms SET userID='0', pm_guest_nick='".$userData['user_nick']."' WHERE pm_from_id='$userID'"); // PM-Nachrichten in fremden Ordnern
				$this->modules['DB']->query("DELETE FROM ".TBLPFX."pms WHERE pm_to_id='$userID'"); // Eigene PMs
				$this->modules['DB']->query("DELETE FROM ".TBLPFX."polls_votes WHERE voter_id='$userID'"); // Umfrageteilnahmen
				$this->modules['DB']->query("DELETE FROM ".TBLPFX."groups_members WHERE member_id='$userID'"); // Gruppenmitgliedschaften
				$this->modules['DB']->query("DELETE FROM ".TBLPFX."forums_auth WHERE auth_type='0' AND auth_id='$userID'"); // Forenzugriffe
				$this->modules['DB']->query("DELETE FROM ".TBLPFX."topics_subscriptions WHERE userID='$userID'"); // Themenabonnements


				//
				// Themen/Beitraege
				//
				if($p_delete_posts == 1) { // Falls alles geloescht werden soll...
					$affected_forum_ids = array(); // Beinhaltet spaeter alle Foren-IDs, in denen Beitraege geloescht wurden
					$affected_topic_ids = array(); // Beinhaltet spaeter alle Themen-IDs, in denen Beitraege geloescht wurden

					//
					// Erst muessen die Themen geloescht werden, die der User erstellt hat
					// Dazu werden erst mal die Themen-IDs bestimmt
					//
					$this->modules['DB']->query("SELECT topic_id FROM ".TBLPFX."topics WHERE poster_id='$userID'");
					$topic_ids = $this->modules['DB']->raw2fvarray();
					$topic_idsi = implode("','",$topic_ids);


					//
					// Jetzt muessen die Beitragszahlen der User entsprechend gesenkt werden
					//
					$this->modules['DB']->query("SELECT COUNT(*) AS posts_counter,poster_id FROM ".TBLPFX."posts WHERE topic_id IN ('$topic_idsi') GROUP BY poster_id");
					$posts_counter = $this->modules['DB']->raw2array();
					while(list(,$akt_posts_counter) = each($posts_counter))
						$this->modules['DB']->query("UPDATE ".TBLPFX."users SET user_posts=user_posts-".$akt_posts_counter['posts_counter']." WHERE userID='".$akt_posts_counter['poster_id']."'");


					//
					// Und nun die Beitragszahlen der entsprechenden Foren
					//
					$this->modules['DB']->query("SELECT COUNT(*) AS posts_counter,forum_id FROM ".TBLPFX."posts WHERE topic_id IN ('$topic_idsi') GROUP BY forum_id");
					$posts_counter = $this->modules['DB']->raw2array();
					while(list(,$akt_posts_counter) = each($posts_counter)) {
						$this->modules['DB']->query("UPDATE ".TBLPFX."forums SET forum_posts_counter=forum_posts_counter-".$akt_posts_counter['posts_counter']." WHERE forum_id='".$akt_posts_counter['forum_id']."'");
						$affected_forum_ids = $akt_posts_counter['forum_id'];
					}


					//
					// Jetzt die Themenzahlen der entsprechenden Foren
					//
					$this->modules['DB']->query("SELECT COUNT(*) AS topics_counter,forum_id FROM ".TBLPFX."topics WHERE topic_id IN ('$topic_idsi') GROUP BY forum_id");
					$topics_counter = $this->modules['DB']->raw2array();
					while(list(,$akt_topics_counter) = each($topics_counter))
						$this->modules['DB']->query("UPDATE ".TBLPFX."forums SET forum_topics_counter=forum_topics_counter-".$akt_topics_counter['topics_counter']." WHERE forum_id='".$akt_topics_counter['forum_id']."'");


					//
					// Jetzt werden die Themen-Abonnnements, die Themen und die Beitraege geloescht
					//
					$this->modules['DB']->query("DELETE FROM ".TBLPFX."topics_subscriptions WHERE topic_id IN ('$topic_idsi')");
					$this->modules['DB']->query("DELETE FROM ".TBLPFX."posts WHERE topic_id IN ('$topic_idsi')");
					$this->modules['DB']->query("DELETE FROM ".TBLPFX."topics WHERE topic_id IN ('$topic_idsi')");


					//
					// Jetzt noch die Umfragen, dazu die Umfrageoptionen und die Abstimmungen
					//
					$this->modules['DB']->query("SELECT poll_id FROM ".TBLPFX."polls WHERE topic_id IN ('$topic_idsi')");
					$poll_ids = $this->modules['DB']->raw2fvarray();
					$poll_idsi = implode("','",$poll_ids);

					$this->modules['DB']->query("DELETE FROM ".TBLPFX."polls WHERE poll_id IN ('$poll_idsi')");
					$this->modules['DB']->query("DELETE FROM ".TBLPFX."polls_options WHERE poll_id IN ('$poll_idsi')");
					$this->modules['DB']->query("DELETE FROM ".TBLPFX."polls_votes WHERE poll_id IN ('$poll_idsi')");


					//
					// Als letztes die einzelnen Beitraege des Users, dazu erst die Beitragszahlen der entsprechenden Foren, dann die Beitragszahlen der einzelnen Themen, dann die Beitraege selbst
					//
					$this->modules['DB']->query("SELECT COUNT(*) AS posts_counter,forum_id FROM ".TBLPFX."posts WHERE poster_id='$userID' GROUP BY forum_id");
					$forum_posts_counter = $this->modules['DB']->raw2array();
					while(list(,$akt_posts_counter) = each($forum_posts_counter)) {
						$this->modules['DB']->query("UPDATE ".TBLPFX."forums SET forum_posts_counter=forum_posts_counter-".$akt_posts_counter['posts_counter']." WHERE forum_id='".$akt_posts_counter['forum_id']."'");
						$affected_forum_ids[] = $akt_posts_counter['forum_id'];
					}

					$this->modules['DB']->query("SELECT COUNT(*) AS replies_counter,topic_id FROM ".TBLPFX."posts WHERE poster_id='$userID' GROUP BY topic_id");
					$replies_counter = $this->modules['DB']->raw2array();
					while(list(,$akt_replies_counter) = each($replies_counter)) {
						$this->modules['DB']->query("UPDATE ".TBLPFX."topics SET topic_replies_counter=topic_replies_counter-".$akt_replies_counter['replies_counter']." WHERE topic_id='".$akt_replies_counter['topic_id']."'");
						$affected_topic_ids[] = $akt_replies_counter['topic_id'];
					}

					$this->modules['DB']->query("DELETE FROM ".TBLPFX."posts WHERE poster_id='$userID'");


					//
					// Jetzt noch Foren und Themen mit dem letzten Beitrag updaten
					//
					$affected_forum_ids = array_unique($affected_forum_ids);
					while(list(,$akt_forum_id) = each($affected_forum_ids))
						update_forum_last_post($akt_forum_id);

					$affected_topic_ids = array_unique($affected_topic_ids);
					while(list(,$akt_topic_id) = each($affected_topic_ids))
						update_topic_last_post($akt_topic_id);
				}
				else { // ...oder auch nicht
					$this->modules['DB']->query("UPDATE ".TBLPFX."posts SET poster_id='0', post_guest_nick='".$userData['user_nick']."' WHERE poster_id='$userID'");
					$this->modules['DB']->query("UPDATE ".TBLPFX."topics SET poster_id='0', topic_guest_nick='".$userData['user_nick']."' WHERE poster_id='$userID'");
				}
			break;

			case 'LockUser':
				$userID = isset($_GET['userID']) ? intval($_GET['userID']) : 0;

				if(!$userData = FuncUsers::getUserData($userID)) die('Cannot load data: user');

				$p = Functions::getSGValues($_POST['p'],array('lockType','lockTime'),0);

				if(($userData['userIsLocked'] == 0 || !FuncUsers::checkLockStatus($userData['userID'])) && in_array($p['lockType'],array(LOCK_TYPE_NO_LOGIN,LOCK_TYPE_NO_POSTING)) && $p['lockTime'] >= -1) {
					$lockStartTime = time();
					$lockEndTime = ($p['lockTime'] == -1 ? $lockStartTime : $lockStartTime+$p['lockTime']*3600);

					$this->modules['DB']->query("
						INSERT INTO
							".TBLPFX."users_locks
						SET
							userID='$userID',
							lockType='".$p['lockType']."',
							lockStartTimestamp='$lockStartTime',
							lockEndTimestamp='$lockEndTime'
					");
					$this->modules['DB']->query("UPDATE ".TBLPFX."users SET userIsLocked='".$p['lockType']."' WHERE userID='$userID'");
				}

				Functions::myHeader(INDEXFILE."?action=AdminUsers&mode=EditUser&userID=$userID&".MYSID);
			break;

			case 'UnlockUser':
				$userID = isset($_GET['userID']) ? intval($_GET['userID']) : 0;

				if(!$userData = FuncUsers::getUserData($userID)) die('Cannot load data: user');

				$this->modules['DB']->query("DELETE FROM ".TBLPFX."users_locks WHERE userID='$userID'");
				$this->modules['DB']->query("UPDATE ".TBLPFX."users SET userIsLocked='0' WHERE userID='$userID'");

				Functions::myHeader(INDEXFILE."?action=AdminUsers&mode=EditUser&userID=$userID&".MYSID);
			break;
		}
	}
}

?>