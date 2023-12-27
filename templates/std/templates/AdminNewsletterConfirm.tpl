<!-- AdminNewsletterConfirm -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_newsletter{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('confirmation')}</span></th></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:10%;"><span class="norm">{Language::getInstance()->getString('recipient_colon')}</span></td>
  <td class="td1" style="width:90%;"><span class="norm">{if $recipient == 2}{Language::getInstance()->getString('only_moderators')}{elseif $recipient == 3}{Language::getInstance()->getString('only_administrators')}{else}{Language::getInstance()->getString('all_members')}{/if}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:10%;"><span class="norm">{Language::getInstance()->getString('dispatch_colon')}</span></td>
  <td class="td1" style="width:90%;"><span class="norm">{if $dispatch == 1}{Language::getInstance()->getString('per_email')}{else}{Language::getInstance()->getString('per_pm')}{/if}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:10%;"><span class="norm">{Language::getInstance()->getString('subject_colon')}</span></td>
  <td class="td1" style="width:90%;"><span class="norm">{$subject}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:10%;"><span class="norm">{Language::getInstance()->getString('message_colon')}</span></td>
  <td class="td1" style="width:90%;"><span class="norm">{$message|nl2br}</span></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('send_newsletter')}" /></p>
<input type="hidden" name="mode" value="send" />
<input type="hidden" name="target" value="{$recipient}" />
<input type="hidden" name="sendmethod" value="{$dispatch}" />
<input type="hidden" name="betreff" value="{$subject}" />
<input type="hidden" name="newsletter" value="{$message}" />
<input type="hidden" name="isArchived" value="{if $isArchived}true{else}false{/if}" />
</form>