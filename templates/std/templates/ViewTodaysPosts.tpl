<!-- ViewTodaysPosts -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('topic')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('author')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('date')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('forum')}</span></th>
 </tr>
{foreach $todaysPosts as $curTodaysPost}
 <tr>
  <td class="td1" style="width:50%;"><span class="norm"><img src="{$curTodaysPost.tSmiley}" alt="" /> {$curTodaysPost.topic}</span></td>
  <td class="td2"><span class="norm">{$curTodaysPost.author}</span></td>
  <td class="td1" style="text-align:center;"><span class="small">{$curTodaysPost.date}</span></td>
  <td class="td2"><span class="norm"><a href="{$smarty.const.INDEXFILE}?mode=viewforum&amp;forum_id={$curTodaysPost.forumID}{$smarty.const.SID_AMPER}">{$curTodaysPost.forumTitle}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" style="text-align:center;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('no_todays_posts')}</span></td></tr>
{/foreach}
</table>