<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class Auth extends ModuleTemplate {
	protected $requiredModules = array(
		'Constants',
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
				if(($tempUserData['userIsLocked'] != LOCK_TYPE_NO_LOGIN || !FuncUsers::checkLockStatus($tempUserData)) && $tempUserData['userPassword'] == $_SESSION['userPassword']) {
					if($tempUserData['userIsLocked'] == LOCK_TYPE_NO_LOGIN)
						$tempUserData['userIsLocked'] = LOCK_TYPE_NO_LOCK;
						
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
		//unset($_SESSION['userID']);
		//unset($_SESSION['userPassword']);
		session_destroy();
	}
	
	public function getAuthedForumsIDs() {
		if(!$this->userLoggedIn) {
			$this->modules['DB']->query('SELECT "forumID" FROM '.TBLPFX.'forums WHERE "authViewForumGuests"=\'1\'');
			return $this->modules['DB']->raw2FVArray();
		}

		if($this->userData['userIsAdmin'] != 1 && $this->userData['userIsSupermod'] != 1) {
			$this->modules['DB']->queryParams('
				SELECT
					"forumID"
				FROM
					'.TBLPFX.'forums
				WHERE
					"forumID" NOT IN ( 
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
								))) AND "authIsMod"=\'0\' AND "authViewForum"=\'0\'
					)
			',array(
				AUTH_TYPE_USER,
				USERID,
				AUTH_TYPE_GROUP
			));
		}
		else {
			$this->modules['DB']->query('
				SELECT
					"forumID"
				FROM
					'.TBLPFX.'forums
			');
		}

		return $this->modules['DB']->raw2FVArray();
	}
}