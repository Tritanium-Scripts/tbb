<?php

class PageParts extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Config',
		'DB',
		'Language',
		'Navbar',
		'Template'
	);

	protected $flags = array(
		'inEditProfile'=>FALSE,
		'inAdministration'=>FALSE,
		'inPrivateMessages'=>FALSE,
		'inAdministration'=>FALSE
	);

	public function initializeMe() {
		$this->modules['Language']->addFile('PageParts');
	}

	public function setInEditProfile($value) {
		$this->flags['inEditProfile'] = $value;
	}

	public function setInAdministration($value) {
		$this->flags['inAdministration'] = $value;
	}

	public function setInPrivateMessages($value) {
		$this->flags['inPrivateMessages'] = $value;
	}

	public function setFlag($flagName,$value) {
		$this->flags[$flagName] = $value;
	}

	public function printHeader() {
		if($this->flags['inAdministration']) {
			$navigation = array(
				array(INDEXFILE.'?action=AdminIndex&amp;'.MYSID,$this->modules['Language']->getString('Overview'),'AdminIndex'),
				array(INDEXFILE.'?action=AdminUsers&amp;'.MYSID,$this->modules['Language']->getString('Manage_users'),'AdminUsers'),
				array(INDEXFILE.'?action=AdminProfile&amp;'.MYSID,$this->modules['Language']->getString('Manage_profile_fields'),'AdminProfile'),
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
		} else {
			if($this->modules['Config']->getValue('board_logo') != '') $boardBanner = '<img src="'.$this->modules['Config']->getValue('board_logo').'" alt="'.$this->modules['Config']->getValue('board_name').'" />';
			else $boardBanner = $this->modules['Config']->getValue('board_name');

			if($this->modules['Auth']->isLoggedIn() == 1) $welcomeText = sprintf($this->modules['Language']->getString('welcome_logged_in'),$this->modules['Auth']->getValue('UserNick'),Functions::toTime(time()),INDEXFILE,MYSID);
			else $welcomeText = sprintf($this->modules['Language']->getString('welcome_not_logged_in'),$this->modules['Config']->getValue('board_name'),INDEXFILE,MYSID);

			$this->modules['Template']->assign(array(
				'boardBanner'=>$boardBanner,
				'welcomeText'=>$welcomeText
			));

			$this->modules['Template']->display('PageHeader.tpl');

			if($this->flags['inEditProfile'])
				$this->modules['Template']->display('EditProfileHeader.tpl');
			elseif($this->flags['inPrivateMessages']) {
				$this->modules['DB']->query("SELECT folderName,folderID FROM ".TBLPFX."pms_folders WHERE userID='".USERID."' ORDER BY folderName");
				$headerFoldersData = $this->modules['DB']->raw2Array();

				array_unshift($headerFoldersData, // Fuegt an den Anfang die Standardordner hinzu...
					array('folderID'=>0,'folderName'=>$this->modules['Language']->getString('Inbox')),
					array('folderID'=>1,'folderName'=>$this->modules['Language']->getString('Outbox'))
				);
				reset($headerFoldersData);

				$this->modules['Template']->assign('headerFoldersData',$headerFoldersData);

				$this->modules['Template']->display('PrivateMessagesHeader.tpl');
			}
		}
	}

	public function printPage($templateName) {
		$this->printHeader();
		$this->modules['Template']->display($templateName);
		$this->printTail();
	}

	public function printMessage($message,$additionalLinks = array(),$pageInPage = FALSE,$inPopup = FALSE) {
		$this->modules['Language']->addFile('Messages');

		$this->modules['Navbar']->addElement((is_array($message) ? $message[0] : $this->modules['Language']->getString('message_title_'.$message)),'');

		$this->modules['Template']->assign(array(
			'flags'=>$this->flags,
			'messageTitle'=>(is_array($message) ? $message[0] : $this->modules['Language']->getString('message_title_'.$message)),
			'messageText'=>(is_array($message) ? $message[1] : $this->modules['Language']->getString('message_text_'.$message)),
			'additionalLinks'=>$additionalLinks,
			'pageInPage'=>$pageInPage
		));

		if($inPopup) $this->printPopupPage('Message.tpl');
		else $this->printPage('Message.tpl');
	}

	public function printTail() {
		if($this->flags['inAdministration']) {
			$this->modules['Template']->display('AdminPageTail.tpl');
		} else {
			if($this->flags['inEditProfile'] == TRUE)
				$this->modules['Template']->display('EditProfileTail.tpl');
			elseif($this->flags['inPrivateMessages'] == TRUE)
				$this->modules['Template']->display('PrivateMessagesTail.tpl');
			elseif($this->flags['inAdministration']) {
				$this->modules['Template']->display('AdminPageTail.tpl');
			}

			$this->modules['Template']->display('PageTail.tpl');
		}
	}

	public function printPopupHeader() {
		$this->modules['Template']->display('PopupHeader.tpl');
	}

	public function printPopupTail() {
		$this->modules['Template']->display('PopupTail.tpl');
	}

	public function printPopupPage($templateName) {
		$this->printPopupHeader();
		$this->modules['Template']->display($templateName);
		$this->printPopupTail();
	}

	public function printStdHeader() {
		$this->printHeader();
	}

	public function printStdTail() {
		$this->printTail();
	}
}

?>