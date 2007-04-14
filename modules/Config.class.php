<?php

class Config extends ModuleTemplate {
	protected $requiredModules = array(
		'Cache',
		'DB'
	);
	protected $myConfig = array();

	public function initializeMe() {
		$this->myConfig = $this->modules['Cache']->getConfig();
	}

	public function getValue($configName) {
		return (isset($this->myConfig[$configName]) == FALSE) ? FALSE : $this->myConfig[$configName];
	}

	public function updateValue($configName,$configValue,$updateCache = TRUE) {
		$this->modules['DB']->query("UPDATE ".TBLPFX."config SET configValue='$configValue' WHERE configName='$configName' LIMIT 1");
		if($updateCache) $this->modules['Cache']->setConfig();
	}
}

?>