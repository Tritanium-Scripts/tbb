<!-- EditTopicDelete -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=topic&amp;mode=killTopic&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('delete_topic')}</span></th></tr>
 <tr><td class="td1" style="text-align:center;"><p><span class="norm">{$title|string_format:$modules.Language->getString('really_delete_topic_x')}</span></p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('delete_topic')}" /></p>
<input type="hidden" name="kill" value="yes" />
</form>