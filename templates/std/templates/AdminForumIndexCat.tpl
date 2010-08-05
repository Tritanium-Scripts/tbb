<!-- AdminForumIndexCat -->
<p class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newkg{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_category')}</a></p>
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('name')}</span></th>
  <th class="thsmall" colspan="2"><span class="thsmall">{$modules.Language->getString('options')}</span></th>
 </tr>
{foreach $catTable as $curCatID => $curCatName}
 <tr>
  <td class="td2"><span class="norm">{$curCatName}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=movekg{if $curCatName@first}down&amp;id={$curCatID}{$smarty.const.SID_AMPER}">&d{elseif $curCatName@last}up&amp;id={$curCatID}{$smarty.const.SID_AMPER}">&u{else}down&amp;id={$curCatID}{$smarty.const.SID_AMPER}">&darr;</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=movekgup&amp;id={$curCatID}{$smarty.const.SID_AMPER}">&u{/if}arr;</a></span></td>
  <td class="td2" style="text-align:center;"><span class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=killkg&amp;id={$curCatID}{$smarty.const.SID_AMPER}">{$modules.Language->getString('delete')}</a> | <a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=chgkg&amp;id={$curCatID}{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="3" style="text-align:center;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('no_categories_available')}</span></td></tr>
{/foreach}
</table>
<p class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newkg{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_category')}</a></p>