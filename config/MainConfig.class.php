<?php

class MainConfig {
	private $IndexFile = 'index.php';
	private $ModulesDir = 'modules/';
	private $DefaultAction = 'ForumIndex';
	private $DefaultLanguage = 'de';

	public function getIndexFile() {
		return $this->IndexFile;
	}

	public function getModulesDir() {
		return $this->ModulesDir;
	}

	public function getDefaultAction() {
		return $this->DefaultAction;
	}

	public function getDefaultLanguage() {
		return $this->DefaultLanguage;
	}
}

?>