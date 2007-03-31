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
		if($this->modules['Config']->getValue('board_logo') != '') $boardBanner = '<img src="'.$this->modules['Config']->getValue('board_logo').'" alt="'.$this->modules['Config']->getValue('board_name').'" />';
		else $boardBanner = $this->modules['Config']->getValue('board_name');

		if($this->modules['Auth']->isLoggedIn() == 1) $welcomeText = sprintf($this->modules['Language']->getString('welcome_logged_in'),$this->modules['Auth']->getValue('UserNick'),Functions::toTime(time()),INDEXFILE,MYSID);
		else $welcomeText = sprintf($this->modules['Language']->getString('welcome_not_logged_in'),$this->modules['Config']->getValue('board_name'),INDEXFILE,MYSID);

		$this->modules['Template']->assign(array(
			'BoardBanner'=>$boardBanner,
			'WelcomeText'=>$welcomeText
		));

		$this->modules['Template']->display('PageHeader.tpl');

		if($this->flags['inEditProfile'])
			$this->modules['Template']->display('EditProfileHeader.tpl');
		elseif($this->flags['inPrivateMessages']) {
			$this->modules['DB']->query("SELECT FolderName,FolderID FROM ".TBLPFX."pms_folders WHERE UserID='".USERID."' ORDER BY FolderName");
			$headerFoldersData = $this->modules['DB']->raw2Array();

			array_unshift($headerFoldersData, // Fuegt an den Anfang die Standardordner hinzu...
				array('FolderID'=>0,'FolderName'=>$this->modules['Language']->getString('Inbox')),
				array('FolderID'=>1,'FolderName'=>$this->modules['Language']->getString('Outbox'))
			);
			reset($headerFoldersData);

			$this->modules['Template']->assign('HeaderFoldersData',$headerFoldersData);

			$this->modules['Template']->display('PrivateMessagesHeader.tpl');
		}
	}

	public function printPage($templateName) {
		$this->printHeader();
		$this->modules['Template']->display($templateName);
		$this->printTail();
	}

	public function printMessage($messageCode,$additionalLinks = array()) {
		$this->modules['Language']->addFile('Messages');

		$this->printHeader();
		$this->modules['Template']->assign(array(
			'Flags'=>$this->flags,
			'MessageTitle'=>$this->modules['Language']->getString('message_title_'.$messageCode),
			'MessageText'=>$this->modules['Language']->getString('message_text_'.$messageCode),
			'AdditionalLinks'=>$additionalLinks
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