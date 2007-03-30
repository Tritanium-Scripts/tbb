<?php

class Auth extends ModuleTemplate {
	protected $RequiredModules = array(
		'Session',
		'DB'
	);
	protected $UserLoggedIn = 0;
	protected $UserID = 0;
	protected $UserData = array();

	public function initializeMe() {
		if(isset($_SESSION['UserID']) == TRUE) {
			$this->Modules['DB']->query("SELECT * FROM ".TBLPFX."users WHERE UserID='".$_SESSION['UserID']."'");
			if($this->Modules['DB']->getAffectedRows() == 1) {
				$TempUserData = $this->Modules['DB']->fetchArray();
				if($TempUserData['UserPassword'] == $_SESSION['UserPassword']) {
					$this->UserID = $TempUserData['UserID'];
					$this->UserLoggedIn = 1;
					$this->UserData = $TempUserData;
				}
			}
		}
		define('USERID',$this->UserID);
	}

	public function getUserID() {
		return $this->UserID;
	}

	public function isLoggedIn() {
		return $this->UserLoggedIn;
	}

	public function getUserData() {
		return $this->UserData;
	}

	public function setSessionUserID($newUserID) {
		$_SESSION['UserID'] = $newUserID;
	}

	public function setSessionUserPassword($newUserPassword) {
		$_SESSION['UserPassword'] = $newUserPassword;
	}

	public function getUserDataValue($Key) {
		return (isset($this->UserData[$Key]) == TRUE) ? $this->UserData[$Key] : FALSE;
	}

	public function getValue($Key) {
		return $this->getUserDataValue($Key);
	}

	public function setValue($Key,$Value) {
		$this->UserData[$Key] = $Value;
	}

	public function destroySessionData() {
		unset($_SESSION['UserID']);
		unset($_SESSION['UserPassword']);
	}
}

?>