<!-- AdminGroup -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('name')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('avatar')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('members')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('options')}</span></th>
 </tr>
{foreach $groups as $curGroup}
 <tr>
  <td class="td1" style="vertical-align:top;"><span class="small">{$curGroup[1]}</span></td>
  <td class="td1" style="text-align:center; vertical-align:top;"><span class="small">{if !empty($curGroup[2])}<img src="{$curGroup[2]}" alt="" />{else}{$modules.Language->getString('no_avatar')}{/if}</span></td>
  <td class="td1" style="vertical-align:top;"><span class="small">{if !empty($curGroup[3])}{', '|implode:$curGroup[3]}{else}{$modules.Language->getString('no_members')}{/if}</span></td>
  <td class="td1" style="text-align:center; vertical-align:top;"><span class="small"><a href="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=edit&amp;group_id={$curGroup[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit')}</a>&nbsp;|&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=kill&amp;group_id={$curGroup[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('delete')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td colspan="4" class="td1" style="text-align:center;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('no_groups_available')}</span></td></tr>
{/foreach}
</table>
<p class="norm"><a href="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=new{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_group')}</a></p>