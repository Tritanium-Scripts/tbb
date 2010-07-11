<!-- ForumIndex -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
{if $modules.Config->getCfgVal('news_position') == 1}{include file='News.tpl'}{/if}
 <tr class="thsmall">
  <th class="thsmall" colspan="2"><span class="thsmall">{$modules.Language->getString('forum')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('topics')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('postings')}</span></th>
  <th class="thsmall" style="width:28%;"><span class="thsmall">{$modules.Language->getString('last_post')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('moderators')}</span></th>
 </tr>
{if $modules.Config->getCfgVal('news_position') == 2}{include file='News.tpl'}{/if}
{foreach $cats as $curCat}
{* 0:id - 1:name *}
{if $modules.Config->getCfgVal('show_kats')} <tr><td class="kat" colspan="6"><span class="kat">{$curCat[1]}</span></td></tr>{/if}
{foreach from=$forums item=curForum name=forums}
{* 0:id - 1:name - 2:descr - 3:topics - 4:postings - 5:catID - 6:newPost - 7:lastPost - 8:mods *}
{if $curForum[5] == $curCat[0]}
 <tr>
  <td class="td1"><img src="{$modules.Template->getTplDir()}images/{if !$curForum[6]}no_{/if}new_post.gif" alt="" /></td>
  <td class="td2"><span class="forumlink"><a class="forumlink" href="{$smarty.const.INDEXFILE}?mode=viewforum&amp;forum_id={$curForum[0]}{$smarty.const.SID_AMPER}">{$curForum[1]}</a></span><br /><span class="small">{$curForum[2]}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm">{$curForum[3]}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm">{$curForum[4]}</span></td>
  <td class="td1" style="text-align:center;"><span class="small">{$curForum[7]}</span></td>
  <td class="td2" style="text-align:center;"><span class="small">{$curForum[8]}</span></td>
 </tr>
<?php unset($forums[$smarty.foreach.forums.index]); ?>
{/if}
{foreachelse}
 <tr><td class="td1" colspan="6" style="font-weight:bold; text-align:center;"><span class="norm">{$modules.Language->getString('no_forum_available')}</span></td></tr>
{/foreach}
{foreachelse}
 <tr><td class="td1" colspan="6" style="font-weight:bold; text-align:center;"><span class="norm">{$modules.Language->getString('no_cat_available')}</span></td></tr>
{/foreach}
</table>

{if $modules.Config->getCfgVal('wio') == 1}
{assign var="wioUser" value=$modules.WhoIsOnline->getUserWIO()}
{* 0:guests - 1:ghosts - 2:memberProfiles *}
<br />
<!-- WIO -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('who_is_online')}</span></th></tr>
 <tr><td class="td1"><span class="small">{$modules.Config->getCfgVal('wio_timeout')|string_format:$modules.Language->getString('in_last_x_min_were_active')}<br />
  {if empty($wioUser[2])}{$modules.Language->getString('no_members')}{else}{', '|implode:$wioUser[2]}{/if}<br />
  {if $wioUser[1] == 0}{$modules.Language->getString('no_ghosts')}{elseif $wioUser[1] == 1}{$modules.Language->getString('one_ghost')}{else}{$wioUser[1]|string_format:$modules.Language->getString('x_ghosts')}{/if}<br />
  {if $wioUser[0] == 0}{$modules.Language->getString('no_guests')}{elseif $wioUser[0] == 1}{$modules.Language->getString('one_guest')}{else}{$wioUser[0]|string_format:$modules.Language->getString('x_guests')}{/if}<br /><br />
  <b>{$modules.Language->getString('legend')}</b> <span{if $modules.Config->getCfgVal('wio_color_admin') != ''} style="color:{$modules.Config->getCfgVal('wio_color_admin')}"{/if}>{$modules.Language->getString('administrator')}</span> &ndash; <span{if $modules.Config->getCfgVal('wio_color_smod') != ''} style="color:{$modules.Config->getCfgVal('wio_color_smod')}"{/if}>{$modules.Language->getString('super_moderator')}</span> &ndash; <span{if $modules.Config->getCfgVal('wio_color_mod') != ''} style="color:{$modules.Config->getCfgVal('wio_color_mod')}"{/if}>{$modules.Language->getString('moderator')}</span> &ndash; <span{if $modules.Config->getCfgVal('wio_color_member') != ''} style="color:{$modules.Config->getCfgVal('wio_color_member')}"{/if}>{$modules.Language->getString('member')}</span> &ndash; <span{if $modules.Config->getCfgVal('wio_color_banned') != ''} style="color:{$modules.Config->getCfgVal('wio_color_banned')}"{/if}>{$modules.Language->getString('banned')}</span></span></td></tr>
<!-- WWO -->
{assign var="wwoUser" value=$modules.WhoIsOnline->getUserWWO()}
{* 0:guests - 1:ghosts - 2:members - 3:0:memberProfiles - 3:1:isGhost *}
{assign var="record" value=$modules.WhoIsOnline->getRecord()}
{* {$modules.Language->getString('who_was_online')} *}
 <tr><td class="td2"><span class="small">{$modules.Language->getString('today_were_here')}<br />
  {foreach from=$wwoUser[3] item=curWWOUser name=wwoBox}{if $curWWOUser[1]}<img src="{$modules.Template->getTplDir()}images/ghost.png" alt="{$modules.Language->getString('browses_as_ghost')}" title="{$modules.Language->getString('browses_as_ghost')}" style="vertical-align:middle;" /> {/if}{$curWWOUser[0]}{if !$smarty.foreach.wwoBox.last}, {/if}{foreachelse}{$modules.Language->getString('no_members')}{/foreach}<br />
  {if $wwoUser[2] == 1 && $wwoUser[1] != 1 && $wwoUser[0] != 1}{sprintf($modules.Language->getString('total_one_member_x_ghosts_and_x_guests'), $wwoUser[1], $wwoUser[0])}
  {elseif $wwoUser[2] != 1 && $wwoUser[1] == 1 && $wwoUser[0] != 1}{sprintf($modules.Language->getString('total_x_member_one_ghost_and_x_guests'), $wwoUser[2], $wwoUser[0])}
  {elseif $wwoUser[2] != 1 && $wwoUser[1] != 1 && $wwoUser[0] == 1}{sprintf($modules.Language->getString('total_x_member_x_ghosts_and_one_guest'), $wwoUser[2], $wwoUser[1])}
  {else}{sprintf($modules.Language->getString('total_x_members_x_ghosts_and_x_guests'), $wwoUser[2], $wwoUser[1], $wwoUser[0])}{/if}<br /><br />
  <b>{$modules.Language->getString('record')}</b> {sprintf($modules.Language->getString('x_members_on_x'), $record[0], $record[1])}</span></td></tr>
</table>
{/if}

{if $modules.Config->getCfgVal('show_board_stats') == 1}
<br />
<!-- BoardStatistics -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('board_statistics')}</span></th></tr>
 <tr><td class="td1"><span class="small">{$modules.Language->getString('registered_members')} {$memberCounter}<br />{$modules.Language->getString('newest_member')} {$newestMember}<br />{$modules.Language->getString('total_amount_of_topics_posts')} {$topicCounter}/{$postCounter}</span></td></tr>
</table>
{/if}

{if $modules.Config->getCfgVal('show_lposts') == 1}
<br />
<!-- NewestPosts -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('newest_posts')}</span></th></tr>
 <tr><td class="td1"><span class="small">{if !empty($newestPosts)}{'<br />'|implode:$newestPosts}{else}{$modules.Language->getString('no_newest_posts')}{/if}</span></td></tr>
</table>
{/if}