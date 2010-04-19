<?php
/**
*
* Tritanium Bulletin Board 2 - viewsmilies.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');


$viewsmilies_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['viewsmilies']);

$smilies_data = cache_get_smilies_data();

$smilies_counter = count($smilies_data);

if($smilies_counter > 0) {
	for($i = 0; $i < $smilies_counter; $i++) {
		$akt_smiley = &$smilies_data[$i];

		$viewsmilies_tpl->blocks['smileyrow']->blocks['smileycol']->parse_code(FALSE,TRUE);
		if(($i+1) % $TCONFIG['smilies_settings']['viewsmilies_smilies_per_row'] == 0 && $i != $smilies_counter-1) {
			$viewsmilies_tpl->blocks['smileyrow']->parse_code(FALSE,TRUE);
			$viewsmilies_tpl->blocks['smileyrow']->blocks['smileycol']->reset_tpl();
		}
	}
	$viewsmilies_tpl->blocks['smileyrow']->parse_code(FALSE,TRUE);
}

include_once('pop_pheader.php');
$viewsmilies_tpl->parse_code(TRUE);
include_once('pop_ptail.php');

?>