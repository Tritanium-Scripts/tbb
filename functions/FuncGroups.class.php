<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class FuncGroups {
	public static function getGroupData($groupID) {
		$DB = Factory::singleton('DB');

        $DB->queryParams('SELECT * FROM '.TBLPFX.'groups WHERE "groupID"=$1', array($groupID));
		return ($DB->getAffectedRows() == 0) ? FALSE : $DB->fetchArray();
	}
}