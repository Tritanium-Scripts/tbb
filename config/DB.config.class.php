<?php
/**
 * @author Julian Backes <julian@tritanium-scripts.com>
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2003 - 2009, Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package tbb2
 */
class DBConfig extends ConfigTemplate {
	protected $config = array(
		'dbType'=>'mysql',
		'dbServer'=>'localhost',
		'dbUser'=>'root',
		'dbPassword'=>'',
		'dbName'=>'tbb2test',
		'tablePrefix'=>'tbb2_'
	);
}