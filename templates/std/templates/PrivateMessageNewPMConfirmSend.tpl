<!-- PrivateMessageNewPMConfirmSend -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=send&amp;send=yes&amp;check=yes{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('confirmation')}</span></th></tr>
 <tr><td class="td1" style="text-align:center;"><p><span class="norm">{sprintf(Language::getInstance()->getString('really_send_pm_x_to_user_x'), $newPM[1], $recipient[1], $recipient[0])}</span></p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('send_pm')}" />{plugin_hook hook=PlugIns::HOOK_TPL_PRIVATE_MESSAGE_NEW_PM_CONFIRM_BUTTONS}</p>
<input type="hidden" name="target_id" value="{$recipient[1]}" />
<input type="hidden" name="betreff" value="{$newPM[1]}" />
<input type="hidden" name="pm" value="{$newPM[2]}" />
<input type="hidden" name="smilies" value="{$newPM[5]}" />
<input type="hidden" name="use_upbcode" value="{$newPM[6]}" />
<input type="hidden" name="storeToOutbox" value="{if $storeToOutbox}true{/if}" />
<input type="hidden" name="pmbox_id" value="{$pmBoxID}" />
</form>