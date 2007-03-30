<?php

class Logout extends ModuleTemplate {
	protected $RequiredModules = array(
		'Auth'
	);

	public function executeMe() {
		$this->Modules['Auth']->destroySessionData();
		Functions::myHeader(INDEXFILE);
	}
}

?>