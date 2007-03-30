<?php

class Language {
	private $Strings = array();
	private $LanguageDir = '';
	private $LoadedFiles = array();

	public function addFile($FileName) {
		if(!isset($this->LoadedFiles[$FileName])) {
			if(file_exists($this->LanguageDir.$FileName.'.language') == FALSE) throw new Exception('Language file "'.$this->LanguageDir.$FileName.'.language" does not exist');
			$this->Strings = array_merge($this->Strings,parse_ini_file($this->LanguageDir.$FileName.'.language'));
			$this->LoadedFiles[$FileName] = TRUE;
		}
	}

	public function resetStrings() {
		$this->Strings = array();
	}

	public function getString($Index) {
		if(isset($this->Strings[$Index]) == FALSE) throw new Exception('Language string "'.$Index.'" does not exists');
		return $this->Strings[$Index];
	}

	public function setLanguageDir($newLanguageDir) {
		$this->LanguageDir = $newLanguageDir;
	}
}

?>