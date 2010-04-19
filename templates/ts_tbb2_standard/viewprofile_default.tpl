<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['View_profile']}</span></th></tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['User_name']}:</span></td>
 <td class="td2" width="85%"><span class="norm">{$profile_data['user_nick']}</span></td>
</tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['Email_address']}:</span></td>
 <td class="td2" width="85%"><span class="norm"><if:"{$profile_data['user_hide_email']} != 1"><a href="mailto:{$profile_data['user_email']}">{$profile_data['user_email']}</a><else />{$lng['Email_address_hidden']}</if><if:"{$profile_data['user_receive_emails']} == 1 && {$USER_LOGGED_IN} == 1 && {$CONFIG['enable_email_formular']} == 1"> <a href="index.php?faction=viewprofile&amp;profile_id={$profile_id}&amp;mode=sendmail&amp;{$MYSID}">[{$lng['Send_email']}]</a></if></span></td>
</tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['Register_date']}:</span></td>
 <td class="td2" width="85%"><span class="norm">{$profile_register_date}</span></td>
</tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['Posts']}:</span></td>
 <td class="td2" width="85%"><span class="norm">{$profile_data['user_posts']}</span></td>
</tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['User_rank']}:</span></td>
 <td class="td2" width="85%"><span class="norm">{$profile_rank_text} {$profile_rank_pic}</span></td>
</tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['Interests']}:</span></td>
 <td class="td2" width="85%"><span class="norm"><if:"{$profile_data['user_interests']} != ''">{$profile_data['user_interests']}<else />{$lng['Not_specified']}</if></span></td>
</tr>
</table>
