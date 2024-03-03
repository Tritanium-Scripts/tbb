{include file='AdminMenu.tpl'}
<!-- AdminForumNewUserRight -->
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=new_user_right&amp;forum_id={$forumID}{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th colspan="2" class="cellTitle"><span class="fontTitle">{Language::getInstance()->getString('add_new_special_user_right')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_NEW_USER_RIGHT_FORM_START}
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('user_id_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="new_user_ids" /> <span class="fontSmall">{Language::getInstance()->getString('separate_ids_with_comma')}</span></td>
 </tr>
 <tr>
  <td class="cellStd" style="vertical-align:top;"><span class="fontNorm">{Language::getInstance()->getString('special_user_rights_colon')}</span></td>
  <td class="cellAlt">
   <span class="fontNorm"><input type="checkbox" value="1" id="newRight0" name="new_right[0]"{if $forumRights[0]} checked="checked"{/if} /> <label for="newRight0">{Language::getInstance()->getString('is_allowed_to_access_forum')}</label><br />
   <input type="checkbox" value="1" id="newRight1" name="new_right[1]"{if $forumRights[1]} checked="checked"{/if} /> <label for="newRight1">{Language::getInstance()->getString('is_allowed_to_post_topics')}</label><br />
   <input type="checkbox" value="1" id="newRight2" name="new_right[2]"{if $forumRights[2]} checked="checked"{/if} /> <label for="newRight2">{Language::getInstance()->getString('is_allowed_to_post_replies')}</label><br />
   <input type="checkbox" value="1" id="newRight3" name="new_right[3]"{if $forumRights[3]} checked="checked"{/if} /> <label for="newRight3">{Language::getInstance()->getString('is_allowed_to_post_polls')}</label><br />
   <input type="checkbox" value="1" id="newRight4" name="new_right[4]"{if $forumRights[4]} checked="checked"{/if} /> <label for="newRight4">{Language::getInstance()->getString('is_allowed_to_edit_own_posts')}</label><br />
   <input type="checkbox" value="1" id="newRight5" name="new_right[5]"{if $forumRights[5]} checked="checked"{/if} /> <label for="newRight5">{Language::getInstance()->getString('is_allowed_to_edit_own_polls')}</label></span>
  </td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_NEW_USER_RIGHT_FORM_END}
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('add_new_special_user_right')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_NEW_USER_RIGHT_BUTTONS}</p>
<input type="hidden" name="change" value="yes" />
</form>
{include file='AdminMenuTail.tpl'}