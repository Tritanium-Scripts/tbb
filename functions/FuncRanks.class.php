<?php

class FuncRanks extends ModuleTemplate {
	public static function getRankData($rankID) {
		$DB = Factory::singleton('DB');
		$DB->query("SELECT * FROM ".TBLPFX."ranks WHERE rankID='".intval($rankID)."'");
		return ($DB->getAffectedRows() == 1) ? $DB->fetchArray() : FALSE;
	}
}

?>