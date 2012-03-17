<!-- PrivateMessageNewPMConfirmSend -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=send&amp;send=yes&amp;check=yes{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('confirmation')}</span></th></tr>
 <tr><td class="td1" style="text-align:center;"><p><span class="norm">{sprintf($modules.Language->getString('really_send_pm_x_to_user_x'), $newPM[1], $recipient[1], $recipient[0])}</span></p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('send_pm')}" /></p>
<input type="hidden" name="target_id" value="{$recipient[1]}" />
<input type="hidden" name="betreff" value="{$newPM[1]}" />
<input type="hidden" name="pm" value="{$newPM[2]}" />
<input type="hidden" name="smilies" value="{$newPM[5]}" />
<input type="hidden" name="use_upbcode" value="{$newPM[6]}" />
<input type="hidden" name="storeToOutbox" value="{if $storeToOutbox}true{/if}" />
<input type="hidden" name="pmbox_id" value="{$pmBoxID}" />
</form>