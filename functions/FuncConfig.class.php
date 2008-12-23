<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
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