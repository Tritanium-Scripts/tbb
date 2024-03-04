{include file='AdminMenu.tpl'}
<!-- AdminForumIndexCat -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="3"><span class="fontTitle">{Language::getInstance()->getString('manage_categories')}</span></th>{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_CATEGORIES_TABLE_HEAD}</tr>
{foreach $catTable as $curCatID => $curCatName}
 <tr>
  <td class="cellStd"><span class="fontNorm">{$curCatName}</span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=movekg{if $curCatName@first}down&amp;id={$curCatID}{$smarty.const.SID_AMPER}">&d{elseif $curCatName@last}up&amp;id={$curCatID}{$smarty.const.SID_AMPER}">&u{else}down&amp;id={$curCatID}{$smarty.const.SID_AMPER}">&darr;</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=movekgup&amp;id={$curCatID}{$smarty.const.SID_AMPER}">&u{/if}arr;</a></span></td>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_CATEGORIES_TABLE_BODY}
  <td class="cellStd" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=killkg&amp;id={$curCatID}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('delete')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=chgkg&amp;id={$curCatID}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="3" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{Language::getInstance()->getString('no_categories_available')}</span></td></tr>
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('options')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newkg{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_category')}</a>{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_CATEGORIES_OPTIONS}</span></td></tr>
</table>
{include file='AdminMenuTail.tpl'}