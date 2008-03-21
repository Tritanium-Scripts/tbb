<form method="post" action="{$indexFile}?action=AdminUsers&amp;mode=SearchUsers&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Manage_users')}</span></td></tr>
<tr><td class="CellInfoBox" colspan="2"><span class="FontInfoBox">{$modules.Language->getString('search_users_info')}</span></td></tr>
<tr>
 <td class="CellStd" width="20%"><span class="FontNorm">{$modules.Language->getString('User_id')}:</span></td>
 <td class="CellAlt" width="80%"><input class="FormText" name="p[userID]" size="10" /></td>
</tr>
<tr>
 <td class="CellStd" width="20%"><span class="FontNorm">{$modules.Language->getString('User_name')}:</span></td>
 <td class="CellAlt" width="80%"><input class="FormText" name="p[userNick]" size="20" /></td>
</tr>
<tr>
 <td class="CellStd" width="20%"><span class="FontNorm">{$modules.Language->getString('Email_address')}:</span></td>
 <td class="CellAlt" width="80%"><input class="FormText" name="p[userEmailAddress]" size="30" /></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Search_users')}" />&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}" /></td></tr>
</table>
</form>
<br/>
<form method="post" action="{$indexFile}?action=AdminUsers&amp;mode=UnlockUsers&amp;{$mySID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Unlock_users')}</span></td></tr>
<template:nolockedusers>
 <tr><td class="CellStd" align="center"><span class="FontNorm">-- {$modules.Language->getString('No_locked_users')} --</span></td></tr>
</template>
<template:lockeduserrow>
 <tr>
  <td></td>
  <td></td>
  <td></td>
 </tr>
</template>
<tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Unlock_selected_users')}" />&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}" /></td></tr>
</table>
</form>
<br/>
<table class="TableStd" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Other_options')}</span></td></tr>
<tr><td class="CellStd"><span class="FontNorm"><a href="{$indexFile}?action=AdminUsers&amp;mode=AddUser&amp;{$mySID}">{$modules.Language->getString('Add_user')}</span></td></tr>
</table>
