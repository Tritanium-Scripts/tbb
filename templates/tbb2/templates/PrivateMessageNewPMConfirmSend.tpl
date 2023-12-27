<!-- PrivateMessageNewPMConfirmSend -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=send&amp;send=yes&amp;check=yes{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('confirmation')}</span></th></tr>
 <tr><td class="cellStd" style="text-align:center;"><p class="fontNorm">{sprintf(Language::getInstance()->getString('really_send_pm_x_to_user_x'), $newPM[1], $recipient[1], $recipient[0])}</p></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('send_pm')}" /></p>
<input type="hidden" name="target_id" value="{$recipient[1]}" />
<input type="hidden" name="betreff" value="{$newPM[1]}" />
<input type="hidden" name="pm" value="{$newPM[2]}" />
<input type="hidden" name="smilies" value="{$newPM[5]}" />
<input type="hidden" name="use_upbcode" value="{$newPM[6]}" />
<input type="hidden" name="storeToOutbox" value="{if $storeToOutbox}true{/if}" />
<input type="hidden" name="pmbox_id" value="{$pmBoxID}" />
</form>