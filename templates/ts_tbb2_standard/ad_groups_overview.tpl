<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Manage_groups']}</span></th></tr>
<template:grouprow>
 <tr>
  <td class="td1"><span class="norm">{$akt_group['group_name']}</span></td>
  <td class="td2" align="right"><span class="small"><a href="administration.php?faction=ad_groups&amp;mode=deletegroup&amp;group_id={$akt_group['group_id']}&amp;{$MYSID}">{$lng['delete']}</a> | <a href="administration.php?faction=ad_groups&amp;mode=managemembers&amp;group_id={$akt_group['group_id']}&amp;{$MYSID}">{$lng['Manage_members']}</a> | <a href="administration.php?faction=ad_groups&amp;mode=editgroup&amp;group_id={$akt_group['group_id']}&amp;{$MYSID}">{$lng['edit']}</a></span></td>
</template>
</table>
<br />
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm"><span class="thnorm">{$lng['Other_options']}</span></th></tr>
<tr><td class="td1"><span class="norm"><a href="administration.php?faction=ad_groups&amp;mode=addgroup&amp;{$MYSID}">{$lng['Add_group']}</span></td></tr>
</table>