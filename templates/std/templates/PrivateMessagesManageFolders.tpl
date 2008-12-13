<table class="TableStd" width="100%">
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('manage_folders')}</span></td></tr>
{foreach from=$foldersData item=curFolder}
 <tr>
  <td class="CellStd"><span class="FontNorm"><a href="{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;mode=EditFolder&amp;folderID={$curFolder.folderID}&amp;{$smarty.const.MYSID}">{$curFolder.folderName}</a></span></td>
  <td class="CellAlt" align="right"><span class="FontSmall"><a href="{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;mode=DeleteFolder&amp;folderID={$curFolder.folderID}&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTemplateDir()}/images/icons/FolderDelete.png" class="ImageIcon" alt="{$modules.Language->getString('delete_folder')}"/></a><a href="{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;folderID={$curFolder.folderID}&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTemplateDir()}/images/icons/FolderGo.png" class="ImageIcon" alt=""/></a><a href="{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;mode=EditFolder&amp;folderID={$curFolder.folderID}&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTemplateDir()}/images/icons/FolderEdit.png" class="ImageIcon" alt="{$modules.Language->getString('edit_folder')}"/></a></span></td>
 </tr>
{foreachelse}
 <tr><td class="CellStd" align="center"><span class="FontNorm">-- Keine Ordner vorhanden --</span></td></tr>
{/foreach}
</table>
<br/>
<table class="TableStd" width="100%">
<tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('other_options')}</span></td></tr>
<tr><td class="CellStd"><span class="FontNorm"><a href="{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;mode=AddFolder&amp;{$smarty.const.MYSID}">Ordner hinzuf&uuml;gen</a></span></td></tr>
</table>