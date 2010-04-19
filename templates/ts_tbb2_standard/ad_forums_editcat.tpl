<form method="post" action="administration.php?faction=ad_forums&amp;mode=editcat&amp;cat_id={$cat_id}&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Edit_category']}</span></th></tr>
<template:errorrow>
 <tr><td class="error" colspan="2"><span class="error">{$error}</span></td></tr>
</template>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['Name']}:</span></td>
 <td class="td1" width="85%"><input class="form_text" type="text" size="35" name="p_cat_name" value="{$p_cat_name}" /></td>
</tr>
<tr>
 <td class="td2" width="15%"><span class="norm">{$lng['Description']}:</span></td>
 <td class="td2" width="85%"><input class="form_text" type="text" size="45" name="p_cat_description" value="{$p_cat_description}" /></td>
</tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng['Standard_status']}:</span></td>
 <td class="td1" width="85%"><select class="form_select" name="p_cat_standard_status"><option value="1"{$checked['open']}>{$lng['open']}</option><option value="0"{$checked['closed']}>{$lng['closed']}</option></select></td>
</tr>
<tr>
 <td class="td2" width="15%"><span class="norm">{$lng['Parent_category']}:</span></td>
 <td class="td2" width="85%"><select class="form_select" name="p_parent_id">´
 <template:optionrow>
  <option value="{$akt_cat['cat_id']}"{$selected}>{$akt_prefix} {$akt_cat['cat_name']}</option>
 </template>
 </select></td>
</tr>
<tr><td colspan="2" class="buttonrow" align="center"><input type="submit" class="form_bbutton" value="{$lng['Edit_category']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>