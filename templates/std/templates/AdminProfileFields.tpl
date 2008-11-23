<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="3"><span class="FontTitle">{$modules.Language->getString('Manage_profile_fields')}</span></td></tr>
{foreach from=$fieldsData item=curField}
 <tr class="RowToHighlight" onmouseover="setRowCellsClass(this,'CellHighlight');" onmouseout="restoreRowCellsClass(this);">
  <td class="CellStd"><span class="FontNorm"><a href="{$indexFile}?action=AdminProfileFields&amp;mode=EditField&amp;fieldID={$curField.fieldID}&amp;{$mySID}">{$curField.fieldName}</a></span></td>
  <td class="CellAlt" align="center"><span class="FontNorm">{$curField._fieldTypeText}</span></td>
  <td class="CellStd" align="right"><span class="FontSmall"><a href="{$indexFile}?action=AdminProfileFields&amp;mode=DeleteField&amp;fieldID={$curField.fieldID}&amp;{$mySID}">{$modules.Language->getString('delete')}</a> | <a href="{$indexFile}?action=AdminProfileFields&amp;mode=EditField&amp;fieldID={$curField.fieldID}&amp;{$mySID}">{$modules.Language->getString('Edit')}</a></span></td>
 </tr>
{/foreach}
</table>
<br />
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Other_options')}</span></td></tr>
<tr><td class="CellStd"><span class="FontNorm"><a href="{$indexFile}?action=AdminProfileFields&amp;mode=AddField&amp;{$mySID}">{$modules.Language->getString('Add_profile_field')}</a></span></td></tr>
</table>
