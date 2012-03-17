<!-- EditProfileConfirmDelete -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=profile&amp;mode=edit&amp;profile_id={$userData[1]}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('delete_account')}</span></th></tr>
 <tr><td class="td1" style="text-align:center;"><p><span class="norm">{$modules.Language->getString('really_delete_account')}</span></p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('delete_account')}" /></p>
<input type="hidden" name="change" value="1" />
<input type="hidden" name="delete" value="1" />
<input type="hidden" name="confirm" value="1" />
</form>