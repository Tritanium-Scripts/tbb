<!-- ForumIndex -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <thead>
{if Config::getInstance()->getCfgVal('news_position') == 1}{include file='News.tpl'}{/if}
  <tr>
   <th class="cellTitle" colspan="2" style="text-align:center;"><span class="fontTitleSmall">{Language::getInstance()->getString('forum')}</span></th>
   <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{Language::getInstance()->getString('topics')}</span></th>
   <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{Language::getInstance()->getString('posts')}</span></th>
   <th class="cellTitle" style="text-align:center; width:28%;"><span class="fontTitleSmall">{Language::getInstance()->getString('last_post')}</span></th>
   <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{Language::getInstance()->getString('moderators')}</span></th>
{plugin_hook hook=PlugIns::HOOK_TPL_FORUM_FORUMS_TABLE_HEAD}
  </tr>
 </thead>
{if Config::getInstance()->getCfgVal('news_position') == 2}{include file='News.tpl'}{/if}
{foreach $cats as $curCat}
{* 0:id - 1:name *}
{if Config::getInstance()->getCfgVal('show_kats')} <tr><td class="cellCat" colspan="6"><span class="fontCat"><img src="{Template::getInstance()->getTplDir()}images/minus.gif" alt="" /> {$curCat[1]}</span></td></tr>{/if}
{foreach $forums as $curForum}
{if $curForum.catID == $curCat[0]}
 <tr onmouseover="setRowCellsClass(this, 'cellHighlight');" onmouseout="restoreRowCellsClass(this);">
  <td class="cellAlt" style="text-align:center;"><img src="{Template::getInstance()->getTplDir()}images/{if !$curForum.isNewPost}no_{/if}new_post.gif" alt="" /></td>
  <td class="cellStd">{if !empty($curForum.forumImage)}<a href="{$smarty.const.INDEXFILE}?mode=viewforum&amp;forum_id={$curForum.forumID}{$smarty.const.SID_AMPER}"><img class="imageIcon" src="{$curForum.forumImage}" alt="" /></a>{/if}<span class="forumLink"><a class="forumLink" href="{$smarty.const.INDEXFILE}?mode=viewforum&amp;forum_id={$curForum.forumID}{$smarty.const.SID_AMPER}">{$curForum.forumTitle}</a></span><br /><span class="fontSmall">{$curForum.forumDescr}</span></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontSmall">{$curForum.forumTopics}</span></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontSmall">{$curForum.forumPosts}</span></td>
  <td class="cellStd" style="text-align:center;"><div class="fontSmall">{$curForum.lastPost}</div></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontSmall">{if is_array($curForum.mods)}{', '|implode:$curForum.mods}{else}{$curForum.mods}{/if}</span></td>
{plugin_hook hook=PlugIns::HOOK_TPL_FORUM_FORUMS_TABLE_BODY}
 </tr>
{/if}
{foreachelse}
 <tr><td class="cellStd" colspan="6" style="font-weight:bold; text-align:center;"><span class="fontNorm">{Language::getInstance()->getString('no_forum_available')}</span></td></tr>
{/foreach}
{foreachelse}
 <tr><td class="cellStd" colspan="6" style="font-weight:bold; text-align:center;"><span class="fontNorm">{Language::getInstance()->getString('no_cat_available')}</span></td></tr>
{/foreach}
</table>

{if Config::getInstance()->getCfgVal('show_lposts') >= 1}<br />
<!-- NewestPosts -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('newest_posts')}</span><a href="{$smarty.const.INDEXFILE}?faction=rssFeed" style="float:right;"><img src="{Template::getInstance()->getTplDir()}images/feed.png" alt="" /></a></th></tr>
 <tr><td class="cellStd"><span class="fontSmall">{if !empty($newestPosts)}{'<br />'|implode:$newestPosts}{else}{Language::getInstance()->getString('no_newest_posts')}{/if}{plugin_hook hook=PlugIns::HOOK_TPL_FORUM_NEWEST_POSTS}</span></td></tr>
</table>{/if}

{if Config::getInstance()->getCfgVal('show_board_stats') == 1}<br />
<!-- BoardStatistics -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('board_statistics')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontSmall">{Language::getInstance()->getString('registered_members_colon')} {$memberCounter}<br />{Language::getInstance()->getString('newest_member_colon')} {$newestMember}<br />{Language::getInstance()->getString('total_amount_of_topics_posts_colon')} {$topicCounter}/{$postCounter}{plugin_hook hook=PlugIns::HOOK_TPL_FORUM_BOARD_STATS}</span></td></tr>
</table>{/if}

