<form method="post" action="{$IndexFile}?Action=EditProfile&amp;Mode=TopicSubscriptions&amp;Doit=1&amp;{$MySID}">
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellCat" colspan="3"><span class="FontCat">{$Modules.Language->getString('Topic_subscriptions')}</span></td></tr>
{foreach from=$SubscriptionsData item=curSubscription}
 <tr>
  <td class="CellAlt" align="center" width="50"><input type="checkbox" name="TopicIDs[]" value="{$curSubscription.TopicID}"/></td>
  <td class="CellStd"><span class="FontNorm"><a href="{$IndexFile}?Action=ViewTopic&amp;TopicID={$curSubscription.TopicID}&amp;{$MySID}">{$curSubscription.TopicTitle}</a></span></td>
  <td class="CellAlt" align="right"><span class="FontSmall"><a href="{$IndexFile}?Action=EditProfile&amp;Mode=TopicSubscriptions&amp;Doit=1&amp;TopicID={$curSubscription.TopicID}&amp;{$MySID}">{$Modules.Language->getString('delete')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="CellStd" align="center"><span class="FontNorm">{$Modules.Language->getString('No_topic_subscriptions_found')}</span></td></tr>
{/foreach}
<tr><td class="CellButtons" colspan="3"><input class="FormBButton" type="submit" value="{$Modules.Language->getString('Delete_selected_topic_subscriptions')}"/></td></tr>
</table>
</form>
