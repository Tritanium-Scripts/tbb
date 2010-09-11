<!-- PrivateMessageViewPM -->
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="15%" />
  <col width="85%" />
 </colgroup>
 <tr><td class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('read_pm')}</span></td></tr>
 <tr>
  <td class="cellAlt"><span class="fontNorm">{$modules.Language->getString('date_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm">{$pm[4]}</span></td>
 </tr>
 <tr>
  <td class="cellAlt"><span class="fontNorm">{$modules.Language->getString('subject_colon')}</span></td>
  <td class="cellAlt"><span class="fontNorm"><b>{$pm[1]}</b> {$pm[3]|string_format:$modules.Language->getString('by_x', 'Forum')}</span></td>
 </tr>
 <tr><td colspan="2" class="cellStd"><span class="fontNorm">{$pm[2]}</span></td></tr>
</table>