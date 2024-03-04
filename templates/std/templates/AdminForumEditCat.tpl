<!-- AdminForumEditCat -->
{include file='Errors.tpl'}
<form method="post" action="{$smarty.const.INDEXFILE}?faction=ad_forum&amp;mode=chgkg&amp;chgkg=yes{$smarty.const.SID_AMPER}">
<table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
 <tr><th class="thnorm" colspan="2"><span class="thnorm">{Language::getInstance()->getString('edit_category')}</span></th></tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_EDIT_CATEGORY_FORM_START}
 <tr>
  <td class="td1"><span class="norm" style="font-weight:bold;">{Language::getInstance()->getString('name_colon')}</span></td>
  <td class="td1"><input type="text" name="name" value="{$editName}" /></td>
 </tr>
{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_EDIT_CATEGORY_FORM_END}
</table>
<p style="text-align:center;"><input type="submit" value="{Language::getInstance()->getString('edit_category')}" />{plugin_hook hook=PlugIns::HOOK_TPL_ADMIN_FORUM_EDIT_CATEGORY_BUTTONS}</p>
<input type="hidden" name="id" value="{$catID}" />
</form>