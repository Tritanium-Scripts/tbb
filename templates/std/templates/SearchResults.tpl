<!-- SearchResults -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('search_results')}</span></th><td style="text-align:right;"><span class="thnorm">{sprintf(Language::getInstance()->getString('found_x_topics_in_x_forums_in_x_secs'), $topicCounter, $forumCounter, $seconds)}</span></td></tr>
</table>
<br />
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('topic')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('author')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('replies')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('views')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('last_post')}</span></th>
 </tr>
{foreach $results as $curForumID => $curTopics}
 <tr><td class="kat" colspan="5"><span class="kat">{$idTable[$curForumID][0]}</span></td></tr>
{foreach $curTopics as $curTopicID => $curPosts}{$isNewTopic = true}
{foreach $curPosts as $curPostID => $curPost}{if $curPostID == 0}
 <tr>
  <td class="td1"><span class="norm"><a href="{$smarty.const.INDEXFILE}?mode=viewthread&amp;forum_id={$curForumID}&amp;thread={$curTopicID}{$smarty.const.SID_AMPER}">{$idTable[$curForumID][$curTopicID]}</a></span></td>
  <td class="td2" style="text-align:center;"><span class="norm">{$curPost.creator}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm">{$curPost.replies}</span></td>
  <td class="td2" style="text-align:center;"><span class="norm">{$curPost.views}</span></td>
  <td class="td1" style="text-align:right;"><span class="norm">{sprintf(Language::getInstance()->getString('x_by_x'), $curPost.lastDate, $curPost.lastPoster)}</span></td>
 </tr>{else}{if $isNewTopic}{$isNewTopic = false}
 <tr>
  <td class="thnorm"><span class="small" style="color:yellow;">{$idTable[$curForumID][$curTopicID]|string_format:Language::getInstance()->getString('found_posts_in_topic_x_colon')}</span></td>
  <td class="thnorm" style="text-align:center;"><span class="small" style="color:yellow;">{Language::getInstance()->getString('author')}</span></td>
  <td class="thnorm" colspan="2" style="text-align:center;"><span class="small" style="color:yellow;">{Language::getInstance()->getString('preview')}</span></td>
  <td class="thnorm" style="text-align:center;"><span class="small" style="color:yellow;">{Language::getInstance()->getString('date')}</span></td>
 </tr>{/if}
 <tr>
  <td class="td1"><span class="small" style="padding-left:2em;"><a href="{$smarty.const.INDEXFILE}?mode=viewthread&amp;forum_id={$curForumID}&amp;thread={$curTopicID}&amp;z={$curPost.page}{$smarty.const.SID_AMPER}#post{$curPostID}">{$idTable[$curForumID][$curTopicID]}</a></span></td>
  <td class="td2" style="text-align:center;"><span class="small">{$curPost.creator}</span></td>
  <td class="td1" colspan="2" style="text-align:center;"><span class="small">{$curPost.post}</span></td>
  <td class="td2" style="text-align:right;"><span class="small">{$curPost.date}</span></td>
 </tr>{if $isFullScope && $curPost@last}
 <tr>
  <td class="thnorm" colspan="5"></td>
 </tr>{/if}{/if}
{/foreach}
{/foreach}
{foreachelse}
 <tr><td class="td1" colspan="5" style="font-weight:bold; text-align:center;"><span class="norm">{Language::getInstance()->getString('no_search_results')}</span></td></tr>
{/foreach}
</table>