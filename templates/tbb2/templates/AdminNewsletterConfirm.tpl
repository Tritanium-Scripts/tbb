<!-- AdminNewsletterConfirm -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_newsletter{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="10%" />
  <col width="90%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('confirmation')}</span></th></tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('recipient_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm">{if $recipient == 2}{Language::getInstance()->getString('only_moderators')}{elseif $recipient == 3}{Language::getInstance()->getString('only_administrators')}{else}{Language::getInstance()->getString('all_members')}{/if}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('dispatch_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm">{if $dispatch == 1}{Language::getInstance()->getString('per_email')}{else}{Language::getInstance()->getString('per_pm')}{/if}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('subject_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$subject}</span></td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('message_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$message|nl2br}</span></td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('send_newsletter')}" /></p>
<input type="hidden" name="mode" value="send" />
<input type="hidden" name="target" value="{$recipient}" />
<input type="hidden" name="sendmethod" value="{$dispatch}" />
<input type="hidden" name="betreff" value="{$subject}" />
<input type="hidden" name="newsletter" value="{$message}" />
<input type="hidden" name="isArchived" value="{if $isArchived}true{else}false{/if}" />
</form>