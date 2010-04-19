<templatefile:"editprofile_header.tpl" />
<form method="post" action="index.php?faction=editprofile&amp;mode=settings&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="cellcat"><span class="fontcat">{$LNG['Settings']}</span></td></tr>
<tr><td class="cellstd">
 <fieldset>
  <legend><span class="fontsmall"><b>{$LNG['General_settings']}</b></span></legend>
  <table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
   <td width="35%" valign="top"><span class="fontnorm">{$LNG['Show_email_address']}:</span><br /><span class="fontsmall">{$LNG['show_email_address_info']}</span></td>
   <td width="65%" valign="top"><select name="p_user_hide_email" class="form_select"><option value="1"<if:"{$p_user_hide_email} == 1"> selected="selected"</if>>{$LNG['No']}</option><option value="0"<if:"{$p_user_hide_email} == 0"> selected="selected"</if>>{$LNG['Yes']}</option></select></td>
  </tr>
  <tr>
   <td width="35%" valign="top"><span class="fontnorm">{$LNG['Receive_board_emails']}:</span><br /><span class="fontsmall">{$LNG['receive_board_emails_info']}</span></td>
   <td width="65%" valign="top"><select name="p_user_receive_emails" class="form_select"><option value="1"<if:"{$p_user_receive_emails} == 1"> selected="selected"</if>>{$LNG['Yes']}</option><option value="0"<if:"{$p_user_receive_emails} == 0"> selected="selected"</if>>{$LNG['No']}</option></select></td>
  </tr>
  <tr>
   <td width="35%" valign="top"><span class="fontnorm">{$LNG['Timezone']}:</span></td>
   <td width="65%" valign="top"><select class="form_select" name="p_user_tz">
   <template:tzrow>
    <option value="{$akt_tz_id}"<if:"{$akt_tz_id} == {$p_user_tz}"> selected="selected"</if>>{$akt_tz_name}</option>
   </template>
   </select></td>
  </tr>
  </table>
 </fieldset>
</td></tr>
</table>
</form>
<templatefile:"editprofile_tail.tpl" />
