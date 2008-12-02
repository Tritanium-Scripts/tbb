<?php
class FuncSmilies {
	public static function getSmileyData($smileyID) {
		$DB = Factory::singleton('DB');

        $DB->queryParams('SELECT * FROM '.TBLPFX.'smilies WHERE "smileyID"=$1', array($smileyID));
		return ($DB->getAffectedRows() == 0) ? FALSE : $DB->fetchArray();
	}
}