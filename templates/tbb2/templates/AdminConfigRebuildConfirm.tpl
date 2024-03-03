<!-- AdminConfigRebuildConfirm -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_settings&amp;mode=rebuildTopicIndex{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('rebuild_topic_index')}</span></th></tr>
 <tr><td class="cellStd" style="text-align:center;"><p class="fontNorm">{Language::getInstance()->getString('really_rebuild_topic_index')}</p></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('rebuild_topic_index')}" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CONFIG_REBUILD_TOPIC_INDEX_BUTTONS}</p>
<input type="hidden" name="confirmed" value="true" />
</form>