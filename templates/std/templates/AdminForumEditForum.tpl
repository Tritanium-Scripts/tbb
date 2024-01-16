<!-- AdminForumEditForum -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=change&amp;change=yes&amp;ad_forum_id={$editID}{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th colspan="2" class="thnorm"><span class="thnorm">{Language::getInstance()->getString('edit_forum')}</span></th></tr>
 <tr><td colspan="2" class="kat"><span class="kat">{Language::getInstance()->getString('general_information')}</span></td></tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('name_colon')}</span></td>
  <td class="td1"><input type="text" name="titel" value="{$editName}" /></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('description_colon')}</span></td>
  <td class="td1"><input type="text" size="50" name="description" value="{$editDescr}" /></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('moderators_colon')}</span></td>
  <td class="td1"><input type="text" size="10" name="mods" value="{','|implode:$editModIDs}" /> <span class="small">{Language::getInstance()->getString('separate_mod_ids_with_comma')}</span></td>
 </tr>
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('category_colon')}</span></td>
  <td class="td1"><select name="kg" size="1">{html_options options=$catTable selected=$editCatID}</select></td>
 </tr>
 <tr><td colspan="2" class="kat"><span class="kat">{Language::getInstance()->getString('general_rights')}</span></td></tr>
 <tr><td colspan="2" class="td1"><input type="checkbox" id="newRights0" name="new_rights[0]" value="1"{if $editRights[0]} checked="checked"{/if} /> <label for="newRights0" class="norm">{Language::getInstance()->getString('members_are_allowed_to_access_forum')}</label></td></tr>
 <tr><td colspan="2" class="td1"><input type="checkbox" id="newRights1" name="new_rights[1]" value="1"{if $editRights[1]} checked="checked"{/if} /> <label for="newRights1" class="norm">{Language::getInstance()->getString('members_are_allowed_to_post_new_topics')}</label></td></tr>
 <tr><td colspan="2" class="td1"><input type="checkbox" id="newRights2" name="new_rights[2]" value="1"{if $editRights[2]} checked="checked"{/if} /> <label for="newRights2" class="norm">{Language::getInstance()->getString('members_are_allowed_to_post_replies')}</label></td></tr>
 <tr><td colspan="2" class="td1"><input type="checkbox" id="newRights3" name="new_rights[3]" value="1"{if $editRights[3]} checked="checked"{/if} /> <label for="newRights3" class="norm">{Language::getInstance()->getString('members_are_allowed_to_post_polls')}</label></td></tr>
 <tr><td colspan="2" class="td1"><input type="checkbox" id="newRights4" name="new_rights[4]" value="1"{if $editRights[4]} checked="checked"{/if} /> <label for="newRights4" class="norm">{Language::getInstance()->getString('members_are_allowed_to_edit_their_posts')}</label></td></tr>
 <tr><td colspan="2" class="td1"><input type="checkbox" id="newRights5" name="new_rights[5]" value="1"{if $editRights[5]} checked="checked"{/if} /> <label for="newRights5" class="norm">{Language::getInstance()->getString('members_are_allowed_to_edit_their_polls')}</label></td></tr>
 <tr><td colspan="2" class="td1"><input type="checkbox" id="newRights6" name="new_rights[6]" value="1"{if $editRights[6]} checked="checked"{/if} /> <label for="newRights6" class="norm">{Language::getInstance()->getString('guests_are_allowed_to_access_forum')}</label></td></tr>
 <tr><td colspan="2" class="td1"><input type="checkbox" id="newRights7" name="new_rights[7]" value="1"{if $editRights[7]} checked="checked"{/if} /> <label for="newRights7" class="norm">{Language::getInstance()->getString('guests_are_allowed_to_post_new_topics')}</label></td></tr>
 <tr><td colspan="2" class="td1"><input type="checkbox" id="newRights8" name="new_rights[8]" value="1"{if $editRights[8]} checked="checked"{/if} /> <label for="newRights8" class="norm">{Language::getInstance()->getString('guests_are_allowed_to_post_replies')}</label></td></tr>
 <tr><td colspan="2" class="td1"><input type="checkbox" id="newRights9" name="new_rights[9]" value="1"{if $editRights[9]} checked="checked"{/if} /> <label for="newRights9" class="norm">{Language::getInstance()->getString('guests_are_allowed_to_post_polls')}</label></td></tr>
 <tr><td colspan="2" class="kat"><span class="kat">{Language::getInstance()->getString('special_rights')}</span></td></tr>
 <tr><td colspan="2" class="td1"><span class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=edit_forum_rights&amp;forum_id={$editID}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit_special_rights')}</a></span></td></tr>
 <tr><td colspan="2" class="kat"><span class="kat">{Language::getInstance()->getString('topic_prefixes')}</span></td></tr>
 <tr><td colspan="2" class="td1"><span class="norm"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=editTopicPrefixes&amp;forum_id={$editID}{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('edit_topic_prefixes')}</a></span></td></tr>
 <tr><td colspan="2" class="kat"><span class="kat">{Language::getInstance()->getString('options')}</span></td></tr>
 <tr><td colspan="2" class="td1"><input type="checkbox" id="isBBCode" name="upbcode" value="1"{if $editOptions[0]} checked="checked"{/if} /> <label for="isBBCode" class="norm">{Language::getInstance()->getString('enable_bbcode')}</label></td></tr>
 <tr><td colspan="2" class="td1"><input type="checkbox" id="isXHTML" name="htmlcode" value="1"{if $editOptions[1]} checked="checked"{/if} /> <label for="isXHTML" class="norm">{Language::getInstance()->getString('enable_xhtml')}</label></td></tr>
 <tr><td colspan="2" class="td1"><input type="checkbox" id="isNotify" name="sm_mods" value="1"{if $editOptions[2]} checked="checked"{/if} /> <label for="isNotify" class="norm">{Language::getInstance()->getString('notify_moderators_about_new_topics')}</label></td></tr>
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('edit_forum')}" />&nbsp;&nbsp;&nbsp;<input type="submit" name="kill" value="{Language::getInstance()->getString('delete_forum')}" /></p>
</form>