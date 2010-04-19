<?php
/**
*
* Tritanium Bulletin Board 2 - templates.class.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

class template {

	var $code;
	var $parsed_code = '';
	var $is_parsed = FALSE;
	var $values = array();
	var $blocks = array();
	var $name = '';

	function load($tpl_file) {
		$this->code = '';
		$this->parse_code = '';
		$this->is_parsed = FALSE;
		$this->values = array();
		$this->blocks = array();
		$this->name = '';

		if(!file_exists($tpl_file)) die('Template fatal error: The file "'.$tpl_file.'" does not exist!');
		else {
			$this->name = $tpl_file;

			$fp = fopen($tpl_file,'rb'); flock($fp,LOCK_SH);
			$this->code = fread($fp,filesize($tpl_file));
			flock($fp,LOCK_UN); fclose($fp);

			$this->create_code($this->code);
		}
	}

	function create_code($data,$prefix = '') {

		$blocks = array();

		preg_match_all('#<template:'.$prefix.'[a-z_]*>[\r\n]*(.*?)[\r\n]*</template:'.$prefix.'[a-z_]*>[\r\n]*#s',$data,$results1); // Die Blöcke
		preg_match_all('#<template:'.$prefix.'([a-z_]*)>[\r\n]*.*?[\r\n]*</template:'.$prefix.'[a-z_]*>[\r\n]*#s',$data,$results2); // Die Namen der Blöcke

		$found = count($results1[1]); // Anzahl der Teile

		$data = str_replace("\\","\\\\",$data);
		$data = str_replace("'","\\'",$data);

		$data = preg_replace_callback('/\{'.$prefix.'\$([a-zA-Z0-9_]+)([a-zA-Z0-9_\\\>\[\]\"\'\-]*)\}/',create_function('$items','$items[2] = preg_replace("/\\$([a-zA-Z_]{1}[a-zA-Z0-9_]*)/","\\$GLOBALS[\'\\1\']",$items[2]); return "\'.\$this->get_var_value(\'\$GLOBALS[\"$items[1]\"]$items[2]\').\'";'),$data); // Die globalen Variablen
		$data = preg_replace_callback('/\{'.$prefix.'([a-zA-Z0-9_]+)([a-zA-Z0-9_\\\[\]\'\"]*)\}/',create_function('$items','$items[2] = str_replace("\\\'","\'",$items[2]); return "\'.\$this->values[\'$items[1]\']$items[2].\'";'),$data); // Die lokalen Variablen
		$data = preg_replace('#<template:'.$prefix.'([a-z_]*)>[\r\n]*.*?[\r\n]*</template:'.$prefix.'[a-z_]*>#s','\'.$this->blocks[\'\1\']->parsed_code.\'',$data);
		$final_code = $data;

		if($found > 0) {
			for($i = 0; $i < $found; $i++) {
				$blocks[$results2[1][$i]] = new template;
				$blocks[$results2[1][$i]]->name = $this->name.'.'.$results2[1][$i];
				$blocks[$results2[1][$i]]->create_code($results1[1][$i],$prefix.$results2[1][$i].'\.');
			}
		}

		reset($blocks);

		$this->blocks = $blocks;
		$this->code = $final_code;
	}

	function parse_code($output_code = FALSE, $append_code = FALSE) {
		while(list($akt_key) = each($this->blocks)) {
			if($this->blocks[$akt_key]->is_parsed === FALSE) {
				echo 'Warning: template '.$this->blocks[$akt_key]->name.' has not yet been parsed!<br>';
				$this->blocks[$akt_key]->parse_code();
			}
		}

		if($append_code == TRUE) eval("\$this->parsed_code .= '".$this->code."';");
		else eval("\$this->parsed_code = '".$this->code."';");

		reset($this->blocks);

		if($output_code == TRUE) echo $this->parsed_code;

		$this->is_parsed = TRUE;

		return $this->parsed_code;
	}

	function unset_block($block_name) {
		if(isset($this->blocks[$block_name])) {
			$this->blocks[$block_name]->is_parsed = TRUE;
			$this->blocks[$block_name]->code = '';
			$this->blocks[$block_name]->parsed_code = '';
		}
		else echo 'Warning: Couldn\'t unset block '.$this->name.'.'.$block_name.'!<br>';
	}

	function reset_tpl() {
		while(list($akt_key) = each($this->blocks)) {
			if($this->blocks[$akt_key]->is_parsed == TRUE) {
				$this->blocks[$akt_key]->reset_tpl();
			}
		}

		reset($this->blocks);

		$this->parsed_code = '';
		$this->is_parsed = FALSE;
	}

	function blank_tpl() {
		while(list($akt_key) = each($this->blocks)) {
			if($this->blocks[$akt_key]->is_parsed == FALSE) {
				$this->blocks[$akt_key]->blank_tpl();
			}
		}

		reset($this->blocks);

		$this->parsed_code = '';
		$this->is_parsed = TRUE;
	}

	function get_var_value($var) {
		eval("\$var = $var;");
		return $var;
	}
}


?>