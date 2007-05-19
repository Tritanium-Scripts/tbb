<?php

class FuncSmilies {
	public static function getSmileyData($smileyID) {
		$DB = Factory::singleton('DB');

		$DB->query("SELECT * FROM ".TBLPFX."smilies WHERE smileyID='$smileyID'");
		return ($DB->getAffectedRows() == 0) ? FALSE : $DB->fetchArray();
	}
}

?>