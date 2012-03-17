<!-- ViewTodaysPosts -->
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{$modules.Language->getString('topic')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{$modules.Language->getString('author')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{$modules.Language->getString('date')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{$modules.Language->getString('forum')}</span></th>
 </tr>
{foreach $todaysPosts as $curTodaysPost}
 <tr onmouseover="setRowCellsClass(this, 'cellHighlight');" onmouseout="restoreRowCellsClass(this);">
  <td class="cellStd" style="width:50%;"><span class="fontNorm"><img src="{$curTodaysPost.tSmiley}" alt="" /> {$curTodaysPost.topic}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$curTodaysPost.author}</span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontSmall">{$curTodaysPost.date}</span></td>
  <td class="cellAlt"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?mode=viewforum&amp;forum_id={$curTodaysPost.forumID}{$smarty.const.SID_AMPER}">{$curTodaysPost.forumTitle}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="4" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{$modules.Language->getString('no_todays_posts')}</span></td></tr>
{/foreach}
</table>