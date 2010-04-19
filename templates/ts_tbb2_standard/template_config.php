<?php
/**
*
* Tritanium Bulletin Board 2 - templates/ts_tbb2_standard/template_config.php
* version #2004-03-07-20-21-33
* (c) 2003-2004 Tritanium Scripts - http://www.tritanium-scripts.com
*
**/

//
// Allgemeine Informationen ueber das Template
//
$tpl_config['template_name'] = 'TBB2 Standard'; // Name des Templates
$tpl_config['template_author'] = 'Tritanium Scripts'; // Autor des Templates
$tpl_config['template_author_url'] = 'http://www.tritanium-scripts.com'; // URL zum Autor
$tpl_config['template_author_comment'] = 'Offizielles Standardtemplate f&auml;r das Tritanium Bulletin Board 2 (c) 2003 Tritanium Scripts'; // Kurzer Kommentar vom Autor


//
// Standardstyle
//
$tpl_config['standard_style'] = 'ts_tbb2_standard.css';


//
// Informationen zum Farbenwechsel
//
$tpl_config['td1_class'] = 'td1'; // Klasse der ersten Farbe
$tpl_config['td2_class'] = 'td2'; // Klasse der zweiten Farbe
$tpl_config['akt_class'] = 'td1'; // Klasse, mit der beim Farbenwechsel angefangen wird


//
// Informationen zur Darstellung der Smilies und Beitragsgrafiken
//
$tpl_config['smiliesbox_smilies_per_row'] = 6; // Anzahl der Smilies pro Reihe
$tpl_config['smiliesbox_maximum_smilies'] = 24; // Maximale Anzahl an Smilies in der Kurzuebersicht z.B. beim Antworten
$tpl_config['viewsmilies_smilies_per_row'] = 10; // Anzahl der Smilies pro Reihe in der Gesamtuebersicht aller Smilies
$tpl_config['ppicsbox_ppics_per_row'] = 7; // Anzahl der Beitragsgrafiken pro Reihe z.B. beim Antworten


//
// Zeichen, um Textlinks zu trennen
//
$tpl_config['separation_char'] = ' | ';


//
// Name (und eventuell Verzeichnis) der benoetigten Bilder
//
$tpl_config['img_blank'] = 'images/blank.gif';
$tpl_config['img_delete_post'] = 'images/button_delete_post.gif';
$tpl_config['img_edit_post'] = 'images/button_edit_post.gif';
$tpl_config['img_forum_on'] = 'images/forum_on.gif';
$tpl_config['img_forum_off'] = 'images/forum_off.gif';
$tpl_config['img_minus'] = 'images/minus.gif';
$tpl_config['img_plus'] = 'images/plus.gif';
$tpl_config['img_post_new_reply'] = 'images/button_post_new_reply.gif';
$tpl_config['img_post_new_topic'] = 'images/button_post_new_topic.gif';
$tpl_config['img_quote_post'] = 'images/button_quote_post.gif';
$tpl_config['img_topic_on_open'] = 'images/topic_on_open.gif';
$tpl_config['img_topic_off_open'] = 'images/topic_off_open.gif';
$tpl_config['img_user_email'] = 'images/button_user_email.gif';
$tpl_config['img_user_hp'] = 'images/button_user_hp.gif';


