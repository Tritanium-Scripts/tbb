<?php

class Globals extends ModuleTemplate {
	protected $requiredModules = array(
		'Config',
		'Navbar',
		'Session'
	);

	public function initializeMe() {
		$this->modules['Navbar']->addElement($this->modules['Config']->getValue('board_name'),INDEXFILE.'?'.MYSID);

		if(isset($_GET['t'])) {
			$_GET['action'] = 'ViewTopic';
			$_GET['topicID'] = $_GET['t'];
		}
		if(isset($_GET['p'])) {
			$_GET['action'] = 'ViewTopic';
			$_GET['postID'] = $_GET['p'];
		}
	}
}

?>