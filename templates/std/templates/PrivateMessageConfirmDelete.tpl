<!-- PrivateMessageConfirmDelete -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=kill&amp;kill=yes{$urlSuffix}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('delete_pm')}</span></th></tr>
 <tr><td class="td1" style="text-align:center;"><p><span class="norm">{$pmTitle|string_format:Language::getInstance()->getString('really_delete_pm_x')}</span></p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('delete_pm')}" /></p>
<input type="hidden" name="pmbox_id" value="{$pmBoxID}" />
<input type="hidden" name="pm_id" value="{$pmID}" />
</form>