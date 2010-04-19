<form method="post" action="administration.php?faction=ad_users&amp;mode=searchusers&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Search_users']}</span></td></tr>
<tr><td class="cellinfobox" colspan="2"><span class="fontinfobox">{$LNG['search_users_info']}</span></td></tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['User_id']}:</span></td>
 <td class="cellalt" width="80%"><input class="form_text" name="p_user_id" value="{$p_user_id}" size="10" /></td>
</tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['User_name']}:</span></td>
 <td class="cellalt" width="80%"><input class="form_text" name="p_user_name" value="{$p_user_name}" size="20" /></td>
</tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Email_address']}:</span></td>
 <td class="cellalt" width="80%"><input class="form_text" name="p_user_email" value="{$p_user_email}" size="30" /></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Search_users']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="4"><span class="fonttitle">{$LNG['Search_results']}</span></td></tr>
<template:resultrow>
 <tr>
  <td class="cellstd"><span class="fontnorm">{$akt_result['user_id']}</span></td>
  <td class="cellalt"><span class="fontnorm">{$akt_result['user_nick']}</span></td>
  <td class="cellstd"><span class="fontnorm">{$akt_result['user_email']}</span></td>
  <td class="cellalt" align="right"><span class="fontsmall"><a href="administration.php?faction=ad_users&amp;mode=edituser&amp;user_id={$akt_result['user_id']}&amp;{$MYSID}">{$LNG['edit']}</a></span></td>
 </tr>
</template>
</table>
