<form method="post" action="{$smarty.const.INDEXFILE}?action=AdminGroups&amp;mode=EditGroup&amp;groupID={$groupID}&amp;doit=1&amp;{$smarty.const.MYSID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('edit_group')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError">{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('name')}:</span></td>
 <td class="CellAlt"><input class="FormText" type="text" name="p[groupName]" value="{$p.groupName}" size="40" maxlength="255"/></td>
</tr>
<tr><td class="CellButtons" align="center" colspan="2"><input class="FormBButton" type="submit" value="{$modules.Language->getString('edit_group')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}"/></td></tr>
</table>
</form>
