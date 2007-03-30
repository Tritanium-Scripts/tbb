<table class="TableStd" width="100%">
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$Modules.Language->getString('Manage_folders')}</span></td></tr>
{foreach from=$FoldersData item=curFolder}
 <tr>
  <td class="CellStd"><span class="FontNorm"><a href="{$IndexFile}?Action=PrivateMessages&amp;Mode=EditFolder&amp;FolderID={$curFolder.FolderID}&amp;{$MySID}">{$curFolder.FolderName}</a></span></td>
  <td class="CellAlt" align="right"><span class="FontSmall"><a href="{$IndexFile}?Action=PrivateMessages&amp;Mode=DeleteFolder&amp;FolderID={$curFolder.FolderID}&amp;{$MySID}"><img src="{$Modules.Template->getTemplateDir()}/images/icons/FolderDelete.png" class="ImageIcon" alt="{$Modules.Language->getString('Delete_folder')}" border="0"/></a><a href="{$IndexFile}?Action=PrivateMessages&amp;FolderID={$curFolder.FolderID}&amp;{$MySID}"><img src="{$Modules.Template->getTemplateDir()}/images/icons/FolderGo.png" class="ImageIcon" alt="" border="0"/></a><a href="{$IndexFile}?Action=PrivateMessages&amp;Mode=EditFolder&amp;FolderID={$curFolder.FolderID}&amp;{$MySID}"><img src="{$Modules.Template->getTemplateDir()}/images/icons/FolderEdit.png" class="ImageIcon" alt="{$Modules.Language->getString('Edit_folder')}" border="0"/></a></span></td>
 </tr>
{foreachelse}
 <tr><td class="CellStd" align="center"><span class="FontNorm">-- Keine Ordner vorhanden --</span></td></tr>
{/foreach}
</table>
<br/>
<table class="TableStd" width="100%">
<tr><td class="CellCat"><span class="FontCat">{$Modules.Language->getString('Other_options')}</span></td></tr>
<tr><td class="CellStd"><span class="FontNorm"><a href="{$IndexFile}?Action=PrivateMessages&amp;Mode=AddFolder&amp;{$MySID}">Ordner hinzuf&uuml;gen</a></span></span></td></tr>
</table>