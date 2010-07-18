<!-- SendMail -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=formmail&amp;target_id={$userData.recipientID}{$smarty.const.SID_AMPER}" onsubmit="return document.getElementsByName('message')[0].value == '' ? confirm('{$modules.Language->getString('really_send_empty_mail')}') : true;">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('send_mail')}</th></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('recipient_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{$userData.recipientName}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('from_colon')}</span></td>
  <td class="td1" style="width:80%;">{if $modules.Auth->isLoggedIn()}<span class="norm">{$modules.Auth->getUserNick()}</span>{else}<input type="text" value="{$senderName}" name="sender_name" />{/if}</td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('email_address_colon')}</span></td>
  <td class="td1" style="width:80%;">{if $modules.Auth->isLoggedIn()}<span class="norm">{$modules.Auth->getUserMail()}</span>{else}<input type="text" value="{$senderMail}" name="sender_email" />{/if}</td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{$modules.Language->getString('subject_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" size="30" name="subject" value="{$subject}" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%; vertical-align:top;"><span class="norm">{$modules.Language->getString('message_colon')}</span></td>
  <td class="td1" style="width:80%;"><textarea name="message" cols="60" rows="8">{$message}</textarea></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('send_mail')}" /></p>
<input type="hidden" name="send" value="yes" />
</form>