<form method="post" action="index.php?faction=register&amp;mode=register&amp;doit=1&amp;{$MYSID}">
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Register']}</span></td></tr>
<if:"{$error} != ''">
 <tr><td class="cellerror" colspan="2"><span class="fonterror">{$error}</span></td></tr>
</if>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Board_rules']}</span></td></tr>
<tr><td class="cellinfobox" colspan="2"><span class="fontinfobox">{$LNG['board_rules_info']}</span></td></tr>
<tr><td class="cellstd" colspan="2"><p align="justify"><span class="fontnorm"><b>{$LNG['board_rules_text']}</b></span></p></td></tr>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['General_information']}</span></td></tr>
<tr><td class="cellinfobox" colspan="2"><span class="fontinfobox">{$LNG['general_information_info']}</span></td></tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['User_name']}:</span><br /><span class="fontsmall">{$LNG['nick_conventions']}</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm"><input class="form_text" type="text" name="p_user_nick" maxlength="15" size="30" value="{$p_user_nick}" /></span></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Email_address']}:</span><br /><span class="fontsmall">{$LNG['email_address_info']}</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm"><input class="form_text" type="text" name="p_user_email" size="40" value="{$p_user_email}" /></span></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Email_address_confirmation']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm"><input class="form_text" type="text" name="p_user_email_confirmation" size="40" value="{$p_user_email_confirmation}" /></span></td>
</tr>
<if:"{$CONFIG['verify_email_address']} != 1">
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Password']}:</span><br /><span class="fontsmall">{$LNG['password_info']}</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm"><input class="form_text" type="password" name="p_user_pw" size="20" /></span></td>
</tr>
<tr>
 <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$LNG['Password_confirmation']}:</span></td>
 <td class="cellalt" width="75%" valign="top"><span class="fontnorm"><input class="form_text" type="password" name="p_user_pw_confirmation" size="20" /></span></td>
</tr>
</if>
<if:"{$profile_fields_counter} > 0">
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Other_information']}</span></td></tr>
<tr><td class="cellstd" colspan="2">
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
    <td class="cellstd" width="25%" valign="top"><span class="fontnorm">{$cur_field_data['field_name']}:</span></td>
    <td class="cellstd" width="75%"><select class="form_select" name="p_fields_data[{$cur_field_data['field_id']}][]" size="5" multiple="multiple">
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
</if>
<tr><td class="cellbuttons" colspan="2" align="center"><input type="submit" name="p_submit" value="{$LNG['Register']}" class="form_bbutton" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>