<!-- AdminCensor -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('word')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('replacement')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('options')}</span></th>
 </tr>
{foreach $censorships as $curCensorship}
 <tr>
  <td class="td1"><span class="norm">{$curCensorship[1]}</span></td>
  <td class="td2"><span class="norm">{$curCensorship[2]}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=kill&amp;id={$curCensorship[0]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('delete')}</a>&nbsp;|&nbsp;<a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=edit&amp;id={$curCensorship[0]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="3" style="text-align:center;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('no_censorships')}</span></td></tr>
{/foreach}
</table>
<p class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=new{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_censorship')}</a></p>