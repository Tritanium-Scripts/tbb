<!-- PrivateMessageIndex -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$pmBoxID}&amp;mode=deletemany{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall"><input type="checkbox" onclick="negateBoxes('pm');" /></span></th>
  <th class="cellTitle" style="text-align:center;">&nbsp;</th>
  <th class="cellTitle" style="text-align:center; width:50%;"><span class="fontTitleSmall">{$modules.Language->getString('subject')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{$modules.Language->getString('from')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{$modules.Language->getString('date')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{$modules.Language->getString('options')}</span></th>
 </tr>{foreach $pms as $curPM}
 <tr>
  <td class="cellAlt" style="text-align:center;"><input type="checkbox" name="deletepm[{$curPM[0]}]" value="1" /></td>
  <td class="cellAlt" style="text-align:center;"><img src="{$modules.Template->getTplDir()}images/icons/pm{if $curPM[7] != '1'}_open{/if}.png" alt="" /></td>
  <td class="cellStd" style="width:50%;"><span class="fontNorm"{if $curPM[7] == '1'} style="font-weight:bold;"{/if}><a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=view&amp;pm_id={$curPM[0]}&amp;pmbox_id={$pmBoxID}{$smarty.const.SID_AMPER}">{$curPM[1]}</a></span></td>
  <td class="cellAlt"><span class="fontSmall">{$curPM[3]}</span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontSmall">{$curPM[4]}</span></td>
  <td class="cellAlt" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=kill&amp;pm_id={$curPM[0]}&amp;pmbox_id={$pmBoxID}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/icons/pm_delete.png" alt="{$modules.Language->getString('delete')}" /></a> <a href="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$pmBoxID}&amp;mode=reply&amp;pm_id={$curPM[0]}{$smarty.const.SID_AMPER}"><img src="{$modules.Template->getTplDir()}images/icons/pm_reply.png" alt="{$modules.Language->getString('reply')}" /></a></span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="6" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{$modules.Language->getString('no_messages_found')}</span></td></tr>
{/foreach}
</table>
{if count($pms) > 0}<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('delete_selected_pms')}" /></p>{/if}
</form>