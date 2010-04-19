<?php
/**
*
* Tritanium Bulletin Board 2 - startup.php
* Initialisiert einige Variablen
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
*
**/

set_magic_quotes_runtime(0);

$CACHE = array();

$CONFIG = array();

$STATS = array(
	'query_counter'=>0,
	'start_time'=>0,
	'end_time'=>0
);

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

?>
