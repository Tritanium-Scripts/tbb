<?php

class GlobalsAdmin extends ModuleTemplate {
	protected $requiredModules = array(
		'Language',
		'Navbar',
		'Template'
	);

	public function initializeMe() {
		$this->modules['Language']->addFile('AdminMain');
		$this->modules['Navbar']->addElement($this->modules['Language']->getString('Administration'),INDEXFILE.'?action=AdminIndex&amp;'.MYSID);
		$this->modules['Template']->setGlobalFrame(array($this,'printHeader'),array($this,'printTail'));
	}

	public function printHeader() {
		$navigation = array(
			array(INDEXFILE.'?action=AdminIndex&amp;'.MYSID,$this->modules['Language']->getString('Overview'),'AdminIndex'),
			array(INDEXFILE.'?action=AdminUsers&amp;'.MYSID,$this->modules['Language']->getString('Manage_users'),'AdminUsers'),
			array(INDEXFILE.'?action=AdminProfileFields&amp;'.MYSID,$this->modules['Language']->getString('Manage_profile_fields'),'AdminProfileFields'),
			array(INDEXFILE.'?action=AdminForums&amp;'.MYSID,$this->modules['Language']->getString('Manage_forums'),'AdminForums'),
			array(INDEXFILE.'?action=AdminSmilies&amp;'.MYSID,$this->modules['Language']->getString('Manage_smilies'),'AdminSmilies'),
			array(INDEXFILE.'?action=AdminConfig&amp;'.MYSID,$this->modules['Language']->getString('Boardconfig'),'AdminConfig'),
			array(INDEXFILE.'?action=AdminTemplates&amp;'.MYSID,$this->modules['Language']->getString('Manage_templates'),'AdminTemplates'),
			array(INDEXFILE.'?action=AdminGroups&amp;'.MYSID,$this->modules['Language']->getString('Manage_groups'),'AdminGroups'),
			array(INDEXFILE.'?action=AdminRanks&amp;'.MYSID,$this->modules['Language']->getString('Manage_ranks'),'AdminRanks'),
			array(INDEXFILE.'?action=AdminAvatars&amp;'.MYSID,$this->modules['Language']->getString('Manage_avatars'),'AdminAvatars'),
			array('-','','')
		);

		$this->modules['Template']->assign('navigation',$navigation);

		$this->modules['Template']->display('AdminPageHeader.tpl');
	}

	public function printTail() {
		$this->modules['Template']->display('AdminPageTail.tpl');
	}
}

?>