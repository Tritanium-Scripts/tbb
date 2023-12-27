<!-- ViewTodaysPosts -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('topic')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('author')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('date')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('forum')}</span></th>
 </tr>
{foreach $todaysPosts as $curTodaysPost}
 <tr>
  <td class="td1" style="width:50%;"><span class="norm"><img src="{$curTodaysPost.tSmiley}" alt="" /> {$curTodaysPost.topic}</span></td>
  <td class="td2"><span class="norm">{$curTodaysPost.author}</span></td>
  <td class="td1" style="text-align:center;"><span class="small">{$curTodaysPost.date}</span></td>
  <td class="td2"><span class="norm"><a href="{$smarty.const.INDEXFILE}?mode=viewforum&amp;forum_id={$curTodaysPost.forumID}{$smarty.const.SID_AMPER}">{$curTodaysPost.forumTitle}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="4" style="text-align:center;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('no_todays_posts')}</span></td></tr>
{/foreach}
</table>