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

	public function addFile($fileName, $languageString = '') {
		$languageString = ($languageString == '' ? $this->languageString : $languageString);
		// Wurde die Datei waehrend der Ausfuehrung schon gechachet, gibt es nichts mehr zu tun
		if(isset($this->loadedFiles[$languageString][$fileName])) return;
		$iniFile = $this->getLD($languageString) . $fileName . '.ini';
		// Gibt es die Originaldatei nicht, erfolgt der Abbruch
		if(!file_exists($iniFile))
			die('Language file "' . $iniFile . '" does not exist!');
		$cacheFile = 'cache/Language-' . $languageString . '-' . $fileName . '.cache.php';
		// Befindet sie sich im Cache und ist sie auch neuer als die Originaldatei, wird sie einfach inkludiert
		if(file_exists($cacheFile) && (filemtime($cacheFile) > filemtime($iniFile))) {
			include($cacheFile);
			return;
		}
		// Datei parsen
		$toWrite = array("<?php");
		foreach(parse_ini_file($iniFile) as $curKey => $curValue)
		{
		 $this->strings[$languageString][$curKey] = $curValue;
		 $toWrite[] = '$this->strings[\'' . $languageString . '\'][\'' . $curKey . '\'] = \'' . addcslashes($curValue, '\'') . '\';';
		}
		// Datei cachen
		Functions::FileWrite($cacheFile, implode("\n", $toWrite) . "\n?>", 'w');
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