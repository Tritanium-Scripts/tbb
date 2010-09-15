<!-- AdminGroupDeleteGroup -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=kill&amp;group_id={$groupID}{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('delete_group')}</span></th></tr>
 <tr><td class="cellStd" style="text-align:center;"><p class="fontNorm">{$groupName|string_format:$modules.Language->getString('really_delete_group')}</p></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('delete_group')}" /></p>
<input type="hidden" name="kill" value="yes" />
</form>