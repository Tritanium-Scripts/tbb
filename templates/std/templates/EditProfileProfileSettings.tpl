<form method="post" action="{$smarty.const.INDEXFILE}?action=EditProfile&amp;mode=ProfileSettings&amp;doit=1&amp;{$smarty.const.MYSID}">
	<table class="TableStd" width="100%">
		<tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('settings')}</span></td></tr>
		<tr>
			<td class="CellStd">
				<fieldset>
					<legend><span class="FontSmall"><b>{$modules.Language->getString('general_settings')}</b></span></legend>
					<table width="100%">
						<colgroup>
							<col width="50%"/>
							<col width="50%"/>
						</colgroup>
						<tr>
							<td style="padding:2px;" valign="top"><span class="FontNorm">{$modules.Language->getString('show_email_address')}:</span><br/><span class="FontSmall">{$modules.Language->getString('show_email_address_info')}</span></td>
							<td style="padding:2px;" valign="top"><span class="FontNorm"><label><input class="FormRadio" type="radio" name="p[userHideEmailAddress]" value="0"{if $p.userHideEmailAddress == 0} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<label><input class="FormRadio" type="radio" name="p[userHideEmailAddress]" value="1"{if $p.userHideEmailAddress == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('negative')}</label></span></td>
						</tr>
						<tr>
							<td style="padding:2px;" valign="top"><span class="FontNorm">{$modules.Language->getString('receive_board_emails')}:</span><br/><span class="FontSmall">{$modules.Language->getString('receive_board_emails_info')}</span></td>
							<td style="padding:2px;" valign="top"><span class="FontNorm"><label><input class="FormRadio" type="radio" name="p[userReceiveEmails]" value="1"{if $p.userReceiveEmails == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<label><input class="FormRadio" type="radio" name="p[userReceiveEmails]" value="0"{if $p.userReceiveEmails == 0} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('negative')}</label></span></td>
						</tr>
						<tr>
							<td style="padding:2px;" valign="top"><span class="FontNorm">{$modules.Language->getString('notify_new_pms_by_email')}:</span></td>
							<td style="padding:2px;" valign="top"><span class="FontNorm"><label><input class="FormRadio" type="radio" name="p[userNotifyNewPM]" value="1"{if $p.userNotifyNewPM == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('positive')}</label>&nbsp;&nbsp;&nbsp;<label><input class="FormRadio" type="radio" name="p[userNotifyNewPM]" value="0"{if $p.userNotifyNewPM == 0} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('negative')}</label></span></td>
						</tr>
						<tr>
							<td style="padding:2px;" valign="top"><span class="FontNorm">{$modules.Language->getString('timezone')}:</span></td>
							<td style="padding:2px;" valign="top">
								<select class="form_select" name="p[userTimeZone]">
									{foreach from=$timeZones item=curTimeZone key=curTimeZoneKey}
										<option value="{$curTimeZoneKey}"{if $curTimeZoneKey == $p.userTimeZone} selected="selected"{/if}>{$curTimeZone}</option>
									{/foreach}
								</select>
							</td>
						</tr>
					</table>
				</fieldset>
			</td>
		</tr>
		<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('save_changes')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}"/></td></tr>
	</table>
</form>
