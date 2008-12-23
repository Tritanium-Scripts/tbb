<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class FuncRanks {
	public static function getRankData($rankID) {
		$DB = Factory::singleton('DB');
        $DB->queryParams('SELECT * FROM '.TBLPFX.'ranks WHERE "rankID"=$1', array(intval($rankID)));
		return ($DB->getAffectedRows() == 1) ? $DB->fetchArray() : FALSE;
	}
}