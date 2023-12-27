<!-- AdminConfigRebuildConfirm -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_settings&amp;mode=rebuildTopicIndex{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm">{Language::getInstance()->getString('rebuild_topic_index')}</th></tr>
 <tr><td class="td1" style="text-align:center;"><p class="norm">{Language::getInstance()->getString('really_rebuild_topic_index')}</p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('rebuild_topic_index')}" /></p>
<input type="hidden" name="confirmed" value="true" />
</form>