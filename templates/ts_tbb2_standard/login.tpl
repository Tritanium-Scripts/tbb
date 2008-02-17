<form method="post" action="index.php?faction=login&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Login']}</span></td></tr>
<template:errorrow>
 <tr><td class="cellerror" colspan="2"><span class="fonterror">{$error}</span></td></tr>
</template>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Logindata']}</span></td></tr>
<tr>
 <td class="cellstd" width="25%"><span class="fontnorm">{$LNG['User_name']}:</span></td>
 <td class="cellalt" width="75%"><input class="form_text" type="text" name="p_nick" value="{$p_nick}" tabindex="1" size="35" /> <span class="fontsmall">(<a href="index.php?faction=register&amp;{$MYSID}" tabindex="3">{$LNG['Register']}</a>)</span></td>
</tr>
<tr>
 <td class="cellstd" width="25%"><span class="fontnorm">{$LNG['Password']}:</span></td>
 <td class="cellalt" width="75%"><input class="form_text" type="password" name="p_pw" tabindex="2" size="30" /> <span class="fontsmall">(<a href="index.php?faction=requestpassword&amp;{$MYSID}" tabindex="4">{$LNG['Request_new_password']}</a>)</span></td>
</tr>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Other_options']}</span></td></tr>
<tr><td class="cellstd" colspan="2"><span class="fontnorm"><input type="checkbox" name="p_hide_from_wio" value="1"{$checked['hide_from_wio']} /> {$LNG['Hide_from_who_is_online']}</span></td></tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input type="submit" name="p_submit" value="{$LNG['Login']}" class="form_bbutton" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" name="p_reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
