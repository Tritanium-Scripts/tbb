<form method="post" action="{$indexFile}?action=Login&amp;mode=ActivateAccount&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Account_activation')}</span></td></tr>
{if $error != ''}<tr><td colspan="2" class="CellError"><span class="FontError">{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd" width="15%"><span class="FontNorm">{$modules.Language->getString('User_name')}:</span></td>
 <td class="CellStd" width="85%"><input class="FormText" name="accountID" value="{$accountID}" size="25" /></td>
</tr>
<tr>
 <td class="CellStd" width="15%"><span class="FontNorm">{$modules.Language->getString('Activation_code')}:</span></td>
 <td class="CellStd" width="85%"><input class="FormText" name="activationCode" value="{$activationCode}" size="35" maxlength="32" /></td>
</tr>
<tr><td colspan="2" class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Activate_account')}" />&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}" /></td></tr>
</table>
</form>