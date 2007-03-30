<?php

class Language extends ModuleTemplate {
	protected $Strings = array();
	protected $LanguageDir = '';
	protected $LoadedFiles = array();
	protected $LanguageCode = '';

	public function initializeMe() {
		$this->LanguageCode = $this->getConfigValue('DefaultLanguageCode');
		$this->LanguageDir = 'languages/'.$this->getConfigValue('DefaultLanguageCode').'/';
		foreach($this->getConfigValue('AutoloadFiles') AS $curFile)
			$this->addFile($curFile);


	}

	public function setLanguageCode($newLanguageCode) {
		$this->LanguageCode = $newLanguageCode;
	}

	public function getLanguageCode() {
		return $this->LanguageCode;
	}

	public function getLC() {
		return $this->LanguageCode;
	}

	public function getLD() {
		return $this->LanguageDir;
	}

	public function addFile($FileName) {
		if(!isset($this->LoadedFiles[$FileName])) {
			if(file_exists($this->LanguageDir.$FileName.'.language') == FALSE) die('Language file "'.$this->LanguageDir.$FileName.'.language" does not exist');

			foreach(explode("\n",file_get_contents($this->LanguageDir.$FileName.'.language')) AS $curLine) {
				preg_match('/^([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)[ ]*=[ ]*"(.*)"$/',rtrim($curLine),$Matches);

				if(count($Matches) == 3)
					$this->Strings[$Matches[1]] = $Matches[2];
			}

			$this->LoadedFiles[$FileName] = TRUE;
		}
	}

	public function resetStrings() {
		$this->Strings = array();
	}

	public function getString($Index) {
		if(isset($this->Strings[$Index]) == FALSE) {
			trigger_error('Language string "'.$Index.'" does not exist');
			return FALSE;
		}
		return $this->Strings[$Index];
	}

	public function setLanguageDir($newLanguageDir) {
		$this->LanguageDir = $newLanguageDir;
	}
}

?>