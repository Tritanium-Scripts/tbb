<form method="post" action="index.php?faction=editprofile&amp;mode=generalprofile&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="cellcat"><span class="fontcat">{$LNG['General_profile']}</span></td></tr>
<if:"{$error} != ''">
 <tr><td class="cellerror"><span class="fonterror">{$error}</span></td></tr>
</if>
<tr><td class="cellstd">
 <fieldset>
 <legend><span class="fontsmall"><b>{$LNG['General_information']}</b></span></legend>
 <table border="0" cellpadding="2" cellspacing="0" border="0" width="100%">
 <tr>
  <td width="20%"><span class="fontnorm">{$LNG['Email_address']}:</span></td>
  <td width="80%"><input class="form_text" type="text" size="40" name="p_user_email" value="{$p_user_email}" /></td>
 </tr>
 <tr>
  <td width="20%" valign="top"><span class="fontnorm">{$LNG['Signature']}:</span></td>
  <td width="80%"><textarea class="form_textarea" cols="60" rows="8" name="p_user_signature">{$p_user_signature}</textarea></td>
 </tr>
 </table>
 </fieldset>
</td></tr>
<tr><td class="cellstd"><span class="fontnorm">&nbsp;</span></td></tr>
<tr><td class="cellstd">
 <fieldset>
 <legend><span class="fontsmall"><b>{$LNG['Change_password']}</b></span></legend>
 <table border="0" cellpadding="2" cellspacing="0" border="0" width="100%">
 <tr><td class="cellinfobox" colspan="2"><span class="fontinfobox">{$LNG['change_password_info']}</span></td></tr>
 <tr>
  <td width="20%"><span class="fontnorm">{$LNG['Current_password']}:</span></td>
  <td width="80%"><input class="form_text" type="password" size="30" name="p_user_old_pw" /></td>
 </tr>
 <tr>
  <td width="20%"><span class="fontnorm">{$LNG['New_password']}:</span></td>
  <td width="80%"><input class="form_text" type="password" size="30" name="p_user_new_pw" /></td>
 </tr>
 <tr>
  <td width="20%"><span class="fontnorm">{$LNG['Confirm_new_password']}:</span></td>
  <td width="80%"><input class="form_text" type="password" size="30" name="p_user_new_pw_cfm" /></td>
 </tr>
 </table>
 </fieldset>
</td></tr>
<tr><td class="cellbuttons" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Save_changes']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
