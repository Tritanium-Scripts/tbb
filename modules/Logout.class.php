<?php
class Logout extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth'
	);

	public function executeMe() {
		$this->modules['Auth']->destroySessionData();
		Functions::myHeader(INDEXFILE);
	}
}