<?php

class FuncUsers {
	public static function getUserData($UserID) {
		$DB = Factory::singleton('DB');
		if(!preg_match('/^[0-9]{1,}$/si',$UserID))
			$DB->query("SELECT * FROM ".TBLPFX."users WHERE UserNick='$UserID'");
		else
			$DB->query("SELECT * FROM ".TBLPFX."users WHERE UserID='$UserID'");
		return ($DB->getAffectedRows() == 1) ? $DB->fetchArray() : FALSE;
	}

	public static function checkLockStatus($userID) {
		$DB = Factory::singleton('DB');

		$DB->query("SELECT lockStartTimestamp,lockEndTimestamp FROM ".TBLPFX."users_locks WHERE userID='$userID'");
		if($DB->getAffectedRows() == 1) {
			$lockData = $DB->fetchArray();
			if($lockData['lockStartTimestamp'] == $lockData['lockEndTimestamp'] || time() < $lockData['lockEndTimestamp'])
				return TRUE; // Benutzer ist gesperrt
		}

		// Benutzer ist nicht (mehr) gesperrt
		$DB->query("DELETE FROM ".TBLPFX."users_locks WHERE userID='$userID'");
		$DB->query("UPDATE ".TBLPFX."users SET userIsLocked='0' WHERE userID='$userID'");

		return FALSE;
	}

	public static function getUserID($userID) {
		$DB = Factory::singleton('DB');

		if(strlen($userID) > 0) {
			if(!preg_match('/^[0-9]{1,}$/si',$userID))
				$DB->query("SELECT UserID FROM ".TBLPFX."users WHERE UserNick='$userID' LIMIT 1");
			else $DB->query("SELECT UserID FROM ".TBLPFX."users WHERE UserID='$userID' LIMIT 1");

			if($DB->getAffectedRows() == 1) {
				list($userID) = $DB->fetchArray();
				return $userID;
			}
		}

		return FALSE;
	}

	public static function updateLatestUser($userID = '',$userNick = '') {
		$DB = Factory::singleton('DB');
		$Config = Factory::singleton('CONFIG');

		if($userID == '' || $userNick == '') {
			$DB->query('
				SELECT
					"userID",
					"userNick"
				FROM
					'.TBLPFX.'users
				ORDER BY
					"userID" DESC
				LIMIT 1
			');
			list($userID,$userNick) = $DB->fetchArray();
		}

		$Config->updateValue('newest_user_id',$userID,FALSE);
		$Config->updateValue('newest_user_nick',$userNick);
	}

	public static function getUsersCounter() {
		$DB = Factory::singleton('DB');
		$DB->query('SELECT COUNT(*) FROM '.TBLPFX.'users');
		list($usersCounter) = $DB->fetchArray();
		return $usersCounter;
	}

	public static function updateUsersCounter() {
		$DB = Factory::singleton('DB');
		$Config = Factory::singleton('Config');

		$DB->query('BEGIN');
		$Config->updateValue('usersCounter',self::getUsersCounter());
		$DB->query('COMMIT');
	}
}

?>