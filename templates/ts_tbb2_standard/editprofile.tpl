<form method="post" action="index.php?faction=editprofile&amp;doit=1&amp;{$MYSID}" name="editprofile_form">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['User_administration']}</span></td></tr>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['General_information']}</span></td></tr>
<template:errorrow>
 <tr><td colspan="2" class="error"><span class="fonterror">{$error}</span></td></tr>
</template>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['User_name']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm">{$USER_DATA['user_nick']}</span></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Posts']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm">{$USER_DATA['user_posts']}</span></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Email_address']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm"><input class="form_text" type="text" name="p_email" size="40" value="{$p_email}" /></span></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Show_email_address']}:</span><br /><span class="fontsmall">{$LNG['show_email_address_info']}</span></td>
 <td class="cellalt" width="75%" valign="top"><select name="p_user_hide_email" class="form_select"><option value="1"<if:"{$p_user_hide_email} == 1"> selected="selected"</if>>{$LNG['No']}</option><option value="0"<if:"{$p_user_hide_email} == 0"> selected="selected"</if>>{$LNG['Yes']}</option></select></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Receive_board_emails']}:</span><br /><span class="fontsmall">{$LNG['receive_board_emails_info']}</span></td>
 <td class="cellalt" width="75%" valign="top"><select name="p_user_receive_emails" class="form_select"><option value="1"<if:"{$p_user_receive_emails} == 1"> selected="selected"</if>>{$LNG['Yes']}</option><option value="0"<if:"{$p_user_receive_emails} == 0"> selected="selected"</if>>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Timezone']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><select class="form_select" name="p_user_tz">
 <template:tzrow>
  <option value="{$akt_tz_id}"{$akt_checked}>{$akt_tz_name}</option>
 </template>
 </select></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Real_name']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm"><input class="form_text" type="text" name="p_name" size="25" value="{$p_name}" /></span></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Location']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm"><input class="form_text" type="text" name="p_location" size="40" value="{$p_location}" /></span></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Homepage']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm"><input class="form_text" type="text" name="p_hp" size="25" value="{$p_hp}" /></span></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Interests']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm"><input class="form_text" type="text" name="p_interests" size="25" value="{$p_interests}" /></span></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['ICQ']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm"><input class="form_text" type="text" name="p_icq" size="25" value="{$p_icq}" /></span></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Yahoo']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm"><input class="form_text" type="text" name="p_yahoo" size="25" value="{$p_yahoo}" /></span></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['AIM']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm"><input class="form_text" type="text" name="p_aim" size="25" value="{$p_aim}" /></span></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['MSN']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm"><input class="form_text" type="text" name="p_msn" size="25" value="{$p_msn}" /></span></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Signature']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm"><textarea class="form_textarea" name="p_signature" cols="60" rows="8" class="postbox">{$p_signature}</textarea></span></td>
</tr>
<template:avatarrow>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Avatar']}</span></td></tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Avatar_address']}:</span><br /><span class="fontsmall">{$LNG['Path_or_url']}</span></td>
 <td class="cellalt" width="75%" valign="top"><input class="form_text" type="text" name="p_avatar_address" value="{$p_avatar_address}" size="40" /><br /><span class="fontsmall"><a href="javascript:popup('index.php?faction=editprofile&amp;mode=selectavatar&amp;{$MYSID}','selectavatarwindow','width=400,height=200,scrollbars=yes,toolbar=no')">{$LNG['Select_avatar_from_list']}</a><br /><a href="javascript:popup('index.php?faction=editprofile&amp;mode=uploadavatar&amp;{$MYSID}','uploadavatarwindow','width=500,height=250,scrollbars=yes,toolbar=no,status=yes')">{$LNG['Upload_avatar']}</a></span></td>
</tr>
</template>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Change_password']}</span></td></tr>
<template:pwerrorrow>
 <tr><td colspan="2" class="error"><span class="fonterror">{$pwerror}</span></td></tr>
</template>
<tr><td class="cellstd" colspan="2"><span class="fontsmall">{$LNG['new_password_info']}</span></td></tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['New_password']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm"><input class="form_text" type="password" name="p_pw1" size="20" /></span></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Confirm_new_password']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm"><input class="form_text" type="password" name="p_pw2" size="20" /></span></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Edit_profile']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="submit" name="p_delete_account" value="{$LNG['Delete_account']}" /></td></tr>
</table>
</form>