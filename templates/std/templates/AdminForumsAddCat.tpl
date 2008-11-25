<form method="post" action="{$indexFile}?action=AdminForums&amp;mode=AddCat&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<colgroup>
 <col width="20%"/>
 <col width="80%"/>
</colgroup>
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('add_category')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('name')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" size="35" name="p[catName]" value="{$p.catName}" /></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('description')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" size="45" name="p[catDescription]" value="{$p.catDescription}" /></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('standard_status')}:</span></td>
 <td class="CellAlt"><select class="FormSelect" name="p[catStandardStatus]"><option value="1"{if $p.catStandardStatus == 1} selected="selected"{/if}>{$modules.Language->getString('open')}</option><option value="0"{if $p.catStandardStatus == 0} selected="selected"{/if}>{$modules.Language->getString('closed')}</option></select></td>
</tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('parent_category')}:</span></td>
 <td class="CellAlt"><select class="FormSelect" name="p[parentCatID]">
 {foreach from=$catsData item=curCat}
  <option value="{$curCat.catID}"{if $curCat.catID == $p.parentCatID} selected="selected"{/if}>{$curCat._catPrefix} {$curCat.catName}</option>
 {/foreach}
 </select></td>
</tr>
<tr><td colspan="2" class="CellButtons" align="center"><input type="submit" class="FormBButton" value="{$modules.Language->getString('add_category')}" />&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}" /></td></tr>
</table>
</form>
