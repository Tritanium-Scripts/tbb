<!-- AdminForumSpecialRights -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=edit_forum_rights&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('user_group')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('access_forum')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('post_topics')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('post_replies')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('post_polls')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('edit_own_posts')}</span></th>
  <th class="thsmall"><span class="thsmall">{Language::getInstance()->getString('edit_own_polls')}</span></th>
  <th class="thsmall"></th>
 </tr>
 <tr><td class="kat" colspan="8"><span class="kat">{Language::getInstance()->getString('special_user_rights')}</span></td></tr>
{* 0:rightID - 1:rightType - 2:user/groupID - 3:isAccessForum - 4:isPostTopics - 5:isPostReplies - 6:isPostPolls - 7:isEditOwnPosts - 8:isEditOwnPolls *}
{foreach $specialUserRights as $curRight}
 <input type="hidden" name="new_rights[{$curRight[0]}][type]" value="{$curRight[1]}" />
 <input type="hidden" name="new_rights[{$curRight[0]}][target]" value="{$curRight[2]}" />
 <tr>
  <td class="td1" style="text-align:center;"><span class="small">{$curRight['idName']}</span></td>
  <td class="td1" style="text-align:center;"><span class="small"><input type="checkbox" value="1" name="new_rights[{$curRight[0]}][0]"{if $curRight[3]} checked="checked"{/if} /></span></td>
  <td class="td1" style="text-align:center;"><span class="small"><input type="checkbox" value="1" name="new_rights[{$curRight[0]}][1]"{if $curRight[4]} checked="checked"{/if} /></span></td>
  <td class="td1" style="text-align:center;"><span class="small"><input type="checkbox" value="1" name="new_rights[{$curRight[0]}][2]"{if $curRight[5]} checked="checked"{/if} /></span></td>
  <td class="td1" style="text-align:center;"><span class="small"><input type="checkbox" value="1" name="new_rights[{$curRight[0]}][3]"{if $curRight[6]} checked="checked"{/if} /></span></td>
  <td class="td1" style="text-align:center;"><span class="small"><input type="checkbox" value="1" name="new_rights[{$curRight[0]}][4]"{if $curRight[7]} checked="checked"{/if} /></span></td>
  <td class="td1" style="text-align:center;"><span class="small"><input type="checkbox" value="1" name="new_rights[{$curRight[0]}][5]"{if $curRight[8]} checked="checked"{/if} /></span></td>
  <td class="td1" style="text-align:center;"><span class="small"><a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=kill_right&amp;forum_id={$forumID}&amp;right_id={$curRight[0]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('delete')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="8" style="text-align:center;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('no_special_user_rights')}</span></td></tr>
{/foreach}
 <tr><td class="kat" colspan="8"><span class="kat">{Language::getInstance()->getString('special_group_rights')}</span></td></tr>
{foreach $specialGroupRights as $curRight}
 <input type="hidden" name="new_rights[{$curRight[0]}][type]" value="{$curRight[1]}" />
 <input type="hidden" name="new_rights[{$curRight[0]}][target]" value="{$curRight[2]}" />
 <tr>
  <td class="td1" style="text-align:center;"><span class="small">{$curRight['idName']}</span></td>
  <td class="td1" style="text-align:center;"><span class="small"><input type="checkbox" value="1" name="new_rights[{$curRight[0]}][0]"{if $curRight[3]} checked="checked"{/if} /></span></td>
  <td class="td1" style="text-align:center;"><span class="small"><input type="checkbox" value="1" name="new_rights[{$curRight[0]}][1]"{if $curRight[4]} checked="checked"{/if} /></span></td>
  <td class="td1" style="text-align:center;"><span class="small"><input type="checkbox" value="1" name="new_rights[{$curRight[0]}][2]"{if $curRight[5]} checked="checked"{/if} /></span></td>
  <td class="td1" style="text-align:center;"><span class="small"><input type="checkbox" value="1" name="new_rights[{$curRight[0]}][3]"{if $curRight[6]} checked="checked"{/if} /></span></td>
  <td class="td1" style="text-align:center;"><span class="small"><input type="checkbox" value="1" name="new_rights[{$curRight[0]}][4]"{if $curRight[7]} checked="checked"{/if} /></span></td>
  <td class="td1" style="text-align:center;"><span class="small"><input type="checkbox" value="1" name="new_rights[{$curRight[0]}][5]"{if $curRight[8]} checked="checked"{/if} /></span></td>
  <td class="td1" style="text-align:center;"><span class="small"><a class="small" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=kill_right&amp;forum_id={$forumID}&amp;right_id={$curRight[0]}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('delete')}</a></span></td>
 </tr>
{foreachelse}
 <tr><td class="td1" colspan="8" style="text-align:center;"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('no_special_group_rights')}</span></td></tr>
{/foreach}
</table>
<p class="norm"><a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=new_user_right&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_special_user_right')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=new_group_right&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('add_new_special_group_right')}</a></p>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('edit_special_rights')}" /></p>
<input type="hidden" name="change" value="yes" />
</form>