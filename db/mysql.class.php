<?php
/**
*
* Tritanium Bulletin Board 2 - db/mysql.class.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

class db {
	var $raw_data;
	var $array_data = array();
	var $query_counter = 0;
	var $query_time = 0;
	var $affected_rows = 0;
	var $insert_id = 0;
	var $sql_queries = array('');
	var $TIMESTAMP_FORMAT = 'YmdHis';
	var $DATETIME_FORMAT = 'Y-m-d H:i:s';

	function connect($db_server,$db_user,$db_password) {
		if(@mysql_connect($db_server,$db_user,$db_password) == FALSE) return FALSE;
		return TRUE;
	}

	function select_db($db_name) {
		return mysql_select_db($db_name);
	}

	function error() {
		return mysql_error().'<br />';
	}

	function query($query,$debug_mode = TRUE) {
		$this->query_counter++;
		$this->affected_rows = 0;

		$mtime_counter = get_mtime_counter();

		if(!$this->raw_data = mysql_query($query)) {
			if($debug_mode == TRUE) echo '<br />MySQL Error: '.mysql_error().'<br />';
			return FALSE;
		}
		else {
			$this->query_time += get_mtime_counter()-$mtime_counter;
			$this->affected_rows = mysql_affected_rows();
			$this->insert_id = mysql_insert_id();
		}

		return TRUE;
	}

	function fetch_array() {
		return mysql_fetch_array($this->raw_data);
	}

	function raw2array() {
		$this->array_data = array();
		while($akt_row = $this->fetch_array())
			$this->array_data[] = $akt_row;
		return $this->array_data;
	}

	function raw2fvarray() {
		$this->array_data = array();
		while(list($akt_first_value) = $this->fetch_array())
			$this->array_data[] = $akt_first_value;
		return $this->array_data;
	}

	function count_rows() {
		return mysql_num_rows($this->raw_data);
	}

	function sql_split($data) {
		$this->sql_queries = array('');
		$i = $j = 0;

		$in_quotes = '';
		$escape_char = FALSE;
		$chars_counter = strlen($data);
		$in_comment = '';

		while($j < $chars_counter) {
			if($in_comment == '') {
				if($data[$j] == ';' && $in_quotes == FALSE) {
					$this->sql_queries[$i] .= $data[$j];
					$i++;
					$this->sql_queries[$i] = '';
					$escape_char = FALSE;
				}
				elseif($data[$j] == '\\') {
					$this->sql_queries[$i] .= $data[$j];
					$escape_char = TRUE;
				}
				elseif($data[$j] == '"') {
					$this->sql_queries[$i] .= $data[$j];
					if($in_quotes == '"' && $escape_char == FALSE) { // String mit " endet, falls nicht escaped wurde
						$in_quotes = '';
					}
					elseif($in_quotes == '') { // String mit " beginnt
						$in_quotes = '"';
					}
					$escape_char = FALSE;
				}
				elseif($data[$j] == "'") {
					$this->sql_queries[$i] .= $data[$j];
					if($in_quotes == "'" && $escape_char == FALSE) { // String mit ' endet, falls nicht escaped wurde
						$in_quotes = '';
					}
					elseif($in_quotes == '') { // String mit ' beginnt
						$in_quotes = "'";
					}
					$escape_char = FALSE;
				}
				elseif($data[$j] == '#') { // Kommentar mit # beginnt
					$in_comment = '#';
				}
				else {
					$this->sql_queries[$i] .= $data[$j];
				}
			}
			elseif($data[$j] == "\n" && $in_comment == '#') {
				$in_comment = '';
			}
			$j++;
		}

		if($this->sql_queries[$i] != '') echo '<br />Fehler beim Splitten von SQL-Befehlen: Nicht abgeschlossener SQL-Befehl: <b>'.$this->sql_queries[$i].'</b><br />';
		else unset($this->sql_queries[$i]);

		return $this->sql_queries;
	}


}

?>