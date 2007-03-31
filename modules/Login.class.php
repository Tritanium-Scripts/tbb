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
			if(trim($userNick) == '' || ($userData = Functions::getUserData($userNick)) == FALSE) $error = $this->modules['Language']->getString('error_unknown_user');
			elseif($userData['userStatus'] == USER_STATUS_INACTIVE) $error = sprintf($this->modules['Language']->getString('error_inactive_account'),$userData['UserNick']);
			elseif(Functions::getSaltedHash($p['userPassword'],$userData['userPasswordSalt']) != $userData['userPassword'] && ($userData['userNewPassword'] == '' || Functions::getSaltedHash($p['userPassword'],$userData['userNewPasswordSalt']) != $userData['userNewPassword'])) $error = $this->modules['Language']->getString('error_wrong_password');
			elseif($userData['userIsLocked'] == 1 && Functions::checkLockStatus($userData['userID']) == TRUE) { // Falls der Benutzer sich nicht mehr einloggen darf
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
				//isset($_SESSION['lastPlaceUrl']) ? Functions::myHeader($_SESSION['lastPlaceUrl']) : Functions::myHeader('index.php?'.MYSID);
			}
		}

		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Login'),INDEXFILE.'?action=Login&amp;'.MYSID);

		$this->modules['Template']->assign(array(
			'c'=>$c,
			'p'=>$p,
			'userNick'=>$userNick,
			'error'=>$error
		));
		$this->modules['PageParts']->printPage('Login.tpl');
	}
}

?>