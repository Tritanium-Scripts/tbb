<?php

class PageParts extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Config',
		'Language',
		'Template',
		'DB'
	);

	protected $flags = array(
		'inEditProfile'=>FALSE,
		'inAdministration'=>FALSE,
		'inPrivateMessages'=>FALSE
	);

	public function initializeMe() {
		$this->modules['Language']->addFile('PageParts');
	}

	public function setInEditProfile($Value) {
		$this->flags['inEditProfile'] = $Value;
	}

	public function setInAdministration($Value) {
		$this->flags['inAdministration'] = $Value;
	}

	public function setInPrivateMessages($Value) {
		$this->flags['inPrivateMessages'] = $Value;
	}

	public function setFlag($flagName,$value) {
		$this->flags[$flagName] = $value;
	}

	public function printHeader() {
		if($this->modules['Config']->getValue('board_logo') != '') $BoardBanner = '<img src="'.$this->modules['Config']->getValue('board_logo').'" alt="'.$this->modules['Config']->getValue('board_name').'" />';
		else $BoardBanner = $this->modules['Config']->getValue('board_name');

		if($this->modules['Auth']->isLoggedIn() == 1) $WelcomeText = sprintf($this->modules['Language']->getString('welcome_logged_in'),$this->modules['Auth']->getValue('UserNick'),Functions::toTime(time()),INDEXFILE,MYSID);
		else $WelcomeText = sprintf($this->modules['Language']->getString('welcome_not_logged_in'),$this->modules['Config']->getValue('board_name'),INDEXFILE,MYSID);

		$this->modules['Template']->assign(array(
			'BoardBanner'=>$BoardBanner,
			'WelcomeText'=>$WelcomeText
		));

		$this->modules['Template']->display('PageHeader.tpl');

		if($this->flags['inEditProfile'])
			$this->modules['Template']->display('EditProfileHeader.tpl');
		elseif($this->flags['inPrivateMessages']) {
			$this->modules['DB']->query("SELECT FolderName,FolderID FROM ".TBLPFX."pms_folders WHERE UserID='".USERID."' ORDER BY FolderName");
			$HeaderFoldersData = $this->modules['DB']->Raw2Array();

			array_unshift($HeaderFoldersData, // Fuegt an den Anfang die Standardordner hinzu...
				array('FolderID'=>0,'FolderName'=>$this->modules['Language']->getString('Inbox')),
				array('FolderID'=>1,'FolderName'=>$this->modules['Language']->getString('Outbox'))
			);
			reset($HeaderFoldersData);

			$this->modules['Template']->assign('HeaderFoldersData',$HeaderFoldersData);

			$this->modules['Template']->display('PrivateMessagesHeader.tpl');
		}
	}

	public function printPage($TemplateName) {
		$this->printHeader();
		$this->modules['Template']->display($TemplateName);
		$this->printTail();
	}

	public function printMessage($MessageCode,$AdditionalLinks = array()) {
		$this->modules['Language']->addFile('Messages');

		$this->printHeader();
		$this->modules['Template']->assign(array(
			'Flags'=>$this->flags,
			'MessageTitle'=>$this->modules['Language']->getString('message_title_'.$MessageCode),
			'MessageText'=>$this->modules['Language']->getString('message_text_'.$MessageCode),
			'AdditionalLinks'=>$AdditionalLinks
		));
		$this->modules['Template']->display('Message.tpl');
		$this->printTail();
	}

	public function printTail() {
		if($this->flags['inEditProfile'] == TRUE)
			$this->modules['Template']->display('EditProfileTail.tpl');
		elseif($this->flags['inPrivateMessages'] == TRUE)
			$this->modules['Template']->display('PrivateMessagesTail.tpl');

		$this->modules['Template']->display('PageTail.tpl');
	}

	public function printPopupHeader() {
		$this->modules['Template']->display('PopupHeader.tpl');
	}

	public function printPopupTail() {
		$this->modules['Template']->display('PopupTail.tpl');
	}

	public function printPopupPage($TemplateName) {
		$this->printPopupHeader();
		$this->modules['Template']->display($TemplateName);
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