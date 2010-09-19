<!-- EditTopicClose -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=topic&amp;mode=close&amp;forum_id={$forumID}&amp;topic_id={$topicID}{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('close_topic')}</span></th></tr>
 <tr><td class="cellStd" style="text-align:center;"><p class="fontNorm">{$title|string_format:$modules.Language->getString('really_close_topic_x')}</p></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('close_topic')}" /></p>
<input type="hidden" name="close" value="yes" />
</form>