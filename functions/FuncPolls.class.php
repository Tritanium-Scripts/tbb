<?php

class FuncPolls {
	public static function getPollData($pollID) {
		$DB = Factory::singleton('DB');
		$DB->query("SELECT * FROM ".TBLPFX."polls WHERE pollID='$pollID'");
		return ($DB->getAffectedRows() == 1 ? $DB->fetchArray() : FALSE);
	}
}

?>