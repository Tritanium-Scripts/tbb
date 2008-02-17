<?php
/**
*
* Tritanium Bulletin Board 2 - db/mysql.class.php5.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

class db {
	public $raw_data;
	public $array_data = array();
	public $query_counter = 0;
	public $query_time = 0;
	public $affected_rows = 0;
	public $insert_id = 0;
	public $sql_queries = array('');
	public $TIMESTAMP_FORMAT = 'YmdHis';
	public $DATETIME_FORMAT = 'Y-m-d H:i:s';

	public function connect($db_server,$db_user,$db_password) {
		if(@mysql_connect($db_server,$db_user,$db_password) == FALSE) return FALSE;
		return TRUE;
	}

	public function select_db($db_name) {
		return mysql_select_db($db_name);
	}

	public function error() {
		return mysql_error().'<br />';
	}

	public function query($query,$debug_mode = TRUE) {
		$this->query_counter++;
		$this->affected_rows = 0;

		$mtime_counter = get_mtime_counter();

		if(!$this->raw_data = mysql_query($query)) {
			if($debug_mode == TRUE) echo '<br />MySQL Error: '.mysql_error().'<br />';
			return FALSE;
		}
		else {
			$akt_query_time = get_mtime_counter()-$mtime_counter;

//			file_write('db.log',$akt_query_time.': '.$query."\n",'a');

			$this->query_time += $akt_query_time;
			$this->affected_rows = mysql_affected_rows();
			$this->insert_id = mysql_insert_id();
		}

		return TRUE;
	}

	public function fetch_array() {
		return mysql_fetch_array($this->raw_data);
	}

	public function raw2array() {
		$this->array_data = array();
		while($akt_row = $this->fetch_array())
			$this->array_data[] = $akt_row;
		return $this->array_data;
	}

	public function raw2fvarray() {
		$this->array_data = array();
		while(list($akt_first_value) = $this->fetch_array())
			$this->array_data[] = $akt_first_value;
		return $this->array_data;
	}

	public function count_rows() {
		return mysql_num_rows($this->raw_data);
	}

	public function execute_queries() {
		while(list(,$akt_query) = each($this->sql_queries)) {
			if(!$this->query($akt_query))
				return FALSE;
		}

		return TRUE;
	}

	public function sql_split($data) {
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