{if Config::getInstance()->getCfgVal('wio') == 1 || Auth::getInstance()->isLoggedIn() && Config::getInstance()->getCfgVal('wio') == 2}
{$wioUser=WhoIsOnline::getInstance()->getUserWIO()}
{* 0:guests - 1:ghosts - 2:memberProfiles - 3:bots *}
<br />
<!-- WIO -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('who_is_online')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontSmall">{Config::getInstance()->getCfgVal('wio_timeout')|string_format:Language::getInstance()->getString('in_last_x_min_were_active_colon')}<br />
  {if empty($wioUser[2])}{Language::getInstance()->getString('no_members')}{else}{Language::getInstance()->getString('members_colon')} {', '|implode:$wioUser[2]}{/if}<br />
  {if $wioUser[1] == 0}{Language::getInstance()->getString('no_ghosts')}{elseif $wioUser[1] == 1}{Language::getInstance()->getString('one_ghost')}{else}{$wioUser[1]|string_format:Language::getInstance()->getString('x_ghosts')}{/if}<br />
  {if $wioUser[0] == 0}{Language::getInstance()->getString('no_guests')}{elseif $wioUser[0] == 1}{Language::getInstance()->getString('one_guest')}{else}{$wioUser[0]|string_format:Language::getInstance()->getString('x_guests')}{/if}<br />
  {if $wioUser[3] == 0}{Language::getInstance()->getString('no_bots')}{elseif $wioUser[3] == 1}{Language::getInstance()->getString('one_bot')}{else}{$wioUser[3]|string_format:Language::getInstance()->getString('x_bots')}{/if}<br />{plugin_hook hook=PlugIns::HOOK_TPL_FORUM_WIO_BOX}<br />
  <span style="font-weight:bold;">{Language::getInstance()->getString('legend_colon')}</span> <span{if Config::getInstance()->getCfgVal('wio_color_admin') != ''} style="color:{Config::getInstance()->getCfgVal('wio_color_admin')};"{/if}>{Language::getInstance()->getString('administrator')}</span> &ndash; <span{if Config::getInstance()->getCfgVal('wio_color_smod') != ''} style="color:{Config::getInstance()->getCfgVal('wio_color_smod')};"{/if}>{Language::getInstance()->getString('super_moderator')}</span> &ndash; <span{if Config::getInstance()->getCfgVal('wio_color_mod') != ''} style="color:{Config::getInstance()->getCfgVal('wio_color_mod')};"{/if}>{Language::getInstance()->getString('moderator')}</span> &ndash; <span{if Config::getInstance()->getCfgVal('wio_color_user') != ''} style="color:{Config::getInstance()->getCfgVal('wio_color_user')};"{/if}>{Language::getInstance()->getString('member')}</span> &ndash; <span{if Config::getInstance()->getCfgVal('wio_color_banned') != ''} style="color:{Config::getInstance()->getCfgVal('wio_color_banned')};"{/if}>{Language::getInstance()->getString('banned')}</span></span></td></tr>
</table>
<br />
<!-- WWO -->
{$wwoUser=WhoIsOnline::getInstance()->getUserWWO()}
{* 0:guests - 1:ghosts - 2:members - 3:0:memberProfiles - 3:1:isGhost - 4:bots *}
{$record=WhoIsOnline::getInstance()->getRecord()}
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('who_was_online')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontSmall">{Language::getInstance()->getString('today_were_here_colon')}<br />
  {foreach $wwoUser[3] as $curWWOUser}{if $curWWOUser[1]}<img src="{Template::getInstance()->getTplDir()}images/ghost.png" alt="{Language::getInstance()->getString('browses_as_ghost')}" title="{Language::getInstance()->getString('browses_as_ghost')}" style="vertical-align:middle;" /> {/if}{$curWWOUser[0]}{if !$curWWOUser@last}, {/if}{foreachelse}{Language::getInstance()->getString('no_members')}{/foreach}<br />
  {sprintf(Language::getInstance()->getString('total_x_member_s_x_ghost_s_x_guest_s_and_x_bot_s'), $wwoUser[2], $wwoUser[1], $wwoUser[0], $wwoUser[4])}<br />{plugin_hook hook=PlugIns::HOOK_TPL_FORUM_WWO_BOX}<br />
  <b>{Language::getInstance()->getString('record_colon')}</b> {sprintf(Language::getInstance()->getString('x_members_on_x'), $record[0], $record[1])}</span></td></tr>
</table>{/if}