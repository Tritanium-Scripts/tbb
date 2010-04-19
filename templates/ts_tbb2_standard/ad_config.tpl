<script type="text/javascript">
<!--
 function check_enable_sig() {
 	if(document.configform.elements[4].options[document.configform.elements[4].options.selectedIndex].value == 0) {
 		document.configform.elements[5].disabled = "disabled";
 		document.configform.elements[6].disabled = "disabled";
 		document.configform.elements[7].disabled = "disabled";
 	}
 	else {
 		document.configform.elements[5].disabled = "";
 		document.configform.elements[6].disabled = "";
 		document.configform.elements[7].disabled = ""; 	
 	}
 }
 
 function check_enable_wio() {
 	if(document.configform.elements[10].options[document.configform.elements[10].options.selectedIndex].value == 0) {
 		document.configform.elements[11].disabled = "disabled";
 		document.configform.elements[12].disabled = "disabled";
 	}
 	else {
 		document.configform.elements[11].disabled = "";
 		document.configform.elements[12].disabled = "";
 	} 
 }
//-->
</script>
<form method="post" action="administration.php?faction=ad_config&amp;doit=1&amp;{MYSID}" name="configform">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><th class="thnorm" colspan="2"><span class="thnorm">{LNG_BOARDCONFIG}</span></th></tr>
<tr><td class="cat" colspan="2"><span class="cat">{LNG_GENERAL_SETTINGS}</span></td></tr>
<tr>
 <td width="20%" class="td1"><span class="norm">{LNG_TOPICS_PER_PAGE}:</span></td>
 <td class="td1"><input size="4" class="form_text" type="text" value="{P_CONFIG_TOPICS_PER_PAGE}" name="p_config[topics_per_page]" /></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{LNG_POSTS_PER_PAGE}:</span></td>
 <td class="td2"><input size="4" class="form_text" type="text" value="{P_CONFIG_POSTS_PER_PAGE}" name="p_config[posts_per_page]" /></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{LNG_BOARD_NAME}:</span></td>
 <td class="td1"><input size="25" class="form_text" type="text" value="{P_CONFIG_BOARD_NAME}" name="p_config[board_name]" /></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{LNG_TIME_FORMAT}:</span></td>
 <td class="td2"><input size="15" class="form_text" type="text" value="{P_CONFIG_TIME_FORMAT}" name="p_config[time_format]" /></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{LNG_SIGNATURE_SETTINGS}</span></td></tr>
<tr>
 <td class="td1"><span class="norm">{LNG_ENABLE_SIGNATURE}:</span></td>
 <td class="td1"><select onchange="check_enable_sig();" class="form_select" name="p_config[enable_sig]"><option value="1"{C_ENABLE_SIG_1}>{LNG_YES}</option><option value="0"{C_ENABLE_SIG_0}>{LNG_NO}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{LNG_MAXIMUM_SIGNATURE_LENGTH}:</span></td>
 <td class="td2"><input size="6" class="form_text" type="text" value="{P_CONFIG_MAXIMUM_SIG_LENGTH}" name="p_config[maximum_sig_length]" /></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{LNG_ALLOW_SIGNATURE_BBCODE}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[allow_sig_bbcode]"><option value="1"{C_ALLOW_SIG_BBCODE_1}>{LNG_YES}</option><option value="0"{C_ALLOW_SIG_BBCODE_0} />{LNG_NO}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{LNG_ALLOW_SIGNATURE_HTML}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[allow_sig_html]"><option value="1"{C_ALLOW_SIG_HTML_1}>{LNG_YES}</option><option value="0"{C_ALLOW_SIG_HTML_0}>{LNG_NO}</option></select></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{LNG_LANGUAGE_SETTINGS}</span></td></tr>
<tr>
 <td class="td1"><span class="norm">{LNG_STANDARD_LANGUAGE}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[standard_language]">
 <!-- TPLBLOCK lng_optionrow -->
  <option value="{lng_optionrow.DIR_NAME}"{lng_optionrow.CHECKED}>{lng_optionrow.DIR_NAME}</option>
 <!-- /TPLBLOCK lng_optionrow -->
 </select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{LNG_ALLOW_SELECT_LANGUAGE}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[allow_select_lng]"><option value="1"{C_ALLOW_SELECT_LNG_1}>{LNG_YES}</option><option value="0"{C_ALLOW_SELECT_LNG_0}>{LNG_NO}</option></select></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{LNG_WHO_IS_ONLINE_SETTINGS}</span></td></tr>
<tr>
 <td class="td1"><span class="norm">{LNG_ENABLE_WHO_IS_ONLINE}:</span></td>
 <td class="td1"><select onchange="check_enable_wio();" class="form_select" name="p_config[enable_wio]"><option value="1"{C_ENABLE_WIO_1}>{LNG_YES}</option><option value="0"{C_ENABLE_WIO_0}>{LNG_NO}</option></select></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{LNG_WHO_IS_ONLINE_TIMEOUT}:</span></td>
 <td class="td1"><input size="6" class="form_text" type="text" value="{P_CONFIG_WIO_TIMEOUT}" name="p_config[wio_timeout]" /> <span class="small">{LNG_IN_MINUTES}</small></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{LNG_SHOW_WIO_IS_ONLINE_BOX_FORUMINDEX}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[show_wio_forumindex]"><option value="1"{C_SHOW_WIO_FORUMINDEX_1}>{LNG_YES}</option><option value="0"{C_SHOW_WIO_FORUMINDEX_0}>{LNG_NO}</option></select></td>
</tr>
<tr><td colspan="2" class="buttonrow" align="center"><input type="submit" class="form_bbutton" value="{LNG_UPDATE_CONFIG}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{LNG_RESET}" /></td></tr>
</table>
</form>
<script type="text/javascript">
<!--
	check_enable_sig();
	check_enable_wio();
//-->
</script>
