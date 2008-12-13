<form method="post" action="{$smarty.const.INDEXFILE}?action=AdminUsers&amp;mode=AddUser&amp;doit=1&amp;{$smarty.const.MYSID}">
<table class="TableStd" width="100%">
<colgroup>
 <col width="20%"/>
 <col width="80%"/>
</colgroup>
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('add_user')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('user_name')}:</span><br /><span class="FontSmall">{$modules.Language->getString('nick_conventions')}</span></td>
 <td class="CellAlt"><input class="FormText" type="text" name="p[userNick]" value="{$p.userNick}" size="20" /></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('email_address')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" name="p[userEmailAddress]" value="{$p.userEmailAddress}" size="30" /></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('password')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="password" name="p[userPassword]" size="20"/></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('password_confirmation')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="password" name="p[userPasswordConfirmation]" size="20"/></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('options')}:</span></td>
 <td class="CellAlt"><span class="FontNorm"><label><input type="checkbox" name="c[notifyUser]" value="1"{if $c.notifyUser == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('notify_user_registration')}</label></span></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('add_user')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}"/></td></tr>
</table>
</form>
