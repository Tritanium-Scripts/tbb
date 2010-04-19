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
<table class="tablestd" border="0" cellpadding="3" cellspacing="0" width="100%">
<colgroup>
 <col width="25%" />
 <col width="75%" />
</colgroup>
<tr><td class="celltitle" colspan="2"><span class="fonttitle">{$LNG['Boardconfig']}</span></td></tr>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['General_settings']}</span></td></tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Board_name']}:</span></td>
 <td class="cellalt"><input size="25" class="form_text" type="text" value="{$p_config['board_name']}" name="p_config[board_name]" /></td>
</tr>
<tr>
 <td class="cellstd" valign="top"><span class="fontnorm">{$LNG['Board_address']}:</span><br /><span class="fontsmall">{$LNG['board_address_info']}</span></td>
 <td class="cellstd" valign="top"><input size="50" class="form_text" type="text" value="{$p_config['board_address']}" name="p_config[board_address]" /></td>
</tr>
<tr>
 <td class="cellalt" valign="top"><span class="fontnorm">{$LNG['Path_to_forum']}:</span><br /><span class="fontsmall">{$LNG['path_to_forum_info']}</span></td>
 <td class="cellalt" valign="top"><input size="50" class="form_text" type="text" value="{$p_config['path_to_forum']}" name="p_config[path_to_forum]" /></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Board_logo']}:</span></td>
 <td class="cellstd"><input size="50" class="form_text" type="text" value="{$p_config['board_logo']}" name="p_config[board_logo]" /></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Topics_per_page']}:</span></td>
 <td class="cellalt"><input size="4" class="form_text" type="text" value="{$p_config['topics_per_page']}" name="p_config[topics_per_page]" /></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Posts_per_page']}:</span></td>
 <td class="cellstd"><input size="4" class="form_text" type="text" value="{$p_config['posts_per_page']}" name="p_config[posts_per_page]" /></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Guests_enter_board']}:</span></td>
 <td class="cellalt"><select class="form_select" name="p_config[guests_enter_board]"><option value="1"{$checked['guests_enter_board'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['guests_enter_board'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Enable_search']}:</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[search_status]"><option value="0"{$checked['search_status'][0]}>{$LNG['No']}</option><option value="1"{$checked['search_status'][1]}>{$LNG['Members_only']}</option><option value="2"{$checked['search_status'][2]}>{$LNG['Yes']}</option></select></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Search_results_timeout']}:</span><br /><span class="fontsmall">{$LNG['search_results_timeout_info']}</span></td>
 <td class="cellalt"><input size="5" class="form_text" type="text" value="{$p_config['sr_timeout']}" name="p_config[sr_timeout]" maxlength="4" /> <span class="fontsmall">{$LNG['in_minutes']}</span></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Show_boardstats_forumindex']}:</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[show_boardstats_forumindex]"><option value="1"{$checked['show_boardstats_forumindex'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['show_boardstats_forumindex'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Show_latest_posts_box_forumindex']}:</span></td>
 <td class="cellalt"><select class="form_select" name="p_config[show_latest_posts_forumindex]"><option value="1"{$checked['show_latest_posts_forumindex'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['show_latest_posts_forumindex'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Maximum_latest_posts']}:</span></td>
 <td class="cellstd"><input size="3" class="form_text" type="text" value="{$p_config['max_latest_posts']}" name="p_config[max_latest_posts]" maxlength="2" /></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Rank_pic_administrators']}:</span></td>
 <td class="cellalt"><input size="40" class="form_text" type="text" value="{$p_config['admin_rank_pic']}" name="p_config[admin_rank_pic]" maxlength="255" /></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Rank_pic_moderators']}:</span></td>
 <td class="cellstd"><input size="40" class="form_text" type="text" value="{$p_config['mod_rank_pic']}" name="p_config[mod_rank_pic]" maxlength="255" /></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Show_technical_statistics']}:</span></td>
 <td class="cellalt"><select class="form_select" name="p_config[show_techstats]"><option value="1"{$checked['show_techstats'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['show_techstats'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Standard_timezone']}:</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[standard_tz]">
 <template:tzrow>
  <option value="{$akt_tz_id}"{$akt_checked}>{$akt_tz_name}</option>
 </template>
 </select></td>
</tr>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Email_settings']}</span></td></tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Enable_email_functions']}:</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[enable_email_functions]"><option value="1"{$checked['enable_email_functions'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['enable_email_functions'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Board_email_address']}:</span><br /><span class="fontsmall">{$LNG['board_email_address_info']}</span></td>
 <td class="cellalt"><input size="40" class="form_text" type="text" value="{$p_config['board_email_address']}" name="p_config[board_email_address]" maxlength="255" /></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Board_email_signature']}:</span><br /><span class="fontsmall">{$LNG['board_email_signature_info']}</span></td>
 <td class="cellstd"><textarea class="form_textarea" name="p_config[email_signature]" cols="40" rows="4">{$p_config['email_signature']}</textarea></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Enable_topic_subscriptions']}:</span></td>
 <td class="cellalt"><select class="form_select" name="p_config[enable_topic_subscription]"><option value="1"{$checked['enable_topic_subscription'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['enable_topic_subscription'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Enable_email_formular']}:</span><br /><span class="fontsmall">{$LNG['email_formular_info']}</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[enable_email_formular]"><option value="1"{$checked['enable_email_formular'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['enable_email_formular'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['News_settings']}</span></td></tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['News_forum']}:</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[news_forum]">
  <option value="0"<if:"{$p_config['news_forum']} == 0"> selected="selected"</if>>{$LNG['No_news_forum']}</option>
 <template:forumrow>
  <option value="{$akt_forum['forum_id']}"<if:"{$akt_forum['forum_id']} == {$p_config['news_forum']}"> selected="selected"</if>>{$akt_forum['forum_name']}</option>
 </template>
 </select></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Display_news_forumindex']}:</span></td>
 <td class="cellalt"><select class="form_select" name="p_config[show_news_forumindex]"><option value="1"{$checked['show_news_forumindex'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['show_news_forumindex'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Enable_news_module']}:</span><br /><span class="fontsmall">{$LNG['news_module_info']}</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[enable_news_module]"><option value="1"{$checked['enable_news_module'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['enable_news_module'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Registration_settings']}</span></td></tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Enable_registration']}:</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[enable_registration]"><option value="1"{$checked['enable_registration'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['enable_registration'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['User_must_accept_board_rules']}:</span></td>
 <td class="cellalt"><select class="form_select" name="p_config[require_accept_boardrules]"><option value="1"{$checked['require_accept_boardrules'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['require_accept_boardrules'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Verify_email_address']}:</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[verify_email_address]"><option value="0"{$checked['verify_email_address'][0]}>{$LNG['No']}</option><option value="1"{$checked['verify_email_address'][1]}>{$LNG['Create_random_password']}</option><option value="2"{$checked['verify_email_address'][2]}>{$LNG['Send_activation_code']}</option></select></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Maximum_registrations']}:</span></td>
 <td class="cellalt"><input size="8" class="form_text" type="text" value="{$p_config['maximum_registrations']}" name="p_config[maximum_registrations]" /> <span class="fontsmall">({$LNG['-1_infinite']})</span></td>
</tr>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Signature_settings']}</span></td></tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Enable_signature']}:</span></td>
 <td class="cellstd"><select onchange="check_enable_sig();" class="form_select" name="p_config[enable_sig]"><option value="1"{$checked['enable_sig'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['enable_sig'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Maximum_signature_length']}:</span></td>
 <td class="cellalt"><input size="6" class="form_text" type="text" value="{$p_config['maximum_sig_length']}" name="p_config[maximum_sig_length]" /></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Allow_signature_bbcode']}:</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[allow_sig_bbcode]"><option value="1"{$checked['allow_sig_bbcode'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['allow_sig_bbcode'][0]} />{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Allow_signature_html']}:</span></td>
 <td class="cellalt"><select class="form_select" name="p_config[allow_sig_html]"><option value="1"{$checked['allow_sig_html'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['allow_sig_html'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Allow_signature_smilies']}:</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[allow_sig_smilies]"><option value="1"{$checked['allow_sig_smilies'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['allow_sig_smilies'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Avatar_settings']}</span></td></tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Enable_avatars']}:</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[enable_avatars]"><option value="1"{$checked['enable_avatars'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['enable_avatars'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Avatar_image_height']}:</span></td>
 <td class="cellalt"><input size="6" class="form_text" type="text" value="{$p_config['avatar_image_height']}" name="p_config[avatar_image_height]" /></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Avatar_image_width']}:</span></td>
 <td class="cellstd"><input size="6" class="form_text" type="text" value="{$p_config['avatar_image_width']}" name="p_config[avatar_image_width]" /></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Enable_avatar_upload']}:</span></td>
 <td class="cellalt"><select class="form_select" name="p_config[enable_avatar_upload]"><option value="1"{$checked['enable_avatar_upload'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['enable_avatar_upload'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Maximum_avatar_file_size']}:</span></td>
 <td class="cellstd"><input size="4" class="form_text" type="text" value="{$p_config['max_avatar_file_size']}" name="p_config[max_avatar_file_size]" maxlength="4" /> <span class="fontsmall">{$LNG['in_kilobytes']}</span></td>
</tr>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Language_settings']}</span></td></tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Standard_language']}:</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[standard_language]">
 <template:lng_optionrow>
  <option value="{$akt_dir}"{$akt_c}>{$akt_dir}</option>
 </template>
 </select></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Allow_select_language']}:</span></td>
 <td class="cellalt"><select class="form_select" name="p_config[allow_select_lng]"><option value="1"{$checked['allow_select_lng'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['allow_select_lng'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Who_is_online_settings']}</span></td></tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Enable_who_is_online']}:</span></td>
 <td class="cellstd"><select onchange="check_enable_wio();" class="form_select" name="p_config[enable_wio]"><option value="1"{$checked['enable_wio'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['enable_wio'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Who_is_online_timeout']}:</span></td>
 <td class="cellalt"><input size="6" class="form_text" type="text" value="{$p_config['wio_timeout']}" name="p_config[wio_timeout]" /> <span class="fontsmall">{$LNG['in_minutes']}</small></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Show_who_is_online_box_forumindex']}:</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[show_wio_forumindex]"><option value="1"{$checked['show_wio_forumindex'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['show_wio_forumindex'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Private_messages_settings']}</span></td></tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Enable_private_messages']}:</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[enable_pms]"><option value="1"{$checked['enable_pms'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['enable_pms'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Maximum_additional_folders']}:</span></td>
 <td class="cellalt"><input class="form_text" type="text" name="p_config[maximum_pms_folders]" value="{$p_config['maximum_pms_folders']}" /> <span class="fontsmall">({$LNG['-1_infinite']})</span></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Maximum_private_messages']}:</span></td>
 <td class="cellstd"><input class="form_text" type="text" name="p_config[maximum_pms]" value="{$p_config['maximum_pms']}" /> <span class="fontsmall">({$LNG['-1_infinite']})</span></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Allow_pms_signature']}:</span></td>
 <td class="cellalt"><select class="form_select" name="p_config[allow_pms_signature]"><option value="1"{$checked['allow_pms_signature'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['allow_pms_signature'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Allow_pms_smilies']}:</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[allow_pms_smilies]"><option value="1"{$checked['allow_pms_smilies'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['allow_pms_smilies'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Allow_pms_bbcode']}:</span></td>
 <td class="cellalt"><select class="form_select" name="p_config[allow_pms_bbcode]"><option value="1"{$checked['allow_pms_bbcode'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['allow_pms_bbcode'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Allow_pms_htmlcode']}:</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[allow_pms_htmlcode]"><option value="1"{$checked['allow_pms_htmlcode'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['allow_pms_htmlcode'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Enable_outbox']}:</span></td>
 <td class="cellalt"><select class="form_select" name="p_config[enable_outbox]"><option value="1"{$checked['enable_outbox'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['enable_outbox'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Enable_pms_read_confirmation']}:</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[allow_pms_rconfirmation]"><option value="1"{$checked['allow_pms_rconfirmation'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['allow_pms_rconfirmation'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr><td class="cellcat" colspan="2"><span class="fontcat">{$LNG['Technical_settings']}</span></td></tr>
<tr>
 <td class="cellstd"><span class="fontnorm">{$LNG['Enable_gzip_compression']}:</span></td>
 <td class="cellstd"><select class="form_select" name="p_config[enable_gzip]"><option value="1"{$checked['enable_gzip'][1]}>{$LNG['Yes']}</option><option value="0"{$checked['enable_gzip'][0]}>{$LNG['No']}</option></select></td>
</tr>
<tr>
 <td class="cellalt"><span class="fontnorm">{$LNG['Search_garbage_collection_probability']}:</span></td>
 <td class="cellalt"><input size="3" class="form_text" type="text" name="p_config[srgc_probability]" value="{$p_config['srgc_probability']}" maxlength="3" /> <span class="fontsmall">{$LNG['in_percent']}</span></td>
</tr>
<tr><td colspan="2" class="cellbuttons" align="center"><input type="submit" class="form_bbutton" value="{$LNG['Update_config']}" />&nbsp;&nbsp;&nbsp;<input class="form_button" type="reset" value="{$LNG['Reset']}" /></td></tr>
</table>
</form>
<script type="text/javascript">
<!--
	check_all();
//-->
</script>