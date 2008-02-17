<table class="navbar" border="0" cellpadding="2" cellspacing="0" width="100%">
<tr><td class="navbar"><span class="navbar">{$page_listing}</td></tr>
</table>
<br />
<form method="post" action="index.php?faction=memberlist&amp;z={$z}&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="celltitle" align="center"><span class="fonttitlesmall">{$LNG['User_id']}</span></td>
 <td class="celltitle" align="center"><span class="fonttitlesmall">{$LNG['User_name']}</span></td>
 <td class="celltitle" align="center"><span class="fonttitlesmall">{$LNG['User_rank']}</span></td>
 <td class="celltitle" align="center"><span class="fonttitlesmall">{$LNG['Posts']}</span></td>
 <template:fieldrow>
  <td class="celltitle" align="center"><span class="fonttitlesmall">{$cur_field['field_name']}</span></td>
 </template>
</tr>
<template:userrow>
 <tr>
  <td class="cellstd"><span class="fontnorm">{$cur_user['user_id']}</span></td>
  <td class="cellalt"><span class="fontnorm">{$cur_user['user_nick']}</span></td>
  <td class="cellstd"><span class="fontnorm">{$cur_user_rank}</span></td>
  <td class="cellalt"><span class="fontnorm">{$cur_user['user_posts']}</span></td>
  <template:fieldrow>
   <td class="{$akt_cell_class}"><span class="fontnorm">{$cur_field_value}</span></td>
  </template>  
 </tr>
</template>
<tr><td colspan="100" class="cellbuttons"><span class="fontsmall"><b>{$LNG['Display_options']}:</b> {$LNG['Sort_by']}
 <select class="form_select" name="sort_type">
  <option value="id"<if:"{$sort_type} == 'id'"> selected="selected"</if>>{$LNG['User_id']}</option>
  <option value="nick"<if:"{$sort_type} == 'nick'"> selected="selected"</if>>{$LNG['User_name']}</option>
  <option value="rank"<if:"{$sort_type} == 'rank'"> selected="selected"</if>>{$LNG['User_rank']}</option>
  <option value="posts"<if:"{$sort_type} == 'posts'"> selected="selected"</if>>{$LNG['Posts']}</option>
 </select>
 <select class="form_select" name="order_type">
  <option value="DESC"<if:"{$order_type} == 'DESC'"> selected="selected"</if>>{$LNG['Descending']}</option>
  <option value="ASC"<if:"{$order_type} == 'ASC'"> selected="selected"</if>>{$LNG['Ascending']}</option>
 </select>; {$LNG['Users_per_page']}
 <select class="form_select" name="users_per_page">
  <option value="10">10</option>
  <option value="20">20</option>
  <option value="50">50</option>
  <option value="100">100</option>
 </select>&nbsp;&nbsp;&nbsp;<input class="form_bbutton" type="submit" value="{$LNG['Go']}" />
</td></tr>
</table>
</form>
