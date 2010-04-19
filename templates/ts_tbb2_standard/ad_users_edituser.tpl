<form method="post" action="administration.php?faction=ad_users&amp;mode=edituser&amp;user_id={$user_id}&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Edit_user']}</span></th></tr>
<template:errorrow>
 <tr><td class="error" colspan="2"><span class="error">{$error}</span></td></tr>
</template>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['User_id']}:</span></td>
 <td class="td2" width="80%"><span class="norm">{$user_data['user_id']}</span></td>
</tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['User_name']}:</span></td>
 <td class="td2" width="80%"><span class="norm">{$user_data['user_nick']}</span></td>
</tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['Email_address']}:</span></td>
 <td class="td2" width="80%"><input class="form_text" type="text" name="p_user_email" value="{$p_user_email}" size="40" /></td>
</tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['Homepage']}:</span></td>
 <td class="td2" width="80%"><input class="form_text" type="text" name="p_user_hp" value="{$p_user_hp}" size="60" /></td>
</tr>
<tr>
 <td class="td1" width="20%" valign="top"><span class="norm">{$lng['Signature']}:</span></td>
 <td class="td2" width="80%" valign="top"><textarea cols="50" rows="5" class="form_textarea">{$p_user_signature}</textarea></td>
</tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['Avatar']}:</span></td>
 <td class="td2" width="80%"><input class="form_text" type="text" name="p_user_avatar_address" value="{$p_user_avatar_address}" size="60" /></td>
</tr>
<tr>
 <td class="td1" width="20%" valign="top"><span class="norm">{$lng['Options']}:</span></td>
 <td class="td2" width="80%" valign="top"><span class="norm"><input type="checkbox" name="p_user_is_admin" value="1"{$checked['isadmin']} />&nbsp;{$lng['User_is_admin']}<br /><input type="checkbox" name="p_user_is_supermod" value="1"{$checked['issupermod']} />&nbsp;{$lng['User_is_supermod']}</span></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Edit_user']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>
<template:lockeduserform>
 <form method="post" action="administration.php?faction=ad_users&amp;mode=unlockuser&amp;user_id={$user_id}&amp;{$MYSID}">
 <table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Unlock_user']}</span></th></tr>
 <tr>
  <td class="td1"><span class="norm">{$lng['Ban_type']}:</span></td>
  <td class="td2"><span class="norm"><if:"{$lock_data['lock_type']} == 1">{$lng['User_must_not_login']}<else />{$lng['User_must_not_write']}</if></span></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm">{$lng['Remaining_lock_time']}:</span></td>
  <td class="td2"><span class="norm">{$remaining_lock_time}</span></td>
 </tr>
 <tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Unlock_user']}" /></td></tr>
 </table>
 </form>
</template>
<template:lockuserform>
 <form method="post" action="administration.php?faction=ad_users&amp;mode=lockuser&amp;user_id={$user_id}&amp;{$MYSID}">
 <table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Lock_user']}</span></th></tr>
 <tr>
  <td class="td1"><span class="norm">{$lng['Ban_type']}:</span></td>
  <td class="td2"><select class="form_select" name="p_lock_type"><option value="1">{$lng['User_must_not_login']}</option><option value="2">{$lng['User_must_not_write']}</option></select></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm">{$lng['Ban_time']}:</span></td>
  <td class="td2"><input class="form_text" type="text" name="p_lock_time" /> <span class="small">({$lng['ban_time_info']})</span></td>
 </tr>
 <tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Lock_user']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
 </table>
 </form>
</template>
<form method="post" action="administration.php?faction=ad_users&amp;mode=deleteuser&amp;user_id={$user_id}&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm"><span class="thnorm">{$lng['Delete_user']}</span></th></tr>
<tr><td class="td1"><span class="norm"><input type="checkbox" name="p_delete_posts" value="1" />&nbsp;{$lng['Delete_users_posts']}</span></td></tr>
<tr><td class="td1"><span class="norm"><input type="checkbox" name="p_ban_nick_email" value="1" checked="checked" />&nbsp;{$lng['Ban_nick_email']}</span></td></tr>
<tr><td class="buttonrow" align="center"><input class="form_bbutton" type="submit" value="{$lng['Delete_user']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>
