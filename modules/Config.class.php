<?php

class Config extends ModuleTemplate {
	protected $requiredModules = array(
		'Cache',
		'DB'
	);
	protected $myConfig = array();

	public function initializeMe() {
		$this->myConfig = $this->modules['Cache']->getConfig();
		$this->modules['Cache']->setPPicsData();
	}

	public function getValue($configName) {
		return (isset($this->myConfig[$configName]) == FALSE) ? FALSE : $this->myConfig[$configName];
	}
}

?>