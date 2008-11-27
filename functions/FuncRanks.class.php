<?php
class FuncRanks {
	public static function getRankData($rankID) {
		$DB = Factory::singleton('DB');
        $DB->queryParams('SELECT * FROM '.TBLPFX.'ranks WHERE "rankID"=$1', array(intval($rankID)));
		return ($DB->getAffectedRows() == 1) ? $DB->fetchArray() : FALSE;
	}
}
?>