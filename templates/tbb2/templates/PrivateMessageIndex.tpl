<!-- PrivateMessageIndex -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$pmBoxID}&amp;mode=deletemany{$urlSuffix}{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall"><input type="checkbox" onclick="negateBoxes('pm');" /></span></th>
  <th class="cellTitle" style="text-align:center;">&nbsp;</th>
  <th class="cellTitle" style="text-align:center; width:50%;"><span class="fontTitleSmall">{Language::getInstance()->getString('subject')}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{if $isOutbox}{Language::getInstance()->getString('to')}{else}{Language::getInstance()->getString('from')}{/if}</span></th>
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{Language::getInstance()->getString('date')}</span></th>
{plugin_hook hook=PlugIns::HOOK_TPL_PRIVATE_MESSAGE_PMS_TABLE_HEAD}
  <th class="cellTitle" style="text-align:center;"><span class="fontTitleSmall">{Language::getInstance()->getString('options')}</span></th>
 </tr>{foreach $pms as $curPM}
 <tr>
  <td class="cellAlt" style="text-align:center;"><input type="checkbox" name="deletepm[{$curPM[0]}]" value="1" /></td>
  <td class="cellAlt" style="text-align:center;"><img src="{Template::getInstance()->getTplDir()}images/icons/pm{if $curPM[7] != '1'}_open{/if}.png" alt="" /></td>
  <td class="cellStd" style="width:50%;"><span class="fontNorm"{if $curPM[7] == '1'} style="font-weight:bold;"{/if}><a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=view&amp;pm_id={$curPM[0]}&amp;pmbox_id={$pmBoxID}{$urlSuffix}{$smarty.const.SID_AMPER}">{$curPM[1]}</a></span></td>
  <td class="cellAlt"><span class="fontSmall">{$curPM[3]}</span></td>
  <td class="cellStd" style="text-align:center;"><span class="fontSmall">{$curPM[4]}</span></td>
{plugin_hook hook=PlugIns::HOOK_TPL_PRIVATE_MESSAGE_PMS_TABLE_BODY}
  <td class="cellAlt" style="text-align:center;"><span class="fontSmall"><a href="{$smarty.const.INDEXFILE}?faction=pm&amp;mode=kill&amp;pm_id={$curPM[0]}&amp;pmbox_id={$pmBoxID}{$urlSuffix}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/pm_delete.png" alt="{Language::getInstance()->getString('delete')}" style="vertical-align:middle;" /> {Language::getInstance()->getString('delete')}</a>{if !$isOutbox} <a href="{$smarty.const.INDEXFILE}?faction=pm&amp;pmbox_id={$pmBoxID}&amp;mode=reply&amp;pm_id={$curPM[0]}{$smarty.const.SID_AMPER}"><img src="{Template::getInstance()->getTplDir()}images/icons/pm_reply.png" alt="{Language::getInstance()->getString('reply')}" style="vertical-align:middle;" /> {Language::getInstance()->getString('reply')}</a>{/if}</span></td>
 </tr>
{foreachelse}
 <tr><td class="cellStd" colspan="6" style="text-align:center;"><span class="fontNorm" style="font-weight:bold;">{Language::getInstance()->getString('no_messages_found')}</span></td></tr>
{/foreach}
</table>
{if count($pms) > 0}<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('delete_selected_pms')}" />{plugin_hook hook=PlugIns::HOOK_TPL_PRIVATE_MESSAGE_PMS_BUTTONS}</p>{/if}
</form>