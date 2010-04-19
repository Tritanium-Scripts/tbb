<form method="post" action="index.php?faction=editprofile&amp;doit=1&amp;{$MYSID}" name="editprofile_form">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['View_change_my_profile']}</span></th></tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['General_information']}</span></td></tr>
<template:errorrow>
 <tr><td colspan="2" class="error"><span class="error">{$error}</span></td></tr>
</template>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['User_name']}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm">{$USER_DATA['user_nick']}</span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Posts']}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm">{$USER_DATA['user_posts']}</span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Email_address']}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="text" name="p_email" size="40" value="{$p_email}" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Show_email_address']}:</span><br /><span class="small">{$lng['show_email_address_info']}</span></td>
 <td class="td2" width="75%" valign="top"><select name="p_user_hide_email" class="form_select"><option value="1"<if:"{$p_user_hide_email} == 1"> selected="selected"</if>>{$lng['No']}</option><option value="0"<if:"{$p_user_hide_email} == 0"> selected="selected"</if>>{$lng['Yes']}</option></select></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Receive_board_emails']}:</span><br /><span class="small">{$lng['receive_board_emails_info']}</span></td>
 <td class="td2" width="75%" valign="top"><select name="p_user_receive_emails" class="form_select"><option value="1"<if:"{$p_user_receive_emails} == 1"> selected="selected"</if>>{$lng['Yes']}</option><option value="0"<if:"{$p_user_receive_emails} == 0"> selected="selected"</if>>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Timezone']}:</span></td>
 <td class="td2" width="75%" valign="top"><select class="form_select" name="p_user_tz">
 <template:tzrow>
  <option value="{$akt_tz_id}"{$akt_checked}>{$akt_tz_name}</option>
 </template>
 </select></td>
</tr>
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
 <td class="td2" width="75%" valign="top"><span class="norm"><textarea class="form_textarea" name="p_signature" cols="60" rows="8" class="postbox">{$p_signature}</textarea></span></td>
</tr>
<template:avatarrow>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['Avatar']}</span></td></tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Avatar_address']}:</span><br /><span class="small">{$lng['Path_or_url']}</span></td>
 <td class="td2" width="75%" valign="top"><input class="form_text" type="text" name="p_avatar_address" value="{$p_avatar_address}" size="40" /><br /><span class="small"><a href="javascript:popup('index.php?faction=editprofile&amp;mode=selectavatar&amp;{$MYSID}','selectavatarwindow','width=400,height=200,scrollbars=yes,toolbar=no')">{$lng['Select_avatar_from_list']}</a><br /><a href="javascript:popup('index.php?faction=editprofile&amp;mode=uploadavatar&amp;{$MYSID}','uploadavatarwindow','width=500,height=250,scrollbars=yes,toolbar=no,status=yes')">{$lng['Upload_avatar']}</a></span></td>
</tr>
</template>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['Change_password']}</span></td></tr>
<template:pwerrorrow>
 <tr><td colspan="2" class="error"><span class="error">{$pwerror}</span></td></tr>
</template>
<tr><td class="td1" colspan="2"><span class="small">{$lng['new_password_info']}</span></td></tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['New_password']}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="password" name="p_pw1" size="20" /></span></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Confirm_new_password']}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input class="form_text" type="password" name="p_pw2" size="20" /></span></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Edit_profile']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="submit" name="p_delete_account" value="{$lng['Delete_account']}" /></td></tr>
</table>
</form>