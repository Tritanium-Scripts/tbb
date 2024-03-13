<!-- ViewForum -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th colspan="3" class="thsmall"><span class="thsmall">{Language::getInstance()->getString('topic')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('topic_starter')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('replies')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('views')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('last_post')}</span></th>
{plugin_hook hook=PlugIns::HOOK_TPL_FORUM_VIEW_FORUM_TABLE_HEAD}
 </tr>
{foreach $topics as $curTopic}
 <tr>
  <td class="td1" style="text-align:center;"><img src="{Template::getInstance()->getTplDir()}images/{$curTopic.topicIcon}.gif" alt="" /></td>
  <td class="td2" style="text-align:center;"><img src="{$curTopic.tSmileyURL}" alt="" /></td>
  <td class="td1"><span class="topiclink">{if $curTopic.isMoved}<span style="font-weight:bold;">{Language::getInstance()->getString('moved_colon')}</span> {/if}{if $curTopic.isSticky}<b>{Language::getInstance()->getString('sticker_colon')|upper}</b> {/if}{if $curTopic.isPoll}{Language::getInstance()->getString('poll_colon')} {/if}{if !empty($curTopic.topicPrefix)}<span style="font-weight:bold;{if !empty($curTopic.topicPrefix.color)} color:{$curTopic.topicPrefix.color};{/if}">{$curTopic.topicPrefix.prefix}</span> {/if}<a class="topiclink" href="{$smarty.const.INDEXFILE}?mode=viewthread&amp;forum_id={if $curTopic.isMoved}{$curTopic.movedForumID}{else}{$forumID}{/if}&amp;thread={if $curTopic.isMoved}{$curTopic.movedTopicID}{else}{$curTopic.topicID}{/if}{$smarty.const.SID_AMPER}">{$curTopic.topicTitle}</a></span> <span class="small">{$curTopic.topicPageBar}</span></td>
  <td class="td2"><span class="norm">{$curTopic.topicStarter}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm">{$curTopic.postCounter}</span></td>
  <td class="td2" style="text-align:center;"><span class="norm">{$curTopic.views}</span></td>
  <td class="td1" style="text-align:center;"><span class="small">{$curTopic.lastPost}</span></td>
{plugin_hook hook=PlugIns::HOOK_TPL_FORUM_VIEW_FORUM_TABLE_BODY}
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="7" style="font-weight:bold; text-align:center;"><span class="norm">{Language::getInstance()->getString('no_topics_available')}</span></td></tr>
{/foreach}
</table>
<br />

<!-- Toolbar -->
<table class="navbar" cellpadding="0" cellspacing="0" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr>
   <td class="navbar" style="width:50%;"><span class="navbar">&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=newtopic&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/newtopic.gif" alt="" style="vertical-align:middle;" /></a>&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=newpoll&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/newpoll.gif" alt="" style="vertical-align:middle;" /></a>{plugin_hook hook=PlugIns::HOOK_TPL_FORUM_VIEW_FORUM_OPTIONS}</span></td>
   <td class="navbar" style="width:49%; text-align:right;"><span class="navbar">{$pageBar}</span></td>
 </tr>
</table>