<?php

class Language extends ModuleTemplate {
	protected $strings = array();
	protected $languageDir = '';
	protected $loadedFiles = array();
	protected $languageCode = '';

	public function initializeMe() {
		$this->languageCode = $this->getConfigValue('defaultLanguageCode');
		$this->languageDir = 'languages/'.$this->languageCode.'/';
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
        //Wenn die Datei bereits geladen ist, gibt es nichts weiter zu tun
		if(!isset($this->loadedFiles[$fileName])) {
            //Gibt es die Originaldatei nicht, erfolgt der Abbruch
            if(!file_exists($this->languageDir.$fileName.'.language')) die('Language file "'.$this->languageDir.$fileName.'.language" does not exist');
            $cacheFile = 'cache/Language-'.$this->languageCode.'-'.$fileName.'.cache.php';
            //Befindet sie sich im Cache und ist sie auch neuer als die Originaldatei, wird sie einfach inkludiert
            if(file_exists($cacheFile) && (filemtime($cacheFile) > filemtime($this->languageDir.$fileName.'.language')))
                include($cacheFile);
            //Ansonsten muss sie (neu-)geparst werden
            else {
                $toWrite = array();

                //Datei parsen
			    foreach(explode("\n",file_get_contents($this->languageDir.$fileName.'.language')) AS $curLine) {
				    preg_match('/^([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)[ ]*=[ ]*(.*)$/',rtrim($curLine),$matches);

                    //Ergebnisse speichern
				    if(count($matches) == 3) {
					    $this->strings[$matches[1]] = $matches[2];
                        $toWrite[] = '$this->strings[\''.$matches[1].'\'] = \''.addslashes($matches[2]).'\'';
                    }
			    }
                //Datei cachen
                $toWrite = '<?php '.implode(';',$toWrite).' ?>';
                Functions::FileWrite($cacheFile,$toWrite,'w');

                //Datei geladen
			    $this->loadedFiles[$fileName] = TRUE;
            }
		}
	}

	public function resetStrings() {
		$this->strings = array();
	}

	public function getString($index) {
		if(!isset($this->strings[$index])) {
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