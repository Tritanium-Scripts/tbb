<!-- EditTopicDelete -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=topic&amp;mode=killTopic&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('delete_topic')}</span></th></tr>
 <tr><td class="cellStd" style="text-align:center;"><p class="fontNorm">{$title|string_format:Language::getInstance()->getString('really_delete_topic_x')}</p></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('delete_topic')}" /></p>
<input type="hidden" name="kill" value="yes" />
</form>