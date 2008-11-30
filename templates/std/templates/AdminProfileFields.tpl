<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="3"><span class="FontTitle">{$modules.Language->getString('manage_profile_fields')}</span></td></tr>
{foreach from=$fieldsData item=curField}
 <tr class="RowToHighlight" onmouseover="setRowCellsClass(this,'CellHighlight');" onmouseout="restoreRowCellsClass(this);">
  <td class="CellStd"><span class="FontNorm"><a href="{$indexFile}?action=AdminProfileFields&amp;mode=EditField&amp;fieldID={$curField.fieldID}&amp;{$mySID}">{$curField.fieldName}</a></span></td>
  <td class="CellAlt" align="center"><span class="FontNorm">{$curField._fieldTypeText}</span></td>
  <td class="CellStd" align="right"><span class="FontSmall">{if $curField.fieldIsLocked != 1}<a href="{$indexFile}?action=AdminProfileFields&amp;mode=DeleteField&amp;fieldID={$curField.fieldID}&amp;{$mySID}">{$modules.Language->getString('delete')}</a> | {/if}<a href="{$indexFile}?action=AdminProfileFields&amp;mode=EditField&amp;fieldID={$curField.fieldID}&amp;{$mySID}">{$modules.Language->getString('edit')}</a></span></td>
 </tr>
{/foreach}
</table>
<br />
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('other_options')}</span></td></tr>
<tr><td class="CellStd"><span class="FontNorm"><a href="{$indexFile}?action=AdminProfileFields&amp;mode=AddField&amp;{$mySID}">{$modules.Language->getString('add_profile_field')}</a></span></td></tr>
</table>
