<form method="post" action="{$IndexFile}?Action=PrivateMessages&amp;Mode=EditFolder&amp;FolderID={$FolderID}&amp;Doit=1&amp;{$MySID}">
<table class="TableStd" width="100%">
<colgroup>
 <col width="20%"/>
 <col width="80%"/>
</colgroup>
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$Modules.Language->getString('Edit_folder')}</span></td></tr>
{if $Error != ''}<tr><td class="CellError" colspan="2"><span class="FontError"><img src="{$Modules.Template->getTemplateDir()}/images/icons/Warning.png" class="ImageIcon" border="0" alt=""/>{$Error}</span></td></tr>{/if}
<tr>
 <td class="CellStd"><span class="FontNorm">{$Modules.Language->getString('Folder_name')}:</span></td>
 <td class="CellAlt"><input class="FormText" name="p[FolderName]" value="{$p.FolderName}" size="40" maxlength="255"/></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$Modules.Language->getString('Edit_folder')}"/></td></tr>
</table>
</form>
