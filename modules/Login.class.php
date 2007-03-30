<?php

class Login extends ModuleTemplate {
	protected $RequiredModules = array(
		'Auth',
		'Config',
		'DB',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
		$this->Modules['Language']->addFile('Login');

		$UserNick = isset($_REQUEST['UserNick']) ? $_REQUEST['UserNick'] : '';
		$p = Functions::getSGValues($_POST['p'],array('UserPassword'),'');
		$c = Functions::getSGValues($_POST['c'],array('EnableGhostMode','StayLoggedIn'),0);

		$Error = '';

		if(isset($_GET['Doit'])) {
			//
			// Im Folgenden wird ueberprueft, ob
			// 1) der User ueberhaupt existiert
			// 2) der Account aktiviert ist
			// 3) das Passwort stimmt, bzw. das Passwort mit einem neu angeforderten ueberein stimmt
			// 4) der User gesperrt ist und falls ja wie lange
			//
			if(trim($UserNick) == '' || ($UserData = Functions::getUserData($UserNick)) == FALSE) $Error = $this->Modules['Language']->getString('error_unknown_user');
			elseif($UserData['UserStatus'] == USER_STATUS_INACTIVE) $Error = sprintf($this->Modules['Language']->getString('error_inactive_account'),$UserData['UserNick']);
			elseif(Functions::getSaltedHash($p['UserPassword'],$UserData['UserPasswordSalt']) != $UserData['UserPassword'] && ($UserData['UserNewPassword'] == '' || Functions::getSaltedHash($p['UserPassword'],$UserData['UserNewPasswordSalt']) != $UserData['UserNewPassword'])) $Error = $this->Modules['Language']->getString('error_wrong_password');
			elseif($UserData['UserIsLocked'] == 1 && Functions::checkLockStatus($UserData['UserID']) == TRUE) { // Falls der Benutzer sich nicht mehr einloggen darf
				$DB->query("SELECT lock_start_time,lock_dur_time FROM ".TBLPFX."users_locks WHERE user_id='".$p_user_data['user_id']."'");
				$lock_data = $DB->fetch_array();

				if($lock_data['lock_dur_time'] == 0) $remaining_lock_time = $this->Modules['Language']->getString('locked_forever');
				else {
					$remaining_lock_time = split_time($lock_data['lock_start_time']+$lock_data['lock_dur_time']-time());

					$remaining_months = sprintf($this->Modules['Language']->getString('x_months'),$remaining_lock_time['months']);
					$remaining_weeks = sprintf($this->Modules['Language']->getString('x_weeks'),$remaining_lock_time['weeks']);
					$remaining_days = sprintf($this->Modules['Language']->getString('x_days'),$remaining_lock_time['days']);
					$remaining_hours = sprintf($this->Modules['Language']->getString('x_hours'),$remaining_lock_time['hours']);
					$remaining_minutes = sprintf($this->Modules['Language']->getString('x_minutes'),$remaining_lock_time['minutes']);
					$remaining_seconds = sprintf($this->Modules['Language']->getString('x_seconds'),$remaining_lock_time['seconds']);

					$remaining_lock_time = "$remaining_months, $remaining_weeks, $remaining_days, $remaining_hours, $remaining_minutes, $remaining_seconds";
				}

				$Error = sprintf($this->Modules['Language']->getString('error_locked_account'),$remaining_lock_time);
			}
			else {
				//
				// Ueberpruefen, ob der Geist-Modus erlaubt sein soll
				//
				if($this->Modules['Config']->getValue('allow_ghost_mode') == 0) $c['EnableGhostMode'] = 0;

				//
				// Erst werden die Logindaten in der Session gespeichert, dann
				// werden sie in noch in der Datenbank aktualisiert
				//
				$this->Modules['Auth']->setSessionUserID($UserData['UserID']);
				$this->Modules['Auth']->setSessionUserPassword($UserData['UserPassword']);
				$this->Modules['DB']->query("UPDATE ".TBLPFX."sessions SET SessionUserID='".$UserData['UserID']."', SessionIsGhost='".$c['EnableGhostMode']."' WHERE SessionID='".session_id()."'");

				//
				// Jetzt wird (falls im Browser aktiviert) ein Cookie gesetzt. Entweder
				// wird das Cookie nur als "Sicherheitscookie" gesetzt, um eventuelles
				// Ausloggen wegen Inaktivitaet zu verhindern, oder das Cookie wird fuer
				// ein Jahr gesetzt, um einen permanenten/automatischen Login zu erreichen
				//
				if($c['StayLoggedIn'] == 1) setcookie('LoginData',$UserData['UserID'].','.$UserPasswordEncrypted,time()+31536000);
				else setcookie('LoginData',$UserData['UserID'].','.$UserPasswordEncrypted);

				//
				// Die folgenden vier Zeilen dienen dazu um entweder ein vorhandenes
				// neu angefordertes Passwort zu loeschen, wenn es nicht benoetigt wurde,
				// oder um das alte Passwort durch das neue zu ersetzen.
				//
				if(Functions::getSaltedHash($p['UserPassword'],$UserData['UserPasswordSalt']) == $UserData['UserPassword'] && $UserData['UserNewPassword'] != '')
					$DB->query("UPDATE ".TBLPFX."users SET UserNewPassword='', UserNewPasswordSalt='' WHERE UserID='".$UserData['UserID']."'");
				elseif($UserData['UserNewPassword'] != '' && Functions::getSaltedHash($p['UserPassword'],$UserData['UserNewPasswordSalt']) == $UserData['UserNewPassword'])
					$DB->query("UPDATE ".TBLPFX."users SET UserNewPassword='', UserNewPasswordSalt='', UserPassword='".$UserData['UserNewPassword']."', UserPasswordSalt='".$UserData['UserPasswordSalt']."' WHERE UserID='".$UserData['UserID']."'");

				//
				// Im Folgenden wird nur gecheckt, ob der User vorher irgendwo war,
				// also dorthin weitergeleitet werden soll. Falls nicht, wird einfach
				// die Forenuebersicht aufgerufen
				//
				isset($_SESSION['LastPlaceUrl']) ? Functions::myHeader($_SESSION['LastPlaceUrl']) : Functions::myHeader('index.php?'.MYSID);
			}
		}

		$this->Modules['Navbar']->addElement($this->Modules['Language']->getString('Login'),INDEXFILE.'?Action=Login&amp;'.MYSID);

		$this->Modules['Template']->assign(array(
			'c'=>$c,
			'p'=>$p,
			'UserNick'=>$UserNick,
			'Error'=>$Error
		));
		$this->Modules['PageParts']->printPage('Login.tpl');
	}
}

?>