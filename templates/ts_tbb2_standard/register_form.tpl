<form method="post" action="index.php?faction=register&amp;mode=register&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Register']}</span></th></tr>
<template:errorrow>
<tr><td class="error" colspan="2"><span class="error">{$error}</span></td></tr>
</template>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['Required_information']}</span></td></tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['User_name']}:*</span><br /><span class="small">{$lng['nick_conventions']}</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_user_name" maxlength="15" size="20" value="{$p_user_name}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Email_address']}:*</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_user_email" size="25" value="{$p_user_email}" /></span></td>
</tr>
<template:userpw>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Password']}:*</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="password" name="p_user_pw1" size="20" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Confirm_password']}:*</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="password" name="p_user_pw2" size="20" /></span></td>
</tr>
</template>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['Other_information']}</span></td></tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Real_name']}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_name" size="25" value="{$p_name}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Location']}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_location" size="40" value="{$p_location}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Homepage']}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_hp" size="25" value="{$p_hp}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Interests']}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_interests" size="25" value="{$p_interests}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['ICQ']}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_icq" size="25" value="{$p_icq}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Yahoo']}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_yahoo" size="25" value="{$p_yahoo}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['AIM']}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_aim" size="25" value="{$p_aim}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['MSN']}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_msn" size="25" value="{$p_msn}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Signature']}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><textarea class="form_textarea" name="p_signature" cols="45" rows="5" class="postbox">{$p_signature}</textarea></span></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input type="submit" name="p_submit" value="{$lng['Register']}" class="form_bbutton" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" name="p_reset" value="{$lng['Reset']}" />
</table>
</form>