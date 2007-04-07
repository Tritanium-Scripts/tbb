<form method="post" action="{$indexFile}?action=RequestPassword&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Request_new_password')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd" width="15%"><span class="FontNorm">{$modules.Language->getString('User_name')}:</span></td>
 <td class="CellAlt" width="85%"><input class="FormText" type="text" name="p[userName]" value="{$p.emailAddress}" size="20"/></td>
</tr>
<tr>
 <td class="CellStd" width="15%"><span class="FontNorm">{$modules.Language->getString('Email_address')}:</span></td>
 <td class="CellAlt" width="85%"><input class="FormText" type="text" name="p[emailAddress]" value="{$p.emailAddress}" size="30"/></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Request_new_password')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
</table>
</form>
