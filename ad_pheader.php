<?php
/**
*
* Tritanium Bulletin Board 2 - ad_pheader.php
* Zeigt den HTML-Kopf der Administration an
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.de
*
**/

require_once('auth.php');

$ad_pheader_tpl = new template;
$ad_pheader_tpl->load($template_path.'/'.$tpl_config['tpl_ad_pheader']);

$nav_links = array("<a href=\"administration.php?$MYSID\">".$lng['Overview'].'</a>');
$nav_links[] = "<a href=\"administration.php?faction=ad_forums&amp;$MYSID\">".$lng['Manage_forums'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_config&amp;$MYSID\">".$lng['Boardconfig'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_templates&amp;$MYSID\">".$lng['Manage_templates'].'</a>';

$nav_links[] = "<a href=\"index.php?$MYSID\">".$lng['Back_to_forumindex'].'</a>';

while(list(,$akt_nav_link) = each($nav_links)) {
	$ad_pheader_tpl->blocks['navrow']->values = array(
		'TEXT'=>$akt_nav_link
	);
	$ad_pheader_tpl->blocks['navrow']->parse_code(FALSE,TRUE);
}

$ad_pheader_tpl->values = array(
	'CONFIG_BOARD_NAME'=>$CONFIG['board_name'],
	'TITLE_ADD'=>$title_add,
	'MYSID'=>$MYSID,
	'LNG_NAVIGATION'=>$lng['Navigation'],
	'TEMPLATE_PATH'=>$template_path,
	'STYLE_PATH'=>$template_path.'/styles/'.$template_style
);

$ad_pheader_tpl->parse_code(TRUE);

?>