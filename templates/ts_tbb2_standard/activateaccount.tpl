<form method="post" action="index.php?faction=activateaccount&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Account_activation']}</span></td></tr>
<template:errorrow>
 <tr><td colspan="2" class="error"><span class="fonterror">{$error}</span></td></tr>
</template>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['User_name']}:</span></td>
 <td class="cellstd" width="85%"><input class="form_text" name="account_id" value="{$account_id}" size="25" />
</tr>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['Activation_code']}:</span></td>
 <td class="cellstd" width="85%"><input class="form_text" name="activation_code" value="{$activation_code}" size="35" maxlength="32" />
</tr>
<tr><td colspan="2" class="cellbuttons" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Activate_account']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>