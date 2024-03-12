{include file='AdminMenu.tpl'}
<!-- AdminMailBlock -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col />
  <col width="1%" />
  <col width="1%" />
  <col width="1%" />
  <col />
  <col />
  <col />
 </colgroup>
 <tr><th class="cellTitle" colspan="7"><span class="fontTitle">{Language::getInstance()->getString('manage_mail_blocks')}</span></th></tr>
 <tr>
  <th class="cellCat" colspan="5" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('email_address')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('remaining_blocking_period')}</span></th>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_MAIL_BLOCK_BLOCKS_TABLE_HEAD}
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{Language::getInstance()->getString('options')}</span></th>
 </tr>
{foreach $mailBlocks as $curMailBlock}
 <tr>
  <td class="cellStd" style="border:none; text-align:right;"><span class="fontNorm">{$curMailBlock[1]}</span></td>
  <td class="cellStd" style="border:none; text-align:center;"><span class="fontNorm">@</span></td>
  <td class="cellStd" style="border:none; text-align:center;"><span class="fontNorm">{$curMailBlock[2]}</span></td>
  <td class="cellStd" style="border:none; text-align:center;"><span class="fontNorm">.</span></td>
  <td class="cellStd" style="border:none; text-align:left;"><span class="fontNorm">{$curMailBlock[3]}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$curMailBlock[4]}</span></td>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_MAIL_BLOCK_BLOCKS_TABLE_BODY}
  <td class="cellStd" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=adminMailBlock&amp;mode=kill&amp;id={$curMailBlock[0]}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/email_delete.png" alt="{Language::getInstance()->getString('delete')}" style="vertical-align:middle;" /> {Language::getInstance()->getString('delete')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="7" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{Language::getInstance()->getString('no_mail_blocks_available')}</span></td></tr>
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('options')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=adminMailBlock&amp;mode=new{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/email_add.png" alt="{Language::getInstance()->getString('add_new_mail_block')}" class="imageIcon" /> {Language::getInstance()->getString('add_new_mail_block')}</a>{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_MAIL_BLOCK_BLOCKS_OPTIONS}</span></td></tr>
</table>
{include file='AdminMenuTail.tpl'}