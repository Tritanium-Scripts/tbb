<!-- PrivateMessageViewPM -->
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="15%" />
  <col width="85%" />
 </colgroup>
 <tr><td class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('read_pm')}</span></td></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_PRIVATE_MESSAGE_VIEW_PM_FORM_START}
 <tr>
  <td class="cellAlt"><span class="fontNorm">{Language::getInstance()->getString('date_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$pm[4]}</span></td>
 </tr>
 <tr>
  <td class="cellAlt"><span class="fontNorm">{Language::getInstance()->getString('subject_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm"><b>{$pm[1]}</b> {if $isOutbox}{$pm[3]|string_format:Language::getInstance()->getString('to_x')}{else}{$pm[3]|string_format:Language::getInstance()->getString('from_x', 'Profile')}{/if}</span></td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_PRIVATE_MESSAGE_VIEW_PM_FORM_END}
 <tr><td colspan="2" class="cellStd"><span class="fontNorm">{$pm[2]}</span></td></tr>
</table>