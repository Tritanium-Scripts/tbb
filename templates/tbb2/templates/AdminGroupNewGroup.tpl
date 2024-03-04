{include file='AdminMenu.tpl'}
<!-- AdminGroupNewGroup -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_groups&amp;mode=new{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <colgroup>
  <col width="20%" />
  <col width="80%" />
 </colgroup>
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('add_new_group')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_GROUP_NEW_GROUP_FORM_START}
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('name_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="title" value="{$newName}" style="width:250px;" /></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('color_colon')}</span><br /><span class="fontSmall">{Language::getInstance()->getString('color_description')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="color" value="{$newColor}" style="width:250px;" onchange="this.style.color = this.value;" /></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('avatar_colon')}</span><br /><span class="fontSmall">{Language::getInstance()->getString('avatar_description')}</span></td>
  <td class="cellAlt" style="vertical-align:top;"><input class="formText" type="text" name="pic" value="{$newAvatar}" style="width:250px;" /> <span class="fontSmall">{Language::getInstance()->getString('url_or_path')}</span></td>
 </tr>
 <tr>
  <td class="cellStd"><span class="fontNorm">{Language::getInstance()->getString('members_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="group_members" value="{$newUserIDs}" style="width:250px;" /> <span class="fontSmall">{Language::getInstance()->getString('separate_ids_with_comma')}</span></td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_GROUP_NEW_GROUP_FORM_END}
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('add_new_group')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_GROUP_NEW_GROUP_BUTTONS}</p>
<input type="hidden" name="create" value="yes" />
</form>
{include file='AdminMenuTail.tpl'}