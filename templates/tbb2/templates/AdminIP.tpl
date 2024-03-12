{include file='AdminMenu.tpl'}
<!-- AdminIP -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="4"><span class="fontTitle">{Language::getInstance()->getString('manage_ip_blocks')}</span></th></tr>
 <tr>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('ip_address')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('remaining_blocking_period')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('blocked_for')}</span></th>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_IP_BLOCKS_TABLE_HEAD}
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('options')}</span></th>
 </tr>
{foreach $ipBlocks as $curIPBlock}
 <tr>
  <td class="cellStd"><span class="fontNorm">{$curIPBlock[0]}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$curIPBlock[1]}</span></td>
  <td class="cellStd"><span class="fontNorm">{$curIPBlock[2]}</span></td>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_IP_BLOCKS_TABLE_BODY}
  <td class="cellAlt" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=ad_ip&amp;mode=kill&amp;id={$curIPBlock[3]}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/computer_delete.png" alt="{Language::getInstance()->getString('delete')}" style="vertical-align:middle;" /> {Language::getInstance()->getString('delete')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="4" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{Language::getInstance()->getString('no_ip_blocks_available')}</span></td></tr>
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('options')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_ip&amp;mode=new{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/computer_add.png" alt="{Language::getInstance()->getString('add_new_ip_block')}" class="imageIcon" /> {Language::getInstance()->getString('add_new_ip_block')}</a>{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_IP_BLOCKS_OPTIONS}</span></td></tr>
</table>
{include file='AdminMenuTail.tpl'}