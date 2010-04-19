<?php
/**
*
* Tritanium Bulletin Board 2 - viewsmilies.php
* version #2004-01-01-18-38-43
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$title_add[] = $lng['Smilies'];

$viewsmilies_tpl = new template;
$viewsmilies_tpl->load($template_path.'/'.$tpl_config['tpl_viewsmilies']);

$db->query("SELECT smiley_id,smiley_gfx,smiley_synonym FROM ".TBLPFX."smilies WHERE smiley_type='0' AND smiley_status='1'");
$db->raw2array();
$smilies_data = $db->array_data;

$smilies_counter = sizeof($smilies_data);

if($smilies_counter > 0) {
	for($i = 0; $i < $smilies_counter; $i++) {
		$akt_smiley = &$smilies_data[$i];

		$viewsmilies_tpl->blocks['smileyrow']->blocks['smileycol']->parse_code(FALSE,TRUE);
		if(($i+1) % $tpl_config['viewsmilies_smilies_per_row'] == 0 && $i != $smilies_counter-1) {
			$viewsmilies_tpl->blocks['smileyrow']->parse_code(FALSE,TRUE);
			$viewsmilies_tpl->blocks['smileyrow']->blocks['smileycol']->reset_tpl();
		}
	}
	$viewsmilies_tpl->blocks['smileyrow']->parse_code(FALSE,TRUE);
}
else $viewsmilies_tpl->unset_block('smileyrow');

include_once('pop_pheader.php');

$viewsmilies_tpl->parse_code(TRUE);

include_once('pop_ptail.php');

?>