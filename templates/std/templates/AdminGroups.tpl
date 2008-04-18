<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Manage_groups')}</span></td></tr>
{foreach from=$groupsData item=curGroup}
 <tr>
  <td class="CellStd"><span class="FontNorm"><a href="{$indexFile}?action=AdminGroups&amp;mode=ManageMembers&amp;groupID={$curGroup.groupID}&amp;{$mySID}">{$curGroup.groupName}</a></span></td>
  <td class="CellAlt" align="right"><span class="FontSmall"><a href="{$indexFile}?action=AdminGroups&amp;mode=DeleteGroup&amp;groupID={$curGroup.groupID}&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/icons/GroupDelete.png" alt="{$modules.Language->getString('delete')}"/></a> <a href="{$indexFile}?action=AdminGroups&amp;mode=ManageMembers&amp;groupID={$curGroup.groupID}&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/icons/GroupGo.png" alt="{$modules.Language->getString('Manage_members')}"/></a> <a href="{$indexFile}?action=AdminGroups&amp;mode=EditGroup&amp;groupID={$curGroup.groupID}&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/icons/GroupEdit.png" alt="{$modules.Language->getString('edit')}"/></a></span></td>
 </tr>
{/foreach}
</table>
<br/>
<table class="TableStd" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Other_options')}</span></td></tr>
<tr><td class="CellStd"><span class="FontNorm"><a href="{$indexFile}?action=AdminGroups&amp;mode=AddGroup&amp;{$mySID}"><img class="ImageIcon" src="{$modules.Template->getTD()}/images/icons/GroupAdd.png" alt="{$modules.Language->getString('Add_group')}"/> {$modules.Language->getString('Add_group')}</a></span></td></tr>
</table>
