<form method="post" action="{$indexFile}?action=ViewProfile&amp;profileID={$profileID}&amp;mode=SendEmail&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<colgroup>
 <col width="15%"/>
 <col width="85%"/>
</colgroup>
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('send_email')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('recipient')}:</span></td>
 <td class="CellStd"><span class="FontNorm">{$profileData.userNick}</span></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('subject')}:</span></td>
 <td class="CellStd"><input class="FormText" type="text" size="80" name="p[emailSubject]" value="{$p.emailSubject}"/></td>
</tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('message')}:</span></td>
 <td class="CellStd"><textarea class="FormTextArea" name="p[emailMessage]" cols="100" rows="15">{$p.emailMessage}</textarea></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('send_email')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}"/></td></tr>
</table>
</form>
