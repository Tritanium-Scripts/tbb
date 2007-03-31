<form method="post" action="{$indexFile}?action=Login&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Login')}</span></td></tr>
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Logindata')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError"><img src="warning.png" class="ImageIcon" border="0" alt=""/>{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd" width="25%"><span class="FontNorm">{$modules.Language->getString('User_name')}:</span></td>
 <td class="CellAlt" width="75%"><input class="FormText" type="text" name="userNick" value="{$userNick}" tabindex="1" size="35" maxlength="15"/>&nbsp;<span class="FontSmall">(<a href="{$indexFile}?action=Register&amp;{$mySID}" tabindex="3">{$modules.Language->getString('Register')}</a>)</span></td>
</tr>
<tr>
 <td class="CellStd" width="25%"><span class="FontNorm">{$modules.Language->getString('Password')}:</span></td>
 <td class="CellAlt" width="75%"><input class="FormText" type="password" name="p[userPassword]" tabindex="2" size="30"/>&nbsp;<span class="FontSmall">(<a href="{$indexFile}?action=RequestPassword&amp;{$mySID}" tabindex="4">{$modules.Language->getString('Request_new_password')}</a>)</span></td>
</tr>
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Other_options')}</span></td></tr>
<tr><td class="CellStd" colspan="2"><span class="FontNorm"><label><input type="checkbox" name="c[StayLoggedIn]" value="1"{if $c.stayLoggedIn == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('Stay_logged_in')}</label></span></td></tr>
<tr><td class="CellStd" colspan="2"><span class="FontNorm"><label><input type="checkbox" name="c[EnableGhostMode]" value="1"{if $c.enableGhostMode == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('Hide_from_who_is_online')}</label></span></td></tr>
<tr><td class="CellButtons" colspan="2" align="center"><input type="submit" value="{$modules.Language->getString('Login')}" class="FormBButton"/></td></tr>
</table>
</form>
