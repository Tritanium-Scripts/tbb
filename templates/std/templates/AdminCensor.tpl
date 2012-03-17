<!-- AdminCensor -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('word')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('replacement')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('options')}</span></th>
 </tr>
{foreach $censorships as $curCensorship}
 <tr>
  <td class="td1"><span class="norm">{$curCensorship[1]}</span></td>
  <td class="td2"><span class="norm">{$curCensorship[2]}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=kill&amp;id={$curCensorship[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('delete')}</a>&nbsp;|&nbsp;<a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=edit&amp;id={$curCensorship[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="3" style="text-align:center;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('no_censorships')}</span></td></tr>
{/foreach}
</table>
<p class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=new{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_censorship')}</a></p>