<?php

class Auth extends ModuleTemplate {
	protected $requiredModules = array(
		'Session',
		'DB'
	);
	protected $userLoggedIn = 0;
	protected $userID = 0;
	protected $userData = array();

	public function initializeMe() {
		if(isset($_SESSION['userID'])) {
			$this->modules['DB']->query("SELECT * FROM ".TBLPFX."users WHERE userID='".$_SESSION['userID']."'");
			if($this->modules['DB']->getAffectedRows() == 1) {
				$tempUserData = $this->modules['DB']->fetchArray();
				if($tempUserData['userPassword'] == $_SESSION['userPassword']) {
					$this->userID = $tempUserData['userID'];
					$this->userLoggedIn = 1;
					$this->userData = $tempUserData;
				}
			}
		}
		define('USERID',$this->userID);
	}

	public function getUserID() {
		return $this->userID;
	}

	public function isLoggedIn() {
		return $this->userLoggedIn;
	}

	public function getUserData() {
		return $this->userData;
	}

	public function setSessionUserID($newUserID) {
		$_SESSION['userID'] = $newUserID;
	}

	public function setSessionUserPassword($newUserPassword) {
		$_SESSION['userPassword'] = $newUserPassword;
	}

	public function getUserDataValue($key) {
		return isset($this->userData[$key]) ? $this->userData[$key] : FALSE;
	}

	public function getValue($key) {
		return $this->getUserDataValue($key);
	}

	public function setValue($key,$value) {
		$this->userData[$key] = $value;
	}

	public function destroySessionData() {
		unset($_SESSION['userID']);
		unset($_SESSION['userPassword']);
	}
}

?>