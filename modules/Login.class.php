<?php

class Login extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Config',
		'Constants',
		'DB',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
		$this->modules['Language']->addFile('Login');

		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Login'),INDEXFILE.'?action=Login&amp;'.MYSID);

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
					elseif($userData['userIsActivated'] != 1) $error = sprintf($this->modules['Language']->getString('error_inactive_account'),$userData['userNick']);
					elseif(Functions::getSaltedHash($p['userPassword'],$userData['userPasswordSalt']) != $userData['userPassword'] && ($userData['userNewPassword'] == '' || Functions::getSaltedHash($p['userPassword'],$userData['userNewPasswordSalt']) != $userData['userNewPassword'])) $error = $this->modules['Language']->getString('error_wrong_password');
					elseif($userData['userIsLocked'] == 1 && Functions::checkLockStatus($userData['userID'])) { // Falls der Benutzer sich nicht mehr einloggen darf
						$dB->query("SELECT lock_start_time,lock_dur_time FROM ".TBLPFX."users_locks WHERE user_id='".$p_user_data['user_id']."'");
						$lock_data = $dB->fetch_array();

						if($lock_data['lock_dur_time'] == 0) $remaining_lock_time = $this->modules['Language']->getString('locked_forever');
						else {
							$remaining_lock_time = split_time($lock_data['lock_start_time']+$lock_data['lock_dur_time']-time());

							$remaining_months = sprintf($this->modules['Language']->getString('x_months'),$remaining_lock_time['months']);
							$remaining_weeks = sprintf($this->modules['Language']->getString('x_weeks'),$remaining_lock_time['weeks']);
							$remaining_days = sprintf($this->modules['Language']->getString('x_days'),$remaining_lock_time['days']);
							$remaining_hours = sprintf($this->modules['Language']->getString('x_hours'),$remaining_lock_time['hours']);
							$remaining_minutes = sprintf($this->modules['Language']->getString('x_minutes'),$remaining_lock_time['minutes']);
							$remaining_seconds = sprintf($this->modules['Language']->getString('x_seconds'),$remaining_lock_time['seconds']);

							$remaining_lock_time = "$remaining_months, $remaining_weeks, $remaining_days, $remaining_hours, $remaining_minutes, $remaining_seconds";
						}

						$error = sprintf($this->modules['Language']->getString('error_locked_account'),$remaining_lock_time);
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
						$this->modules['DB']->query("UPDATE ".TBLPFX."sessions SET sessionUserID='".$userData['userID']."', sessionIsGhost='".$c['enableGhostMode']."' WHERE sessionID='".session_id()."'");

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
							$this->Modules['DB']->query("UPDATE ".TBLPFX."users SET userNewPassword='', userNewPasswordSalt='' WHERE userID='".$userData['userID']."'");
						elseif($userData['userNewPassword'] != '' && Functions::getSaltedHash($p['userPassword'],$userData['userNewPasswordSalt']) == $userData['userNewPassword'])
							$this->Modules['DB']->query("UPDATE ".TBLPFX."users SET userNewPassword='', userNewPasswordSalt='', userPassword='".$userData['userNewPassword']."', userPasswordSalt='".$userData['userPasswordSalt']."' WHERE userID='".$userData['userID']."'");

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
				$this->modules['PageParts']->printPage('LoginLogin.tpl');
				break;

			case 'ActivateAccount':
				$accountID = isset($_REQUEST['accountID']) ? $_REQUEST['accountID'] : '';
				$activationCode = isset($_REQUEST['activationCode']) ? $_REQUEST['activationCode'] : '';

				$error = '';

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Account_activation'),INDEXFILE."?action=Login&amp;mode=ActivateAccount&amp;".MYSID);

				if(isset($_GET['doit'])) {
					if(!$accountID = Functions::getUserID($accountID)) $error = $this->modules['Language']->getString('error_unknown_user');
					else {
						$accountData = FuncUsers::getUserData($accountID);
						if($accountData['userIsActivated'] != 0 || $accountData['userHash'] == '') $error = $this->modules['Language']->getString('error_no_inactive_account');
						elseif($accountData['userHash'] != $activationCode) $error = $this->modules['Language']->getString('error_wrong_activationCode');
						else {
							$this->modules['DB']->query("
								UPDATE
									".TBLPFX."users
								SET
									userIsActivated='1',
									userHash=''
								WHERE
									userID='$accountID'
							");

							$_SESSION['last_place_url'] = INDEXFILE.'?'.MYSID;

							$this->modules['Navbar']->addElement($this->modules['Language']->getString('Account_activated'),'');

							$this->modules['PageParts']->printMessage('account_activated',array(sprintf($this->modules['Language']->getString('message_link_click_here_login'),'<a href="'.INDEXFILE.'?action=Login&amp;'.MYSID.'">','</a>')));
							exit;
						}
					}
				}

				$this->modules['Template']->assign(array(
					'error'=>$error,
					'accountID'=>$accountID,
					'activationCode'=>$activationCode
				));

				$this->modules['PageParts']->printPage('LoginActivateAccount.tpl');
				break;

			case 'RequestPassword':
				$p = Functions::getSGValues($_POST,array('userName','emailAddress'));

				$error = '';

				$this->modules['Navbar']->addElement($this->modules['Language']->getString('Request_new_password'),INDEXFILE."?action=Login&amp;mode=RequestPassword&amp;".MYSID);

				if(isset($_GET['doit'])) {
					if(!$userData = Functions::getUserData($p['userName'])) $error = $this->modules['Language']->getString('error_unknown_user');
					elseif($userData['userEmailAddress'] != $p['emailAddress']) $error = $this->modules['Language']->getString('error_wrong_email_address');
					else {
						$newPassword = Functions::getRandomString(8);
						$newPasswordSalt = Functions::getRandomString(10);
						$newPasswordEncrypted = Functions::getSaltedHash($newPassword,$newPasswordSalt);

						$this->modules['DB']->query("
							UPDATE
								".TBLPFX."users
							SET
								userNewPassword='$newPasswordEncrypted',
								userNewPasswordSalt='$newPasswordSalt'
							WHERE
								userID='".$userData['userID']."'
						");

						if($this->modules['Config']->getValue('enable_email_functions') == 1) {
							// TODO: Email
							mymail('"'.$CONFIG['board_name'].'" <'.$CONFIG['board_email_address'].'>',$p_user_data['user_email'],$LNG['email_subject_new_password_requested'],$email_tpl->parseCode());
						}

						$this->modules['Navbar']->addElement($this->modules['Language']->getString('New_password_sent'),'');

						$this->modules['PageParts']->printMessage('new_password_sent',array(
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
				$this->modules['PageParts']->printPage('LoginRequestPassword.tpl');
				break;
		}
	}
}

?>