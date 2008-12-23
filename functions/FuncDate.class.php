<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class FuncDate {
	public static function splitTime($seconds) {
		$array = array();

		$array['days'] = floor($seconds/86400);
		$seconds -= $array['days']*86400;
		$array['hours'] = floor($seconds/3600);
		$seconds -= $array['hours']*3600;
		$array['minutes'] = ceil($seconds/60);

		return $array;
	}
}