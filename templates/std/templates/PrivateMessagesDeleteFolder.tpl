<form method="post" action="{$indexFile}?action=PrivateMessages&amp;mode=DeleteFolder&amp;folderID={$folderID}&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('delete_folder')}</span></td></tr>
<tr><td class="CellStd"><div class="DivInfoBox">
 <span class="FontNorm"><img src="{$modules.Template->getTemplateDir()}/images/icons/Attention.png" class="ImageIcon" alt=""/>{$modules.Language->getString('delete_folder_text')}</span>
 <br/>
 <select class="FormSelect" name="moveFolderID">
  <option value="-1">{$modules.Language->getString('delete_messages')}</option>
  <option value="-1"></option>
  {foreach from=$foldersData item=curFolder}
   <option value="{$curFolder.folderID}">{$curFolder._moveText}</option>
  {/foreach}
 </select>
</div></td></tr>
<tr><td class="CellButtons" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('delete_folder')}"/></td></tr>
</table>
</form>
