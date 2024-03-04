{include file='AdminMenu.tpl'}
<!-- AdminForumEditCat -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=chgkg&amp;chgkg=yes{$smarty.const.SID_AMPER}">
<table class="tableStd" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:100%;">
 <tr><th class="cellTitle" colspan="2"><span class="fontTitle">{Language::getInstance()->getString('edit_category')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_EDIT_CATEGORY_FORM_START}
 <tr>
  <td class="cellStd"><span class="fontNorm" style="font-weight:bold;">{Language::getInstance()->getString('name_colon')}</span></td>
  <td class="cellAlt"><input class="formText" type="text" name="name" value="{$editName}" /></td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_EDIT_CATEGORY_FORM_END}
</table>
<p class="cellButtons"><input class="formBButton" type="submit" value="{Language::getInstance()->getString('edit_category')}" />&nbsp;&nbsp;&nbsp;<input class="formButton" type="reset" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_EDIT_CATEGORY_BUTTONS}</p>
<input type="hidden" name="id" value="{$catID}" />
</form>
{include file='AdminMenuTail.tpl'}