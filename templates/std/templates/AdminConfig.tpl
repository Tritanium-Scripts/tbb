<!-- AdminConfig -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_settings&amp;mode=editsettings{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{$modules.Language->getString('edit_settings')}</span></th></tr>
 <tr><td class="kat" colspan="2"><span class="kat">{$modules.Language->getString('maintenance_mode')}</span></th></tr>
 <tr><td class="td1" colspan="2"><span class="small">{$modules.Language->getString('maintenance_mode_description')}</span></td></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:35%;"><span class="norm">{$modules.Language->getString('maintenance_mode')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;"><input type="radio" id="15y" name="settings[15]" value="1"{if $configValues['uc'] == 1} checked="checked"{/if} /><label for="15y" class="norm">{$modules.Language->getString('enabled')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="15n" name="settings[15]" value="0"{if $configValues['uc'] != 1} checked="checked"{/if} /><label for="15n" class="norm">{$modules.Language->getString('disabled')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="vertical-align:top; width:35%;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('maintenance_mode_message')}</span><br /><span class="small">{$modules.Language->getString('xhtml_code_is_enabled')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;" onclick="alert('{$modules.Language->getLangCode()|string_format:$modules.Language->getString('maintenance_mode_message_hint')}');"><textarea name="settings[7]" cols="50" rows="10" disabled="disabled">{$configValues['uc_message']|escape}</textarea></td>
 </tr>
 <tr><td class="kat" colspan="2"><span class="kat">{$modules.Language->getString('general_settings')}</span></th></tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:35%;"><span class="norm">{$modules.Language->getString('address_to_forum')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;"><input type="text" name="settings[1]" value="{$configValues['address_to_forum']}" style="width:250px;" />&nbsp;<span class="small">{$modules.Language->getString('address_to_forum_example')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:35%;"><span class="norm">{$modules.Language->getString('site_name_of_forum')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;"><input type="text" name="settings[2]" value="{$configValues['site_name']|escape}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:35%;"><span class="norm">{$modules.Language->getString('site_address_of_forum')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;"><input type="text" name="settings[3]" value="{$configValues['site_address']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:35%;"><span class="norm">{$modules.Language->getString('contact_mail_address')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;"><input type="text" name="settings[4]" value="{$configValues['site_contact']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:35%;"><span class="norm">{$modules.Language->getString('name_of_forum')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;"><input type="text" name="settings[5]" value="{$configValues['forum_name']|escape}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:35%;"><span class="norm">{$modules.Language->getString('logo_of_forum')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;"><input type="text" name="settings[6]" value="{$configValues['forum_logo']}" style="width:250px;" />&nbsp;<span class="small">{$modules.Language->getString('address_or_path')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:35%;"><span class="norm">{$modules.Language->getString('timezone')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;"><select name="settings[8]">{foreach $timeZones as $curTimeZone}<option value="{$curTimeZone[0]}"{if $configValues['gmt_offset'] == $curTimeZone[0]} selected="selected"{/if}>{$curTimeZone[1]}</option>{/foreach}</select></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:35%;"><span class="norm">{$modules.Language->getString('only_allow_logged_in_users_access_to_forum')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;"><input type="radio" id="25y" name="settings[25]" value="1"{if $configValues['must_be_logged_in'] == 1} checked="checked"{/if} /><label for="25y" class="norm">{$modules.Language->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="25n" name="settings[25]" value="0"{if $configValues['must_be_logged_in'] != 1} checked="checked"{/if} /><label for="25n" class="norm">{$modules.Language->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:35%;"><span class="norm">{$modules.Language->getString('show_categories_in_forum_index')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;"><input type="radio" id="23y" name="settings[23]" value="1"{if $configValues['show_kats'] == 1} checked="checked"{/if} /><label for="23y" class="norm">{$modules.Language->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="23n" name="settings[23]" value="0"{if $configValues['show_kats'] != 1} checked="checked"{/if} /><label for="23n" class="norm">{$modules.Language->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:35%;"><span class="norm">{$modules.Language->getString('show_forum_stats_in_forum_index')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;"><input type="radio" id="21y" name="settings[21]" value="1"{if $configValues['show_board_stats'] == 1} checked="checked"{/if} /><label for="21y" class="norm">{$modules.Language->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="21n" name="settings[21]" value="0"{if $configValues['show_board_stats'] != 1} checked="checked"{/if} /><label for="21n" class="norm">{$modules.Language->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:35%;"><span class="norm">{$modules.Language->getString('amount_of_newest_posts_in_forum_index')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;"><input type="text" name="settings[22]" value="{$configValues['show_lposts']}" style="width:250px;" />&nbsp;<span class="small">{$modules.Language->getString('zero_disables_function')}</span></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:35%;"><span class="norm">{$modules.Language->getString('show_technical_stats_at_the_end_of_every_page')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;"><input type="radio" id="20y" name="settings[20]" value="1"{if $configValues['show_site_creation_time'] == 1} checked="checked"{/if} /><label for="20y" class="norm">{$modules.Language->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="20n" name="settings[20]" value="0"{if $configValues['show_site_creation_time'] != 1} checked="checked"{/if} /><label for="20n" class="norm">{$modules.Language->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:35%;"><span class="norm">{$modules.Language->getString('censor_topics_posts_and_signatures')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;"><input type="radio" id="24y" name="settings[24]" value="1"{if $configValues['censored'] == 1} checked="checked"{/if} /><label for="24y" class="norm">{$modules.Language->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="24n" name="settings[24]" value="0"{if $configValues['censored'] != 1} checked="checked"{/if} /><label for="24n" class="norm">{$modules.Language->getString('negative')}</label></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:35%;"><span class="norm">{$modules.Language->getString('number_of_topics_per_page')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;"><input type="text" name="settings[16]" value="{$configValues['topics_per_page']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:35%;"><span class="norm">{$modules.Language->getString('number_of_posts_per_page')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;"><input type="text" name="settings[17]" value="{$configValues['posts_per_page']}" style="width:250px;" /></td>
 </tr>
{*
 <tr>
  <td class="td1" style="font-weight:bold; width:35%;"><span class="norm">{$modules.Language->getString('')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;"><input type="text" name="settings[]" value="{$configValues['']}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="td1" style="font-weight:bold; width:35%;"><span class="norm">{$modules.Language->getString('')}</span></td>
  <td class="td1" style="vertical-align:top; width:65%;"><input type="radio" id="y" name="settings[]" value="1"{if $configValues[''] == 1} checked="checked"{/if} /><label for="y" class="norm">{$modules.Language->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="n" name="settings[]" value="0"{if $configValues[''] != 1} checked="checked"{/if} /><label for="n" class="norm">{$modules.Language->getString('negative')}</label></td>
 </tr>
*}
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('save_settings')}"></p>
<input type="hidden" name="save" value="1" />
</form>