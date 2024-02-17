{include file='AdminMenu.tpl'}
<!-- AdminGroup -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="5"><span class="fontTitle">{Language::getInstance()->getString('manage_groups')}</span></th></tr>
 <tr>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('name')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('color')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('avatar')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('members')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('options')}</span></th>
 </tr>
{foreach $groups as $curGroup}
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=edit&amp;group_id={$curGroup[0]}{$smarty.const.SID_AMPER}">{$curGroup[1]}</a></span></td>
  <td class="cellAlt" style="text-align:center; vertical-align:top;"><span class="fontSmall">{if !empty($curGroup[4])}<span style="color:{$curGroup[4]};">{$curGroup[4]}</span>{else}{Language::getInstance()->getString('no_color')}{/if}</span></td>
  <td class="cellStd" style="text-align:center; vertical-align:top;"><span class="fontSmall">{if !empty($curGroup[2])}<img src="{$curGroup[2]}" alt="" />{else}{Language::getInstance()->getString('no_avatar')}{/if}</span></td>
  <td class="cellAlt" style="vertical-align:top;"><span class="fontSmall">{if !empty($curGroup[3])}{', '|implode:$curGroup[3]}{else}{Language::getInstance()->getString('no_members')}{/if}</span></td>
  <td class="cellStd" style="text-align:center; vertical-align:top;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=kill&amp;group_id={$curGroup[0]}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/group_delete.png" alt="{Language::getInstance()->getString('delete')}" style="vertical-align:middle;" /> {Language::getInstance()->getString('delete')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=edit&amp;group_id={$curGroup[0]}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/group_edit.png" alt="{Language::getInstance()->getString('edit')}" style="vertical-align:middle;" /> {Language::getInstance()->getString('edit')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td colspan="5" class="cellStd" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{Language::getInstance()->getString('no_groups_available')}</span></td></tr>
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('options')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=new{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/group_add.png" alt="{Language::getInstance()->getString('add_new_group')}" class="imageIcon" /> {Language::getInstance()->getString('add_new_group')}</a></span></td></tr>
</table>
{include file='AdminMenuTail.tpl'}
