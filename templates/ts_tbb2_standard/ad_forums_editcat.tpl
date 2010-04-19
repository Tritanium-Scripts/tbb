<form method="post" action="administration.php?faction=ad_forums&amp;mode=editcat&amp;cat_id={CAT_ID}&amp;doit=1&amp;{MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{LNG_EDIT_CATEGORY}</span></th></tr>
<!-- TPLBLOCK errorrow -->
 <tr><td class="error" colspan="2"><span class="error">{errorrow.ERROR}</span></td></tr>
<!-- /TPLBLOCK errorrow -->
<tr>
 <td class="td1" width="15%"><span class="norm">{LNG_NAME}:</span></td>
 <td class="td1" width="85%"><input class="form_text" type="text" size="35" name="p_cat_name" value="{P_CAT_NAME}" /></td>
</tr>
<tr>
 <td class="td2" width="15%"><span class="norm">{LNG_DESCRIPTION}:</span></td>
 <td class="td2" width="85%"><input class="form_text" type="text" size="45" name="p_cat_description" value="{P_CAT_DESCRIPTION}" /></td>
</tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{LNG_PARENT_CATEGORY}:</span></td>
 <td class="td1" width="85%"><select class="form_select" name="p_parent_id">
 <!-- TPLBLOCK optionrow -->
  <option value="{optionrow.VALUE}"{optionrow.SELECTED}>{optionrow.TEXT}</option>
 <!-- /TPLBLOCK optionrow -->
 </select></td>
</tr>
<tr><td colspan="2" class="buttonrow" align="center"><input type="submit" class="form_bbutton" value="{LNG_EDIT_CATEGORY}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{LNG_RESET}" /></td></tr>
</table>
</form>