<!-- SendMail -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=formmail&amp;target_id={$userData.recipientID}{$smarty.const.SID_AMPER}" onsubmit="return document.getElementsByName('message')[0].value == '' ? confirm('{Language::getInstance()->getString('really_send_empty_mail')}') : true;">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('send_mail')}</th></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('recipient_colon')}</span></td>
  <td class="td1" style="width:80%;"><span class="norm">{$userData.recipientName}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('from_colon')}</span></td>
  <td class="td1" style="width:80%;">{if Auth::getInstance()->isLoggedIn()}<span class="norm">{Auth::getInstance()->getUserNick()}</span>{else}<input type="text" value="{$senderName}" name="sender_name" />{/if}</td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('email_address_colon')}</span></td>
  <td class="td1" style="width:80%;">{if Auth::getInstance()->isLoggedIn()}<span class="norm">{Auth::getInstance()->getUserMail()}</span>{else}<input type="text" value="{$senderMail}" name="sender_email" />{/if}</td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%;"><span class="norm">{Language::getInstance()->getString('subject_colon')}</span></td>
  <td class="td1" style="width:80%;"><input type="text" size="30" name="subject" value="{$subject}" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:20%; vertical-align:top;"><span class="norm">{Language::getInstance()->getString('message_colon')}</span></td>
  <td class="td1" style="width:80%;"><textarea name="message" cols="60" rows="8">{$message}</textarea></td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('send_mail')}" /></p>
<input type="hidden" name="send" value="yes" />
</form>