<?php

class DBConfig {
	protected $DBType = 'MySql';
	protected $DBServer = 'localhost';
	protected $DBUser = 'root';
	protected $DBPassword = '';
	protected $DBName = 'tbb2test';

	public function getDBType() {
		return $this->DBType;
	}

	public function getDBServer() {
		return $this->DBServer;
	}

	public function getDBUser() {
		return $this->DBUser;
	}

	public function getDBPassword() {
		return $this->DBPassword;
	}

	public function getDBName() {
		return $this->DBName;
	}
}

?>