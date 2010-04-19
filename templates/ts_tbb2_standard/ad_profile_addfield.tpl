<form method="post" action="administration.php?faction=ad_profile&amp;mode=addfield&amp;doit=1&amp;{$MYSID}">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Add_profile_field']}</span></th></tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['Field_name']}:</span></td>
 <td class="td2" width="80%"><input class="form_text" type="text" size="40" name="p_field_name" value="{$p_field_name}" /></td>
</tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['Field_type']}:</span></td>
 <td class="td2" width="80%"><select class="form_select" name="p_field_type"><option value="0"<if:"{$p_field_type} == 0"> selected="selected"</if>>{$lng['Textfield']}</option><option value="1"<if:"{$p_field_type} == 1"> selected="selected"</if>>{$lng['Textarea']}</option><option value="2"<if:"{$p_field_type} == 2"> selected="selected"</if>>{$lng['Single_selection_list']}</option><option value="3"<if:"{$p_field_type} == 3"> selected="selected"</if>>{$lng['Multiple_selection_list']}</option></select></td>
</tr>
<tr>
 <td class="td1" width="20%" valign="top"><span class="norm">{$lng['Field_data']}:</span><br /><span class="small">{$lng['field_data_info']}</td>
 <td class="td2" width="80%"><textarea class="form_textarea" cols="40" rows="8" name="p_field_data">{$p_field_data}</textarea></td>
</tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['Regex_verification']}:</span><br /><span class="small">{$lng['regex_verification_info']}</span></td>
 <td class="td2" width="80%" valign="top"><input class="form_text" type="text" size="50" name="p_field_regex_verification" value="{$p_field_regex_verification}" /></td>
</tr>
<tr>
 <td class="td1" width="20%"><span class="norm">{$lng['Field_is_required']}:</span></td>
 <td class="td2" width="80%"><select class="form_select" name="p_field_is_required"><option value="0"<if:"{$p_field_is_required} == 0"> selected="selected"</if>>{$lng['No']}</option><option value="1"<if:"{$p_field_is_required} == 1"> selected="selected"</if>>{$lng['Yes']}</option></select></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Add_profile_field']}" /></td></tr>
</table>
</form>
