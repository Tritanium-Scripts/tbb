<?php
/**
*
* Tritanium Bulletin Board 2 - pheader.php
* version #2005-05-02-18-17-06
* (c) 2003-2005 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

require_once('auth.php');

$HEADER_TITLE = $NAVBAR->getArea('left')->parseElements(FALSE);

$pheader_tpl = new template();

if(defined('IN_ADMINISTRATION') == TRUE) { // Der User befindet sich in der Administration
	$pheader_tpl->load($TEMPLATE_PATH.'/'.$TCONFIG['templates']['ad_pheader']);

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
		$pheader_tpl->blocks['navrow']->parse_code(FALSE,TRUE);
	}
}
else { // Der User befindet sich sich nicht in der Administration
	$pheader_tpl = new template($TEMPLATE_PATH.'/'.$TCONFIG['templates']['pheader']);

	if($CONFIG['board_logo'] != '') $board_banner = '<img src="'.$CONFIG['board_logo'].'" alt="'.$CONFIG['board_name'].'" />';
	else $board_banner = $CONFIG['board_name'];

	$welcome_text = ($USER_LOGGED_IN == 1) ? sprintf($LNG['welcome_logged_in'],$USER_DATA['user_nick']) : sprintf($LNG['welcome_not_logged_in'],$CONFIG['board_name']);
}
$pheader_tpl->parse_code(TRUE);

if(!defined('IN_ADMINISTRATION')) show_navbar();

if(defined('IN_EDITPROFILE') == TRUE) {
	$pheader_tpl->load($TEMPLATE_PATH.'/'.$TCONFIG['templates']['editprofile_header']);
	$pheader_tpl->parse_code(TRUE);
}

unset($pheader_tpl);

?>