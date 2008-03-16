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
			$this->modules['DB']->queryParams('SELECT * FROM '.TBLPFX.'users WHERE "userID"=$1', array($_SESSION['userID']));
			if($this->modules['DB']->numRows() == 1) {
				$tempUserData = $this->modules['DB']->fetchArray();
				if(($tempUserData['userIsLocked'] == 0 || !FuncUsers::checkLockStatus($tempUserData['userID'])) && $tempUserData['userPassword'] == $_SESSION['userPassword']) {
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
	
	public function getAuthedForumsIDs() {
		if(!$this->userLoggedIn) {
			$this->modules['DB']->query('SELECT "forumID" FROM '.TBLPFX.'forums WHERE "authViewForumGuests"=\'1\'');
			return $this->modules['DB']->raw2FVArray();
		}

		$authedForumsIDs = array();

		$notAuthedForumsIDs = array();
		if($this->userData['userIsAdmin'] != 1 && $this->userData['userIsSupermod'] != 1) {
			$this->modules['DB']->queryParams('
				SELECT
					"forumID"
				FROM
					'.TBLPFX.'forums_auth
				WHERE
					(("authType"=$1 AND "authID"=$2)
					OR ("authType"=$3 AND "authID" IN (
						SELECT
							"groupID"
						FROM
							'.TBLPFX.'groups_members
						WHERE
							"memberID"=$2
					)) AND "authIsMod=\'0\' AND "authViewForum"=\'0\'
			',array(
				AUTH_TYPE_USER,
				USERID,
				AUTH_TYPE_GROUP
			));
		}

		$this->modules['DB']->query('
			SELECT
				"forumID"
			FROM
				'.TBLPFX.'forums
			WHERE
				"forumID" NOT IN ($1)
		',array(
			$notAuthedForumsIDs)
		);
		return $this->modules['DB']->raw2FVArray();
	}
}

?>