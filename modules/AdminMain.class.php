<?php

class AdminMain extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'AuthAdmin',
		'GlobalsAdmin',
		'Language',
		'PageParts'
	);

	public function executeMe() {
		$this->modules['Language']->addFile('AdminMain');
		$this->modules['PageParts']->printPage('AdminMain.tpl');
	}
}

?>