<?php

class FuncForums {
	public static function getForumData($ForumID) {
		$DB = Factory::singleton('DB');
		$DB->query("SELECT * FROM ".TBLPFX."forums WHERE ForumID='$ForumID'");
		return ($DB->getAffectedRows() == 1) ? $DB->fetchArray() : FALSE;
	}
}

?>