<!-- ForumIndex -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
{if Config::getInstance()->getCfgVal('news_position') == 1}{include file='News.tpl'}{/if}
 <tr class="thsmall">
  <th class="thsmall" colspan="2"><span class="thsmall">{Language::getInstance()->getString('forum')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('topics')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('posts')}</span></th>
  <th class="thsmall" style="width:28%;"><span class="thsmall">{Language::getInstance()->getString('last_post')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('moderators')}</span></th>
{plugin_hook hook=PlugIns::HOOK_TPL_FORUM_FORUMS_TABLE_HEAD}
 </tr>
{if Config::getInstance()->getCfgVal('news_position') == 2}{include file='News.tpl'}{/if}
{foreach $cats as $curCat}
{* 0:id - 1:name *}
{if Config::getInstance()->getCfgVal('show_kats')} <tr><td class="kat" colspan="6"><span class="kat">{$curCat[1]}</span></td></tr>{/if}
{foreach $forums as $curForum}
{if $curForum.catID == $curCat[0]}
 <tr>
  <td class="td1" style="text-align:center;"><img src="{Template::getInstance()->getTplDir()}images/{if !$curForum.isNewPost}no_{/if}new_post.gif" alt="" /></td>
  <td class="td2">{if !empty($curForum.forumImage)}<a href="{$smarty.const.INDEXFILE}?mode=viewforum&amp;forum_id={$curForum.forumID}{$smarty.const.SID_AMPER}"><img src="{$curForum.forumImage}" alt="" style="float:left; margin-right:4px;" /></a>{/if}<span class="forumlink"><a class="forumlink" href="{$smarty.const.INDEXFILE}?mode=viewforum&amp;forum_id={$curForum.forumID}{$smarty.const.SID_AMPER}">{$curForum.forumTitle}</a></span><br /><span class="small">{$curForum.forumDescr}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm">{$curForum.forumTopics}</span></td>
  <td class="td2" style="text-align:center;"><span class="norm">{$curForum.forumPosts}</span></td>
  <td class="td1 small" style="text-align:center;">{$curForum.lastPost}</td>
  <td class="td2" style="text-align:center;"><span class="small">{if is_array($curForum.mods)}{', '|implode:$curForum.mods}{else}{$curForum.mods}{/if}</span></td>
{plugin_hook hook=PlugIns::HOOK_TPL_FORUM_FORUMS_TABLE_BODY}
 </tr>
{/if}
{foreachelse}
 <tr><td class="td1" colspan="6" style="font-weight:bold; text-align:center;"><span class="norm">{Language::getInstance()->getString('no_forum_available')}</span></td></tr>
{/foreach}
{foreachelse}
 <tr><td class="td1" colspan="6" style="font-weight:bold; text-align:center;"><span class="norm">{Language::getInstance()->getString('no_cat_available')}</span></td></tr>
{/foreach}
</table>

