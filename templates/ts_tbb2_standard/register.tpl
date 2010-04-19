<form method="post" action="index.php?faction=register&amp;doit=1&amp;{MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{LNG_REGISTER}</span></th></tr>
<!-- TPLBLOCK errorrow -->
<tr><td class="error" colspan="2"><span class="error">{errorrow.ERROR}</span></td></tr>
<!-- /TPLBLOCK errorrow -->
<tr><td class="cat" colspan="2"><span class="cat">{LNG_REQUIRED_INFORMATION}</span></td></tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{LNG_USER_NAME}:*</span><br /><span class="small">{LNG_NICK_CONVENTIONS}</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_nick" maxlength="15" size="20" value="{P_NICK}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{LNG_EMAIL}:*</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_email" size="25" value="{P_EMAIL}" /></span></td>
</tr>
<!-- TPLBLOCK userpw -->
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{userpw.LNG_PASSWORD}:*</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="password" name="p_pw1" size="20" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{userpw.LNG_CONFIRM_PASSWORD}:*</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="password" name="p_pw2" size="20" /></span></td>
</tr>
<!-- /TPLBLOCK userpw -->
<tr><td class="cat" colspan="2"><span class="cat">{LNG_OTHER_INFORMATION}</span></td></tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{LNG_REAL_NAME}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_name" size="25" value="{P_NAME}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{LNG_LOCATION}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_location" size="40" value="{P_LOCATION}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{LNG_HOMEPAGE}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_hp" size="25" value="{P_HP}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{LNG_INTERESTS}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_interests" size="25" value="{P_INTERESTS}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{LNG_ICQ}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_icq" size="25" value="{P_ICQ}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{LNG_YAHOO}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_yahoo" size="25" value="{P_YAHOO}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{LNG_AIM}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_aim" size="25" value="{P_AIM}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{LNG_MSN}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_msn" size="25" value="{P_MSN}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{LNG_SIGNATURE}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><textarea class="form_textarea" name="p_signature" cols="45" rows="5" class="postbox">{P_SIGNATURE}</textarea></span></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input type="submit" name="p_submit" value="{LNG_REGISTER}" class="form_bbutton" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" name="p_reset" value="{LNG_RESET}" />
</table>
</form>