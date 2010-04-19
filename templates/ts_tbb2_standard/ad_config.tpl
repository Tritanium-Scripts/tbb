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
<tr><th class="thnorm" colspan="2"><span class="thnorm">{$lng['Boardconfig']}</span></th></tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['General_settings']}</span></td></tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Board_name']}:</span></td>
 <td class="td1"><input size="25" class="form_text" type="text" value="{$p_config['board_name']}" name="p_config[board_name]" /></td>
</tr>
<tr>
 <td class="td2" valign="top"><span class="norm">{$lng['Board_address']}:</span><br /><span class="small">{$lng['board_address_info']}</span></td>
 <td class="td2" valign="top"><input size="50" class="form_text" type="text" value="{$p_config['board_address']}" name="p_config[board_address]" /></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Board_logo']}:</span></td>
 <td class="td1"><input size="50" class="form_text" type="text" value="{$p_config['board_logo']}" name="p_config[board_logo]" /></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Topics_per_page']}:</span></td>
 <td class="td2"><input size="4" class="form_text" type="text" value="{$p_config['topics_per_page']}" name="p_config[topics_per_page]" /></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Posts_per_page']}:</span></td>
 <td class="td1"><input size="4" class="form_text" type="text" value="{$p_config['posts_per_page']}" name="p_config[posts_per_page]" /></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Guests_enter_board']}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[guests_enter_board]"><option value="1"{$checked['guests_enter_board'][1]}>{$lng['Yes']}</option><option value="0"{$checked['guests_enter_board'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Enable_search']}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[search_status]"><option value="0"{$checked['search_status'][0]}>{$lng['No']}</option><option value="1"{$checked['search_status'][1]}>{$lng['Members_only']}</option><option value="2"{$checked['search_status'][2]}>{$lng['Yes']}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Search_results_timeout']}:</span><br /><span class="small">{$lng['search_results_timeout_info']}</span></td>
 <td class="td2"><input size="5" class="form_text" type="text" value="{$p_config['sr_timeout']}" name="p_config[sr_timeout]" maxlength="4" /> <span class="small">{$lng['in_minutes']}</span></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Show_boardstats_forumindex']}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[show_boardstats_forumindex]"><option value="1"{$checked['show_boardstats_forumindex'][1]}>{$lng['Yes']}</option><option value="0"{$checked['show_boardstats_forumindex'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Show_latest_posts_box_forumindex']}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[show_latest_posts_forumindex]"><option value="1"{$checked['show_latest_posts_forumindex'][1]}>{$lng['Yes']}</option><option value="0"{$checked['show_latest_posts_forumindex'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Maximum_latest_posts']}:</span></td>
 <td class="td1"><input size="3" class="form_text" type="text" value="{$p_config['max_latest_posts']}" name="p_config[max_latest_posts]" maxlength="2" /></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Rank_pic_administrators']}:</span></td>
 <td class="td2"><input size="40" class="form_text" type="text" value="{$p_config['admin_rank_pic']}" name="p_config[admin_rank_pic]" maxlength="255" /></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Rank_pic_moderators']}:</span></td>
 <td class="td1"><input size="40" class="form_text" type="text" value="{$p_config['mod_rank_pic']}" name="p_config[mod_rank_pic]" maxlength="255" /></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Show_technical_statistics']}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[show_techstats]"><option value="1"{$checked['show_techstats'][1]}>{$lng['Yes']}</option><option value="0"{$checked['show_techstats'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['Email_settings']}</span></td></tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Enable_email_functions']}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[enable_email_functions]"><option value="1"{$checked['enable_email_functions'][1]}>{$lng['Yes']}</option><option value="0"{$checked['enable_email_functions'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Board_email_address']}:</span><br /><span class="small">{$lng['board_email_address_info']}</span></td>
 <td class="td2"><input size="40" class="form_text" type="text" value="{$p_config['board_email_address']}" name="p_config[board_email_address]" maxlength="255" /></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Board_email_signature']}:</span><br /><span class="small">{$lng['board_email_signature_info']}</span></td>
 <td class="td1"><textarea class="form_textarea" name="p_config[email_signature]" cols="40" rows="4">{$p_config['email_signature']}</textarea></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Enable_topic_subscriptions']}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[enable_topic_subscription]"><option value="1"{$checked['enable_topic_subscription'][1]}>{$lng['Yes']}</option><option value="0"{$checked['enable_topic_subscription'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['Registration_settings']}</span></td></tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Enable_registration']}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[enable_registration]"><option value="1"{$checked['enable_registration'][1]}>{$lng['Yes']}</option><option value="0"{$checked['enable_registration'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['User_must_accept_board_rules']}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[require_accept_boardrules]"><option value="1"{$checked['require_accept_boardrules'][1]}>{$lng['Yes']}</option><option value="0"{$checked['require_accept_boardrules'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Verify_email_address']}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[verify_email_address]"><option value="0"{$checked['verify_email_address'][0]}>{$lng['No']}</option><option value="1"{$checked['verify_email_address'][1]}>{$lng['Create_random_password']}</option><option value="2"{$checked['verify_email_address'][2]}>{$lng['Send_activation_code']}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Maximum_registrations']}:</span></td>
 <td class="td2"><input size="8" class="form_text" type="text" value="{$p_config['maximum_registrations']}" name="p_config[maximum_registrations]" /> <span class="small">({$lng['-1_infinite']})</span></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['Signature_settings']}</span></td></tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Enable_signature']}:</span></td>
 <td class="td1"><select onchange="check_enable_sig();" class="form_select" name="p_config[enable_sig]"><option value="1"{$checked['enable_sig'][1]}>{$lng['Yes']}</option><option value="0"{$checked['enable_sig'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Maximum_signature_length']}:</span></td>
 <td class="td2"><input size="6" class="form_text" type="text" value="{$p_config['maximum_sig_length']}" name="p_config[maximum_sig_length]" /></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Allow_signature_bbcode']}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[allow_sig_bbcode]"><option value="1"{$checked['allow_sig_bbcode'][1]}>{$lng['Yes']}</option><option value="0"{$checked['allow_sig_bbcode'][0]} />{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Allow_signature_html']}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[allow_sig_html]"><option value="1"{$checked['allow_sig_html'][1]}>{$lng['Yes']}</option><option value="0"{$checked['allow_sig_html'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Allow_signature_smilies']}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[allow_sig_smilies]"><option value="1"{$checked['allow_sig_smilies'][1]}>{$lng['Yes']}</option><option value="0"{$checked['allow_sig_smilies'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['Avatar_settings']}</span></td></tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Enable_avatars']}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[enable_avatars]"><option value="1"{$checked['enable_avatars'][1]}>{$lng['Yes']}</option><option value="0"{$checked['enable_avatars'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Avatar_image_height']}:</span></td>
 <td class="td2"><input size="6" class="form_text" type="text" value="{$p_config['avatar_image_height']}" name="p_config[avatar_image_height]" /></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Avatar_image_width']}:</span></td>
 <td class="td1"><input size="6" class="form_text" type="text" value="{$p_config['avatar_image_width']}" name="p_config[avatar_image_width]" /></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Enable_avatar_file_upload']}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[enable_avatar_file_upload]"><option value="1"{$checked['enable_avatar_file_upload'][1]}>{$lng['Yes']}</option><option value="0"{$checked['enable_avatar_file_upload'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Maximum_avatar_file_size']}:</span></td>
 <td class="td1"><input size="4" class="form_text" type="text" value="{$p_config['max_avatar_file_size']}" name="p_config[max_avatar_file_size]" maxlength="4" /> <span class="small">{$lng['in_kilobytes']}</span></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['Language_settings']}</span></td></tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Standard_language']}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[standard_language]">
 <template:lng_optionrow>
  <option value="{lng_optionrow.$akt_dir}"{lng_optionrow.$akt_c}>{lng_optionrow.$akt_dir}</option>
 </template:lng_optionrow>
 </select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Allow_select_language']}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[allow_select_lng]"><option value="1"{$checked['allow_select_lng'][1]}>{$lng['Yes']}</option><option value="0"{$checked['allow_select_lng'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['Who_is_online_settings']}</span></td></tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Enable_who_is_online']}:</span></td>
 <td class="td1"><select onchange="check_enable_wio();" class="form_select" name="p_config[enable_wio]"><option value="1"{$checked['enable_wio'][1]}>{$lng['Yes']}</option><option value="0"{$checked['enable_wio'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Who_is_online_timeout']}:</span></td>
 <td class="td2"><input size="6" class="form_text" type="text" value="{$p_config['wio_timeout']}" name="p_config[wio_timeout]" /> <span class="small">{$lng['in_minutes']}</small></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Show_who_is_online_box_forumindex']}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[show_wio_forumindex]"><option value="1"{$checked['show_wio_forumindex'][1]}>{$lng['Yes']}</option><option value="0"{$checked['show_wio_forumindex'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['Private_messages_settings']}</span></td></tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Enable_private_messages']}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[enable_pms]"><option value="1"{$checked['enable_pms'][1]}>{$lng['Yes']}</option><option value="0"{$checked['enable_pms'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Maximum_additional_folders']}:</span></td>
 <td class="td2"><input class="form_text" type="text" name="p_config[maximum_pms_folders]" value="{$p_config['maximum_pms_folders']}" /> <span class="small">({$lng['-1_infinite']})</span></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Maximum_private_messages']}:</span></td>
 <td class="td1"><input class="form_text" type="text" name="p_config[maximum_pms]" value="{$p_config['maximum_pms']}" /> <span class="small">({$lng['-1_infinite']})</span></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Allow_pms_signature']}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[allow_pms_signature]"><option value="1"{$checked['allow_pms_signature'][1]}>{$lng['Yes']}</option><option value="0"{$checked['allow_pms_signature'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Allow_pms_smilies']}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[allow_pms_smilies]"><option value="1"{$checked['allow_pms_smilies'][1]}>{$lng['Yes']}</option><option value="0"{$checked['allow_pms_smilies'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Allow_pms_bbcode']}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[allow_pms_bbcode]"><option value="1"{$checked['allow_pms_bbcode'][1]}>{$lng['Yes']}</option><option value="0"{$checked['allow_pms_bbcode'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Allow_pms_htmlcode']}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[allow_pms_htmlcode]"><option value="1"{$checked['allow_pms_htmlcode'][1]}>{$lng['Yes']}</option><option value="0"{$checked['allow_pms_htmlcode'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Enable_outbox']}:</span></td>
 <td class="td2"><select class="form_select" name="p_config[enable_outbox]"><option value="1"{$checked['enable_outbox'][1]}>{$lng['Yes']}</option><option value="0"{$checked['enable_outbox'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Enable_pms_read_confirmation']}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[allow_pms_rconfirmation]"><option value="1"{$checked['allow_pms_rconfirmation'][1]}>{$lng['Yes']}</option><option value="0"{$checked['allow_pms_rconfirmation'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr><td class="cat" colspan="2"><span class="cat">{$lng['Technical_settings']}</span></td></tr>
<tr>
 <td class="td1"><span class="norm">{$lng['Enable_gzip_compression']}:</span></td>
 <td class="td1"><select class="form_select" name="p_config[enable_gzip]"><option value="1"{$checked['enable_gzip'][1]}>{$lng['Yes']}</option><option value="0"{$checked['enable_gzip'][0]}>{$lng['No']}</option></select></td>
</tr>
<tr>
 <td class="td2"><span class="norm">{$lng['Search_garbage_collection_probability']}:</span></td>
 <td class="td2"><input size="3" class="form_text" type="text" name="p_config[srgc_probability]" value="{$p_config['srgc_probability']}" maxlength="3" /> <span class="small">{$lng['in_percent']}</span></td>
</tr>
<tr><td colspan="2" class="buttonrow" align="center"><input type="submit" class="form_bbutton" value="{$lng['Update_config']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$lng['Reset']}" /></td></tr>
</table>
</form>
<script type="text/javascript">
<!--
	check_all();
//-->
</script>