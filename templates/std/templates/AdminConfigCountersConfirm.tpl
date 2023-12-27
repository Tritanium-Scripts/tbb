<!-- AdminConfigCountersConfirm -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_settings&amp;mode=recalculateCounters{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm">{Language::getInstance()->getString('recalculate_counters')}</th></tr>
 <tr><td class="td1" style="text-align:center;"><p class="norm">{Language::getInstance()->getString('really_recalculate_counters')}</p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('recalculate_counters')}" /></p>
<input type="hidden" name="confirmed" value="true" />
</form>