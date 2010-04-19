<form method="post" action="administration.php?faction=ad_users&amp;mode=searchusers&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Search_users']}</span></th></tr>
<tr><td class="td2" colspan="2"><span class="norm">{$lng['search_users_info']}</span></td></tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['User_id']}:</span></td>
 <td class="td2" width="80%"><input class="form_text" name="p_user_id" value="{$p_user_id}" size="10" /></td>
</tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['User_name']}:</span></td>
 <td class="td2" width="80%"><input class="form_text" name="p_user_name" value="{$p_user_name}" size="20" /></td>
</tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['Email_address']}:</span></td>
 <td class="td2" width="80%"><input class="form_text" name="p_user_email" value="{$p_user_email}" size="30" /></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Search_users']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="4"><span class="thnorm">{$lng['Search_results']}</span></th></tr>
<template:resultrow>
 <tr>
  <td class="td1"><span class="norm">{$akt_result['user_id']}</span></td>
  <td class="td2"><span class="norm">{$akt_result['user_nick']}</span></td>
  <td class="td1"><span class="norm">{$akt_result['user_email']}</span></td>
  <td class="td2" align="right"><span class="small"><a href="administration.php?faction=ad_users&amp;mode=edituser&amp;user_id={$akt_result['user_id']}&amp;{$MYSID}">{$lng['edit']}</a></span></td>
 </tr>
</template>
</table>
