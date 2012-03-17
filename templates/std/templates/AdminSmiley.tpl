<!-- AdminSmiley -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><td class="kat" colspan="5"><span class="kat">{$modules.Language->getString('smilies')}</span></td></tr>
 <tr>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('smiley')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('address')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('synonym')}</span></th>
  <th class="thsmall" colspan="2"><span class="thsmall">{$modules.Language->getString('options')}</span></th>
 </tr>
{foreach $smilies as $curSmiley}
 <tr>
  <td class="td1" style="text-align:center;"><img src="{$curSmiley[2]}" alt="{$curSmiley[1]}" /></td>
  <td class="td2"><span class="norm">{$curSmiley[2]}</span></td>
  <td class="td1"><span class="norm">{$curSmiley[1]}</span></td>
  <td class="td2" style="text-align:center;"><span class="norm"><a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=move{if $curSmiley@first}down&amp;id={$curSmiley[0]}{$smarty.const.SID_AMPER}">&d{elseif $curSmiley@last}up&amp;id={$curSmiley[0]}{$smarty.const.SID_AMPER}">&u{else}down&amp;id={$curSmiley[0]}{$smarty.const.SID_AMPER}">&darr;</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=moveup&amp;id={$curSmiley[0]}{$smarty.const.SID_AMPER}">&u{/if}arr;</a></span></td>
  <td class="td1" style="text-align:center;"><span class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=edit&amp;id={$curSmiley[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit')}</a> | <a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=kill&amp;id={$curSmiley[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('delete')}</a></span></td>
 </tr>
{/foreach}
</table>
<p class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=new{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_smiley')}</a></p>

<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><td class="kat" colspan="5"><span class="kat">{$modules.Language->getString('asmilies')}</span></td></tr>
 <tr>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('smiley')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('address')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('synonym')}</span></th>
  <th class="thsmall" colspan="2"><span class="thsmall">{$modules.Language->getString('options')}</span></th>
 </tr>
{foreach $aSmilies as $curASmiley}
 <tr>
  <td class="td1" style="text-align:center;"><img src="{$curASmiley[2]}" alt="{$curASmiley[1]}" /></td>
  <td class="td2"><span class="norm">{$curASmiley[2]}</span></td>
  <td class="td1"><span class="norm">{$curASmiley[1]}</span></td>
  <td class="td2" style="text-align:center;"><span class="norm"><a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=move{if $curASmiley@first}downa&amp;id={$curASmiley[0]}{$smarty.const.SID_AMPER}">&d{elseif $curASmiley@last}upa&amp;id={$curASmiley[0]}{$smarty.const.SID_AMPER}">&u{else}downa&amp;id={$curASmiley[0]}{$smarty.const.SID_AMPER}">&darr;</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=moveupa&amp;id={$curASmiley[0]}{$smarty.const.SID_AMPER}">&u{/if}arr;</a></span></td>
  <td class="td1" style="text-align:center;"><span class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=edita&amp;id={$curASmiley[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit')}</a> | <a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=killa&amp;id={$curASmiley[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('delete')}</a></span></td>
 </tr>
{/foreach}
</table>
<p class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=newa{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_asmiley')}</a></p>

<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><td class="kat" colspan="4"><span class="kat">{$modules.Language->getString('post_icons')}</span></td></tr>
 <tr>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('post_icon')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('address')}</span></th>
  <th class="thsmall" colspan="2"><span class="thsmall">{$modules.Language->getString('options')}</span></th>
 </tr>
{foreach $tSmilies as $curTSmiley}
 <tr>
  <td class="td1" style="text-align:center;"><img src="{$curTSmiley[1]}" alt="" /></td>
  <td class="td2"><span class="norm">{$curTSmiley[1]}</span></td>
  <td class="td1" style="text-align:center;"><span class="norm"><a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=move{if $curTSmiley@first}downt&amp;id={$curTSmiley[0]}{$smarty.const.SID_AMPER}">&d{elseif $curTSmiley@last}upt&amp;id={$curTSmiley[0]}{$smarty.const.SID_AMPER}">&u{else}downt&amp;id={$curTSmiley[0]}{$smarty.const.SID_AMPER}">&darr;</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=moveupt&amp;id={$curTSmiley[0]}{$smarty.const.SID_AMPER}">&u{/if}arr;</a></span></td>
  <td class="td2" style="text-align:center;"><span class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=editt&amp;id={$curTSmiley[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('edit')}</a>&nbsp;|&nbsp;<a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=killt&amp;id={$curTSmiley[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('delete')}</a></span></td>
 </tr>
{/foreach}
</table>
<p class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_smilies&amp;mode=newt{$smarty.const.SID_AMPER}">{$modules.Language->getString('add_new_post_icon')}</a></p>