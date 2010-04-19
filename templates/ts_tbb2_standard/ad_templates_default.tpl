<table class="tbl" border="0" cellspacing="0" cellpadding="3" width="100%">
<tr>
 <th class="thsmall"><span class="thsmall">{LNG_TEMPLATE_NAME}</span></th>
 <th class="thsmall"><span class="thsmall">{LNG_TEMPLATE_AUTHOR}</span></th>
 <th class="thsmall"><span class="thsmall">{LNG_AUTHOR_COMMENT}</span></th>
</tr>
<!-- TPLBLOCK tplrow -->
 <tr>
  <td class="td1" valign="top"><span class="norm">{tplrow.TEMPLATE_NAME}</span></td>
  <td class="td2" valign="top"><span class="norm">{tplrow.TEMPLATE_AUTHOR}</span></td>
  <td class="td1"><span class="small">{tplrow.TEMPLATE_AUTHOR_COMMENT}</span></td>
 </tr>
<!-- /TPLBLOCK tplrow -->
</table>
<br />
<form method="post" action="administration.php?faction=ad_templates&amp;doit=1&amp;{MYSID}" name="tbb2form">
<table class="tbl" border="0" cellspacing="0" cellpadding="3" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{LNG_TEMPLATE_SETTINGS}</span></th></tr>
<tr>
 <td class="td1" width="25%"><span class="norm">{LNG_STANDARD_TEMPLATE}</span></td>
 <td class="td1" width="75%"><select onchange="document.tbb2form.submit();" class="form_select" name="p_standard_tpl">
 <!-- TPLBLOCK tploptionrow -->
  <option value="{tploptionrow.TPL_FOLDER}"{tploptionrow.CHECKED}>{tploptionrow.TPL_NAME}</option>
 <!-- /TPLBLOCK tploptionrow -->
 </select></td>
</tr>
<tr>
 <td class="td2" width="25%"><span class="norm">{LNG_ALLOW_SELECT_TEMPLATE}</span></td>
 <td class="td2" width="75%"><select class="form_select" name="p_allow_select_tpl"><option value="1"{C_ALLOW_SELECT_TPL_1}>{LNG_YES}</option><option value="0"{C_ALLOW_SELECT_TPL_0}>{LNG_NO}</option></select></td>
</tr>
<tr>
 <td class="td1" width="25%"><span class="norm">{LNG_TEMPLATE_STANDARD_STYLE}</span></td>
 <td class="td1" width="75%"><select class="form_select" name="p_standard_style">
 <!-- TPLBLOCK stylerow -->
  <option value="{stylerow.STYLE_NAME}"{stylerow.CHECKED}>{stylerow.STYLE_NAME}</option>
 <!-- /TPLBLOCK stylerow -->
 </select></td>
</tr>
<tr>
 <td class="td2" width="25%"><span class="norm">{LNG_ALLOW_SELECT_STYLE}</span></td>
 <td class="td2" width="75%"><select class="form_select" name="p_allow_select_style"><option value="1"{C_ALLOW_SELECT_STYLE_1}>{LNG_YES}</option><option value="0"{C_ALLOW_SELECT_STYLE_0}>{LNG_NO}</option></select></td>
</tr>
<tr><td class="buttonrow" colspan="2" align="center"><input class="form_bbutton" type="submit" value="{LNG_UPDATE_TEMPLATE_CONFIG}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{LNG_RESET}" /></td></tr>
</table>
</form>