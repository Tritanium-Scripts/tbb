<?php
/**
*
* Tritanium Bulletin Board 2 - ad_pheader.php
* version #2004-11-15-20-38-18
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

create_header_title();

$ad_pheader_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_pheader']);

$nav_links = array("<a href=\"administration.php?$MYSID\">".$lng['Overview'].'</a>');
$nav_links[] = "<a href=\"administration.php?faction=ad_users&amp;$MYSID\">".$lng['Manage_users'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_profile&amp;$MYSID\">".$lng['Manage_profile_fields'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_forums&amp;$MYSID\">".$lng['Manage_forums'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_smilies&amp;$MYSID\">".$lng['Manage_smilies'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_config&amp;$MYSID\">".$lng['Boardconfig'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_templates&amp;$MYSID\">".$lng['Manage_templates'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_groups&amp;$MYSID\">".$lng['Manage_groups'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_ranks&amp;$MYSID\">".$lng['Manage_ranks'].'</a>';
$nav_links[] = "<a href=\"administration.php?faction=ad_avatars&amp;$MYSID\">".$lng['Manage_avatars'].'</a>';

$nav_links[] = '&nbsp;';
$nav_links[] = "<a href=\"index.php?$MYSID\">".$lng['Back_to_forumindex'].'</a>';

while(list(,$akt_nav_link) = each($nav_links)) {
	$ad_pheader_tpl->blocks['navrow']->parse_code(FALSE,TRUE);
}

$ad_pheader_tpl->parse_code(TRUE);

?>