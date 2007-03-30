<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$Modules.Language->getString('Private_messages')}</span></td></tr>
<tr><td class="CellStd" style="background-color:#DCDCDC;">
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td valign="top" width="200">
   <table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
   <tr><td class="CellCat"><span class="FontCat">{$Modules.Language->getString('Navigation')}</span></td></tr>
   <tr><td class="CellNav" onclick="goTo('{$IndexFile}?Action=PrivateMessages&amp;Mode=NewPM&amp;{$MySID}');"><a class="FontNav" href="{$IndexFile}?Action=PrivateMessages&amp;Mode=NewPM&amp;{$MySID}">{$Modules.Language->getString('New_private_message')}</a></td></tr>
   <tr><td class="CellNavNone" style="padding:0px;"><hr style="width:90%; height:1px;"/></td></tr>
   {foreach from=$HeaderFoldersData item=curFolder}
    <tr><td class="CellNav" onclick="goTo('{$IndexFile}?Action=PrivateMessages&amp;FolderID={$curFolder.FolderID}&amp;{$MySID}');"><a class="FontNav" href="{$IndexFile}?Action=PrivateMessages&amp;FolderID={$curFolder.FolderID}&amp;{$MySID}">{$curFolder.FolderName}</a></td></tr>
   {/foreach}
   <tr><td class="CellNavNone" style="padding:0px;"><hr style="width:90%; height:1px;"/></td></tr>
   <tr><td class="CellNav" onclick="goTo('{$IndexFile}?Action=PrivateMessages&amp;Mode=ManageFolders&amp;{$MySID}');"><a class="FontNav" href="{$IndexFile}?Action=PrivateMessages&amp;Mode=ManageFolders&amp;{$MySID}">{$Modules.Language->getString('Manage_folders')}</a></td></tr>
   </table>
  </td>
  <td valign="top" width="10">&nbsp;</td>
  <td valign="top">