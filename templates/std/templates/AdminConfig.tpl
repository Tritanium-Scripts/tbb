<script type="text/javascript">
	{literal}
	function check_enable_sig() {
		/*if(document.configform.elements[3].options[document.configform.elements[3].options.selectedIndex].value == 0) {
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
	{/literal}
</script>
<form method="post" action="{$indexFile}?action=AdminConfig&amp;doit=1&amp;{$mySID}">
	<table class="TableStd" width="100%">
		<colgroup>
			<col width="25%"/>
			<col width="75%"/>
		</colgroup>
		<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Boardconfig')}</span></td></tr>
		<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('General_settings')}</span></td></tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Board_name')}:</span></td>
			<td class="CellAlt"><input size="25" class="FormText" type="text" value="{$p.config.board_name}" name="p[config][board_name]"/></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Board_address')}:</span><br/><span class="FontSmall">{$modules.Language->getString('board_address_info')}</span></td>
			<td class="CellAlt"><input size="50" class="FormText" type="text" value="{$p.config.board_address}" name="p[config][board_address]"/></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Path_to_forum')}:</span><br/><span class="FontSmall">{$modules.Language->getString('path_to_forum_info')}</span></td>
			<td class="CellAlt"><input size="50" class="FormText" type="text" value="{$p.config.path_to_forum}" name="p[config][path_to_forum]"/></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Board_logo')}:</span></td>
			<td class="CellAlt"><input size="50" class="FormText" type="text" value="{$p.config.board_logo}" name="p[config][board_logo]"/></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Topics_per_page')}:</span></td>
			<td class="CellAlt"><input size="4" class="FormText" type="text" value="{$p.config.topics_per_page}" name="p[config][topics_per_page]"/></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Posts_per_page')}:</span></td>
			<td class="CellAlt"><input size="4" class="FormText" type="text" value="{$p.config.posts_per_page}" name="p[config][posts_per_page]"/></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Guests_enter_board')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][guests_enter_board]" value="1"{if $p.config.guests_enter_board == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][guests_enter_board]" value="0"{if $p.config.guests_enter_board == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Enable_search')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][search_status]" value="2"{if $p.config.search_status == 2} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][search_status]" value="0"{if $p.config.search_status == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][search_status]" value="1"{if $p.config.search_status == 1} checked="checked"{/if}/> {$modules.Language->getString('Members_only')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Search_results_timeout')}:</span><br/><span class="FontSmall">{$modules.Language->getString('search_results_timeout_info')}</span></td>
			<td class="CellAlt"><input size="5" class="FormText" type="text" value="{$p.config.sr_timeout}" name="p[config][sr_timeout]" maxlength="4"/> <span class="FontSmall">{$modules.Language->getString('in_minutes')}</span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Show_boardstats_forumindex')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][show_boardstats_forumindex]" value="1"{if $p.config.show_boardstats_forumindex == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][show_boardstats_forumindex]" value="0"{if $p.config.show_boardstats_forumindex == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Show_latest_posts_box_forumindex')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][show_latest_posts_forumindex]" value="1"{if $p.config.show_latest_posts_forumindex == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][show_latest_posts_forumindex]" value="0"{if $p.config.show_latest_posts_forumindex == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Maximum_latest_posts')}:</span></td>
			<td class="CellAlt"><input size="3" class="FormText" type="text" value="{$p.config.max_latest_posts}" name="p[config][max_latest_posts]" maxlength="2"/></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Rank_pic_administrators')}:</span></td>
			<td class="CellAlt"><input size="40" class="FormText" type="text" value="{$p.config.admin_rank_pic}" name="p[config][admin_rank_pic]" maxlength="255"/></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Rank_pic_moderators')}:</span></td>
			<td class="CellAlt"><input size="40" class="FormText" type="text" value="{$p.config.mod_rank_pic}" name="p[config][mod_rank_pic]" maxlength="255"/></td>
		</tr>
		{*<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Show_technical_statistics')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][show_techstats]" value="1"{if $p.config.show_techstats == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][show_techstats]" value="0"{if $p.config.show_techstats == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>*}
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Standard_timezone')}:</span></td>
			<td class="CellAlt">
				<select class="FormSelect" name="p[config][standard_tz]">
					{foreach from=$timeZones key=curTimeZoneKey item=curTimeZoneName}
						<option value="{$curTimeZoneKey}"{if $p.config.standard_tz == $curTimeZoneKey} selected="selected"{/if}>{$curTimeZoneName}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Profile_notes')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][allow_profile_notes]" value="1"{if $p.config.allow_profile_notes == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][allow_profile_notes]" value="0"{if $p.config.allow_profile_notes == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		{*<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Show_smilies_box')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][auth_global_smilies]" value="1"{if $p.config.auth_global_smilies == 1} checked="checked"{/if}/> {$modules.Language->getString('For_everybody')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][auth_global_smilies]" value="0"{if $p.config.auth_global_smilies == 0} checked="checked"{/if}/> {$modules.Language->getString('For_admins_mods_only')}</label></span></td>
		</tr>*}
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Allow_ghost_mode')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][allow_ghost_mode]" value="1"{if $p.config.allow_ghost_mode == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][allow_ghost_mode]" value="0"{if $p.config.allow_ghost_mode == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Hot_status_posts_last_hour')}:</span><br/><span class="FontSmall">{$modules.Language->getString('hot_status_posts_last_hour_description')}</span></td>
			<td class="CellAlt"><input size="5" class="FormText" type="text" value="{$p.config.hot_status_posts_last_hour}" name="p[config][hot_status_posts_last_hour]" maxlength="255"/></td>
		</tr>
		<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Email_settings')}</span></td></tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Enable_email_functions')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][enable_email_functions]" value="1"{if $p.config.enable_email_functions == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][enable_email_functions]" value="0"{if $p.config.enable_email_functions == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Board_email_address')}:</span><br/><span class="FontSmall">{$modules.Language->getString('board_email_address_info')}</span></td>
			<td class="CellAlt"><input size="40" class="FormText" type="text" value="{$p.config.board_email_address}" name="p[config][board_email_address]" maxlength="255"/></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Board_email_signature')}:</span><br/><span class="FontSmall">{$modules.Language->getString('board_email_signature_info')}</span></td>
			<td class="CellAlt"><textarea class="FormTextArea" name="p[config][email_signature]" cols="40" rows="4">{$p.config.email_signature}</textarea></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Enable_topic_subscriptions')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][enable_topic_subscription]" value="1"{if $p.config.enable_topic_subscription == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][enable_topic_subscription]" value="0"{if $p.config.enable_topic_subscription == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Enable_email_formular')}:</span><br/><span class="FontSmall">{$modules.Language->getString('email_formular_info')}</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][enable_email_formular]" value="1"{if $p.config.enable_email_formular == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][enable_email_formular]" value="0"{if $p.config.enable_email_formular == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('News_settings')}</span></td></tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('News_forum')}:</span></td>
			<td class="CellAlt">
				<select class="FormSelect" name="p[config][news_forum]">
					<option value="0"{if $p.config.news_forum == 0} selected="selected"{/if}>{$modules.Language->getString('No_news_forum')}</option>
					{foreach from=$forumsData item=curForum}
						<option value="{$curForum.forumID}"{if $curForum.forumID == $p.config.news_forum} selected="selected"{/if}>{$curForum.forumName}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Display_news_forumindex')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][show_news_forumindex]" value="1"{if $p.config.show_news_forumindex == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][show_news_forumindex]" value="0"{if $p.config.show_news_forumindex == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		{*<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Enable_news_module')}:</span><br/><span class="FontSmall">{$modules.Language->getString('news_module_info')}</span></td>
			<td class="CellAlt"><select class="FormSelect" name="p[config][enable_news_module]"><option value="1"{$checked.enable_news_module[1]}>{$modules.Language->getString('Yes')}</option><option value="0"{$checked.enable_news_module[0]}>{$modules.Language->getString('No')}</option></select></td>
		</tr>*}
		<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Registration_settings')}</span></td></tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Enable_registration')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][enable_registration]" value="1"{if $p.config.enable_registration == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][enable_registration]" value="0"{if $p.config.enable_registration == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('User_must_accept_board_rules')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][require_accept_boardrules]" value="1"{if $p.config.require_accept_boardrules == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][require_accept_boardrules]" value="0"{if $p.config.require_accept_boardrules == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Allow_email_addresses_only_once')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][check_unique_email_addresses]" value="1"{if $p.config.check_unique_email_addresses == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][check_unique_email_addresses]" value="0"{if $p.config.check_unique_email_addresses == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Verify_email_address')}:</span></td>
			<td class="CellAlt"><select class="FormSelect" name="p[config][verify_email_address]"><option value="0"{if $p.config.verify_email_address == 0} selected="selected"{/if}>{$modules.Language->getString('No')}</option><option value="1"{if $p.config.verify_email_address == 1} selected="selected"{/if}>{$modules.Language->getString('Create_random_password')}</option><option value="2"{if $p.config.verify_email_address == 2} selected="selected"{/if}>{$modules.Language->getString('Send_activation_code')}</option></select></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Maximum_registrations')}:</span></td>
			<td class="CellAlt"><input size="8" class="FormText" type="text" value="{$p.config.maximum_registrations}" name="p[config][maximum_registrations]"/> <span class="FontSmall">({$modules.Language->getString('minus_1_infinite')})</span></td>
		</tr>
		<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Signature_settings')}</span></td></tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Enable_signature')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][enable_sig]" value="1"{if $p.config.enable_sig == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][enable_sig]" value="0"{if $p.config.enable_sig == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Maximum_signature_length')}:</span></td>
			<td class="CellAlt"><input size="6" class="FormText" type="text" value="{$p.config.maximum_sig_length}" name="p[config][maximum_sig_length]"/></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Allow_signature_bbcode')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][allow_sig_bbcode]" value="1"{if $p.config.allow_sig_bbcode == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][allow_sig_bbcode]" value="0"{if $p.config.allow_sig_bbcode == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Allow_signature_html')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][allow_sig_html]" value="1"{if $p.config.allow_sig_html == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][allow_sig_html]" value="0"{if $p.config.allow_sig_html == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Allow_signature_smilies')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][allow_sig_smilies]" value="1"{if $p.config.allow_sig_smilies == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][allow_sig_smilies]" value="0"{if $p.config.allow_sig_smilies == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Avatar_settings')}</span></td></tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Enable_avatars')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][enable_avatars]" value="1"{if $p.config.enable_avatars == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][enable_avatars]" value="0"{if $p.config.enable_avatars == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Avatar_image_height')}:</span></td>
			<td class="CellAlt"><input size="6" class="FormText" type="text" value="{$p.config.avatar_image_height}" name="p[config][avatar_image_height]"/></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Avatar_image_width')}:</span></td>
			<td class="CellAlt"><input size="6" class="FormText" type="text" value="{$p.config.avatar_image_width}" name="p[config][avatar_image_width]"/></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Enable_avatar_upload')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][enable_avatar_upload]" value="1"{if $p.config.enable_avatar_upload == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][enable_avatar_upload]" value="0"{if $p.config.enable_avatar_upload == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Maximum_avatar_file_size')}:</span></td>
			<td class="CellAlt"><input size="4" class="FormText" type="text" value="{$p.config.max_avatar_file_size}" name="p[config][max_avatar_file_size]" maxlength="4"/> <span class="FontSmall">{$modules.Language->getString('in_kilobytes')}</span></td>
		</tr>
		<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Language_settings')}</span></td></tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Standard_language')}:</span></td>
			<td class="CellAlt">
				<select class="FormSelect" name="p[config][standard_language]">
					{foreach from=$languages item=curLanguage}
						<option value="{$curLanguage.dir}"{if $p.config.standard_language == $curLanguage.dir} selected="selected"{/if}>{$curLanguage.name} ({$curLanguage.nativeName})</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Enable_language_detection')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][use_language_detection]" value="1"{if $p.config.use_language_detection == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][use_language_detection]" value="0"{if $p.config.use_language_detection == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Allow_members_select_language')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][allow_select_lng]" value="1"{if $p.config.allow_select_lng == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][allow_select_lng]" value="0"{if $p.config.allow_select_lng == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Allow_guests_select_language')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][allow_select_lng_guests]" value="1"{if $p.config.allow_select_lng_guests == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][allow_select_lng_guests]" value="0"{if $p.config.allow_select_lng_guests == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Who_is_online_settings')}</span></td></tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Enable_who_is_online')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][enable_wio]" value="1"{if $p.config.enable_wio == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][enable_wio]" value="0"{if $p.config.enable_wio == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Who_is_online_timeout')}:</span></td>
			<td class="CellAlt"><input size="6" class="FormText" type="text" value="{$p.config.wio_timeout}" name="p[config][wio_timeout]"/> <span class="FontSmall">{$modules.Language->getString('in_minutes')}</span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Show_who_is_online_box_forumindex')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][show_wio_forumindex]" value="1"{if $p.config.show_wio_forumindex == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][show_wio_forumindex]" value="0"{if $p.config.show_wio_forumindex == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Private_messages_settings')}</span></td></tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Enable_private_messages')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][enable_pms]" value="1"{if $p.config.enable_pms == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][enable_pms]" value="0"{if $p.config.enable_pms == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Maximum_additional_folders')}:</span></td>
			<td class="CellAlt"><input class="FormText" type="text" name="p[config][maximum_pms_folders]" value="{$p.config.maximum_pms_folders}"/> <span class="FontSmall">({$modules.Language->getString('minus_1_infinite')})</span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Maximum_private_messages')}:</span></td>
			<td class="CellAlt"><input class="FormText" type="text" name="p[config][maximum_pms]" value="{$p.config.maximum_pms}"/> <span class="FontSmall">({$modules.Language->getString('minus_1_infinite')})</span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Allow_pms_signature')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][allow_pms_signature]" value="1"{if $p.config.allow_pms_signature == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][allow_pms_signature]" value="0"{if $p.config.allow_pms_signature == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Allow_pms_smilies')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][allow_pms_smilies]" value="1"{if $p.config.allow_pms_smilies == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][allow_pms_smilies]" value="0"{if $p.config.allow_pms_smilies == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Allow_pms_bbcode')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][allow_pms_bbcode]" value="1"{if $p.config.allow_pms_bbcode == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][allow_pms_bbcode]" value="0"{if $p.config.allow_pms_bbcode == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Allow_pms_htmlcode')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][allow_pms_htmlcode]" value="1"{if $p.config.allow_pms_htmlcode == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][allow_pms_htmlcode]" value="0"{if $p.config.allow_pms_htmlcode == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Enable_outbox')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][enable_outbox]" value="1"{if $p.config.enable_outbox == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][enable_outbox]" value="0"{if $p.config.enable_outbox == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Enable_pms_read_confirmation')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][allow_pms_rconfirmation]" value="1"{if $p.config.allow_pms_rconfirmation == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][allow_pms_rconfirmation]" value="0"{if $p.config.allow_pms_rconfirmation == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Attachments_settings')}</span></td></tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Enable_attachments')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][enable_attachments]" value="1"{if $p.config.enable_attachments == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][enable_attachments]" value="0"{if $p.config.enable_attachments == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Allowed_attachment_types')}:</span></td>
			<td class="CellAlt"><input class="FormText" type="text" name="p[config][allowed_attachment_types]" value="{$p.config.allowed_attachment_types}" size="50"/> <span class="FontSmall">({$modules.Language->getString('Seperate_file_extentions_with_spaces')})</span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Forbidden_attachment_types')}:</span></td>
			<td class="CellAlt"><input class="FormText" type="text" name="p[config][forbidden_attachment_types]" value="{$p.config.forbidden_attachment_types}" size="50"/> <span class="FontSmall">({$modules.Language->getString('Seperate_file_extentions_with_spaces')})</span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Maximum_attachments_per_post')}:</span></td>
			<td class="CellAlt"><input class="FormText" type="text" name="p[config][max_attachments_per_post]" value="{$p.config.max_attachments_per_post}" size="4"/> <span class="FontSmall">({$modules.Language->getString('minus_1_infinite')})</span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Maximum_attachment_size')}:</span></td>
			<td class="CellAlt"><input class="FormText" type="text" name="p[config][max_attachment_size]" value="{$p.config.max_attachment_size}" size="15"/> <span class="FontSmall">({$modules.Language->getString('minus_1_infinite')}; {$modules.Language->getString('in_bytes')})</span></td>
		</tr>
		<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Technical_settings')}</span></td></tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Enable_gzip_compression')}:</span></td>
			<td class="CellAlt"><span class="FontNorm"><label><input type="radio" name="p[config][enable_gzip]" value="1"{if $p.config.enable_gzip == 1} checked="checked"{/if}/> {$modules.Language->getString('Yes')}</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="p[config][enable_gzip]" value="0"{if $p.config.enable_gzip == 0} checked="checked"{/if}/> {$modules.Language->getString('No')}</label></span></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Search_garbage_collection_probability')}:</span></td>
			<td class="CellAlt"><input size="3" class="FormText" type="text" name="p[config][srgc_probability]" value="{$p.config.srgc_probability}" maxlength="3"/> <span class="FontSmall">{$modules.Language->getString('in_percent')}</span></td>
		</tr>
		<tr><td colspan="2" class="CellButtons" align="center"><input type="submit" class="FormBButton" value="{$modules.Language->getString('Update_config')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
	</table>
</form>
