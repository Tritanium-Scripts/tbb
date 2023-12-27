<!-- PrivateMessageConfirmDelete -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=kill&amp;kill=yes{$urlSuffix}{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('delete_pm')}</span></th></tr>
 <tr><td class="cellStd" style="text-align:center;"><p class="fontNorm">{$pmTitle|string_format:Language::getInstance()->getString('really_delete_pm_x')}</p></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('delete_pm')}" /></p>
<input type="hidden" name="pmbox_id" value="{$pmBoxID}" />
<input type="hidden" name="pm_id" value="{$pmID}" />
</form>