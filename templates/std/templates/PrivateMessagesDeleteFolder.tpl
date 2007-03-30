<form method="post" action="{$IndexFile}?Action=PrivateMessages&amp;Mode=DeleteFolder&amp;FolderID={$FolderID}&amp;Doit=1&amp;{$MySID}">
<table class="TableStd" width="100%">
<tr><td class="CellCat"><span class="FontCat">{$Modules.Language->getString('Delete_folder')}</span></td></tr>
<tr><td class="CellStd"><div class="DivInfoBox">
 <span class="FontNorm"><img src="{$Modules.Template->getTemplateDir()}/images/icons/Attention.png" class="ImageIcon" alt="" border="0"/>{$Modules.Language->getString('delete_folder_text')}</span>
 <br/>
 <select class="FormSelect" name="MoveFolderID">
  <option value="-1">{$Modules.Language->getString('Delete_messages')}</option>
  <option value=""></option>
  {foreach from=$FoldersData item=curFolder}
   <option value="{$curFolder.FolderID}">{$curFolder._MoveText}</option>
  {/foreach}
 </select>
</div></td></tr>
<tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$Modules.Language->getString('Delete_folder')}"/></td></tr>
</table>
</form>
