<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class FuncForums {
	public static function getForumData($forumID) {
		$DB = Factory::singleton('DB');
        $DB->queryParams('SELECT * FROM '.TBLPFX.'forums WHERE "forumID"=$1', array($forumID));
		return ($DB->getAffectedRows() == 1) ? $DB->fetchArray() : FALSE;
	}

	function updateLastPost($forumID) {
		$DB = Factory::singleton('DB');

		$DB->queryParams('SELECT "postID" FROM '.TBLPFX.'posts WHERE "forumID"=$1 ORDER BY "postID" DESC LIMIT 1',array($forumID));
		($DB->numRows() == 0) ? $postID = 0 : list($postID) = $DB->fetchArray();

		$DB->queryParams('UPDATE '.TBLPFX.'forums SET "forumLastPostID"=$1 WHERE "forumID"=$2',array($postID,$forumID));
	}
}