<!-- NewsletterReadLetter -->
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><td class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('read_newsletter')}</span></td></tr>
 <tr>
  <td class="cellStd" style="width:15%;"><span class="fontNorm" style="font-weight:bold;">{$modules.Language->getString('from_colon')}</span></td>
  <td class="cellAlt" style="width:85%;"><span class="fontNorm">{$author}</span></td>
 </tr>
 <tr>
  <td class="cellStd" style="width:15%;"><span class="fontNorm" style="font-weight:bold;">{$modules.Language->getString('date_colon')}</span></td>
  <td class="cellAlt" style="width:85%;"><span class="fontNorm">{$date}</span></td>
 </tr>
 <tr>
  <td class="cellStd" style="width:15%;"><span class="fontNorm" style="font-weight:bold;">{$modules.Language->getString('subject_colon')}</span></td>
  <td class="cellAlt" style="width:85%;"><span class="fontNorm">{$subject}</span></td>
 </tr>
 <tr><td colspan="2" class="cellAlt"><span class="fontNorm">{$message}</span></td></tr>
</table>