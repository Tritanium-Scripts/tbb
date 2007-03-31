<?php

class DB extends ModuleTemplate {
	protected $QueriesCounter = 0;
	protected $DBObject = NULL;
	protected $CurResult = NULL;
	protected $QueryTime = 0;

	public function initializeMe() {
		$this->DBObject = new mysqli;

		@$this->DBObject->connect($this->getC('dbServer'),$this->getC('dbUser'),$this->getC('dbPassword'),$this->getC('dbName'));
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

	public function query($Query) {
		$StarTime = Functions::getMicroTime();
		if(($this->CurResult = $this->DBObject->query($Query)) == FALSE) die('Database error: <b>'.$this->DBObject->error.'</b><br/>Query: <b>'.$Query.'</b>');
		$this->QueryTime += Functions::getMicroTime()-$StarTime;
		$this->QueriesCounter++;
	}

	public function getQueryTime() {
		return $this->QueryTime;
	}

	public function getInsertID() {
		return $this->DBObject->insert_id;
	}

	public function Raw2Array() {
		$Temp = array();
		while($CurRow = $this->CurResult->fetch_array())
			$Temp[] = $CurRow;

		return $Temp;
	}

	public function Raw2FVArray() {
		$Temp = array();

		while(list($curValue) = $this->fetchArray())
			$Temp[] = $curValue;

		return $Temp;
	}

	public function fetchArray() {
		return $this->CurResult->fetch_array();
	}

	public function getAffectedRows() {
		return $this->DBObject->affected_rows;
	}

	public function getTablePrefix() {
		return $this->getC('tablePrefix');
	}

	public function fromUnixTimestamp($Timestamp) {
		return date('Y-m-d H:i:s',$Timestamp);
	}

	public function toUnixTimstamp($Date) {
	}

	public function escapeString($String) {
		return mysql_escape_string($String);
	}
}

?>