<form method="post" action="administration.php?faction=ad_forums&amp;mode=addcat&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Add_category']}</span></td></tr>
<template:errorrow>
 <tr><td class="cellerror" colspan="2"><span class="fonterror">{$error}</span></td></tr>
</template>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['Name']}:</span></td>
 <td class="cellstd" width="85%"><input class="form_text" type="text" size="35" name="p_cat_name" value="{$p_cat_name}" /></td>
</tr>
<tr>
 <td class="cellalt" width="15%"><span class="fontnorm">{$LNG['Description']}:</span></td>
 <td class="cellalt" width="85%"><input class="form_text" type="text" size="45" name="p_cat_description" value="{$p_cat_description}" /></td>
</tr>
<tr>
 <td class="cellstd" width="15%"><span class="fontnorm">{$LNG['Standard_status']}:</span></td>
 <td class="cellstd" width="85%"><select class="form_select" name="p_cat_standard_status"><option value="1"{$checked['open']}>{$LNG['open']}</option><option value="0"{$checked['closed']}>{$LNG['closed']}</option></select></td>
</tr>
<tr>
 <td class="cellalt" width="15%"><span class="fontnorm">{$LNG['Parent_category']}:</span></td>
 <td class="cellalt" width="85%"><select class="form_select" name="p_parent_id">
 <template:optionrow>
  <option value="{$akt_cat['cat_id']}"{$akt_selected}>{$akt_prefix} {$akt_cat['cat_name']}</option>
 </template>
 </select></td>
</tr>
<tr><td colspan="2" class="cellbuttons" align="center"><input type="submit" class="form_bbutton" value="{$LNG['Add_category']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>