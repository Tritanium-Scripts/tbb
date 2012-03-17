<!-- AdminConfigCountersConfirm -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_settings&amp;mode=recalculateCounters{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm">{$modules.Language->getString('recalculate_counters')}</th></tr>
 <tr><td class="td1" style="text-align:center;"><p class="norm">{$modules.Language->getString('really_recalculate_counters')}</p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('recalculate_counters')}" /></p>
<input type="hidden" name="confirmed" value="true" />
</form>