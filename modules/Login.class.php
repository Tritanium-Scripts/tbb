<?php
class Login extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Config',
		'Constants',
		'DB',
		'Language',
		'Navbar',
		'Template'
	);

	public function executeMe() {
		$this->modules['Language']->addFile('Login');

		$this->modules['Navbar']->addElement($this->modules['Language']->getString('login'),INDEXFILE.'?action=Login&amp;'.MYSID);

		switch(@$_GET['mode']) {
			default:
				$userNick = isset($_REQUEST['userNick']) ? $_REQUEST['userNick'] : '';
				$p = Functions::getSGValues($_POST['p'],array('userPassword'),'');
				$c = Functions::getSGValues($_POST['c'],array('enableGhostMode','stayLoggedIn'),0);

				$error = '';

				if(isset($_GET['doit'])) {
					//
					// Im Folgenden wird ueberprueft, ob
					// 1) der User ueberhaupt existiert
					// 2) der Account aktiviert ist
					// 3) das Passwort stimmt, bzw. das Passwort mit einem neu angeforderten ueberein stimmt
					// 4) der User gesperrt ist und falls ja wie lange
					//
					if(trim($userNick) == '' || ($userData = FuncUsers::getUserData($userNick)) == FALSE) $error = $this->modules['Language']->getString('error_unknown_user');
					elseif($userData['userIsActivated'] != 1) {
						//$error = sprintf($this->modules['Language']->getString('error_inactive_account'),$userData['userNick']);
						Functions::myHeader(INDEXFILE.'?action=Login&mode=ActivateAccount&accountID='.$userData['userNick'].'&showMessage=1&'.MYSID);
					}
					elseif(Functions::getSaltedHash($p['userPassword'],$userData['userPasswordSalt']) != $userData['userPassword'] && ($userData['userNewPassword'] == '' || Functions::getSaltedHash($p['userPassword'],$userData['userNewPasswordSalt']) != $userData['userNewPassword'])) $error = $this->modules['Language']->getString('error_wrong_password');
					elseif($userData['userIsLocked'] == LOCK_TYPE_NO_LOGIN && FuncUsers::checkLockStatus($userData)) { // Falls der Benutzer sich nicht mehr einloggen darf
						if($userData['userLockStartTimestamp'] == $userData['userLockEndTimestamp']) $remainingLockTime = $this->modules['Language']->getString('locked_forever');
						else {
							$remainingLockTime = Functions::splitTime($userData['userLockEndTimestamp']-$userData['userLockStartTimestamp']);

							$remainingMonths = sprintf($this->modules['Language']->getString('x_months'),$remainingLockTime['months']);
							$remainingWeeks = sprintf($this->modules['Language']->getString('x_weeks'),$remainingLockTime['weeks']);
							$remainingDays = sprintf($this->modules['Language']->getString('x_days'),$remainingLockTime['days']);
							$remainingHours = sprintf($this->modules['Language']->getString('x_hours'),$remainingLockTime['hours']);
							$remainingMinutes = sprintf($this->modules['Language']->getString('x_minutes'),$remainingLockTime['minutes']);
							$remainingSeconds = sprintf($this->modules['Language']->getString('x_seconds'),$remainingLockTime['seconds']);

							$remainingLockTime = "$remainingMonths, $remainingWeeks, $remainingDays, $remainingHours, $remainingMinutes, $remainingSeconds";
						}

						$error = sprintf($this->modules['Language']->getString('error_locked_account'),$remainingLockTime);
					}
					else {
						//
						// Ueberpruefen, ob der Geist-Modus erlaubt sein soll
						//
						if($this->modules['Config']->getValue('allow_ghost_mode') == 0) $c['enableGhostMode'] = 0;

						//
						// Erst werden die Logindaten in der Session gespeichert, dann
						// werden sie in noch in der Datenbank aktualisiert
						//
						$this->modules['Auth']->setSessionUserID($userData['userID']);
						$this->modules['Auth']->setSessionUserPassword($userData['userPassword']);
                        $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'sessions SET "sessionUserID"=$1, "sessionIsGhost"=$2 WHERE "sessionID"=$3', array($userData['userID'], $c['enableGhostMode'], session_id()));

						//
						// Jetzt wird (falls im Browser aktiviert) ein Cookie gesetzt. Entweder
						// wird das Cookie nur als "Sicherheitscookie" gesetzt, um eventuelles
						// Ausloggen wegen Inaktivitaet zu verhindern, oder das Cookie wird fuer
						// ein Jahr gesetzt, um einen permanenten/automatischen Login zu erreichen
						//
						if($c['stayLoggedIn'] == 1) setcookie('loginData',$userData['userID'].','.$userData['userPassword'],time()+31536000);
						else setcookie('loginData',$userData['userID'].','.$userData['userPassword']);

						//
						// Die folgenden vier Zeilen dienen dazu um entweder ein vorhandenes
						// neu angefordertes Passwort zu loeschen, wenn es nicht benoetigt wurde,
						// oder um das alte Passwort durch das neue zu ersetzen.
						//
						if(Functions::getSaltedHash($p['userPassword'],$userData['userPasswordSalt']) == $userData['userPassword'] && $userData['userNewPassword'] != '')
                            $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'users SET "userNewPassword"=\'\', "userNewPasswordSalt"=\'\' WHERE "userID"=$1', array($userData['userID']));
						elseif($userData['userNewPassword'] != '' && Functions::getSaltedHash($p['userPassword'],$userData['userNewPasswordSalt']) == $userData['userNewPassword'])
                            $this->modules['DB']->queryParams('UPDATE '.TBLPFX.'users SET "userNewPassword"=\'\', "userNewPasswordSalt"=\'\', "userPassword"=$1, "userPasswordSalt"=$2 WHERE "userID"=$3', array($userData['userNewPassword'], $userData['userNewPasswordSalt'], $userData['userID']));

						// set userLastVisit
						$this->modules['DB']->queryParams('UPDATE '.TBLPFX.'users SET "userLastVisit"="userLastAction", "userLastAction"='.time().' WHERE "userID"=$1',array($userData['userID']));

						//
						// Im Folgenden wird nur gecheckt, ob der User vorher irgendwo war,
						// also dorthin weitergeleitet werden soll. Falls nicht, wird einfach
						// die Forenuebersicht aufgerufen
						//
						isset($_SESSION['lastPlaceUrl']) ? Functions::myHeader($_SESSION['lastPlaceUrl']) : Functions::myHeader('index.php?'.MYSID);
					}
				}

				$this->modules['Template']->assign(array(
					'c'=>$c,
					'p'=>$p,
					'userNick'=>$userNick,
					'error'=>$error
				));
				$this->modules['Template']->printPage('LoginLogin.tpl');
				break;

			case 'ActivateAccount':
				$accountID = isset($_REQUEST['accountID']) ? $_REQUEST['accountID'] : '';
				$activationCode = isset($_REQUEST['activationCode']) ? $_REQUEST['activationCode'] : '';

				$error = '';

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('account_activation'),INDEXFILE."?action=Login&amp;mode=ActivateAccount&amp;".MYSID);

				if(isset($_GET['doit'])) {
					if(!$accountIDReal = FuncUsers::getUserID($accountID)) $error = $this->modules['Language']->getString('error_unknown_user');
					else {
						$accountData = FuncUsers::getUserData($accountIDReal);
						if($accountData['userIsActivated'] != 0 || $accountData['userHash'] == '') $error = $this->modules['Language']->getString('error_already_activated');
						elseif($accountData['userHash'] != $activationCode) $error = $this->modules['Language']->getString('error_wrong_activation_code');
						else {
                            $this->modules['DB']->queryParams('
                                UPDATE
                                    '.TBLPFX.'users
                                SET
                                    "userIsActivated"=1,
                                    "userHash"=\'\'
                                WHERE
                                    "userID"=$1
                            ', array(
                                $accountIDReal
                            ));

							$_SESSION['last_place_url'] = INDEXFILE.'?'.MYSID;

							FuncMisc::printMessage('account_activated',array(sprintf($this->modules['Language']->getString('message_link_click_here_login'),'<a href="'.INDEXFILE.'?action=Login&amp;'.MYSID.'">','</a>')));
							exit;
						}
					}
				}

				$this->modules['Template']->assign(array(
					'error'=>$error,
					'accountID'=>$accountID,
					'activationCode'=>$activationCode
				));

				$this->modules['Template']->printPage('LoginActivateAccount.tpl');
				break;

			case 'RequestPassword':
				$p = Functions::getSGValues($_POST['p'],array('userName','emailAddress'));

				$error = '';

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('request_new_password'),INDEXFILE."?action=Login&amp;mode=RequestPassword&amp;".MYSID);

				if(isset($_GET['doit'])) {
					if(!$userData = FuncUsers::getUserData($p['userName'])) $error = $this->modules['Language']->getString('error_unknown_user');
					elseif($userData['userEmailAddress'] != $p['emailAddress']) $error = $this->modules['Language']->getString('error_wrong_email_address');
					else {
						$newPassword = Functions::getRandomString(8);
						$newPasswordSalt = Functions::getRandomString(10);
						$newPasswordEncrypted = Functions::getSaltedHash($newPassword,$newPasswordSalt);

                        $this->modules['DB']->queryParams('
                            UPDATE
                                '.TBLPFX.'users
                            SET
                                "userNewPassword"=$1,
                                "userNewPasswordSalt"=$2
                            WHERE
                                "userID"=$3
                        ', array(
                            $newPasswordEncrypted,
                            $newPasswordSalt,
                            $userData['userID']
                        ));

						if($this->modules['Config']->getValue('enable_email_functions') == 1) {
							$this->modules['Template']->assign(array(
								'userNick'=>$userData['userNick'],
								'newPassword'=>$newPassword
							));

							Functions::myMail(
								$this->modules['Config']->getValue('board_name').' <'.$this->modules['Config']->getValue('board_email_address').'>',
								$userData['userEmailAddress'],
								$this->modules['Language']->getString('email_subject_new_password_requested'),
								$this->modules['Template']->fetch('PasswordRequested.mail',$this->modules['Language']->getLD().'mails')
							);
						}

						FuncMisc::printMessage('new_password_sent',array(
							sprintf($this->modules['Language']->getString('message_link_click_here_login'),'<a href="'.INDEXFILE.'?action=Login&amp;'.MYSID.'">','</a>'),
							sprintf($this->modules['Language']->getString('message_link_click_here_back_forumindex'),'<a href="'.INDEXFILE.'?'.MYSID.'">','</a>'),
						));
						exit;
					}
				}

				$this->modules['Template']->assign(array(
					'error'=>$error,
					'p'=>$p
				));
				$this->modules['Template']->printPage('LoginRequestPassword.tpl');
				break;

			case 'RequestActivationCode':
				$p = Functions::getSGValues($_POST['p'],array('userName','emailAddress'));

				$errors = array();

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('request_activation_code'),INDEXFILE."?action=Login&amp;mode=RequestActivationCode&amp;".MYSID);

				if(isset($_GET['doit'])) {
					if(!$userData = FuncUsers::getUserData($p['userName'])) $errors[] = $this->modules['Language']->getString('error_unknown_user');
					elseif($userData['userEmailAddress'] != $p['emailAddress']) $errors[] = $this->modules['Language']->getString('error_wrong_email_address');
					elseif($userData['userIsActivated'] == 1) $errors[] = $this->modules['Language']->getString('error_already_activated');

					if(count($errors) == 0) {
						$this->modules['Template']->assign(array(
							'userNick'=>$userData['userNick'],
							'activationLink'=>$this->modules['Config']->getValue('board_address').'/'.INDEXFILE.'?action=Login&mode=ActivateAccount&accountID='.$p['userName'].'&activationCode='.$userData['userHash'].'&doit=1',
							'activationCode'=>$userData['userHash']
						));
						Functions::myMail(
							$this->modules['Config']->getValue('board_name').' <'.$this->modules['Config']->getValue('board_email_address').'>',
							$userData['userEmailAddress'],
							sprintf($this->modules['Language']->getString('email_subject_account_activation'),$this->modules['Config']->getValue('board_name')),
							$this->modules['Template']->fetch('RegistrationAccountVerification.mail',$this->modules['Language']->getLD().'mails')
						);

						FuncMisc::printMessage('activation_code_sent',array(
							sprintf($this->modules['Language']->getString('message_link_click_here_account_activation'),'<a href="'.INDEXFILE.'?action=ActivateAccount&amp;'.MYSID.'">','</a>'),
							sprintf($this->modules['Language']->getString('message_link_click_here_login'),'<a href="'.INDEXFILE.'?action=Login&amp;'.MYSID.'">','</a>'),
							sprintf($this->modules['Language']->getString('message_link_click_here_back_forumindex'),'<a href="'.INDEXFILE.'?'.MYSID.'">','</a>'),
						));
						exit;
					}
				}

				$this->modules['Template']->assign(array(
					'errors'=>$errors,
					'p'=>$p
				));
				$this->modules['Template']->printPage('LoginRequestActivationCode.tpl');
				break;
		}
	}
}