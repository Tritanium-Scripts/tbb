<!-- AdminGroup -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('name')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('color')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('avatar')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('members')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('options')}</span></th>
 </tr>
{foreach $groups as $curGroup}
 <tr>
  <td class="td1" style="vertical-align:top;"><span class="small">{$curGroup[1]}</span></td>
  <td class="td2" style="text-align:center; vertical-align:top;"><span class="small">{if !empty($curGroup[4])}<span style="color:{$curGroup[4]};">{$curGroup[4]}</span>{else}{Language::getInstance()->getString('no_color')}{/if}</span></td>
  <td class="td1" style="text-align:center; vertical-align:top;"><span class="small">{if !empty($curGroup[2])}<img src="{$curGroup[2]}" alt="" />{else}{Language::getInstance()->getString('no_avatar')}{/if}</span></td>
  <td class="td2" style="vertical-align:top;"><span class="small">{if !empty($curGroup[3])}{', '|implode:$curGroup[3]}{else}{Language::getInstance()->getString('no_members')}{/if}</span></td>
  <td class="td1" style="text-align:center; vertical-align:top;"><span class="small"><a href="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=edit&amp;group_id={$curGroup[0]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit')}</a>&nbsp;|&nbsp;<a href="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=kill&amp;group_id={$curGroup[0]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('delete')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td colspan="5" class="td1" style="text-align:center;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('no_groups_available')}</span></td></tr>
{/foreach}
</table>
<p class="norm"><a href="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=new{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_group')}</a></p>