<?php

class Globals extends ModuleTemplate {
	protected $requiredModules = array(
		'Config',
		'Navbar',
		'Session'
	);

	public function initializeMe() {
		$this->modules['Navbar']->addElement($this->modules['Config']->getValue('board_name'),INDEXFILE.'?'.MYSID);
	}
}

?>