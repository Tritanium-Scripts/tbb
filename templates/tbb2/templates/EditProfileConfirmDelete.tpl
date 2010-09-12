<!-- EditProfileConfirmDelete -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=profile&amp;mode=edit&amp;profile_id={$userData[1]}{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('delete_account')}</span></th></tr>
 <tr><td class="cellStd" style="text-align:center;"><p class="fontNorm">{$modules.Language->getString('really_delete_account')}</p></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('delete_account')}" /></p>
<input type="hidden" name="change" value="1" />
<input type="hidden" name="delete" value="1" />
<input type="hidden" name="confirm" value="1" />
</form>