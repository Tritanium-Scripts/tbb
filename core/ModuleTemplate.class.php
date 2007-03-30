<?php

class ModuleTemplate {
	protected $RequiredModules = array();
	protected $Modules = array();
	protected $ModuleConfig = NULL;

	public function __construct() {
		$ClassName = get_class($this);

		if(file_exists('config/'.$ClassName.'.config.class.php')) {
			include('config/'.$ClassName.'.config.class.php');
			$Temp = $ClassName.'Config';
			$this->ModuleConfig = new $Temp;
		}

		foreach($this->RequiredModules AS $curModule)
			$this->Modules[$curModule] = &Factory::singleton($curModule);
	}

	public function initializeMe() {
	}

	public function executeMe() {
	}

	public function getConfigValue($ConfigName) {
		return $this->ModuleConfig->getValue($ConfigName);
	}
}

?>