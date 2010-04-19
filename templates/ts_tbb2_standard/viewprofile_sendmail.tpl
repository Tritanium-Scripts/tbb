<form method="post" action="index.php?faction=viewprofile&amp;profile_id={$profile_id}&amp;mode=sendmail&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Send_email']}</span></th></tr>
<if:"{$error} != ''"><tr><td class="error" colspan="2"><span class="error">{$error}</span></td></tr></if>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['Recipient']}:</span></td>
 <td class="td1" width="85%"><span class="norm">{$profile_data['user_nick']}</span></td>
</tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['Subject']}:</span></td>
 <td class="td1" width="85%"><input class="form_text" type="text" size="40" name="p_mail_subject" value="{$p_mail_subject}" /></td>
</tr>
<tr>
 <td class="td1" width="15%" valign="top"><span class="norm">{$lng['Message']}:</span></td>
 <td class="td1" width="85%"><textarea class="form_textarea" name="p_mail_message" cols="100" rows="15">{$p_mail_message}</textarea></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Send_email']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>
