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
}

?>