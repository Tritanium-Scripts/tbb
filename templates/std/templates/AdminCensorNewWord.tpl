<!-- AdminCensorNewWord -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=new{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('add_new_censorship')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CENSOR_NEW_CENSORSHIP_FORM_START}
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('word_colon')}</span></td>
  <td class="td1"><input type="text" name="word" value="{$newWord}" /> <span class="small">{Language::getInstance()->getString('case_insensitivity_hint')}</span></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('replacement_colon')}</span></td>
  <td class="td1"><input type="text" name="replacement" value="{$newReplacement}" /></td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CENSOR_NEW_CENSORSHIP_FORM_END}
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('add_new_censorship')}" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CENSOR_NEW_CENSORSHIP_BUTTONS}</p>
<input type="hidden" name="create" value="1" />
</form>