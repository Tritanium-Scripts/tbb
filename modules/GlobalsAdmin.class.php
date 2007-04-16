<?php

class GlobalsAdmin extends ModuleTemplate {
	protected $requiredModules = array(
		'Language',
		'Navbar',
		'PageParts'
	);

	public function initializeMe() {
		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Administration'),INDEXFILE.'?action=AdminIndex&amp;'.MYSID);
		$this->modules['PageParts']->setFlag('inAdministration',TRUE);
	}
}

?>