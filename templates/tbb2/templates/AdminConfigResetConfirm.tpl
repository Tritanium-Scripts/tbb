<!-- AdminConfigResetConfirm -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_settings&amp;mode=readsetfile{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('reset_settings')}</span></th></tr>
 <tr><td class="cellStd" style="text-align:center;"><p class="fontNorm">{$modules.Language->getString('really_reset_settings')}</p></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('reset_settings')}" /></p>
<input type="hidden" name="confirm" value="1" />
</form>