<form method="post" action="{$indexFile}?action=AdminForums&amp;mode=AddCat&amp;catID={$catID}&amp;doit=1&amp;{$mySID}">
<table class="TableStd" cellspacing="0" width="100%">
<colgroup>
 <col width="20%"/>
 <col width="80%"/>
</colgroup>
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Add_category')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Name')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" size="35" name="p[catName]" value="{$p.catName}" /></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Description')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" size="45" name="p[catDescription]" value="{$p.catDescription}" /></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Standard_status')}:</span></td>
 <td class="CellAlt"><select class="FormSelect" name="p[catStandardStatus]"><option value="1"{if $p.catStandardStatus == 1} selected="selected"{/if}>{$modules.Language->getString('open')}</option><option value="0"{if $p.catStandardStatus == 0} selected="selected"{/if}>{$modules.Language->getString('closed')}</option></select></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Parent_category')}:</span></td>
 <td class="CellAlt"><select class="FormSelect" name="p[parentID]">´
 {foreach from=$catsData item=curCat}
  <option value="{$curCat.catID}"{if $curCat.catID == $p.parentID} selected="selected"{/if}>{$curCat._catPrefix} {$curCat.catName}</option>
 {/foreach}
 </select></td>
</tr>
<tr><td colspan="2" class="CellButtons" align="center"><input type="submit" class="FormBButton" value="{$modules.Language->getString('Add_category')}" />&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}" /></td></tr>
</table>
</form>
