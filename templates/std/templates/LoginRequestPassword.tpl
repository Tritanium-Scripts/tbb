<form method="post" action="{$smarty.const.INDEXFILE}?action=Login&amp;mode=RequestPassword&amp;doit=1&amp;{$smarty.const.MYSID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('request_new_password')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd" width="15%"><span class="FontNorm">{$modules.Language->getString('user_name')}:</span></td>
 <td class="CellAlt" width="85%"><input class="FormText" type="text" name="p[userName]" value="{$p.userName}" size="20"/></td>
</tr>
<tr>
 <td class="CellStd" width="15%"><span class="FontNorm">{$modules.Language->getString('email_address')}:</span></td>
 <td class="CellAlt" width="85%"><input class="FormText" type="text" name="p[emailAddress]" value="{$p.emailAddress}" size="30"/></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('request_new_password')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}"/></td></tr>
</table>
</form>
