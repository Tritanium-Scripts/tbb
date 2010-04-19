<form method="post" action="index.php?faction=viewprofile&amp;profile_id={$profile_id}&amp;mode=sendmail&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Send_email']}</span></td></tr>
<if:"{$error} != ''"><tr><td class="cellerror" colspan="2"><span class="fonterror">{$error}</span></td></tr></if>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['Recipient']}:</span></td>
 <td class="cellstd" width="85%"><span class="fontnorm">{$profile_data['user_nick']}</span></td>
</tr>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['Subject']}:</span></td>
 <td class="cellstd" width="85%"><input class="form_text" type="text" size="40" name="p_mail_subject" value="{$p_mail_subject}" /></td>
</tr>
<tr>
 <td class="cellstd" width="15%" valign="top"><span class="fontnorm">{$LNG['Message']}:</span></td>
 <td class="cellstd" width="85%"><textarea class="form_textarea" name="p_mail_message" cols="100" rows="15">{$p_mail_message}</textarea></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Send_email']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
