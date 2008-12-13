<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('manage_groups')}</span></td></tr>
{foreach from=$groupsData item=curGroup}
 <tr>
  <td class="CellStd"><span class="FontNorm"><a href="{$smarty.const.INDEXFILE}?action=AdminGroups&amp;mode=ManageMembers&amp;groupID={$curGroup.groupID}&amp;{$smarty.const.MYSID}">{$curGroup.groupName}</a></span></td>
  <td class="CellAlt" align="right"><span class="FontSmall"><a href="{$smarty.const.INDEXFILE}?action=AdminGroups&amp;mode=DeleteGroup&amp;groupID={$curGroup.groupID}&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTD()}/images/icons/GroupDelete.png" alt="{$modules.Language->getString('delete')}"/></a> <a href="{$smarty.const.INDEXFILE}?action=AdminGroups&amp;mode=ManageMembers&amp;groupID={$curGroup.groupID}&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTD()}/images/icons/GroupGo.png" alt="{$modules.Language->getString('manage_members')}"/></a> <a href="{$smarty.const.INDEXFILE}?action=AdminGroups&amp;mode=EditGroup&amp;groupID={$curGroup.groupID}&amp;{$smarty.const.MYSID}"><img src="{$modules.Template->getTD()}/images/icons/GroupEdit.png" alt="{$modules.Language->getString('edit')}"/></a></span></td>
 </tr>
{/foreach}
</table>
<br/>
<table class="TableStd" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('other_options')}</span></td></tr>
<tr><td class="CellStd"><span class="FontNorm"><a href="{$smarty.const.INDEXFILE}?action=AdminGroups&amp;mode=AddGroup&amp;{$smarty.const.MYSID}"><img class="ImageIcon" src="{$modules.Template->getTD()}/images/icons/GroupAdd.png" alt="{$modules.Language->getString('add_group')}"/> {$modules.Language->getString('add_group')}</a></span></td></tr>
</table>
