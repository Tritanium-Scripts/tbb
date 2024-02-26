{include file='AdminMenu.tpl'}
<!-- AdminCensorNewWord -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=new{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('add_new_censorship')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CENSOR_NEW_CENSORSHIP_FORM_START}
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('word_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="word" value="{$newWord}" /> <span class="fontSmall">{Language::getInstance()->getString('case_insensitivity_hint')}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('replacement_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="replacement" value="{$newReplacement}" /></td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CENSOR_NEW_CENSORSHIP_FORM_END}
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('add_new_censorship')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CENSOR_NEW_CENSORSHIP_BUTTONS}</p>
<input type="hidden" name="create" value="1" />
</form>
{include file='AdminMenuTail.tpl'}