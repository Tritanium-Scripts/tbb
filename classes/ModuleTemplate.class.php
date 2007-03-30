<?php

class ModuleTemplate {
	protected $Smarty = NULL;
	protected $Config = NULL;
	protected $Language = NULL;
	protected $DB = NULL;
	protected $RequiredLanguageFiles = array();

	final function __construct(&$Smarty,&$Language,&$DB) {
		$this->Smarty = &$Smarty;
		$this->Language = &$Language;
		$this->DB = &$DB;

		foreach($this->RequiredLanguageFiles AS $curLanguageFile)
			$this->Language->addFile($curLanguageFile);
	}

	protected function printPageHeader() {
		$this->Smarty->display('pageheader.tpl');
	}

	protected function printPageTail() {
		$this->Smarty->display('pagetail.tpl');
	}

	public function executeStuff() {
	}
}

?>