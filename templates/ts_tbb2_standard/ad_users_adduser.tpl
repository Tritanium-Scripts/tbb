<form method="post" action="administration.php?faction=ad_users&amp;mode=adduser&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Add_user']}</span></td></tr>
<if:"{$error} != ''">
 <tr><td class="cellerror" colspan="2"><span class="fonterror">{$error}</span></td></tr>
</if>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['User_name']}:</span><br /><span class="fontsmall">{$LNG['nick_conventions']}</span></td>
 <td class="cellalt" width="75%" valign="top"><input class="form_text" type="text" name="p_user_nick" value="{$p_user_nick}" size="20" /></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Email_address']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><input class="form_text" type="text" name="p_user_email" value="{$p_user_email}" size="30" /></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Password']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><input class="form_text" type="password" name="p_user_pw1" size="20" /></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Password_confirmation']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><input class="form_text" type="password" name="p_user_pw2" size="20" /></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Options']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm"><input type="checkbox" name="p_notify_user" value="1"<if:"{$p_notify_user} == 1"> checked="checked"</if> id="check_notify_user" /><label for="check_notify_user">&nbsp;{$LNG['Notify_user_registration']}</label></span></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Add_user']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
