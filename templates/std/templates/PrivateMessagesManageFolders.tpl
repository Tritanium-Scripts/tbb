<table class="TableStd" width="100%">
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Manage_folders')}</span></td></tr>
{foreach from=$foldersData item=curFolder}
 <tr>
  <td class="CellStd"><span class="FontNorm"><a href="{$indexFile}?action=PrivateMessages&amp;mode=EditFolder&amp;FolderID={$curFolder.FolderID}&amp;{$mySID}">{$curFolder.FolderName}</a></span></td>
  <td class="CellAlt" align="right"><span class="FontSmall"><a href="{$indexFile}?action=PrivateMessages&amp;mode=DeleteFolder&amp;FolderID={$curFolder.FolderID}&amp;{$mySID}"><img src="{$modules.Template->getTemplateDir()}/images/icons/FolderDelete.png" class="ImageIcon" alt="{$modules.Language->getString('Delete_folder')}" border="0"/></a><a href="{$indexFile}?action=PrivateMessages&amp;FolderID={$curFolder.FolderID}&amp;{$mySID}"><img src="{$modules.Template->getTemplateDir()}/images/icons/FolderGo.png" class="ImageIcon" alt="" border="0"/></a><a href="{$indexFile}?action=PrivateMessages&amp;mode=EditFolder&amp;FolderID={$curFolder.FolderID}&amp;{$mySID}"><img src="{$modules.Template->getTemplateDir()}/images/icons/FolderEdit.png" class="ImageIcon" alt="{$modules.Language->getString('Edit_folder')}" border="0"/></a></span></td>
 </tr>
{foreachelse}
 <tr><td class="CellStd" align="center"><span class="FontNorm">-- Keine Ordner vorhanden --</span></td></tr>
{/foreach}
</table>
<br/>
<table class="TableStd" width="100%">
<tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('Other_options')}</span></td></tr>
<tr><td class="CellStd"><span class="FontNorm"><a href="{$indexFile}?action=PrivateMessages&amp;mode=AddFolder&amp;{$mySID}">Ordner hinzuf&uuml;gen</a></span></span></td></tr>
</table>