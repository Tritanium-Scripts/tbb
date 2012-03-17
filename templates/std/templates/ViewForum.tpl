<!-- ViewForum -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th colspan="3" class="thsmall"><span class="thsmall">{$modules.Language->getString('topic')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('topic_starter')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('replies')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('views')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('last_post')}</span></th>
 </tr>
{foreach $topics as $curTopic}
 <tr>
  <td class="td1" style="text-align:center;"><img src="{$modules.Template->getTplDir()}images/{$curTopic.topicIcon}.gif" alt="" /></td>
  <td class="td2" style="text-align:center;"><img src="{$curTopic.tSmileyURL}" alt="" /></td>
  <td class="td1"><span class="topiclink">{if $curTopic.isMoved}<span style="font-weight:bold;">{$modules.Language->getString('moved_colon')}</span> {/if}{if $curTopic.isSticky}<b>{$modules.Language->getString('sticker_colon')|upper}</b> {/if}{if $curTopic.isPoll}{$modules.Language->getString('poll_colon')} {/if}<a class="topiclink" href="{$smarty.const.INDEXFILE}?mode=viewthread&amp;forum_id={if $curTopic.isMoved}{$curTopic.movedForumID}{else}{$forumID}{/if}&amp;thread={if $curTopic.isMoved}{$curTopic.movedTopicID}{else}{$curTopic.topicID}{/if}{$smarty.const.SID_AMPER}">{$curTopic.topicTitle}</a></span> <span class="small">{$curTopic.topicPageBar}</span></td>
  <td class="td2"><span class="norm">{$curTopic.topicStarter}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm">{$curTopic.postCounter}</span></td>
  <td class="td2" style="text-align:center;"><span class="norm">{$curTopic.views}</span></td>
  <td class="td1" style="text-align:center;"><span class="small">{$curTopic.lastPost}</span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="7" style="font-weight:bold; text-align:center;"><span class="norm">{$modules.Language->getString('no_topics_available')}</span></td></tr>
{/foreach}
</table>
<br />

<!-- Toolbar -->
<table class="navbar" cellpadding="0" cellspacing="0" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr>
   <td class="navbar" style="width:50%;"><span class="navbar">&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=newtopic&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/newtopic.gif" alt="" style="vertical-align:middle;" /></a>&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=newpoll&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/newpoll.gif" alt="" style="vertical-align:middle;" /></a></span></td>
   <td class="navbar" style="width:49%; text-align:right;"><span class="navbar">{$pageBar}</span></td>
 </tr>
</table>