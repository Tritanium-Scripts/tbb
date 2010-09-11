<!-- PrivateMessageConfirmDelete -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=kill&amp;kill=yes{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('delete_pm')}</span></th></tr>
 <tr><td class="cellStd" style="text-align:center;"><p class="fontNorm">{$pmTitle|string_format:$modules.Language->getString('really_delete_pm_x')}</p></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('delete_pm')}" /></p>
<input type="hidden" name="pmbox_id" value="{$pmBoxID}" />
<input type="hidden" name="pm_id" value="{$pmID}" />
</form>