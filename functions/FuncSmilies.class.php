<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class FuncSmilies {
	public static function getSmileyData($smileyID) {
		$DB = Factory::singleton('DB');

        $DB->queryParams('SELECT * FROM '.TBLPFX.'smilies WHERE "smileyID"=$1', array($smileyID));
		return ($DB->getAffectedRows() == 0) ? FALSE : $DB->fetchArray();
	}
}