<form method="post" action="{$indexFile}?action=MemberList&amp;page={$page}&amp;{$mySID}">
<table class="TableStd" width="100%">
<tr>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('User_id')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('User_name')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('User_rank')}</span></td>
 <td class="CellTitle" align="center"><span class="FontTitleSmall">{$modules.Language->getString('Posts')}</span></td>
 {foreach from=$fieldsData item=curField}
  <td class="CellTitle" align="center"><span class="FontTitleSmall">{$curField.fieldName}</span></td>
 {/foreach}
</tr>
{foreach from=$usersData item=curUser}
 <tr onmouseover="setRowCellsClass(this,'CellHighlight');" onmouseout="restoreRowCellsClass(this);">
  <td class="CellStd"><span class="FontNorm">{$curUser.userID}</span></td>
  <td class="CellAlt"><span class="FontNorm"><a href="{$indexFile}?action=ViewProfile&amp;profileID={$curUser.userID}&amp;{$mySID}">{$curUser.userNick}</a></span></td>
  <td class="CellStd"><span class="FontNorm">{$curUser._userRankName}</span></td>
  <td class="CellAlt"><span class="FontNorm">{$curUser.userPostsCounter}</span></td>
  {foreach from=$curUser._userFieldsValues item=curFieldValue}
   <td class="{cycle values="CellStd,CellAlt" reset=true}"><span class="FontNorm">{$curFieldValue}</span></td>
  {/foreach}
 </tr>
{/foreach}
<tr><td colspan="100" class="CellButtons"><span class="FontSmall"><b>{$modules.Language->getString('Display_options')}:</b> {$modules.Language->getString('Sort_by')}
 <select class="FormSelect" name="orderBy">
  <option value="id"{if $orderBy == 'id'} selected="selected"{/if}>{$modules.Language->getString('User_id')}</option>
  <option value="nick"{if $orderBy == 'nick'} selected="selected"{/if}>{$modules.Language->getString('User_name')}</option>
  <option value="rank"{if $orderBy == 'rank'} selected="selected"{/if}>{$modules.Language->getString('User_rank')}</option>
  <option value="posts"{if $orderBy == 'posts'} selected="selected"{/if}>{$modules.Language->getString('Posts')}</option>
 </select>
 <select class="FormSelect" name="orderType">
  <option value="DESC"{if $orderType == 'DESC'} selected="selected"{/if}>{$modules.Language->getString('Descending')}</option>
  <option value="ASC"{if $orderType == 'ASC'} selected="selected"{/if}>{$modules.Language->getString('Ascending')}</option>
 </select>; {$modules.Language->getString('Users_per_page')}
 <select class="FormSelect" name="usersPerPage">
  <option value="10"{if $usersPerPage == 10} selected="selected"{/if}>10</option>
  <option value="20"{if $usersPerPage == 20} selected="selected"{/if}>20</option>
  <option value="50"{if $usersPerPage == 50} selected="selected"{/if}>50</option>
  <option value="100"{if $usersPerPage == 100} selected="selected"{/if}>100</option>
 </select>&nbsp;&nbsp;&nbsp;<input class="FormBButton" type="submit" value="{$modules.Language->getString('Go')}"/>
</td></tr>
</table>
</form>
