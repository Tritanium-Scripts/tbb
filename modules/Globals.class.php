<?php

class Globals extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Config',
		'Language',
		'Navbar',
		'Session',
		'Template'
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

		$this->modules['Template']->setGlobalFrame(array($this,'printHeader'),array($this,'printTail'));
	}

	public function printHeader() {
		if($this->modules['Config']->getValue('board_logo') != '') $boardBanner = '<img src="'.$this->modules['Config']->getValue('board_logo').'" alt="'.$this->modules['Config']->getValue('board_name').'" />';
		else $boardBanner = $this->modules['Config']->getValue('board_name');

		if($this->modules['Auth']->isLoggedIn() == 1) $welcomeText = sprintf($this->modules['Language']->getString('welcome_logged_in'),$this->modules['Auth']->getValue('UserNick'),Functions::toTime(time()),INDEXFILE,MYSID);
		else $welcomeText = sprintf($this->modules['Language']->getString('welcome_not_logged_in'),$this->modules['Config']->getValue('board_name'),INDEXFILE,MYSID);

		$this->modules['Template']->assign(array(
			'boardBanner'=>$boardBanner,
			'welcomeText'=>$welcomeText
		));

		$this->modules['Template']->display('PageHeader.tpl');
	}

	public function printTail() {
		$this->modules['Template']->display('PageTail.tpl');
	}
}

?>