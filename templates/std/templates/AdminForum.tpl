<!-- AdminForum -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm"><span class="thnorm">{Language::getInstance()->getString('manage_forums_categories')}</span></th></tr>
 <tr><td class="td1"><span class="norm" style="font-weight:bold;"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=forumview{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('manage_forums')}</a></span><br /><span class="small">{Language::getInstance()->getString('manage_forums_description')}</span></td></tr>
 <tr><td class="td1"><span class="norm" style="font-weight:bold;"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=viewkg{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('manage_categories')}</a></span><br /><span class="small">{Language::getInstance()->getString('manage_categories_description')}</span></td></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM}
</table>