//
// Namen (und eventuell Verzeichnis) der Templatedateien fuer die verschiedenen Bereiche
//
$tpl_config['tpl_activateaccount'] = 'activateaccount.tpl';
$tpl_config['tpl_ad_avatars_index'] = 'ad_avatars_index.tpl';
$tpl_config['tpl_ad_avatars_addavatar'] = 'ad_avatars_addavatar.tpl';
$tpl_config['tpl_ad_avatars_editavatar'] = 'ad_avatars_editavatar.tpl';
$tpl_config['tpl_ad_config'] = 'ad_config.tpl';
$tpl_config['tpl_ad_forums_addcat'] = 'ad_forums_addcat.tpl';
$tpl_config['tpl_ad_forums_addforum'] = 'ad_forums_addforum.tpl';
$tpl_config['tpl_ad_forums_addgroupright'] = 'ad_forums_addgroupright.tpl';
$tpl_config['tpl_ad_forums_adduserright'] = 'ad_forums_adduserright.tpl';
$tpl_config['tpl_ad_forums_overview'] = 'ad_forums_overview.tpl';
$tpl_config['tpl_ad_forums_editcat'] = 'ad_forums_editcat.tpl';
$tpl_config['tpl_ad_forums_editforum'] = 'ad_forums_editforum.tpl';
$tpl_config['tpl_ad_forums_editsrights'] = 'ad_forums_editsrights.tpl';
$tpl_config['tpl_ad_groups_addgroup'] = 'ad_groups_addgroup.tpl';
$tpl_config['tpl_ad_groups_editgroup'] = 'ad_groups_editgroup.tpl';
$tpl_config['tpl_ad_groups_managemembers'] = 'ad_groups_managemembers.tpl';
$tpl_config['tpl_ad_groups_overview'] = 'ad_groups_overview.tpl';
$tpl_config['tpl_ad_index'] = 'ad_index.tpl';
$tpl_config['tpl_ad_pheader'] = 'ad_pheader.tpl';
$tpl_config['tpl_ad_ptail'] = 'ad_ptail.tpl';
$tpl_config['tpl_ad_ranks_addrank'] = 'ad_ranks_addrank.tpl';
$tpl_config['tpl_ad_ranks_editrank'] = 'ad_ranks_editrank.tpl';
$tpl_config['tpl_ad_ranks_index'] = 'ad_ranks_index.tpl';
$tpl_config['tpl_ad_smilies_add'] = 'ad_smilies_add.tpl';
$tpl_config['tpl_ad_smilies_default'] = 'ad_smilies_default.tpl';
$tpl_config['tpl_ad_smilies_edit'] = 'ad_smilies_edit.tpl';
$tpl_config['tpl_ad_templates_default'] = 'ad_templates_default.tpl';
$tpl_config['tpl_bbcode_buttons'] = 'bbcode_buttons.tpl';
$tpl_config['tpl_bbcode_html'] = 'bbcode_html.tpl';
$tpl_config['tpl_editpost_edit'] = 'editpost_edit.tpl';
$tpl_config['tpl_editprofile_avatarresult'] = 'editprofile_avatarresult.tpl';
$tpl_config['tpl_editprofile_index'] = 'editprofile_index.tpl';
$tpl_config['tpl_editprofile_selectavatar'] = 'editprofile_selectavatar.tpl';
$tpl_config['tpl_editprofile_uploadavatar'] = 'editprofile_uploadavatar.tpl';
$tpl_config['tpl_edittopic_edit'] = 'edittopic_edit.tpl';
$tpl_config['tpl_edittopic_move'] = 'edittopic_move.tpl';
$tpl_config['tpl_forumindex'] = 'forumindex.tpl';
$tpl_config['tpl_login'] = 'login.tpl';
$tpl_config['tpl_message'] = 'message.tpl';
$tpl_config['tpl_navbar'] = 'navbar.tpl';
$tpl_config['tpl_pheader'] = 'pheader.tpl';
$tpl_config['tpl_pop_pheader'] = 'pop_pheader.tpl';
$tpl_config['tpl_pop_ptail'] = 'pop_ptail.tpl';
$tpl_config['tpl_postreply'] = 'postreply.tpl';
$tpl_config['tpl_posttopic'] = 'posttopic.tpl';
$tpl_config['tpl_ptail'] = 'ptail.tpl';
$tpl_config['tpl_pms_addfolder'] = 'pms_addfolder.tpl';
$tpl_config['tpl_pms_newpm'] = 'pms_newpm.tpl';
$tpl_config['tpl_pms_newpmreceived'] = 'pms_newpmreceived.tpl';
$tpl_config['tpl_pms_overview'] = 'pms_overview.tpl';
$tpl_config['tpl_pms_viewfolder'] = 'pms_viewfolder.tpl';
$tpl_config['tpl_pms_viewpm'] = 'pms_viewpm.tpl';
$tpl_config['tpl_ppicsbox'] = 'ppicsbox.tpl';
$tpl_config['tpl_register_boardrules'] = 'register_boardrules.tpl';
$tpl_config['tpl_register_form'] = 'register_form.tpl';
$tpl_config['tpl_smiliesbox'] = 'smiliesbox.tpl';
$tpl_config['tpl_search_form'] = 'search_form.tpl';
$tpl_config['tpl_search_viewresults_posts'] = 'search_viewresults_posts.tpl';
$tpl_config['tpl_search_viewresults_topics'] = 'search_viewresults_topics.tpl';
$tpl_config['tpl_viewcat'] = 'viewcat.tpl';
$tpl_config['tpl_viewforum'] = 'viewforum.tpl';
$tpl_config['tpl_viewsmilies'] = 'viewsmilies.tpl';
$tpl_config['tpl_viewtopic'] = 'viewtopic.tpl';
$tpl_config['tpl_viewtopic_poll_results'] = 'viewtopic_poll_results.tpl';
$tpl_config['tpl_viewtopic_poll_voting'] = 'viewtopic_poll_voting.tpl';
$tpl_config['tpl_viewwio'] = 'viewwio.tpl';


?>