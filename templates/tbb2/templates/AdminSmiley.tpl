{include file='AdminMenu.tpl'}
<!-- AdminSmiley -->
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="5"><span class="fontTitle">{$modules.Language->getString('smilies')}</span></th></tr>
 <tr>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('smiley')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('address')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('synonym')}</span></th>
  <th class="cellCat" colspan="2" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('options')}</span></th>
 </tr>
{foreach $smilies as $curSmiley}
 <tr>
  <td class="cellStd" style="text-align:center;"><img src="{$curSmiley[2]}" alt="{$curSmiley[1]}" /></td>
  <td class="cellAlt"><span class="fontSmall">{$curSmiley[2]}</span></td>
  <td class="cellStd"><span class="fontSmall">{$curSmiley[1]}</span></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=move{if $curSmiley@first}down&amp;id={$curSmiley[0]}{$smarty.const.SID_AMPER}">&d{elseif $curSmiley@last}up&amp;id={$curSmiley[0]}{$smarty.const.SID_AMPER}">&u{else}down&amp;id={$curSmiley[0]}{$smarty.const.SID_AMPER}">&darr;</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=moveup&amp;id={$curSmiley[0]}{$smarty.const.SID_AMPER}">&u{/if}arr;</a></span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=kill&amp;id={$curSmiley[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('delete')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=edit&amp;id={$curSmiley[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit')}</a></span></td>
 </tr>
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="5"><span class="fontTitle">{$modules.Language->getString('asmilies')}</span></th></tr>
 <tr>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('smiley')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('address')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('synonym')}</span></th>
  <th class="cellCat" colspan="2" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('options')}</span></th>
 </tr>
{foreach $aSmilies as $curASmiley}
 <tr>
  <td class="cellStd" style="text-align:center;"><img src="{$curASmiley[2]}" alt="{$curASmiley[1]}" /></td>
  <td class="cellAlt"><span class="fontSmall">{$curASmiley[2]}</span></td>
  <td class="cellStd"><span class="fontSmall">{$curASmiley[1]}</span></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=move{if $curASmiley@first}downa&amp;id={$curASmiley[0]}{$smarty.const.SID_AMPER}">&d{elseif $curASmiley@last}upa&amp;id={$curASmiley[0]}{$smarty.const.SID_AMPER}">&u{else}downa&amp;id={$curASmiley[0]}{$smarty.const.SID_AMPER}">&darr;</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=moveupa&amp;id={$curASmiley[0]}{$smarty.const.SID_AMPER}">&u{/if}arr;</a></span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=killa&amp;id={$curASmiley[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('delete')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=edita&amp;id={$curASmiley[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit')}</a></span></td>
 </tr>
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="4"><span class="fontTitle">{$modules.Language->getString('post_icons')}</span></th></tr>
 <tr>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('post_icon')}</span></th>
  <th class="cellCat" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('address')}</span></th>
  <th class="cellCat" colspan="2" style="text-align:center;"><span class="fontCat">{$modules.Language->getString('options')}</span></th>
 </tr>
{foreach $tSmilies as $curTSmiley}
 <tr>
  <td class="cellStd" style="text-align:center;"><img src="{$curTSmiley[1]}" alt="" /></td>
  <td class="cellAlt"><span class="fontSmall">{$curTSmiley[1]}</span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=move{if $curTSmiley@first}downt&amp;id={$curTSmiley[0]}{$smarty.const.SID_AMPER}">&d{elseif $curTSmiley@last}upt&amp;id={$curTSmiley[0]}{$smarty.const.SID_AMPER}">&u{else}downt&amp;id={$curTSmiley[0]}{$smarty.const.SID_AMPER}">&darr;</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=moveupt&amp;id={$curTSmiley[0]}{$smarty.const.SID_AMPER}">&u{/if}arr;</a></span></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=killt&amp;id={$curTSmiley[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('delete')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=editt&amp;id={$curTSmiley[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit')}</a></span></td>
 </tr>
{/foreach}
</table>
<br />
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle"><span class="fontTitle">{$modules.Language->getString('options')}</span></th></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=new{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_smiley')}</a></span></td></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=newa{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_asmiley')}</a></span></td></tr>
 <tr><td class="cellStd"><span class="fontNorm"><a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=newt{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_post_icon')}</a></span></td></tr>
</table>
{include file='AdminMenuTail.tpl'}