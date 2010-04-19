<form method="post" action="index.php?faction=editprofile&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng["View_change_my_profile"]}</span></th></tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng["General_information"]}</span></td></tr>
<!-- TPLBLOCK errorrow -->
 <tr><td colspan="2" class="error"><span class="error">{errorrow.$error}</span></td></tr>
<!-- /TPLBLOCK errorrow -->
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng["User_name"]}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm">{$user_data["user_nick"]}</span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng["Posts"]}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm">{$user_data["user_posts"]}</span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng["Email_address"]}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_email" size="25" value="{$p_email}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng["Real_name"]}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_name" size="25" value="{$p_name}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng["Location"]}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_location" size="40" value="{$p_location}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng["Homepage"]}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_hp" size="25" value="{$p_hp}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng["Interests"]}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_interests" size="25" value="{$p_interests}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng["ICQ"]}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_icq" size="25" value="{$p_icq}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng["Yahoo"]}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_yahoo" size="25" value="{$p_yahoo}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng["AIM"]}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_aim" size="25" value="{$p_aim}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng["MSN"]}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_msn" size="25" value="{$p_msn}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng["Signature"]}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><textarea class="form_textarea" name="p_signature" cols="45" rows="5" class="postbox">{$p_signature}</textarea></span></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng["Change_password"]}</span></td></tr>
<!-- TPLBLOCK pwerrorrow -->
 <tr><td colspan="2" class="error"><span class="error">{pwerrorrow.$pwerror}</span></td></tr>
<!-- /TPLBLOCK pwerrorrow -->
<tr><td class="td1" colspan="2"><span class="small">{$lng["new_password_info"]}</span></td></tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng["New_password"]}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="password" name="p_pw1" size="20" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng["Confirm_new_password"]}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="password" name="p_pw2" size="20" /></span></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng["Edit_profile"]}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng["Reset"]}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="submit" name="p_delete_account" value="{$lng["Delete_account"]}" /></td></tr>
</table>
</form>