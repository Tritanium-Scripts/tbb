<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Manage_groups']}</span></td></tr>
<template:grouprow>
 <tr>
  <td class="cellstd"><span class="fontnorm">{$akt_group['group_name']}</span></td>
  <td class="cellalt" align="right"><span class="fontsmall"><a href="administration.php?faction=ad_groups&amp;mode=deletegroup&amp;group_id={$akt_group['group_id']}&amp;{$MYSID}">{$LNG['delete']}</a> | <a href="administration.php?faction=ad_groups&amp;mode=managemembers&amp;group_id={$akt_group['group_id']}&amp;{$MYSID}">{$LNG['Manage_members']}</a> | <a href="administration.php?faction=ad_groups&amp;mode=editgroup&amp;group_id={$akt_group['group_id']}&amp;{$MYSID}">{$LNG['edit']}</a></span></td>
</template>
</table>
<br />
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle"><span class="fonttitle">{$LNG['Other_options']}</span></td></tr>
<tr><td class="cellstd"><span class="fontnorm"><a href="administration.php?faction=ad_groups&amp;mode=addgroup&amp;{$MYSID}">{$LNG['Add_group']}</span></td></tr>
</table>