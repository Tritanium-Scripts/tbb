<form method="post" action="{$indexFile}?action=AdminGroups&amp;mode=AddGroup&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Add_group')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
</template>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Name')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" name="p[groupName]" value="{$p.groupName}" size="40" maxlength="255"/></td>
</tr>
<tr><td class="CellButtons" align="center" colspan="2"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Add_group')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
</table>
</form>
