<form method="post" action="administration.php?faction=ad_forums&amp;mode=addcat&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng["Add_category"]}</span></th></tr>
<!-- TPLBLOCK errorrow -->
 <tr><td class="error" colspan="2"><span class="error">{errorrow.$error}</span></td></tr>
<!-- /TPLBLOCK errorrow -->
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng["Name"]}:</span></td>
 <td class="td1" width="85%"><input class="form_text" type="text" size="35" name="p_cat_name" value="{$p_cat_name}" /></td>
</tr>
<tr>
 <td class="td2" width="15%"><span class="norm">{$lng["Description"]}:</span></td>
 <td class="td2" width="85%"><input class="form_text" type="text" size="45" name="p_cat_description" value="{$p_cat_description}" /></td>
</tr>
<tr>
 <td class="td1" width="15%"><span class="norm">{$lng["Parent_category"]}:</span></td>
 <td class="td1" width="85%"><select class="form_select" name="p_parent_id">
 <!-- TPLBLOCK optionrow -->
  <option value="{optionrow.$akt_cat["cat_id"]}"{optionrow.$akt_selected}>{optionrow.$akt_cat["cat_name"]}</option>
 <!-- /TPLBLOCK optionrow -->
 </select></td>
</tr>
<tr><td colspan="2" class="buttonrow" align="center"><input type="submit" class="form_bbutton" value="{$lng["Add_category"]}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng["Reset"]}" /></td></tr>
</table>
</form>