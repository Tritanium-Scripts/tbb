{include file='AdminMenu.tpl'}
<!-- AdminForumIndexCat -->
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="3"><span class="fontTitle">{$modules.Language->getString('manage_categories')}</span></th></tr>
{foreach $catTable as $curCatID => $curCatName}
 <tr>
  <td class="cellStd"><span class="fontNorm">{$curCatName}</span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=movekg{if $curCatName@first}down&amp;id={$curCatID}{$smarty.const.SID_AMPER}">&d{elseif $curCatName@last}up&amp;id={$curCatID}{$smarty.const.SID_AMPER}">&u{else}down&amp;id={$curCatID}{$smarty.const.SID_AMPER}">&darr;</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=movekgup&amp;id={$curCatID}{$smarty.const.SID_AMPER}">&u{/if}arr;</a></span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=killkg&amp;id={$curCatID}{$smarty.const.SID_AMPER}">{$modules.Language->getString('delete')}</a> | <a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=chgkg&amp;id={$curCatID}{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="3" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{$modules.Language->getString('no_categories_available')}</span></td></tr>
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('options')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newkg{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_category')}</a></span></td></tr>
</table>
{include file='AdminMenuTail.tpl'}