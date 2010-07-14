<?

/* ad_settings.php - Verwaltet die Einstellungen (c) 2001-2002 Tritanium Scripts */

require_once("functions.php");
require_once("loadset.php");
require_once("auth.php");
ad();

if($user_logged_in != 1 || $user_data[status] != 1) {
	mylog("2","%1: Administrationszugriffversuch (IP: %2)");
	header("Location: ad_login.php?$HSID"); exit;
}
else {
	switch($mode) {
		default:
			if(isset($save)) {
				$settings[0] = '/';
				$settings[2] = mutate($settings[2]);
				$settings[5] = mutate($settings[5]);
				$settings[7] = nlbr(mysslashes($settings[7]));
				$settings[26] = mutate($settings[26]);
				$settings[27] = mutate($settings[27]);
				$settings[28] = mutate($settings[28]);
				$settings[29] = mutate($settings[29]);
				$settings[50] = $config['lng_folder'];
				if(!isset($settings[9])) $settings[9] = '';
				else $settings[9] = implode(',',$settings[9]);
				ksort($settings);
				$settings = implode("\n",array_pad($settings,200,''))."\n";
				myfwrite("vars/settings.var",$settings,'w');

				include("pageheader.php");
				echo adnavbar("<a class=\"navbar\" href=\"ad_settings.php$MYSID1\">".$lng['ad_settings']['Edit_Settings']."</a>\t".$lng['templates']['settings_saved'][0]);
				echo get_message('settings_saved');
			}
			else {
				$logging = explode(',',$config['log_options']);
				include('pageheader.php');
				echo adnavbar($lng['ad_settings']['Edit_Settings']);
				?>
					<form method="post" action="ad_settings.php?mode=editsettings<?=$MYSID2?>"><input type="hidden" name="save" value="1">
					<table class="tbl" width="<?=$twidth?>" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
					<tr><th class="thnorm" colspan="2"><span class="thnorm"><?=$lng['ad_settings']['Edit_Settings']?></span></th></tr>
					<tr><td class="kat" colspan="2"><span class="kat"><?=$lng['ad_settings']['Under_construction_mode']?></span></th></tr>
					<tr><td class="td1" colspan="2"><span class="small"><?=$lng['ad_settings']['under_construction_mode_description']?></span></td></tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Under_construction_mode']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[15]"><option value="1"<? if($config['uc'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Enabled']?></option><option value="0"<? if($config['uc'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['Disabled']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%" valign="top"><span class="norm"><b><?=$lng['ad_settings']['Message_if_uc_mode_is_activated']?></b></span><br><span class="small">HTML-Code ist aktiviert</span></td>
					 <td class="td1" width="65%" valign="top"><textarea name="settings[7]" cols="50" rows="10"><?=brnl($config['uc_message'])?></textarea></td>
					</tr>
					<tr><td class="kat" colspan="2"><span class="kat"><?=$lng['ad_settings']['General_settings']?></span></th></tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Address_to_forum']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input type="text" name="settings[1]" value="<?=$config['address_to_forum']?>">&nbsp;<span class="small">(e.g. http://www.mydomain.com/forum)</span></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Pagename_where_forum_is_used']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input type="text" name="settings[2]" value="<?=$config['site_name']?>"></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Pageaddress_where_forum_is_used']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input type="text" name="settings[3]" value="<?=$config['site_address']?>"></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Contact_Emailaddress']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input type="text" name="settings[4]" value="<?=$config['site_contact']?>"></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Name_of_the_forum']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input type="text" name="settings[5]" value="<?=$config['forum_name']?>"></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Forumlogo']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input type="text" name="settings[6]" value="<?=$config['forum_logo']?>">&nbsp;<span class="small">(Adresse oder Pfad)</span></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Timezone']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[8]">
				<?
				for($i = 0; $i < sizeof($lng['tz']); $i++) {
					if($config['gmt_offset'] == $lng['tz'][$i][1]) $x = " selected";
					else $x = '';
					echo "<option value=\"".$lng['tz'][$i][1]."\"$x>".$lng['tz'][$i][0]."</option>";
				}
				?>
					 </select>
					 </td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Only_logged_in_users_are_allowed_to_access_forum']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[25]"><option value="1"<? if($config['must_be_logged_in'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['must_be_logged_in'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Show_categories_in_forum_index']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[23]"><option value="1"<? if($config['show_kats'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['show_kats'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Show_forum_statistic_in_forum_index']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[21]"><option value="1"<? if($config['show_board_stats'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['show_board_stats'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Show_last_posts_in_forum_index']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[22]"><option value="1"<? if($config['show_lposts'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['show_lposts'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Show_technical_stuff_at_the_end_of_every_page']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[20]"><option value="1"<? if($config['show_site_creation_time'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['show_site_creation_time'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Censor_topics_posts_and_signatures']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[24]"><option value="1"<? if($config['censored'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['censored'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Number_of_topics_shown_per_page']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input size="10" type="text" name="settings[16]" value="<?=$config['topics_per_page']?>"></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Number_of_posts_shown_per_page']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input size="10" type="text" name="settings[17]" value="<?=$config['posts_per_page']?>"></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Newsposition']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[32]"><option value="1"<? if($config['news_position'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Position_1']?></option><option value="2"<? if($config['news_position'] == 2) echo " selected"; ?>><?=$lng['ad_settings']['Position_2']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['With_how_many_replies_is_a_topic_marked_as_hot']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input size="10" type="text" name="settings[33]" value="<?=$config['topic_is_hot']?>"></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Users_must_be_logged_in_to_send_formmails']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[34]"><option value="1"<? if($config['formmail_mbli'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['formmail_mbli'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Enable_memberlist']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[35]"><option value="1"<? if($config['activate_mlist'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['activate_mlist'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Guests_must_enter_a_name_to_post']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[36]"><option value="1"<? if($config['nli_must_enter_name'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['nli_must_enter_name'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Show_forums_you_dont_have_access_to']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[37]"><option value="1"<? if($config['show_private_forums'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['show_private_forums'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['CSS_file']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input type="text" name="settings[38]" value="<?=$config['css_file']?>">&nbsp;<span class="small">(Adresse oder Pfad)</span></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Tablewidth']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input size="10" type="text" name="settings[39]" value="<?=$twidth_old?>"></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Cellspacing']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input size="10" type="text" name="settings[40]" value="<?=$tspacing?>"></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Cellpadding']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input size="10" type="text" name="settings[41]" value="<?=$tpadding?>"></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Maximum_avatar_height']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input size="10" type="text" name="settings[47]" value="<?=$config['avatar_height']?>"></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Maximum_avatar_width']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input size="10" type="text" name="settings[48]" value="<?=$config['avatar_width']?>"></td>
					</tr>
					<tr><td class="kat" colspan="2"><span class="kat"><?=$lng['ad_settings']['Diskspace_settings']?></span></th></tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Warning_limit']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input size="10" type="text" name="settings[10]" value="<?=$config['warn_admin_fds']?>">&nbsp;<span class="small">(<?=$lng['ad_settings']['warning_limit_description']?>)</span></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Close_limit']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input size="10" type="text" name="settings[11]" value="<?=$config['close_forum_fds']?>">&nbsp;<span class="small">(<?=$lng['ad_settings']['close_limit_description']?>)</span></td>
					</tr>

					<tr><td class="kat" colspan="2"><span class="kat"><?=$lng['ad_settings']['Registration_setttings']?></span></th></tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Enable_registration']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[12]"><option value="1"<? if($config['activate_registration'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['activate_registration'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Maximum_number_of_registrations']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input size="10" type="text" name="settings[13]" value="<?=$config['max_registrations']?>">&nbsp;<span class="small">(<?=$lng['ad_settings']['-1_infinite']?>)</span></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Create_random_password']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[14]"><option value="1"<? if($config['create_reg_pw'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['create_reg_pw'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select>&nbsp;<span class="small">(<?=$lng['ad_settings']['Mailfunctions_must_be_enabled']?>)</span></td>
					</tr>

					<tr><td class="kat" colspan="2"><span class="kat"><?=$lng['ad_settings']['WhoIsOnline_settings']?></span></th></tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Enable_WhoIsOnline']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[19]"><option value="1"<? if($config['wio'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['wio'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['number_of_minutes_marked_as_online']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input size="10" type="text" name="settings[18]" value="<?=$config['wio_timeout']?>"></td>
					</tr>

					<tr><td class="kat" colspan="2"><span class="kat"><?=$lng['ad_settings']['Status_settings']?></span></th></tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Status_for_administrators']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input type="text" name="settings[26]" value="<?=$config['var_admin']?>"></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Status_for_moderators']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input type="text" name="settings[27]" value="<?=$config['var_mod']?>"></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Status_for_banned_users']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input type="text" name="settings[28]" value="<?=$config['var_banned']?>"></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Status_for_deleted_users']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input type="text" name="settings[29]" value="<?=$config['var_killed']?>"></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['number_of_stars_for_administrators']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input type="text" size="10" name="settings[30]" value="<?=$config['stars_admin']?>"></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['number_of_stars_for_moderators']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input type="text" size="10" name="settings[31]" value="<?=$config['stars_mod']?>"></td>
					</tr>
					<tr><td class="kat" colspan="2"><span class="kat"><?=$lng['ad_settings']['Technical_settings']?></span></th></tr>
					<tr><td class="td1" colspan="2"><span class="small"><?=$lng['ad_settings']['Normally_not_change']?></span></td></tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Append_SID_in_any_case']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[42]"><option value="1"<? if($config['append_sid_url'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['appen_sid_url'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Enable_gzip_compression_if_available']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[43]"><option value="1"<? if($config['use_gzip_compression'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['use_gzip_compression'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Enable_file_caching']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[44]"><option value="1"<? if($config['use_file_caching'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['use_file_caching'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Enable_output_caching_in_any_case']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[45]"><option value="1"<? if($config['activate_ob'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['activate_ob'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Use_getimagesize']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[46]"><option value="1"<? if($config['use_getimagesize'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['use_getimagesize'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Use_diskfreespace']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[49]"><option value="1"<? if($config['use_diskfreespace'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['use_diskfreespace'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr><td class="kat" colspan="2"><span class="kat"><?=$lng['ad_settings']['Email_settings']?></span></th></tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Enable_email_functions']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[51]"><option value="1"<? if($config['activate_mail'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['activate_mail'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Emailaddress_of_administrator']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input type="text" name="settings[52]" value="<?=$config['admin_email']?>"></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Emailaddress_used_by_mails_from_forum']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><input type="text" name="settings[53]" value="<?=$config['forum_email']?>"></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Notify_admin_about_new_registrations']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[54]"><option value="1"<? if($config['mail_admin_new_registration'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['mail_admin_new_registration'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr>
					 <td class="td1" width="35%"><span class="norm"><b><?=$lng['ad_settings']['Members_can_be_notified_about_new_replies']?></b></span></td>
					 <td class="td1" width="65%" valign="top"><select name="settings[55]"><option value="1"<? if($config['notify_new_replies'] == 1) echo " selected"; ?>><?=$lng['ad_settings']['Yes']?></option><option value="0"<? if($config['notify_new_replies'] != 1) echo " selected"; ?>><?=$lng['ad_settings']['No']?></option></select></td>
					</tr>
					<tr><td class="kat" colspan="2"><span class="kat"><?=$lng['ad_settings']['Logging_settings']?></span></th></tr>
					<tr><td class="td1" colspan="2"><span class="norm"><input type="checkbox" name="settings[9][0]" value="1" onfocus="this.blur()"<? if(in_array(1,$logging)) echo " checked" ?>>&nbsp;<?=$lng['ad_settings']['Log_filesystem_problems']?><br><input type="checkbox" name="settings[9][1]" value="2" onfocus="this.blur()"<? if(in_array(2,$logging)) echo " checked" ?>>&nbsp;<?=$lng['ad_settings']['Log_failed_administration_access_attempts']?><br><input type="checkbox" name="settings[9][2]" value="3" onfocus="this.blur()"<? if(in_array(3,$logging)) echo " checked" ?>>&nbsp;<?=$lng['ad_settings']['Log_failed_logins']?><br><input type="checkbox" name="settings[9][3]" value="4" onfocus="this.blur()"<? if(in_array(4,$logging)) echo " checked" ?>>&nbsp;<?=$lng['ad_settings']['Log_new_posts']?><br><input type="checkbox" name="settings[9][4]" value="5" onfocus="this.blur()"<? if(in_array(5,$logging)) echo " checked" ?>>&nbsp;<?=$lng['ad_settings']['Log_posts_edited_deleted_etc']?><br><input type="checkbox" name="settings[9][5]" value="6" onfocus="this.blur()"<? if(in_array(6,$logging)) echo " checked" ?>>&nbsp;<?=$lng['ad_settings']['Log_users_connected_to_board']?><br><input type="checkbox" name="settings[9][6]" value="7" onfocus="this.blur()"<? if(in_array(7,$logging)) echo " checked" ?>>&nbsp;<?=$lng['ad_settings']['Log_login_and_logouts']?><br><input type="checkbox" name="settings[9][7]" value="8" onfocus="this.blur()"<? if(in_array(8,$logging)) echo " checked" ?>>&nbsp;<?=$lng['ad_settings']['Log_administration_actions']?><br><input type="checkbox" name="settings[9][8]" value="9" onfocus="this.blur()"<? if(in_array(9,$logging)) echo " checked" ?>>&nbsp;<?=$lng['ad_settings']['Log_pm_mail_traffic']?><br><input type="checkbox" name="settings[9][9]" value="10" onfocus="this.blur()"<? if(in_array(10,$logging)) echo " checked" ?>>&nbsp;<?=$lng['ad_settings']['Log_profiles_edited']?><br><input type="checkbox" name="settings[9][10]" value="11" onfocus="this.blur()"<? if(in_array(11,$logging)) echo " checked" ?>>&nbsp;<?=$lng['ad_settings']['Log_new_registrations']?><br><input type="checkbox" name="settings[9][11]" value="12" onfocus="this.blur()"<? if(in_array(12,$logging)) echo " checked" ?>>&nbsp;<?=$lng['ad_settings']['Log_new_passwords_sent']?><br></span></td></tr>
					</table><br><input type="submit" value="<?=$lng['ad_settings']['Save_Settings']?>"></form>
				<?
			}
		break;

		case 'readsetfile':
			if(isset($confirm)) {
				include("settings.php");
				$towrite = array();
				$towrite[0] = '/';
				$towrite[1] = $config['address_to_forum'];
				$towrite[2] = $config['site_name'];
				$towrite[3] = $config['site_address'];
				$towrite[4] = $config['site_contact'];
				$towrite[5] = $config['forum_name'];
				$towrite[6] = $config['forum_logo'];
				$towrite[7] = $config['uc_message'];
				$towrite[8] = $config['gmt_offset'];
				$towrite[9] = $config['log_options'];
				$towrite[10] = $config['warn_admin_fds'];
				$towrite[11] = $config['close_forum_fds'];
				$towrite[12] = $config['activate_registration'];
				$towrite[13] = $config['max_registrations'];
				$towrite[14] = $config['create_reg_pw'];
				$towrite[15] = $config['uc'];
				$towrite[16] = $config['topics_per_page'];
				$towrite[17] = $config['posts_per_page'];
				$towrite[18] = $config['wio_timeout'];
				$towrite[19] = $config['wio'];
				$towrite[20] = $config['show_site_creation_time'];
				$towrite[21] = $config['show_board_stats'];
				$towrite[22] = $config['show_lposts'];
				$towrite[23] = $config['show_kats'];
				$towrite[24] = $config['censored'];
				$towrite[25] = $config['must_be_logged_in'];
				$towrite[26] = $config['var_admin'];
				$towrite[27] = $config['var_mod'];
				$towrite[28] = $config['var_banned'];
				$towrite[29] = $config['var_killed'];
				$towrite[30] = $config['stars_admin'];
				$towrite[31] = $config['stars_mod'];
				$towrite[32] = $config['news_position'];
				$towrite[33] = $config['topic_is_hot'];
				$towrite[34] = $config['formmail_mbli'];
				$towrite[35] = $config['activate_mlist'];
				$towrite[36] = $config['nli_must_enter_name'];
				$towrite[37] = $config['show_private_forums'];
				$towrite[38] = $config['css_file'];
				$towrite[39] = $twidth;
				$towrite[40] = $tspacing;
				$towrite[41] = $tpadding;
				$towrite[42] = $config['append_sid_url'];
				$towrite[43] = $config['use_gzip_compression'];
				$towrite[44] = $config['use_file_caching'];
				$towrite[45] = $config['activate_ob'];
				$towrite[46] = $config['use_getimagesize'];
				$towrite[47] = $config['avatar_height'];
				$towrite[48] = $config['avatar_width'];
				$towrite[49] = $config['use_diskfreespace'];
				$towrite[50] = $config['lng_folder'];
				$towrite[51] = $config['activate_mail'];
				$towrite[52] = $config['admin_email'];
				$towrite[53] = $config['forum_email'];
				$towrite[54] = $config['mail_admin_new_registration'];
				$towrite[55] = $config['notify_new_replies'];
				$towrite = implode("\n",array_pad($towrite,200,''))."\n";
				myfwrite("vars/settings.var",$towrite,'w');
				include("pageheader.php");
				echo adnavbar("<a class=\"navbar\" href=\"ad_settings.php?mode=readsetfile$MYSID2\">settings.php einlesen</a>\t".$lng['templates']['settings_read_in'][0]);
				echo get_message('settings_read_in');
			}
			else {
				include("pageheader.php");
				echo adnavbar($lng['ad_settings']['Read_settings_in']);
				?>
					<form method="post" action="ad_settings.php?mode=readsetfile<?=$MYSID2?>"><input type="hidden" name="confirm" value="1">
					<table class="tbl" width="<?=$twidth?>" border="0" cellspacing="<?=$tspacing?>" cellpadding="<?=$tpadding?>">
					<tr><th class="thnorm"><span class="thnorm"><?=$lng['ad_settings']['Read_settings_in']?></span></th></tr>
					<tr><td class="td1"><span class="norm"><center><br><?=$lng['ad_settings']['Really_read_in']?><br><br></center></span></td></tr>
					</table><br><input type="submit" value="<?=$lng['ad_settings']['Read_settings_in']?>"></form>
				<?
			}
		break;
	}
}

include("pagetail.php");
// I
?>