<?php

class FuncTopics {
	public static function getTopicData($TopicID) {
		$DB = Factory::singleton('DB');
		$DB->query("SELECT * FROM ".TBLPFX."topics WHERE TopicID='$TopicID'");
		return ($DB->getAffectedRows() == 1) ? $DB->fetchArray() : FALSE;
	}

	function updateLastPost($topicID) {
		$DB = Factory::singleton('DB');

		$DB->queryParams('SELECT "postID" FROM '.TBLPFX.'posts WHERE "topicID"=$1 ORDER BY "postID" DESC LIMIT 1',array($topicID));
		($DB->numRows() == 0) ? $postID = 0 : list($postID) = $DB->fetchArray();

		$DB->queryParams('UPDATE '.TBLPFX.'topics SET "topicLastPostID"=$1 WHERE "topicID"=$2',array($postID,$topicID));
	}
}

?>