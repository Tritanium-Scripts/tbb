<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class Factory {
	protected static $instances = array();

	public static function &singleton($className) {
		if(!isset(self::$instances[$className])) {
			// TODO: Check if module exists
			include('modules/'.$className.'.class.php');
			self::$instances[$className] = new $className;
			self::$instances[$className]->initializeMe();
		}

		return self::$instances[$className];
	}

	public static function &getInstances() {
		return self::$instances;
	}

	public static function moduleExists($moduleName) {
		return file_exists('modules/'.$moduleName.'.class.php');
	}

	public static function moduleLoaded($moduleName) {
		return isset(self::$instances[$modulesName]);
	}
}