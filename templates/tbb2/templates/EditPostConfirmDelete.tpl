<!-- EditPostConfirmDelete -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=edit&amp;mode=kill&amp;forum_id={$forumID}&amp;topic_id={$topicID}&amp;post_id={$postID}{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('delete_post')}</span></th></tr>
 <tr><td class="cellStd" style="text-align:center;"><p class="fontNorm">{Language::getInstance()->getString('really_delete_post')}</p></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('delete_post')}" /></p>
<input type="hidden" name="kill" value="yes" />
</form>