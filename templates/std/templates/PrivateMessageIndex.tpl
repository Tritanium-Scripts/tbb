<!-- PrivateMessageIndex -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$pmBoxID}&amp;mode=deletemany{$urlSuffix}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall"><input type="checkbox" onclick="negateBoxes('pm');" /></span></th>
  <th class="thsmall" style="width:50%;"><span class="thsmall">{Language::getInstance()->getString('subject')}</span></th>
  <th class="thsmall"><span class="thsmall">{if $isOutbox}{Language::getInstance()->getString('to')}{else}{Language::getInstance()->getString('from')}{/if}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('date')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('options')}</span></th>
 </tr>{foreach $pms as $curPM}
 <tr>
  <td class="td1"><span class="norm"><input type="checkbox" name="deletepm[{$curPM[0]}]" value="1" /></span></td>
  <td class="td2" style="width:50%;"><span class="norm"{if $curPM[7] == '1'} style="font-weight:bold;"{/if}><a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=view&amp;pm_id={$curPM[0]}&amp;pmbox_id={$pmBoxID}{$urlSuffix}{$smarty.const.SID_AMPER}">{$curPM[1]}</a></span></td>
  <td class="td1"><span class="norm">{$curPM[3]}</span></td>
  <td class="td2" style="text-align:center;"><span class="small">{$curPM[4]}</span></td>
  <td class="td1" style="text-align:center;"><span class="small"><a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=kill&amp;pm_id={$curPM[0]}&amp;pmbox_id={$pmBoxID}{$urlSuffix}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('delete')}</a>{if !$isOutbox} | <a href="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$pmBoxID}&amp;mode=reply&amp;pm_id={$curPM[0]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('reply')}</a>{/if}</span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="5" style="text-align:center;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('no_messages_found')}</span></td></tr>
{/foreach}
</table>
{if count($pms) > 0}<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('delete_selected_pms')}" /></p>{/if}
</form>