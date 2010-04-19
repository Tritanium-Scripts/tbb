<?php
/**
*
* Tritanium Bulletin Board 2 - ad_pheader.php
* version #2003-09-17-17-03-24
* (c) 2003 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$ad_pheader_tpl = new template;
$ad_pheader_tpl->load($template_path.'/'.$tpl_config['tpl_ad_pheader']);

$nav_links = array("<a href=\"administration.php?$MYSID\">".$lng['Overview'].'</a>');
$nav_links[] = "<a href=\"administration.php?faction=ad_forums&amp;$MYSID\">".$lng['Manage_forums'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_smilies&amp;$MYSID\">".$lng['Manage_smilies'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_config&amp;$MYSID\">".$lng['Boardconfig'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_templates&amp;$MYSID\">".$lng['Manage_templates'].'</a>';

$nav_links[] = "<a href=\"index.php?$MYSID\">".$lng['Back_to_forumindex'].'</a>';

while(list(,$akt_nav_link) = each($nav_links)) {
	$ad_pheader_tpl->blocks['navrow']->parse_code(FALSE,TRUE);
}

$ad_pheader_tpl->parse_code(TRUE);

?>