<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
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

	/**
	 * Returns the current language directory.
	 * 
	 * @param string $languageString
	 */
	public function getLD($languageString = '') {
		return 'languages/'.($languageString != '' ? $languageString : $this->languageString).'/';
	}

	/**
	 * Adds a language file for caching.
	 * 
	 * @param string Name of language file
	 * @param string $languageString
	 */
	public function addFile($fileName, $languageString = '') {
		$languageString = ($languageString == '' ? $this->languageString : $languageString);

		// already loaded?
		if(isset($this->loadedFiles[$languageString][$fileName])) return;
		$iniFile = $this->getLD($languageString) . $fileName . '.ini';

		// check original file
		if(!file_exists($iniFile))
			throw new Exception('Language file "' . $iniFile . '" does not exist!');

		// check cache
		$cacheFile = 'cache/Language-' . $languageString . '-' . $fileName . '.cache.php';
		if(file_exists($cacheFile) && (filemtime($cacheFile) > filemtime($iniFile))) {
			include($cacheFile);
			return;
		}

		// parse file and cache it
		$toWrite = array();
		foreach(parse_ini_file($iniFile) as $curKey => $curValue) {
			$this->strings[$languageString][$curKey] = $curValue;
			$toWrite[] = '$this->strings[\'' . $languageString . '\'][\'' . $curKey . '\'] = \'' . addcslashes($curValue, '\'') . '\';';
		}
		Functions::FileWrite($cacheFile, '<?php ' . implode('', $toWrite) . ' ?>', 'wb');

		$this->loadedFiles[$languageString][$fileName] = TRUE;
	}

	/**
	 * Returns a cached language string stated by the index key.
	 * 
	 * @param string Language key, identifies the requested string
	 * @param string $languageString
	 * @return string Translated string
	 */
	public function getString($index,$languageString = '') {
		$languageString = ($languageString == '' ? $this->languageString : $languageString);
		
		if(!isset($this->strings[$languageString][$index])) {
			trigger_error('Language string "'.$index.'" for language string '.$languageString.' does not exist');
			return FALSE;
		}
		return $this->strings[$languageString][$index];
	}
}