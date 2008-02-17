<form method="post" action="index.php?faction=editprofile&amp;mode=extendedprofile&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="cellcat"><span class="fontcat">{$LNG['Extended_profile']}</span></td></tr>
<if:"{$error} != ''"><tr><td class="cellerror"><span class="fonterror">{$error}</span></td></tr></if>
<tr><td class="cellstd">
<template:fieldsgroup>
 <fieldset>
 <legend><span class="fontsmall"><b>{$akt_group_data['group_name']}</b></span></legend>
 <table border="0" cellpadding="2" cellspacing="0" width="100%">
 <template:fields>
  <template:fieldtext>
   <tr>
    <td width="25%"><span class="fontnorm">{$cur_field_data['field_name']}:</span></td>
    <td width="75%"><input class="form_text" type="text" size="50" name="p_fields_data[{$cur_field_data['field_id']}]" value="{$akt_field_value}" /></td>
   </tr>
  </template>
  <template:fieldtextarea>
   <tr>
    <td width="25%"><span class="fontnorm">{$cur_field_data['field_name']}:</span></td>
    <td width="75%"><input class="form_text" type="text" size="50" name="p_fields_data[{$cur_field_data['field_id']}]" value="{$akt_field_value}" /></td>
   </tr>
  </template>
  <template:fieldsingleselection>
   <tr>
    <td width="25%"><span class="fontnorm">{$cur_field_data['field_name']}:</span></td>
    <td width="75%"><select class="form_select" name="p_fields_data[{$cur_field_data['field_id']}]">
    <template:optionrow>
    <option value="{$cur_option_key}"<if:"{$cur_option_key} == {$selected_id}"> selected="selected"</if>>{$cur_option_value}</option>
    </template>
    </select></td>
   </tr>  
  </template>
  <template:fieldmultiselection>
   <tr>
    <td width="25%" valign="top"><span class="fontnorm">{$cur_field_data['field_name']}:</span></td>
    <td width="75%"><select class="form_select" name="p_fields_data[{$cur_field_data['field_id']}][]" size="5" multiple="multiple">
    <template:optionrow>
    <option value="{$cur_option_key}"<if:"in_array({$cur_option_key},{$selected_ids}) == TRUE"> selected="selected"</if>>{$cur_option_value}</option>
    </template>
    </select></td>
   </tr>  
  </template>
 </template>
 </table>
 </fieldset>
</template>
</td></tr>
<tr><td class="cellbuttons" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Save_changes']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
