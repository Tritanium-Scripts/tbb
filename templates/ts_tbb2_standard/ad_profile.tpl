<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="3"><span class="fonttitle">{$LNG['Manage_profile_fields']}</span></td></tr>
<template:fieldrow>
 <tr>
  <td class="cellstd"><span class="fontnorm"><a href="administration.php?faction=ad_profile&amp;mode=editfield&amp;field_id={$akt_field['field_id']}&amp;{$MYSID}">{$akt_field['field_name']}</a></span></td>
  <td class="cellalt" align="center"><span class="fontnorm">{$akt_field_type}</span></td>
  <td class="cellstd" align="right"><span class="fontsmall"><a href="administration.php?faction=ad_profile&amp;mode=deletefield&amp;field_id={$akt_field['field_id']}&amp;{$MYSID}">{$LNG['delete']}</a> | <a href="administration.php?faction=ad_profile&amp;mode=editfield&amp;field_id={$akt_field['field_id']}&amp;{$MYSID}">{$LNG['edit']}</a></span></td>
 </tr>
</template>
</table>
<br />
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Other_options']}</span></td></tr>
<tr><td class="cellstd"><span class="fontnorm"><a href="administration.php?faction=ad_profile&amp;mode=addfield&amp;{$MYSID}">{$LNG['Add_profile_field']}</a></span></td></tr>
</table>
