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
<template:notestable>
 <br />
 <table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Notes_about_user']}</span></td></tr>
 <template:noterow>
 <tr>
  <td class="cellalt" width="15%" valign="top" rowspan="2"><span class="fontnorm"><b><a href="index.php?faction=viewprofile&amp;profile_id={$cur_note['user_id']}&amp;{$MYSID}">{$cur_note['user_nick']}</a></b></span></td>
  <td class="cellalt" width="85%">
   <table border="0" cellpadding="0" cellspacing="0" width="100%"> 
   <tr>
    <td><span class="fontsmall">{$cur_note_date}</span></td>
    <td align="right"><span class="fontsmall"><if:"{$USER_DATA['user_is_admin']} == 1 || {$USER_ID} == {$cur_note['user_id']}"><a href="index.php?faction=viewprofile&amp;profile_id={$profile_id}&amp;mode=deletenote&amp;note_id={$cur_note['note_id']}&amp;{$MYSID}">{$LNG['Delete_note']}</a> | <a href="index.php?faction=viewprofile&amp;profile_id={$profile_id}&amp;mode=editnote&amp;note_id={$cur_note['note_id']}&amp;{$MYSID}">{$LNG['Edit_note']}</a></if></span></td>
   </table>
  </td>
 </tr>
 <tr><td class="cellstd"><span class="fontnorm">{$cur_note['note_text']}</span></td></tr>
 </template>
 <if:"{$profile_notes_counter} == 0"><tr><td class="cellstd" align="center"><span class="fontnorm">-- Keine Anmerkungen vorhanden --</span></td></tr></if>
 </table>
 <br />
 <table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
 <tr><td class="celltitle"><span class="fonttitle">{$LNG['Other_options']}</span></td></tr>
 <tr><td class="cellstd"><span class="fontnorm"><a href="index.php?faction=viewprofile&amp;profile_id={$profile_id}&amp;mode=addnote&amp;{$MYSID}">Anmerkung hinzuf&uuml;gen</a></span></td></tr>
 </table>
</template>