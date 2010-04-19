<?php
/**
*
* Tritanium Bulletin Board 2 - viewsmilies.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$title_add .= ' &#187; '.$lng['Smilies'];

$viewsmilies_tpl = new template;
$viewsmilies_tpl->load($template_path.'/'.$tpl_config['tpl_viewsmilies']);

$smilies_data = get_smilies_data(array('smiley_type'=>0,'smiley_status'=>1));

$smilies_counter = sizeof($smilies_data);

for($i = 0; $i < $smilies_counter; $i++) {
	$akt_smiley = &$smilies_data[$i];

	$viewsmilies_tpl->blocks['smileyrow']->blocks['smileycol']->parse_code(FALSE,TRUE);
	if(($i+1) % $tpl_config['viewsmilies_smilies_per_row'] == 0 && $i != $smilies_counter-1) {
		$viewsmilies_tpl->blocks['smileyrow']->parse_code(FALSE,TRUE);
		$viewsmilies_tpl->blocks['smileyrow']->blocks['smileycol']->reset_tpl();
	}
}
$viewsmilies_tpl->blocks['smileyrow']->parse_code(FALSE,TRUE);

include_once('pop_pheader.php');

$viewsmilies_tpl->parse_code(TRUE);

include_once('pop_ptail.php');

?>