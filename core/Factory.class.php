<?php

class Factory {
	protected static $instances = array();

	public static function &singleton($className) {
		if(!isset(self::$instances[$className])) {
			include('modules/'.$className.'.class.php');
			self::$instances[$className] = new $className;
			self::$instances[$className]->initializeMe();
		}

		return self::$instances[$className];
	}

	public static function &getInstances() {
		return self::$instances;
	}
}

?>