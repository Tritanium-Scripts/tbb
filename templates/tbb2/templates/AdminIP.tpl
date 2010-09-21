{include file='AdminMenu.tpl'}
<!-- AdminIP -->
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="4"><span class="fontTitle">{$modules.Language->getString('manage_ip_blocks')}</span></th></tr>
 <tr>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('ip_address')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('remaining_blocking_period')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('blocked_for')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('options')}</span></th>
 </tr>
{foreach $ipBlocks as $curIPBlock}
 <tr>
  <td class="cellStd"><span class="fontNorm">{$curIPBlock[0]}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$curIPBlock[1]}</span></td>
  <td class="cellStd"><span class="fontNorm">{$curIPBlock[2]}</span></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=ad_ip&amp;mode=kill&amp;id={$curIPBlock[3]}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/icons/computer_delete.png" alt="{$modules.Language->getString('delete')}" style="vertical-align:middle;" /> {$modules.Language->getString('delete')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="4" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{$modules.Language->getString('no_ip_blocks_available')}</span></td></tr>
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('options')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_ip&amp;mode=new{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/icons/computer_add.png" alt="{$modules.Language->getString('add_new_ip_block')}" class="imageIcon" /> {$modules.Language->getString('add_new_ip_block')}</a></span></td></tr>
</table>
{include file='AdminMenuTail.tpl'}