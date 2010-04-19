<form method="post" action="index.php?faction=requestpassword&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Request_new_password']}</span></td></tr>
<template:errorrow>
 <tr><td class="cellerror" colspan="2"><span class="fonterror">{$error}</span></td></tr>
</template>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['User_name']}:</span></td>
 <td class="cellalt" width="85%"><input class="form_text" type="text" name="p_user_name" value="{$p_user_name}" size="20" /></td>
</tr>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['Email_address']}:</span></td>
 <td class="cellalt" width="85%"><input class="form_text" type="text" name="p_email_address" value="{$p_email_address}" size="30" /></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Request_new_password']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
