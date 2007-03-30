<?php

class Engine {
	private $Action = '';
	private $MainConfig = NULL;
	private $Smarty = NULL;
	private $Language = NULL;
	private $DB = NULL;

	function __construct($MainConfig,&$Smarty,&$Language,&$DB) {
		$this->MainConfig = $MainConfig;
		$this->Smarty = &$Smarty;
		$this->Language = &$Language;
		$this->DB = &$DB;
	}

	public function setAction($newAction) {
		$this->Action = $newAction;
		return TRUE;
	}
	public function getAction() {
		return $this->Action;
	}

	public function setModulesDir($newModulesDir) {
		$this->ModulesDir = $newModulesDir;
		return TRUE;
	}
	public function getModulesDir() {
		return $this->ModulesDir;
	}

	public function getMainConfig() {
		return $this->MainConfig;
	}

	public function doAction() {
		$ClassFile = $this->MainConfig->getModulesDir().$this->Action.'.class.php';
		if(file_exists($ClassFile) == FALSE) throw new Exception('Class-File does not exists; File: '.__FILE__.'; Line: '.__LINE__);

		include($ClassFile);

		$Module = new Module($this->Smarty,$this->Language,$this->DB);
		$Module->executeStuff();
	}
}

?>