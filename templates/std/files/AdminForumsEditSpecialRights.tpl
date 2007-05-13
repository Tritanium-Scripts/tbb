<form method="post" action="{$indexFile}?action=AdminForums&amp;mode=EditSpecialRights&amp;forumID={$forumID}&amp;doit=1&amp;{$mySID}">
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td class="CellTitle"><span class="FontTitleSmall">{$modules.Language->getString('Name')}</span></td>
 <td class="CellTitle"><span class="FontTitleSmall">{$modules.Language->getString('Auth_is_moderator')}</span></td>
 <td class="CellTitle"><span class="FontTitleSmall">{$modules.Language->getString('Auth_view_forum')}</span></td>
 <td class="CellTitle"><span class="FontTitleSmall">{$modules.Language->getString('Auth_post_topic')}</span></td>
 <td class="CellTitle"><span class="FontTitleSmall">{$modules.Language->getString('Auth_post_reply')}</span></td>
 <td class="CellTitle"><span class="FontTitleSmall">{$modules.Language->getString('Auth_post_poll')}</span></td>
 <td class="CellTitle"><span class="FontTitleSmall">{$modules.Language->getString('Auth_edit_posts')}</span></td>
 <td class="CellTitle"><span class="FontTitleSmall">&nbsp;</span></td>
</tr>
<tr><td class="CellCat" colspan="8"><span class="FontCat">{$modules.Language->getString('User_rights')}</span></td></tr>
{foreach from=$rightsDataUsers item=curRight}
 <tr>
  <td class="CellStd"><span class="FontSmall">{$curRight.authUserNick}</span><input type="hidden" name="p[rightsData][{$curRight.authID}][authID]" value="{$curRight.authID}"/><input type="hidden" name="p[rightsData][{$curRight.authID}][authType]" value="{$smarty.const.AUTH_TYPE_USER}"/></td>
  <td class="CellStd" align="center"><input type="checkbox" name="p[rightsData][{$curRight.authID}][authIsMod]" value="1"{if $curRight.authIsMod == 1} checked="checked"{/if}/></td>
  <td class="CellStd" align="center"><input type="checkbox" name="p[rightsData][{$curRight.authID}][authViewForum]" value="1"{if $curRight.authViewForum == 1} checked="checked"{/if}/></td>
  <td class="CellStd" align="center"><input type="checkbox" name="p[rightsData][{$curRight.authID}][authPostTopic]" value="1"{if $curRight.authPostTopic == 1} checked="checked"{/if}/></td>
  <td class="CellStd" align="center"><input type="checkbox" name="p[rightsData][{$curRight.authID}][authPostReply]" value="1"{if $curRight.authPostReply == 1} checked="checked"{/if}/></td>
  <td class="CellStd" align="center"><input type="checkbox" name="p[rightsData][{$curRight.authID}][authPostPoll]" value="1"{if $curRight.authPostPoll == 1} checked="checked"{/if}/></td>
  <td class="CellStd" align="center"><input type="checkbox" name="p[rightsData][{$curRight.authID}][authEditPosts]" value="1"{if $curRight.authEditPosts == 1} checked="checked"{/if}/></td>
  <td class="CellStd" align="center"><span class="FontSmall"><a href="{$indexFile}?action=AdminForums&amp;mode=DeleteSpecialRight&amp;forumID={$forumID}&amp;authType={$smarty.const.AUTH_TYPE_USER}&amp;authID={$curRight.authID}&amp;{$mySID}">{$modules.Language->getString('delete')}</a></span></td>
 </tr>
{/foreach}
<tr><td class="CellCat" colspan="8"><span class="FontCat">{$modules.Language->getString('Group_rights')}</span></td></tr>
{foreach from=$rightsDataGroups item=curRight}
 <tr>
  <td class="CellStd"><span class="FontSmall">{$curRight.authGroupName}</span><input type="hidden" name="p[rightsData][{$curRight.authID}][authID]" value="{$curRight.authID}"/><input type="hidden" name="p[rightsData][{$curRight.authID}][authType]" value="{$smarty.const.AUTH_TYPE_GROUP}"/></td>
  <td class="CellStd" align="center"><input type="checkbox" name="p[rightsData][{$curRight.authID}][authIsMod]" value="1"{if $curRight.authIsMod == 1} checked="checked"{/if}/></td>
  <td class="CellStd" align="center"><input type="checkbox" name="p[rightsData][{$curRight.authID}][authViewForum]" value="1"{if $curRight.authViewForum == 1} checked="checked"{/if}/></td>
  <td class="CellStd" align="center"><input type="checkbox" name="p[rightsData][{$curRight.authID}][authPostTopic]" value="1"{if $curRight.authPostTopic == 1} checked="checked"{/if}/></td>
  <td class="CellStd" align="center"><input type="checkbox" name="p[rightsData][{$curRight.authID}][authPostReply]" value="1"{if $curRight.authPostReply == 1} checked="checked"{/if}/></td>
  <td class="CellStd" align="center"><input type="checkbox" name="p[rightsData][{$curRight.authID}][authPostPoll]" value="1"{if $curRight.authPostPoll == 1} checked="checked"{/if}/></td>
  <td class="CellStd" align="center"><input type="checkbox" name="p[rightsData][{$curRight.authID}][authEditPosts]" value="1"{if $curRight.authEditPosts == 1} checked="checked"{/if}/></td>
  <td class="CellStd" align="center"><span class="FontSmall"><a href="{$indexFile}?action=AdminForums&amp;mode=DeleteSpecialRight&amp;forumID={$forumID}&amp;authType={$smarty.const.AUTH_TYPE_GROUP}&amp;authID={$curRight.authID}&amp;{$mySID}">{$modules.Language->getString('delete')}</a></span></td>
 </tr>
{/foreach}
<tr><td colspan="8" class="CellButtons" align="center"><input type="submit" class="FormBButton" value="{$modules.Language->getString('Edit_special_rights')}"/>&nbsp;&nbsp;&nbsp;<input class="FormButton" type="reset" value="{$modules.Language->getString('Reset')}"/></td></tr>
</table>
<br/>
<table class="TableStd" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr><td class="CellTitle"><span class="FontTitle">{$modules.Language->getString('Other_options')}</span></td></tr>
<tr><td class="CellStd"><span class="FontNorm">
 <a href="{$indexFile}?action=AdminForums&amp;mode=AddUserRight&amp;forumID={$forumID}&amp;{$mySID}">{$modules.Language->getString('Add_user_right')}</a><br/>
 <a href="{$indexFile}?action=AdminForums&amp;mode=AddGroupRight&amp;forumID={$forumID}&amp;{$mySID}">{$modules.Language->getString('Add_group_right')}</a>
</span></td></tr>
</table>
</form>
