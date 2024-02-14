<!-- AdminMailBlock -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <colgroup>
  <col />
  <col width="1%" />
  <col width="1%" />
  <col width="1%" />
  <col />
  <col />
  <col />
 </colgroup>
 <tr>
  <th class="thsmall" colspan="5"><span class="thsmall">{Language::getInstance()->getString('email_address')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('remaining_blocking_period')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('options')}</span></th>
 </tr>
{foreach $mailBlocks as $curMailBlock}
 <tr>
  <td class="td1" style="text-align:right;"><span class="norm">{$curMailBlock[1]}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm">@</span></td>
  <td class="td1" style="text-align:center;"><span class="norm">{$curMailBlock[2]}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm">.</span></td>
  <td class="td1" style="text-align:left;"><span class="norm">{$curMailBlock[3]}</span></td>
  <td class="td2"><span class="norm">{$curMailBlock[4]}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=adminMailBlock&amp;mode=kill&amp;id={$curMailBlock[0]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('delete')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="7" style="text-align:center;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('no_mail_blocks_available')}</span></td></tr>
{/foreach}
</table>
<p class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=adminMailBlock&amp;mode=new{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_mail_block')}</a></p>