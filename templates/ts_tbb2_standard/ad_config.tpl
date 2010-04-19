<script type="text/javascript">
<!--
 function check_enable_sig() {
 /*	if(document.configform.elements[3].options[document.configform.elements[3].options.selectedIndex].value == 0) {
 		document.configform.elements[4].disabled = "disabled";
 		document.configform.elements[5].disabled = "disabled";
 		document.configform.elements[6].disabled = "disabled";
 	}
 	else {
 		document.configform.elements[4].disabled = "";
 		document.configform.elements[5].disabled = "";
 		document.configform.elements[6].disabled = ""; 	
 	}*/
 }
 
 function check_enable_wio() {
/* 	if(document.configform.elements[9].options[document.configform.elements[9].options.selectedIndex].value == 0) {
 		document.configform.elements[10].disabled = "disabled";
 		document.configform.elements[11].disabled = "disabled";
 	}
 	else {
 		document.configform.elements[10].disabled = "";
 		document.configform.elements[11].disabled = "";
 	}*/
 }
 
 function check_all() {
	check_enable_sig();
	check_enable_wio(); 	
 }
 
//-->
</script>
<form method="post" action="administration.php?faction=ad_config&amp;doit=1&amp;{$MYSID}" name="configform">
<table class="tbl" border="0" cellpadding="3" cellspacing="0" width="100%">
<colgroup>
 <col width="25%" />
 <col width="75%" />
</colgroup>
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng["Boardconfig"]}</span></th></tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng["General_settings"]}</span></td></tr>
<tr>
 <td class="td1"><span class="norm">{$lng["Board_name"]}:</span></td>
 <td class="td1"><input size="25" class="form_text" type="text" value="{$p_config["board_name"]}" name="p_config[board_name]" /></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng["Board_address"]}:</span></td>
 <td class="td2"><input size="50" class="form_text" type="text" value="{$p_config["board_address"]}" name="p_config[board_address]" /></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng["Board_logo"]}:</span></td>
 <td class="td1"><input size="50" class="form_text" type="text" value="{$p_config["board_logo"]}" name="p_config[board_logo]" /></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng["Topics_per_page"]}:</span></td>
 <td class="td2"><input size="4" class="form_text" type="text" value="{$p_config["topics_per_page"]}" name="p_config[topics_per_page]" /></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng["Posts_per_page"]}:</span></td>
 <td class="td1"><input size="4" class="form_text" type="text" value="{$p_config["posts_per_page"]}" name="p_config[posts_per_page]" /></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng["Guests_enter_board"]}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[guests_enter_board]"><option value="1"{$checked["guests_enter_board_1"]}>{$lng["Yes"]}</option><option value="0"{$checked["guests_enter_board_0"]}>{$lng["No"]}</option></select></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng["Enable_search"]}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[search_status]"><option value="0"{$checked["search_status_0"]}>{$lng["No"]}</option><option value="1"{$checked["search_status_1"]}>{$lng["Members_only"]}</option><option value="2"{$checked["search_status_2"]}>{$lng["Yes"]}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng["Show_boardstats_forumindex"]}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[show_boardstats_forumindex]"><option value="1"{$checked["show_boardstats_forumindex_1"]}>{$lng["Yes"]}</option><option value="0"{$checked["show_boardstats_forumindex_0"]}>{$lng["No"]}</option></select></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng["Registration_settings"]}</span></td></tr>
<tr>
 <td class="td1"><span class="norm">{$lng["Enable_registration"]}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[enable_registration]"><option value="1"{$checked["enable_registration_1"]}>{$lng["Yes"]}</option><option value="0"{$checked["enable_registration_0"]}>{$lng["No"]}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng["Verify_email_address"]}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[verify_email_address]"><option value="0"{$checked["verify_email_address_0"]}>{$lng["No"]}</option><option value="1"{$checked["verify_email_address_1"]}>{$lng["Create_random_password"]}</option><option value="2"{$checked["verify_email_address_2"]}>{$lng["Send_activation_code"]}</option></select></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng["Maximum_registrations"]}:</span></td>
 <td class="td1"><input size="8" class="form_text" type="text" value="{$p_config["maximum_registrations"]}" name="p_config[maximum_registrations]" /> <span class="small">({$lng["-1_infinite"]})</span></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng["Signature_settings"]}</span></td></tr>
