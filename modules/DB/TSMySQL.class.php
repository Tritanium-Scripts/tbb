<?php

class TSMySQL {
	protected $dbObject = NULL;
	protected $curResult = NULL;
	protected $connectError = '';

	public function __construct() {
		$this->dbObject = new mysqli;
	}

	public function connect($dbServer,$dbUser,$dbPassword,$dbName) {
		@$this->dbObject->connect($dbServer,$dbUser,$dbPassword,$dbName);
		if(mysqli_connect_error() != '') {
			$this->connectError = mysqli_connect_error();
			return FALSE;
		}

		$this->query('SET NAMES utf8');
		$this->query('SET sql_mode=\'ANSI\'');

		return TRUE;
	}

	public function getConnectError() {
		return $this->connectError;
	}

	public function query($query) {
		if(!($this->curResult = $this->dbObject->query($query))) return FALSE;
		return TRUE;
	}

	public function getError() {
		return $this->dbObject->error;
	}

	protected function queryParamsCallback($at) {
		return $this->parameters[$at[1]-1];
	}

	protected function parseQueryParam($parameter) {
		if(is_array($parameter)) {
			if(count($parameter) == 0) $parameter = '(NULL)';
			else $parameter = '('.implode(',',array_map(array($this,'parseQueryParam'),$parameter)).')';
		}
		elseif(is_null($parameter))
			$parameter = 'NULL';
		elseif(!is_int($parameter))
			$parameter = "'".$this->escapeString($parameter)."'";

		return $parameter;
	}

	public function queryParams($query,$parameters = array()) {
		$this->parameters = array_map(array($this,'parseQueryParam'),$parameters);
		$query = preg_replace_callback('/\$([0-9]+)/',array($this,'queryParamsCallback'),$query);

		$startTime = Functions::getMicroTime();
		if(!($this->curResult = $this->dbObject->query($query))) die('Database error: <b>'.$this->dbObject->error.'</b><br/>Query: <b>'.$query.'</b>');
		$this->queryTime += Functions::getMicroTime()-$startTime;
		$this->queriesCounter++;
		return TRUE;
	}

	public function splitQueries($data) {
		$queries = array('');
		$i = 0;
		$j = -1;

		$inQuotes = FALSE;
		$escapeChar = FALSE;
		$charsCounter = Functions::strlen($data);
		$inComment = '';

		while(++$j < $charsCounter) {
			if($inComment == '') {
				if($data[$j] == ';' && !$inQuotes) {
					$queries[$i] = trim($queries[$i]);
					if($queries[$i] != '')
						$queries[++$i] = '';
					$escapeChar = FALSE;
				}
				elseif($data[$j] == '\\') {
					$queries[$i] .= $data[$j];

					if($inQuotes)
						$escapeChar = TRUE;
				}
				elseif($data[$j] == "'") {
					$queries[$i] .= $data[$j];
					if($inQuotes && !$escapeChar) {
						$inQuotes = FALSE;
					}
					elseif(!$inQuotes) {
						$inQuotes = TRUE;
					}
					$escapeChar = FALSE;
				}
				elseif($data[$j] == '#') {
					$inComment = '#';
				}
				else {
					$queries[$i] .= $data[$j];
				}
			}
			elseif($data[$j] == "\n" && $inComment == '#') {
				$inComment = '';
			}
		}

		if(trim($queries[$i]) != '') return FALSE;
		else unset($queries[$i]);

		return $queries;
	}

	public function getInsertID() {
		return $this->dbObject->insert_id;
	}

	public function fetchArray() {
		return $this->curResult->fetch_array();
	}

	public function getAffectedRows() {
		return $this->dbObject->affected_rows;
	}

	public function numRows() {
		return $this->curResult->num_rows;
	}

	public function fromUnixTimestamp($timestamp) {
		return date('Y-m-d H:i:s',$timestamp);
	}

	public function toUnixTimstamp($date) {
	}

	public function escapeString($string) {
		return $this->dbObject->real_escape_string($string);
	}
	
	public function getTablesData() {
		$tablesData = array();
		$this->query('SHOW TABLES');
		while(list($tablesData[]) = $this->fetchArray()) {}
		return $tablesData;
	}
	
	public function getColumnsData($tableName) {
		$columnsData = array();
		$this->query('SHOW COLUMNS '.$tableName);	
		while(list($columnsData[]) = $this->fetchArray()) {}
		return $columnsData;
	}
}

?>