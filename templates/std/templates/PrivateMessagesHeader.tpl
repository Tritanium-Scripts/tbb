<table class="TableStd" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('private_messages')}</span></td></tr>
<tr><td class="CellStd" style="background-color:#DCDCDC;">
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td valign="top" width="200">
   <table class="TableStd" width="100%">
   <tr><td class="CellCat"><span class="FontCat">{$modules.Language->getString('navigation')}</span></td></tr>
   <tr><td class="CellNav" onclick="goTo('{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;mode=NewPM&amp;{$smarty.const.MYSID}');"><a class="FontNav" href="{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;mode=NewPM&amp;{$smarty.const.MYSID}">{$modules.Language->getString('new_private_message')}</a></td></tr>
   <tr><td class="CellNavNone"><hr class="LineNav"/></td></tr>
   {foreach from=$headerFoldersData item=curFolder}
    <tr><td class="CellNav" onclick="goTo('{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;folderID={$curFolder.folderID}&amp;{$smarty.const.MYSID}');"><a class="FontNav" href="{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;folderID={$curFolder.folderID}&amp;{$smarty.const.MYSID}">{$curFolder.folderName}</a></td></tr>
   {/foreach}
   <tr><td class="CellNavNone"><hr class="LineNav"/></td></tr>
   <tr><td class="CellNav" onclick="goTo('{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;mode=ManageFolders&amp;{$smarty.const.MYSID}');"><a class="FontNav" href="{$smarty.const.INDEXFILE}?action=PrivateMessages&amp;mode=ManageFolders&amp;{$smarty.const.MYSID}">{$modules.Language->getString('manage_folders')}</a></td></tr>
   </table>
  </td>
  <td valign="top" width="10">&nbsp;</td>
  <td valign="top">