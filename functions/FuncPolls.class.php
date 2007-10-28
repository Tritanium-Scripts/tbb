<?php

class FuncPolls {
	public static function getPollData($pollID) {
		$DB = Factory::singleton('DB');
        $DB->queryParams('SELECT * FROM '.TBLPFX.'polls WHERE "pollID"=$1', array($pollID));
		return ($DB->getAffectedRows() == 1 ? $DB->fetchArray() : FALSE);
	}
}

?>