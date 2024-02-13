<!-- AdminMailBlock -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('email_address')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('options')}</span></th>
 </tr>
{foreach $mailBlocks as $curMailBlock}
 <tr>
  <td class="td1"><span class="norm">{$curMailBlock[1]}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=adminMailBlock&amp;mode=kill&amp;id={$curMailBlock[0]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('delete')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="2" style="text-align:center;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('no_mail_blocks_available')}</span></td></tr>
{/foreach}
</table>
<p class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=adminMailBlock&amp;mode=new{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_mail_block')}</a></p>