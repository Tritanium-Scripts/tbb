{include file='AdminMenu.tpl'}
<!-- AdminGroup -->
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="4"><span class="fontTitle">{$modules.Language->getString('manage_groups')}</span></th></tr>
 <tr>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('name')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('avatar')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('members')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('options')}</span></th>
 </tr>
{foreach $groups as $curGroup}
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=edit&amp;group_id={$curGroup[0]}{$smarty.const.SID_AMPER}">{$curGroup[1]}</a></span></td>
  <td class="cellAlt" style="text-align:center; vertical-align:top;"><span class="fontSmall">{if !empty($curGroup[2])}<img src="{$curGroup[2]}" alt="" />{else}{$modules.Language->getString('no_avatar')}{/if}</span></td>
  <td class="cellStd" style="vertical-align:top;"><span class="fontSmall">{if !empty($curGroup[3])}{', '|implode:$curGroup[3]}{else}{$modules.Language->getString('no_members')}{/if}</span></td>
  <td class="cellAlt" style="text-align:center; vertical-align:top;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=kill&amp;group_id={$curGroup[0]}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/icons/group_delete.png" alt="" style="vertical-align:middle;" /> {$modules.Language->getString('delete')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=edit&amp;group_id={$curGroup[0]}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/icons/group_edit.png" alt="" style="vertical-align:middle;" /> {$modules.Language->getString('edit')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td colspan="4" class="cellStd" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{$modules.Language->getString('no_groups_available')}</span></td></tr>
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('options')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=new{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/icons/group_add.png" alt="" class="imageIcon" /> {$modules.Language->getString('add_new_group')}</a></span></td></tr>
</table>
{include file='AdminMenuTail.tpl'}
