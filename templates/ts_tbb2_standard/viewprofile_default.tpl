<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['View_profile']}</span></td></tr>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['User_name']}:</span></td>
 <td class="cellalt" width="85%"><span class="fontnorm">{$profile_data['user_nick']}</span></td>
</tr>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['Email_address']}:</span></td>
 <td class="cellalt" width="85%"><span class="fontnorm"><if:"{$profile_data['user_hide_email']} != 1"><a href="mailto:{$profile_data['user_email']}">{$profile_data['user_email']}</a><else />{$LNG['Email_address_hidden']}</if><if:"{$profile_data['user_receive_emails']} == 1 && {$USER_LOGGED_IN} == 1 && {$CONFIG['enable_email_formular']} == 1"> <a href="index.php?faction=viewprofile&amp;profile_id={$profile_id}&amp;mode=sendmail&amp;{$MYSID}">[{$LNG['Send_email']}]</a></if></span></td>
</tr>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['Register_date']}:</span></td>
 <td class="cellalt" width="85%"><span class="fontnorm">{$profile_register_date}</span></td>
</tr>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['Posts']}:</span></td>
 <td class="cellalt" width="85%"><span class="fontnorm">{$profile_data['user_posts']}</span></td>
</tr>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['User_rank']}:</span></td>
 <td class="cellalt" width="85%"><span class="fontnorm">{$profile_rank_text} {$profile_rank_pic}</span></td>
</tr>
</table>
