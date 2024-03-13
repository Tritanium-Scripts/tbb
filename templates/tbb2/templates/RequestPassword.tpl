<!-- RequestPassword -->
{include file='Errors.tpl'}
<form action="{$smarty.const.INDEXFILE}?faction=sendpw{$smarty.const.SID_AMPER}" method="post">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('request_new_password')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_LOGIN_REQUEST_PASSWORD_FORM_START}
 <tr>
  <td class="cellStd" style="width:15%;"><span class="fontNorm" style="font-weight:bold;">{Language::getInstance()->getString('user_name_colon')}</span></td>
  <td class="cellAlt" style="width:85%;"><input class="formText" type="text" name="nick" value="{$loginName}" /></td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_LOGIN_REQUEST_PASSWORD_FORM_END}
 <tr><td class="divInfoBox" colspan="2"><span class="fontInfoBox"><img src="{Template::getInstance()->getTplDir()}images/icons/info.png" alt="" class="imageIcon" /> {Language::getInstance()->getString('create_new_password')|string_format:Language::getInstance()->getString('request_new_password_info')}</span></td></tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('create_new_password')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" />{plugin_hook hook=PlugIns::HOOK_TPL_LOGIN_REQUEST_PASSWORD_BUTTONS}</p>
<input type="hidden" name="send" value="1" />
</form>