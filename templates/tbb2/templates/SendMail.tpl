<!-- SendMail -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=formmail&amp;target_id={$userData.recipientID}{$smarty.const.SID_AMPER}" onsubmit="return document.getElementsByName('message')[0].value == '' ? confirm('{$modules.Language->getString('really_send_empty_mail')}') : true;">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="15%" />
  <col width="85%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{$modules.Language->getString('send_mail')}</th></tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('recipient_colon')}</span></td>
  <td class="cellStd"><span class="fontNorm">{$userData.recipientName}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('from_colon')}</span></td>
  <td class="cellStd">{if $modules.Auth->isLoggedIn()}<span class="fontNorm">{$modules.Auth->getUserNick()}</span>{else}<input class="formText" type="text" value="{$senderName}" name="sender_name" />{/if}</td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('email_address_colon')}</span></td>
  <td class="cellStd">{if $modules.Auth->isLoggedIn()}<span class="fontNorm">{$modules.Auth->getUserMail()}</span>{else}<input class="formText" type="text" value="{$senderMail}" name="sender_email" />{/if}</td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('subject_colon')}</span></td>
  <td class="cellStd"><input class="formText" type="text" size="80" name="subject" value="{$subject}" /></td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{$modules.Language->getString('message_colon')}</span></td>
  <td class="cellStd"><textarea class="formTextArea" name="message" cols="100" rows="15">{$message}</textarea></td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('send_mail')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
<input type="hidden" name="send" value="yes" />
</form>