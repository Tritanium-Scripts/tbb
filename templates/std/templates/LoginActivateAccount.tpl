<form method="post" action="{$indexFile}?action=Login&amp;mode=ActivateAccount&amp;doit=1&amp;{$mySID}">
	<table class="TableStd" width="100%">
		<colgroup>
			<col width="15%"/>
			<col width="85%"/>
		</colgroup>
		<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Account_activation')}</span></td></tr>
		{if isset($smarty.get.showMessage)}
			<tr><td class="CellInfoBox" colspan="2"><span class="FontInfoBox"><img src="{$modules.Template->getTemplateDir()}/images/icons/Info.png" class="ImageIcon" alt=""/>{$modules.Language->getString('info_inactive_account')}</span></td></tr>
		{/if}
		{if $error != ''}<tr><td colspan="2" class="CellError"><span class="FontError">{$error}</span></td></tr>{/if}
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('User_name')}:</span></td>
			<td class="CellAlt"><input class="FormText" name="accountID" value="{$accountID}" size="25" tabindex="1"/></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Activation_code')}:</span></td>
			<td class="CellAlt"><input class="FormText" name="activationCode" value="{$activationCode}" size="35" maxlength="32" tabindex="2"/>&nbsp;<span class="FontSmall">(<a href="{$indexFile}?action=Login&amp;mode=RequestActivationCode&amp;{$mySID}" tabindex="3">{$modules.Language->getString('Request_activation_code')}</a>)</span></td>
		</tr>
		<tr><td colspan="2" class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Activate_account')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
	</table>
</form>