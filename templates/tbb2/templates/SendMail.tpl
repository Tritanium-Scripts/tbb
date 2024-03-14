<!-- SendMail -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=formmail&amp;target_id={$userData.recipientID}{$smarty.const.SID_AMPER}" onsubmit="return document.getElementsByName('message')[0].value == '' ? confirm('{Language::getInstance()->getString('really_send_empty_mail')}') : true;">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="15%" />
  <col width="85%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('send_mail')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_PROFILE_SEND_MAIL_FORM_START}
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('recipient_colon')}</span></td>
  <td class="cellStd"><span class="fontNorm">{$userData.recipientName}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('from_colon')}</span></td>
  <td class="cellStd">{if Auth::getInstance()->isLoggedIn()}<span class="fontNorm">{Auth::getInstance()->getUserNick()}</span>{else}<input class="formText" type="text" value="{$senderName}" name="sender_name" />{/if}</td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('email_address_colon')}</span></td>
  <td class="cellStd">{if Auth::getInstance()->isLoggedIn()}<span class="fontNorm">{Auth::getInstance()->getUserMail()}</span>{else}<input class="formText" type="text" value="{$senderMail}" name="sender_email" />{/if}</td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('subject_colon')}</span></td>
  <td class="cellStd"><input class="formText" type="text" size="80" name="subject" value="{$subject}" /></td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('message_colon')}</span></td>
  <td class="cellStd"><textarea class="formTextArea" name="message" cols="100" rows="15">{$message}</textarea></td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_PROFILE_SEND_MAIL_FORM_END}
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('send_mail')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" />{plugin_hook hook=PlugIns::HOOK_TPL_PROFILE_SEND_MAIL_BUTTONS}</p>
<input type="hidden" name="send" value="yes" />
</form>