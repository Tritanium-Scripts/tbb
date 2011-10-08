<!-- PrivateMessageViewPM -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><td class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('read_pm')}</span></td></tr>
 <tr>
  <td class="td1" style="width:15%;"><span class="norm" style="font-weight:bold;">{if $isOutbox}{$modules.Language->getString('to_colon')}{else}{$modules.Language->getString('from_colon')}{/if}</span></td>
  <td class="td1" style="width:85%;"><span class="norm">{$pm[3]}</span></td>
 </tr>
 <tr>
  <td class="td1" style="width:15%;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('date_colon')}</span></td>
  <td class="td1" style="width:85%;"><span class="norm">{$pm[4]}</span></td>
 </tr>
 <tr>
  <td class="td1" style="width:15%;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('subject_colon')}</span></td>
  <td class="td1" style="width:85%;"><span class="norm">{$pm[1]}</span></td>
 </tr>{if $modules.Config->getCfgVal('tspacing') < 1}
 <tr><td colspan="2" class="td1"><hr /></td></tr>{/if}
 <tr><td colspan="2" class="td1"><span class="norm">{$pm[2]}</span></td></tr>
</table>