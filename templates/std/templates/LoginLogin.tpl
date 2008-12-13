<form method="post" action="{$smarty.const.INDEXFILE}?action=Login&amp;doit=1&amp;{$smarty.const.MYSID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('login')}</span></td></tr>
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('logindata')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError"><img src="{$modules.Template->getTD()}/images/icons/Warning.png" class="ImageIcon" alt="Warning"/>{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd" width="25%"><span class="FontNorm">{$modules.Language->getString('user_name')}:</span></td>
 <td class="CellAlt" width="75%"><input class="FormText" type="text" name="userNick" value="{$userNick}" tabindex="1" size="35" maxlength="15"/>&nbsp;<span class="FontSmall">(<a href="{$smarty.const.INDEXFILE}?action=Register&amp;{$smarty.const.MYSID}" tabindex="3">{$modules.Language->getString('register')}</a>)</span></td>
</tr>
<tr>
 <td class="CellStd" width="25%"><span class="FontNorm">{$modules.Language->getString('password')}:</span></td>
 <td class="CellAlt" width="75%"><input class="FormText" type="password" name="p[userPassword]" tabindex="2" size="30"/>&nbsp;<span class="FontSmall">(<a href="{$smarty.const.INDEXFILE}?action=Login&amp;mode=RequestPassword&amp;{$smarty.const.MYSID}" tabindex="4">{$modules.Language->getString('request_new_password')}</a>)</span></td>
</tr>
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('other_options')}</span></td></tr>
<tr><td class="CellStd" colspan="2"><span class="FontNorm"><label><input type="checkbox" name="c[StayLoggedIn]" value="1"{if $c.stayLoggedIn == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('stay_logged_in')}</label></span></td></tr>
{if $modules.Config->getValue('allow_ghost_mode') == 1}<tr><td class="CellStd" colspan="2"><span class="FontNorm"><label><input type="checkbox" name="c[EnableGhostMode]" value="1"{if $c.enableGhostMode == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('hide_from_who_is_online')}</label></span></td></tr>{/if}
<tr><td class="CellButtons" colspan="2" align="center"><input type="submit" value="{$modules.Language->getString('login')}" class="FormBButton"/></td></tr>
</table>
</form>
