<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Manage_members')}</span></td></tr>
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Group_leaders')}</span></td></tr>
{foreach from=$groupAdminsData item=curMember}
 <tr>
  <td class="CellStd"><span class="FontNorm">{$curMember.memberNick}</span></td>
  <td class="CellAlt" align="right"><span class="FontSmall"><a href="{$indexFile}?action=AdminGroups&amp;groupID={$groupID}&amp;mode=SwitchMemberStatus&amp;memberID={$curMember.memberID}&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/icons/MedalGoldDelete.png" alt="{$modules.Language->getString('Downgrade_member')}" title="{$modules.Language->getString('Downgrade_member')}"/></a> <a href="{$indexFile}?action=AdminGroups&amp;groupID={$groupID}&amp;mode=DeleteMember&amp;memberID={$curMember.memberID}&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/icons/UserDelete.png" alt="{$modules.Language->getString('delete')}" title="{$modules.Language->getString('delete')}"/></a></span></td>
 </tr>
{/foreach}
<tr><td class="CellCat" colspan="2"><span class="FontCat">{$modules.Language->getString('Other_members')}</span></td></tr>
{foreach from=$groupMembersData item=curMember}
 <tr>
  <td class="CellStd"><span class="FontNorm">{$curMember.memberNick}</span></td>
  <td class="CellAlt" align="right"><span class="FontSmall"><a href="{$indexFile}?action=AdminGroups&amp;groupID={$groupID}&amp;mode=SwitchMemberStatus&amp;memberID={$curMember.memberID}&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/icons/MedalGoldAdd.png" alt="{$modules.Language->getString('Upgrade_member')}" title="{$modules.Language->getString('Upgrade_member')}"/></a> <a href="{$indexFile}?action=AdminGroups&amp;groupID={$groupID}&amp;mode=DeleteMember&amp;memberID={$curMember.memberID}&amp;{$mySID}"><img src="{$modules.Template->getTD()}/images/icons/UserDelete.png" alt="{$modules.Language->getString('delete')}" title="{$modules.Language->getString('delete')}"/></a></span></td>
 </tr>
{/foreach}
</table>
<br/>
<form method="post" action="{$indexFile}?action=AdminGroups&amp;mode=AddMembers&amp;groupID={$groupID}&amp;{$mySID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('Add_members')}</span></td></tr>
<tr>
 <td class="CellStd" width="15%"><span class="FontNorm">{$modules.Language->getString('User')}:</span></td>
 <td class="CellAlt" width="85%"><input class="FormText" type="text" name="p[newMembers]" value="" size="40"/></td>
</tr>
<tr>
 <td class="CellStd" width="15%"><span class="FontNorm">{$modules.Language->getString('Group_leader')}:</span></td>
 <td class="CellAlt" width="85%"><select class="FormSelect" name="p[membersAreLeader]"><option value="0">{$modules.Language->getString('Negative')}</option><option value="1">{$modules.Language->getString('Positive')}</option></select></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input class="FormBButton" type="submit" value="{$modules.Language->getString('Add_members')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
</table>
</form>