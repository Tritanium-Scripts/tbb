<?php
class FuncConfig {
	public static function updateLatestUser($userID = '',$userNick = '') {
		if($userID == '' || $userNick == '') {
			$DB = Factory::singleton('DB');
			$DB->query('SELECT "userID", "userNick" FROM '.TBLPFX.'users ORDER BY "userID" DESC LIMIT 1');
			list($userID,$userNick) = $DB->fetchArray();
		}

		$Config = Factory::singleton('Config');
		$Config->updateValue('newest_user_id',$userID,FALSE);
		$Config->updateValue('newest_user_nick',$userNick);
	}
}
?>