<?php

class FuncUsers {
	public static function getUserData($userID) {
		$DB = Factory::singleton('DB');
		if(!preg_match('/^[0-9]{1,}$/si',$userID))
            $DB->queryParams('SELECT * FROM '.TBLPFX.'users WHERE "userNick"=$1', array($userID));
		else
            $DB->queryParams('SELECT * FROM '.TBLPFX.'users WHERE "userID"=$1', array($userID));
		return ($DB->getAffectedRows() == 1) ? $DB->fetchArray() : FALSE;
	}

	public static function checkLockStatus($userID) {
		$DB = Factory::singleton('DB');

        $DB->queryParams('SELECT "lockStartTimestamp", "lockEndTimestamp" FROM '.TBLPFX.'users_locks WHERE "userID"=$1', array($userID));
		if($DB->getAffectedRows() == 1) {
			$lockData = $DB->fetchArray();
			if($lockData['lockStartTimestamp'] == $lockData['lockEndTimestamp'] || time() < $lockData['lockEndTimestamp'])
				return TRUE; // Benutzer ist gesperrt
		}

		// Benutzer ist nicht (mehr) gesperrt
        $DB->queryParams('DELETE FROM '.TBLPFX.'users_locks WHERE "userID"=$1', array($userID));
        $DB->queryParams('UPDATE '.TBLPFX.'users SET "userIsLocked"=0 WHERE "userID"=$1', array($userID));

		return FALSE;
	}

	public static function getUserID($userID) {
		$DB = Factory::singleton('DB');

		if(Functions::strlen($userID) > 0) {
			if(!preg_match('/^[0-9]{1,}$/si',$userID))
                $DB->queryParams('SELECT "userID" FROM '.TBLPFX.'users WHERE "userNick"=$1 LIMIT 1', array($userID));
			else $DB->queryParams('SELECT "userID" FROM '.TBLPFX.'users WHERE "userID"=$1 LIMIT 1', array($userID));

			if($DB->getAffectedRows() == 1) {
				list($userID) = $DB->fetchArray();
				return $userID;
			}
		}

		return FALSE;
	}

	public static function updateLatestUser($userID = '',$userNick = '') {
		$DB = Factory::singleton('DB');
		$Config = Factory::singleton('Config');

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
		$Cache = Factory::singleton('Cache');

		$DB->query('BEGIN');
		$Config->updateValue('usersCounter',self::getUsersCounter());
		$DB->query('COMMIT');
		$Cache->setConfig();
	}
}

?>