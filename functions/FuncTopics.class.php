<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class FuncTopics {
	public static function getTopicData($topicID) {
		$DB = Factory::singleton('DB');
        $DB->queryParams('SELECT * FROM '.TBLPFX.'topics WHERE "topicID"=$1', array($topicID));
		return ($DB->getAffectedRows() == 1) ? $DB->fetchArray() : FALSE;
	}

	function updateLastPost($topicID) {
		$DB = Factory::singleton('DB');

		$DB->queryParams('SELECT "postID" FROM '.TBLPFX.'posts WHERE "topicID"=$1 ORDER BY "postID" DESC LIMIT 1',array($topicID));
		($DB->numRows() == 0) ? $postID = 0 : list($postID) = $DB->fetchArray();

		$DB->queryParams('UPDATE '.TBLPFX.'topics SET "topicLastPostID"=$1 WHERE "topicID"=$2',array($postID,$topicID));
	}
}