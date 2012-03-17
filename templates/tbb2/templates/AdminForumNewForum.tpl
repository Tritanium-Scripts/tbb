{include file='AdminMenu.tpl'}
<!-- AdminForumNewForum -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=newforum&amp;create=yes{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th colspan="2" class="cellTitle"><span class="fontTitle">{$modules.Language->getString('add_new_forum')}</span></th></tr>
 <tr><td colspan="2" class="cellCat"><span class="fontCat">{$modules.Language->getString('general_information')}</span></td></tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('name_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="titel" value="{$newName}" /></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('description_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" size="50" name="description" value="{$newDescr}" /></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('moderators_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" size="10" name="mods" value="{','|implode:$newModIDs}" /> <span class="fontSmall">{$modules.Language->getString('separate_mod_ids_with_comma')}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{$modules.Language->getString('category_colon')}</span></td>
  <td class="cellAlt"><select class="formSelect" name="kg" size="1">{foreach $catTable as $curCatID => $curCatName}<option value="{$curCatID}"{if $newCatID == $curCatID} selected="selected"{/if}>-- {$curCatName}</option>{/foreach}</select></td>
 </tr>
 <tr><td colspan="2" class="cellCat"><span class="fontCat">{$modules.Language->getString('general_rights')}</span></td></tr>
 <tr>
  <td colspan="2" class="cellStd">
   <span class="fontNorm"><input type="checkbox" id="newRights0" name="new_rights[0]" value="1"{if $newRights[0]} checked="checked"{/if} /> <label for="newRights0">{$modules.Language->getString('members_are_allowed_to_access_forum')}</label><br />
   <input type="checkbox" id="newRights1" name="new_rights[1]" value="1"{if $newRights[1]} checked="checked"{/if} /> <label for="newRights1">{$modules.Language->getString('members_are_allowed_to_post_new_topics')}</label><br />
   <input type="checkbox" id="newRights2" name="new_rights[2]" value="1"{if $newRights[2]} checked="checked"{/if} /> <label for="newRights2">{$modules.Language->getString('members_are_allowed_to_post_replies')}</label><br />
   <input type="checkbox" id="newRights3" name="new_rights[3]" value="1"{if $newRights[3]} checked="checked"{/if} /> <label for="newRights3">{$modules.Language->getString('members_are_allowed_to_post_polls')}</label><br />
   <input type="checkbox" id="newRights4" name="new_rights[4]" value="1"{if $newRights[4]} checked="checked"{/if} /> <label for="newRights4">{$modules.Language->getString('members_are_allowed_to_edit_their_posts')}</label><br />
   <input type="checkbox" id="newRights5" name="new_rights[5]" value="1"{if $newRights[5]} checked="checked"{/if} /> <label for="newRights5">{$modules.Language->getString('members_are_allowed_to_edit_their_polls')}</label><br />
   <input type="checkbox" id="newRights6" name="new_rights[6]" value="1"{if $newRights[6]} checked="checked"{/if} /> <label for="newRights6">{$modules.Language->getString('guests_are_allowed_to_access_forum')}</label><br />
   <input type="checkbox" id="newRights7" name="new_rights[7]" value="1"{if $newRights[7]} checked="checked"{/if} /> <label for="newRights7">{$modules.Language->getString('guests_are_allowed_to_post_new_topics')}</label><br />
   <input type="checkbox" id="newRights8" name="new_rights[8]" value="1"{if $newRights[8]} checked="checked"{/if} /> <label for="newRights8">{$modules.Language->getString('guests_are_allowed_to_post_replies')}</label><br />
   <input type="checkbox" id="newRights9" name="new_rights[9]" value="1"{if $newRights[9]} checked="checked"{/if} /> <label for="newRights9">{$modules.Language->getString('guests_are_allowed_to_post_polls')}</label></span>
  </td>
 </tr>
 <tr><td colspan="2" class="cellCat"><span class="fontCat">{$modules.Language->getString('options')}</span></td></tr>
 <tr>
  <td colspan="2" class="cellStd"><span class="fontNorm">
   <input type="checkbox" id="isBBCode" name="upbcode" value="1"{if $newIsBBCode} checked="checked"{/if} /> <label for="isBBCode">{$modules.Language->getString('enable_bbcode')}</label><br />
   <input type="checkbox" id="isXHTML" name="htmlcode" value="1"{if $newIsXHTML} checked="checked"{/if} /> <label for="isXHTML">{$modules.Language->getString('enable_xhtml')}</label><br />
   <input type="checkbox" id="isNotify" name="sm_mods" value="1"{if $newIsNotify} checked="checked"{/if} /> <label for="isNotify">{$modules.Language->getString('notify_moderators_about_new_topics')}</label></span>
  </td>
 </tr>
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{$modules.Language->getString('add_new_forum')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" /></p>
</form>
{include file='AdminMenuTail.tpl'}