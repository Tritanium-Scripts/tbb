<!-- AdminConfigResetConfirm -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_settings&amp;mode=readsetfile{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('reset_settings')}</span></th></tr>
 <tr><td class="td1" style="text-align:center;"><p><span class="norm">{$modules.Language->getString('really_reset_settings')}</span></p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('reset_settings')}" /></p>
<input type="hidden" name="confirm" value="1" />
</form>