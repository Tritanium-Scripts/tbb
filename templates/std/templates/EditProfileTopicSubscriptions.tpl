<form method="post" action="{$indexFile}?action=EditProfile&amp;mode=TopicSubscriptions&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<tr><td class="CellCat" colspan="3"><span class="FontCat">{$modules.Language->getString('topic_subscriptions')}</span></td></tr>
{foreach from=$subscriptionsData item=curSubscription}
 <tr>
  <td class="CellAlt" align="center" width="50"><input type="checkbox" name="topicIDs[]" value="{$curSubscription.topicID}"/></td>
  <td class="CellStd"><span class="FontNorm"><a href="{$indexFile}?action=ViewTopic&amp;topicID={$curSubscription.topicID}&amp;{$mySID}">{$curSubscription.topicTitle}</a></span></td>
  <td class="CellAlt" align="right"><span class="FontSmall"><a href="{$indexFile}?action=EditProfile&amp;mode=TopicSubscriptions&amp;doit=1&amp;topicID={$curSubscription.topicID}&amp;{$mySID}">{$modules.Language->getString('delete')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="CellStd" align="center"><span class="FontNorm">{$modules.Language->getString('no_topic_subscriptions_found')}</span></td></tr>
{/foreach}
<tr><td class="CellButtons" colspan="3"><input class="FormBButton" type="submit" value="{$modules.Language->getString('delete_selected_topic_subscriptions')}"/></td></tr>
</table>
</form>
