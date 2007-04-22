<?php

class DB extends ModuleTemplate {
	protected $queriesCounter = 0;
	protected $dbObject = NULL;
	protected $curResult = NULL;
	protected $queryTime = 0;
	protected $destructFunctions = array();

	public function initializeMe() {
		$this->dbObject = new mysqli;

		@$this->dbObject->connect($this->getC('dbServer'),$this->getC('dbUser'),$this->getC('dbPassword'),$this->getC('dbName'));
		if(mysqli_connect_error() != '') die('Database error: <b>'.mysqli_connect_error().'</b>');

		define('TBLPFX',$this->getTablePrefix());
		$this->query("SET NAMES utf8");

		/*/$this->query("update tbb2_posts set PostText = REPLACE(PostText,'Ã¶','ö')");
		$this->query("update tbb2_posts set PostText = REPLACE(PostText,'Ã¤','ä')");
		$this->query("update tbb2_posts set PostText = REPLACE(PostText,'Ã¼','ü')");
		$this->query("update tbb2_posts set PostText = REPLACE(PostText,'ÃŸ','ß')");
		$this->query("update tbb2_posts set PostText = REPLACE(PostText,'Ã¶','ö')");
		$this->query("update tbb2_posts set PostText = REPLACE(PostText,'Ã¶','ö')");
		$this->query("update tbb2_posts set PostText = REPLACE(PostText,'Ã¶','ö')");


		$this->query("update tbb2_posts set PostTitle = REPLACE(PostTitle,'Ã¶','ö')");
		$this->query("update tbb2_posts set PostTitle = REPLACE(PostTitle,'Ã¤','ä')");
		$this->query("update tbb2_posts set PostTitle = REPLACE(PostTitle,'Ã¼','ü')");
		$this->query("update tbb2_posts set PostTitle = REPLACE(PostTitle,'ÃŸ','ß')");

		$this->query("update tbb2_forums set ForumName = REPLACE(ForumName,'Ã¶','ö')");
		$this->query("update tbb2_forums set ForumName = REPLACE(ForumName,'Ã¤','ä')");
		$this->query("update tbb2_forums set ForumName = REPLACE(ForumName,'Ã¼','ü')");
		$this->query("update tbb2_forums set ForumName = REPLACE(ForumName,'ÃŸ','ß')");
		$this->query("update tbb2_forums set ForumDescription = REPLACE(ForumDescription,'Ã¶','ö')");
		$this->query("update tbb2_forums set ForumDescription = REPLACE(ForumDescription,'Ã¤','ä')");
		$this->query("update tbb2_forums set ForumDescription = REPLACE(ForumDescription,'Ã¼','ü')");
		$this->query("update tbb2_forums set ForumDescription = REPLACE(ForumDescription,'ÃŸ','ß')");/**/
	}

	public function query($query) {
		$startTime = Functions::getMicroTime();
		if(!($this->curResult = $this->dbObject->query($query))) die('Database error: <b>'.$this->dbObject->error.'</b><br/>Query: <b>'.$query.'</b>');
		$this->queryTime += Functions::getMicroTime()-$startTime;
		$this->queriesCounter++;
	}

	public function getQueryTime() {
		return $this->queryTime;
	}

	public function getInsertID() {
		return $this->dbObject->insert_id;
	}

	public function raw2Array() {
		$temp = array();
		while($curRow = $this->curResult->fetch_array())
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
		return $this->curResult->fetch_array();
	}

	public function getAffectedRows() {
		return $this->dbObject->affected_rows;
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
		return mysql_escape_string($string);
	}

	public function registerDestructFunction($function) {
		$this->destructFunctions[] = $function;
	}

	public function __destruct() {
		foreach($this->destructFunctions AS $curFunc)
			call_user_func($curFunc);
	}
}

?>