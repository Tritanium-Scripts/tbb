<form method="post" action="{$indexFile}?action=Login&amp;mode=RequestActivationCode&amp;doit=1&amp;{$mySID}">
	<table class="TableStd" width="100%">
		<colgroup>
			<col width="15%"/>
			<col width="85%"/>
		</colgroup>
		<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Request_activation_code')}</span></td></tr>
		{include file=_ErrorRow.tpl colSpan=2}
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('User_name')}:</span></td>
			<td class="CellAlt"><input class="FormText" type="text" name="p[userName]" value="{$p.userName}" size="20"/></td>
		</tr>
		<tr>
			<td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Email_address')}:</span></td>
			<td class="CellAlt"><input class="FormText" type="text" name="p[emailAddress]" value="{$p.emailAddress}" size="30"/></td>
		</tr>
		<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Request_activation_code')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
	</table>
</form>
