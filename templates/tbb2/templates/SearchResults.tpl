<!-- SearchResults -->
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('search_results')}</span></th><td class="cellTitle" style="text-align:right;"><span class="fontTitle">{sprintf($modules.Language->getString('found_x_topics_in_x_forums_in_x_secs'), $topicCounter, $forumCounter, $seconds)}</span></td></tr>
</table>
<br />
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{$modules.Language->getString('topic')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{$modules.Language->getString('author')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{$modules.Language->getString('replies')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{$modules.Language->getString('views')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{$modules.Language->getString('last_post')}</span></th>
 </tr>
{foreach $results as $curForumID => $curTopics}
 <tr><td class="cellCat" colspan="5"><span class="fontCat">{$idTable[$curForumID][0]}</span></td></tr>
{foreach $curTopics as $curTopicID => $curPosts}{$isNewTopic = true}
{foreach $curPosts as $curPostID => $curPost}{if $curPostID == 0}
 <tr>
  <td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?mode=viewthread&amp;forum_id={$curForumID}&amp;thread={$curTopicID}{$smarty.const.SID_AMPER}">{$idTable[$curForumID][$curTopicID]}</a></span></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontNorm">{$curPost.creator}</span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontNorm">{$curPost.replies}</span></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontNorm">{$curPost.views}</span></td>
  <td class="cellStd" style="text-align:right;"><span class="fontNorm">{sprintf($modules.Language->getString('x_by_x'), $curPost.lastDate, $curPost.lastPoster)}</span></td>
 </tr>{else}{if $isNewTopic}{$isNewTopic = false}
 <tr>
  <td class="cellTitle"><span class="fontTitleSmall" style="color:yellow;">{$idTable[$curForumID][$curTopicID]|string_format:$modules.Language->getString('found_posts_in_topic_x_colon')}</span></td>
  <td class="cellTitle" style="text-align:center;"><span class="fontTitleSmall" style="color:yellow;">{$modules.Language->getString('author')}</span></td>
  <td class="cellTitle" colspan="2" style="text-align:center;"><span class="fontTitleSmall" style="color:yellow;">{$modules.Language->getString('preview')}</span></td>
  <td class="cellTitle" style="text-align:center;"><span class="fontTitleSmall" style="color:yellow;">{$modules.Language->getString('date')}</span></td>
 </tr>{/if}
 <tr>
  <td class="cellStd"><span class="fontSmall" style="padding-left:2em;"><a href="{$smarty.const.INDEXFILE}?mode=viewthread&amp;forum_id={$curForumID}&amp;thread={$curTopicID}&amp;z={$curPost.page}{$smarty.const.SID_AMPER}#post{$curPostID}">{$idTable[$curForumID][$curTopicID]}</a></span></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontSmall">{$curPost.creator}</span></td>
  <td class="cellStd" colspan="2" style="text-align:center;"><span class="fontSmall">{$curPost.post}</span></td>
  <td class="cellAlt" style="text-align:right;"><span class="fontSmall">{$curPost.date}</span></td>
 </tr>{if $isFullScope && $curPost@last}
 <tr>
  <td class="cellStd" colspan="5"></td>
 </tr>{/if}{/if}
{/foreach}
{/foreach}
{foreachelse}
 <tr><td class="cellStd" colspan="5" style="font-weight:bold; text-align:center;"><span class="fontNorm">{$modules.Language->getString('no_search_results')}</span></td></tr>
{/foreach}
</table>