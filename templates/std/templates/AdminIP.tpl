<!-- AdminIP -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('ip_address')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('remaining_blocking_period')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('blocked_for')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('options')}</span></th>
 </tr>
{foreach $ipBlocks as $curIPBlock}
 <tr>
  <td class="td1"><span class="norm">{$curIPBlock[0]}</span></td>
  <td class="td2"><span class="norm">{$curIPBlock[1]}</span></td>
  <td class="td1"><span class="norm">{$curIPBlock[2]}</span></td>
  <td class="td2" style="text-align:center;"><span class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_ip&amp;mode=kill&amp;id={$curIPBlock[3]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('delete')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="4" style="text-align:center;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('no_ip_blocks_available')}</span></td></tr>
{/foreach}
</table>
<p class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_ip&amp;mode=new{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_ip_block')}</a></p>