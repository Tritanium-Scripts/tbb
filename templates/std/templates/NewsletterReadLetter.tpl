<!-- NewsletterReadLetter -->
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><td class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('read_newsletter')}</span></td></tr>
 <tr>
  <td class="td1" style="width:15%;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('from_colon')}</span></td>
  <td class="td1" style="width:85%;"><span class="norm">{$author}</span></td>
 </tr>
 <tr>
  <td class="td1" style="width:15%;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('date_colon')}</span></td>
  <td class="td1" style="width:85%;"><span class="norm">{$date}</span></td>
 </tr>
 <tr>
  <td class="td1" style="width:15%;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('subject_colon')}</span></td>
  <td class="td1" style="width:85%;"><span class="norm">{$subject}</span></td>
 </tr>{if $modules.Config->getCfgVal('tspacing') < 1}
 <tr><td colspan="2" class="td1"><hr /></td></tr>{/if}
 <tr><td colspan="2" class="td1"><span class="norm">{$message}</span></td></tr>
</table>