<?php

class PageParts extends ModuleTemplate {
	protected $RequiredModules = array(
		'Auth',
		'Config',
		'Language',
		'Template',
		'DB'
	);

	protected $Flags = array(
		'InEditProfile'=>FALSE,
		'InAdministration'=>FALSE,
		'InPrivateMessages'=>FALSE
	);

	public function initializeMe() {
		$this->Modules['Language']->addFile('PageParts');
	}

	public function setInEditProfile($Value) {
		$this->Flags['InEditProfile'] = $Value;
	}

	public function setInAdministration($Value) {
		$this->Flags['InAdministration'] = $Value;
	}

	public function setInPrivateMessages($Value) {
		$this->Flags['InPrivateMessages'] = $Value;
	}

	public function setFlag($FlagName,$Value) {
		$this->Flags[$FlagName] = $Value;
	}

	public function printHeader() {
		if($this->Modules['Config']->getValue('board_logo') != '') $BoardBanner = '<img src="'.$this->Modules['Config']->getValue('board_logo').'" alt="'.$this->Modules['Config']->getValue('board_name').'" />';
		else $BoardBanner = $this->Modules['Config']->getValue('board_name');

		if($this->Modules['Auth']->isLoggedIn() == 1) $WelcomeText = sprintf($this->Modules['Language']->getString('welcome_logged_in'),$this->Modules['Auth']->getValue('UserNick'),Functions::toTime(time()),INDEXFILE,MYSID);
		else $WelcomeText = sprintf($this->Modules['Language']->getString('welcome_not_logged_in'),$this->Modules['Config']->getValue('board_name'),INDEXFILE,MYSID);

		$this->Modules['Template']->assign(array(
			'BoardBanner'=>$BoardBanner,
			'WelcomeText'=>$WelcomeText
		));

		$this->Modules['Template']->display('PageHeader.tpl');

		if($this->Flags['InEditProfile'])
			$this->Modules['Template']->display('EditProfileHeader.tpl');
		elseif($this->Flags['InPrivateMessages']) {
			$this->Modules['DB']->query("SELECT FolderName,FolderID FROM ".TBLPFX."pms_folders WHERE UserID='".USERID."' ORDER BY FolderName");
			$HeaderFoldersData = $this->Modules['DB']->Raw2Array();

			array_unshift($HeaderFoldersData, // Fuegt an den Anfang die Standardordner hinzu...
				array('FolderID'=>0,'FolderName'=>$this->Modules['Language']->getString('Inbox')),
				array('FolderID'=>1,'FolderName'=>$this->Modules['Language']->getString('Outbox'))
			);
			reset($HeaderFoldersData);

			$this->Modules['Template']->assign('HeaderFoldersData',$HeaderFoldersData);

			$this->Modules['Template']->display('PrivateMessagesHeader.tpl');
		}
	}

	public function printPage($TemplateName) {
		$this->printHeader();
		$this->Modules['Template']->display($TemplateName);
		$this->printTail();
	}

	public function printMessage($MessageCode,$AdditionalLinks = array()) {
		$this->Modules['Language']->addFile('Messages');

		$this->printHeader();
		$this->Modules['Template']->assign(array(
			'Flags'=>$this->Flags,
			'MessageTitle'=>$this->Modules['Language']->getString('message_title_'.$MessageCode),
			'MessageText'=>$this->Modules['Language']->getString('message_text_'.$MessageCode),
			'AdditionalLinks'=>$AdditionalLinks
		));
		$this->Modules['Template']->display('Message.tpl');
		$this->printTail();
	}

	public function printTail() {
		if($this->Flags['InEditProfile'] == TRUE)
			$this->Modules['Template']->display('EditProfileTail.tpl');
		elseif($this->Flags['InPrivateMessages'] == TRUE)
			$this->Modules['Template']->display('PrivateMessagesTail.tpl');

		$this->Modules['Template']->display('PageTail.tpl');
	}

	public function printPopupHeader() {
		$this->Modules['Template']->display('PopupHeader.tpl');
	}

	public function printPopupTail() {
		$this->Modules['Template']->display('PopupTail.tpl');
	}

	public function printPopupPage($TemplateName) {
		$this->printPopupHeader();
		$this->Modules['Template']->display($TemplateName);
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