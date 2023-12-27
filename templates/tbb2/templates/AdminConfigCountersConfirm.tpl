<!-- AdminConfigCountersConfirm -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_settings&amp;mode=recalculateCounters{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('recalculate_counters')}</span></th></tr>
 <tr><td class="cellStd" style="text-align:center;"><p class="fontNorm">{Language::getInstance()->getString('really_recalculate_counters')}</p></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('recalculate_counters')}" /></p>
<input type="hidden" name="confirmed" value="true" />
</form>