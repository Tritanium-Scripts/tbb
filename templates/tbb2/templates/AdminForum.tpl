{include file='AdminMenu.tpl'}
<!-- AdminForum -->
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('manage_forums_categories')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm" style="font-weight:bold;"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=forumview{$smarty.const.SID_AMPER}">{$modules.Language->getString('manage_forums')}</a></span><br /><span class="fontSmall">{$modules.Language->getString('manage_forums_description')}</span></td></tr>
 <tr><td class="cellStd"><span class="fontNorm" style="font-weight:bold;"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=viewkg{$smarty.const.SID_AMPER}">{$modules.Language->getString('manage_categories')}</a></span><br /><span class="fontSmall">{$modules.Language->getString('manage_categories_description')}</span></td></tr>
</table>
{include file='AdminMenuTail.tpl'}