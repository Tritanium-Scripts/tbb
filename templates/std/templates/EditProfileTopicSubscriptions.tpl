<form method="post" action="{$indexFile}?action=EditProfile&amp;mode=TopicSubscriptions&amp;Doit=1&amp;{$mySID}">
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellCat" colspan="3"><span class="FontCat">{$modules.Language->getString('Topic_subscriptions')}</span></td></tr>
{foreach from=$subscriptionsData item=curSubscription}
 <tr>
  <td class="CellAlt" align="center" width="50"><input type="checkbox" name="TopicIDs[]" value="{$curSubscription.TopicID}"/></td>
  <td class="CellStd"><span class="FontNorm"><a href="{$indexFile}?action=ViewTopic&amp;TopicID={$curSubscription.TopicID}&amp;{$mySID}">{$curSubscription.TopicTitle}</a></span></td>
  <td class="CellAlt" align="right"><span class="FontSmall"><a href="{$indexFile}?action=EditProfile&amp;mode=TopicSubscriptions&amp;Doit=1&amp;TopicID={$curSubscription.TopicID}&amp;{$mySID}">{$modules.Language->getString('delete')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="CellStd" align="center"><span class="FontNorm">{$modules.Language->getString('No_topic_subscriptions_found')}</span></td></tr>
{/foreach}
<tr><td class="CellButtons" colspan="3"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Delete_selected_topic_subscriptions')}"/></td></tr>
</table>
</form>
