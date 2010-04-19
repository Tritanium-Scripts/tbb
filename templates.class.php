<?php
/**
*
* Tritanium Bulletin Board 2 - templates.class.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

class template {
	var $parsed_code = '';
	var $raw_code = '';
	var $blocks = array();
	var $values = array();
	var $tpl_file_dir = '';

	function template($template_file = '') {
		if($template_file != '')
			$this->load($template_file);
	}

	function include_template($tpl_file) {
		$temp_tpl = new template($this->tpl_file_dir.'/'.$tpl_file);
		return $temp_tpl->parse_code();
	}

	function load($tpl_file,$use_cache = TRUE) {
		if(file_exists($tpl_file) == FALSE) die('Template error: The file "'.$tpl_file.'" does not exist');
		else {
			$file_last_modified = filemtime($tpl_file);
			$file_info = pathinfo($tpl_file);

			$this->tpl_file_dir = &$file_info['dirname'];
			$cache_dir = $file_info['dirname'].'/cache';
			$cache_file = $file_info['dirname'].'/cache/'.$file_info['basename'];
			$cache_file_exists = file_exists($cache_file);

			$do_reload = TRUE;

			if($use_cache == TRUE && $cache_file_exists == TRUE) { // Falls eine gecachte Version des Templates gefunden wurde...
				$cache_file_last_modified = filemtime($cache_file);

				if($file_last_modified <= $cache_file_last_modified) {
					$do_reload = FALSE;

					$fp = fopen($cache_file,'rb'); flock($fp,LOCK_SH);
					$temp_tpl = unserialize(@fread($fp,filesize($cache_file)));
					flock($fp,LOCK_UN); fclose($fp);

					foreach(get_object_vars($temp_tpl) as $key => $value)
						$this->$key = $value;
				}
			}

			if($do_reload == TRUE) {
				$fp = fopen($tpl_file,'rb'); flock($fp,LOCK_SH);
				$this->raw_code = @fread($fp,filesize($tpl_file));
				flock($fp,LOCK_UN); fclose($fp);

				$this->create_code();

				if($use_cache == TRUE && is_writable($file_info['dirname'].'/cache') == TRUE) {
					$fp = fopen($cache_file,'wb'); flock($fp,LOCK_EX);
					fwrite($fp,serialize($this));
					flock($fp,LOCK_UN); fclose($fp);
					@chmod($cache_file,0777);
				}
			}
		}
	}

	function create_code() {
		$temp = &$this->raw_code;

		$temp = str_replace("\\","\\\\",$temp);
		$temp = str_replace("'","\\'",$temp);

		while(preg_match('/<template:([a-zA-Z0-9_]{1,})>/i',$temp,$match)) {
			$block_start_tag = '<template:'.$match[1].'>';
			$block_start_pos = strpos($temp,$block_start_tag);
			$block_raw_code = substr($temp,$block_start_pos+strlen($block_start_tag));

			if(isset($this->blocks[$match[1]])) die('Template error: Duplicate block \''.$match[1].'\'');

			$this->blocks[$match[1]] = new template_block($match[1]);
			$this->blocks[$match[1]]->tpl_file_dir = $this->tpl_file_dir;
			$this->blocks[$match[1]]->set_raw_code($block_raw_code);
			$block_rest_code = $this->blocks[$match[1]]->create_code();

			$temp = substr($temp,0,$block_start_pos)."'.\$this->blocks['".$match[1]."']->parsed_code.'".$block_rest_code;
		}

		$temp = $this->create_if_blocks($temp);

		$temp = preg_replace_callback('/\{\$([a-zA-Z0-9_]+)([a-zA-Z0-9_\\\>\[\]\"\'\-]*)\}/',create_function('$items','$items[2] = preg_replace("/\\$([a-zA-Z_]{1}[a-zA-Z0-9_]*)/","\\$GLOBALS[\'\\1\']",$items[2]); return "\'.\$this->get_var_value(\'\$GLOBALS[\\\'$items[1]\\\']$items[2]\').\'";'),$temp); // Die globalen Variablen
		$temp = preg_replace_callback('/\{([a-zA-Z0-9_]+)([a-zA-Z0-9_\\\[\]\'\"]*)\}/',create_function('$items','$items[2] = str_replace("\\\'","\'",$items[2]); return "\'.\$this->values[\'$items[1]\']$items[2].\'";'),$temp); // Die lokalen Variablen
		$temp = preg_replace('/<templatefile:\"([a-zA-Z0-9_.]{1,})\" \/>/i','\'.$this->include_template(\'\1\').\'',$temp); // Die Template-Referenzen

		unset($temp);
	}

	function create_if_blocks($temp) {
		while(preg_match('/<if:"([^"]*)">/is',$temp,$match)) {
			$block_start_tag = '<if:"'.$match[1].'">';
			$block_start_pos = strpos($temp,$block_start_tag);
			$code_start_pos = $block_start_pos+strlen($block_start_tag);
			$block_next_end_pos = strpos($temp,'</if>');

			if($block_next_end_pos > $block_start_pos)
				$temp = substr_replace($temp,$this->create_if_blocks(substr($temp,$code_start_pos)),$code_start_pos);
			else
				break;

			$block_end_pos = strpos($temp,'</if>');

			if(($block_else_pos = strpos($temp,'<else />')) && $block_else_pos < $block_end_pos && $block_else_pos > $block_start_pos) {
				$block_if_code = substr($temp,$code_start_pos,$block_else_pos-$code_start_pos);
				$block_else_code = substr($temp,$block_else_pos+8,$block_end_pos-$block_else_pos-8);
			}
			else {
				$block_if_code = substr($temp,$code_start_pos,$block_end_pos-$code_start_pos);
				$block_else_code = '';
			}

			$match[1] = stripslashes($match[1]);

			$match[1] = preg_replace_callback('/\{\$([a-zA-Z0-9_]+)([a-zA-Z0-9_\\\>\[\]\"\'\-]*)\}/',create_function('$items','$items[2] = str_replace("\'","\\\'",$items[2]); $items[2] = preg_replace("/\\$([a-zA-Z_]{1}[a-zA-Z0-9_]*)/","\\$GLOBALS[\'\\1\']",$items[2]); return "\$this->get_var_value(\'\$GLOBALS[\\\'$items[1]\\\']$items[2]\')";'),$match[1]); // Die globalen Variablen
			$match[1] = preg_replace_callback('/\{([a-zA-Z0-9_]+)([a-zA-Z0-9_\\\[\]\'\"]*)\}/',create_function('$items','$items[2] = str_replace("\\\'","\'",$items[2]); return "\$this->values[\'$items[1]\']$items[2]";'),$match[1]); // Die lokalen Variablen
			$match[1] = preg_replace('/<templatefile:\"([a-zA-Z0-9_.]{1,})\" \/>/i','\'.$this->include_template(\'\1\').\'',$match[1]); // Die Template-Referenzen

			$temp = substr_replace($temp,'\'.(('.$match[1].') ? \''.$block_if_code.'\' : \''.$block_else_code.'\').\'',$block_start_pos,$block_end_pos+5-$block_start_pos);
		}

		return $temp;
	}

	function parse_code($output_code = FALSE, $append_code = FALSE) {
		if($append_code == TRUE) eval("\$this->parsed_code .= '".$this->raw_code."';");
		else eval("\$this->parsed_code = '".$this->raw_code."';");

		reset($this->blocks);

		if($output_code == TRUE) echo $this->parsed_code;

		return $this->parsed_code;
	}

	function reset_tpl() {
		while(list($akt_key) = each($this->blocks)) {
			$this->blocks[$akt_key]->reset_tpl();
		}

		reset($this->blocks);

		$this->parsed_code = '';
	}

	function set_parsed_code($new_parsed_code) {
		$this->parsed_code = $new_parsed_code;
	}

	function set_raw_code($new_raw_code) {
		$this->raw_code = $new_raw_code;
	}

	function get_var_value($var) {
		eval("\$var = $var;");
		return $var;
	}

	function unset_block() {
	}

	function blank_tpl() {
		$this->set_parsed_code('');
	}
}

class template_block extends template {
	var $name = '';

	function template_block($name) {
		$this->set_name($name);
	}

	function set_name($new_name) {
		$this->name = $new_name;
	}

	function create_code() {
		$temp = $this->raw_code;

		$temp = ltrim($temp);

		while(preg_match('/<template:([a-zA-Z0-9_]{1,})>/i',$temp,$match)) {
			$block_start_tag = '<template:'.$match[1].'>';
			$block_start_pos = strpos($temp,$block_start_tag);
			$block_next_end_pos = strpos($temp,'</template>');

			if($block_next_end_pos > $block_start_pos) {
				$block_raw_code = substr($temp,$block_start_pos+strlen($block_start_tag));

				$this->blocks[$match[1]] = new template_block($match[1]);
				$this->blocks[$match[1]]->set_raw_code($block_raw_code);
				$block_rest_code = $this->blocks[$match[1]]->create_code();

				$temp = substr($temp,0,$block_start_pos)."'.\$this->blocks['".$match[1]."']->parsed_code.'".$block_rest_code;
			}
			else
				break;
		}

		$temp = $this->create_if_blocks($temp);

		$temp = preg_replace_callback('/\{\$([a-zA-Z0-9_]+)([a-zA-Z0-9_\\\>\[\]\"\'\-]*)\}/',create_function('$items','$items[2] = preg_replace("/\\$([a-zA-Z_]{1}[a-zA-Z0-9_]*)/","\\$GLOBALS[\'\\1\']",$items[2]); return "\'.\$this->get_var_value(\'\$GLOBALS[\\\'$items[1]\\\']$items[2]\').\'";'),$temp); // Die globalen Variablen
		$temp = preg_replace_callback('/\{([a-zA-Z0-9_]+)([a-zA-Z0-9_\\\[\]\'\"]*)\}/',create_function('$items','$items[2] = str_replace("\\\'","\'",$items[2]); return "\'.\$this->values[\'$items[1]\']$items[2].\'";'),$temp); // Die lokalen Variablen
		$temp = preg_replace('/<templatefile:\"([a-zA-Z0-9_.]{1,})\" \/>/i','\'.$this->include_template(\'\1\').\'',$temp); // Die Template-Referenzen

		if(!$end_pos = strpos($temp,'</template>'))
			die('Template error: Block \''.$this->name.'\' not finished');

		$this->set_raw_code(substr($temp,0,$end_pos));

		return substr($temp,$end_pos+11);
	}
}

?>