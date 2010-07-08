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
{foreach from=$cats item=curCat}
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
{assign var="guests" value=$modules.WhoIsOnline->getGuests()}
<br />
<!-- WIO -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><td class="thnorm"><span class="thnorm">{$modules.Language->getString('who_is_online')}</span></td></tr>
 <tr><td class="td1"><span class="small">{$modules.Config->getCfgVal('wio_timeout')|string_format:$modules.Language->getString('in_last_x_min_were_active')}<br />
{foreach $modules.WhoIsOnline->getMembers() as $curMember}{$curMember}, {foreachelse}{$modules.Language->getString('no_members')}{/foreach}<br />
{if $guests == 0}{$modules.Language->getString('no_guests')}{elseif $guests == 1}{$modules.Language->getString('one_guest')}{else}{$guests|string_format:$modules.Language->getString('x_guests')}{/if}</td></tr>
</table>
{/if}