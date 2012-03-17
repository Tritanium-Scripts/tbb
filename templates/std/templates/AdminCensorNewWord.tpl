<!-- AdminCensorNewWord -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=new{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('add_new_censorship')}</span></th></tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('word_colon')}</span></td>
  <td class="td1"><input type="text" name="word" value="{$newWord}" /> <span class="small">{$modules.Language->getString('case_insensitivity_hint')}</span></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('replacement_colon')}</span></td>
  <td class="td1"><input type="text" name="replacement" value="{$newReplacement}" /></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('add_new_censorship')}" /></p>
<input type="hidden" name="create" value="1" />
</form>