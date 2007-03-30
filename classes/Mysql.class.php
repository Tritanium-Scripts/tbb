<?php

class DB {
	protected $QueriesCounter = 0;
	protected $DBObject = NULL;
	protected $CurResult = NULL;

	public function connect($DBServer,$DBUser,$DBPassword) {
		$this->DBObject = new mysqli($DBServer,$DBUser,$DBPassword);
	}

	public function selectDB($DBName) {
		$this->DBObject->select_db($DBName);
	}

	public function query($Query) {
		$this->CurResult = $this->DBObject->query($Query);
		$this->QueriesCounter++;
	}

	public function Raw2Array() {
		$Temp = array();
		while($CurRow = $this->CurResult->fetch_array())
			$Temp[] = $CurRow;

		return $Temp;
	}
}

?>