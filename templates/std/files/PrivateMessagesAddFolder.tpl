<form method="post" action="{$indexFile}?action=PrivateMessages&amp;mode=AddFolder&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<colgroup>
 <col width="20%"/>
 <col width="80%"/>
</colgroup>
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Add_folder')}</span></td></tr>
{if $error != ''}<tr><td class="CellError" colspan="2"><span class="FontError"><img src="{$modules.Template->getTemplateDir()}/images/icons/Warning.png" class="ImageIcon" border="0" alt=""/>{$error}</span></td></tr>{/if}
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('Folder_name')}:</span></td>
 <td class="CellAlt"><input class="FormText" name="p[folderName]" value="{$p.folderName}" size="40" maxlength="255"/></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Add_folder')}"/></td></tr>
</table>
</form>
