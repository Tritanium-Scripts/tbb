<!-- AdminCensorEditWord -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=edit{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('edit_censorship')}</span></th></tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('word_colon')}</span></td>
  <td class="td1"><input type="text" name="word" value="{$editWord}" /> <span class="small">{Language::getInstance()->getString('case_insensitivity_hint')}</span></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('replacement_colon')}</span></td>
  <td class="td1"><input type="text" name="replacement" value="{$editReplacement}" /></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('edit_censorship')}" /></p>
<input type="hidden" name="update" value="1" />
<input type="hidden" name="id" value="{$censorshipID}" />
</form>