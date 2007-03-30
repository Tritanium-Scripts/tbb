<?php

class Config extends ModuleTemplate {
	protected $RequiredModules = array(
		'Cache',
		'DB'
	);
	protected $MyConfig = array();

	public function initializeMe() {
		$this->MyConfig = $this->Modules['Cache']->getConfig();
		$this->Modules['Cache']->setPPicsData();
	}

	public function getValue($ConfigName) {
		return (isset($this->MyConfig[$ConfigName]) == FALSE) ? FALSE : $this->MyConfig[$ConfigName];
	}
}

?>