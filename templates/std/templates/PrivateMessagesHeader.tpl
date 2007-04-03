<table class="TableStd" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Private_messages')}</span></td></tr>
<tr><td class="CellStd" style="background-color:#DCDCDC;">
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td valign="top" width="200">
   <table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
   <tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('Navigation')}</span></td></tr>
   <tr><td class="CellNav" onclick="goTo('{$indexFile}?action=PrivateMessages&amp;mode=NewPM&amp;{$mySID}');"><a class="FontNav" href="{$indexFile}?action=PrivateMessages&amp;mode=NewPM&amp;{$mySID}">{$modules.Language->getString('New_private_message')}</a></td></tr>
   <tr><td class="CellNavNone" style="padding:0px;"><hr style="width:90%; height:1px;"/></td></tr>
   {foreach from=$headerFoldersData item=curFolder}
    <tr><td class="CellNav" onclick="goTo('{$indexFile}?action=PrivateMessages&amp;folderID={$curFolder.folderID}&amp;{$mySID}');"><a class="FontNav" href="{$indexFile}?action=PrivateMessages&amp;folderID={$curFolder.folderID}&amp;{$mySID}">{$curFolder.folderName}</a></td></tr>
   {/foreach}
   <tr><td class="CellNavNone" style="padding:0px;"><hr style="width:90%; height:1px;"/></td></tr>
   <tr><td class="CellNav" onclick="goTo('{$indexFile}?action=PrivateMessages&amp;mode=ManageFolders&amp;{$mySID}');"><a class="FontNav" href="{$indexFile}?action=PrivateMessages&amp;mode=ManageFolders&amp;{$mySID}">{$modules.Language->getString('Manage_folders')}</a></td></tr>
   </table>
  </td>
  <td valign="top" width="10">&nbsp;</td>
  <td valign="top">