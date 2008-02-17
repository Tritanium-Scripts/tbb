<form method="post" action="administration.php?faction=ad_users&amp;mode=edituser&amp;user_id={$user_id}&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Edit_user']}</span></td></tr>
<if:"{$error} != ''">

 <tr><td class="cellerror" colspan="2"><span class="fonterror">{$error}</span></td></tr>
</if>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['User_id']}:</span></td>
 <td class="cellalt" width="80%"><span class="fontnorm">{$user_data['user_id']}</span></td>
</tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['User_name']}:</span></td>
 <td class="cellalt" width="80%"><span class="fontnorm">{$user_data['user_nick']}</span></td>
</tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Email_address']}:</span></td>
 <td class="cellalt" width="80%"><input class="form_text" type="text" name="p_user_email" value="{$p_user_email}" size="40" /></td>
</tr>
<tr>
 <td class="cellstd" width="20%" valign="top"><span class="fontnorm">{$LNG['Signature']}:</span></td>
 <td class="cellalt" width="80%" valign="top"><textarea cols="50" rows="5" class="form_textarea">{$p_user_signature}</textarea></td>
</tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Avatar']}:</span></td>
 <td class="cellalt" width="80%"><input class="form_text" type="text" name="p_user_avatar_address" value="{$p_user_avatar_address}" size="60" /></td>
</tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Profile_notes']}:</span></td>
 <td class="cellalt" width="80%"><select class="form_select" name="p_user_auth_profile_notes"><option value="1"<if:"{$p_user_auth_profile_notes} == 1"> selected="selected"</if>>{$LNG['Allow']}</option><option value="1"<if:"{$p_user_auth_profile_notes} == 2"> selected="selected"</if>>{$LNG['Use_default']}</option><option value="1"<if:"{$p_user_auth_profile_notes} == 0"> selected="selected"</if>>{$LNG['Disallow']}</option></select></td>
</tr>
<tr>
 <td class="cellstd" width="20%" valign="top"><span class="fontnorm">{$LNG['Options']}:</span></td>
 <td class="cellalt" width="80%" valign="top"><span class="fontnorm">
  <input type="checkbox" name="p_user_is_admin" value="1"{$checked['isadmin']} id="check_user_is_admin" /><label for="check_user_is_admin">&nbsp;{$LNG['User_is_admin']}</label><br />
  <input type="checkbox" name="p_user_is_supermod" value="1"{$checked['issupermod']} id="check_user_is_supermod" /><label for="check_user_is_supermod">&nbsp;{$LNG['User_is_supermod']}</label><br />
 </span></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Edit_user']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
<template:lockeduserform>
 <form method="post" action="administration.php?faction=ad_users&amp;mode=unlockuser&amp;user_id={$user_id}&amp;{$MYSID}">
 <table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Unlock_user']}</span></td></tr>
 <tr>
  <td class="cellstd"><span class="fontnorm">{$LNG['Ban_type']}:</span></td>
  <td class="cellalt"><span class="fontnorm"><if:"{$lock_data['lock_type']} == 1">{$LNG['User_must_not_login']}<else />{$LNG['User_must_not_write']}</if></span></td>
 </tr>
 <tr>
  <td class="cellstd"><span class="fontnorm">{$LNG['Remaining_lock_time']}:</span></td>
  <td class="cellalt"><span class="fontnorm">{$remaining_lock_time}</span></td>
 </tr>
 <tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Unlock_user']}" /></td></tr>
 </table>
 </form>
</template>
<template:lockuserform>
 <form method="post" action="administration.php?faction=ad_users&amp;mode=lockuser&amp;user_id={$user_id}&amp;{$MYSID}">
 <table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Lock_user']}</span></td></tr>
 <tr>
  <td class="cellstd"><span class="fontnorm">{$LNG['Ban_type']}:</span></td>
  <td class="cellalt"><select class="form_select" name="p_lock_type"><option value="1">{$LNG['User_must_not_login']}</option><option value="2">{$LNG['User_must_not_write']}</option></select></td>
 </tr>
 <tr>
  <td class="cellstd"><span class="fontnorm">{$LNG['Ban_time']}:</span></td>
  <td class="cellalt"><input class="form_text" type="text" name="p_lock_time" /> <span class="fontsmall">({$LNG['ban_time_info']})</span></td>
 </tr>
 <tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Lock_user']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
 </table>
 </form>
</template>
<form method="post" action="administration.php?faction=ad_users&amp;mode=deleteuser&amp;user_id={$user_id}&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle"><span class="fonttitle">{$LNG['Delete_user']}</span></td></tr>
<tr><td class="cellstd"><span class="fontnorm"><input type="checkbox" name="p_delete_posts" value="1" />&nbsp;{$LNG['Delete_users_posts']}</span></td></tr>
<tr><td class="cellstd"><span class="fontnorm"><input type="checkbox" name="p_ban_nick_email" value="1" checked="checked" />&nbsp;{$LNG['Ban_nick_email']}</span></td></tr>
<tr><td class="cellbuttons" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Delete_user']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
