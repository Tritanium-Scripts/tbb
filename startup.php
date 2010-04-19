<?php
/**
*
* Tritanium Bulletin Board 2 - startup.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

error_reporting(E_ALL);
set_magic_quotes_runtime(0);

require_once('version.php');
require_once('functions.php');
require_once('startup.php');
require_once('dbconfig.php');
require_once('db/'.$CONFIG['dbfunc_path'].'/functions.php');

$CONFIG = array();
$CACHE = array();
$STATS = array(
	'query_counter'=>0,
	'start_time'=>0,
	'end_time'=>0,
	'gzip_status'=>0
);

get_config_data();

if(get_magic_quotes_gpc() == 0) {
	array_addslashes($_POST);
}

$mtime = explode(" ",microtime());
$STATS['start_time'] = $mtime[1] + $mtime[0];

$html_trans_table = get_html_translation_table(HTML_ENTITIES);
unset($html_trans_table[chr(160)]);
unset($html_trans_table['<']);
unset($html_trans_table['>']);
unset($html_trans_table['"']);
unset($html_trans_table['&']);

$html_schars_table = array(
	'&'=>'$amp;',
	'"'=>'&quot;',
	'<'=>'&lt',
	'>'=>'&gt'
);

if($CONFIG['enable_gzip'] == 1) {
	if(ini_get('zlib.output_compression') != 1 && ini_get('output_handler') != 'ob_gzhandler')
		@ob_start('ob_gzhandler');
	$STATS['gzip_status'] = 1;
}

?>
