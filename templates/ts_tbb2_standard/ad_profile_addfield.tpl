<form method="post" action="administration.php?faction=ad_profile&amp;mode=addfield&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Add_profile_field']}</span></td></tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Field_name']}:</span></td>
 <td class="cellalt" width="80%"><input class="form_text" type="text" size="40" name="p_field_name" value="{$p_field_name}" /></td>
</tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Field_type']}:</span></td>
 <td class="cellalt" width="80%"><select class="form_select" name="p_field_type"><option value="0"<if:"{$p_field_type} == 0"> selected="selected"</if>>{$LNG['Textfield']}</option><option value="1"<if:"{$p_field_type} == 1"> selected="selected"</if>>{$LNG['Textarea']}</option><option value="2"<if:"{$p_field_type} == 2"> selected="selected"</if>>{$LNG['Single_selection_list']}</option><option value="3"<if:"{$p_field_type} == 3"> selected="selected"</if>>{$LNG['Multiple_selection_list']}</option></select></td>
</tr>
<tr>
 <td class="cellstd" width="20%" valign="top"><span class="fontnorm">{$LNG['Field_data']}:</span><br /><span class="fontsmall">{$LNG['field_data_info']}</td>
 <td class="cellalt" width="80%"><textarea class="form_textarea" cols="40" rows="8" name="p_field_data">{$p_field_data}</textarea></td>
</tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Regex_verification']}:</span><br /><span class="fontsmall">{$LNG['regex_verification_info']}</span></td>
 <td class="cellalt" width="80%" valign="top"><input class="form_text" type="text" size="50" name="p_field_regex_verification" value="{$p_field_regex_verification}" /></td>
</tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Field_is_required']}:</span></td>
 <td class="cellalt" width="80%"><select class="form_select" name="p_field_is_required"><option value="0"<if:"{$p_field_is_required} == 0"> selected="selected"</if>>{$LNG['No']}</option><option value="1"<if:"{$p_field_is_required} == 1"> selected="selected"</if>>{$LNG['Yes']}</option></select></td>
</tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Show_at_registration']}:</span></td>
 <td class="cellalt" width="80%"><select class="form_select" name="p_field_show_registration"><option value="0"<if:"{$p_field_show_registration} == 0"> selected="selected"</if>>{$LNG['No']}</option><option value="1"<if:"{$p_field_show_registration} == 1"> selected="selected"</if>>{$LNG['Yes']}</option></select></td>
</tr>
<tr>
 <td class="cellstd" width="20%"><span class="fontnorm">{$LNG['Show_at_memberlist']}:</span></td>
 <td class="cellalt" width="80%"><select class="form_select" name="p_field_show_memberlist"><option value="0"<if:"{$p_field_show_memberlist} == 0"> selected="selected"</if>>{$LNG['No']}</option><option value="1"<if:"{$p_field_show_memberlist} == 1"> selected="selected"</if>>{$LNG['Yes']}</option></select></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Add_profile_field']}" /></td></tr>
</table>
</form>
