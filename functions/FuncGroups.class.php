<?php

class FuncGroups {
	public static function getGroupData($groupID) {
		$DB = Factory::singleton('DB');

		$DB->query("SELECT * FROM ".TBLPFX."groups WHERE groupID='$groupID'");
		return ($DB->getAffectedRows() == 0) ? FALSE : $DB->fetchArray();
	}
}

?>