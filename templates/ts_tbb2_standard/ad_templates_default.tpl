<table class="tbl" border="0" cellspacing="0" cellpadding="3" width="100%">
<tr>
 <th class="thsmall"><span class="thsmall">{$lng["Template_name"]}</span></th>
 <th class="thsmall"><span class="thsmall">{$lng["Template_author"]}</span></th>
 <th class="thsmall"><span class="thsmall">{$lng["Author_comment"]}</span></th>
</tr>
<!-- TPLBLOCK tplrow -->
 <tr>
  <td class="td1" valign="top"><span class="norm">{tplrow.$tpl_config["template_name"]}</span></td>
  <td class="td2" valign="top"><span class="norm">{tplrow.$akt_author}</span></td>
  <td class="td1"><span class="small">{tplrow.$tpl_config["template_author_comment"]}</span></td>
 </tr>
<!-- /TPLBLOCK tplrow -->
</table>
<br />
<form method="post" action="administration.php?faction=ad_templates&amp;doit=1&amp;{$MYSID}" name="tbb2form">
<table class="tbl" border="0" cellspacing="0" cellpadding="3" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng["Template_settings"]}</span></th></tr>
<tr>
 <td class="td1" width="25%"><span class="norm">{$lng["Standard_template"]}</span></td>
 <td class="td1" width="75%"><select onchange="document.tbb2form.submit();" class="form_select" name="p_standard_tpl">
 <!-- TPLBLOCK tploptionrow -->
  <option value="{tploptionrow.$akt_dir}"{tploptionrow.$akt_c}>{tploptionrow.$tpl_config["template_name"]}</option>
 <!-- /TPLBLOCK tploptionrow -->
 </select></td>
</tr>
<tr>
 <td class="td2" width="25%"><span class="norm">{$lng["Allow_select_template"]}</span></td>
 <td class="td2" width="75%"><select class="form_select" name="p_allow_select_tpl"><option value="1"{$checked["allow_select_tpl_1"]}>{$lng["Yes"]}</option><option value="0"{$checked["allow_select_tpl_0"]}>{$lng["No"]}</option></select></td>
</tr>
<tr>
 <td class="td1" width="25%"><span class="norm">{$lng["Template_standard_style"]}</span></td>
 <td class="td1" width="75%"><select class="form_select" name="p_standard_style">
 <!-- TPLBLOCK stylerow -->
  <option value="{stylerow.$akt_dir}"{stylerow.$akt_c}>{stylerow.$akt_dir}</option>
 <!-- /TPLBLOCK stylerow -->
 </select></td>
</tr>
<tr>
 <td class="td2" width="25%"><span class="norm">{$lng["Allow_select_style"]}</span></td>
 <td class="td2" width="75%"><select class="form_select" name="p_allow_select_style"><option value="1"{$checked["allow_select_style_1"]}>{$lng["Yes"]}</option><option value="0"{$checked["allow_select_style_0"]}>{$lng["No"]}</option></select></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{$lng["Update_template_config"]}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng["Reset"]}" /></td></tr>
</table>
</form>