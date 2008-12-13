<form method="post" action="{$smarty.const.INDEXFILE}?action=AdminUsers&amp;mode=SearchUsers&amp;doit=1&amp;{$smarty.const.MYSID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('search_users')}</span></td></tr>
<tr><td class="CellInfoBox" colspan="2"><span class="FontInfoBox">{$modules.Language->getString('search_users_info')}</span></td></tr>
<tr>
 <td class="CellStd" width="20%"><span class="FontNorm">{$modules.Language->getString('user_id')}:</span></td>
 <td class="CellAlt" width="80%"><input class="FormText" name="p[userID]" value="{$p.userID}" size="10"/></td>
</tr>
<tr>
 <td class="CellStd" width="20%"><span class="FontNorm">{$modules.Language->getString('user_name')}:</span></td>
 <td class="CellAlt" width="80%"><input class="FormText" name="p[userNick]" value="{$p.userNick}" size="20"/></td>
</tr>
<tr>
 <td class="CellStd" width="20%"><span class="FontNorm">{$modules.Language->getString('email_address')}:</span></td>
 <td class="CellAlt" width="80%"><input class="FormText" name="p[userEmailAddress]" value="{$p.userEmailAddress}" size="30"/></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('search_users')}" />&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}" /></td></tr>
</table>
</form>
<br/>
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="4"><span class="FontTitle">{$modules.Language->getString('search_results')}</span></td></tr>
{foreach from=$usersData item=curUser}
 <tr class="RowToHighlight" onmouseover="setRowCellsClass(this,'CellHighlight');" onmouseout="restoreRowCellsClass(this);">
  <td class="CellStd"><span class="FontNorm">{$curUser.userID}</span></td>
  <td class="CellAlt"><span class="FontNorm">{$curUser.userNick}</span></td>
  <td class="CellStd"><span class="FontNorm">{$curUser.userEmailAddress}</span></td>
  <td class="CellAlt" align="right"><span class="FontSmall"><a href="{$smarty.const.INDEXFILE}?action=AdminUsers&amp;mode=EditUser&amp;userID={$curUser.userID}&amp;{$smarty.const.MYSID}">{$modules.Language->getString('edit')}</a></span></td>
 </tr>
{/foreach}
</table>