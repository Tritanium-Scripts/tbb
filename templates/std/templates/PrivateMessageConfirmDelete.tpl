<!-- PrivateMessageConfirmDelete -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=kill&amp;kill=yes{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('delete_pm')}</span></th></tr>
 <tr><td class="td1" style="text-align:center;"><p><span class="norm">{$pmTitle|string_format:$modules.Language->getString('really_delete_pm_x')}</span></p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('delete_pm')}" /></p>
<input type="hidden" name="pmbox_id" value="{$pmBoxID}" />
<input type="hidden" name="pm_id" value="{$pmID}" />
</form>