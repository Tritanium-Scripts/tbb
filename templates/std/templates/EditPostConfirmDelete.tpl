<!-- EditPostConfirmDelete -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=edit&amp;mode=kill&amp;forum_id={$forumID}&amp;topic_id={$topicID}&amp;post_id={$postID}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('delete_post')}</span></th></tr>
 <tr><td class="td1" style="text-align:center;"><p><span class="norm">{Language::getInstance()->getString('really_delete_post')}</span></p></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('delete_post')}" />{plugin_hook hook=PlugIns::HOOK_TPL_POSTING_DELETE_POST_BUTTONS}</p>
<input type="hidden" name="kill" value="yes" />
</form>