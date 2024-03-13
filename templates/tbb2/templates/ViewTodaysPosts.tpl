<!-- ViewTodaysPosts -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{Language::getInstance()->getString('topic')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{Language::getInstance()->getString('author')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{Language::getInstance()->getString('date')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{Language::getInstance()->getString('forum')}</span></th>
{plugin_hook hook=PlugIns::HOOK_TPL_FORUM_VIEW_TODAYS_POSTS_TABLE_HEAD}
 </tr>
{foreach $todaysPosts as $curTodaysPost}
 <tr onmouseover="setRowCellsClass(this, 'cellHighlight');" onmouseout="restoreRowCellsClass(this);">
  <td class="cellStd" style="width:50%;"><span class="fontNorm"><img src="{$curTodaysPost.tSmiley}" alt="" /> {$curTodaysPost.topic}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$curTodaysPost.author}</span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontSmall">{$curTodaysPost.date}</span></td>
  <td class="cellAlt"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?mode=viewforum&amp;forum_id={$curTodaysPost.forumID}{$smarty.const.SID_AMPER}">{$curTodaysPost.forumTitle}</a></span></td>
{plugin_hook hook=PlugIns::HOOK_TPL_FORUM_VIEW_TODAYS_POSTS_TABLE_BODY}
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="4" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{Language::getInstance()->getString('no_todays_posts')}</span></td></tr>
{/foreach}
</table>