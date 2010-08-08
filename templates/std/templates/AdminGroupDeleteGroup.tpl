<!-- AdminGroupDeleteGroup -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=kill&amp;group_id={$groupID}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('delete_group')}</span></th></tr>
 <tr><td class="td1" style="text-align:center;"><p class="norm">{$groupName|string_format:$modules.Language->getString('really_delete_group')}</p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('delete_group')}" /></p>
<input type="hidden" name="kill" value="yes" />
</form>