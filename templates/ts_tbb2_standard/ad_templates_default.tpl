<table class="tbl" border="0" cellspacing="0" cellpadding="3" width="100%">
<tr>
 <th class="thsmall"><span class="thsmall">{$lng['Template_name']}</span></th>
 <th class="thsmall"><span class="thsmall">{$lng['Template_author']}</span></th>
 <th class="thsmall"><span class="thsmall">{$lng['Author_comment']}</span></th>
</tr>
<template:tplrow>
 <tr>
  <td class="td1" valign="top"><span class="norm">{$akt_tconfig['basic_info']['template_name']}</span></td>
  <td class="td2" valign="top"><span class="norm">{$akt_author}</span></td>
  <td class="td1"><span class="small">{$akt_tconfig['basic_info']['author_comment']}</span></td>
 </tr>
</template>
</table>
<br />
<form method="post" action="administration.php?faction=ad_templates&amp;doit=1&amp;{$MYSID}" name="tbb2form">
<table class="tbl" border="0" cellspacing="0" cellpadding="3" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Template_settings']}</span></th></tr>
<tr>
 <td class="td1" width="25%"><span class="norm">{$lng['Standard_template']}</span></td>
 <td class="td1" width="75%"><select onchange="document.tbb2form.submit();" class="form_select" name="p_standard_tpl">
 <template:tploptionrow>
  <option value="{$akt_dir}"<if:"{$akt_dir} == {$p_standard_tpl}"> selected="selected"</if>>{$akt_tconfig['basic_info']['template_name']}</option>
 </template>
 </select></td>
</tr>
<tr>
 <td class="td2" width="25%"><span class="norm">{$lng['Allow_select_template']}</span></td>
 <td class="td2" width="75%"><select class="form_select" name="p_allow_select_tpl"><option value="1"{$checked['allow_select_tpl_1']}>{$lng['Yes']}</option><option value="0"{$checked['allow_select_tpl_0']}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td1" width="25%"><span class="norm">{$lng['Template_standard_style']}</span></td>
 <td class="td1" width="75%"><select class="form_select" name="p_standard_style">
 <template:stylerow>
  <option value="{$akt_dir}"<if:"{$akt_dir} == {$p_standard_style}"> selected="selected"</if>>{$akt_dir}</option>
 </template>
 </select></td>
</tr>
<tr>
 <td class="td2" width="25%"><span class="norm">{$lng['Allow_select_style']}</span></td>
 <td class="td2" width="75%"><select class="form_select" name="p_allow_select_style"><option value="1"{$checked['allow_select_style_1']}>{$lng['Yes']}</option><option value="0"{$checked['allow_select_style_0']}>{$lng['No']}</option></select></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng['Update_template_config']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>