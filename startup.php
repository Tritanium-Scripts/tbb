<?php
/**
*
* Tritanium Bulletin Board 2 - startup.php
* version #2004-01-01-18-38-43
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

define('SECURITY',TRUE);

error_reporting(E_ALL);
set_magic_quotes_runtime(0);

$STATS = array(
	'start_time'=>0,
	'end_time'=>0,
	'gzip_status'=>0,
	'new_pm'=>0
);

$CONFIG = array();

require_once('version.php');
require_once('functions.php');
require_once('functions_data.php');
require_once('dbconfig.php');

$STATS['start_time'] = get_mtime_counter();

switch($CONFIG['db_type']) {
	case 'mysql':
		include_once('db/mysql.class.php');
	break;
}

$db = new db;
if(!$db->connect($CONFIG['db_server'],$CONFIG['db_user'],$CONFIG['db_password'])) die('Error connecting to database server: '.$db->error());
if(!$db->select_db($CONFIG['db_name'])) die('Error selecting database: '.$db->error());

$db->query("SELECT * FROM ".TBLPFX."config");
while($akt_row = $db->fetch_array())
	$CONFIG[$akt_row['config_name']] = $akt_row['config_value'];


if(get_magic_quotes_gpc() == 0) {
	array_addslashes($_POST);
}

if($CONFIG['enable_gzip'] == 1) {
	if(ini_get('zlib.output_compression') != 1 && ini_get('output_handler') != 'ob_gzhandler')
		@ob_start('ob_gzhandler');
	$STATS['gzip_status'] = 1;
}

$title_add = array($CONFIG['board_name']);


?>
