<?php
/**
*
* Tritanium Bulletin Board 2 - ad_pheader.php
* version #2005-01-20-20-45-11
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

create_header_title();

$ad_pheader_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_pheader']);

$nav_links = array("<a href=\"administration.php?$MYSID\">".$LNG['Overview'].'</a>');
$nav_links[] = "<a href=\"administration.php?faction=ad_users&amp;$MYSID\">".$LNG['Manage_users'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_profile&amp;$MYSID\">".$LNG['Manage_profile_fields'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_forums&amp;$MYSID\">".$LNG['Manage_forums'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_smilies&amp;$MYSID\">".$LNG['Manage_smilies'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_config&amp;$MYSID\">".$LNG['Boardconfig'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_templates&amp;$MYSID\">".$LNG['Manage_templates'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_groups&amp;$MYSID\">".$LNG['Manage_groups'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_ranks&amp;$MYSID\">".$LNG['Manage_ranks'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_avatars&amp;$MYSID\">".$LNG['Manage_avatars'].'</a>';

$nav_links[] = '&nbsp;';
$nav_links[] = "<a href=\"index.php?$MYSID\">".$LNG['Back_to_forumindex'].'</a>';

while(list(,$akt_nav_link) = each($nav_links)) {
	$ad_pheader_tpl->blocks['navrow']->parse_code(FALSE,TRUE);
}

$ad_pheader_tpl->parse_code(TRUE);

?>