<form method="post" action="{$indexFile}?action=AdminForums&amp;mode=AddUserRight&amp;forumID={$forumID}&amp;doit=1&amp;{$mySID}">
<table class="TableStd" width="100%">
<tr><td class="CellTitle" colspan="2"><span class="FontTitle">{$modules.Language->getString('add_user_right')}</span></td></tr>
<tr>
 <td class="CellStd"><span class="FontNorm">{$modules.Language->getString('users')}:</span></td>
 <td class="CellAlt"><input type="text" class="FormText" name="p[users]" value="{$p.users}" size="30"/></td>
</tr>
<tr>
 <td class="CellStd" valign="top"><span class="FontNorm">{$modules.Language->getString('rights')}:</span></td>
 <td class="CellAlt"><span class="FontNorm">
  <label><input type="checkbox" name="c[authIsModMembers]" value="1"{if $c.authIsMod} checked="checked"{/if}/> {$modules.Language->getString('auth_is_moderator')}</label><br/>
  <label><input type="checkbox" name="c[authViewForumMembers]" value="1"{if $c.authViewForumMembers} checked="checked"{/if}/> {$modules.Language->getString('auth_view_forum')}</label><br/>
  <label><input type="checkbox" name="c[authPostTopicMembers]" value="1"{if $c.authPostTopicMembers} checked="checked"{/if}/> {$modules.Language->getString('auth_post_topic')}</label><br/>
  <label><input type="checkbox" name="c[authPostReplyMembers]" value="1"{if $c.authPostReplyMembers} checked="checked"{/if}/> {$modules.Language->getString('auth_post_reply')}</label><br/>
  <label><input type="checkbox" name="c[authPostPollMembers]" value="1"{if $c.authPostPollMembers} checked="checked"{/if}/> {$modules.Language->getString('auth_post_poll')}</label><br/>
  <label><input type="checkbox" name="c[authEditPostsMembers]" value="1"{if $c.authEditPostsMembers} checked="checked"{/if}/> {$modules.Language->getString('auth_edit_posts')}</label><br/>
 </span></td>
</tr>
<tr><td class="CellButtons" colspan="2" align="center"><input type="submit" class="FormBButton" value="{$modules.Language->getString('add_user_right')}"/>&nbsp;&nbsp;&nbsp;<input type="reset" class="FormButton" value="{$modules.Language->getString('reset')}"/></td></tr>
</table>
</form>
