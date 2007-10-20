<?php

include('DB/TSMySQL.class.php');

class DB extends ModuleTemplate {
	protected $queriesCounter = 0;
	protected $dbObject = NULL;
	protected $queryTime = 0;

	public function initializeMe() {
		$this->dbObject = new TSMySQL;
		$this->dbObject->connect($this->getC('dbServer'),$this->getC('dbUser'),$this->getC('dbPassword'),$this->getC('dbName'));
		define('TBLPFX',$this->getTablePrefix());
	}

	public function query($query) {
		$startTime = Functions::getMicroTime();
		$result = $this->dbObject->query($query);
		$this->queryTime += Functions::getMicroTime()-$startTime;
		$this->queriesCounter++;
		return $result;
	}

	public function queryParams($query,$parameters = array()) {
		$startTime = Functions::getMicroTime();
		$result = $this->dbObject->queryParams($query,$parameters);
		$this->queryTime += Functions::getMicroTime()-$startTime;
		$this->queriesCounter++;
		return $result;
	}

	public function getQueryTime() {
		return $this->queryTime;
	}

	public function getInsertID() {
		return $this->dbObject->insert_id;
	}

	public function raw2Array() {
		$temp = array();
		while($curRow = $this->dbObject->fetchArray())
			$temp[] = $curRow;

		return $temp;
	}

	public function raw2FVArray() {
		$temp = array();

		while(list($curValue) = $this->fetchArray())
			$temp[] = $curValue;

		return $temp;
	}

	public function fetchArray() {
		return $this->dbObject->fetchArray();
	}

	public function getAffectedRows() {
		return $this->dbObject->getAffectedRows();
	}

	public function numRows() {
		return $this->dbObject->numRows();
	}

	public function getTablePrefix() {
		return $this->getC('tablePrefix');
	}

	public function fromUnixTimestamp($timestamp) {
		return date('Y-m-d H:i:s',$timestamp);
	}

	public function toUnixTimstamp($date) {
	}

	public function escapeString($string) {
		return $this->dbObject->escapeString($string);
	}
}

?>