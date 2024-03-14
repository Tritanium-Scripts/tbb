<!-- EditProfileConfirmDelete -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=profile&amp;mode=edit&amp;profile_id={$userData[1]}{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('delete_account')}</span></th></tr>
 <tr><td class="cellStd" style="text-align:center;"><p class="fontNorm">{Language::getInstance()->getString('really_delete_account')}</p></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('delete_account')}" />{plugin_hook hook=PlugIns::HOOK_TPL_PROFILE_DELETE_PROFILE_BUTTONS}</p>
<input type="hidden" name="change" value="1" />
<input type="hidden" name="delete" value="1" />
<input type="hidden" name="confirm" value="1" />
</form>