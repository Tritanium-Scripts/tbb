<!-- AdminNewsletterConfirm -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_newsletter{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('confirmation')}</span></th></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:10%;"><span class="norm">{$modules.Language->getString('recipient_colon')}</span></td>
  <td class="td1" style="width:90%;"><span class="norm">{if $recipient == 2}{$modules.Language->getString('only_moderators')}{elseif $recipient == 3}{$modules.Language->getString('only_administrators')}{else}{$modules.Language->getString('all_members')}{/if}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:10%;"><span class="norm">{$modules.Language->getString('dispatch_colon')}</span></td>
  <td class="td1" style="width:90%;"><span class="norm">{if $dispatch == 1}{$modules.Language->getString('per_email')}{else}{$modules.Language->getString('per_pm')}{/if}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:10%;"><span class="norm">{$modules.Language->getString('subject_colon')}</span></td>
  <td class="td1" style="width:90%;"><span class="norm">{$subject}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; vertical-align:top; width:10%;"><span class="norm">{$modules.Language->getString('message_colon')}</span></td>
  <td class="td1" style="width:90%;"><span class="norm">{$message|nl2br}</span></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('send_newsletter')}" /></p>
<input type="hidden" name="mode" value="send" />
<input type="hidden" name="target" value="{$recipient}" />
<input type="hidden" name="sendmethod" value="{$dispatch}" />
<input type="hidden" name="betreff" value="{$subject}" />
<input type="hidden" name="newsletter" value="{$message}" />
<input type="hidden" name="isArchived" value="{if $isArchived}true{else}false{/if}" />
</form>