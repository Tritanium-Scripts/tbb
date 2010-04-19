<table class="tablestd" border="0" cellspacing="0" cellpadding="3" width="100%">
<tr>
 <td class="celltitle"><span class="fonttitle">{$LNG['Template_name']}</span></td>
 <td class="celltitle"><span class="fonttitle">{$LNG['Template_author']}</span></td>
 <td class="celltitle"><span class="fonttitle">{$LNG['Author_comment']}</span></td>
</tr>
<template:tplrow>
 <tr>
  <td class="cellstd" valign="top"><span class="fontnorm">{$akt_tconfig['basic_info']['template_name']}</span></td>
  <td class="cellalt" valign="top"><span class="fontnorm">{$akt_author}</span></td>
  <td class="cellstd"><span class="fontsmall">{$akt_tconfig['basic_info']['author_comment']}</span></td>
 </tr>
</template>
</table>
<br />
<form method="post" action="administration.php?faction=ad_templates&amp;doit=1&amp;{$MYSID}" name="tbb2form">
<table class="tablestd" border="0" cellspacing="0" cellpadding="3" width="100%">
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Template_settings']}</span></td></tr>
<tr>
 <td class="cellstd" width="25%"><span class="fontnorm">{$LNG['Standard_template']}</span></td>
 <td class="cellstd" width="75%"><select onchange="document.tbb2form.submit();" class="form_select" name="p_standard_tpl">
 <template:tploptionrow>
  <option value="{$akt_dir}"<if:"{$akt_dir} == {$p_standard_tpl}"> selected="selected"</if>>{$akt_tconfig['basic_info']['template_name']}</option>
 </template>
 </select></td>
</tr>
<tr>
 <td class="cellalt" width="25%"><span class="fontnorm">{$LNG['Allow_select_template']}</span></td>
 <td class="cellalt" width="75%"><select class="form_select" name="p_allow_select_tpl"><option value="1"{$checked['allow_select_tpl_1']}>{$LNG['Yes']}</option><option value="0"{$checked['allow_select_tpl_0']}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellstd" width="25%"><span class="fontnorm">{$LNG['Template_standard_style']}</span></td>
 <td class="cellstd" width="75%"><select class="form_select" name="p_standard_style">
 <template:stylerow>
  <option value="{$akt_dir}"<if:"{$akt_dir} == {$p_standard_style}"> selected="selected"</if>>{$akt_dir}</option>
 </template>
 </select></td>
</tr>
<tr>
 <td class="cellalt" width="25%"><span class="fontnorm">{$LNG['Allow_select_style']}</span></td>
 <td class="cellalt" width="75%"><select class="form_select" name="p_allow_select_style"><option value="1"{$checked['allow_select_style_1']}>{$LNG['Yes']}</option><option value="0"{$checked['allow_select_style_0']}>{$LNG['No']}</option></select></td>
</tr>
<tr><td class="cellbuttons" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$LNG['Update_template_config']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>