<table class="TableStd" cellspacing="0" width="100%">
<tr><td class="CellTitle" colspan="3"><span class="FontTitle">{$modules.Language->getString('Manage_forums')}</span></td></tr>
{foreach from=$catsData item=curCat}
 <tr>
  <td class="CellCat"><span class="FontCat">{$curCat._catPrefix} {$curCat.catName}</span></td>
  <td class="CellCat"><span class="FontCatSmall">{$curCat._catPrefix} {$curCat._catUp} | {$curCat._catDown}</span></td>
  <td class="CellCat" align="right"><span class="FontCatSmall"><a href="{$indexFile}?action=AdminForums&amp;mode=EditCat&amp;catID={$curCat.catID}&amp;{$mySID}">{$modules.Language->getString('Edit')}</a> | <a href="{$indexFile}?action=AdminForums&amp;mode=AddCat&amp;parentCatID={$curCat.catID}&amp;{$mySID}">{$modules.Language->getString('Add_sub_category')}</a> | <a href="{$indexFile}?action=AdminForums&amp;mode=AddForum&amp;catID={$curCat.catID}&amp;{$mySID}">{$modules.Language->getString('Add_forum')}</a></span></td>
 </tr>
 {foreach from=$forumsData item=curForum}
  {if $curForum.catID == $curCat.catID}
   <tr class="RowToHighlight" onmouseover="setRowCellsClass(this,'CellHighlight');" onmouseout="restoreRowCellsClass(this);">
    <td class="CellStd"><span class="FontNorm">--{$curCat._catPrefix} {$curForum.forumName}</span></td>
    <td class="CellStd"><span class="FontSmall">--{$curCat._catPrefix} {$curForum._forumUp} | {$curForum._forumDown}</span></td>
    <td class="CellStd" align="right"><span class="FontSmall"><a href="{$indexFile}?action=AdminForums&amp;mode=EditSpecialRights&amp;forumID={$curForum.forumID}&amp;{$mySID}">{$modules.Language->getString('Edit_special_rights')}</a> | <a href="{$indexFile}?action=AdminForums&amp;mode=EditForum&amp;forumID={$curForum.forumID}&amp;{$mySID}">{$modules.Language->getString('Edit')}</a></span></td>
   </tr>
  {/if}
 {/foreach}
{/foreach}
</table>
<br />
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Forums_without_category')}</span></td></tr>
{foreach from=$forumsData item=curForum}
 {if $curForum.catID == 1}
  <tr class="RowToHighlight" onmouseover="setRowCellsClass(this,'CellHighlight');" onmouseout="restoreRowCellsClass(this);">
   <td class="CellStd"><span class="FontNorm">{$curForum.forumName}</span></td>
   <td class="CellStd" align="right"><span class="FontSmall"><a href="{$indexFile}?action=AdminForums&amp;mode=EditSpecialRights&amp;forumID={$curForum.forumID}&amp;{$mySID}">{$modules.Language->getString('Edit_special_rights')}</a> | <span class="FontSmall"><a href="{$indexFile}?action=AdminForums&amp;mode=EditForum&amp;forumID={$curForum.forumID}&amp;{$mySID}">{$modules.Language->getString('Edit')}</a></span></td>
  </tr>
 {/if}
{/foreach}
</table>
<br />
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Other_options')}</span></td></tr>
<tr><td class="CellStd"><span class="FontNorm"><a href="{$indexFile}?action=AdminForums&amp;mode=AddCat&amp;parentCatID=1&amp;{$mySID}">{$modules.Language->getString('Add_category')}</a><br /><a href="{$indexFile}?action=AdminForums&amp;mode=AddForum&amp;catID=1&amp;{$mySID}">{$modules.Language->getString('Add_forum')}</a></span></td></tr>
</table>
