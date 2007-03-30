<?php

class Factory {
	protected static $Instances = array();

	public static function &singleton($ClassName) {
		if(!isset(self::$Instances[$ClassName])) {
			include('modules/'.$ClassName.'.class.php');
			self::$Instances[$ClassName] = new $ClassName;
			self::$Instances[$ClassName]->initializeMe();
		}

		return self::$Instances[$ClassName];
	}

	public static function &getInstances() {
		return self::$Instances;
	}
}

?>