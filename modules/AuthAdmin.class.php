<?php
class AuthAdmin extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth'
	);

	public function initializeMe() {
		if($this->modules['Auth']->getValue('userIsAdmin') != 1)
			die('Access denied: Administrator rights required');
	}
}
?>