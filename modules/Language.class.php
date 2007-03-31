<?php

class Language extends ModuleTemplate {
	protected $strings = array();
	protected $languageDir = '';
	protected $loadedFiles = array();
	protected $languageCode = '';

	public function initializeMe() {
		$this->languageCode = $this->getConfigValue('defaultLanguageCode');
		$this->languageDir = 'languages/'.$this->getConfigValue('defaultLanguageCode').'/';
		foreach($this->getConfigValue('autoloadFiles') AS $curFile)
			$this->addFile($curFile);
	}

	public function setLanguageCode($newLanguageCode) {
		$this->languageCode = $newLanguageCode;
	}

	public function getLanguageCode() {
		return $this->languageCode;
	}

	public function getLC() {
		return $this->languageCode;
	}

	public function getLD() {
		return $this->languageDir;
	}

	public function addFile($FileName) {
		if(!isset($this->loadedFiles[$FileName])) {
			if(file_exists($this->languageDir.$FileName.'.language') == FALSE) die('Language file "'.$this->languageDir.$FileName.'.language" does not exist');

			foreach(explode("\n",file_get_contents($this->languageDir.$FileName.'.language')) AS $curLine) {
				preg_match('/^([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)[ ]*=[ ]*"(.*)"$/',rtrim($curLine),$Matches);

				if(count($Matches) == 3)
					$this->strings[$Matches[1]] = $Matches[2];
			}

			$this->loadedFiles[$FileName] = TRUE;
		}
	}

	public function resetStrings() {
		$this->strings = array();
	}

	public function getString($Index) {
		if(isset($this->strings[$Index]) == FALSE) {
			trigger_error('Language string "'.$Index.'" does not exist');
			return FALSE;
		}
		return $this->strings[$Index];
	}

	public function setLanguageDir($newLanguageDir) {
		$this->languageDir = $newLanguageDir;
	}
}

?>