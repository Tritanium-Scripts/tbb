<table class="navbar" border="0" cellpadding="2" cellspacing="0" width="100%">
<tr><td class="navbar"><span class="navbar">{$page_listing}</td></tr>
</table>
<br />
<form method="post" action="index.php?faction=userslist&amp;z={$z}&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <th class="thsmall"><span class="thsmall">{$lng['User_id']}</span></th>
 <th class="thsmall"><span class="thsmall">{$lng['User_name']}</span></th>
 <th class="thsmall"><span class="thsmall">{$lng['User_rank']}</span></th>
 <th class="thsmall"><span class="thsmall">{$lng['Posts']}</span></th>
 <th class="thsmall"><span class="thsmall">&nbsp;</span></th>
 <th class="thsmall"><span class="thsmall">&nbsp;</span></th>
</tr>
<template:userrow>
 <tr>
  <td class="td1"><span class="norm">{$akt_user['user_id']}</span></td>
  <td class="td2"><span class="norm">{$akt_user['user_nick']}</span></td>
  <td class="td1"><span class="norm">{$akt_user_rank}</span></td>
  <td class="td2"><span class="norm">{$akt_user['user_posts']}</span></td>
  <td class="td1"><span class="norm"></span></td>
  <td class="td2"><span class="norm"></span></td>
 </tr>
</template>
<tr><td colspan="6" class="buttonrow"><span class="small"><b>{$lng['Display_options']}:</b> {$lng['Sort_by']} <select class="form_select" name="sort_type"><option value="id"{$checked['sort_type_id']}>{$lng['User_id']}</option><option value="nick"{$checked['sort_type_nick']}>{$lng['User_name']}</option><option value="rank"{$checked['sort_type_rank']}>{$lng['User_rank']}</option><option value="posts"{$checked['sort_type_posts']}>{$lng['Posts']}</option></select> <select class="form_select" name="order_type"><option value="DESC"{$checked['order_type_desc']}>{$lng['Descending']}</option><option value="ASC"{$checked['order_type_asc']}>{$lng['Ascending']}</option></select>; {$lng['Users_per_page']} <select class="form_select" name="users_per_page"><option value="10">10</option><option value="20">20</option><option value="50">50</option><option value="100">100</option></select>&nbsp;&nbsp;&nbsp;<input class="form_bbutton" type="submit" value="{$lng['Go']}" /></td></tr>
</table>
</form>
