{include file='AdminMenu.tpl'}
<!-- AdminCensor -->
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="3"><span class="fontTitle">{$modules.Language->getString('manage_censorships')}</span></th></tr>
 <tr>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('word')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('replacement')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('options')}</span></th>
 </tr>
{foreach $censorships as $curCensorship}
 <tr>
  <td class="cellStd"><span class="fontNorm">{$curCensorship[1]}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$curCensorship[2]}</span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=kill&amp;id={$curCensorship[0]}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/icons/censor_delete.png" alt="" style="vertical-align:middle;" /> {$modules.Language->getString('delete')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=edit&amp;id={$curCensorship[0]}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/icons/censor_edit.png" alt="" style="vertical-align:middle;" /> {$modules.Language->getString('edit')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="3" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{$modules.Language->getString('no_censorships')}</span></td></tr>
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('options')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=new{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/icons/censor_add.png" alt="" style="vertical-align:middle;" /> {$modules.Language->getString('add_new_censorship')}</a></span></td></tr>
</table>
{include file='AdminMenuTail.tpl'}