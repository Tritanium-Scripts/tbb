<!-- NewsletterReadLetter -->
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><td class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('read_newsletter')}</span></td></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_NEWSLETTER_READ_LETTER_FORM_START}
 <tr>
  <td class="td1" style="width:15%;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('from_colon')}</span></td>
  <td class="td1" style="width:85%;"><span class="norm">{$author}</span></td>
 </tr>
 <tr>
  <td class="td1" style="width:15%;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('date_colon')}</span></td>
  <td class="td1" style="width:85%;"><span class="norm">{$date}</span></td>
 </tr>
 <tr>
  <td class="td1" style="width:15%;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('subject_colon')}</span></td>
  <td class="td1" style="width:85%;"><span class="norm">{$subject}</span></td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_NEWSLETTER_READ_LETTER_FORM_END}{if Config::getInstance()->getCfgVal('tspacing') < 1}
 <tr><td colspan="2" class="td1"><hr /></td></tr>{/if}
 <tr><td colspan="2" class="td1"><span class="norm">{$message}</span></td></tr>
</table>