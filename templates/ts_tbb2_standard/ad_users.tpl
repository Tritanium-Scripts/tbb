<form method="post" action="administration.php?faction=ad_users&amp;mode=searchusers&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Manage_users']}</span></td></tr>
<tr><td class="cellinfobox" colspan="2"><span class="fontinfobox">{$LNG['search_users_info']}</span></td></tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['User_id']}:</span></td>
 <td class="cellalt" width="80%"><input class="form_text" name="p_user_id" size="10" /></td>
</tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['User_name']}:</span></td>
 <td class="cellalt" width="80%"><input class="form_text" name="p_user_name" size="20" /></td>
</tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Email_address']}:</span></td>
 <td class="cellalt" width="80%"><input class="form_text" name="p_user_email" size="30" /></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Search_users']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
<form method="post" action="administration.php?faction=ad_users&amp;mode=unlockusers&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle"><span class="fonttitle">{$LNG['Unlock_users']}</span></td></tr>
<template:nolockedusers>
 <tr><td class="cellstd" align="center"><span class="fontnorm">-- {$LNG['No_locked_users']} --</span></td></tr>
</template>
<template:lockeduserrow>
 <tr>
  <td></td>
  <td></td>
  <td></td>
 </tr>
</template>
<tr><td class="cellbuttons" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Unlock_selected_users']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle"><span class="fonttitle">{$LNG['Other_options']}</span></td></tr>
<tr><td class="cellstd"><span class="fontnorm"><a href="administration.php?faction=ad_users&amp;mode=adduser&amp;{$MYSID}">{$LNG['Add_user']}</span></td></tr>
</table>
