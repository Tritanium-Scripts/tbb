{include file='AdminMenu.tpl'}
<!-- AdminCensorEditWord -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_censor&amp;mode=edit{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('edit_censorship')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CENSOR_EDIT_CENSORSHIP_FORM_START}
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('word_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="word" value="{$editWord}" /> <span class="fontSmall">{Language::getInstance()->getString('case_insensitivity_hint')}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('replacement_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="replacement" value="{$editReplacement}" /></td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CENSOR_EDIT_CENSORSHIP_FORM_END}
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('edit_censorship')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_CENSOR_EDIT_CENSORSHIP_BUTTONS}</p>
<input type="hidden" name="update" value="1" />
<input type="hidden" name="id" value="{$censorshipID}" />
</form>
{include file='AdminMenuTail.tpl'}