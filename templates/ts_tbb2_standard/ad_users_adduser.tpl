<form method="post" action="administration.php?faction=ad_users&amp;mode=adduser&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Add_user']}</span></th></tr>
<template:errorrow>
 <tr><td class="error" colspan="2"><span class="error">{$error}</span></td></tr>
</template>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['User_name']}:</span><br /><span class="small">{$lng['nick_conventions']}</span></td>
 <td class="td2" width="75%" valign="top"><input class="form_text" type="text" name="p_user_name" value="{$p_user_name}" size="20" /></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Email_address']}:</span></td>
 <td class="td2" width="75%" valign="top"><input class="form_text" type="text" name="p_user_email" value="{$p_user_email}" size="30" /></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Password']}:</span></td>
 <td class="td2" width="75%" valign="top"><input class="form_text" type="password" name="p_user_pw1" size="20" /></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Confirm_password']}:</span></td>
 <td class="td2" width="75%" valign="top"><input class="form_text" type="password" name="p_user_pw2" size="20" /></td>
</tr>
<tr>
 <td class="td1" width="25%" valign="top"><span class="norm">{$lng['Options']}:</span></td>
 <td class="td2" width="75%" valign="top"><span class="norm"><input type="checkbox" name="p_notify_user" value="1"{$checked['notify']} />&nbsp;{$lng['Notify_user_registration']}</span></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Add_user']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>
