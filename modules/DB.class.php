<?php

class DB extends ModuleTemplate {
	protected $queriesCounter = 0;
	protected $dBObject = NULL;
	protected $curResult = NULL;
	protected $queryTime = 0;

	public function initializeMe() {
		$this->dBObject = new mysqli;

		@$this->dBObject->connect($this->getC('dbServer'),$this->getC('dbUser'),$this->getC('dbPassword'),$this->getC('dbName'));
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
		$starTime = Functions::getMicroTime();
		if(($this->curResult = $this->dBObject->query($query)) == FALSE) die('Database error: <b>'.$this->dBObject->error.'</b><br/>Query: <b>'.$query.'</b>');
		$this->queryTime += Functions::getMicroTime()-$starTime;
		$this->queriesCounter++;
	}

	public function getQueryTime() {
		return $this->queryTime;
	}

	public function getInsertID() {
		return $this->dBObject->insert_id;
	}

	public function Raw2Array() {
		$temp = array();
		while($curRow = $this->curResult->fetch_array())
			$temp[] = $curRow;

		return $temp;
	}

	public function Raw2FVArray() {
		$temp = array();

		while(list($curValue) = $this->fetchArray())
			$temp[] = $curValue;

		return $temp;
	}

	public function fetchArray() {
		return $this->curResult->fetch_array();
	}

	public function getAffectedRows() {
		return $this->dBObject->affected_rows;
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
}

?>