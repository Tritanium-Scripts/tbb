<?php

class AdminIndex extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'AuthAdmin',
		'GlobalsAdmin',
		'Language',
		'PageParts'
	);

	public function executeMe() {
		$this->modules['PageParts']->printPage('AdminIndex.tpl');
	}
}

?>