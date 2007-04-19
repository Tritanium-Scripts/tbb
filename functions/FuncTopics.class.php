<?php

class FuncTopics {
	public static function getTopicData($TopicID) {
		$DB = Factory::singleton('DB');
		$DB->query("SELECT * FROM ".TBLPFX."topics WHERE TopicID='$TopicID'");
		return ($DB->getAffectedRows() == 1) ? $DB->fetchArray() : FALSE;
	}
}

?>