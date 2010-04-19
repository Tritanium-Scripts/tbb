<form method="post" action="index.php?faction=requestpassword&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Request_new_password']}</span></th></tr>
<template:errorrow>
 <tr><td class="error" colspan="2"><span class="error">{$error}</span></td></tr>
</template>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['User_name']}:</span></td>
 <td class="td2" width="85%"><input class="form_text" type="text" name="p_user_name" value="{$p_user_name}" size="20" /></td>
</tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['Email_address']}:</span></td>
 <td class="td2" width="85%"><input class="form_text" type="text" name="p_email_address" value="{$p_email_address}" size="30" /></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Request_new_password']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>
