<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="3"><span class="thnorm">{$lng['Manage_profile_fields']}</span></th></tr>
<template:fieldrow>
 <tr>
  <td class="td1"><span class="norm"><a href="administration.php?faction=ad_profile&amp;mode=editfield&amp;field_id={$akt_field['field_id']}&amp;{$MYSID}">{$akt_field['field_name']}</a></span></td>
  <td class="td2" align="center"><span class="norm">{$akt_field_type}</span></td>
  <td class="td1" align="right"><span class="small"><a href="administration.php?faction=ad_profile&amp;mode=deletefield&amp;field_id={$akt_field['field_id']}&amp;{$MYSID}">{$lng['delete']}</a> | <a href="administration.php?faction=ad_profile&amp;mode=editfield&amp;field_id={$akt_field['field_id']}&amp;{$MYSID}">{$lng['edit']}</a></span></td>
 </tr>
</template>
</table>
<br />
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Other_options']}</span></th></tr>
<tr><td class="td1"><span class="norm"><a href="administration.php?faction=ad_profile&amp;mode=addfield&amp;{$MYSID}">{$lng['Add_profile_field']}</a></span></td></tr>
</table>
