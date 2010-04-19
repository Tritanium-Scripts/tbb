<?php
/**
*
* Tritanium Bulletin Board 2 - functions_files.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

define('STANDARD_CHMOD_MODE',0777);

/*** Komplette Datei in einen String laden ***/
function file_to_str($filename) {
	if(file_exists($filename) == FALSE) return FALSE;
	if(filesize($filename) == 0) return '';

	if(!$fp = @fopen($filename,'rb')) // ...überprüfen, ob Datei geöffnet werden kann...
		return FALSE;

	$file_content = '';

	flock($fp,LOCK_SH); // Datei sperren
	$file_content = fread($fp,filesize($filename)); // Komplette Datei in den Cache lesen
	flock($fp,LOCK_UN); fclose($fp); // Datei entsperren und schließen

	return $file_content;
}

/*** Daten in eine Datei schreiben ***/
function file_write($filename,$data,$mode = 'w') {
	if(!$fp = @fopen($filename,$mode.'b')) // ...überprüfen, ob Datei geöffnet werden kann...
		return FALSE;

	flock($fp,LOCK_EX);
	fwrite($fp,$data);
	flock($fp,LOCK_UN); fclose($fp);
	@chmod($filename,STANDARD_CHMOD_MODE);

	return TRUE;
}

?>