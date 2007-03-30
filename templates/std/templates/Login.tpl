<form method="post" action="{$IndexFile}?Action=Login&amp;Doit=1&amp;{$MySID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$Modules.Language->getString('Login')}</span></td></tr>
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$Modules.Language->getString('Logindata')}</span></td></tr>
{if $Error != ''}<tr><td class="CellError" colspan="2"><span class="FontError"><img src="warning.png" class="ImageIcon" border="0" alt=""/>{$Error}</span></td></tr>{/if}
<tr>
 <td class="CellStd" width="25%"><span class="FontNorm">{$Modules.Language->getString('User_name')}:</span></td>
 <td class="CellAlt" width="75%"><input class="FormText" type="text" name="UserNick" value="{$UserNick}" tabindex="1" size="35" maxlength="15"/>&nbsp;<span class="FontSmall">(<a href="{$IndexFile}?Action=Register&amp;{$MySID}" tabindex="3">{$Modules.Language->getString('Register')}</a>)</span></td>
</tr>
<tr>
 <td class="CellStd" width="25%"><span class="FontNorm">{$Modules.Language->getString('Password')}:</span></td>
 <td class="CellAlt" width="75%"><input class="FormText" type="password" name="p[UserPassword]" tabindex="2" size="30"/>&nbsp;<span class="FontSmall">(<a href="{$IndexFile}?Action=RequestPassword&amp;{$MySID}" tabindex="4">{$Modules.Language->getString('Request_new_password')}</a>)</span></td>
</tr>
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$Modules.Language->getString('Other_options')}</span></td></tr>
<tr><td class="CellStd" colspan="2"><span class="FontNorm"><input type="checkbox" name="c[StayLoggedIn]" value="1"{if $c.StayLoggedIn == 1} checked="checked"{/if} id="idStayLoggedIn"/><label for="idStayLoggedIn">&nbsp;{$Modules.Language->getString('Stay_logged_in')}</label></span></td></tr>
<tr><td class="CellStd" colspan="2"><span class="FontNorm"><input type="checkbox" name="c[EnableGhostMode]" value="1"{if $c.EnableGhostMode == 1} checked="checked"{/if} id="idEnableGhostMode"/><label for="idEnableGhostMode">&nbsp;{$Modules.Language->getString('Hide_from_who_is_online')}</label></span></td></tr>
<tr><td class="CellButtons" colspan="2" align="center"><input type="submit" value="{$Modules.Language->getString('Login')}" class="FormBButton"/></td></tr>
</table>
</form>
