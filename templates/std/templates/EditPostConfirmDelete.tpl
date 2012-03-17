<!-- EditPostConfirmDelete -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=edit&amp;mode=kill&amp;forum_id={$forumID}&amp;topic_id={$topicID}&amp;post_id={$postID}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{$modules.Language->getString('delete_post')}</span></th></tr>
 <tr><td class="td1" style="text-align:center;"><p><span class="norm">{$modules.Language->getString('really_delete_post')}</span></p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('delete_post')}" /></p>
<input type="hidden" name="kill" value="yes" />
</form>