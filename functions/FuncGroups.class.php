<?php
class FuncGroups {
	public static function getGroupData($groupID) {
		$DB = Factory::singleton('DB');

        $DB->queryParams('SELECT * FROM '.TBLPFX.'groups WHERE "groupID"=$1', array($groupID));
		return ($DB->getAffectedRows() == 0) ? FALSE : $DB->fetchArray();
	}
}
?>