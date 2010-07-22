<!-- PrivateMessageIndex -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$modules.Auth->getUserID()}&amp;mode=deletemany{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"></th>
  <th class="thsmall" style="width:50%;"><span class="thsmall">{$modules.Language->getString('subject')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('from')}</span></th>
  <th class="thsmall"><span class="thsmall">{$modules.Language->getString('date')}</span></th>
  <th class="thsmall"></th>
 </tr>{foreach $pms as $curPM}
 <tr>
  <td class="td1"><span class="norm"><input type="checkbox" name="deletepm[{$curPM[0]}]" value="1" /></span></td>
  <td class="td2" style="width:50%;"><span class="norm"{if $curPM[7] == '1'} style="font-weight:bold;"{/if}><a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=view&amp;pm_id={$curPM[0]}&amp;pmbox_id={$modules.Auth->getUserID()}{$smarty.const.SID_AMPER}">{$curPM[1]}</a></span></td>
  <td class="td1"><span class="norm">{$curPM[3]}</font></td>
  <td class="td2" style="text-align:center;"><span class="small">{$curPM[4]}</span></td>
  <td class="td1" style="text-align:center;"><span class="small"><a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=kill&amp;pm_id={$curPM[0]}&amp;pmbox_id={$modules.Auth->getUserID()}{$smarty.const.SID_AMPER}">{$modules.Language->getString('delete')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$modules.Auth->getUserID()}&amp;mode=reply&amp;pm_id={$curPM[0]}{$smarty.const.SID_AMPER}">{$modules.Language->getString('reply')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="5" style="text-align:center;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('no_messages_found')}</span></td></tr>
{/foreach}
</table>
{if count($pms) > 0}<br />
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('delete_selected_pms')}" /></p>{/if}
</form>