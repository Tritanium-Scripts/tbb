<!-- AdminForumNewGroupRight -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=new_group_right&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
 <tr><th colspan="2" class="thnorm"><span class="thnorm">{$modules.Language->getString('add_new_special_group_right')}</span></th></tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('group_colon')}</span></td>
  <td class="td1"><select size="1" name="new_group_id">{foreach $groups as $curGroup}<option value="{$curGroup[0]}">{$curGroup[1]}</option>{/foreach}</select></td>
 </tr>
 <tr>
  <td class="td1" style="vertical-align:top;"><span class="norm" style="font-weight:bold;">{$modules.Language->getString('special_group_rights_colon')}</span></td>
  <td class="td1">
   <input type="checkbox" value="1" id="newRight0" name="new_right[0]"{if $forumRights[0]} checked="checked"{/if} /> <label for="newRight0" class="norm">{$modules.Language->getString('is_allowed_to_access_forum')}</label><br />
   <input type="checkbox" value="1" id="newRight1" name="new_right[1]"{if $forumRights[1]} checked="checked"{/if} /> <label for="newRight1" class="norm">{$modules.Language->getString('is_allowed_to_post_topics')}</label><br />
   <input type="checkbox" value="1" id="newRight2" name="new_right[2]"{if $forumRights[2]} checked="checked"{/if} /> <label for="newRight2" class="norm">{$modules.Language->getString('is_allowed_to_post_replies')}</label><br />
   <input type="checkbox" value="1" id="newRight3" name="new_right[3]"{if $forumRights[3]} checked="checked"{/if} /> <label for="newRight3" class="norm">{$modules.Language->getString('is_allowed_to_post_polls')}</label><br />
   <input type="checkbox" value="1" id="newRight4" name="new_right[4]"{if $forumRights[4]} checked="checked"{/if} /> <label for="newRight4" class="norm">{$modules.Language->getString('is_allowed_to_edit_own_posts')}</label><br />
   <input type="checkbox" value="1" id="newRight5" name="new_right[5]"{if $forumRights[5]} checked="checked"{/if} /> <label for="newRight5" class="norm">{$modules.Language->getString('is_allowed_to_edit_own_polls')}</label>
  </td>
 </tr>
</table>
<p style="text-align:center;"><input type="submit" value="{$modules.Language->getString('add_new_special_group_right')}" /></p>
<input type="hidden" name="add" value="yes" />
</form>