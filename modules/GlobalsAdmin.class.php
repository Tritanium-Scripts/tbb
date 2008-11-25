<?php

class GlobalsAdmin extends ModuleTemplate {
	protected $requiredModules = array(
		'Language',
		'Navbar',
		'Template'
	);

	public function initializeMe() {
		$this->modules['Language']->addFile('AdminMain');
		$this->modules['Navbar']->addElement($this->modules['Language']->getString('administration'),INDEXFILE.'?action=AdminIndex&amp;'.MYSID);
		$this->modules['Template']->setGlobalFrame(array($this,'printHeader'),array($this,'printTail'));
	}

	public function printHeader() {
		$navigation = array(
			array(INDEXFILE.'?action=AdminIndex&amp;'.MYSID,$this->modules['Language']->getString('overview'),'AdminIndex'),
			array(INDEXFILE.'?action=AdminUsers&amp;'.MYSID,$this->modules['Language']->getString('manage_users'),'AdminUsers'),
			array(INDEXFILE.'?action=AdminProfileFields&amp;'.MYSID,$this->modules['Language']->getString('manage_profile_fields'),'AdminProfileFields'),
			array(INDEXFILE.'?action=AdminForums&amp;'.MYSID,$this->modules['Language']->getString('manage_forums'),'AdminForums'),
			array(INDEXFILE.'?action=AdminSmilies&amp;'.MYSID,$this->modules['Language']->getString('manage_smilies'),'AdminSmilies'),
			array(INDEXFILE.'?action=AdminConfig&amp;'.MYSID,$this->modules['Language']->getString('boardconfig'),'AdminConfig'),
			array(INDEXFILE.'?action=AdminTemplates&amp;'.MYSID,$this->modules['Language']->getString('manage_templates'),'AdminTemplates'),
			array(INDEXFILE.'?action=AdminGroups&amp;'.MYSID,$this->modules['Language']->getString('manage_groups'),'AdminGroups'),
			array(INDEXFILE.'?action=AdminRanks&amp;'.MYSID,$this->modules['Language']->getString('manage_ranks'),'AdminRanks'),
			array(INDEXFILE.'?action=AdminAvatars&amp;'.MYSID,$this->modules['Language']->getString('manage_avatars'),'AdminAvatars'),
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