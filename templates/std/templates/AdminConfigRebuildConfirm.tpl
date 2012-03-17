<!-- AdminConfigRebuildConfirm -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_settings&amp;mode=rebuildTopicIndex{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm">{$modules.Language->getString('rebuild_topic_index')}</th></tr>
 <tr><td class="td1" style="text-align:center;"><p class="norm">{$modules.Language->getString('really_rebuild_topic_index')}</p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('rebuild_topic_index')}" /></p>
<input type="hidden" name="confirmed" value="true" />
</form>