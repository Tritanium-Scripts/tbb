<form method="post" action="{$smarty.const.INDEXFILE}?action=ViewProfile&amp;profileID={$profileID}&amp;mode=EditNote&amp;noteID={$noteID}&amp;doit=1&amp;{$smarty.const.MYSID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('edit_note')}</span></td></tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('note')}:</span></td>
 <td class="CellStd"><textarea class="FormTextArea" cols="50" rows="8" name="p[noteText]">{$p.noteText}</textarea></td>
</tr>
{if $modules.Auth->getValue('userIsAdmin') == 1 || $modules.Auth->getValue('userIsSupermod') == 1 || $userIsMod}
 <tr>
  <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('options')}:</span></td>
  <td class="CellStd"><span class="FontNorm"><label><input type="checkbox" name="c[noteIsPublic]" value="1"{if $c.noteIsPublic == 1} checked="checked"{/if}/>&nbsp;{$modules.Language->getString('post_public_note')}</label></span></td>
 </tr>
{/if}
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('edit_note')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('reset')}"/></td></tr>
</table>
</form>
