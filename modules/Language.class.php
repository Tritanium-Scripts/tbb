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

	public function addFile($fileName) {
		if(!isset($this->loadedFiles[$fileName])) {
			if(file_exists($this->languageDir.$fileName.'.language') == FALSE) die('Language file "'.$this->languageDir.$fileName.'.language" does not exist');

			foreach(explode("\n",file_get_contents($this->languageDir.$fileName.'.language')) AS $curLine) {
				preg_match('/^([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)[ ]*=[ ]*(.*)$/',rtrim($curLine),$matches);

				if(count($matches) == 3)
					$this->strings[$matches[1]] = $matches[2];
			}

			$this->loadedFiles[$fileName] = TRUE;
		}
	}

	public function resetStrings() {
		$this->strings = array();
	}

	public function getString($index) {
		if(isset($this->strings[$index]) == FALSE) {
			trigger_error('Language string "'.$index.'" does not exist');
			return FALSE;
		}
		return $this->strings[$index];
	}

	public function setLanguageDir($newLanguageDir) {
		$this->languageDir = $newLanguageDir;
	}
}

?>