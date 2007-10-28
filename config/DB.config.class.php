<?php

class DBConfig extends ConfigTemplate {
	protected $config = array(
		'dbType'=>'Mysql',
		'dbServer'=>'localhost',
		'dbUser'=>'root',
		'dbPassword'=>'root',
		'dbName'=>'tbb2test',
		'tablePrefix'=>'tbb2_'
	);
}

?>