{if Config::getInstance()->getCfgVal('wio') == 1 || Auth::getInstance()->isLoggedIn() && Config::getInstance()->getCfgVal('wio') == 2}
{$wioUser=WhoIsOnline::getInstance()->getUserWIO()}
{* 0:guests - 1:ghosts - 2:memberProfiles - 3:bots *}
<br />
<!-- WIO -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('who_is_online')}</span></th></tr>
 <tr><td class="td1"><span class="small">{Config::getInstance()->getCfgVal('wio_timeout')|string_format:Language::getInstance()->getString('in_last_x_min_were_active_colon')}<br />
  {if empty($wioUser[2])}{Language::getInstance()->getString('no_members')}{else}{Language::getInstance()->getString('members_colon')} {', '|implode:$wioUser[2]}{/if}<br />
  {if $wioUser[1] == 0}{Language::getInstance()->getString('no_ghosts')}{elseif $wioUser[1] == 1}{Language::getInstance()->getString('one_ghost')}{else}{$wioUser[1]|string_format:Language::getInstance()->getString('x_ghosts')}{/if}<br />
  {if $wioUser[0] == 0}{Language::getInstance()->getString('no_guests')}{elseif $wioUser[0] == 1}{Language::getInstance()->getString('one_guest')}{else}{$wioUser[0]|string_format:Language::getInstance()->getString('x_guests')}{/if}<br />
  {if $wioUser[3] == 0}{Language::getInstance()->getString('no_bots')}{elseif $wioUser[3] == 1}{Language::getInstance()->getString('one_bot')}{else}{$wioUser[3]|string_format:Language::getInstance()->getString('x_bots')}{/if}<br />{plugin_hook hook=PlugIns::HOOK_TPL_FORUM_WIO_BOX}<br />
  <span style="font-weight:bold;">{Language::getInstance()->getString('legend_colon')}</span> <span{if Config::getInstance()->getCfgVal('wio_color_admin') != ''} style="color:{Config::getInstance()->getCfgVal('wio_color_admin')};"{/if}>{Language::getInstance()->getString('administrator')}</span> &ndash; <span{if Config::getInstance()->getCfgVal('wio_color_smod') != ''} style="color:{Config::getInstance()->getCfgVal('wio_color_smod')};"{/if}>{Language::getInstance()->getString('super_moderator')}</span> &ndash; <span{if Config::getInstance()->getCfgVal('wio_color_mod') != ''} style="color:{Config::getInstance()->getCfgVal('wio_color_mod')};"{/if}>{Language::getInstance()->getString('moderator')}</span> &ndash; <span{if Config::getInstance()->getCfgVal('wio_color_user') != ''} style="color:{Config::getInstance()->getCfgVal('wio_color_user')};"{/if}>{Language::getInstance()->getString('member')}</span> &ndash; <span{if Config::getInstance()->getCfgVal('wio_color_banned') != ''} style="color:{Config::getInstance()->getCfgVal('wio_color_banned')};"{/if}>{Language::getInstance()->getString('banned')}</span></span></td></tr>
<!-- WWO -->
{$wwoUser=WhoIsOnline::getInstance()->getUserWWO()}
{* 0:guests - 1:ghosts - 2:members - 3:0:memberProfiles - 3:1:isGhost - 4:bots *}
{$record=WhoIsOnline::getInstance()->getRecord()}
{* {Language::getInstance()->getString('who_was_online')} *}
 <tr><td class="td2"><span class="small">{Language::getInstance()->getString('today_were_here_colon')}<br />
  {foreach $wwoUser[3] as $curWWOUser}{if $curWWOUser[1]}<img src="{Template::getInstance()->getTplDir()}images/ghost.png" alt="{Language::getInstance()->getString('browses_as_ghost')}" title="{Language::getInstance()->getString('browses_as_ghost')}" style="vertical-align:middle;" /> {/if}{$curWWOUser[0]}{if !$curWWOUser@last}, {/if}{foreachelse}{Language::getInstance()->getString('no_members')}{/foreach}<br />
  {sprintf(Language::getInstance()->getString('total_x_member_s_x_ghost_s_x_guest_s_and_x_bot_s'), $wwoUser[2], $wwoUser[1], $wwoUser[0], $wwoUser[4])}<br />{plugin_hook hook=PlugIns::HOOK_TPL_FORUM_WWO_BOX}<br />
  <b>{Language::getInstance()->getString('record_colon')}</b> {sprintf(Language::getInstance()->getString('x_members_on_x'), $record[0], $record[1])}</span></td></tr>
</table>
{/if}

{if Config::getInstance()->getCfgVal('show_board_stats') == 1}
<br />
<!-- BoardStatistics -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('board_statistics')}</span></th></tr>
 <tr><td class="td1"><span class="small">{Language::getInstance()->getString('registered_members_colon')} {$memberCounter}<br />{Language::getInstance()->getString('newest_member_colon')} {$newestMember}<br />{Language::getInstance()->getString('total_amount_of_topics_posts_colon')} {$topicCounter}/{$postCounter}{plugin_hook hook=PlugIns::HOOK_TPL_FORUM_BOARD_STATS}</span></td></tr>
</table>
{/if}

{if Config::getInstance()->getCfgVal('show_lposts') >= 1}
<br />
<!-- NewestPosts -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('newest_posts')}</span><a href="{$smarty.const.INDEXFILE}?faction=rssFeed" style="float:right;"><img src="{Template::getInstance()->getTplDir()}images/rss.gif" alt="" /></a></th></tr>
 <tr><td class="td1"><span class="small">{if !empty($newestPosts)}{'<br />'|implode:$newestPosts}{else}{Language::getInstance()->getString('no_newest_posts')}{/if}{plugin_hook hook=PlugIns::HOOK_TPL_FORUM_NEWEST_POSTS}</span></td></tr>
</table>
{/if}