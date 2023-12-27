<!-- ViewForum -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr>
  <th colspan="3" class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{Language::getInstance()->getString('topic')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{Language::getInstance()->getString('topic_starter')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{Language::getInstance()->getString('replies')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{Language::getInstance()->getString('views')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{Language::getInstance()->getString('last_post')}</span></th>
 </tr>
{foreach $topics as $curTopic}
 <tr onmouseover="setRowCellsClass(this, 'cellHighlight');" onmouseout="restoreRowCellsClass(this);">
  <td class="cellAlt" style="text-align:center; width:20px;"><img src="{Template::getInstance()->getTplDir()}images/icons/{$curTopic.topicIcon}.gif" alt="" /></td>
  <td class="cellAlt" style="text-align:center; width:20px;"><img src="{$curTopic.tSmileyURL}" alt="" /></td>
  <td class="cellStd"><span class="topicLink">{if $curTopic.isMoved}<span style="font-weight:bold;">{Language::getInstance()->getString('moved_colon')}</span> {/if}{if $curTopic.isSticky}<b>{Language::getInstance()->getString('sticker_colon')|upper}</b> {/if}{if $curTopic.isPoll}{Language::getInstance()->getString('poll_colon')} {/if}<a class="topicLink" href="{$smarty.const.INDEXFILE}?mode=viewthread&amp;forum_id={if $curTopic.isMoved}{$curTopic.movedForumID}{else}{$forumID}{/if}&amp;thread={if $curTopic.isMoved}{$curTopic.movedTopicID}{else}{$curTopic.topicID}{/if}{$smarty.const.SID_AMPER}">{$curTopic.topicTitle}</a></span> <span class="fontSmall">{$curTopic.topicPageBar}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$curTopic.topicStarter}</span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontSmall">{$curTopic.postCounter}</span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontSmall">{$curTopic.views}</span></td>
  <td class="cellAlt" style="text-align:right;"><span class="fontSmall">{$curTopic.lastPost}{if !$curTopic.isMoved} <a href="{$smarty.const.INDEXFILE}?mode=viewthread&amp;forum_id={$forumID}&amp;thread={$curTopic.topicID}&amp;z=last{$smarty.const.SID_AMPER}#last">&raquo;</a>{/if}</span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="7" style="font-weight:bold; text-align:center;"><span class="fontNorm">{Language::getInstance()->getString('no_topics_available')}</span></td></tr>
{/foreach}
</table>
<br />

<!-- Toolbar -->
<table class="tableNavBar">
 <tr>
  <td class="cellNavBarBig">
   <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
    <tr>
     <td><span class="fontNavBar">{$pageBar}</span></td>
     <td style="text-align:right;"><span class="fontNavBar"><a href="{$smarty.const.INDEXFILE}?faction=newtopic&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/new_topic.png" alt="" class="imageButton" /></a>&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=newpoll&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/buttons/new_poll.png" alt="" class="imageButton" /></a></span></td>
    </tr>
   </table>
  </td>
 </tr>
</table>