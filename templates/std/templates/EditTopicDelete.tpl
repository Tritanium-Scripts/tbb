<!-- EditTopicDelete -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=topic&amp;mode=killTopic&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('delete_topic')}</span></th></tr>
 <tr><td class="td1" style="text-align:center;"><p><span class="norm">{$title|string_format:Language::getInstance()->getString('really_delete_topic_x')}</span></p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('delete_topic')}" />{plugin_hook hook=PlugIns::HOOK_TPL_POSTING_DELETE_TOPIC_BUTTONS}</p>
<input type="hidden" name="kill" value="yes" />
</form>