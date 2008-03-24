<?php

class ModuleTemplate {
	protected $requiredModules = array();
	protected $modules = array();
	protected $moduleConfig = NULL;

	public function __construct() {
		$className = get_class($this);

		if(file_exists('config/'.$className.'.config.class.php')) {
			include('config/'.$className.'.config.class.php');
			$temp = $className.'Config';
			$this->moduleConfig = new $temp;
		}

		foreach($this->requiredModules AS $curModule)
			$this->modules[$curModule] = &Factory::singleton($curModule);
	}

	public function initializeMe() {
	}

	public function executeMe() {
	}

	public function getConfigValue($configName) {
		return $this->moduleConfig->getValue($configName);
	}

	public function getC($configName) {
		return $this->moduleConfig->getValue($configName);
	}
}

?>