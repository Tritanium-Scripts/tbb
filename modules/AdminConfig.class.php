<?php

class AdminConfig extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'AuthAdmin',
		'DB',
		'GlobalsAdmin',
		'Language',
		'Navbar',
		'PageParts',
		'Template'
	);

	public function executeMe() {
	}
}

?>