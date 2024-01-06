<!-- AdminConfig -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_settings&amp;mode=editsettings{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <colgroup>
  <col width="35%" />
  <col width="65%" />
 </colgroup>
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('edit_settings')}</span></th></tr>
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('maintenance_mode')}</span></td></tr>
 <tr><td class="td1" colspan="2"><span class="small">{Language::getInstance()->getString('maintenance_mode_description')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('maintenance_mode')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y15" name="settings[15]" value="1"{if $configValues['uc'] == 1} checked="checked"{/if} /><label for="y15" class="norm">{Language::getInstance()->getString('enabled')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n15" name="settings[15]" value="0"{if $configValues['uc'] != 1} checked="checked"{/if} /><label for="n15" class="norm">{Language::getInstance()->getString('disabled')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="vertical-align:top;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('maintenance_mode_message')}</span><br /><span class="small">{Language::getInstance()->getString('xhtml_code_is_enabled')}</span></td>
  <td class="td1" style="vertical-align:top;"><textarea name="settings[7]" cols="50" rows="10" readonly="readonly" onclick="alert('{Language::getInstance()->getLangCode()|string_format:Language::getInstance()->getString('maintenance_mode_message_hint')}');">{$configValues['uc_message']|escape}</textarea></td>
 </tr>
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('general_settings')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('address_to_forum')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[1]" value="{$configValues['address_to_forum']}" style="width:250px;" />&nbsp;<span class="small">{Language::getInstance()->getString('address_to_forum_example')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('site_name_of_forum')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[2]" value="{$configValues['site_name']|escape}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('site_address_of_forum')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[3]" value="{$configValues['site_address']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('contact_mail_address')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[4]" value="{$configValues['site_contact']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('name_of_forum')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[5]" value="{$configValues['forum_name']|escape}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('logo_of_forum')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[6]" value="{$configValues['forum_logo']}" style="width:250px;" />&nbsp;<span class="small">{Language::getInstance()->getString('address_or_path')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('privacy_policy')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[83]" value="{$configValues['privacy_policy_link']}" style="width:250px;" />&nbsp;<span class="small">{Language::getInstance()->getString('address_or_path')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('timezone')}</span></td>
  <td class="td1" style="vertical-align:top;"><select name="settings[8]">{foreach $timeZones as $curTimeZone}<option value="{$curTimeZone[0]}"{if $configValues['gmt_offset'] == $curTimeZone[0]} selected="selected"{/if}>{$curTimeZone[1]}</option>{/foreach}</select></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('only_allow_logged_in_users_access_to_forum')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y25" name="settings[25]" value="1"{if $configValues['must_be_logged_in'] == 1} checked="checked"{/if} /><label for="y25" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n25" name="settings[25]" value="0"{if $configValues['must_be_logged_in'] != 1} checked="checked"{/if} /><label for="n25" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('show_categories_in_forum_index')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y23" name="settings[23]" value="1"{if $configValues['show_kats'] == 1} checked="checked"{/if} /><label for="y23" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n23" name="settings[23]" value="0"{if $configValues['show_kats'] != 1} checked="checked"{/if} /><label for="n23" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('show_forum_stats_in_forum_index')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y21" name="settings[21]" value="1"{if $configValues['show_board_stats'] == 1} checked="checked"{/if} /><label for="y21" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n21" name="settings[21]" value="0"{if $configValues['show_board_stats'] != 1} checked="checked"{/if} /><label for="n21" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('amount_of_newest_posts_in_forum_index')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[22]" value="{$configValues['show_lposts']}" style="width:250px;" />&nbsp;<span class="small">{Language::getInstance()->getString('zero_disables_function')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('show_technical_stats_at_the_end_of_every_page')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y20" name="settings[20]" value="1"{if $configValues['show_site_creation_time'] == 1} checked="checked"{/if} /><label for="y20" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n20" name="settings[20]" value="0"{if $configValues['show_site_creation_time'] != 1} checked="checked"{/if} /><label for="n20" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('censor_topics_posts_and_signatures')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y24" name="settings[24]" value="1"{if $configValues['censored'] == 1} checked="checked"{/if} /><label for="y24" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n24" name="settings[24]" value="0"{if $configValues['censored'] != 1} checked="checked"{/if} /><label for="n24" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('number_of_topics_per_page')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[16]" value="{$configValues['topics_per_page']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('number_of_posts_per_page')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[17]" value="{$configValues['posts_per_page']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('position_of_forum_news')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y32" name="settings[32]" value="1"{if $configValues['news_position'] == 1} checked="checked"{/if} /><label for="y32" class="norm">{Language::getInstance()->getString('above_the_forums_bar')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n32" name="settings[32]" value="2"{if $configValues['news_position'] == 2} checked="checked"{/if} /><label for="n32" class="norm">{Language::getInstance()->getString('below_the_forums_bar')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('by_how_many_replies_a_topic_is_hot')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[33]" value="{$configValues['topic_is_hot']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('user_must_be_logged_in_to_view_profiles')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y84" name="settings[84]" value="1"{if $configValues['profile_mbli'] == 1} checked="checked"{/if} /><label for="y84" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n84" name="settings[84]" value="0"{if $configValues['profile_mbli'] != 1} checked="checked"{/if} /><label for="n84" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('user_must_be_logged_in_to_send_form_mails')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y34" name="settings[34]" value="1"{if $configValues['formmail_mbli'] == 1} checked="checked"{/if} /><label for="y34" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n34" name="settings[34]" value="0"{if $configValues['formmail_mbli'] != 1} checked="checked"{/if} /><label for="n34" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('enable_member_list')}</span></td>
  <td class="td1" style="vertical-align:top;">{html_options name='settings[35]' values=array(1, 0, 2) output=array(Language::getInstance()->getString('positive'), Language::getInstance()->getString('negative'), Language::getInstance()->getString('for_members_only')) selected=$configValues['activate_mlist']}</td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('number_of_members_per_page')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[66]" value="{$configValues['members_per_page']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('guests_must_enter_name_to_post')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y36" name="settings[36]" value="1"{if $configValues['nli_must_enter_name'] == 1} checked="checked"{/if} /><label for="y36" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n36" name="settings[36]" value="0"{if $configValues['nli_must_enter_name'] != 1} checked="checked"{/if} /><label for="n36" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('show_forums_without_access_rights')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y37" name="settings[37]" value="1"{if $configValues['show_private_forums'] == 1} checked="checked"{/if} /><label for="y37" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n37" name="settings[37]" value="0"{if $configValues['show_private_forums'] != 1} checked="checked"{/if} /><label for="n37" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('css_file')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[38]" value="{$configValues['css_file']}" style="width:250px;" readonly="readonly" onclick="alert('{Language::getInstance()->getString('css_file_hint')}');" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('table_width')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[39]" value="{$oldTableWidth}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('cell_spacing')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[40]" value="{$configValues['tspacing']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('cell_padding')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[41]" value="{$configValues['tpadding']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('max_height_of_avatars')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[47]" value="{$configValues['avatar_height']}" style="width:250px;" />&nbsp;<span class="small">{Language::getInstance()->getString('in_pixel')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('max_width_of_avatars')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[48]" value="{$configValues['avatar_width']}" style="width:250px;" />&nbsp;<span class="small">{Language::getInstance()->getString('in_pixel')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('native_language_of_forum')}</span></td>
  <td class="td1" style="vertical-align:top;"><select name="settings[50]">{foreach Language::getInstance()->getAvailLangs() as $curLangCode}<option value="{$curLangCode}"{if $curLangCode == $configValues['lng_folder']} selected="selected"{/if}>{$curLangCode}</option>{/foreach}</select></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('emphasize_date_not_older_than')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[63]" value="{$configValues['emph_date_hours']}" style="width:250px;" />&nbsp;<span class="small">{Language::getInstance()->getString('in_hours')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('show_date_of_last_two_days_as_text')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y81" name="settings[81]" value="1"{if $configValues['date_as_text'] == 1} checked="checked"{/if} /><label for="y81" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n81" name="settings[81]" value="0"{if $configValues['date_as_text'] != 1} checked="checked"{/if} /><label for="n81" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('enable_steam_achievements_in_profiles')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y67" name="settings[67]" value="1"{if $configValues['achievements'] == 1} checked="checked"{/if} /><label for="y67" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n67" name="settings[67]" value="0"{if $configValues['achievements'] != 1} checked="checked"{/if} /><label for="n67" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('show_pm_reminder_each')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[69]" value="{$configValues['new_pm_reminder']}" style="width:250px;" />&nbsp;<span class="small">{Language::getInstance()->getString('in_seconds')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('enable_clickjacking_protection')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y57" name="settings[57]" value="1"{if $configValues['clickjacking'] == 1} checked="checked"{/if} /><label for="y57" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n57" name="settings[57]" value="0"{if $configValues['clickjacking'] != 1} checked="checked"{/if} /><label for="n57" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr><td class="td1" colspan="2"><span class="small">{Language::getInstance()->getString('enable_clickjacking_protection_hint')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('spam_protection')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[76]" value="{$configValues['spam_delay']}" style="width:250px;" />&nbsp;<span class="small">{Language::getInstance()->getString('in_seconds')}</span></td>
 </tr>
 <tr><td class="td1" colspan="2"><span class="small">{Language::getInstance()->getString('spam_protection_hint')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('allow_temporary_editing_of_new_posts_for')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[77]" value="{$configValues['edit_time']}" style="width:250px;" />&nbsp;<span class="small">{Language::getInstance()->getString('in_seconds')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('save_ip_addresses_of_posts')}</span></td>
  <td class="td1" style="vertical-align:top;">{html_options name='settings[78]' values=array(1, 2, 0) output=array(Language::getInstance()->getString('always'), Language::getInstance()->getString('guests_only'), Language::getInstance()->getString('never')) selected=$configValues['save_ip_address']}</td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('limit_entries_in_page_navigation_bars_to_each')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[79]" value="{$configValues['shorten_page_bars']}" style="width:250px;" />&nbsp;<span class="small">{Language::getInstance()->getString('zero_disables_function')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('skipping_of_confirmation_messages')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y80" name="settings[80]" value="1"{if $configValues['skip_confirm_msg'] == 1} checked="checked"{/if} /><label for="y80" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n80" name="settings[80]" value="0"{if $configValues['skip_confirm_msg'] != 1} checked="checked"{/if} /><label for="n80" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('deletion_of_profiles')}</span></td>
  <td class="td1" style="vertical-align:top;">{html_options name='settings[82]' values=array(1, 2, 0) output=array(Language::getInstance()->getString('always'), Language::getInstance()->getString('zero_posts_only'), Language::getInstance()->getString('never')) selected=$configValues['delete_profiles']}</td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('enable_calendar')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y85" name="settings[85]" value="1"{if $configValues['activate_calendar'] == 1} checked="checked"{/if} /><label for="y85" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n85" name="settings[85]" value="0"{if $configValues['activate_calendar'] != 1} checked="checked"{/if} /><label for="n85" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('enable_plug_ins')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y86" name="settings[86]" value="1"{if $configValues['activate_plug_ins'] == 1} checked="checked"{/if} /><label for="y86" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n86" name="settings[86]" value="0"{if $configValues['activate_plug_ins'] != 1} checked="checked"{/if} /><label for="n86" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>

 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('disk_space_settings')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('warning_limit')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[10]" value="{$configValues['warn_admin_fds']}" style="width:250px;" />&nbsp;<span class="small">{Language::getInstance()->getString('warning_limit_description')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('closing_limit')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[11]" value="{$configValues['close_forum_fds']}" style="width:250px;" />&nbsp;<span class="small">{Language::getInstance()->getString('closing_limit_description')}</span></td>
 </tr>
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('registration_settings')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('enable_registration')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y12" name="settings[12]" value="1"{if $configValues['activate_registration'] == 1} checked="checked"{/if} /><label for="y12" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n12" name="settings[12]" value="0"{if $configValues['activate_registration'] != 1} checked="checked"{/if} /><label for="n12" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('maximal_number_of_registrations')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[13]" value="{$configValues['max_registrations']}" style="width:250px;" />&nbsp;<span class="small">{Language::getInstance()->getString('maximal_number_of_registrations_hint')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('create_random_password')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y14" name="settings[14]" value="1"{if $configValues['create_reg_pw'] == 1} checked="checked"{/if} /><label for="y14" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n14" name="settings[14]" value="0"{if $configValues['create_reg_pw'] != 1} checked="checked"{/if} /><label for="n14" class="norm">{Language::getInstance()->getString('negative')}</label>&nbsp;<span class="small">{Language::getInstance()->getString('mail_functions_must_be_enabled')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('send_activation_code')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y68" name="settings[68]" value="1"{if $configValues['confirm_reg_mail'] == 1} checked="checked"{/if} /><label for="y68" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n68" name="settings[68]" value="0"{if $configValues['confirm_reg_mail'] != 1} checked="checked"{/if} /><label for="n68" class="norm">{Language::getInstance()->getString('negative')}</label>&nbsp;<span class="small">{Language::getInstance()->getString('mail_functions_must_be_enabled')}</span></td>
 </tr>
 <tr><td class="td1" colspan="2"><span class="small">{Language::getInstance()->getString('send_activation_code_hint')}</span></td></tr>
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('who_is_was_online_settings')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('enable_who_is_was_online')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y19" name="settings[19]" value="1"{if $configValues['wio'] == 1} checked="checked"{/if} /><label for="y19" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n19" name="settings[19]" value="0"{if $configValues['wio'] != 1} checked="checked"{/if} /><label for="n19" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('number_of_minutes_to_stay_in_wio')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[18]" value="{$configValues['wio_timeout']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('color_for_admins')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[58]" value="{$configValues['wio_color_admin']}" style="color:{$configValues['wio_color_admin']}; width:250px;" onchange="this.style.color = this.value;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('color_for_super_mods')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[62]" value="{$configValues['wio_color_smod']}" style="color:{$configValues['wio_color_smod']}; width:250px;" onchange="this.style.color = this.value;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('color_for_moderators')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[59]" value="{$configValues['wio_color_mod']}" style="color:{$configValues['wio_color_mod']}; width:250px;" onchange="this.style.color = this.value;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('color_for_user')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[60]" value="{$configValues['wio_color_user']}" style="color:{$configValues['wio_color_user']}; width:250px;" onchange="this.style.color = this.value;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('color_for_banned')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[61]" value="{$configValues['wio_color_banned']}" style="color:{$configValues['wio_color_banned']}; width:250px;" onchange="this.style.color = this.value;" /></td>
 </tr>
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('status_settings')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('status_for_administrators')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[26]" value="{$configValues['var_admin']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('status_for_super_moderators')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[65]" value="{$configValues['var_smod']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('status_for_moderators')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[27]" value="{$configValues['var_mod']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('status_for_banned_users')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[28]" value="{$configValues['var_banned']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('status_for_deleted_users')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[29]" value="{$configValues['var_killed']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('number_of_stars_for_administrators')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[30]" value="{$configValues['stars_admin']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('number_of_stars_for_super_moderators')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[64]" value="{$configValues['stars_smod']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('number_of_stars_for_moderators')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[31]" value="{$configValues['stars_mod']}" style="width:250px;" /></td>
 </tr>
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('technical_settings')}</span></td></tr>
 <tr><td class="td1" colspan="2"><span class="small">{Language::getInstance()->getString('normally_no_need_to_change_this')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('internal_path_for_cookies')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[0]" value="{$configValues['path_to_forum']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('append_sid_in_any_case')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y42" name="settings[42]" value="1"{if $configValues['append_sid_url'] == 1} checked="checked"{/if} /><label for="y42" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n42" name="settings[42]" value="0"{if $configValues['append_sid_url'] != 1} checked="checked"{/if} /><label for="n42" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('enable_gzip_compression_if_available')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y43" name="settings[43]" value="1"{if $configValues['use_gzip_compression'] == 1} checked="checked"{/if} /><label for="y43" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n43" name="settings[43]" value="0"{if $configValues['use_gzip_compression'] != 1} checked="checked"{/if} /><label for="n43" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('enable_file_caching')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y44" name="settings[44]" value="1"{if $configValues['use_file_caching'] == 1} checked="checked"{/if} /><label for="y44" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n44" name="settings[44]" value="0"{if $configValues['use_file_caching'] != 1} checked="checked"{/if} /><label for="n44" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('enable_output_caching_in_any_case')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y45" name="settings[45]" value="1"{if $configValues['activate_ob'] == 1} checked="checked"{/if} /><label for="y45" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n45" name="settings[45]" value="0"{if $configValues['activate_ob'] != 1} checked="checked"{/if} /><label for="n45" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('use_command_getimagesize')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y46" name="settings[46]" value="1"{if $configValues['use_getimagesize'] == 1} checked="checked"{/if} /><label for="y46" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n46" name="settings[46]" value="0"{if $configValues['use_getimagesize'] != 1} checked="checked"{/if} /><label for="n46" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('use_command_disk_free_space')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y49" name="settings[49]" value="1"{if $configValues['use_diskfreespace'] == 1} checked="checked"{/if} /><label for="y49" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n49" name="settings[49]" value="0"{if $configValues['use_diskfreespace'] != 1} checked="checked"{/if} /><label for="n49" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('php_error_reporting_level')}</span></td>
  <td class="td1" style="vertical-align:top;">{html_options name='settings[75]' options=$errorLevels selected=$configValues['error_level']}</td>
 </tr>
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('mail_settings')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('enable_mail_functions')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y51" name="settings[51]" value="1"{if $configValues['activate_mail'] == 1} checked="checked"{/if} /><label for="y51" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n51" name="settings[51]" value="0"{if $configValues['activate_mail'] != 1} checked="checked"{/if} /><label for="n51" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('mail_address_of_administrator')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[52]" value="{$configValues['admin_email']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('mail_address_used_by_mails_from_forum')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[53]" value="{$configValues['forum_email']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('notify_admin_about_new_registrations')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y54" name="settings[54]" value="1"{if $configValues['mail_admin_new_registration'] == 1} checked="checked"{/if} /><label for="y54" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n54" name="settings[54]" value="0"{if $configValues['mail_admin_new_registration'] != 1} checked="checked"{/if} /><label for="n54" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('members_can_be_notified_about_new_replies')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y55" name="settings[55]" value="1"{if $configValues['notify_new_replies'] == 1} checked="checked"{/if} /><label for="y55" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n55" name="settings[55]" value="0"{if $configValues['notify_new_replies'] != 1} checked="checked"{/if} /><label for="n55" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('file_upload_settings')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('enable_file_uploads')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="radio" id="y72" name="settings[72]" value="1"{if $configValues['enable_uploads'] == 1} checked="checked"{/if} /><label for="y72" class="norm">{Language::getInstance()->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n72" name="settings[72]" value="0"{if $configValues['enable_uploads'] != 1} checked="checked"{/if} /><label for="n72" class="norm">{Language::getInstance()->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('allowed_file_extensions')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[73]" value="{$configValues['upload_allowed_ext']}" style="width:250px;" />&nbsp;<span class="small">{Language::getInstance()->getString('allowed_file_extensions_hint')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold;"><span class="norm">{Language::getInstance()->getString('maximal_filesize')}</span></td>
  <td class="td1" style="vertical-align:top;"><input type="text" name="settings[74]" value="{$configValues['upload_max_filesize']}" style="width:250px;" />&nbsp;<span class="small">{Language::getInstance()->getString('maximal_filesize_hint')}</span></td>
 </tr>
 <tr><td class="kat" colspan="2"><span class="kat">{Language::getInstance()->getString('logging_settings')}</span></td></tr>
 <tr>
  <td class="td1" colspan="2">
   <input type="checkbox" id="log1" name="settings[9][0]" value="{Logger::LOG_FILESYSTEM}"{if Logger::LOG_FILESYSTEM|in_array:$configValues['log_options']} checked="checked"{/if} /> <label for="log1" class="norm">{Language::getInstance()->getString('log_filesystem_problems')}</label><br />
   <input type="checkbox" id="log2" name="settings[9][1]" value="{Logger::LOG_ACP_ACCESS}"{if Logger::LOG_ACP_ACCESS|in_array:$configValues['log_options']} checked="checked"{/if} /> <label for="log2" class="norm">{Language::getInstance()->getString('log_failed_administration_access_attempts')}</label><br />
   <input type="checkbox" id="log3" name="settings[9][2]" value="{Logger::LOG_FAILED_LOGIN}"{if Logger::LOG_FAILED_LOGIN|in_array:$configValues['log_options']} checked="checked"{/if} /> <label for="log3" class="norm">{Language::getInstance()->getString('log_failed_logins')}</label><br />
   <input type="checkbox" id="log4" name="settings[9][3]" value="{Logger::LOG_NEW_POSTING}"{if Logger::LOG_NEW_POSTING|in_array:$configValues['log_options']} checked="checked"{/if} /> <label for="log4" class="norm">{Language::getInstance()->getString('log_new_posts')}</label><br />
   <input type="checkbox" id="log5" name="settings[9][4]" value="{Logger::LOG_EDIT_POSTING}"{if Logger::LOG_EDIT_POSTING|in_array:$configValues['log_options']} checked="checked"{/if} /> <label for="log5" class="norm">{Language::getInstance()->getString('log_posts_edited_deleted_etc')}</label><br />
   <input type="checkbox" id="log6" name="settings[9][5]" value="{Logger::LOG_USER_CONNECT}"{if Logger::LOG_USER_CONNECT|in_array:$configValues['log_options']} checked="checked"{/if} /> <label for="log6" class="norm">{Language::getInstance()->getString('log_users_connected_to_board')}</label><br />
   <input type="checkbox" id="log7" name="settings[9][6]" value="{Logger::LOG_LOGIN_LOGOUT}"{if Logger::LOG_LOGIN_LOGOUT|in_array:$configValues['log_options']} checked="checked"{/if} /> <label for="log7" class="norm">{Language::getInstance()->getString('log_login_and_logouts')}</label><br />
   <input type="checkbox" id="log8" name="settings[9][7]" value="{Logger::LOG_ACP_ACTION}"{if Logger::LOG_ACP_ACTION|in_array:$configValues['log_options']} checked="checked"{/if} /> <label for="log8" class="norm">{Language::getInstance()->getString('log_administration_actions')}</label><br />
   <input type="checkbox" id="log9" name="settings[9][8]" value="{Logger::LOG_USER_TRAFFIC}"{if Logger::LOG_USER_TRAFFIC|in_array:$configValues['log_options']} checked="checked"{/if} /> <label for="log9" class="norm">{Language::getInstance()->getString('log_pm_mail_traffic')}</label><br />
   <input type="checkbox" id="log10" name="settings[9][9]" value="{Logger::LOG_EDIT_PROFILE}"{if Logger::LOG_EDIT_PROFILE|in_array:$configValues['log_options']} checked="checked"{/if} /> <label for="log10" class="norm">{Language::getInstance()->getString('log_profiles_edited')}</label><br />
   <input type="checkbox" id="log11" name="settings[9][10]" value="{Logger::LOG_REGISTRATION}"{if Logger::LOG_REGISTRATION|in_array:$configValues['log_options']} checked="checked"{/if} /> <label for="log11" class="norm">{Language::getInstance()->getString('log_new_registrations')}</label><br />
   <input type="checkbox" id="log12" name="settings[9][11]" value="{Logger::LOG_NEW_PASSWORD}"{if Logger::LOG_NEW_PASSWORD|in_array:$configValues['log_options']} checked="checked"{/if} /> <label for="log12" class="norm">{Language::getInstance()->getString('log_new_passwords_sent')}</label><br />
  </td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('save_settings')}" /></p>
<input type="hidden" name="save" value="1" />
</form>