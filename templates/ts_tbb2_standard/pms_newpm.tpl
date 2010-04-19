<form method="post" action="index.php?faction=pms&amp;mode=newpm&amp;doit=1&amp;{$MYSID}" name="tbb_form">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['New_private_message']}</span></th></tr>
<template:errorrow>
 <tr><td class="error" colspan="2"><span class="error">{errorrow.$error}</span></td></tr>
</template:errorrow>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['Recipient']}:</span><br /><span class="small">{$lng['recipient_info']}</span></td>
 <td class="td2" width="80%" valign="top"><input size=25" class="form_text" type="text" name="p_recipient" value="{$p_recipient}" /></td>
</tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['Subject']}:</span></td>
 <td class="td2" width="80%"><input size="60" class="form_text" type="text" name="p_subject" value="{$p_subject}" maxlength="255" /></td>
</tr>
<template:bbcoderow>
 <tr>
  <td class="td1" width="20%" valign="top"></td>
  <td class="td2" width="80%">{bbcoderow.$bbcode_box}</td>
 </tr>
</template:bbcoderow>
<tr>
 <td class="td1" width="20%" valign="top"><span class="norm">{$lng['Message']}:</span></td>
 <td class="td2" width="80%"><textarea class="form_textarea" rows="14" cols="80" name="p_post" onselect="storecaret();" onclick="storecaret();" onkeyup="storecaret();">{$p_message}</textarea></td>
</tr>
<tr>
 <td class="td1" width="20%" valign="top"><span class="norm">{$lng['Options']}:</span></td>
 <td class="td2" width="80%"><span class="norm">
  <template:smiliescheck>
   <input type="checkbox" name="p_smilies" value="1" onfocus="this.blur()"{smiliescheck.$checked['smilies']} /> {smiliescheck.$lng['Enable_smilies']}<br />
  </template:smiliescheck>
  <template:sigcheck>
   <input type="checkbox" name="p_signature" value="1" onfocus="this.blur()"{sigcheck.$checked['signature']} /> {sigcheck.$lng['Show_signature']}<br />
  </template:sigcheck>
  <template:bbcodecheck>
   <input type="checkbox" name="p_bbcode" value="1" onfocus="this.blur()"{bbcodecheck.$checked['bbcode']} /> {bbcodecheck.$lng['Enable_bbcode']}<br />
  </template:bbcodecheck>
  <template:htmlcodecheck>
   <input type="checkbox" name="p_htmlcode" value="1" onfocus="this.blur()"{htmlcodecheck.$checked['htmlcode']} /> {htmlcodecheck.$lng['Enable_html_code']}<br />
  </template:htmlcodecheck>
  <template:saveoutboxcheck>
   <input type="checkbox" name="p_saveoutbox" value="1" onfocus="this.blur()"{saveoutboxcheck.$checked['saveoutbox']} /> {saveoutboxcheck.$lng['Save_pm_outbox']}<br />
  </template:saveoutboxcheck>
  <template:rconfirmationcheck>
   <input type="checkbox" name="p_rconfirmation" value="1" onfocus="this.blur()"{rconfirmationcheck.$checked['rconfirmation']} /> {rconfirmationcheck.$lng['Request_read_confirmation']}<br />
  </template:rconfirmationcheck>
 </span></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Send_private_message']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>