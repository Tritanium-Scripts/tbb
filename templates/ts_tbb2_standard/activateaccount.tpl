<form method="post" action="index.php?faction=activateaccount&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th colspan="2" class="thnorm"><span class="thnorm">{$lng['Account_activation']}</span></th></tr>
<template:errorrow>
 <tr><td colspan="2" class="error"><span class="error">{errorrow.$error}</span></td></tr>
</template:errorrow>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['User_name']}:</span></td>
 <td class="td1" width="85%"><input class="form_text" name="account_id" value="{$account_id}" size="25" />
</tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['Activation_code']}:</span></td>
 <td class="td1" width="85%"><input class="form_text" name="activation_code" value="{$activation_code}" size="35" maxlength="32" />
</tr>
<tr><td colspan="2" class="buttonrow" align="center"><input class="form_bbutton" type="submit" value="{$lng['Activate_account']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>