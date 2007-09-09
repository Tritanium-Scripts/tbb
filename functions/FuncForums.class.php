<?php

class FuncForums {
	public static function getForumData($ForumID) {
		$DB = Factory::singleton('DB');
		$DB->query("SELECT * FROM ".TBLPFX."forums WHERE ForumID='$ForumID'");
		return ($DB->getAffectedRows() == 1) ? $DB->fetchArray() : FALSE;
	}

	function updateLastPost($forumID) {
		$DB = Factory::singleton('DB');

		$DB->queryParams('SELECT "postID" FROM '.TBLPFX.'posts WHERE "forumID"=$1 ORDER BY "postID" DESC LIMIT 1',array($forumID));
		($DB->numRows() == 0) ? $postID = 0 : list($postID) = $DB->fetchArray();

		$DB->queryParams('UPDATE '.TBLPFX.'forums SET "forumLastPostID"=$1 WHERE "forumID"=$2',array($postID,$forumID));
	}
}

?>