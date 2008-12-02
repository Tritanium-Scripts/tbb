<?php
class AdminIndex extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'AuthAdmin',
		'GlobalsAdmin',
		'Template',
		'Language'
	);

	public function executeMe() {
		$this->modules['Template']->printPage('AdminIndex.tpl');
	}
}