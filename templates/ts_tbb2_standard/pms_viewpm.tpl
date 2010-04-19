<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['View_private_message']}</span></th></tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['Date']}:</span></td>
 <td class="td1" width="85%"><span class="norm">{$pm_send_date}</span></td>
</tr>
<tr>
 <td class="td1" width="15%" style="border-bottom:black 1px solid;"><span class="norm">{$lng['Subject']}:</span></td>
 <td class="td1" width="85%" style="border-bottom:black 1px solid;"><span class="norm"><b>{$pm_data['pm_subject']}</b> {$pm_sender}</span></td>
</tr>
<tr><td class="td1" colspan="2"><span class="norm">{$pm_data['pm_text']}</span></td></tr>
</table>
<template:replyform>
<br />
<form method="post" action="index.php?faction=pms&amp;mode=viewpm&amp;pm_id={replyform.$pm_id}&amp;doit=1&amp;{replyform.$MYSID}" name="tbb_form">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{replyform.$lng['Reply']}</span></th></tr>
<template:replyform.errorrow>
 <tr><td class="error" colspan="2"><span class="error">{replyform.errorrow.$error}</span></td></tr>
</template:replyform.errorrow>
<tr>
 <td class="td1" width="20%"><span class="norm">{replyform.$lng['Recipient']}:</span><br /><span class="small">{replyform.$lng['recipient_info']}</span></td>
 <td class="td2" width="80%" valign="top"><input size=25" class="form_text" type="text" name="p_recipient" value="{replyform.$p_recipient}" /></td>
</tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{replyform.$lng['Subject']}:</span></td>
 <td class="td2" width="80%"><input size="60" class="form_text" type="text" name="p_subject" value="{replyform.$p_subject}" maxlength="255" /></td>
</tr>
<template:replyform.bbcoderow>
 <tr>
  <td class="td1" width="20%" valign="top"></td>
  <td class="td2" width="80%">{replyform.bbcoderow.$bbcode_box}</td>
 </tr>
</template:replyform.bbcoderow>
<tr>
 <td class="td1" width="20%" valign="top"><span class="norm">{replyform.$lng['Message']}:</span></td>
 <td class="td2" width="80%"><textarea class="form_textarea" rows="14" cols="80" name="p_post" onselect="storecaret();" onclick="storecaret();" onkeyup="storecaret();">{replyform.$p_message}</textarea></td>
</tr>
<tr>
 <td class="td1" width="20%" valign="top"><span class="norm">{replyform.$lng['Options']}:</span></td>
 <td class="td2" width="80%"><span class="norm">
  <template:replyform.smiliescheck>
   <input type="checkbox" name="p_smilies" value="1" onfocus="this.blur()"{replyform.smiliescheck.$checked['smilies']} /> {replyform.smiliescheck.$lng['Enable_smilies']}<br />
  </template:replyform.smiliescheck>
  <template:replyform.sigcheck>
   <input type="checkbox" name="p_signature" value="1" onfocus="this.blur()"{replyform.sigcheck.$checked['signature']} /> {replyform.sigcheck.$lng['Show_signature']}<br />
  </template:replyform.sigcheck>
  <template:replyform.bbcodecheck>
   <input type="checkbox" name="p_bbcode" value="1" onfocus="this.blur()"{replyform.bbcodecheck.$checked['bbcode']} /> {replyform.bbcodecheck.$lng['Enable_bbcode']}<br />
  </template:replyform.bbcodecheck>
  <template:replyform.htmlcodecheck>
   <input type="checkbox" name="p_htmlcode" value="1" onfocus="this.blur()"{replyform.htmlcodecheck.$checked['htmlcode']} /> {replyform.htmlcodecheck.$lng['Enable_html_code']}<br />
  </template:replyform.htmlcodecheck>
  <template:replyform.saveoutboxcheck>
   <input type="checkbox" name="p_saveoutbox" value="1" onfocus="this.blur()"{replyform.saveoutboxcheck.$checked['saveoutbox']} /> {replyform.saveoutboxcheck.$lng['Save_pm_outbox']}<br />
  </template:replyform.saveoutboxcheck>
  <template:replyform.rconfirmationcheck>
   <input type="checkbox" name="p_rconfirmation" value="1" onfocus="this.blur()"{replyform.rconfirmationcheck.$checked['rconfirmation']} /> {replyform.rconfirmationcheck.$lng['Request_read_confirmation']}<br />
  </template:replyform.rconfirmationcheck>
 </span></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{replyform.$lng['Send_private_message']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{replyform.$lng['Reset']}" /></td></tr>
</table>
</form>
</template:replyform>