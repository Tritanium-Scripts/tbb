<?php

class Language extends ModuleTemplate {
	protected $requiredModules = array(
		'Auth',
		'Cache',
		'Config'
	);
	protected $strings = array();
	protected $languageDir = '';
	protected $loadedFiles = array();
	protected $languageString = '';
	protected $languages = array(array(),array());

	public function initializeMe() {
		$this->languages = $this->modules['Cache']->getLanguages();
		
		if($this->modules['Auth']->isLoggedIn() && isset($this->languages[0][$this->modules['Auth']->getValue('userLanguage')]))
			$this->languageString = $this->languages[$this->modules['Auth']->getValue('userLanguage')];
		elseif(isset($this->languages[0][$this->getConfigValue('defaultLanguageCode')]))
			$this->languageString = $this->getConfigValue('defaultLanguageCode');
		else {
			// TODO: throw error
		}
		
		//$this->languageDir = 'languages/'.$this->languageCode.'/';
		foreach($this->getConfigValue('autoloadFiles') AS $curFile)
			$this->addFile($curFile);
	}

	public function getLS($languageCode = '') {
		return ($languageCode == '' ? $this->languageString : (isset($this->languages[$languageCode]) ? $this->languages[$languageCode] : die('Unknown language: '.$languageCode)));
	}
	
	/*public function getLC($languageString = '') {
		return ($languageCode == '' ? $this->languageCode : (isset($this->languages[$languageCode]) ? $this->languages[$languageCode] : die('Unknown language: '.$languageCode)));
	}*/

	public function getLD($languageString = '') {
		return 'languages/'.($languageString != '' ? $languageString : $this->languageString).'/';
	}

	public function addFile($fileName,$languageString = '') {
		$languageString = ($languageString == '' ? $this->languageString : $languageString);
		
		if(isset($this->loadedFiles[$languageString][$fileName])) return;
		$languageDir = $this->getLD($languageString);

		// Gibt es die Originaldatei nicht, erfolgt der Abbruch
		if(!file_exists($languageDir.$fileName.'.language'))
			die('Language file "'.$languageDir.$fileName.'.language" does not exist');
		
		$cacheFile = 'cache/Language-'.$languageString.'-'.$fileName.'.cache.php';
		// Befindet sie sich im Cache und ist sie auch neuer als die Originaldatei, wird sie einfach inkludiert
		if(file_exists($cacheFile) && (filemtime($cacheFile) > filemtime($languageDir.$fileName.'.language'))) {
			include($cacheFile);
			return;
		}

		$toWrite = array();

		// Datei parsen
		foreach(explode("\n",file_get_contents($languageDir.$fileName.'.language')) AS $curLine) {
			preg_match('/^([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)[ ]*=[ ]*(.*)$/',rtrim($curLine),$matches);

			// Ergebnisse speichern
			if(count($matches) == 3) {
				$this->strings[$languageString][$matches[1]] = $matches[2];
				$toWrite[] = '$this->strings[\''.$languageString.'\'][\''.$matches[1].'\'] = \''.addcslashes($matches[2],'\'').'\'';
			}
		}
		// Datei cachen
		$toWrite = '<?php '.implode(';',$toWrite).' ?>';
		Functions::FileWrite($cacheFile,$toWrite,'w');

		// Datei geladen
		$this->loadedFiles[$languageString][$fileName] = TRUE;
	}

	public function getString($index,$languageString = '') {
		$languageString = ($languageString == '' ? $this->languageString : $languageString);
		
		if(!isset($this->strings[$languageString][$index])) {
			trigger_error('Language string "'.$index.'" for language string '.$languageString.' does not exist');
			return FALSE;
		}
		return $this->strings[$languageString][$index];
	}
}

?>