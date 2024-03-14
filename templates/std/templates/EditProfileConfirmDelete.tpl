<!-- EditProfileConfirmDelete -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=profile&amp;mode=edit&amp;profile_id={$userData[1]}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('delete_account')}</span></th></tr>
 <tr><td class="td1" style="text-align:center;"><p><span class="norm">{Language::getInstance()->getString('really_delete_account')}</span></p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('delete_account')}" />{plugin_hook hook=PlugIns::HOOK_TPL_PROFILE_DELETE_PROFILE_BUTTONS}</p>
<input type="hidden" name="change" value="1" />
<input type="hidden" name="delete" value="1" />
<input type="hidden" name="confirm" value="1" />
</form>