<tr>
 <td class="td1"><span class="norm">{$lng["Enable_signature"]}:</span></td>
 <td class="td1"><select onchange="check_enable_sig();" class="form_select" name="p_config[enable_sig]"><option value="1"{$checked["enable_sig_1"]}>{$lng["Yes"]}</option><option value="0"{$checked["enable_sig_0"]}>{$lng["No"]}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng["Maximum_signature_length"]}:</span></td>
 <td class="td2"><input size="6" class="form_text" type="text" value="{$p_config["maximum_sig_length"]}" name="p_config[maximum_sig_length]" /></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng["Allow_signature_bbcode"]}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[allow_sig_bbcode]"><option value="1"{$checked["allow_sig_bbcode_1"]}>{$lng["Yes"]}</option><option value="0"{$checked["allow_sig_bbcode_0"]} />{$lng["No"]}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng["Allow_signature_html"]}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[allow_sig_html]"><option value="1"{$checked["allow_sig_html_1"]}>{$lng["Yes"]}</option><option value="0"{$checked["allow_sig_html_0"]}>{$lng["No"]}</option></select></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng["Language_settings"]}</span></td></tr>
<tr>
 <td class="td1"><span class="norm">{$lng["Standard_language"]}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[standard_language]">
 <!-- TPLBLOCK lng_optionrow -->
  <option value="{lng_optionrow.$akt_dir}"{lng_optionrow.$akt_c}>{lng_optionrow.$akt_dir}</option>
 <!-- /TPLBLOCK lng_optionrow -->
 </select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng["Allow_select_language"]}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[allow_select_lng]"><option value="1"{$checked["allow_select_lng_1"]}>{$lng["Yes"]}</option><option value="0"{$checked["allow_select_lng_0"]}>{$lng["No"]}</option></select></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng["Who_is_online_settings"]}</span></td></tr>
<tr>
 <td class="td1"><span class="norm">{$lng["Enable_who_is_online"]}:</span></td>
 <td class="td1"><select onchange="check_enable_wio();" class="form_select" name="p_config[enable_wio]"><option value="1"{$checked["enable_wio_1"]}>{$lng["Yes"]}</option><option value="0"{$checked["enable_wio_0"]}>{$lng["No"]}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng["Who_is_online_timeout"]}:</span></td>
 <td class="td2"><input size="6" class="form_text" type="text" value="{$p_config["wio_timeout"]}" name="p_config[wio_timeout]" /> <span class="small">{$lng["in_minutes"]}</small></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng["Show_who_is_online_box_forumindex"]}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[show_wio_forumindex]"><option value="1"{$checked["show_wio_forumindex_1"]}>{$lng["Yes"]}</option><option value="0"{$checked["show_wio_forumindex_0"]}>{$lng["No"]}</option></select></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng["Technical_settings"]}</span></td></tr>
<tr>
 <td class="td2"><span class="norm">{$lng["Enable_gzip_compression"]}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[enable_gzip]"><option value="1"{$checked["enable_gzip_1"]}>{$lng["Yes"]}</option><option value="0"{$checked["enable_gzip_0"]}>{$lng["No"]}</option></select></td>
</tr>
<tr><td colspan="2" class="buttonrow" align="center"><input type="submit" class="form_bbutton" value="{$lng["Update_config"]}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng["Reset"]}" /></td></tr>
</table>
</form>
<script type="text/javascript">
<!--
	check_all();
//-->
</script>